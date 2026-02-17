const fs = require('fs');
const path = require('path');

const htmlFiles = [
  'public/index.html',
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

const oldPatch = `\t// Патч для устранения предупреждений о non-passive event listeners
\t// Применяем ДО загрузки jQuery и Owl Carousel
\t(function() {
\t\tif (typeof EventTarget !== 'undefined' && EventTarget.prototype.addEventListener) {
\t\t\tvar originalAddEventListener = EventTarget.prototype.addEventListener;
\t\t\tEventTarget.prototype.addEventListener = function(type, listener, options) {
\t\t\t\t// Для touchstart событий автоматически добавляем passive: true, если не указано явно
\t\t\t\tif (type === 'touchstart' || type === 'touchmove' || type === 'touchend') {
\t\t\t\t\tif (typeof options === 'object' && options !== null) {
\t\t\t\t\t\t// Если passive не указан явно (ни true, ни false), устанавливаем true
\t\t\t\t\t\tif (!('passive' in options)) {
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
\t})();`;

const newPatch = `\t// Патч для устранения предупреждений о non-passive event listeners
\t// Применяем ДО загрузки jQuery и Owl Carousel
\t// Исключаем элементы слайдеров, чтобы они могли использовать preventDefault()
\t(function() {
\t\tif (typeof EventTarget !== 'undefined' && EventTarget.prototype.addEventListener) {
\t\t\tvar originalAddEventListener = EventTarget.prototype.addEventListener;
\t\t\tEventTarget.prototype.addEventListener = function(type, listener, options) {
\t\t\t\t// Проверяем, является ли элемент частью слайдера
\t\t\t\tvar isSliderElement = false;
\t\t\t\tif (this && this.nodeType === 1) { // Element node
\t\t\t\t\tvar element = this;
\t\t\t\t\t// Проверяем классы и родительские элементы
\t\t\t\t\tif (element.classList) {
\t\t\t\t\t\tisSliderElement = element.classList.contains('owl-carousel') ||
\t\t\t\t\t\t\telement.classList.contains('owl-mobile') ||
\t\t\t\t\t\t\telement.classList.contains('owl-stage') ||
\t\t\t\t\t\t\telement.classList.contains('owl-stage-outer') ||
\t\t\t\t\t\t\telement.classList.contains('owl-item') ||
\t\t\t\t\t\t\telement.closest && (
\t\t\t\t\t\t\t\telement.closest('.owl-carousel') ||
\t\t\t\t\t\t\t\telement.closest('.owl-mobile') ||
\t\t\t\t\t\t\t\telement.closest('.owl-stage-outer')
\t\t\t\t\t\t);
\t\t\t\t\t}
\t\t\t\t}
\t\t\t\t
\t\t\t\t// Для touchstart событий автоматически добавляем passive: true, если не указано явно
\t\t\t\t// НО НЕ для элементов слайдеров, чтобы они могли использовать preventDefault()
\t\t\t\tif ((type === 'touchstart' || type === 'touchmove' || type === 'touchend') && !isSliderElement) {
\t\t\t\t\tif (typeof options === 'object' && options !== null) {
\t\t\t\t\t\t// Если passive не указан явно (ни true, ни false), устанавливаем true
\t\t\t\t\t\tif (!('passive' in options)) {
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
\t})();`;

htmlFiles.forEach(file => {
  const filePath = path.join(__dirname, '..', file);
  if (!fs.existsSync(filePath)) {
    console.log(`File not found: ${file}`);
    return;
  }
  
  let content = fs.readFileSync(filePath, 'utf8');
  let modified = false;
  
  // Заменить старый патч на новый
  if (content.includes('// Патч для устранения предупреждений о non-passive event listeners') &&
      content.includes('// Применяем ДО загрузки jQuery и Owl Carousel') &&
      !content.includes('Исключаем элементы слайдеров')) {
    const pattern = /\/\/ Патч для устранения предупреждений о non-passive event listeners[\s\S]*?Применяем ДО загрузки jQuery и Owl Carousel[\s\S]*?return originalAddEventListener\.call\(this, type, listener, options\);[\s\S]*?\};[\s\S]*?\}\)\(\);[\s\S]*?<\/script>/;
    if (pattern.test(content)) {
      // Попробуем более точный паттерн
      const exactPattern = /\/\/ Патч для устранения предупреждений о non-passive event listeners[\s\S]*?\/\/ Применяем ДО загрузки jQuery и Owl Carousel[\s\S]*?}\)\(\);/;
      if (exactPattern.test(content)) {
        content = content.replace(exactPattern, newPatch);
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
