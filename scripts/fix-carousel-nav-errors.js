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

const oldCarouselInit = `\t\t\$(".owl-carousel").owlCarousel({
			items: 1,
			margin: 30,
			loop: true,
			nav: false,
			dots: false,
			dotsEach: false,
			autoplay: true,
			autoplayTimeout: 5000,
			touchDrag: true,
			mouseDrag: true,
			rtl:false,
			ltr:true,
			onInitialized: function() {
				addAriaLabelsToDots();
				initCarouselCustomNav(this);
			},
			onChanged: function(event) {
				updateCarouselCustomNav(event.target);
			}
		});`;

const newCarouselInit = `\t\t\$(".owl-carousel").each(function() {
			var \$carousel = \$(this);
			\$carousel.owlCarousel({
				items: 1,
				margin: 30,
				loop: true,
				nav: false,
				dots: false,
				dotsEach: false,
				autoplay: true,
				autoplayTimeout: 5000,
				touchDrag: true,
				mouseDrag: true,
				rtl:false,
				ltr:true,
				onInitialized: function(event) {
					addAriaLabelsToDots();
					initCarouselCustomNav(\$carousel[0]);
				},
				onChanged: function(event) {
					updateCarouselCustomNav(\$carousel[0]);
				}
			});
		});`;

const oldMobileInit = `\t\t\$(".owl-mobile").owlCarousel({
			items: 3,
			margin: 30,
			loop: true,
			nav: false,
			dots: true,
			rtl:false,
			ltr:true,
			autoplay: true,
			autoplayTimeout: 5000,
			touchDrag: true,
			mouseDrag: true,
			responsive: {
				0: { items: 1, margin: 10 },
				600: { items: 2, margin: 20 },
				1000: { items: 3, margin: 30 }
			},
			onInitialized: function() {
				addAriaLabelsToDots();
				initMobileCustomNav(this);
			},
			onChanged: function(event) {
				updateMobileCustomNav(event.target);
			}
		});`;

const newMobileInit = `\t\t\$(".owl-mobile").each(function() {
			var \$carousel = \$(this);
			\$carousel.owlCarousel({
				items: 3,
				margin: 30,
				loop: true,
				nav: false,
				dots: true,
				rtl:false,
				ltr:true,
				autoplay: true,
				autoplayTimeout: 5000,
				touchDrag: true,
				mouseDrag: true,
				responsive: {
					0: { items: 1, margin: 10 },
					600: { items: 2, margin: 20 },
					1000: { items: 3, margin: 30 }
				},
				onInitialized: function(event) {
					addAriaLabelsToDots();
					initMobileCustomNav(\$carousel[0]);
				},
				onChanged: function(event) {
					updateMobileCustomNav(\$carousel[0]);
				}
			});
		});`;

const oldCarouselNavFunction = `\t\t// Инициализация кастомной навигации для .owl-carousel
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
			}`;

const newCarouselNavFunction = `\t\t// Инициализация кастомной навигации для .owl-carousel
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
				try {
					if ($carousel[0] && $carousel[0].parentNode) {
						$carousel[0].parentNode.insertBefore($nav[0], $carousel[0].nextSibling);
					} else {
						$carousel.after($nav);
					}
				} catch (e) {
					console.warn('Error inserting carousel nav:', e);
					return;
				}
			}`;

const oldMobileNavFunction = `\t\t// Инициализация кастомной мобильной навигации
		function initMobileCustomNav(carousel) {
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
			}`;

const newMobileNavFunction = `\t\t// Инициализация кастомной мобильной навигации
		function initMobileCustomNav(carousel) {
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
				try {
					if ($carousel[0] && $carousel[0].parentNode) {
						$carousel[0].parentNode.insertBefore($nav[0], $carousel[0].nextSibling);
					} else {
						$carousel.after($nav);
					}
				} catch (e) {
					console.warn('Error inserting mobile nav:', e);
					return;
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
  
  // Заменить инициализацию .owl-carousel
  if (content.includes('$(".owl-carousel").owlCarousel({') && 
      !content.includes('$(".owl-carousel").each(function()')) {
    const pattern = /\$\(["']\.owl-carousel["']\)\.owlCarousel\(\{[\s\S]*?onChanged:\s*function\(event\)\s*\{[\s\S]*?updateCarouselCustomNav\(event\.target\);[\s\S]*?\}[\s\S]*?\}\);?/;
    if (pattern.test(content)) {
      content = content.replace(pattern, newCarouselInit);
      modified = true;
    }
  }
  
  // Заменить инициализацию .owl-mobile
  if (content.includes('$(".owl-mobile").owlCarousel({') && 
      !content.includes('$(".owl-mobile").each(function()')) {
    const pattern = /\$\(["']\.owl-mobile["']\)\.owlCarousel\(\{[\s\S]*?onChanged:\s*function\(event\)\s*\{[\s\S]*?updateMobileCustomNav\(event\.target\);[\s\S]*?\}[\s\S]*?\}\);?/;
    if (pattern.test(content)) {
      content = content.replace(pattern, newMobileInit);
      modified = true;
    }
  }
  
  // Заменить функцию initCarouselCustomNav
  if (content.includes('$carousel.after($nav);') && 
      content.includes('function initCarouselCustomNav') &&
      !content.includes('$carousel[0].parentNode.insertBefore')) {
    content = content.replace(oldCarouselNavFunction, newCarouselNavFunction);
    modified = true;
  }
  
  // Заменить функцию initMobileCustomNav
  if (content.includes('$carousel.after($nav);') && 
      content.includes('function initMobileCustomNav') &&
      !content.includes('$carousel[0].parentNode.insertBefore')) {
    content = content.replace(oldMobileNavFunction, newMobileNavFunction);
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
