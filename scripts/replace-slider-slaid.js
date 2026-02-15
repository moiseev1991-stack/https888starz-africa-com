/**
 * После fetch-africa-pages: заменить блок слайдера (owl-carousel с .slide) на слайды из slaid (1.webp–10.webp).
 * Страницы: index, apk, registration, promo-code.
 */

const fs = require('fs');
const path = require('path');

const PROJECT_ROOT = path.resolve(__dirname, '..');
const OUT_DIR = process.env.STATIC_OUT_DIR || 'dist';
const DIST = path.join(PROJECT_ROOT, OUT_DIR);

const SLIDE_COUNT = 10;
const ASSET_PATH = 'wp-content/uploads/slaid/';

function getPrefix(pagePath) {
  if (pagePath === 'index.html') return './';
  return '../';
}

function buildSlidesHtml(prefix) {
  const parts = [];
  for (let i = 1; i <= SLIDE_COUNT; i++) {
    const src = prefix + ASSET_PATH + i + '.webp';
    const alt = 'Slide ' + i;
    parts.push(
      '<div class="slide">' +
        '<a href="' + src + '" data-fancybox="gallery">' +
          '<img decoding="async" src="' + src + '" alt="' + alt + '" title="' + alt + '" width="1200" height="675" loading="' + (i === 1 ? 'eager' : 'lazy') + '">' +
        '</a>' +
      '</div>'
    );
  }
  return parts.join('\n          ');
}

function replaceSliderInHtml(html, pagePath) {
  const prefix = getPrefix(pagePath);
  const marker = '<div class="owl-carousel owl-theme hero-slider"';
  const owlStart = html.indexOf(marker);
  if (owlStart === -1) return html;
  let depth = 0;
  let pos = owlStart;
  let endOfCarousel = -1;
  while (pos < html.length) {
    const nextOpen = html.indexOf('<div', pos);
    const nextClose = html.indexOf('</div>', pos);
    if (nextClose === -1) break;
    if (nextOpen !== -1 && nextOpen < nextClose) {
      depth++;
      pos = nextOpen + 4;
    } else {
      depth--;
      pos = nextClose + 6;
      if (depth === 0) {
        endOfCarousel = pos;
        break;
      }
    }
  }
  if (endOfCarousel === -1) return html;
  const newSlides = buildSlidesHtml(prefix);
  const newCarousel = marker + ' dir="ltr">\n          ' + newSlides + '\n     </div>';
  return html.slice(0, owlStart) + newCarousel + html.slice(endOfCarousel);
}

function main() {
  if (!fs.existsSync(DIST)) {
    console.error('dist/ not found.');
    process.exit(1);
  }

  const pages = ['index.html', 'apk/index.html', 'registration/index.html', 'promo-code/index.html'];
  for (const pagePath of pages) {
    const fullPath = path.join(DIST, pagePath);
    if (!fs.existsSync(fullPath)) continue;
    let html = fs.readFileSync(fullPath, 'utf8');
    if (html.indexOf('owl-carousel owl-theme hero-slider') === -1) continue;
    const updated = replaceSliderInHtml(html, pagePath);
    if (updated !== html) {
      fs.writeFileSync(fullPath, updated, 'utf8');
      console.log('Replaced slider with slaid on', pagePath);
    }
  }
  console.log('Done.');
}

main();
