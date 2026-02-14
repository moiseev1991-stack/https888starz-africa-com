/**
 * Fetch full HTML for key pages from 888starz-africa.com and save to dist/
 * with domain replaced to Egypt. Ensures 1:1 clone of apk, registration, promo-code
 * and footer pages (contacts, terms, about, etc.) on Egypt.
 */

const fs = require('fs');
const path = require('path');

const PROJECT_ROOT = path.resolve(__dirname, '..');
const OUT_DIR = process.env.STATIC_OUT_DIR || 'dist';
const DIST = path.join(PROJECT_ROOT, OUT_DIR);
const AFRICA_BASE = 'https://888starz-africa.com';
const EGYPT_BASE = (process.env.STATIC_BASE_URL || 'https://888starzeg-egypt.com').replace(/\/$/, '');

const CLONE_SLUGS = ['apk', 'registration', 'promo-code'];
const FOOTER_SLUGS = [
  'contacts', 'dispute-resolution', 'responsible', 'terms', 'about',
  'privacy-policy', 'self-exclusion', 'accounts-withdrawals-and-bonuses', 'fairness-rng-testing-methods'
];
const ALL_SLUGS = [...CLONE_SLUGS, ...FOOTER_SLUGS];

async function fetchUrl(url) {
  const res = await fetch(url, {
    redirect: 'follow',
    headers: { 'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36' }
  });
  if (!res.ok) return { ok: false, status: res.status, html: null };
  const html = await res.text();
  return { ok: true, status: res.status, html };
}

function replaceDomain(html) {
  return html.replace(/https?:\/\/888starz-africa\.com/gi, EGYPT_BASE);
}

async function main() {
  if (!fs.existsSync(DIST)) {
    console.error('dist/ not found. Run export first.');
    process.exit(1);
  }

  for (const slug of ALL_SLUGS) {
    const url = `${AFRICA_BASE}/${slug}/`;
    try {
      const { ok, status, html } = await fetchUrl(url);
      if (!ok || !html) {
        console.log(`Skip ${slug}: HTTP ${status}`);
        continue;
      }
      const out = replaceDomain(html);
      const dir = path.join(DIST, slug);
      if (!fs.existsSync(dir)) fs.mkdirSync(dir, { recursive: true });
      fs.writeFileSync(path.join(dir, 'index.html'), out, 'utf8');
      console.log('Saved:', slug + '/index.html');
    } catch (err) {
      console.error('Fetch', slug, err.message);
    }
  }
}

main();
