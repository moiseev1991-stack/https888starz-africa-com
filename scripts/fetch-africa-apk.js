/**
 * Fetch full APK page from 888starz-africa.com and save to dist/apk/index.html
 * with domain replaced to Egypt. So Egypt /apk/ is an exact copy of Africa /apk/.
 */

const fs = require('fs');
const path = require('path');

const PROJECT_ROOT = path.resolve(__dirname, '..');
const OUT_DIR = process.env.STATIC_OUT_DIR || 'dist';
const DIST = path.join(PROJECT_ROOT, OUT_DIR);
const AFRICA_APK_URL = 'https://888starz-africa.com/apk/';
const EGYPT_BASE = (process.env.STATIC_BASE_URL || 'https://888starzeg-egypt.com').replace(/\/$/, '');

async function fetchUrl(url) {
  const res = await fetch(url, {
    redirect: 'follow',
    headers: { 'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36' }
  });
  if (!res.ok) throw new Error(`HTTP ${res.status} ${url}`);
  return res.text();
}

async function main() {
  if (!fs.existsSync(DIST)) {
    console.error('dist/ not found. Run export first.');
    process.exit(1);
  }
  console.log('Fetching', AFRICA_APK_URL, '...');
  try {
    const html = await fetchUrl(AFRICA_APK_URL);
    let out = html.replace(/https?:\/\/888starz-africa\.com/gi, EGYPT_BASE);
    const apkDir = path.join(DIST, 'apk');
    if (!fs.existsSync(apkDir)) fs.mkdirSync(apkDir, { recursive: true });
    fs.writeFileSync(path.join(apkDir, 'index.html'), out, 'utf8');
    console.log('Saved dist/apk/index.html (full page from Africa, domain -> Egypt).');
  } catch (err) {
    console.error('Fetch failed:', err.message);
    process.exit(1);
  }
}

main();
