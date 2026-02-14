/**
 * Stage 1: Capture Africa and Egypt pages via Playwright.
 * For each of /apk/, /registration/, /promo-code/:
 * - Save rendered HTML
 * - Save list of resources (CSS/JS/img/font)
 * - Save fullpage screenshot
 * Output: scripts/capture-output/{africa|egypt}-{slug}.html, .json, .png
 */

const fs = require('fs');
const path = require('path');
const { chromium } = require('playwright');

const PROJECT_ROOT = path.resolve(__dirname, '..');
const OUT = path.join(__dirname, 'capture-output');
const SLUGS = ['apk', 'registration', 'promo-code'];
const AFRICA_BASE = 'https://888starz-africa.com';
const EGYPT_BASE = 'https://888starzeg-egypt.com';

async function capturePage(browser, url, label) {
  const page = await browser.newPage();
  const resources = [];
  page.on('request', req => {
    const u = req.url();
    const res = req.resourceType();
    if (['stylesheet', 'script', 'image', 'font', 'document'].includes(res)) {
      resources.push({ url: u, type: res });
    }
  });
  const res = await page.goto(url, { waitUntil: 'networkidle', timeout: 45000 }).catch(() => null);
  if (!res || res.status() >= 400) {
    await page.close();
    return { html: null, resources: [], status: res ? res.status() : 0 };
  }
  await page.waitForTimeout(2000);
  const html = await page.content();
  const slug = url.replace(/\/$/, '').split('/').pop() || 'index';
  const outDir = path.join(OUT, label);
  if (!fs.existsSync(outDir)) fs.mkdirSync(outDir, { recursive: true });
  const base = path.join(outDir, slug);
  fs.writeFileSync(base + '.html', html, 'utf8');
  fs.writeFileSync(base + '-resources.json', JSON.stringify(resources, null, 2), 'utf8');
  await page.screenshot({ path: base + '.png', fullPage: true }).catch(() => {});
  await page.close();
  return { html: base + '.html', resources, status: res.status() };
}

function diffResources(africaRes, egyptRes) {
  const aUrls = new Set((africaRes || []).map(r => r.url));
  const eUrls = new Set((egyptRes || []).map(r => r.url));
  const onlyAfrica = [...aUrls].filter(u => !eUrls.has(u));
  const onlyEgypt = [...eUrls].filter(u => !aUrls.has(u));
  return { onlyAfrica, onlyEgypt, africaCount: aUrls.size, egyptCount: eUrls.size };
}

async function main() {
  if (!fs.existsSync(OUT)) fs.mkdirSync(OUT, { recursive: true });
  const browser = await chromium.launch({ headless: true });
  const viewport = { width: 1280, height: 720 };
  const results = { africa: {}, egypt: {}, diff: {} };

  for (const slug of SLUGS) {
    const africaUrl = `${AFRICA_BASE}/${slug}/`;
    const egyptUrl = `${EGYPT_BASE}/${slug}/`;
    console.log('Capture Africa', africaUrl);
    const a = await capturePage(browser, africaUrl, 'africa');
    results.africa[slug] = { status: a.status, resourcesCount: (a.resources || []).length };
    console.log('Capture Egypt', egyptUrl);
    const e = await capturePage(browser, egyptUrl, 'egypt');
    results.egypt[slug] = { status: e.status, resourcesCount: (e.resources || []).length };
    results.diff[slug] = diffResources(a.resources, e.resources);
  }

  await browser.close();
  fs.writeFileSync(path.join(OUT, 'summary.json'), JSON.stringify(results, null, 2), 'utf8');
  console.log('Summary written to capture-output/summary.json');
  console.log('Diff (resources only in Africa):', Object.fromEntries(
    Object.entries(results.diff).map(([k, v]) => [k, v.onlyAfrica?.length || 0])
  ));
}

main().catch(err => { console.error(err); process.exit(1); });
