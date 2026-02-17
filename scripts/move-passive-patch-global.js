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

const oldPatch = `\tfunction initOwlAndFancybox() {
\t\tvar $ = window.jQuery;
\t\tif (!$ || !$.fn.owlCarousel) return;
\t\t
\t\t// Патч для устранения предупреждений о non-passive event listeners
\t\t// Автоматически добавляем passive: true для touchstart событий
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
\t\t}`;

const newPatch = `\t// Патч для устранения предупреждений о non-passive event listeners
\t// Применяем глобально до инициализации любых слайдеров
\t(function() {
\t\tif (typeof EventTarget !== 'undefined' && EventTarget.prototype.addEventListener) {
\t\t\tvar originalAddEventListener = EventTarget.prototype.addEventListener;
\t\t\tvar patched = false;
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

\tfunction initOwlAndFancybox() {
\t\tvar $ = window.jQuery;
\t\tif (!$ || !$.fn.owlCarousel) return;`;

htmlFiles.forEach(file => {
  const filePath = path.join(__dirname, '..', file);
  if (!fs.existsSync(filePath)) {
    console.log(`File not found: ${file}`);
    return;
  }
  
  let content = fs.readFileSync(filePath, 'utf8');
  let modified = false;
  
  // Заменить патч внутри функции на глобальный патч перед функцией
  if (content.includes('// Патч для устранения предупреждений о non-passive event listeners') &&
      content.includes('function initOwlAndFancybox()')) {
    const pattern = /function initOwlAndFancybox\(\) \{[\s\S]*?var \$ = window\.jQuery;[\s\S]*?if \(\!\$ \|\| \!\$\.fn\.owlCarousel\) return;[\s\S]*?\/\/ Патч для устранения предупреждений о non-passive event listeners[\s\S]*?return originalAddEventListener\.call\(this, type, listener, options\);[\s\S]*?\};[\s\S]*?\}[\s\S]*?\t\t\/\/ Восстановить/;
    if (pattern.test(content)) {
      content = content.replace(pattern, newPatch + '\n\t\t// Восстановить');
      modified = true;
    } else {
      // Попробуем более простой паттерн
      const simplePattern = /function initOwlAndFancybox\(\) \{[\s\S]*?\/\/ Патч для устранения предупреждений о non-passive event listeners[\s\S]*?\};[\s\S]*?\}[\s\S]*?\t\t\/\/ Восстановить/;
      if (simplePattern.test(content)) {
        content = content.replace(simplePattern, newPatch + '\n\t\t// Восстановить');
        modified = true;
      }
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
