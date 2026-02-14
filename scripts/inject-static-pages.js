/**
 * After crawl: create /apk/, /registration/, /promo-code/ in dist from template.
 * Static deploy does not run WordPress — these pages must exist as HTML.
 */

const fs = require('fs');
const path = require('path');

const PROJECT_ROOT = path.resolve(__dirname, '..');
const OUT_DIR = process.env.STATIC_OUT_DIR || 'dist';
const DIST = path.join(PROJECT_ROOT, OUT_DIR);
const CONTENT_DIR = path.join(__dirname, 'static-pages-content');
const BASE_URL = (process.env.STATIC_BASE_URL || 'https://888starzeg-egypt.com').replace(/\/$/, '');

const PAGES = {
  'apk': {
    title: '888starz تحميل للاندرويد APK عربي Egypt app | 888Starz',
    h1: '888starz تحميل للاندرويد APK عربي Egypt App'
  },
  'registration': {
    title: '888Starz تسجيل لاعبين جدد بمكافأة في مصر | 888Starz',
    h1: 'تسجيل 888Starz مع مكافأة في مصر'
  },
  'promo-code': {
    title: '888Starz الرمز الترويجي في مصر | 888Starz',
    h1: 'رمز ترويجي 888Starz في مصر'
  }
};

function getContent(slug) {
  const filePath = path.join(CONTENT_DIR, slug + '.html');
  if (fs.existsSync(filePath)) {
    let content = fs.readFileSync(filePath, 'utf8');
    content = content.replace(/https?:\/\/888starz-africa\.com/g, BASE_URL);
    return content.trim();
  }
  const fallback = {
    'apk': '<p>تطبيق 888Starz لأندرويد (APK) يتيح المراهنة ولعب الكازينو من هاتفك. <a href="/registration/">التسجيل</a> | <a href="/terms/">الشروط</a> | <a href="/contacts/">اتصل بنا</a></p>',
    'registration': '<p>إنشاء حساب في 888Starz سريع. <a href="/terms/">الشروط</a> | <a href="/apk/">تحميل التطبيق</a> | <a href="/contacts/">اتصل بنا</a></p>',
    'promo-code': '<p>الرمز الترويجي يعطيك مكافأة عند التسجيل أو الإيداع. <a href="/registration/">التسجيل</a> | <a href="/contacts/">اتصل بنا</a></p>'
  };
  return fallback[slug] || '';
}

function main() {
  if (!fs.existsSync(DIST)) {
    console.error('dist/ not found. Run export first.');
    process.exit(1);
  }
  const templatePath = path.join(DIST, 'about', 'index.html');
  const indexPath = path.join(DIST, 'index.html');
  const srcPath = fs.existsSync(templatePath) ? templatePath : indexPath;
  if (!fs.existsSync(srcPath)) {
    console.error('No template found (about or index).');
    process.exit(1);
  }
  let baseHtml = fs.readFileSync(srcPath, 'utf8');
  for (const [slug, { title, h1 }] of Object.entries(PAGES)) {
    const content = getContent(slug);
    let html = baseHtml
      .replace(/<title>[^<]*<\/title>/i, '<title>' + title + '</title>')
      .replace(/<link\s+rel=["']canonical["'][^>]*>/i, '<link rel="canonical" href="' + BASE_URL + '/' + slug + '/">');
    const mainMatch = html.match(/<main\s+id=["']page["'][^>]*>[\s\S]*?<\/main>/i);
    if (mainMatch) {
      const inner = '<div id="single" class="content no-sidebar no-thumb"><div class="epcl-page-wrapper content clearfix"><div class="left-content grid-100"><article class="main-article no-bg"><section class="post-content"><h1 class="title ularge textcenter bordered">' + h1 + '</h1><div class="text">' + content + '</div></section></article></div><div class="clear"></div></div></div>';
      html = html.replace(/<main\s+id=["']page["'][^>]*>[\s\S]*?<\/main>/i, '<main id="page" class="main grid-container">' + inner + '</main>');
    }
    const dir = path.join(DIST, slug);
    if (!fs.existsSync(dir)) fs.mkdirSync(dir, { recursive: true });
    fs.writeFileSync(path.join(dir, 'index.html'), html, 'utf8');
    console.log('Created:', slug + '/index.html');
  }
}

main();
