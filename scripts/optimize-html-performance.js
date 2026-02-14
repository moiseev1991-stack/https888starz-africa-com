/**
 * Post-processing оптимизация HTML для PageSpeed:
 * - Удаляет дублирующиеся jQuery (оставляет только один)
 * - Добавляет defer/async для скриптов
 * - Добавляет font-display: swap для Font Awesome
 * - Оптимизирует загрузку CSS (preload для критических, defer для остальных)
 * - Удаляет jQuery Migrate если не нужен
 */

const fs = require('fs');
const path = require('path');

const PROJECT_ROOT = path.resolve(__dirname, '..');
const OUT_DIR = process.env.STATIC_OUT_DIR || 'dist';
const DIST = path.join(PROJECT_ROOT, OUT_DIR);

function walk(dir, callback, fileExt = '.html') {
  const entries = fs.readdirSync(dir, { withFileTypes: true });
  for (const e of entries) {
    const full = path.join(dir, e.name);
    if (e.isDirectory()) {
      walk(full, callback, fileExt);
    } else if (e.name.endsWith(fileExt)) {
      callback(full);
    }
  }
}

function optimizeCss(css) {
  let out = css;
  
  // Добавить font-display: swap для всех @font-face (Font Awesome webfont.woff2 — PageSpeed 30 мс)
  out = out.replace(
    /(@font-face\s*\{[^}]*)(\})/gi,
    (match, before, closing) => {
      if (/font-display/.test(before)) return match;
      const sep = /\n/.test(before) ? '\n  font-display: swap;' : ' font-display:swap;';
      return before + sep + closing;
    }
  );
  
  return out;
}

const BASE_URL = process.env.STATIC_BASE_URL || 'https://888starzeg-egypt.com';

