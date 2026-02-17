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
  
  // Заменить nav: true на nav: false для .owl-mobile
  const pattern = /(\$\("\.owl-mobile"\)\.owlCarousel\(\{[^}]*?nav:\s*)true([^}]*?navText:\s*\["‹","›"\],)/g;
  if (pattern.test(content)) {
    content = content.replace(pattern, '$1false$2');
    modified = true;
  }
  
  // Альтернативный паттерн без navText
  const pattern2 = /(\$\("\.owl-mobile"\)\.owlCarousel\(\{[^}]*?nav:\s*)true([^}]*?dots:)/g;
  if (pattern2.test(content)) {
    content = content.replace(pattern2, '$1false$2');
    modified = true;
  }
  
  // Более простой паттерн - заменить nav: true на nav: false в контексте owl-mobile
  const simplePattern = /(\$\("\.owl-mobile"\)\.owlCarousel\(\{[^}]*?\n\t\t\tnav:\s*)true/g;
  if (simplePattern.test(content)) {
    content = content.replace(simplePattern, '$1false');
    modified = true;
  }
  
  // Удалить строку navText если она есть после nav: false
  const navTextPattern = /(\t\t\tnav:\s*false,\n\t\t\t)navText:\s*\["‹","›"\],\n\t\t\t/g;
  if (navTextPattern.test(content)) {
    content = content.replace(navTextPattern, '$1');
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
