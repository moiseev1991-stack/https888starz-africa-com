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

// Новый CSS
const newCSS = `/* Кастомная навигация для .owl-carousel и .owl-mobile */
.owl-carousel-custom-nav,
.owl-mobile-custom-nav
{
	display:flex;
	justify-content:space-between;
	align-items:center;
	margin-top:15px;
	padding:0 20px;
}
.owl-carousel-custom-nav button,
.owl-mobile-custom-nav button
{
	background:#3d3d3d;
	color:#fff;
	border:2px solid transparent;
	border-radius:50%;
	width:40px;
	height:40px;
	font-size:18px;
	cursor:pointer;
	display:flex;
	align-items:center;
	justify-content:center;
	transition:all .3s ease;
	font-weight:700;
}
.owl-carousel-custom-nav button:hover,
.owl-mobile-custom-nav button:hover
{
	background:#666;
	border-color:#900;
	transform:scale(1.1);
}
.owl-carousel-custom-nav .slide-counter,
.owl-mobile-custom-nav .slide-counter
{
	font-size:16px;
	font-weight:700;
	color:#333;
	padding:8px 16px;
	background:#f0f0f0;
	border-radius:20px;
	min-width:80px;
	text-align:center;
}
/* Скрываем dots для .owl-carousel на всех устройствах */
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
}`;

// JavaScript функции
const carouselNavFunctions = `
		// Инициализация кастомной навигации для .owl-carousel
		function initCarouselCustomNav(carousel) {
			if (!carousel || !window.jQuery) return;
			var $ = window.jQuery;
			var $carousel = $(carousel);
			if (!$carousel.length) return;
			
			var $nav = $carousel.siblings('.owl-carousel-custom-nav');
			if ($nav.length === 0) {
				var navHtml = '<div class="owl-carousel-custom-nav">' +
					'<button class="owl-carousel-prev" aria-label="Previous slide">‹</button>' +
					'<span class="slide-counter">1 / 1</span>' +
					'<button class="owl-carousel-next" aria-label="Next slide">›</button>' +
					'</div>';
				$nav = $(navHtml);
				$carousel.after($nav);
			}
			
			updateCarouselCustomNav(carousel);
			
			// Обработчики кликов
			$nav.find('.owl-carousel-prev').off('click.carouselNav').on('click.carouselNav', function(e) {
				e.preventDefault();
				e.stopPropagation();
				$carousel.trigger('prev.owl.carousel');
			});
			$nav.find('.owl-carousel-next').off('click.carouselNav').on('click.carouselNav', function(e) {
				e.preventDefault();
				e.stopPropagation();
				$carousel.trigger('next.owl.carousel');
			});
		}
		// Обновление счетчика слайдов для .owl-carousel
		function updateCarouselCustomNav(carousel) {
			if (!carousel || !window.jQuery) return;
			var $ = window.jQuery;
			var $carousel = $(carousel);
			if (!$carousel.length) return;
			
			var $nav = $carousel.siblings('.owl-carousel-custom-nav');
			if ($nav.length) {
				try {
					var $active = $carousel.find('.owl-item.active').first();
					if (!$active.length) return;
					
					var current = $active.index() + 1;
					var total = $carousel.find('.owl-item:not(.cloned)').length;
					
					// Если loop включен, нужно правильно считать текущий слайд
					var owlData = $carousel.data('owl.carousel');
					if (owlData && owlData.settings && owlData.settings.loop && typeof owlData.relative === 'function') {
						var realIndex = owlData.relative($active.index());
						if (realIndex !== undefined && realIndex !== null) {
							current = realIndex + 1;
						}
					}
					
					if (total > 0 && current > 0) {
						$nav.find('.slide-counter').text(current + ' / ' + total);
					}
				} catch (e) {
					console.warn('Error updating carousel nav counter:', e);
				}
			}
		}`;

htmlFiles.forEach(file => {
  const filePath = path.join(__dirname, '..', file);
  if (!fs.existsSync(filePath)) {
    console.log(`File not found: ${file}`);
    return;
  }
  
  let content = fs.readFileSync(filePath, 'utf8');
  let modified = false;
  
  // Заменить CSS для кастомной навигации
  if (content.includes('.owl-mobile-custom-nav') && !content.includes('.owl-carousel-custom-nav')) {
    const oldCSSPattern = /\/\* Кастомная мобильная навигация для \.owl-mobile \*\/[\s\S]*?@media screen and \(max-width:767px\)[\s\S]*?\}/;
    if (oldCSSPattern.test(content)) {
      content = content.replace(oldCSSPattern, newCSS);
      modified = true;
    }
  }
  
  // Добавить CSS если его нет
  if (!content.includes('.owl-carousel-custom-nav')) {
    const cssInsertPoint = content.indexOf('/* На мобильных устройствах');
    if (cssInsertPoint !== -1) {
      content = content.substring(0, cssInsertPoint) + newCSS + '\n' + content.substring(cssInsertPoint);
      modified = true;
    }
  }
  
  // Обновить инициализацию .owl-carousel
  const owlCarouselPattern = /(\$\("\.owl-carousel"\)\.owlCarousel\(\{[\s\S]*?dots:\s*)true([\s\S]*?onInitialized:\s*)(addAriaLabelsToDots)([\s\S]*?\}\));/;
  if (owlCarouselPattern.test(content) && !content.includes('initCarouselCustomNav')) {
    content = content.replace(owlCarouselPattern, (match, p1, p2, p3, p4) => {
      return p1 + 'false' + p2 + `function() {
				` + p3 + `();
				initCarouselCustomNav(this);
			},
			onChanged: function(event) {
				updateCarouselCustomNav(event.target);
			}` + p4;
    });
    modified = true;
  }
  
  // Добавить функции JavaScript
  if (!content.includes('function initCarouselCustomNav')) {
    const jsInsertPoint = content.indexOf('function initMobileCustomNav');
    if (jsInsertPoint !== -1) {
      content = content.substring(0, jsInsertPoint) + carouselNavFunctions + '\n\t\t' + content.substring(jsInsertPoint);
      modified = true;
    }
  }
  
  // Обновить обработчики событий
  if (!content.includes('$(".owl-carousel").on("changed.owl.carousel"')) {
    const eventHandlerPattern = /(\$\("\.owl-mobile"\)\.on\("changed\.owl\.carousel", function\(event\) \{[\s\S]*?\}\);)/;
    if (eventHandlerPattern.test(content)) {
      content = content.replace(eventHandlerPattern, (match) => {
        return '$(".owl-carousel").on("changed.owl.carousel", function(event) {\n\t\t\tupdateCarouselCustomNav(event.target);\n\t\t});\n\t\t' + match;
      });
      modified = true;
    }
  }
  
  // Обновить обработчик initialized
  const initPattern = /(\$\("\.slider-888-slider, \.owl-carousel, \.owl-mobile"\)\.on\("initialized\.owl\.carousel", function\(\) \{[\s\S]*?if \(\$\(this\)\.hasClass\('owl-mobile'\)\) \{[\s\S]*?\}[\s\S]*?\}\);)/;
  if (initPattern.test(content) && !content.includes('hasClass(\'owl-carousel\')')) {
    content = content.replace(initPattern, (match) => {
      return match.replace('if ($(this).hasClass(\'owl-mobile\')) {', `if ($(this).hasClass('owl-carousel')) {
				initCarouselCustomNav(this);
			} else if ($(this).hasClass('owl-mobile')) {`);
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
