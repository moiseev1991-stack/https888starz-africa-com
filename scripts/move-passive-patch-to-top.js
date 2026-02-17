const fs = require('fs');
const path = require('path');

const htmlFiles = [
  'public/about/index.html',
  'public/registration/index.html',
  'public/dispute-resolution/index.html',
  'public/apk/index.html',
  'public/promo-code/index.html',
  'public/terms/index.html',
  'public/self-exclusion/index.html',
  'public/accounts-withdrawals-and-bonuses/index.html',
  'public/fairness-rng-testing-methods/index.html',
  'public/contacts/index.html',
  'public/responsible/index.html',
  'public/privacy-policy/index.html',
  'public/cookies/index.html'
];

const scriptStartPattern = /<script>[\s\S]*?(?=\/\/ Исправить пустые ссылки|function fixEmptyTocLinks)/;

const newScriptStart = `<script>
\t// Патч для устранения предупреждений о non-passive event listeners
\t// Применяем глобально ДО загрузки jQuery и Owl Carousel
\t(function() {
\t\tif (typeof EventTarget !== 'undefined' && EventTarget.prototype.addEventListener) {
\t\t\tvar originalAddEventListener = EventTarget.prototype.addEventListener;
\t\t\tEventTarget.prototype.addEventListener = function(type, listener, options) {
\t\t\t\t// Для touchstart событий автоматически добавляем passive: true, если не указано явно
\t\t\t\tif (type === 'touchstart' || type === 'touchmove' || type === 'touchend') {
\t\t\t\t\tif (typeof options === 'object' && options !== null) {
\t\t\t\t\t\tif (options.passive === undefined) {
\t\t\t\t\t\t\toptions.passive = true;
\t\t\t\t\t\t}
\t\t\t\t\t} else if (typeof options === 'boolean') {
\t\t\t\t\t\t// Если options - это useCapture (boolean), создаем объект с passive
\t\t\t\t\t\toptions = { capture: options, passive: true };
\t\t\t\t\t} else {
\t\t\t\t\t\t// Если options не указан, используем passive: true
\t\t\t\t\t\toptions = { passive: true };
\t\t\t\t\t}
\t\t\t\t}
\t\t\t\treturn originalAddEventListener.call(this, type, listener, options);
\t\t\t};
\t\t}
\t})();

\t// Исправить пустые ссылки в custom-toc (accessibility fix)`;

const oldPatchPattern = /\t\/\/ Патч для устранения предупреждений о non-passive event listeners[\s\S]*?\t\t\}\s*\}\)\(\);\s*\n\tfunction initOwlAndFancybox/;

htmlFiles.forEach(file => {
  const filePath = path.join(__dirname, '..', file);
  if (!fs.existsSync(filePath)) {
    console.log(`File not found: ${file}`);
    return;
  }
  
  let content = fs.readFileSync(filePath, 'utf8');
  let modified = false;
  
  // Удалить старый патч перед initOwlAndFancybox, если он есть
  if (oldPatchPattern.test(content)) {
    content = content.replace(oldPatchPattern, '\tfunction initOwlAndFancybox');
    modified = true;
  }
  
  // Добавить патч в начало скрипта
  if (content.includes('<script>') && 
      !content.includes('// Патч для устранения предупреждений о non-passive event listeners\n\t// Применяем глобально ДО загрузки jQuery')) {
    const scriptTagPattern = /(<script>[\s\S]*?)(\/\/ Исправить пустые ссылки|function fixEmptyTocLinks)/;
    if (scriptTagPattern.test(content)) {
      content = content.replace(scriptTagPattern, (match, p1, p2) => {
        if (p1.includes('// Патч')) {
          return match; // Уже есть патч
        }
        return newScriptStart + '\n\t' + p2;
      });
      modified = true;
    }
  }
  
  if (modified) {
    fs.writeFileSync(filePath, content, 'utf8');
    console.log(`Updated: ${file}`);
  } else {
    console.log(`No changes needed: ${file}`);
  }
});

console.log('Done!');
