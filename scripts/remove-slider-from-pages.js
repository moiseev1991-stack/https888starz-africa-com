/**
 * Удалить блок слайдера (owl-carousel hero-slider / embla) со страниц.
 * Запускать после fetch-africa-pages. Страницы: index, apk, registration, promo-code.
 */

const fs = require('fs');
const path = require('path');

const PROJECT_ROOT = path.resolve(__dirname, '..');
const OUT_DIR = process.env.STATIC_OUT_DIR || 'dist';
const DIST = path.join(PROJECT_ROOT, OUT_DIR);

const PAGES = [
  'index.html',
  'apk/index.html',
  'registration/index.html',
  'promo-code/index.html'
];

function findSliderBlock(html) {
  // Ищем начало блока: <div class="... owl-carousel ..." с hero-slider или embla в том же теге
  const re = /<div\s+[^>]*class=["'][^"']*owl-carousel[^"']*["'][^>]*>/gi;
  let match;
  while ((match = re.exec(html)) !== null) {
    const start = match.index;
    const tagEnd = html.indexOf('>', start);
    const tagContent = html.slice(start, tagEnd + 1);
    if (/hero-slider|embla|owl-loaded/.test(tagContent)) {
      let depth = 0;
      let p = start;
      let endOfBlock = -1;
      while (p < html.length) {
        const nextOpen = html.indexOf('<div', p);
        const nextClose = html.indexOf('</div>', p);
        if (nextClose === -1) break;
        if (nextOpen !== -1 && nextOpen < nextClose) {
          depth++;
          p = nextOpen + 4;
        } else {
          depth--;
          p = nextClose + 6;
          if (depth === 0) {
            endOfBlock = p;
            break;
          }
        }
      }
      return { start, end: endOfBlock };
    }
  }
  return { start: -1, end: -1 };
}

function removeEmblaAssets(html) {
  let out = html;
  out = out.replace(/<link[^>]*href=["'][^"']*carousel-embla\.css[^"']*["'][^>]*>\s*/gi, '');
  out = out.replace(/<script[^>]*src=["'][^"']*embla-carousel\.umd\.js[^"']*["'][^>]*><\/script>\s*/gi, '');
  out = out.replace(/<script[^>]*src=["'][^"']*carousel-embla\.js[^"']*["'][^>]*><\/script>\s*/gi, '');
  return out;
}

function removeSliderInHtml(html) {
  let out = html;
  const { start, end } = findSliderBlock(out);
  if (start !== -1 && end !== -1) {
    out = out.slice(0, start) + out.slice(end);
  }
  return removeEmblaAssets(out);
}

function main() {
  if (!fs.existsSync(DIST)) {
    console.error('dist/ not found.');
    process.exit(1);
  }

  for (const pagePath of PAGES) {
    const fullPath = path.join(DIST, pagePath);
    if (!fs.existsSync(fullPath)) continue;
    let html = fs.readFileSync(fullPath, 'utf8');
    const updated = removeSliderInHtml(html);
    if (updated !== html) {
      fs.writeFileSync(fullPath, updated, 'utf8');
      console.log('Removed slider from', pagePath);
    }
  }
  console.log('Done.');
}

main();
