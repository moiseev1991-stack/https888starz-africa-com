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

const oldLogic = `\t\t\tif (typeof options === 'object' && options !== null) {
\t\t\t\tif (options.passive === undefined) {
\t\t\t\t\toptions.passive = true;
\t\t\t\t}
\t\t\t}`;

const newLogic = `\t\t\tif (typeof options === 'object' && options !== null) {
\t\t\t\t// Если passive не указан явно (ни true, ни false), устанавливаем true
\t\t\t\tif (!('passive' in options)) {
\t\t\t\t\toptions.passive = true;
\t\t\t\t}
\t\t\t}`;

htmlFiles.forEach(file => {
  const filePath = path.join(__dirname, '..', file);
  if (!fs.existsSync(filePath)) {
    console.log(`File not found: ${file}`);
    return;
  }
  
  let content = fs.readFileSync(filePath, 'utf8');
  let modified = false;
  
  // Заменить логику проверки passive
  if (content.includes('if (options.passive === undefined)')) {
    content = content.replace(oldLogic, newLogic);
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
