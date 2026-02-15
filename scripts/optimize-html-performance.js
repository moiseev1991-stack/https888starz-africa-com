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

const JQUERY_CDN = 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js';
const FANCYBOX_CDN = 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js';

function optimizeHtml(html, relativePath) {
  let out = html;
  const bodyStart = out.indexOf('</head>');

  // 0. Сразу удалить скрипты, которые на статике дают 404 (HTML вместо JS → Unexpected token '<')
  // Обычный и многострочный тег (атрибуты с переносами)
  [
    /<script[\s\S]*?src=["'][^"']*wp-includes[^"']*["'][\s\S]*?<\/script>/gi,
    /<script[\s\S]*?src=["'][^"']*wp-admin[^"']*["'][\s\S]*?<\/script>/gi,
    /<script[\s\S]*?src=["'][^"']*owl\.carousel[^"']*["'][\s\S]*?<\/script>/gi,
    /<script[\s\S]*?src=["'][^"']*cdn-cgi[^"']*["'][\s\S]*?<\/script>/gi
  ].forEach(re => { out = out.replace(re, ''); });
  out = out.replace(/<script[^>]*src=["'][^"']*wp-includes[^"']*["'][^>]*><\/script>/gi, '');
  out = out.replace(/<script[^>]*src=["'][^"']*wp-admin[^"']*["'][^>]*><\/script>/gi, '');
  out = out.replace(/<script[^>]*src=["'][^"']*owl\.carousel[^"']*["'][^>]*><\/script>/gi, '');
  out = out.replace(/<script[^>]*src=["'][^"']*cdn-cgi[^"']*["'][^>]*><\/script>/gi, '');

  // 0. Домен: если краулили Africa, подменить на Egypt в ссылках/канонике (слайдер и разметка с Africa)
  out = out.replace(/https?:\/\/888starz-africa\.com/gi, BASE_URL);

  // 0a. jQuery: CDN + убрать defer (чтобы $ был до скриптов с $)
  out = out.replace(
    /<script([^>]*src=["'])([^"']*jquery[^"']*\.min\.js[^"']*)(["'][^>]*)><\/script>/gi,
    (m, before, src, after) => {
      if (/^https?:\/\//i.test(src)) {
        return m.replace(/\s+defer\s*/gi, ' ');
      }
      const attrs = after.replace(/\s+defer\s*/gi, ' ');
      return '<script' + before + JQUERY_CDN + attrs + '></script>';
    }
  );
  out = out.replace(/(<script[^>]*src=["'][^"']*jquery[^"']*["'][^>]*)\s+defer(\s[^>]*)?>/gi, '$1$2>');
  // 0b. Убрать битые preload (flexy-breadcrumb, font-awesome)
  out = out.replace(/<link[^>]*rel=["']preload["'][^>]*href=["'][^"']*flexy-breadcrumb[^"']*["'][^>]*>\s*/gi, '');
  out = out.replace(/<link[^>]*rel=["']preload["'][^>]*href=["'][^"']*font-awesome\.min\.css[^"']*["'][^>]*>\s*/gi, '');
  out = out.replace(/<noscript><link[^>]*flexy-breadcrumb[^>]*><\/noscript>\s*/gi, '');
  out = out.replace(/<noscript><link[^>]*font-awesome\.min\.css[^>]*><\/noscript>\s*/gi, '');
  // 0c. Не менять число }); в скриптах — любая замена ломает переключатель языка (Unexpected end of input). Отключено.
  // 0c2. Статика: заглушка ajax_var.url (admin-ajax.php на домене даёт 404/HTML → JSON и раскрытие блоков не работают)
  out = out.replace(/"url"\s*:\s*"[^"]*admin-ajax[^"]*"/gi, '"url":""');
  out = out.replace(/"url"\s*:\s*"[^"]*wp-json[^"]*"/gi, '"url":""');
  // 0d. Удалить flexy-breadcrumb-public.js и wp-emoji (404)
  out = out.replace(/<script[^>]*src=["'][^"']*flexy-breadcrumb-public\.js[^"']*["'][^>]*><\/script>\s*/gi, '');
  out = out.replace(/<script id="wp-emoji-settings" type="application\/json">[\s\S]*?<\/script>\s*/i, '');
  out = out.replace(/<script type="module">[\s\S]*?wp-emoji-loader[\s\S]*?<\/script>\s*/gi, '');
  // 0e. Удалить локальный Fancybox (404 на статике → Unexpected token '<'), вставить CDN после jQuery в шаге 4
  out = out.replace(/<script[^>]*src=["'][^"']*fancybox[^"']*["'][^>]*><\/script>\s*/gi, '');
  out = out.replace(/\$\(\'\[data-fancybox="gallery"\][^\']*\'\)\.fancybox\s*\(/g, "if($.fn.fancybox)$('[data-fancybox=\"gallery\"]:not(.owl-item.cloned [data-fancybox=\"gallery\"])').fancybox(");
  out = out.replace(/\$\(\'\[data-fancybox="gallerymob"\][^\']*\'\)\.fancybox\s*\(/g, "if($.fn.fancybox)$('[data-fancybox=\"gallerymob\"]:not(.owl-item.cloned [data-fancybox=\"gallerymob\"])').fancybox(");

  const isApkPage = relativePath && /apk[\/\\]index\.html$/i.test(relativePath.replace(/\\/g, '/'));

  // 0. Не вырезаем инлайн-скрипты (regex ломает код с "</script>" в строке → SyntaxError). jQuery просто вставим перед первым скриптом с $.

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
  
  // 3. Добавить defer для скриптов которые не критичны (owl, fancybox, prism — не блокируют рендер). Embla не трогаем — без defer слайдер инициализируется сразу.
  out = out.replace(
    /<script([^>]*src=["']([^"']*)["'][^>]*)><\/script>/gi,
    (m, attrs, src) => {
      if (/embla/.test(src)) return m;
      if (/(?:owl|fancybox|carousel)/.test(src) && !/embla/.test(src))
        return /defer/.test(attrs) ? m : '<script' + attrs + ' defer></script>';
      return m;
    }
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
  
  // 4. jQuery и Fancybox сразу после <head>; заглушка ajax_var; owlCarousel no-op (несколько уровней)
  {
    const jqueryTagMatch = out.match(/<script[^>]*src=["'][^"']*jquery[^"']*\.min\.js[^"']*["'][^>]*><\/script>/i);
    const jqueryTag = jqueryTagMatch ? jqueryTagMatch[0].replace(/\s*defer\s*/gi, ' ') : null;
    if (jqueryTag && /<head[\s>]/i.test(out)) {
      const fancyboxTag = out.includes('.fancybox(') ? '<script src="' + FANCYBOX_CDN + '"></script>' : '';
      const ajaxStub = '<script>window.ajax_var=window.ajax_var||{url:"",nonce:"",assets_folder:""};</script>';
      const owlNoop = '<script>(function(){function set(){var $=window.jQuery;if($&&!$.fn.owlCarousel)$.fn.owlCarousel=function(){return this;};}set();if(document.readyState==="loading")document.addEventListener("DOMContentLoaded",set);window.addEventListener("load",set);})();</script>';
      const block = jqueryTag + '\n' + fancyboxTag + '\n' + ajaxStub + '\n' + owlNoop;
      out = out.replace(jqueryTagMatch[0], '');
      out = out.replace(/<head(\s[^>]*)?>/i, '<head$1>\n' + block + '\n');
    }
  }

  // 5. email-decode.min.js (cloudflare-static) — defer, убирает 482 ms из критической цепочки
  out = out.replace(
    /<script([^>]*src=["'][^"']*email-decode\.min\.js[^"']*["'][^>]*)><\/script>/gi,
    (m) => (/defer/.test(m) ? m : m.replace(/><\/script>/, ' defer></script>'))
  );

  // 5a. Повторно удалить Owl/wp-includes/wp-admin (на случай если в шаге 0 не сматчилось из-за порядка атрибутов)
  out = out.replace(/<script[^>]*src=["'][^"']*owl\.carousel[^"']*["'][^>]*><\/script>/gi, '');
  out = out.replace(/<script[^>]*src=["'][^"']*wp-includes[^"']*["'][^>]*><\/script>/gi, '');
  out = out.replace(/<script[^>]*src=["'][^"']*wp-admin[^"']*["'][^>]*><\/script>/gi, '');
  // Убрать неиспользуемые селекторы Owl из JS (owl-item.cloned уже не существует)
  out = out.replace(/:not\(\.owl-item\.cloned\s+\[data-fancybox="gallery"\]\)/gi, '');
  out = out.replace(/:not\(\.owl-item\.cloned\s+\[data-fancybox="gallerymob"\]\)/gi, '');
  // Удалить пустые HTML-комментарии W3TC (не используются без плагина)
  out = out.replace(/<!--\s*W3TC-include-css\s*-->\s*/gi, '');
  out = out.replace(/<!--\s*W3TC-include-js-head\s*-->\s*/gi, '');

  // 5a. Удалить скрипты/ссылки и инлайн-биконы, вызывающие 404 (cdn-cgi/rum — PageSpeed, POST 404)
  out = out.replace(/<script[^>]*wp-emoji-release[^>]*><\/script>/gi, '');
  out = out.replace(/<script[^>]*cdn-cgi\/rum[^>]*><\/script>/gi, '');
  out = out.replace(/<script[^>]*src=["'][^"']*cdn-cgi[^"']*["'][^>]*><\/script>/gi, '');
  out = out.replace(/<link[^>]*href=["'][^"']*cdn-cgi[^"']*["'][^>]*>\s*/gi, '');
  out = out.replace(/<script([^>]*)>([\s\S]*?cdn-cgi\/rum[\s\S]*?)<\/script>/gi, (m, attrs, content) => (content.length < 4000 ? '' : m));
  // Заглушка sendBeacon/fetch/XHR к cdn-cgi/rum — убрать POST 404 в консоли (Cloudflare RUM на статике недоступен)
  if (!out.includes('cdn-cgi-rum-stub')) {
    const rumStub = '<script id="cdn-cgi-rum-stub">(function(){var u="cdn-cgi/rum";if(typeof navigator!=="undefined"&&navigator.sendBeacon){var b=navigator.sendBeacon.bind(navigator);navigator.sendBeacon=function(url){if(String(url).indexOf(u)!==-1)return true;return b.apply(this,arguments);};}if(typeof window.fetch==="function"){var f=window.fetch;window.fetch=function(url){if(typeof url==="string"&&url.indexOf(u)!==-1)return Promise.resolve(new Response(null,{status:204}));return f.apply(this,arguments);};}var X=window.XMLHttpRequest;if(X){var op=X.prototype.open;X.prototype.open=function(method,url){if(String(url).indexOf(u)!==-1){this._skipRum=true;return op.apply(this,["GET","about:blank"]);}return op.apply(this,arguments);};}})();</script>';
    out = out.replace(/<head(\s[^>]*)?>/i, '$&\n' + rumStub + '\n');
  }

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

  // 5d. Последний шанс owlCarousel no-op перед </body> (если инлайн в body выполнился до head — редко)
  if (/\.owlCarousel\s*\(/.test(out) && !out.includes('owl-noop-body')) {
    out = out.replace('</body>', '<script id="owl-noop-body">(function(){var $=window.jQuery;if($&&!$.fn.owlCarousel)$.fn.owlCarousel=function(){return this;};})();</script>\n</body>');
  }
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

  // 12a. Слайдер: один ряд точек — удалить ВТОРОЙ блок .owl-dots с 10+ точками (дубль), чтобы точки привязаны к десктопному hero и клики работают
  (function () {
    const owlDotsStart = /<div\s+[^>]*class=["'][^"']*owl-dots[^"']*["'][^>]*>/gi;
    let pos = 0;
    let bigDotsBlockIndex = 0;
    while (pos < out.length) {
      const m = owlDotsStart.exec(out);
      if (!m) break;
      const start = m.index;
      let depth = 0;
      let p = start;
      let end = -1;
      while (p < out.length) {
        const nextOpen = out.indexOf('<div', p);
        const nextClose = out.indexOf('</div>', p);
        if (nextClose === -1) break;
        if (nextOpen !== -1 && nextOpen < nextClose) {
          depth++;
          p = nextOpen + 4;
        } else {
          depth--;
          p = nextClose + 6;
          if (depth === 0) {
            end = p;
            break;
          }
        }
      }
      if (end === -1) break;
      const block = out.slice(start, end);
      const dotCount = (block.match(/class=["'][^"']*owl-dot[^"']*["']/g) || []).length;
      if (dotCount >= 10) {
        bigDotsBlockIndex++;
        if (bigDotsBlockIndex === 2) {
          out = out.slice(0, start) + out.slice(end);
          owlDotsStart.lastIndex = start;
          pos = start;
          continue;
        }
      }
      if (dotCount > 10) {
        const inner = block.replace(/^<div[^>]*>/, '').replace(/<\/div>$/, '');
        const buttonRe = /<button[^>]*class=["'][^"']*owl-dot[^"']*["'][^>]*>[\s\S]*?<\/button>/gi;
        const buttons = [];
        let b;
        while ((b = buttonRe.exec(inner)) !== null && buttons.length < 10) {
          buttons.push(b[0]);
        }
        const openTag = block.slice(0, block.indexOf('>') + 1);
        const newBlock = openTag + buttons.join('') + '</div>';
        out = out.slice(0, start) + newBlock + out.slice(end);
        owlDotsStart.lastIndex = start + newBlock.length;
      } else {
        owlDotsStart.lastIndex = end;
      }
      pos = end;
    }
  })();

  // 13. A11y: owl-dot / carousel buttons — aria-label (Lighthouse)
  let dotIndex = 0;
  out = out.replace(/<(button|span)([^>]*class=["'][^"']*owl-dot[^"']*["'][^>]*)>/gi, (m, tag, attrs) => {
    if (/aria-label\s*=/.test(attrs)) return m;
    dotIndex++;
    const role = (tag === 'span' && !/role\s*=/.test(attrs)) ? ' role="button"' : '';
    return '<' + tag + attrs + ' aria-label="Slide ' + dotIndex + '"' + role + '>';
  });

  // 13b. Увеличить размер точек слайдера (круги побольше)
  if (!out.includes('data-owl-dots-size-fix')) {
    const dotStyle = '<style id="owl-dots-size-fix" data-owl-dots-size-fix>.owl-dots .owl-dot { width: 16px; height: 16px; padding: 0; margin: 0 6px; border-radius: 50%; } .owl-dots .owl-dot span { display: block; width: 12px; height: 12px; margin: 0 auto; border-radius: 50%; } .owl-dots .owl-dot.active span { transform: scale(1.15); }</style>';
    out = out.replace('</head>', dotStyle + '\n</head>');
  }
  // 13c. Клик по точкам слайдера (Owl to.owl.carousel) — только если Owl не удалён (на статике используем Embla, точки вешает carousel-embla.js)
  const owlScriptRemoved = !/<script[^>]*src=["'][^"']*owl\.carousel[^"']*["']/.test(out);
  const hasEmbla = /carousel-embla\.js|embla-carousel\.umd\.js/.test(out);
  if (out.includes('owl-dots') && !out.includes('owl-dots-click-fix') && !(owlScriptRemoved && hasEmbla)) {
    const owlDotsClickFix = '<script id="owl-dots-click-fix">(function(){function run(){var $=window.jQuery;if(!$||!$.fn.owlCarousel)return;$(document).off("click.owlDotsFix").on("click.owlDotsFix",".owl-dots .owl-dot",function(e){var $dot=$(this),$dots=$dot.closest(".owl-dots"),$carousel=$dots.closest(".owl-carousel, .owl-mobile");if(!$carousel.length)return;var idx=$dots.find(".owl-dot").index($dot);if(idx>=0){e.preventDefault();e.stopPropagation();$carousel.trigger("to.owl.carousel",[idx,300]);}});}if(document.readyState==="loading")document.addEventListener("DOMContentLoaded",function(){setTimeout(run,400);});else setTimeout(run,400);window.addEventListener("load",function(){setTimeout(run,600);});})();</script>';
    out = out.replace('</body>', owlDotsClickFix + '\n</body>');
  }
  // 14. «Чёрный блок» облака тегов: раскрытие по клику (pure JS)
  if (/id=["']seo-module["']/.test(out) && /caption-seo-module\s+faq-question/.test(out) && !/caption-seo-module.*addEventListener/.test(out)) {
    const tagToggleScript = '<script>(function(){var q=document.querySelector(".caption-seo-module.faq-question");var a=document.getElementById("seo-module");if(q&&a){q.setAttribute("role","button");q.setAttribute("aria-expanded","false");q.setAttribute("aria-controls","seo-module");q.addEventListener("click",function(e){e.stopImmediatePropagation();var open=a.style.display==="block";a.style.display=open?"none":"block";q.setAttribute("aria-expanded",!open);q.classList.toggle("open",!open);var r=q.querySelector(".ico_rotater_footer");if(r)r.classList.toggle("rotate",!open);},true);}})();</script>';
    out = out.replace('</body>', tagToggleScript + '\n</body>');
  }
  // 15. Пустые параграфы и .table-wrapper
  out = out.replace(/<p(\s[^>]*)?>\s*(&nbsp;|&#160;|\s)*\s*<\/p>/gi, '');
  out = out.replace(/<p>\s*<\/p>/gi, '');
  for (let i = 0; i < 8; i++) {
    out = out.replace(/<div\s+[^>]*class=["'][^"']*table-wrapper[^"']*["'][^>]*>\s*<\/div>/gi, '');
  }
  out = out.replace(/(<div\s+[^>]*class=["'][^"']*table-wrapper[^"']*["'][^>]*>\s*)+/gi, '<div class="table-wrapper">');
  out = out.replace(/(<\/table>\s*<\/div>)(\s*<\/div>)+/gi, '$1');
  out = out.replace(/<table(\s+[^>]*)\s+width=["']0["']/gi, '<table$1 width="100%"');
  out = out.replace(/<table\s+width=["']0["'](\s+[^>]*)/gi, '<table width="100%"$1');
  if (!/\.table-wrapper\s*\{\s*min-height:\s*0/.test(out)) {
    const tableWrapperFix = '<style>.table-wrapper{min-height:0 !important;}.table-wrapper:empty{display:none !important;}</style>';
    out = out.replace('</head>', tableWrapperFix + '\n</head>');
  }
  // 16. Удалить пустые строки
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
