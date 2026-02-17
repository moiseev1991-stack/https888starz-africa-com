const fs = require('fs');
const path = require('path');

const htmlFiles = [
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

// Паттерн для #single .post-content селекторов
const pattern1 = /(#single \.post-content \.owl-carousel \.owl-nav\s*\{[^}]*?\})\s*(#single \.post-content \.owl-mobile \.owl-nav\s*\{[^}]*?display:flex[^}]*?\})\s*(#single \.post-content \.owl-mobile \.owl-nav\.disabled[^}]*?\})\s*(#single \.post-content \.owl-carousel \.owl-nav button[^}]*?\})\s*(#single \.post-content \.owl-mobile \.owl-nav button[^}]*?\})\s*(#single \.post-content \.owl-carousel \.owl-nav button:hover[^}]*?\})\s*(#single \.post-content \.owl-mobile \.owl-nav button:hover[^}]*?\})\s*(#single \.post-content \.owl-carousel \.owl-prev[^}]*?\})\s*(#single \.post-content \.owl-mobile \.owl-prev[^}]*?\})\s*(#single \.post-content \.owl-carousel \.owl-next[^}]*?\})\s*(#single \.post-content \.owl-mobile \.owl-next[^}]*?\})/s;

const replacement1 = `#single .post-content .owl-carousel .owl-nav,
#single .post-content .owl-mobile .owl-nav
{
	display:none !important;
	visibility:hidden !important;
}`;

htmlFiles.forEach(file => {
  const filePath = path.join(__dirname, '..', file);
  if (!fs.existsSync(filePath)) {
    console.log(`File not found: ${file}`);
    return;
  }
  
  let content = fs.readFileSync(filePath, 'utf8');
  let modified = false;
  
  // Проверить, есть ли #single .post-content селекторы
  if (content.includes('#single .post-content .owl-mobile .owl-nav')) {
    // Попробовать заменить весь блок
    const blockPattern = /(#single \.post-content \.owl-carousel \.owl-nav\s*\{[^}]*?\})\s*(#single \.post-content \.owl-mobile \.owl-nav\s*\{[\s\S]*?display:flex[^}]*?\}[\s\S]*?)(#single \.post-content \.owl-mobile \.owl-nav\.disabled[\s\S]*?\})\s*(#single \.post-content \.owl-carousel \.owl-nav button[\s\S]*?\})\s*(#single \.post-content \.owl-mobile \.owl-nav button[\s\S]*?\})\s*(#single \.post-content \.owl-carousel \.owl-nav button:hover[\s\S]*?\})\s*(#single \.post-content \.owl-mobile \.owl-nav button:hover[\s\S]*?\})\s*(#single \.post-content \.owl-carousel \.owl-prev[\s\S]*?\})\s*(#single \.post-content \.owl-mobile \.owl-prev[\s\S]*?\})\s*(#single \.post-content \.owl-carousel \.owl-next[\s\S]*?\})\s*(#single \.post-content \.owl-mobile \.owl-next[\s\S]*?\})/;
    
    if (blockPattern.test(content)) {
      content = content.replace(blockPattern, replacement1);
      modified = true;
    } else {
      // Более простой подход - найти начало и конец блока
      const startPattern = /#single \.post-content \.owl-carousel \.owl-nav\s*\{/;
      const endPattern = /#single \.post-content \.owl-mobile \.owl-next\s*\{[^}]*?\}/;
      
      if (startPattern.test(content) && endPattern.test(content)) {
        const startMatch = content.match(startPattern);
        const endMatch = content.match(endPattern);
        if (startMatch && endMatch) {
          const startIndex = startMatch.index;
          const endIndex = endMatch.index + endMatch[0].length;
          const before = content.substring(0, startIndex);
          const after = content.substring(endIndex);
          content = before + replacement1 + '\n' + after;
          modified = true;
        }
      }
    }
  }
  
  if (modified) {
    fs.writeFileSync(filePath, content, 'utf8');
    console.log(`Updated CSS in: ${file}`);
  } else {
    console.log(`No CSS changes needed: ${file}`);
  }
});

console.log('Done!');
