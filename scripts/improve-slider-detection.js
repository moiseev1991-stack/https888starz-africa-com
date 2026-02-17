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

const oldDetection = `\t\t\t// Проверяем, является ли элемент частью слайдера
\t\t\tvar isSliderElement = false;
\t\t\tif (this && this.nodeType === 1) { // Element node
\t\t\t\tvar element = this;
\t\t\t\t// Проверяем классы и родительские элементы
\t\t\t\tif (element.classList) {
\t\t\t\t\tisSliderElement = element.classList.contains('owl-carousel') ||
\t\t\t\t\t\telement.classList.contains('owl-mobile') ||
\t\t\t\t\t\telement.classList.contains('owl-stage') ||
\t\t\t\t\t\telement.classList.contains('owl-stage-outer') ||
\t\t\t\t\t\telement.classList.contains('owl-item') ||
\t\t\t\t\t\telement.closest && (
\t\t\t\t\t\t\telement.closest('.owl-carousel') ||
\t\t\t\t\t\t\telement.closest('.owl-mobile') ||
\t\t\t\t\t\t\telement.closest('.owl-stage-outer')
\t\t\t\t\t);
\t\t\t\t}
\t\t\t}`;

const newDetection = `\t\t\t// Проверяем, является ли элемент частью слайдера
\t\t\tvar isSliderElement = false;
\t\t\tif (this && this.nodeType === 1) { // Element node
\t\t\t\tvar element = this;
\t\t\t\t// Проверяем классы элемента
\t\t\tif (element.classList) {
\t\t\t\tvar className = element.className || '';
\t\t\t\tif (typeof className === 'string') {
\t\t\t\t\tisSliderElement = className.indexOf('owl-carousel') !== -1 ||
\t\t\t\t\t\tclassName.indexOf('owl-mobile') !== -1 ||
\t\t\t\t\t\tclassName.indexOf('owl-stage') !== -1 ||
\t\t\t\t\t\tclassName.indexOf('owl-stage-outer') !== -1 ||
\t\t\t\t\t\tclassName.indexOf('owl-item') !== -1;
\t\t\t\t}
\t\t\t\t// Также проверяем через classList
\t\t\t\tif (!isSliderElement && element.classList.length) {
\t\t\t\t\tisSliderElement = element.classList.contains('owl-carousel') ||
\t\t\t\t\t\telement.classList.contains('owl-mobile') ||
\t\t\t\t\t\telement.classList.contains('owl-stage') ||
\t\t\t\t\t\telement.classList.contains('owl-stage-outer') ||
\t\t\t\t\t\telement.classList.contains('owl-item');
\t\t\t\t}
\t\t\t}
\t\t\t// Проверяем родительские элементы через closest (если доступен)
\t\t\tif (!isSliderElement && element.closest && typeof element.closest === 'function') {
\t\t\t\ttry {
\t\t\t\t\tisSliderElement = !!(element.closest('.owl-carousel') ||
\t\t\t\t\t\telement.closest('.owl-mobile') ||
\t\t\t\t\t\telement.closest('.owl-stage-outer'));
\t\t\t\t} catch (e) {
\t\t\t\t\t// closest может не работать в некоторых контекстах
\t\t\t\t}
\t\t\t}
\t\t\t// Проверяем родительские элементы через parentElement (fallback)
\t\t\tif (!isSliderElement) {
\t\t\t\tvar parent = element.parentElement;
\t\t\t\tvar depth = 0;
\t\t\t\twhile (parent && depth < 10) {
\t\t\t\t\tif (parent.classList) {
\t\t\t\t\t\tvar parentClass = parent.className || '';
\t\t\t\t\t\tif (typeof parentClass === 'string' && (
\t\t\t\t\t\t\tparentClass.indexOf('owl-carousel') !== -1 ||
\t\t\t\t\t\t\tparentClass.indexOf('owl-mobile') !== -1 ||
\t\t\t\t\t\t\tparentClass.indexOf('owl-stage-outer') !== -1
\t\t\t\t\t\t)) {
\t\t\t\t\t\t\tisSliderElement = true;
\t\t\t\t\t\t\tbreak;
\t\t\t\t\t\t}
\t\t\t\t\t}
\t\t\t\t\tparent = parent.parentElement;
\t\t\t\t\tdepth++;
\t\t\t\t}
\t\t\t}
\t\t\t}`;

htmlFiles.forEach(file => {
  const filePath = path.join(__dirname, '..', file);
  if (!fs.existsSync(filePath)) {
    console.log(`File not found: ${file}`);
    return;
  }
  
  let content = fs.readFileSync(filePath, 'utf8');
  let modified = false;
  
  // Заменить старую проверку на новую
  if (content.includes('// Проверяем, является ли элемент частью слайдера') &&
      content.includes('element.closest') &&
      !content.includes('parentElement')) {
    const pattern = /\/\/ Проверяем, является ли элемент частью слайдера[\s\S]*?if \(element\.classList\) \{[\s\S]*?element\.closest\('\.owl-stage-outer'\)[\s\S]*?\}\)[\s\S]*?\t\t\t\t\}/;
    if (pattern.test(content)) {
      content = content.replace(pattern, newDetection);
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
