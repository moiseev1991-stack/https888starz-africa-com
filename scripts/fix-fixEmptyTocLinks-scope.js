const fs = require('fs');
const path = require('path');

const htmlFiles = [
  'public/apk/index.html',
  'public/promo-code/index.html',
  'public/terms/index.html',
  'public/self-exclusion/index.html',
  'public/accounts-withdrawals-and-bonuses/index.html',
  'public/fairness-rng-testing-methods/index.html',
  'public/contacts/index.html',
  'public/responsible/index.html',
  'public/privacy-policy/index.html',
  'public/cookies/index.html',
  'public/registration/index.html'
];

const functionToAdd = `\t// Исправить пустые ссылки в custom-toc (accessibility fix)
\tfunction fixEmptyTocLinks() {
\t\tvar $ = window.jQuery;
\t\tif (!$) return;
\t\t$(".custom-toc a[href='#']").each(function() {
\t\t\tvar $link = $(this);
\t\t\tif (!$link.text().trim() && !$link.attr("aria-label")) {
\t\t\t\t$link.attr("aria-hidden", "true");
\t\t\t\t$link.attr("tabindex", "-1");
\t\t\t}
\t\t});
\t}

`;

htmlFiles.forEach(file => {
  const filePath = path.join(__dirname, '..', file);
  if (!fs.existsSync(filePath)) {
    console.log(`File not found: ${file}`);
    return;
  }
  
  let content = fs.readFileSync(filePath, 'utf8');
  
  // Проверить, нужно ли исправление
  if (!content.includes('function initOwlAndFancybox()')) {
    console.log(`No initOwlAndFancybox in ${file}`);
    return;
  }
  
  // Проверить, не добавлена ли уже функция на правильном уровне
  if (content.match(/^\tfunction fixEmptyTocLinks\(\) \{/m)) {
    console.log(`Already fixed: ${file}`);
    return;
  }
  
  // Найти место перед initOwlAndFancybox
  const beforeInitPattern = /(\toriginalAddEventListener\.call\(this, type, listener, options\);\n\t\};\n\n\t)(function initOwlAndFancybox\(\) \{)/;
  
  if (beforeInitPattern.test(content)) {
    // Добавить функцию перед initOwlAndFancybox
    content = content.replace(beforeInitPattern, '$1' + functionToAdd + '\t$2');
    
    // Удалить функцию изнутри initOwlAndFancybox
    const removeFromInsidePattern = /\t\t\}\n\t\t\/\/ Исправить пустые ссылки в custom-toc \(accessibility fix\)\n\t\tfunction fixEmptyTocLinks\(\) \{\n\t\t\t\$\([^}]+\}\n\t\t\}\n\t\t\$\(/;
    content = content.replace(removeFromInsidePattern, '\t\t}\n\t\t$(');
    
    // Альтернативный паттерн для удаления
    const altPattern = /\t\t\/\/ Исправить пустые ссылки в custom-toc \(accessibility fix\)\n\t\tfunction fixEmptyTocLinks\(\) \{\n\t\t\t\$\([^}]+\}\n\t\t\}\n\t\t/;
    content = content.replace(altPattern, '\t\t');
    
    fs.writeFileSync(filePath, content, 'utf8');
    console.log(`Fixed: ${file}`);
  } else {
    console.log(`Pattern not found in ${file}`);
  }
});

console.log('Done!');
