/**
 * After fetch-africa-pages: on dist/apk/index.html only,
 * replace the owl-carousel block with lite-slider markup and inject lite-slider.css + .js.
 * Copies static-assets/lite-slider to dist/assets/lite-slider.
 */

const fs = require('fs');
const path = require('path');

const PROJECT_ROOT = path.resolve(__dirname, '..');
const OUT_DIR = process.env.STATIC_OUT_DIR || 'dist';
const DIST = path.join(PROJECT_ROOT, OUT_DIR);
const APK_INDEX = path.join(DIST, 'apk', 'index.html');
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

function findOwlBlockStart(html) {
  const re = /<div\s+[^>]*class=["'][^"']*owl-carousel[^"']*["'][^>]*>/i;
  const m = html.match(re);
  return m ? html.indexOf(m[0]) : -1;
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

function main() {
  copyAssets();
  if (!fs.existsSync(APK_INDEX)) {
    console.log('apk/index.html not found, skip lite-slider inject.');
    return;
  }
  let html = fs.readFileSync(APK_INDEX, 'utf8');
  const start = findOwlBlockStart(html);
  if (start === -1) {
    console.log('No owl-carousel block found in apk/index.html, skip.');
    return;
  }
  const end = findMatchingEnd(html, start);
  if (end === -1) {
    console.log('Could not find end of owl-carousel block, skip.');
    return;
  }
  const block = html.slice(start, end);
  const imgs = extractImagesFromBlock(block);
  if (imgs.length === 0) {
    console.log('No images in owl-carousel block, skip.');
    return;
  }
  const newBlock = buildLiteSliderHtml(imgs);
  html = html.slice(0, start) + newBlock + html.slice(end);

  if (!html.includes('lite-slider.css')) {
    html = html.replace('</head>', '<link rel="stylesheet" href="/assets/lite-slider/lite-slider.css">\n</head>');
  }
  if (!html.includes('lite-slider.js')) {
    html = html.replace('</body>', '<script src="/assets/lite-slider/lite-slider.js" defer></script>\n</body>');
  }
  fs.writeFileSync(APK_INDEX, html, 'utf8');
  console.log('Replaced owl-carousel with lite-slider on apk/index.html,', imgs.length, 'slides.');
}

main();
