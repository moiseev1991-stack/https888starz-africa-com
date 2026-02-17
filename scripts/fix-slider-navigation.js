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

// CSS для скрытия стрелок навигации для .owl-carousel
const cssOld = `/* Навигация (стрелки) для ВСЕХ слайдеров .owl-carousel и .owl-mobile */
.owl-carousel .owl-nav,
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
.owl-carousel .owl-nav.disabled,
.owl-mobile .owl-nav.disabled
{
	display:flex !important;
	visibility:visible !important
}
.owl-carousel .owl-nav button,
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
.owl-carousel .owl-nav button:hover,
.owl-mobile .owl-nav button:hover
{
	background:rgba(0,0,0,0.85)
}
.owl-carousel .owl-prev,
.owl-mobile .owl-prev
{
	position:absolute;
	left:8px
}
.owl-carousel .owl-next,
.owl-mobile .owl-next
{
	position:absolute;
	right:8px
}`;

const cssNew = `/* Навигация (стрелки) - отключены для .owl-carousel, оставлены для .owl-mobile */
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

// CSS для улучшения dots
const dotsOld = `.owl-carousel .owl-dots .owl-dot,
.owl-mobile .owl-dots .owl-dot
{
	display:inline-flex !important;
	visibility:visible !important;
	pointer-events:auto !important;
	align-items:center;
	justify-content:center;
	width:32px;
	height:32px;
	min-width:32px;
	border-radius:50%;
	background:#3d3d3d;
	color:#fff;
	font-size:14px;
	font-weight:600;
	cursor:pointer;
	border:none;
	transition:background .2s;
	position:relative;
	z-index:9999
}`;

const dotsNew = `.owl-carousel .owl-dots .owl-dot,
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
}`;

const dotsHoverOld = `.owl-carousel .owl-dots .owl-dot:hover,
.owl-mobile .owl-dots .owl-dot:hover
{
	background:#555
}
.owl-carousel .owl-dots .owl-dot.active,
.owl-mobile .owl-dots .owl-dot.active
{
	background:#900
}`;

const dotsHoverNew = `.owl-carousel .owl-dots .owl-dot:hover,
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

// JavaScript для улучшения обработчика кликов
const jsOld = `			else if (dot) {
						var idx = window.jQuery(dot).index();
						$c.trigger("to.owl.carousel", [idx]);
					}`;

const jsNew = `			else if (dot) {
						var $dot = window.jQuery(dot);
						var idx = $dot.index();
						$c.trigger("to.owl.carousel", [idx, 300]);
					}`;

const jsHandlerOld = `		};`;

const jsHandlerNew = `		};
		// Дополнительный обработчик кликов на dots для надежности
		$(document).on("click", ".owl-carousel .owl-dots .owl-dot, .owl-mobile .owl-dots .owl-dot", function(e) {
			e.preventDefault();
			e.stopPropagation();
			var $dot = $(this);
			var $carousel = $dot.closest(".owl-carousel, .owl-mobile");
			if ($carousel.length && window.jQuery && window.jQuery.fn.owlCarousel) {
				var idx = $dot.index();
				$carousel.trigger("to.owl.carousel", [idx, 300]);
			}
		});`;

htmlFiles.forEach(file => {
  const filePath = path.join(__dirname, '..', file);
  if (!fs.existsSync(filePath)) {
    console.log(`File not found: ${file}`);
    return;
  }
  
  let content = fs.readFileSync(filePath, 'utf8');
  let modified = false;
  
  // Заменить CSS для навигации
  if (content.includes(cssOld)) {
    content = content.replace(cssOld, cssNew);
    modified = true;
  }
  
  // Заменить CSS для dots
  if (content.includes(dotsOld)) {
    content = content.replace(dotsOld, dotsNew);
    modified = true;
  }
  
  // Заменить CSS для hover dots
  if (content.includes(dotsHoverOld)) {
    content = content.replace(dotsHoverOld, dotsHoverNew);
    modified = true;
  }
  
  // Заменить JavaScript обработчик
  if (content.includes(jsOld)) {
    content = content.replace(jsOld, jsNew);
    modified = true;
  }
  
  // Добавить дополнительный обработчик кликов
  const handlerPattern = /(\s+var contentSliderCapture = function\(e\) \{[\s\S]*?\n\s+\}\);)/;
  if (handlerPattern.test(content) && !content.includes('Дополнительный обработчик кликов на dots')) {
    content = content.replace(handlerPattern, (match) => {
      return match + jsHandlerNew.replace('		};', '');
    });
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
