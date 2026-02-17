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

const oldCSS = `/* Навигация (стрелки) - отключены для .owl-carousel, оставлены для .owl-mobile */
.owl-carousel .owl-nav
{
	display:none !important;
	visibility:hidden !important;
}
.owl-mobile .owl-nav
{
	display:flex !important;
	visibility:visible !important;
	position:absolute;
	left:0;
	right:0;
	top:50%;
	transform:translateY(-50%);
	justify-content:space-between;
	pointer-events:none;
	margin:0;
	padding:0 8px;
	z-index:9999
}
.owl-mobile .owl-nav.disabled
{
	display:flex !important;
	visibility:visible !important
}
.owl-mobile .owl-nav button
{
	pointer-events:auto !important;
	width:44px;
	height:44px;
	min-width:44px;
	min-height:44px;
	border-radius:50%;
	background:rgba(0,0,0,0.6);
	color:#fff;
	border:none;
	cursor:pointer;
	font-size:24px;
	line-height:1;
	display:flex;
	align-items:center;
	justify-content:center;
	transition:background .2s;
	position:relative;
	z-index:10000
}
.owl-mobile .owl-nav button:hover
{
	background:rgba(0,0,0,0.85)
}
.owl-mobile .owl-prev
{
	position:absolute;
	left:8px
}
.owl-mobile .owl-next
{
	position:absolute;
	right:8px
}`;

const newCSS = `/* Навигация (стрелки) - отключены для .owl-carousel и .owl-mobile */
.owl-carousel .owl-nav,
.owl-mobile .owl-nav
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
  
  // Заменить старый CSS на новый
  if (content.includes('.owl-mobile .owl-nav') && content.includes('display:flex !important')) {
    // Попробовать точное совпадение
    if (content.includes(oldCSS)) {
      content = content.replace(oldCSS, newCSS);
      modified = true;
    } else {
      // Попробовать более гибкий поиск
      const pattern = /\/\* Навигация \(стрелки\)[^*]*\*\/[\s\S]*?\.owl-mobile \.owl-next[\s\S]*?\}/;
      if (pattern.test(content)) {
        content = content.replace(pattern, newCSS);
        modified = true;
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
