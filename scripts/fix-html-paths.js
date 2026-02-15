/**
 * Заменяет абсолютные пути (/wp-content/..., /wp-includes/...) на относительные от каждой HTML-страницы.
 * Тогда картинки и стили работают и при открытии через file://, и с любого сервера.
 */

const fs = require('fs');
const path = require('path');

const PROJECT_ROOT = path.resolve(__dirname, '..');
const OUT_DIR = process.env.STATIC_OUT_DIR || 'dist';
const DIST = path.join(PROJECT_ROOT, OUT_DIR);

function depthFromDist(filePath) {
  const rel = path.relative(DIST, filePath).replace(/\\/g, '/');
  if (rel === 'index.html') return 0;
  const parts = rel.split('/');
  return Math.max(0, parts.length - 1);
}

function getRelativePrefix(depth) {
  return depth === 0 ? './' : '../'.repeat(depth);
}

function isAssetPath(p) {
  return p.startsWith('wp-content/') || p.startsWith('wp-includes/');
}

/** Кодирует сегменты пути с не-ASCII (арабские и др.) для корректной загрузки на сервере. */
function encodeAssetPath(pathStr) {
  if (!pathStr || !/[^\x00-\x7F]/.test(pathStr)) return pathStr;
  return pathStr
    .split('/')
    .map((seg) => (/[^\x00-\x7F]/.test(seg) ? encodeURIComponent(seg) : seg))
    .join('/');
}

/** В HTML заменяет пути к ассетам с не-ASCII на percent-encoded (чтобы картинки с арабскими именами грузились). */
function encodeAssetPathsInHtml(html) {
  let out = html;
  const attrRe = /(src|href)=(["'])([^"']+)\2/g;
  out = out.replace(attrRe, (_, attr, q, p) => {
    if ((p.includes('/wp-content/') || p.includes('/wp-includes/')) && /[^\x00-\x7F]/.test(p)) {
      const encoded = encodeAssetPath(p);
      return `${attr}=${q}${encoded}${q}`;
    }
    return _;
  });
  return out;
}

// Footer "حول الموقع": restore system page links (export from prod may have /game/)
const FOOTER_LINK_FIXES = [
  ['من نحن', 'about'],
  ['معلومات الاتصال', 'contacts'],
  ['شروط الاستخدام', 'terms'],
  ['اللعب المسؤول', 'responsible'],
  ['الخصوصية وإدارة البيانات', 'privacy-policy'],
  ['الاستبعاد الذاتي', 'self-exclusion'],
  ['حل النزاعات', 'dispute-resolution'],
  ['طرق اختبار النزاهة والعشوائية', 'fairness-rng-testing-methods'],
  ['الحسابات والمدفوعات والمكافآت', 'accounts-withdrawals-and-bonuses'],
];

function fixFooterSystemLinks(html, prefix) {
  let out = html;
  for (const [label, slug] of FOOTER_LINK_FIXES) {
    const re = new RegExp(
      '(<a\\s+href=")[^"]*("\\s*[^>]*>\\s*' + label.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + '\\s*</a>)',
      'g'
    );
    const href = prefix ? prefix + slug + '/' : '/' + slug + '/';
    out = out.replace(re, '$1' + href + '$2');
  }
  return out;
}

function fixHtml(html, prefix) {
  if (!prefix) return html;
  // Сначала кодируем пути с арабскими/не-ASCII именами файлов (картинки иначе не грузятся)
  html = encodeAssetPathsInHtml(html);
  // Восстановить ссылки на системные страницы в футере
  html = fixFooterSystemLinks(html, prefix);
  // Нормализуем относительные пути к ассетам в корневые /wp-content/, /wp-includes/
  let normalized = html
    .replace(/(["'])(?:\.\.\/)+wp-content\//g, '$1/wp-content/')
    .replace(/(["'])(?:\.\.\/)+wp-includes\//g, '$1/wp-includes/')
    .replace(/(["'])\.\/wp-content\//g, '$1/wp-content/')
    .replace(/(["'])\.\/wp-includes\//g, '$1/wp-includes/');
  // Пути к ассетам делаем относительными (prefix), чтобы картинки грузились с любого базового URL и из подпапок
  let out = normalized
    .replace(/(\s|")(src|href)=(["'])\/(?!\/)([^"'#]*)\3/g, (_, before, attr, q, p) => {
      const lead = before === '"' ? '" ' : before;
      const path = (isAssetPath(p) ? prefix + encodeAssetPath(p) : prefix + p);
      return `${lead}${attr}=${q}${path}${q}`;
    })
    .replace(/\s(srcset)=(["'])([^"']+)\2/g, (_, attr, q, list) => {
      const parts = list.split(',').map((s) => {
        const u = s.trim().split(/\s+/)[0];
        if (u.startsWith('/') && !u.startsWith('//') && !u.startsWith('data:')) {
          const p = u.slice(1);
          return prefix + (isAssetPath(p) ? encodeAssetPath(p) : p);
        }
        return u;
      });
      return ` ${attr}=${q}${parts.join(', ')}${q}`;
    })
    .replace(/url\s*\(\s*["']?\/(wp-content\/[^"')]+)["']?\s*\)/g, (_, p) => `url("${prefix}${encodeAssetPath(p)}")`)
    .replace(/url\s*\(\s*\/(wp-content\/[^"')]+)\s*\)/g, (_, p) => `url("${prefix}${encodeAssetPath(p)}")`);
  out = out.replace(/((?:src|href)=["'][^"']*(?:\.(?:svg|webp|png|jpg|jpeg|gif|ico|css|js))?)""/g, '$1"');
  out = out.replace(/"\s*(src|href)=/g, '" $1=');
  out = out.replace(/<ahref=/g, '<a href=');
  out = out.replace(/<scriptsrc=/g, '<script src=');
  out = out.replace(/<imgsrc=/g, '<img src=');
  out = out.replace(/xlink:href=["']\/(?!\/)([^"']+)["']/g, (_, p) => `xlink:href="${prefix}${p}"`);
  out = out.replace(/href="\.\.\/game\/"/g, 'href="../game/index.html"');
  out = out.replace(/href="\.\/game\/"/g, 'href="./game/index.html"');
  return out;
}

function walk(dir) {
  const entries = fs.readdirSync(dir, { withFileTypes: true });
  for (const e of entries) {
    const full = path.join(dir, e.name);
    if (e.isDirectory()) walk(full);
    else if (e.name.endsWith('.html')) {
      const depth = depthFromDist(full);
      const prefix = getRelativePrefix(depth);
      let html = fs.readFileSync(full, 'utf8');
      const fixed = fixHtml(html, prefix);
      if (fixed !== html) {
        fs.writeFileSync(full, fixed, 'utf8');
        console.log('Fixed paths:', path.relative(DIST, full));
      }
    }
  }
}

function main() {
  if (!fs.existsSync(DIST)) {
    console.error('dist/ not found.');
    process.exit(1);
  }
  console.log('Converting root-relative paths to document-relative in HTML...');
  walk(DIST);
  console.log('Done.');
}

main();
