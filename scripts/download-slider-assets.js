/**
 * Download hero slider images from Africa so they exist in dist (no 404).
 * Paths must match theme: /wp-content/uploads/2025/03/... (desktop + mobile sliders).
 * Run after copy-wp-assets so dist/wp-content exists.
 */

const fs = require('fs');
const path = require('path');
const https = require('https');

const PROJECT_ROOT = path.resolve(__dirname, '..');
const OUT_DIR = process.env.STATIC_OUT_DIR || 'dist';
const DIST = path.join(PROJECT_ROOT, OUT_DIR);
const AFRICA_BASE = 'https://888starz-africa.com';

// Same paths as in wp-content/themes/zento/functions.php (deck_slider_shortcode + custom_slider_shortcode)
const SLIDER_PATHS = [
  '/wp-content/uploads/2025/03/الإحصاءات.webp',
  '/wp-content/uploads/2025/03/البنغو.webp',
  '/wp-content/uploads/2025/03/الرياضة-الإلكترونية.webp',
  '/wp-content/uploads/2025/03/الصفحة-الرئيسية-888ستارز.webp',
  '/wp-content/uploads/2025/03/ألعاب-الكازينو-1.webp',
  '/wp-content/uploads/2025/03/النتائج.webp',
  '/wp-content/uploads/2025/03/بوكر.webp',
  '/wp-content/uploads/2025/03/جميع-الألعاب-888.webp',
  '/wp-content/uploads/2025/03/جميع-المكافآت.webp',
  '/wp-content/uploads/2025/03/ماكينات-سلوتس-888-ستارز.webp',
  '/wp-content/uploads/2025/03/888starz.com_.webp',
  '/wp-content/uploads/2025/03/888ألعاب-888GAMES.webp',
  '/wp-content/uploads/2025/03/الألعاب-التلفزيونية.webp',
  '/wp-content/uploads/2025/03/الرمز-الترويجي-1500-و150-دورة-مجانية.webp',
  '/wp-content/uploads/2025/03/الرهان-على-الرياضات-الإلكترونية.webp',
  '/wp-content/uploads/2025/03/ألعاب-الكازينو.webp',
  '/wp-content/uploads/2025/03/حساب-تجريبي-888-ستارز.webp',
  '/wp-content/uploads/2025/03/لعبة-الطيار.webp',
  '/wp-content/uploads/2025/03/ماكينات-القمار-888ستارز.webp',
  '/wp-content/uploads/2025/03/نوع-الألعاب-في-الكازينو.webp',
];

function fetchBuffer(urlStr) {
  return new Promise((resolve, reject) => {
    const req = https.get(urlStr, {
      headers: { 'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0' },
      timeout: 15000
    }, (res) => {
      const redirect = res.statusCode >= 300 && res.statusCode < 400 && res.headers.location;
      if (redirect) {
        const next = new URL(redirect, urlStr).href;
        return fetchBuffer(next).then(resolve).catch(reject);
      }
      const chunks = [];
      res.on('data', (c) => chunks.push(c));
      res.on('end', () => resolve({ buffer: Buffer.concat(chunks), statusCode: res.statusCode }));
    });
    req.on('error', reject);
  });
}

/** Save path: segment with non-ASCII must be URL-encoded on disk (browser requests encoded). */
function pathnameToLocal(pathname) {
  return pathname
    .split('/')
    .map((seg) => (/[^\x00-\x7F]/.test(seg) ? encodeURIComponent(seg) : seg))
    .join(path.sep);
}

async function main() {
  if (!fs.existsSync(DIST)) {
    console.error('dist/ not found. Run build step that creates dist first.');
    process.exit(1);
  }
  const uploadsDir = path.join(DIST, 'wp-content', 'uploads', '2025', '03');
  if (!fs.existsSync(uploadsDir)) {
    fs.mkdirSync(uploadsDir, { recursive: true });
  }

  let saved = 0;
  for (const p of SLIDER_PATHS) {
    const localRel = pathnameToLocal(p.replace(/^\//, ''));
    const localPath = path.join(DIST, localRel);
    if (fs.existsSync(localPath)) continue;

    const url = AFRICA_BASE + p;
    try {
      const { buffer, statusCode } = await fetchBuffer(url);
      if (statusCode !== 200 || !buffer.length) continue;
      fs.mkdirSync(path.dirname(localPath), { recursive: true });
      fs.writeFileSync(localPath, buffer);
      saved++;
      console.log('Slider asset:', p);
    } catch (e) {
      console.warn('Skip', p, e.message);
    }
  }
  console.log('download-slider-assets: saved', saved, 'slider images from Africa.');
}

main().catch((err) => { console.error(err); process.exit(1); });
