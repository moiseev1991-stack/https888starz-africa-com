const fs = require('fs');
const path = require('path');

const htmlFiles = [
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

const oldPattern = /#single \.post-content \.owl-carousel \.owl-nav,\s*#single \.post-content \.owl-mobile \.owl-nav\s*\{[\s\S]*?display:flex !important;[\s\S]*?\}\s*#single \.post-content \.owl-carousel \.owl-nav\.disabled,[\s\S]*?#single \.post-content \.owl-mobile \.owl-nav\.disabled[\s\S]*?\}\s*#single \.post-content \.owl-carousel \.owl-nav button,[\s\S]*?#single \.post-content \.owl-mobile \.owl-nav button[\s\S]*?\}\s*#single \.post-content \.owl-carousel \.owl-nav button:hover,[\s\S]*?#single \.post-content \.owl-mobile \.owl-nav button:hover[\s\S]*?\}\s*#single \.post-content \.owl-carousel \.owl-prev,[\s\S]*?#single \.post-content \.owl-mobile \.owl-prev[\s\S]*?\}\s*#single \.post-content \.owl-carousel \.owl-next,[\s\S]*?#single \.post-content \.owl-mobile \.owl-next[\s\S]*?\}/;

const newCSS = `#single .post-content .owl-carousel .owl-nav,
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
  
  // Проверить, есть ли старый CSS блок
  if (content.includes('#single .post-content .owl-mobile .owl-nav') && 
      content.includes('display:flex !important')) {
    
    // Найти начало блока
    const startIdx = content.indexOf('#single .post-content .owl-carousel .owl-nav,');
    if (startIdx === -1) {
      console.log(`Pattern start not found in ${file}`);
      return;
    }
    
    // Найти конец блока (после .owl-next)
    const endPattern = /#single \.post-content \.owl-mobile \.owl-next[\s\S]*?\}/;
    const endMatch = content.substring(startIdx).match(endPattern);
    
    if (endMatch) {
      const endIdx = startIdx + endMatch.index + endMatch[0].length;
      const before = content.substring(0, startIdx);
      const after = content.substring(endIdx);
      content = before + newCSS + '\n' + after;
      modified = true;
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
