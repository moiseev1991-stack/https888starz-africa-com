#!/usr/bin/env node
/**
 * Stage 1: Inventory + save HTML to public/
 * Visits each URL on localhost:8080, collects CSS/JS/images/fonts, saves HTML, optional screenshots.
 * Output: public/<path>/index.html, docs/inventory.md
 */

const fs = require('fs');
const path = require('path');
const { chromium } = require('playwright');

const ROOT = path.resolve(__dirname, '..');
const PUBLIC = path.join(ROOT, 'public');
const DOCS = path.join(ROOT, 'docs');
const URLS_FILE = path.join(ROOT, 'STATIC_URLS.txt');
const BASE_URL = process.argv[2] || 'http://localhost:8080';

function getUrls() {
  const text = fs.readFileSync(URLS_FILE, 'utf8');
  return text
    .split(/\r?\n/)
    .map((line) => line.trim())
    .filter((line) => line && !line.startsWith('#'));
}

function pathToDir(urlPath) {
  const clean = urlPath.replace(/^\/+/, '').replace(/\/+$/, '') || 'index';
  return clean === 'index' ? '' : clean;
}

function extractResources(html, baseUrl) {
  const css = [];
  const js = [];
  const images = [];
  const fonts = [];
  const base = baseUrl.replace(/\/$/, '');

  const linkRe = /<link[^>]+href=["']([^"']+)["'][^>]*>/gi;
  let m;
  while ((m = linkRe.exec(html)) !== null) {
    const href = m[1].split('?')[0].trim();
    if (/\.css$/i.test(href)) css.push(href.startsWith('http') ? href : new URL(href, base + '/').href);
    if (m[0].includes('stylesheet')) css.push(href.startsWith('http') ? href : new URL(href, base + '/').href);
  }

  const scriptRe = /<script[^>]+src=["']([^"']+)["']/gi;
  while ((m = scriptRe.exec(html)) !== null) {
    const src = m[1].split('?')[0].trim();
    if (src) js.push(src.startsWith('http') ? src : new URL(src, base + '/').href);
  }

  const imgRe = /<img[^>]+src=["']([^"']+)["']/gi;
  while ((m = imgRe.exec(html)) !== null) {
    const src = m[1].split('?')[0].trim();
    if (src && !src.startsWith('data:')) images.push(src.startsWith('http') ? src : new URL(src, base + '/').href);
  }

  const srcSetRe = /srcset=["']([^"']+)["']/gi;
  while ((m = srcSetRe.exec(html)) !== null) {
    m[1].split(',').forEach((s) => {
      const url = s.trim().split(/\s+/)[0];
      if (url && !url.startsWith('data:')) images.push(url.startsWith('http') ? url : new URL(url, base + '/').href);
    });
  }

  return { css: [...new Set(css)], js: [...new Set(js)], images: [...new Set(images)], fonts: [...new Set(fonts)] };
}

async function main() {
  const urls = getUrls();
  if (!urls.length) {
    console.error('No URLs in STATIC_URLS.txt');
    process.exit(1);
  }

  fs.mkdirSync(PUBLIC, { recursive: true });
  fs.mkdirSync(DOCS, { recursive: true });

  const inventory = [];
  const browser = await chromium.launch({ headless: true });
  const context = await browser.newContext({ userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36' });

  for (const urlPath of urls) {
    const fullUrl = BASE_URL.replace(/\/$/, '') + (urlPath.startsWith('/') ? urlPath : '/' + urlPath);
    const dir = pathToDir(urlPath);
    const outDir = path.join(PUBLIC, dir || '.');
    const slug = dir || 'index';

    try {
      const page = await context.newPage();
      await page.goto(fullUrl, { waitUntil: 'networkidle', timeout: 30000 });
      await page.waitForTimeout(1500);

      let html = await page.content();
      html = html.replace(new RegExp(BASE_URL.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'g'), '');

      const resources = extractResources(html, BASE_URL);
      inventory.push({ url: urlPath || '/', slug, css: resources.css, js: resources.js, images: resources.images, fonts: resources.fonts });

      fs.mkdirSync(outDir, { recursive: true });
      fs.writeFileSync(path.join(outDir, 'index.html'), html, 'utf8');
      console.log('OK', urlPath || '/', '->', path.join(outDir, 'index.html'));

      await page.close();
    } catch (err) {
      console.error('FAIL', fullUrl, err.message);
      inventory.push({ url: urlPath || '/', slug, error: err.message });
    }
  }

  await browser.close();

  const md = [
    '# Inventory (Stage 1)',
    '',
    '| URL | CSS | JS | Images | Fonts | Notes |',
    '|-----|-----|-----|--------|-------|-------|',
    ...inventory.map((i) => {
      if (i.error) return `| ${i.url} | - | - | - | - | ${i.error} |`;
      const css = i.css.length;
      const js = i.js.length;
      const img = i.images.length;
      const fonts = i.fonts.length;
      return `| ${i.url} | ${css} | ${js} | ${img} | ${fonts} | |`;
    }),
    '',
    '## All unique CSS',
    ...[].concat(...inventory.filter((i) => i.css).map((i) => i.css)).filter((v, i, a) => a.indexOf(v) === i).map((u) => `- ${u}`),
    '',
    '## All unique JS',
    ...[].concat(...inventory.filter((i) => i.js).map((i) => i.js)).filter((v, i, a) => a.indexOf(v) === i).map((u) => `- ${u}`),
  ].join('\n');

  fs.writeFileSync(path.join(DOCS, 'inventory.md'), md, 'utf8');
  console.log('Wrote docs/inventory.md');
}

main().catch(console.error);
