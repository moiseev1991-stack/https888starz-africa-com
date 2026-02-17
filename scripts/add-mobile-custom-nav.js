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

// CSS для добавления
const mobileNavCSS = `/* Кастомная мобильная навигация для .owl-mobile */
.owl-mobile-custom-nav
{
	display:none;
	justify-content:space-between;
	align-items:center;
	margin-top:15px;
	padding:0 20px;
}
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
.owl-mobile-custom-nav button:hover
{
	background:#666;
	border-color:#900;
	transform:scale(1.1);
}
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
/* На мобильных устройствах показываем кастомную навигацию и скрываем dots */
@media screen and (max-width:767px)
{
	.owl-mobile .owl-dots
	{
		display:none !important;
	}
	.owl-mobile-custom-nav
	{
		display:flex !important;
	}
}`;

// JavaScript функции для добавления
const mobileNavJS = `
		// Инициализация кастомной мобильной навигации
		function initMobileCustomNav(carousel) {
			var $carousel = $(carousel);
			var $nav = $carousel.siblings('.owl-mobile-custom-nav');
			if ($nav.length === 0) {
				$nav = $('<div class="owl-mobile-custom-nav"></div>');
				$nav.append('<button class="owl-mobile-prev" aria-label="Previous slide">‹</button>');
				$nav.append('<span class="slide-counter">1 / 1</span>');
				$nav.append('<button class="owl-mobile-next" aria-label="Next slide">›</button>');
				$carousel.after($nav);
			}
			updateMobileCustomNav(carousel);
			// Обработчики кликов
			$nav.find('.owl-mobile-prev').off('click').on('click', function() {
				$carousel.trigger('prev.owl.carousel');
			});
			$nav.find('.owl-mobile-next').off('click').on('click', function() {
				$carousel.trigger('next.owl.carousel');
			});
		}
		// Обновление счетчика слайдов
		function updateMobileCustomNav(carousel) {
			var $carousel = $(carousel);
			var $nav = $carousel.siblings('.owl-mobile-custom-nav');
			if ($nav.length) {
				var current = $carousel.find('.owl-item.active').index() + 1;
				var total = $carousel.find('.owl-item:not(.cloned)').length;
				$nav.find('.slide-counter').text(current + ' / ' + total);
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
  
  // Добавить CSS перед закрывающим тегом style или перед другим CSS
  if (!content.includes('.owl-mobile-custom-nav')) {
    // Найти место после стилей для owl-mobile
    const cssInsertPoint = content.indexOf('.owl-mobile\n{');
    if (cssInsertPoint !== -1) {
      const insertAfter = content.indexOf('}', cssInsertPoint) + 1;
      if (insertAfter > 0) {
        content = content.substring(0, insertAfter) + '\n' + mobileNavCSS + '\n' + content.substring(insertAfter);
        modified = true;
      }
    } else {
      // Альтернативный поиск
      const altPoint = content.indexOf('/* Навигация (стрелки)');
      if (altPoint !== -1) {
        const insertAfter = content.indexOf('}', altPoint) + 1;
        if (insertAfter > 0) {
          content = content.substring(0, insertAfter) + '\n' + mobileNavCSS + '\n' + content.substring(insertAfter);
          modified = true;
        }
      }
    }
  }
  
  // Обновить инициализацию owl-mobile
  const owlMobilePattern = /(\$\("\.owl-mobile"\)\.owlCarousel\(\{[\s\S]*?onInitialized:\s*)(addAriaLabelsToDots)([\s\S]*?\}\));/;
  if (owlMobilePattern.test(content) && !content.includes('initMobileCustomNav')) {
    content = content.replace(owlMobilePattern, (match, p1, p2, p3) => {
      return p1 + `function() {
				` + p2 + `();
				initMobileCustomNav(this);
			},
			onChanged: function(event) {
				updateMobileCustomNav(event.target);
			}` + p3;
    });
    modified = true;
  }
  
  // Добавить функции JavaScript
  if (!content.includes('function initMobileCustomNav')) {
    // Найти место после функции addAriaLabelsToDots
    const jsInsertPoint = content.indexOf('function addAriaLabelsToDots()');
    if (jsInsertPoint !== -1) {
      const insertAfter = content.indexOf('}\n\t\t$(".slider-888-slider', jsInsertPoint);
      if (insertAfter !== -1) {
        content = content.substring(0, insertAfter) + mobileNavJS + content.substring(insertAfter);
        modified = true;
      }
    }
  }
  
  // Добавить обработчики событий
  if (!content.includes('$(".owl-mobile").on("changed.owl.carousel"')) {
    const eventHandlerPattern = /(\$\("\.owl-carousel, \.owl-mobile"\)\.on\("initialized\.owl\.carousel", addAriaLabelsToDots\);)/;
    if (eventHandlerPattern.test(content)) {
      content = content.replace(eventHandlerPattern, (match) => {
        return match + '\n\t\t$(".owl-mobile").on("changed.owl.carousel", function(event) {\n\t\t\tupdateMobileCustomNav(event.target);\n\t\t});';
      });
      modified = true;
    }
  }
  
  // Обновить обработчик initialized
  const initPattern = /(\$\("\.slider-888-slider, \.owl-carousel, \.owl-mobile"\)\.on\("initialized\.owl\.carousel", function\(\) \{[\s\S]*?addAriaLabelsToDots\(\);[\s\S]*?\}\);)/;
  if (initPattern.test(content) && !content.includes('if ($(this).hasClass(\'owl-mobile\'))')) {
    content = content.replace(initPattern, (match) => {
      return match.replace('addAriaLabelsToDots();', `addAriaLabelsToDots();
			if ($(this).hasClass('owl-mobile')) {
				initMobileCustomNav(this);
			}`);
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
