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

const oldDotsCSS = `/* Скрываем dots для .owl-carousel на всех устройствах */
.owl-carousel .owl-dots
{
	display:none !important;
}
/* На мобильных устройствах скрываем dots для .owl-mobile */
@media screen and (max-width:767px)
{
	.owl-mobile .owl-dots
	{
		display:none !important;
	}
}
.owl-carousel,
.owl-mobile
{
	position:relative
}
.owl-carousel .owl-stage-outer,
.owl-mobile .owl-stage-outer
{
	position:relative;
	z-index:1
}
/* Точки (кружочки) только для .owl-mobile (на десктопе) */
.owl-mobile .owl-dots
{
	display:flex !important;
	visibility:visible !important;
	flex-wrap:wrap;
	gap:6px;
	justify-content:center;
	margin-top:14px;
	padding:10px 0
}
.owl-mobile .owl-dots .owl-dot
{
	display:inline-flex !important;
	visibility:visible !important;
	pointer-events:auto !important;
	align-items:center;
	justify-content:center;
	width:40px;
	height:40px;
	min-width:40px;
	border-radius:50%;
	background:#3d3d3d;
	color:#fff;
	font-size:16px;
	font-weight:700;
	cursor:pointer;
	border:2px solid transparent;
	transition:all .3s ease;
	position:relative;
	z-index:9999;
	margin:0 4px;
}
.owl-mobile .owl-dots .owl-dot span
{
	display:inline-block;
	line-height:1;
}
.owl-mobile .owl-dots .owl-dot:hover
{
	background:#666;
	border-color:#900;
	transform:scale(1.1);
}
.owl-mobile .owl-dots .owl-dot.active
{
	background:#900;
	border-color:#fff;
	box-shadow:0 0 10px rgba(153,0,0,0.5);
	transform:scale(1.15);
}`;

const newDotsCSS = `/* Скрываем dots для .owl-carousel и .owl-mobile на всех устройствах */
.owl-carousel .owl-dots,
.owl-mobile .owl-dots
{
	display:none !important;
	visibility:hidden !important;
}
.owl-carousel,
.owl-mobile
{
	position:relative
}
.owl-carousel .owl-stage-outer,
.owl-mobile .owl-stage-outer
{
	position:relative;
	z-index:1
}`;

htmlFiles.forEach(file => {
  const filePath = path.join(__dirname, '..', file);
  if (!fs.existsSync(filePath)) {
    console.log(`File not found: ${file}`);
    return;
  }
  
  let content = fs.readFileSync(filePath, 'utf8');
  let modified = false;
  
  // Заменить CSS для dots
  if (content.includes('.owl-mobile .owl-dots') && 
      content.includes('display:flex !important')) {
    // Попробуем найти и заменить весь блок
    const pattern = /\/\* Скрываем dots для \.owl-carousel на всех устройствах \*\/[\s\S]*?\.owl-mobile \.owl-dots \.owl-dot\.active[\s\S]*?transform:scale\(1\.15\);[\s\S]*?\}/;
    if (pattern.test(content)) {
      content = content.replace(pattern, newDotsCSS);
      modified = true;
    } else {
      // Попробуем более простой паттерн
      const simplePattern = /\.owl-mobile \.owl-dots[\s\S]*?display:flex !important;[\s\S]*?visibility:visible !important;[\s\S]*?flex-wrap:wrap;[\s\S]*?gap:6px;[\s\S]*?justify-content:center;[\s\S]*?margin-top:14px;[\s\S]*?padding:10px 0[\s\S]*?\}/;
      if (simplePattern.test(content)) {
        // Удалить старые стили для .owl-mobile .owl-dots
        content = content.replace(simplePattern, '');
        // Добавить скрытие dots
        const hidePattern = /\/\* Скрываем dots для \.owl-carousel на всех устройствах \*\/[\s\S]*?\.owl-carousel \.owl-dots[\s\S]*?display:none !important;[\s\S]*?\}/;
        if (hidePattern.test(content)) {
          content = content.replace(hidePattern, newDotsCSS);
        }
        modified = true;
      }
    }
  }
  
  // Изменить dots: true на dots: false для .owl-mobile
  const owlMobilePattern = /(\$\(["']\.owl-mobile["']\)\.each\(function\(\) \{[\s\S]*?dots:\s*)true([\s\S]*?\}\);)/;
  if (owlMobilePattern.test(content)) {
    content = content.replace(owlMobilePattern, '$1false$2');
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
