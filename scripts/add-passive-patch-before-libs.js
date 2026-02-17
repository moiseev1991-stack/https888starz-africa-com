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

const libsPattern = /<link rel="stylesheet" href="https:\/\/cdnjs\.cloudflare\.com\/ajax\/libs\/OwlCarousel2\/2\.3\.4\/assets\/owl\.carousel\.min\.css">[\s\S]*?<script src="https:\/\/cdnjs\.cloudflare\.com\/ajax\/libs\/jquery\/3\.6\.0\/jquery\.min\.js"><\/script>/;

const libsWithPatch = `<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
<script>
\t// Патч для устранения предупреждений о non-passive event listeners
\t// Применяем ДО загрузки jQuery и Owl Carousel
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
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>`;

htmlFiles.forEach(file => {
  const filePath = path.join(__dirname, '..', file);
  if (!fs.existsSync(filePath)) {
    console.log(`File not found: ${file}`);
    return;
  }
  
  let content = fs.readFileSync(filePath, 'utf8');
  let modified = false;
  
  // Добавить патч перед библиотеками, если его там еще нет
  if (libsPattern.test(content) && !content.includes('// Патч для устранения предупреждений о non-passive event listeners\n\t// Применяем ДО загрузки jQuery')) {
    content = content.replace(libsPattern, libsWithPatch);
    modified = true;
  }
  
  // Удалить дублирующийся патч из основного скрипта, если он есть
  const duplicatePatchPattern = /\t\/\/ Патч для устранения предупреждений о non-passive event listeners[\s\S]*?\t\t\}\s*\}\)\(\);[\s\S]*?\n\t\/\/ Исправить пустые ссылки/;
  if (duplicatePatchPattern.test(content)) {
    content = content.replace(duplicatePatchPattern, '\t// Исправить пустые ссылки');
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