function optimizeHtml(html, relativePath) {
  let out = html;
  const bodyStart = out.indexOf('</head>');
  
  // 0. Собрать инлайн-скрипты с $/jQuery из body и перенести в конец (исправляет " $ is not defined")
  const inlineScriptsWithJQuery = [];
  out = out.replace(/<script(\s+[^>]*)?>([\s\S]*?)<\/script>/gi, (m, attrs, content) => {
    if (attrs && /\bsrc\s*=/.test(attrs)) return m;
    if (!/\$\(|jQuery\(/.test(content)) return m;
    const pos = out.indexOf(m);
    if (pos >= 0 && pos <= bodyStart) return m; // в head не трогаем (обернём в DOMContentLoaded)
    inlineScriptsWithJQuery.push(m);
    return '';
  });
  
  // 1. Удалить дублирующиеся jQuery (оставить только один — тот, что идёт первым в документе)
  const jqueryRegex = /<script[^>]*src=["']([^"']*jquery[^"']*)["'][^>]*><\/script>/gi;
  const jqueryMatches = [];
  let match;
  while ((match = jqueryRegex.exec(html)) !== null) {
    jqueryMatches.push({ full: match[0], url: match[1], index: match.index });
  }
  
  if (jqueryMatches.length > 1) {
    // Оставить тот jQuery, который встречается первым (чтобы скрипты в head не ломались)
    jqueryMatches.sort((a, b) => a.index - b.index);
    const toKeep = jqueryMatches[0];
    jqueryMatches.forEach(m => {
      if (m.full !== toKeep.full) {
        out = out.replace(m.full, '');
      }
    });
  }
  
  // 2. Удалить jQuery Migrate если есть (обычно не нужен для современных сайтов)
  out = out.replace(/<script[^>]*jquery-migrate[^>]*><\/script>/gi, '');
  
  // 3. Добавить defer для скриптов которые не критичны (owl, fancybox, prism — не блокируют рендер)
  out = out.replace(
    /<script([^>]*src=["'][^"']*(?:owl|fancybox|carousel)[^"']*["'][^>]*)><\/script>/gi,
    '<script$1 defer></script>'
  );
  out = out.replace(
    /<script([^>]*src=["'][^"']*prism-plugins\.min\.js[^"']*["'][^>]*)><\/script>/gi,
    (m) => (/defer/.test(m) ? m : m.replace(/><\/script>/, ' defer></script>'))
  );
  // flexy-breadcrumb и prism (core/autoloader) — defer, чтобы убрать из критической цепочки (646 ms)
  out = out.replace(
    /<script([^>]*src=["'][^"']*(?:flexy-breadcrumb-public\.js|prism-core\.min\.js|prism-autoloader\.min\.js)[^"']*["'][^>]*)><\/script>/gi,
    (m) => (/defer/.test(m) ? m : m.replace(/><\/script>/, ' defer></script>'))
  );
  
  // 4. Перенести jQuery и scripts.min.js в конец body с defer — разрыв критической цепочки (LCP/FCP), порядок сохраняется
  const jqueryTagMatch = out.match(/<script[^>]*src=["'][^"']*jquery[^"']*\.min\.js[^"']*["'][^>]*><\/script>/i);
  const scriptsMinMatch = out.match(/<script[^>]*src=["'][^"']*scripts\.min\.js[^"']*["'][^>]*><\/script>/i);
  const addDefer = (tag) => {
    if (!tag) return tag;
    return /defer|async/i.test(tag) ? tag : tag.replace(/><\/script>/, ' defer></script>');
  };
  if (jqueryTagMatch) {
    out = out.replace(jqueryTagMatch[0], '');
  }
  if (scriptsMinMatch) {
    out = out.replace(scriptsMinMatch[0], '');
  }
  const scriptsAtEnd = [addDefer(jqueryTagMatch && jqueryTagMatch[0]), addDefer(scriptsMinMatch && scriptsMinMatch[0])].filter(Boolean).join('\n');
  const deferredInline = inlineScriptsWithJQuery.length ? '\n' + inlineScriptsWithJQuery.join('\n') : '';
  if (scriptsAtEnd || deferredInline) {
    out = out.replace('</body>', (scriptsAtEnd || '') + deferredInline + '\n</body>');
  }
  // Оборачиваем jQuery(document).ready в head в DOMContentLoaded (jQuery теперь в конце body)
  out = out.replace(
    /(<script>(?:\s*\/\/[^\n]*\n)*)(\s*)(jQuery\s*\(\s*document\s*\)\s*\.\s*ready\s*\()/gi,
    '$1$2document.addEventListener("DOMContentLoaded",function(){ $3'
  );
  let addedDOMContentLoadedClose = false;
  out = out.replace(
    /(}\s*\)\s*;)\s*(\s*<\/script>)/g,
    (m, close, rest) => {
      if (addedDOMContentLoadedClose) return m;
      const pos = out.indexOf(m);
      const chunk = pos >= 0 ? out.slice(Math.max(0, pos - 2500), pos) : '';
      if (/document\.addEventListener\s*\(\s*["']DOMContentLoaded["']/.test(chunk)) {
        addedDOMContentLoadedClose = true;
        return close + ' }); ' + rest;
      }
      return m;
    }
  );

  // 5. email-decode.min.js (cloudflare-static) — defer, убирает 482 ms из критической цепочки
  out = out.replace(
    /<script([^>]*src=["'][^"']*email-decode\.min\.js[^"']*["'][^>]*)><\/script>/gi,
    (m) => (/defer/.test(m) ? m : m.replace(/><\/script>/, ' defer></script>'))
  );

  // 5a. Удалить скрипты, вызывающие ошибки (404 cdn-cgi/rum — убирает 1099 мс из цепочки, PageSpeed)
  out = out.replace(/<script[^>]*wp-emoji-release[^>]*><\/script>/gi, '');
  out = out.replace(/<script[^>]*cdn-cgi\/rum[^>]*><\/script>/gi, '');
  out = out.replace(/<script[^>]*src=["'][^"']*cdn-cgi[^"']*["'][^>]*><\/script>/gi, '');

  // 5b. Yandex Metrica — загружать асинхронно (не блокировать рендер, PageSpeed)
  out = out.replace(
    /<script([^>]*src=["'][^"']*mc\.yandex\.(ru|com)[^"']*["'][^>]*)><\/script>/gi,
    (m) => (/async/.test(m) ? m : m.replace(/><\/script>/, ' async></script>'))
  );

  // 5c. Preconnect — убрать пустой href (PageSpeed: ошибка пустого preconnect)
  out = out.replace(/<link\s+rel=["']preconnect["'][^>]*href=["']["'][^>]*\/?>/gi, '');
  if (!/preconnect[^>]*href=["']https?:\/\/888starzeg-egypt\.com["']/.test(out)) {
    const preconnectBlock = `
<link rel="preconnect" href="https://888starzeg-egypt.com">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>`;
    out = out.replace(/<head[^>]*>/i, '$&' + preconnectBlock);
  }

  // 5b. font-display: swap во всех инлайн <style> (на случай @font-face в странице)
  out = out.replace(/<style(\s[^>]*)?>([\s\S]*?)<\/style>/gi, (m, attrs, content) => {
    const fixed = optimizeCss(content);
    return fixed !== content ? '<style' + (attrs || '') + '>' + fixed + '</style>' : m;
  });

  // 6. font-display: swap для Font Awesome (PageSpeed: fonts/fontawesome-webfont.woff2)
  // Вставляем перед </body>, чтобы правило шло после всех stylesheet (в т.ч. preload+onload) и перекрывало @font-face
  const needsFontDisplayFix = !/font-display\s*:\s*swap/.test(out) && (/font-awesome|webfont\.woff2|["']\s*fa\s+fa-|class=["'][^"']*fa-/i.test(out));
  if (needsFontDisplayFix) {
    const fontDisplaySwap = `<style>@font-face{font-family:'FontAwesome';font-display:swap}@font-face{font-family:'Font Awesome 5 Free';font-display:swap}@font-face{font-family:'Font Awesome 5 Brands';font-display:swap}</style>`;
    out = out.replace('</body>', fontDisplaySwap + '\n</body>');
  }
  
  // 6. Оптимизировать загрузку CSS: preload для не-критических (любой порядок атрибутов — PageSpeed)
  const nonCriticalCss = ['font-awesome', 'plugins\\.min', 'fancybox', 'flexy-breadcrumb'];
  out = out.replace(/<link\s+([^>]*)>/gi, (match, attrs) => {
    if (!/rel=["']stylesheet["']/i.test(attrs)) return match;
    const hrefMatch = attrs.match(/href=["']([^"']+)["']/);
    if (!hrefMatch) return match;
    const href = hrefMatch[1];
    const isNonCritical = nonCriticalCss.some(p => new RegExp(p, 'i').test(href));
    if (!isNonCritical) return match;
    if (/preload|onload/.test(attrs)) return match;
    return `<link rel="preload" href="${href}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="${href}"></noscript>`;
  });

  // 8. Google Fonts — неблокирующая загрузка (css/css2, любой порядок атрибутов)
  out = out.replace(/<link\s+([^>]*)>/gi, (match, attrs) => {
    if (!/href=["']https?:\/\/fonts\.googleapis\.com\/[^"']*["']/.test(attrs)) return match;
    if (/rel=["']preload["']/.test(attrs)) return match;
    const hrefMatch = attrs.match(/href=["']([^"']+)["']/);
    if (!hrefMatch) return match;
    const href = hrefMatch[1];
    return `<link rel="preload" href="${href}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="${href}"></noscript>`;
  });

  // 8b. Дополнительный проход: принудительно preload для plugins.min.css и Google Fonts (если формат тега отличался)
  const preloadStyle = (href) => `<link rel="preload" href="${href}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="${href}"></noscript>`;
  out = out.replace(/<link\s+([^>]*)>/gi, (match, attrs) => {
    if (/rel=["']preload["']/.test(attrs)) return match;
    const hrefMatch = attrs.match(/href=["']([^"']+)["']/);
    if (!hrefMatch) return match;
    const href = hrefMatch[1];
    if (!/rel=["']stylesheet["']/i.test(attrs)) return match;
    if (/plugins\.min\.css|fonts\.googleapis\.com/.test(href)) return preloadStyle(href);
    return match;
  });
  
  // 9. Lazy-load изображений (снижение нагрузки на сеть, PageSpeed) — первый img без lazy (LCP)
  let imgIndex = 0;
  out = out.replace(/<img(\s+[^>]*)>/gi, (match, attrs) => {
    if (/loading\s*=/i.test(attrs)) return match;
    imgIndex++;
    let a = attrs;
    if (imgIndex === 1) {
      if (!/decoding\s*=/i.test(a)) a += ' decoding="async"';
      if (!/fetchpriority\s*=/i.test(a)) a += ' fetchpriority="high"';
      return '<img' + a + '>';
    }
    if (!/decoding\s*=/i.test(a)) a += ' decoding="async"';
    return '<img' + a + ' loading="lazy">';
  });

  // 9b. width/height из имени файла (CLS, соотношение сторон) — паттерны 320-250, 315x250 и т.п.
  out = out.replace(/<img(\s+[^>]*)>/gi, (match, attrs) => {
    if (/width\s*=/.test(attrs) && /height\s*=/.test(attrs)) return match;
    const srcMatch = attrs.match(/src=["']([^"']+)["']/);
    if (!srcMatch) return match;
    const dimMatch = srcMatch[1].match(/(\d{2,4})[-x×](\d{2,4})/i);
    if (!dimMatch) return match;
    const w = dimMatch[1];
    const h = dimMatch[2];
    return '<img' + attrs + ' width="' + w + '" height="' + h + '">';
  });

  // 10. Canonical — абсолютный URL (PageSpeed: «не действительный атрибут», «не абсолютный URL»)
  if (relativePath) {
    const pathSeg = relativePath.replace(/\\/g, '/').replace(/index\.html$/i, '').replace(/^\.\/?/, '');
    const canonicalHref = BASE_URL + (pathSeg ? '/' + pathSeg + '/' : '/');
    out = out.replace(/<link\s+[^>]*rel=["']canonical["'][^>]*href=["'][^"']*["'][^>]*>/gi,
      '<link rel="canonical" href="' + canonicalHref + '">');
    if (!/rel=["']canonical["']/.test(out)) {
      out = out.replace('</head>', '<link rel="canonical" href="' + canonicalHref + '">\n</head>');
    }
  }

  // 11. Футер: добавить ссылки на /apk/, /registration/, /promo-code/ если их ещё нет (статический деплой не использует тему WP)
  if (!/href=["']\/apk\/["']/.test(out) && /href=["']\/accounts-withdrawals-and-bonuses\/["']/.test(out)) {
    out = out.replace(
      /(<a\s+href=["']\/accounts-withdrawals-and-bonuses\/["'][^>]*>[^<]*<\/a>)\s*(<\/div>\s*<div\s+class=["']block-ul-footer["'][^>]*>)/,
      '$1\n\t\t\t\t\t\t\t<a href="/promo-code/">عرض الرمز الترويجي</a>\n\t\t\t\t\t\t\t<a href="/registration/">التسجيل</a>\n\t\t\t\t\t\t\t<a href="/apk/">تحميل التطبيق (APK)</a>$2'
    );
  }

  // 12. Удалить пустые строки после удаления скриптов
  out = out.replace(/\n\s*\n\s*\n/g, '\n\n');
  
  return out;
}

function main() {
  if (!fs.existsSync(DIST)) {
    console.error('dist/ not found. Run export first.');
    process.exit(1);
  }
  
  let processedHtml = 0;
  let processedCss = 0;
  
  // Оптимизировать HTML файлы
  walk(DIST, (filePath) => {
    const html = fs.readFileSync(filePath, 'utf8');
    const relativePath = path.relative(DIST, filePath);
    const optimized = optimizeHtml(html, relativePath);
    if (optimized !== html) {
      fs.writeFileSync(filePath, optimized, 'utf8');
      processedHtml++;
      console.log('Optimized HTML:', path.relative(DIST, filePath));
    }
  }, '.html');
  
  // Оптимизировать CSS файлы: font-display: swap для всех @font-face (Font Awesome woff2 и др. — PageSpeed)
  walk(DIST, (filePath) => {
    const css = fs.readFileSync(filePath, 'utf8');
    const optimized = optimizeCss(css);
    if (optimized !== css) {
      fs.writeFileSync(filePath, optimized, 'utf8');
      processedCss++;
      console.log('Optimized CSS:', path.relative(DIST, filePath));
    }
  }, '.css');
  
  console.log(`\nOptimization complete. Processed ${processedHtml} HTML files and ${processedCss} CSS files.`);
}

main();
