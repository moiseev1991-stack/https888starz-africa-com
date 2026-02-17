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

const oldPattern = /(\t\t\/\/ Показывать номер в точке \(1, 2, 3, 4, 5…\) для всех слайдеров\n\t\t\t\$dot\.addClass\("owl-dot-number"\)\.html\("<span>" \+ n \+ "<\/span>"\);)/g;
const newReplacement = '\t\t\t// Убираем цифры, оставляем только кружки\n\t\t\t$dot.empty();';

const oldPattern2 = /(\t\t\tif \(\!\$dot\.attr\("aria-label"\)\) \$dot\.attr\("aria-label", "Go to slide " \+ n\);\n\t\t\t\/\/ Показывать номер в точке \(1, 2, 3, 4, 5…\) для всех слайдеров\n\t\t\t\$dot\.addClass\("owl-dot-number"\)\.html\("<span>" \+ n \+ "<\/span>"\);)/g;
const newReplacement2 = '\t\t\tif (!$dot.attr("aria-label")) $dot.attr("aria-label", "Go to slide " + n);\n\t\t\t// Убираем цифры, оставляем только кружки\n\t\t\t$dot.empty();';

htmlFiles.forEach(file => {
  const filePath = path.join(__dirname, '..', file);
  if (!fs.existsSync(filePath)) {
    console.log(`File not found: ${file}`);
    return;
  }
  
  let content = fs.readFileSync(filePath, 'utf8');
  let modified = false;
  
  // Попробовать первый паттерн
  if (oldPattern.test(content)) {
    content = content.replace(oldPattern, newReplacement);
    modified = true;
  }
  
  // Попробовать второй паттерн
  if (oldPattern2.test(content)) {
    content = content.replace(oldPattern2, newReplacement2);
    modified = true;
  }
  
  // Более гибкий поиск и замена
  const flexiblePattern = /(\$dot\.addClass\("owl-dot-number"\)\.html\("<span>" \+ n \+ "<\/span>"\);)/g;
  if (flexiblePattern.test(content)) {
    content = content.replace(flexiblePattern, '$dot.empty();');
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
