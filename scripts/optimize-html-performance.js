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
  // Страница /apk/: не переносить jQuery и инлайн в конец — нужен порядок jQuery → Owl → Fancybox → init для слайдеров
  const isApkPage = relativePath && /apk[\/\\]index\.html$/i.test(relativePath.replace(/\\/g, '/'));

  // 0. Собрать инлайн-скрипты с $/jQuery из body и перенести в конец (исправляет " $ is not defined")
  const inlineScriptsWithJQuery = [];
  if (!isApkPage) {
    out = out.replace(/<script(\s+[^>]*)?>([\s\S]*?)<\/script>/gi, (m, attrs, content) => {
      if (attrs && /\bsrc\s*=/.test(attrs)) return m;
      if (!/\$\(|jQuery\(/.test(content)) return m;
      const pos = out.indexOf(m);
      if (pos >= 0 && pos <= bodyStart) return m; // в head не трогаем (обернём в DOMContentLoaded)
      inlineScriptsWithJQuery.push(m);
      return '';
    });
  }

  // 1. Удалить дублирующиеся jQuery (оставить только один — тот, что идёт первым в документе)
  const jqueryRegex = /<script[^>]*src=["']([^"']*jquery[^"']*)["'][^>]*><\/script>/gi;
  const jqueryMatches = [];
  let match;
  while ((match = jqueryRegex.exec(html)) !== null) {
    jqueryMatches.push({ full: match[0], url: match[1], index: match.index });
  }
  if (jqueryMatches.length > 1) {
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
  // flexy-breadcrumb и prism (core/autoloader) — defer
  out = out.replace(
    /<script([^>]*src=["'][^"']*(?:flexy-breadcrumb-public\.js|prism-core\.min\.js|prism-autoloader\.min\.js)[^"']*["'][^>]*)><\/script>/gi,
    (m) => (/defer/.test(m) ? m : m.replace(/><\/script>/, ' defer></script>'))
  );
  
  // 4. Перенести jQuery и scripts.min.js в конец body с defer — НЕ для /apk/ (слайдеры требуют порядок: jQuery → Owl → init)
  if (!isApkPage) {
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
    // Оборачиваем jQuery(document).ready в DOMContentLoaded (jQuery теперь в конце body)
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
  }

  // 5. email-decode.min.js (cloudflare-static) — defer, убирает 482 ms из критической цепочки
  out = out.replace(
    /<script([^>]*src=["'][^"']*email-decode\.min\.js[^"']*["'][^>]*)><\/script>/gi,
    (m) => (/defer/.test(m) ? m : m.replace(/><\/script>/, ' defer></script>'))
  );

  // 5a. Удалить Owl Carousel (на статике используем Embla; убирает конфликты и вес)
  out = out.replace(/<script[^>]*src=["'][^"']*owl\.carousel[^"']*["'][^>]*><\/script>/gi, '');

  // 5a. Удалить скрипты, вызывающие ошибки (404 cdn-cgi/rum — убирает 1099 мс из цепочки, PageSpeed)
  out = out.replace(/<script[^>]*wp-emoji-release[^>]*><\/script>/gi, '');
  out = out.replace(/<script[^>]*cdn-cgi\/rum[^>]*><\/script>/gi, '');
  out = out.replace(/<script[^>]*src=["'][^"']*cdn-cgi[^"']*["'][^>]*><\/script>/gi, '');

  // 5b. Yandex Metrica — загружать асинхронно (не блокировать рендер, PageSpeed)
  out = out.replace(
    /<script([^>]*src=["'][^"']*mc\.yandex\.(ru|com)[^"']*["'][^>]*)><\/script>/gi,
    (m) => (/async/.test(m) ? m : m.replace(/><\/script>/, ' async></script>'))
  );

  // 5c. Preconnect — убрать пустой href, оставить макс. 2–3 (PageSpeed: не более 3–4)
  out = out.replace(/<link\s+rel=["']preconnect["'][^>]*href=["']["'][^>]*\/?>/gi, '');
  const preconnectRegex = /<link\s+rel=["']preconnect["'][^>]*href=["']([^"']+)["'][^>]*\/?>/gi;
  const preconnects = [];
  let m;
  while ((m = preconnectRegex.exec(out)) !== null) preconnects.push({ full: m[0], href: m[1] });
  const seen = new Set();
  const kept = [];
  for (const p of preconnects) {
    const origin = (p.href || '').replace(/\/$/, '').replace(/^(https?:\/\/[^/]+).*/, '$1');
    if (!origin || seen.has(origin)) continue;
    seen.add(origin);
    kept.push(p.full);
    if (kept.length >= 3) break;
  }
  preconnects.forEach(p => { out = out.replace(p.full, ''); });
  const toInsert = kept.length ? kept : [
    '<link rel="preconnect" href="' + BASE_URL + '">',
    '<link rel="preconnect" href="https://fonts.googleapis.com">',
    '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>'
  ].slice(0, 3);
  if (toInsert.length) {
    out = out.replace(/<head[^>]*>/i, '$&\n' + toInsert.join('\n') + '\n');
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

  // 11. Футер: добавить ссылки на /apk/, /registration/, /promo-code/ если их ещё нет
  if (!/href=["']\/apk\/["']/.test(out) && /href=["']\/accounts-withdrawals-and-bonuses\/["']/.test(out)) {
    out = out.replace(
      /(<a\s+href=["']\/accounts-withdrawals-and-bonuses\/["'][^>]*>[^<]*<\/a>)\s*(<\/div>\s*<div\s+class=["']block-ul-footer["'][^>]*>)/,
      '$1\n\t\t\t\t\t\t\t<a href="/promo-code/">عرض الرمز الترويجي</a>\n\t\t\t\t\t\t\t<a href="/registration/">التسجيل</a>\n\t\t\t\t\t\t\t<a href="/apk/">تحميل التطبيق (APK)</a>$2'
    );
  }
  // 11b. Футер: семантика и доступность — aria-label для навигации, гарантированные ссылки на системные страницы
  if (/<nav\s+class=["']navigation-footer["'][^>]*>/.test(out) && !/<nav[^>]*\saria-label=/.test(out)) {
    out = out.replace(
      /<nav\s+class=["']navigation-footer["']([^>]*)>/,
      '<nav class="navigation-footer" aria-label="روابط الموقع"$1>'
    );
  }
  const footerSlugs = ['contacts', 'about', 'terms', 'responsible', 'privacy-policy', 'self-exclusion', 'dispute-resolution', 'accounts-withdrawals-and-bonuses', 'fairness-rng-testing-methods'];
  const labels = { contacts: 'معلومات الاتصال', about: 'من نحن', terms: 'شروط الاستخدام', responsible: 'اللعب المسؤول', 'privacy-policy': 'الخصوصية وإدارة البيانات', 'self-exclusion': 'الاستبعاد الذاتي', 'dispute-resolution': 'حل النزاعات', 'accounts-withdrawals-and-bonuses': 'الحسابات والمدفوعات والمكافآت', 'fairness-rng-testing-methods': 'طرق اختبار النزاهة والعشوائية' };
  const missing = footerSlugs.filter(slug => !new RegExp('href=["\']/' + slug.replace(/-/g, '[-]') + '/["\']', 'i').test(out));
  if (missing.length && /<div\s+class=["']block-ul-footer["'][^>]*>\s*<strong>حول الموقع<\/strong>/.test(out)) {
    const insert = missing.map(slug => '<a href="/' + slug + '/">' + (labels[slug] || slug) + '</a>').join('\n\t\t\t\t\t\t\t');
    out = out.replace(
      /(<div\s+class=["']block-ul-footer["'][^>]*>\s*<strong>حول الموقع<\/strong>)/,
      '$1\n\t\t\t\t\t\t\t' + insert
    );
  }

  // 11c. Футер "حول الموقع": оставить только уникальные ссылки (дедупликация по href)
  out = out.replace(
    /(<div\s+class=["']block-ul-footer["'][^>]*>\s*<strong>حول الموقع<\/strong>)([\s\S]*?)(<\/div>)/,
    function (match, open, linksContent, close) {
      const linkRegex = /<a\s+[^>]*href=["']([^"']*)["'][^>]*>[^<]*<\/a>/g;
      const seen = new Set();
      const unique = [];
      let m;
      while ((m = linkRegex.exec(linksContent)) !== null) {
        const href = (m[1] || '').replace(/\s+/g, '');
        if (seen.has(href)) continue;
        seen.add(href);
        unique.push(m[0]);
      }
      return open + (unique.length ? '\n\t\t\t\t\t\t\t' + unique.join('\n\t\t\t\t\t\t\t') : '') + '\n\t\t\t\t\t\t\t' + close;
    }
  );

  // 12. Меню: добавить ссылки на /promo-code/, /registration/, /apk/ (как на 888starz-africa.com, с нашим доменом)
  if (!/href=["']\/promo-code\/["']/.test(out)) {
    const menuLiPromo = /(<li[^>]*>[\s\S]*?<a[^>]*>[\s\S]*?قسيمة[\s\S]*?<\/a>\s*<\/li>)/i;
    const menuLiCazino = /(<li[^>]*>[\s\S]*?<a[^>]*>[\s\S]*?كازينو[\s\S]*?<\/a>\s*<\/li>)/i;
    const extra = '<li class="menu-item"><a href="/promo-code/">عرض الرمز الترويجي</a></li><li class="menu-item"><a href="/registration/">التسجيل</a></li><li class="menu-item"><a href="/apk/">تحميل التطبيق (APK)</a></li>';
    if (menuLiPromo.test(out)) {
      out = out.replace(menuLiPromo, '$1' + extra);
    } else if (menuLiCazino.test(out)) {
      out = out.replace(menuLiCazino, '$1' + extra);
    }
  }

  // 13. A11y: owl-dot / carousel buttons — aria-label (Lighthouse)
  let dotIndex = 0;
  out = out.replace(/<(button|span)([^>]*class=["'][^"']*owl-dot[^"']*["'][^>]*)>/gi, (m, tag, attrs) => {
    if (/aria-label\s*=/.test(attrs)) return m;
    dotIndex++;
    const role = (tag === 'span' && !/role\s*=/.test(attrs)) ? ' role="button"' : '';
    return '<' + tag + attrs + ' aria-label="Slide ' + dotIndex + '"' + role + '>';
  });

  // 14. «Чёрный блок» облака тегов: раскрытие по клику (pure JS, без jQuery)
  if (/id=["']seo-module["']/.test(out) && /caption-seo-module\s+faq-question/.test(out) && !/caption-seo-module.*addEventListener/.test(out)) {
    const tagToggleScript = '<script>(function(){var q=document.querySelector(".caption-seo-module.faq-question");var a=document.getElementById("seo-module");if(q&&a){q.setAttribute("role","button");q.setAttribute("aria-expanded","false");q.setAttribute("aria-controls","seo-module");q.addEventListener("click",function(e){e.stopImmediatePropagation();var open=a.style.display==="block";a.style.display=open?"none":"block";q.setAttribute("aria-expanded",!open);q.classList.toggle("open",!open);var r=q.querySelector(".ico_rotater_footer");if(r)r.classList.toggle("rotate",!open);},true);}})();</script>';
    out = out.replace('</body>', tagToggleScript + '\n</body>');
  }

  // 15. Убрать пустые пространства на главной: пустые параграфы и пустые/высокие .table-wrapper
  out = out.replace(/<p(\s[^>]*)?>\s*(&nbsp;|&#160;|\s)*\s*<\/p>/gi, '');
  out = out.replace(/<p>\s*<\/p>/gi, '');
  // Пустые div.table-wrapper (без таблицы внутри) — удалить, в т.ч. вложенные
  for (let i = 0; i < 8; i++) {
    out = out.replace(/<div\s+[^>]*class=["'][^"']*table-wrapper[^"']*["'][^>]*>\s*<\/div>/gi, '');
  }
  // Ограничить высоту .table-wrapper, чтобы не создавать огромные пустые зоны
  if (!/\.table-wrapper\s*\{\s*min-height:\s*0/.test(out)) {
    const tableWrapperFix = '<style>.table-wrapper{min-height:0 !important;}.table-wrapper:empty{display:none !important;}</style>';
    out = out.replace('</head>', tableWrapperFix + '\n</head>');
  }

  // 16. Удалить пустые строки после удаления скриптов
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
