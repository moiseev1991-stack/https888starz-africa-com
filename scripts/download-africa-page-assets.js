/**
 * Stage 3: After fetch-africa-pages, ensure assets referenced by apk/registration/promo-code
 * exist in dist. Fetch from Africa (same path) if missing and save locally.
 * No external image hosts (ibb.co etc.) — all assets local.
 */

const fs = require('fs');
const path = require('path');
const { URL } = require('url');

const PROJECT_ROOT = path.resolve(__dirname, '..');
const OUT_DIR = process.env.STATIC_OUT_DIR || 'dist';
const DIST = path.join(PROJECT_ROOT, OUT_DIR);
const AFRICA_BASE = 'https://888starz-africa.com';
const EGYPT_BASE = (process.env.STATIC_BASE_URL || 'https://888starzeg-egypt.com').replace(/\/$/, '');

const PAGE_SLUGS = ['apk', 'registration', 'promo-code'];
const ASSET_EXT = /\.(css|js|jpg|jpeg|png|gif|webp|svg|ico|woff2?|ttf|eot)(\?|$)/i;

function extractAssetUrls(html, baseOrigin) {
  const urls = new Set();
  const srcRegex = /(?:src|href)=["']([^"']+)["']/gi;
  let m;
  while ((m = srcRegex.exec(html)) !== null) {
    const u = m[1].trim();
    if (!u || u.startsWith('data:') || u.startsWith('#')) continue;
    try {
      const full = new URL(u, baseOrigin).href;
      const pathname = new URL(full).pathname;
      if (ASSET_EXT.test(pathname)) urls.add(pathname);
    } catch (_) {}
  }
  return [...urls];
}

async function fetchAndSave(url) {
  const res = await fetch(url, {
    redirect: 'follow',
    headers: { 'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36' }
  });
  if (!res.ok) return false;
  const buf = await res.arrayBuffer();
  return Buffer.from(buf);
}

async function main() {
  if (!fs.existsSync(DIST)) {
    console.error('dist/ not found.');
    process.exit(1);
  }

  const allPaths = new Set();
  for (const slug of PAGE_SLUGS) {
    const indexPath = path.join(DIST, slug, 'index.html');
    if (!fs.existsSync(indexPath)) continue;
    const html = fs.readFileSync(indexPath, 'utf8');
    const paths = extractAssetUrls(html, EGYPT_BASE);
    paths.forEach(p => allPaths.add(p));
  }

  let saved = 0;
  const toFetch = [...allPaths].filter(assetPath => !fs.existsSync(path.join(DIST, assetPath.replace(/^\//, ''))));
  for (const assetPath of toFetch) {
    try {
      const buf = await fetchAndSave(AFRICA_BASE + assetPath);
      if (buf && buf.length) {
        const localPath = path.join(DIST, assetPath.replace(/^\//, ''));
        const dir = path.dirname(localPath);
        if (!fs.existsSync(dir)) fs.mkdirSync(dir, { recursive: true });
        fs.writeFileSync(localPath, buf);
        saved++;
        console.log('Saved:', assetPath);
      }
    } catch (_) {}
  }
  console.log('Stage 3: downloaded', saved, 'missing assets from Africa.');
}

main().catch(err => { console.error(err); process.exit(1); });
