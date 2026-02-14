/**
 * After fetch-africa-pages: on dist/apk, registration, promo-code
 * replace ALL owl-carousel and owl-mobile blocks with Carousel Lite (lite-slider) markup
 * and inject lite-slider.css + .js. So sliders work everywhere without jQuery/Owl.
 */

const fs = require('fs');
const path = require('path');

const PROJECT_ROOT = path.resolve(__dirname, '..');
const OUT_DIR = process.env.STATIC_OUT_DIR || 'dist';
const DIST = path.join(PROJECT_ROOT, OUT_DIR);
const SLIDER_PAGES = ['apk', 'registration', 'promo-code'];
const STATIC_ASSETS = path.join(__dirname, 'static-assets', 'lite-slider');
const DIST_ASSETS = path.join(DIST, 'assets', 'lite-slider');

function copyAssets() {
  if (!fs.existsSync(STATIC_ASSETS)) return;
  if (!fs.existsSync(DIST)) return;
  if (!fs.existsSync(path.join(DIST, 'assets'))) fs.mkdirSync(path.join(DIST, 'assets'), { recursive: true });
  if (!fs.existsSync(DIST_ASSETS)) fs.mkdirSync(DIST_ASSETS, { recursive: true });
  ['lite-slider.js', 'lite-slider.css'].forEach(name => {
    const src = path.join(STATIC_ASSETS, name);
    const dest = path.join(DIST_ASSETS, name);
    if (fs.existsSync(src)) {
      fs.copyFileSync(src, dest);
      console.log('Copied', name, 'to dist/assets/lite-slider/');
    }
  });
}

function findOwlBlockStart(html, fromIndex) {
  const re = /<div\s+[^>]*class=["'][^"']*owl-(?:carousel|mobile)[^"']*["'][^>]*>/i;
  const idx = fromIndex || 0;
  const slice = html.slice(idx);
  const m = slice.match(re);
  return m ? idx + slice.indexOf(m[0]) : -1;
}

function findMatchingEnd(html, start) {
  let depth = 1;
  let i = start + 1;
  const len = html.length;
  while (i < len) {
    const nextDiv = html.indexOf('<div', i);
    const nextClose = html.indexOf('</div>', i);
    if (nextClose === -1) break;
    if (nextDiv !== -1 && nextDiv < nextClose) {
      depth++;
      i = nextDiv + 4;
    } else {
      depth--;
      i = nextClose + 6;
      if (depth === 0) return nextClose + 6;
    }
  }
  return -1;
}

function extractImagesFromBlock(blockHtml) {
  const imgs = [];
  const imgRe = /<img\s+([^>]*)>/gi;
  let m;
  while ((m = imgRe.exec(blockHtml)) !== null) {
    const attrs = m[1];
    const src = attrs.match(/src=["']([^"']+)["']/i);
    const alt = attrs.match(/alt=["']([^"']*)["']/i);
    if (src) imgs.push({ src: src[1], alt: (alt && alt[1]) ? alt[1] : 'Slide' });
  }
  return imgs;
}

function buildLiteSliderHtml(imgs) {
  const slides = imgs.map((img, i) => {
    const isFirst = i === 0;
    const loading = isFirst ? 'eager' : 'lazy';
    const fp = isFirst ? ' fetchpriority="high"' : '';
    return `<figure class="lite-slider__slide"><img src="${escapeHtml(img.src)}" width="800" height="600" alt="${escapeHtml(img.alt)}" loading="${loading}" decoding="async"${fp}></figure>`;
  }).join('\n      ');
  return `<div class="lite-slider" data-lite-slider>
  <div class="lite-slider__viewport">
    <div class="lite-slider__track">
      ${slides}
    </div>
  </div>
  <div class="lite-slider__dots" role="tablist" aria-label="Скриншоты приложения"></div>
</div>`;
}

function escapeHtml(s) {
  return String(s)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

function processPage(indexPath) {
  if (!fs.existsSync(indexPath)) return 0;
  let html = fs.readFileSync(indexPath, 'utf8');
  let replaced = 0;
  let fromIndex = 0;
  for (;;) {
    const start = findOwlBlockStart(html, fromIndex);
    if (start === -1) break;
    const end = findMatchingEnd(html, start);
    if (end === -1) break;
    const block = html.slice(start, end);
    const imgs = extractImagesFromBlock(block);
    if (imgs.length === 0) {
      fromIndex = end;
      continue;
    }
    const newBlock = buildLiteSliderHtml(imgs);
    html = html.slice(0, start) + newBlock + html.slice(end);
    replaced++;
    fromIndex = start + newBlock.length;
  }

  if (replaced > 0) {
    if (!html.includes('lite-slider.css')) {
      html = html.replace('</head>', '<link rel="stylesheet" href="/assets/lite-slider/lite-slider.css">\n</head>');
    }
    if (!html.includes('lite-slider.js')) {
      html = html.replace('</body>', '<script src="/assets/lite-slider/lite-slider.js" defer></script>\n</body>');
    }
    fs.writeFileSync(indexPath, html, 'utf8');
  }
  return replaced;
}

function main() {
  copyAssets();
  let total = 0;
  for (const slug of SLIDER_PAGES) {
    const indexPath = path.join(DIST, slug, 'index.html');
    const n = processPage(indexPath);
    if (n > 0) {
      console.log('Replaced', n, 'owl block(s) with Carousel Lite on', slug + '/index.html');
      total += n;
    }
  }
  if (total === 0) {
    console.log('No owl-carousel/owl-mobile blocks with images found on apk/registration/promo-code, skip.');
  }
}

main();
