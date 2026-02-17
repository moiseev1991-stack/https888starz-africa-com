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

const oldInitFunction = `\tfunction initOwlAndFancybox() {
\t\tvar $ = window.jQuery;
\t\tif (!$ || !$.fn.owlCarousel) return;`;

const newInitFunction = `\tfunction initOwlAndFancybox() {
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

htmlFiles.forEach(file => {
  const filePath = path.join(__dirname, '..', file);
  if (!fs.existsSync(filePath)) {
    console.log(`File not found: ${file}`);
    return;
  }
  
  let content = fs.readFileSync(filePath, 'utf8');
  let modified = false;
  
  // Заменить функцию initOwlAndFancybox
  if (content.includes('function initOwlAndFancybox()') && 
      !content.includes('// Патч для устранения предупреждений о non-passive event listeners')) {
    const pattern = /function initOwlAndFancybox\(\) \{[\s\S]*?var \$ = window\.jQuery;[\s\S]*?if \(\!\$ \|\| \!\$\.fn\.owlCarousel\) return;/;
    if (pattern.test(content)) {
      content = content.replace(pattern, newInitFunction);
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
