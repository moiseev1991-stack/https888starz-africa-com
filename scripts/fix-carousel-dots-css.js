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

const oldDotsCSS = `/* Точки (кружочки) под ВСЕМИ слайдерами — показывать номера 1, 2, 3, 4, 5… */
.owl-carousel .owl-dots,
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
.owl-carousel .owl-dots .owl-dot,
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
.owl-carousel .owl-dots .owl-dot span,
.owl-mobile .owl-dots .owl-dot span
{
	display:inline-block;
	line-height:1;
}
.owl-carousel .owl-dots .owl-dot:hover,
.owl-mobile .owl-dots .owl-dot:hover
{
	background:#666;
	border-color:#900;
	transform:scale(1.1);
}
.owl-carousel .owl-dots .owl-dot.active,
.owl-mobile .owl-dots .owl-dot.active
{
	background:#900;
	border-color:#fff;
	box-shadow:0 0 10px rgba(153,0,0,0.5);
	transform:scale(1.15);
}`;

const newDotsCSS = `/* Точки (кружочки) только для .owl-mobile (на десктопе) */
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

htmlFiles.forEach(file => {
  const filePath = path.join(__dirname, '..', file);
  if (!fs.existsSync(filePath)) {
    console.log(`File not found: ${file}`);
    return;
  }
  
  let content = fs.readFileSync(filePath, 'utf8');
  let modified = false;
  
  // Заменить старые стили dots
  if (content.includes('.owl-carousel .owl-dots,') && 
      content.includes('/* Точки (кружочки) под ВСЕМИ слайдерами')) {
    content = content.replace(oldDotsCSS, newDotsCSS);
    modified = true;
  }
  
  // Убедиться что dots: false для .owl-carousel
  const owlCarouselPattern = /(\$\("\.owl-carousel"\)\.owlCarousel\(\{[\s\S]*?dots:\s*)true([\s\S]*?\}\));/;
  if (owlCarouselPattern.test(content)) {
    content = content.replace(owlCarouselPattern, '$1false$2');
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
