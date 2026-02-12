/**
 * Verify system pages in /dist:
 * - file exists for each of the 9 slugs
 * - HTML contains expected H1 (exact string)
 * - no localhost references in HTML
 * - no 404 assets (optional: check relative css/js/img)
 */

const fs = require('fs');
const path = require('path');

const PROJECT_ROOT = path.resolve(__dirname, '..');
const DIST = path.join(PROJECT_ROOT, 'dist');

const SYSTEM_PAGES = [
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

const LOCALHOST_RE = /https?:\/\/(localhost|127\.0\.0\.1)(:\d+)?/gi;

function main() {
  let failed = 0;
  const errors = [];

  if (!fs.existsSync(DIST)) {
    console.error('dist/ not found. Run build first (e.g. npm run build:live).');
    process.exit(1);
  }

  for (const { slug, h1 } of SYSTEM_PAGES) {
    const filePath = path.join(DIST, slug, 'index.html');
    const relPath = slug + '/index.html';

    if (!fs.existsSync(filePath)) {
      errors.push(`${relPath}: file missing`);
      failed++;
      continue;
    }

    const html = fs.readFileSync(filePath, 'utf8');

    if (!html.includes(h1)) {
      errors.push(`${relPath}: expected H1 "${h1}" not found`);
      failed++;
    }

    const localhostMatch = html.match(LOCALHOST_RE);
    if (localhostMatch && localhostMatch.length > 0) {
      const unique = [...new Set(localhostMatch)];
      errors.push(`${relPath}: contains localhost references: ${unique.join(', ')}`);
      failed++;
    }
  }

  if (failed > 0) {
    console.error('Verify system pages: FAILED\n');
    errors.forEach(e => console.error('  -', e));
    process.exit(1);
  }

  console.log('Verify system pages: OK — 9 files present, H1 and no localhost.');
}

main();
