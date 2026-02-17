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

const oldFunction = `		function updateMobileCustomNav(carousel) {
			var $carousel = $(carousel);
			var $nav = $carousel.siblings('.owl-mobile-custom-nav');
			if ($nav.length) {
				var current = $carousel.find('.owl-item.active').index() + 1;
				var total = $carousel.find('.owl-item:not(.cloned)').length;
				$nav.find('.slide-counter').text(current + ' / ' + total);
			}
		}`;

const newFunction = `		function updateMobileCustomNav(carousel) {
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

htmlFiles.forEach(file => {
  const filePath = path.join(__dirname, '..', file);
  if (!fs.existsSync(filePath)) {
    console.log(`File not found: ${file}`);
    return;
  }
  
  let content = fs.readFileSync(filePath, 'utf8');
  let modified = false;
  
  // Заменить старую функцию на новую
  if (content.includes('function updateMobileCustomNav') && 
      !content.includes('$carousel.data(\'owl.carousel\')')) {
    content = content.replace(oldFunction, newFunction);
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
