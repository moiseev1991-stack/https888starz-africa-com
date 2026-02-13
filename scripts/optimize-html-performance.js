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
  
  // Добавить font-display: swap для всех @font-face правил (особенно Font Awesome)
  out = out.replace(
    /(@font-face\s*\{[^}]*)(\})/gi,
    (match, before, closing) => {
      if (/font-display/.test(before)) {
        return match; // Уже есть font-display
      }
      // Добавить font-display: swap перед закрывающей скобкой
      return before + '\n  font-display: swap;' + closing;
    }
  );
  
  return out;
}

function optimizeHtml(html) {
  let out = html;
  
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
  
  // 3. Добавить defer для скриптов которые не критичны (owl.carousel, fancybox, и т.д.)
  // Но НЕ для jQuery (он должен загружаться первым)
  out = out.replace(
    /<script([^>]*src=["'][^"']*(?:owl|fancybox|carousel)[^"']*["'][^>]*)><\/script>/gi,
    '<script$1 defer></script>'
  );
  
  // 4. НЕ добавляем defer ко всем скриптам — только к owl/fancybox выше, чтобы не сломать порядок (jQuery должен загрузиться до scripts.min.js и inline-скриптов с $).

  // 5. Удалить скрипты, вызывающие ошибки на статическом сайте
  out = out.replace(/<script[^>]*wp-emoji-release[^>]*><\/script>/gi, '');
  out = out.replace(/<script[^>]*cdn-cgi\/rum[^>]*><\/script>/gi, '');

  // 5b. Yandex Metrica — загружать асинхронно (не блокировать рендер)
  out = out.replace(
    /<script([^>]*src=["'][^"']*mc\.yandex\.ru[^"']*["'][^>]*)><\/script>/gi,
    (m) => (/defer|async/.test(m) ? m : m.replace(/><\/script>/, ' defer></script>'))
  );

  // 6. Добавить font-display: swap для Font Awesome через inline style в head
  // Если есть font-awesome CSS, добавить правило font-display
  if (/font-awesome/.test(out) && !/font-display.*swap/.test(out)) {
    const fontAwesomeFix = `
<style>
@font-face {
  font-family: 'FontAwesome';
  font-display: swap;
}
@font-face {
  font-family: 'Font Awesome 5 Free';
  font-display: swap;
}
@font-face {
  font-family: 'Font Awesome 5 Brands';
  font-display: swap;
}
</style>`;
    // Вставить перед закрывающим </head>
    out = out.replace('</head>', fontAwesomeFix + '\n</head>');
  }
  
  // 6. Оптимизировать загрузку CSS: preload для не-критических CSS
  // Font Awesome, plugins.min.css, fancybox - не критичны, можно загрузить асинхронно
  const nonCriticalCss = ['font-awesome', 'plugins\\.min', 'fancybox', 'flexy-breadcrumb'];
  nonCriticalCss.forEach(pattern => {
    const regex = new RegExp(`<link([^>]*rel=["']stylesheet["'][^>]*href=["'][^"']*${pattern}[^"']*["'][^>]*)>`, 'gi');
    out = out.replace(regex, (match) => {
      if (/preload|onload/.test(match)) return match; // Уже оптимизирован
      const hrefMatch = match.match(/href=["']([^"']+)["']/);
      if (hrefMatch) {
        const href = hrefMatch[1];
        return `<link rel="preload" href="${href}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="${href}"></noscript>`;
      }
      return match;
    });
  });

  // 8. Google Fonts — сделать неблокирующими (preload + onload)
  out = out.replace(
    /<link([^>]*href=["']https?:\/\/fonts\.googleapis\.com\/[^"']*["'][^>]*)>/gi,
    (match) => {
      if (/rel=["']preload["']/.test(match)) return match;
      const hrefMatch = match.match(/href=["']([^"']+)["']/);
      if (hrefMatch) {
        const href = hrefMatch[1];
        return `<link rel="preload" href="${href}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="${href}"></noscript>`;
      }
      return match;
    }
  );
  
  // 9. Удалить пустые строки после удаления скриптов
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
    const optimized = optimizeHtml(html);
    if (optimized !== html) {
      fs.writeFileSync(filePath, optimized, 'utf8');
      processedHtml++;
      console.log('Optimized HTML:', path.relative(DIST, filePath));
    }
  }, '.html');
  
  // Оптимизировать CSS файлы (добавить font-display: swap)
  walk(DIST, (filePath) => {
    // Обрабатываем только font-awesome CSS
    if (!/font-awesome/i.test(filePath)) return;
    
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
