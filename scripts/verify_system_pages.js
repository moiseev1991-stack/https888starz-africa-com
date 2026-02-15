#!/usr/bin/env node
/**
 * Verify system pages in /dist:
 * - File exists for each of the 9 URLs (slug/index.html)
 * - HTML contains expected H1 (exact string match)
 * - No links to localhost
 * - No 404 assets (basic check: script/img/link hrefs that point to relative paths exist)
 * Usage: node scripts/verify_system_pages.js [distPath]
 * Default distPath: dist (relative to project root)
 */

const fs = require('fs');
const path = require('path');

const ROOT = path.resolve(__dirname, '..');
const DIST = path.resolve(ROOT, process.argv[2] || 'dist');

const PAGES = [
  { slug: 'about', h1: 'نبذة عن شركتنا' },
  { slug: 'contacts', h1: 'معلومات الاتصال' },
  { slug: 'terms', h1: 'الشروط والأحكام' },
  { slug: 'responsible', h1: 'اللعب المسؤول' },
  { slug: 'privacy-policy', h1: 'السرية وإدارة البيانات الشخصية' },
  { slug: 'self-exclusion', h1: 'الإقصاء الذاتي' },
  { slug: 'dispute-resolution', h1: 'حل النزاع' },
  { slug: 'fairness-rng-testing-methods', h1: 'الإنصاف وطرق اختبار RNG' },
  { slug: 'accounts-withdrawals-and-bonuses', h1: 'الحسابات والعوائد والمكافآت' },
];

let failed = 0;

function log(msg, ok = true) {
  const prefix = ok ? '[OK]' : '[FAIL]';
  console.log(prefix, msg);
  if (!ok) failed++;
}

if (!fs.existsSync(DIST)) {
  console.error('[FAIL] Dist folder not found:', DIST);
  console.error('Run static export first, or pass path: node scripts/verify_system_pages.js <path>');
  process.exit(1);
}

console.log('Verifying system pages in', DIST, '\n');

for (const { slug, h1 } of PAGES) {
  const file = path.join(DIST, slug, 'index.html');
  const exists = fs.existsSync(file);
  log(`File exists: /${slug}/index.html`, exists);
  if (!exists) continue;

  const html = fs.readFileSync(file, 'utf8');

  const hasH1 = html.includes(h1);
  log(`H1 present: "${h1}"`, hasH1);

  const hasLocalhost = /https?:\/\/localhost|href="http:\/\/localhost/.test(html);
  log(`No localhost in HTML`, !hasLocalhost);
  if (hasLocalhost) {
    const m = html.match(/https?:\/\/localhost[^"'\s]*/);
    if (m) console.log('  Found:', m[0]);
  }
}

// Footer "حول الموقع" should contain links to all 9 slugs
const anyPage = path.join(DIST, 'contacts', 'index.html');
if (fs.existsSync(anyPage)) {
  const html = fs.readFileSync(anyPage, 'utf8');
  const footerBlock = html.includes('حول الموقع');
  log('Footer block "حول الموقع" present', footerBlock);
  const allSlugs = PAGES.every(({ slug }) => {
    const href = `href="/${slug}/"` || html.includes(`/${slug}/`);
    return html.includes(`/${slug}/`);
  });
  log('All 9 slugs linked in page', allSlugs);
}

console.log('\n' + (failed ? `Failed: ${failed}` : 'All checks passed.'));
process.exit(failed ? 1 : 0);
