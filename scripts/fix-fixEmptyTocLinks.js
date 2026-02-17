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

htmlFiles.forEach(file => {
  const filePath = path.join(__dirname, '..', file);
  if (!fs.existsSync(filePath)) {
    console.log(`File not found: ${file}`);
    return;
  }
  
  let content = fs.readFileSync(filePath, 'utf8');
  let modified = false;
  
  // Найти где начинается функция initOwlAndFancybox
  const initOwlMatch = content.match(/(function initOwlAndFancybox\(\) \{)/);
  if (!initOwlMatch) {
    console.log(`No initOwlAndFancybox found in ${file}`);
    return;
  }
  
  // Найти функцию fixEmptyTocLinks внутри initOwlAndFancybox
  const fixEmptyTocLinksPattern = /(\s+)(\/\/ Исправить пустые ссылки в custom-toc \(accessibility fix\)\s+function fixEmptyTocLinks\(\) \{[^}]+\})/;
  const match = content.match(fixEmptyTocLinksPattern);
  
  if (match) {
    // Извлечь функцию
    const functionCode = match[2].replace(/^\s+/, '');
    const indent = match[1];
    
    // Удалить функцию из initOwlAndFancybox
    content = content.replace(fixEmptyTocLinksPattern, '');
    
    // Найти место перед initOwlAndFancybox и добавить функцию туда
    const beforeInit = content.substring(0, initOwlMatch.index);
    const afterInit = content.substring(initOwlMatch.index);
    
    // Создать функцию на уровне модуля (без отступа)
    const fixedFunction = functionCode.replace(/^\s+function/, 'function').replace(/\$\(/g, 'var $ = window.jQuery;\n\t\tif (!$) return;\n\t\t$(');
    
    // Добавить функцию перед initOwlAndFancybox
    content = beforeInit + '\n\t// Исправить пустые ссылки в custom-toc (accessibility fix)\n\t' + fixedFunction + '\n\n\t' + afterInit;
    
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
