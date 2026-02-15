#!/usr/bin/env node
/**
 * Export static HTML for URLs listed in URLS.txt.
 * Usage: node scripts/export-static.js [baseUrl]
 * Example: node scripts/export-static.js http://localhost:8080
 * Requires: npm install playwright && npx playwright install chromium
 * Output: dist/<path>/index.html for each URL. Assets not downloaded (use wget for full mirror).
 */

const fs = require('fs');
const path = require('path');
const { chromium } = require('playwright');

const ROOT = path.resolve(__dirname, '..');
const URLS_FILE = path.join(ROOT, 'URLS.txt');
const DIST = path.join(ROOT, 'dist');
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

async function main() {
  const urls = getUrls();
  if (!urls.length) {
    console.error('No URLs in URLS.txt');
    process.exit(1);
  }

  if (!fs.existsSync(DIST)) fs.mkdirSync(DIST, { recursive: true });

  const browser = await chromium.launch({ headless: true });
  const context = await browser.newContext();
  const page = await context.newPage();

  for (const urlPath of urls) {
    const fullUrl = BASE_URL.replace(/\/$/, '') + (urlPath.startsWith('/') ? urlPath : '/' + urlPath);
    const dir = pathToDir(urlPath);
    const outDir = path.join(DIST, dir || '');
    const outFile = path.join(outDir, 'index.html');

    try {
      await page.goto(fullUrl, { waitUntil: 'networkidle', timeout: 30000 });
      let html = await page.content();
      // Replace base URL with relative so static site works
      html = html.replace(new RegExp(BASE_URL.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'g'), '');
      if (!fs.existsSync(outDir)) fs.mkdirSync(outDir, { recursive: true });
      fs.writeFileSync(outFile, html, 'utf8');
      console.log('OK', urlPath || '/', '->', outFile);
    } catch (err) {
      console.error('FAIL', fullUrl, err.message);
    }
  }

  await browser.close();
  console.log('Done. Run: node scripts/verify_system_pages.js');
}

main();
