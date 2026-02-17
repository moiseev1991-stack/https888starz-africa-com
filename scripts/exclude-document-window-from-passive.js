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

const oldStart = `\t\t\tEventTarget.prototype.addEventListener = function(type, listener, options) {
\t\t\t\t// Проверяем, является ли элемент частью слайдера
\t\t\t\tvar isSliderElement = false;
\t\t\t\tif (this && this.nodeType === 1) { // Element node`;

const newStart = `\t\t\tEventTarget.prototype.addEventListener = function(type, listener, options) {
\t\t\t\t// Не применяем passive для document и window, так как там могут быть делегированные события для слайдеров
\t\t\t\tvar isDocumentOrWindow = (this === document || this === window || (this && (this.nodeType === 9 || this === globalThis)));
\t\t\t\t
\t\t\t\t// Проверяем, является ли элемент частью слайдера
\t\t\t\tvar isSliderElement = false;
\t\t\t\tif (this && this.nodeType === 1) { // Element node`;

const oldCondition = `\t\t\t// Для touchstart событий автоматически добавляем passive: true, если не указано явно
\t\t\t// НО НЕ для элементов слайдеров, чтобы они могли использовать preventDefault()
\t\t\t// Также НЕ применяем passive для touchmove, так как оно часто используется для drag и требует preventDefault
\t\t\tif ((type === 'touchstart' || type === 'touchend') && !isSliderElement) {`;

const newCondition = `\t\t\t// Для touchstart событий автоматически добавляем passive: true, если не указано явно
\t\t\t// НО НЕ для элементов слайдеров, чтобы они могли использовать preventDefault()
\t\t\t// Также НЕ применяем passive для touchmove, так как оно часто используется для drag и требует preventDefault
\t\t\t// И НЕ применяем для document/window, так как там могут быть делегированные события
\t\t\tif ((type === 'touchstart' || type === 'touchend') && !isSliderElement && !isDocumentOrWindow) {`;

htmlFiles.forEach(file => {
  const filePath = path.join(__dirname, '..', file);
  if (!fs.existsSync(filePath)) {
    console.log(`File not found: ${file}`);
    return;
  }
  
  let content = fs.readFileSync(filePath, 'utf8');
  let modified = false;
  
  // Добавить проверку на document/window
  if (content.includes('EventTarget.prototype.addEventListener = function(type, listener, options)') &&
      !content.includes('isDocumentOrWindow')) {
    content = content.replace(oldStart, newStart);
    modified = true;
  }
  
  // Обновить условие
  if (content.includes('type === \'touchstart\' || type === \'touchend\'') &&
      !content.includes('!isDocumentOrWindow')) {
    content = content.replace(oldCondition, newCondition);
    modified = true;
  }
  
  if (modified) {
    fs.writeFileSync(filePath, content, 'utf8');
    console.log(`Updated: ${file}`);
  } else {
    console.log(`No changes needed: ${file}`);
  }
});

console.log('Done!');
