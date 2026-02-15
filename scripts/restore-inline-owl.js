/**
 * Restore the inline Owl/Fancybox init block that was removed (user request).
 */
const fs = require('fs');
const path = require('path');

const publicDir = path.join(__dirname, '..', 'public');
const htmlFiles = [
  'index.html',
  'about/index.html', 'contacts/index.html', 'terms/index.html',
  'responsible/index.html', 'privacy-policy/index.html', 'self-exclusion/index.html',
  'dispute-resolution/index.html', 'fairness-rng-testing-methods/index.html',
  'accounts-withdrawals-and-bonuses/index.html', 'cookies/index.html',
  'registration/index.html', 'apk/index.html', 'promo-code/index.html'
];

const INLINE_BLOCK = `<script>
	// Отключение пассивных обработчиков событий для всего документа
	var originalAddEventListener = EventTarget.prototype.addEventListener;
	EventTarget.prototype.addEventListener = function(type, listener, options) {
		if (typeof options === "object" && options.passive === true) {
			options.passive = false; // Отключаем passive
		}
		originalAddEventListener.call(this, type, listener, options);
	};

	function initOwlAndFancybox() {
		var $ = window.jQuery;
		if (!$ || !$.fn.owlCarousel) return;
		// Переинициализация: снести старый Owl, чтобы слайдер отображался на всех страницах
		$(".owl-carousel, .owl-mobile, .slider-888-slider").each(function() {
			if ($(this).hasClass("owl-loaded")) {
				$(this).trigger("destroy.owl.carousel");
				$(this).removeClass("owl-loaded owl-drag owl-grab");
			}
		});
		$(".owl-carousel").owlCarousel({
			items: 1,
			margin: 30,
			loop: true,
			nav: false,
			dots: true,
			autoplay: true,
			autoplayTimeout: 5000,
			touchDrag: true,
			mouseDrag: true,
			dots: true,
			rtl:false,
			ltr:true,
		});
		$(".owl-mobile").owlCarousel({
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
			}
		});
		// Исключаем клонированные элементы из выборки
$('[data-fancybox="gallery"]:not(.owl-item.cloned [data-fancybox="gallery"])').fancybox({
    buttons: ["zoom","share","slideShow","fullScreen","download","thumbs","close"],
    loop: true,
    protect: true,
    animationEffect: "zoom",
    transitionEffect: "fade"
});
$('[data-fancybox="gallerymob"]:not(.owl-item.cloned [data-fancybox="gallerymob"])').fancybox({
    buttons: ["zoom","share","slideShow","fullScreen","download","thumbs","close"],
    loop: true,
    protect: true,
    animationEffect: "zoom",
    transitionEffect: "fade"
});

		$(".slider-888-slider").owlCarousel({
			items: 1,
			margin: 30,
			loop: true,
			nav: false,
			rtl:false,
			ltr:true,
			dots: true,
			autoplay: true,
			autoplayTimeout: 5000,
			touchDrag: true,
			mouseDrag: true,
			dots: true,
		});
	}

	$(document).ready(function(){
		if (window.jQuery && window.jQuery.fn.owlCarousel) {
			initOwlAndFancybox();
		} else {
			var s = document.createElement("script");
			s.src = "https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js";
			s.onload = function() { window.jQuery && initOwlAndFancybox(); };
			document.head.appendChild(s);
		}
	});
</script>


`;

htmlFiles.forEach((file) => {
  const filePath = path.join(publicDir, file);
  if (!fs.existsSync(filePath)) {
    console.warn('Skip (not found):', file);
    return;
  }
  let html = fs.readFileSync(filePath, 'utf8');

  // Replace: Fancybox script + app.js (no defer)  =>  Fancybox script + inline block
  const pattern = /<script src="https:\/\/cdnjs\.cloudflare\.com\/ajax\/libs\/fancybox\/3\.5\.7\/jquery\.fancybox\.min\.js"><\/script>\s*\n<script src="\/assets\/js\/app\.js"><\/script>\s*\n/;
  if (pattern.test(html)) {
    html = html.replace(pattern, '<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>\n' + INLINE_BLOCK);
  } else {
    // Replace old inline block (direct owl init) with new one (load Owl from CDN if missing)
    const oldBlock = /<script>\s*\/\/ Отключение пассивных[\s\S]*?\t\t\}\);\s*\n\s*\t\t\t\}\);\s*\n\s*<\/script>/;
    if (oldBlock.test(html)) {
      html = html.replace(oldBlock, INLINE_BLOCK.trim());
    }
  }

  // Add app.js with defer before </body> if not present (we removed it from after Fancybox)
  if (!/<script src="\/assets\/js\/app\.js" defer><\/script>/.test(html)) {
    html = html.replace(/\s*<\/body>/, '\n<script src="/assets/js/app.js" defer></script>\n</body>');
  }

  fs.writeFileSync(filePath, html, 'utf8');
  console.log('Restored:', file);
});
console.log('Done.');
