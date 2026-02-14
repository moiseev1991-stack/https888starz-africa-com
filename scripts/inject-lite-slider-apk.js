/**
 * After fetch-africa-pages: inject Embla Carousel on pages that have sliders.
 * Pages: main (index), apk, registration, promo-code.
 * We do NOT replace owl HTML — carousel-embla.js wraps .owl-mobile/.owl-carousel/.slider-888-slider at runtime.
 * Copy embla assets to dist/assets/embla/ and add link + scripts to each slider page.
 */

const fs = require('fs');
const path = require('path');

const PROJECT_ROOT = path.resolve(__dirname, '..');
const OUT_DIR = process.env.STATIC_OUT_DIR || 'dist';
const DIST = path.join(PROJECT_ROOT, OUT_DIR);
const EMBLA_SOURCES = path.join(__dirname, 'static-assets', 'embla');
const DIST_EMBLA = path.join(DIST, 'assets', 'embla');

const SLIDER_MARKER = /owl-carousel|owl-mobile|slider-888-slider/i;

const PAGES = [
  { path: 'index.html', dir: '' },
  { path: 'apk/index.html', dir: 'apk' },
  { path: 'registration/index.html', dir: 'registration' },
  { path: 'promo-code/index.html', dir: 'promo-code' }
];

function copyAssets() {
  if (!fs.existsSync(EMBLA_SOURCES)) {
    console.warn('static-assets/embla/ not found. Run: node scripts/download-embla.js');
    return false;
  }
  if (!fs.existsSync(DIST)) return false;
  if (!fs.existsSync(path.join(DIST, 'assets'))) fs.mkdirSync(path.join(DIST, 'assets'), { recursive: true });
  if (!fs.existsSync(DIST_EMBLA)) fs.mkdirSync(DIST_EMBLA, { recursive: true });
  const files = ['embla-carousel.umd.js', 'carousel-embla.js', 'carousel-embla.css'];
  for (const name of files) {
    const src = path.join(EMBLA_SOURCES, name);
    const dest = path.join(DIST_EMBLA, name);
    if (fs.existsSync(src)) {
      fs.copyFileSync(src, dest);
      console.log('Copied', name, 'to dist/assets/embla/');
    } else if (name === 'embla-carousel.umd.js') {
      console.warn('Run node scripts/download-embla.js to get embla-carousel.umd.js');
    }
  }
  return true;
}

function hasSlider(html) {
  return SLIDER_MARKER.test(html);
}

function injectEmbla(html) {
  const base = '/assets/embla/';
  let out = html;
  if (!out.includes('carousel-embla.css')) {
    out = out.replace('</head>', '<link rel="stylesheet" href="' + base + 'carousel-embla.css">\n</head>');
  }
  if (!out.includes('embla-carousel.umd.js')) {
    out = out.replace('</body>', '<script src="' + base + 'embla-carousel.umd.js"></script>\n</body>');
  }
  if (!out.includes('carousel-embla.js')) {
    out = out.replace('</body>', '<script src="' + base + 'carousel-embla.js" defer></script>\n</body>');
  }
  if (out.includes('data-fancybox="gallery"') && !out.includes('fancyboxReinitAfterCarousel')) {
    var fancyboxReinit = '<script>/* fancyboxReinitAfterCarousel */ window.addEventListener("load",function(){var $=window.jQuery;if($&&$.fn.fancybox){setTimeout(function(){ $(\'[data-fancybox="gallery"]\').fancybox({loop:true,buttons:["zoom","share","slideShow","fullScreen","download","thumbs","close"]}); $(\'[data-fancybox="gallerymob"]\').fancybox({loop:true,buttons:["zoom","share","slideShow","fullScreen","download","thumbs","close"]}); },500);}});</script>';
    out = out.replace('</body>', fancyboxReinit + '\n</body>');
  }
  return out;
}

function main() {
  if (!copyAssets()) {
    console.log('Skipping inject (no dist or no embla assets).');
    return;
  }

  let injected = 0;
  for (const page of PAGES) {
    const fullPath = path.join(DIST, page.path);
    if (!fs.existsSync(fullPath)) continue;
    let html = fs.readFileSync(fullPath, 'utf8');
    if (!hasSlider(html)) continue;
    const updated = injectEmbla(html);
    if (updated !== html) {
      fs.writeFileSync(fullPath, updated, 'utf8');
      console.log('Injected Embla on', page.path);
      injected++;
    }
  }
  if (injected === 0) {
    console.log('No slider pages needed Embla injection.');
  }
}

main();
