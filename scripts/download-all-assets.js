/**
 * Скачивает с сайта все ресурсы (CSS, JS, изображения, шрифты), на которые ссылаются HTML в dist.
 * Делает статику полностью автономной — без WordPress и без обращений к живому сайту.
 * Запускать после export-crawler.js (после того как HTML уже в dist).
 */

const fs = require('fs');
const path = require('path');
const https = require('https');
const http = require('http');
const { URL } = require('url');

const PROJECT_ROOT = path.resolve(__dirname, '..');
const BASE_URL = (process.env.STATIC_BASE_URL || 'https://888starz-africa.com').replace(/\/$/, '');
const OUT_DIR = process.env.STATIC_OUT_DIR || 'dist';
const DIST = path.join(PROJECT_ROOT, OUT_DIR);

const CONCURRENCY = 2;
const RETRIES = 2;
const DELAY_MS = 200;

function getOrigin(url) {
  try { return new URL(url).origin; } catch { return null; }
}

function pathnameFromUrl(urlStr) {
  try {
    const u = new URL(urlStr);
    let p = u.pathname || '/';
    if (p.startsWith('//')) p = p.slice(1);
    return p.replace(/\?.*$/, '').replace(/#.*$/, '') || '/';
  } catch { return null; }
}

function delay(ms) {
  return new Promise((r) => setTimeout(r, ms));
}

function fetchBuffer(urlStr) {
  return new Promise((resolve, reject) => {
    const u = new URL(urlStr);
    const mod = u.protocol === 'https:' ? https : http;
    const req = mod.get(urlStr, { headers: { 'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0' }, timeout: 20000 }, (res) => {
      const redirect = res.statusCode >= 300 && res.statusCode < 400 && res.headers.location;
      if (redirect) return fetchBuffer(new URL(redirect, urlStr).href).then(resolve).catch(reject);
      const chunks = [];
      res.on('data', (c) => chunks.push(c));
      res.on('end', () => resolve({ buffer: Buffer.concat(chunks), contentType: res.headers['content-type'] || '' }));
    });
    req.on('error', reject);
    req.on('timeout', () => { req.destroy(); reject(new Error('timeout')); });
  });
}

async function fetchWithRetry(urlStr) {
  let lastErr;
  for (let i = 0; i <= RETRIES; i++) {
    try {
      return await fetchBuffer(urlStr);
    } catch (e) {
      lastErr = e;
      if (i < RETRIES) await delay(DELAY_MS * (i + 1));
    }
  }
  throw lastErr;
}

function extractResourceUrls(html, pageOrigin) {
  const urls = new Set();
  const reHref = /<link[^>]+href\s*=\s*["']([^"']+)["'][^>]*>/gi;
  const reSrc = /<script[^>]+src\s*=\s*["']([^"']+)["']/gi;
  const reImg = /<img[^>]+src\s*=\s*["']([^"']+)["']/gi;
  const reSource = /<source[^>]+src\s*=\s*["']([^"']+)["']/gi;
  let m;
  while ((m = reHref.exec(html)) !== null) {
    const href = m[1].trim();
    if (/\.(css|woff2?|ttf|eot)(\?|$)/i.test(href) || href.includes('stylesheet')) urls.add(href);
  }
  while ((m = reSrc.exec(html)) !== null) urls.add(m[1].trim());
  while ((m = reImg.exec(html)) !== null) urls.add(m[1].trim());
  while ((m = reSource.exec(html)) !== null) urls.add(m[1].trim());
  const origin = getOrigin(pageOrigin);
  return [...urls].filter((u) => {
    try { return getOrigin(new URL(u, pageOrigin).href) === origin; } catch { return false; }
  }).map((u) => new URL(u, pageOrigin).href);
}

function extractCssUrls(cssText, cssBaseUrl) {
  const urls = [];
  const re = /url\s*\(\s*["']?([^"')]+)["']?\s*\)/g;
  let m;
  while ((m = re.exec(cssText)) !== null) {
    const raw = m[1].trim();
    if (/^(data:|#)/.test(raw)) continue;
    try { urls.push(new URL(raw, cssBaseUrl).href); } catch (_) {}
  }
  return urls;
}

function resolveRelative(cssFilePath, assetFilePath) {
  const cssDir = path.dirname(cssFilePath).replace(/\\/g, '/');
  const rel = path.relative(cssDir, assetFilePath).replace(/\\/g, '/');
  return rel || './';
}

function escapeRe(s) {
  return s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

async function downloadOne(urlStr, savedPaths) {
  const pathname = pathnameFromUrl(urlStr);
  if (!pathname || pathname === '/') return null;
  const localPath = path.join(DIST, pathname.replace(/^\//, ''));
  if (savedPaths.has(pathname)) return null;
  if (fs.existsSync(localPath)) {
    savedPaths.add(pathname);
    return null;
  }
  savedPaths.add(pathname);

  try {
    const { buffer, contentType } = await fetchWithRetry(urlStr);
    const dir = path.dirname(localPath);
    if (!fs.existsSync(dir)) fs.mkdirSync(dir, { recursive: true });
    fs.writeFileSync(localPath, buffer);
    console.log('  asset:', pathname);
    return { pathname, localPath, buffer, contentType, url: urlStr };
  } catch (err) {
    savedPaths.delete(pathname);
    const localFallback = path.join(PROJECT_ROOT, pathname.replace(/^\//, ''));
    if (fs.existsSync(localFallback)) {
      const dir = path.dirname(localPath);
      if (!fs.existsSync(dir)) fs.mkdirSync(dir, { recursive: true });
      fs.copyFileSync(localFallback, localPath);
      console.log('  asset (from local):', pathname);
      return { localPath, contentType: '', url: urlStr };
    }
    console.warn('  skip (failed):', pathname, err.message);
    return null;
  }
}

async function processCss(localPath, cssUrl, savedPaths) {
  if (!fs.existsSync(localPath)) return;
  let css = fs.readFileSync(localPath, 'utf8');
  const urls = extractCssUrls(css, cssUrl);
  const cssPathNorm = path.resolve(localPath);
  for (const u of urls) await downloadOne(u, savedPaths);
  css = fs.readFileSync(localPath, 'utf8');
  const reUrl = /url\s*\(\s*["']?([^"')]+)["']?\s*\)/g;
  let m;
  const replacements = [];
  while ((m = reUrl.exec(css)) !== null) {
    const raw = m[1].trim();
    if (/^(data:|#)/.test(raw)) continue;
    try {
      const absUrl = new URL(raw, cssUrl).href;
      const pathname = pathnameFromUrl(absUrl);
      if (!pathname) continue;
      const localAsset = path.join(DIST, pathname.replace(/^\//, ''));
      if (!fs.existsSync(localAsset)) continue;
      const newRel = resolveRelative(cssPathNorm, localAsset);
      replacements.push({ raw: m[0], from: raw, to: `url("${newRel.replace(/\\/g, '/')}")` });
    } catch (_) {}
  }
  for (const { raw, to } of replacements) css = css.replace(raw, to);
  fs.writeFileSync(localPath, css);
}

async function run() {
  if (!fs.existsSync(DIST)) {
    console.error('dist/ not found. Run export-crawler.js first.');
    process.exit(1);
  }

  const wpContent = path.join(PROJECT_ROOT, 'wp-content');
  const distWp = path.join(DIST, 'wp-content');
  if (fs.existsSync(wpContent)) {
    console.log('Copying local wp-content (uploads, themes) so images and CSS are present...');
    function mergeRecursive(src, dest) {
      if (!fs.existsSync(src)) return;
      fs.readdirSync(src, { withFileTypes: true }).forEach((e) => {
        const s = path.join(src, e.name);
        const d = path.join(dest, e.name);
        if (e.isDirectory()) {
          if (!fs.existsSync(d)) fs.mkdirSync(d, { recursive: true });
          mergeRecursive(s, d);
        } else {
          fs.mkdirSync(path.dirname(d), { recursive: true });
          if (!fs.existsSync(d)) fs.copyFileSync(s, d);
        }
      });
    }
    mergeRecursive(path.join(wpContent, 'uploads'), path.join(distWp, 'uploads'));
    mergeRecursive(path.join(wpContent, 'themes', 'zento'), path.join(distWp, 'themes', 'zento'));
  }

  const baseOrigin = getOrigin(BASE_URL);
  const assetUrls = new Set();

  const assetListPath = path.join(DIST, 'asset-urls.json');
  if (fs.existsSync(assetListPath)) {
    try {
      const list = JSON.parse(fs.readFileSync(assetListPath, 'utf8'));
      list.forEach((u) => assetUrls.add(u));
      console.log('Loaded', list.length, 'URLs from asset-urls.json');
    } catch (_) {}
  }

  function walkDir(dir) {
    const entries = fs.readdirSync(dir, { withFileTypes: true });
    for (const e of entries) {
      const full = path.join(dir, e.name);
      if (e.isDirectory()) walkDir(full);
      else if (e.name.endsWith('.html')) {
        const html = fs.readFileSync(full, 'utf8');
        const relPath = path.relative(DIST, full).replace(/\\/g, '/');
        const pageUrl = BASE_URL + (relPath === 'index.html' ? '/' : '/' + relPath.replace(/\/index\.html$/, '/'));
        extractResourceUrls(html, pageUrl).forEach((u) => assetUrls.add(u));
      }
    }
  }
  console.log('Collecting asset URLs from HTML in dist...');
  walkDir(DIST);
  const toDownload = [...assetUrls].filter((u) => getOrigin(u) === baseOrigin);
  console.log('Found', toDownload.length, 'same-origin resource URLs to download');

  const savedPaths = new Set();
  const skipPaths = /^\/(wp-json|feed|xmlrpc\.php|comments\/feed)/i;
  const list = toDownload.filter((u) => !skipPaths.test(pathnameFromUrl(u) || ''));
  for (let i = 0; i < list.length; i += CONCURRENCY) {
    const batch = list.slice(i, i + CONCURRENCY);
    const results = await Promise.all(batch.map((u) => downloadOne(u, savedPaths)));
    for (const r of results) {
      if (!r) continue;
      const ct = (r.contentType || '').toLowerCase();
      if (ct.includes('text/css')) await processCss(r.localPath, r.url, savedPaths);
    }
    await delay(DELAY_MS);
  }

  console.log('Assets done. Dist is self-contained and works offline.');
}

run().catch((err) => { console.error(err); process.exit(1); });
