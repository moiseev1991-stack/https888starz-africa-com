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

// Исправленная функция initMobileCustomNav
const oldInitFunction = `		function initMobileCustomNav(carousel) {
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
		}`;

const newInitFunction = `		function initMobileCustomNav(carousel) {
			if (!carousel || !window.jQuery) return;
			var $ = window.jQuery;
			var $carousel = $(carousel);
			if (!$carousel.length) return;
			
			var $nav = $carousel.siblings('.owl-mobile-custom-nav');
			if ($nav.length === 0) {
				var navHtml = '<div class="owl-mobile-custom-nav">' +
					'<button class="owl-mobile-prev" aria-label="Previous slide">‹</button>' +
					'<span class="slide-counter">1 / 1</span>' +
					'<button class="owl-mobile-next" aria-label="Next slide">›</button>' +
					'</div>';
				$nav = $(navHtml);
				$carousel.after($nav);
			}
			
			updateMobileCustomNav(carousel);
			
			// Обработчики кликов
			$nav.find('.owl-mobile-prev').off('click.mobileNav').on('click.mobileNav', function(e) {
				e.preventDefault();
				e.stopPropagation();
				$carousel.trigger('prev.owl.carousel');
			});
			$nav.find('.owl-mobile-next').off('click.mobileNav').on('click.mobileNav', function(e) {
				e.preventDefault();
				e.stopPropagation();
				$carousel.trigger('next.owl.carousel');
			});
		}`;

// Исправленная функция updateMobileCustomNav
const oldUpdateFunction = `		function updateMobileCustomNav(carousel) {
			var $carousel = $(carousel);
			var $nav = $carousel.siblings('.owl-mobile-custom-nav');
			if ($nav.length) {
				var $active = $carousel.find('.owl-item.active').first();
				var current = $active.index() + 1;
				var total = $carousel.find('.owl-item:not(.cloned)').length;
				// Если loop включен, нужно правильно считать текущий слайд
				if ($carousel.data('owl.carousel') && $carousel.data('owl.carousel').settings.loop) {
					var realIndex = $carousel.data('owl.carousel').relative($active.index());
					current = realIndex + 1;
				}
				$nav.find('.slide-counter').text(current + ' / ' + total);
			}
		}`;

const newUpdateFunction = `		function updateMobileCustomNav(carousel) {
			if (!carousel || !window.jQuery) return;
			var $ = window.jQuery;
			var $carousel = $(carousel);
			if (!$carousel.length) return;
			
			var $nav = $carousel.siblings('.owl-mobile-custom-nav');
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
					console.warn('Error updating mobile nav counter:', e);
				}
			}
		}`;

// Удалить код отключения passive listeners
const passiveCode = `	// Отключение пассивных обработчиков событий для всего документа
	var originalAddEventListener = EventTarget.prototype.addEventListener;
	EventTarget.prototype.addEventListener = function(type, listener, options) {
		if (typeof options === "object" && options.passive === true) {
			options.passive = false; // Отключаем passive
		}
		originalAddEventListener.call(this, type, listener, options);
	};`;

const passiveReplacement = `	// Отключение пассивных обработчиков событий только для нужных элементов (не для всех)
	// Это убирает предупреждения о non-passive listeners`;

htmlFiles.forEach(file => {
  const filePath = path.join(__dirname, '..', file);
  if (!fs.existsSync(filePath)) {
    console.log(`File not found: ${file}`);
    return;
  }
  
  let content = fs.readFileSync(filePath, 'utf8');
  let modified = false;
  
  // Заменить функцию initMobileCustomNav
  if (content.includes('function initMobileCustomNav') && 
      !content.includes('if (!carousel || !window.jQuery) return')) {
    content = content.replace(oldInitFunction, newInitFunction);
    modified = true;
  }
  
  // Заменить функцию updateMobileCustomNav
  if (content.includes('function updateMobileCustomNav') && 
      !content.includes('if (!carousel || !window.jQuery) return')) {
    content = content.replace(oldUpdateFunction, newUpdateFunction);
    modified = true;
  }
  
  // Убрать код отключения passive listeners
  if (content.includes('EventTarget.prototype.addEventListener') && 
      content.includes('options.passive = false')) {
    content = content.replace(passiveCode, passiveReplacement);
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
