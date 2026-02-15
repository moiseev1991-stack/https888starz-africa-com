/**
 * Static export: crawls site at baseUrl, saves HTML + JSON index + list of asset URLs.
 * Works with LIVE site (no WordPress needed). Default: https://888starz-africa.com
 * Then run download-all-assets.js to download all CSS/JS/images (and merge local wp-content for gaps).
 */

const fs = require('fs');
const path = require('path');
const { URL } = require('url');
const { chromium } = require('playwright');

const PROJECT_ROOT = path.resolve(__dirname, '..');
// Краулить Africa (рабочая разметка слайдера); для Egypt задать STATIC_BASE_URL при сборке
const BASE_URL = process.env.CRAWL_BASE_URL || process.env.STATIC_BASE_URL || 'https://888starz-africa.com';
const OUT_DIR = process.env.STATIC_OUT_DIR || 'dist';
const DIST = path.join(PROJECT_ROOT, OUT_DIR);
const MAX_PAGES = 2000;
const CONCURRENCY = 2;

const SKIP_PATHS = /^\/(wp-admin|wp-login|wp-includes|wp-content\/plugins|xmlrpc|wp-json|cgi-bin)/i;
const SKIP_EXT = /\.(php|xml|rss|atom)(\?|$)/i;
const ASSET_EXT = /\.(css|js|jpg|jpeg|png|gif|webp|svg|ico|woff2?|ttf|eot|mp4|webm|pdf)(\?|$)/i;

function isPageUrl(pathname) {
  return !ASSET_EXT.test(pathname) && !SKIP_EXT.test(pathname);
}

function normalizePath(urlPath) {
  let p = urlPath.replace(/^\//, '').replace(/\?.*$/, '').replace(/#.*$/, '');
  if (!p) return 'index.html';
  if (!/\.(html?|htm)$/i.test(p)) p = p.replace(/\/?$/, '/') + 'index.html';
  return p;
}

function sameOrigin(href, base) {
  try {
    const u = new URL(href, base);
    return u.origin === new URL(base).origin;
  } catch { return false; }
}

// System pages: always seed so they are exported even if not linked from menu
const SYSTEM_PAGE_SLUGS = [
  'about', 'contacts', 'terms', 'responsible', 'privacy-policy',
  'self-exclusion', 'dispute-resolution', 'fairness-rng-testing-methods', 'accounts-withdrawals-and-bonuses',
  'promo-code'
];

async function main() {
  const base = BASE_URL.replace(/\/$/, '');
  const baseOrigin = new URL(base).origin;
  const toVisit = new Set([base + '/']);
  SYSTEM_PAGE_SLUGS.forEach(slug => toVisit.add(base + '/' + slug + '/'));
  const visited = new Set();
  const assetUrls = new Set();
  const urlList = [];
  const pagesMeta = [];

  if (fs.existsSync(DIST)) fs.rmSync(DIST, { recursive: true });
  fs.mkdirSync(DIST, { recursive: true });

  const browser = await chromium.launch({ headless: true });
  const context = await browser.newContext({
    userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    viewport: { width: 1280, height: 720 },
  });

  while (toVisit.size > 0 && visited.size < MAX_PAGES) {
    const batch = [...toVisit].slice(0, CONCURRENCY);
    batch.forEach(u => toVisit.delete(u));

    await Promise.all(batch.map(async (url) => {
      if (visited.has(url)) return;
      const pathname = new URL(url).pathname;
      if (SKIP_PATHS.test(pathname) || SKIP_EXT.test(pathname)) return;
      visited.add(url);

      try {
        const page = await context.newPage();
        const res = await page.goto(url, { waitUntil: 'load', timeout: 35000 });
        if (!res || res.status() >= 400) {
          await page.close();
          return;
        }
        await page.waitForTimeout(1500);
        let html;
        try {
          html = await page.content();
        } catch (e) {
          if (/navigating|content/i.test(e.message)) {
            await page.waitForTimeout(3000);
            html = await page.content().catch(() => null);
          } else throw e;
        }
        if (!html) {
          await page.close();
          return;
        }

        const resourceUrls = await page.evaluate((origin) => {
          const out = new Set();
          document.querySelectorAll('link[href], script[src], img[src], source[src]').forEach((el) => {
            const u = el.href || el.src;
            if (u && (u.startsWith(origin) || u.startsWith('/'))) out.add(u.startsWith('/') ? origin + u : u);
          });
          return [...out];
        }, baseOrigin);
        resourceUrls.forEach((u) => assetUrls.add(u));

        const links = await page.$$eval('a[href]', (nodes) =>
          nodes.map(a => a.getAttribute('href')).filter(Boolean)
        );
        links.forEach(href => {
          if (!sameOrigin(href, url)) return;
          const u = new URL(href, url);
          if (!isPageUrl(u.pathname) || SKIP_PATHS.test(u.pathname)) return;
          const p = u.pathname.replace(/\/$/, '') || '/';
          const full = u.origin + (p === '/' ? '/' : p + '/');
          toVisit.add(full);
        });

        const canonicalPath = pathname === '/' ? '/' : pathname + (pathname.endsWith('/') ? '' : '/');
        let title = '', description = '', ogImage = '';
        try { title = await page.title() || ''; } catch (_) {}
        try { description = (await page.$eval('meta[name="description"]', el => el.content)) || ''; } catch (_) {}
        try { ogImage = (await page.$eval('meta[property="og:image"]', el => el.content)) || ''; } catch (_) {}
        pagesMeta.push({ url: canonicalPath, title, description, ogImage });

        const filePath = path.join(DIST, normalizePath(pathname));
        const dir = path.dirname(filePath);
        if (!fs.existsSync(dir)) fs.mkdirSync(dir, { recursive: true });
        const baseEscaped = base.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        const relativeHtml = html
          .replace(new RegExp(baseEscaped, 'g'), '')
          .replace(new RegExp('https://www\\.888starz-africa\\.com', 'g'), '')
          .replace(new RegExp('http://www\\.888starz-africa\\.com', 'g'), '');
        fs.writeFileSync(filePath, relativeHtml, 'utf8');
        urlList.push(canonicalPath);

        await page.close();
        console.log('Saved:', pathname || '/');
      } catch (err) {
        console.error('Error', url, err.message);
      }
    }));
  }

  await browser.close();

  const uniqueUrls = [...new Set(urlList)].sort();
  fs.writeFileSync(path.join(PROJECT_ROOT, 'URLS.txt'), uniqueUrls.join('\n'), 'utf8');
  fs.writeFileSync(
    path.join(DIST, 'asset-urls.json'),
    JSON.stringify([...assetUrls], null, 0),
    'utf8'
  );
  fs.writeFileSync(
    path.join(DIST, 'pages.json'),
    JSON.stringify({ baseUrl: base, pages: pagesMeta, count: pagesMeta.length }, null, 2),
    'utf8'
  );
  console.log('Export done. Pages:', visited.size, 'Asset URLs:', assetUrls.size, 'Output:', DIST);
}

main().catch(err => { console.error(err); process.exit(1); });
