        <?php get_template_part('partials/footer-cta'); ?>
        
        <?php get_template_part('partials/footer'); ?>

        <div class="clear"></div>
    </div>

<?php // PageSpeed: font-display swap для Font Awesome (fonts/fontawesome-webfont.woff2) ?>
<style>@font-face{font-family:'FontAwesome';font-display:swap}@font-face{font-family:'Font Awesome 5 Free';font-display:swap}@font-face{font-family:'Font Awesome 5 Brands';font-display:swap}</style>

<!-- jQuery уже загружен через WordPress wp_enqueue_script, не дублируем -->
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" /></noscript>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
<script>
	// Отключение пассивных обработчиков событий для всего документа
	var originalAddEventListener = EventTarget.prototype.addEventListener;
	EventTarget.prototype.addEventListener = function(type, listener, options) {
		if (typeof options === "object" && options.passive === true) {
			options.passive = false; // Отключаем passive
		}
		originalAddEventListener.call(this, type, listener, options);
	};

	$(document).ready(function(){
		function owlDotsA11y(carouselEl) {
			var $el = $(carouselEl);
			$el.find('.owl-dot').each(function(i) {
				var $dot = $(this);
				$dot.attr('aria-label', 'Slide ' + (i + 1));
				$dot.attr('aria-current', $dot.hasClass('active') ? 'true' : null);
			});
		}
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
			rtl: false,
			ltr: true,
			onInitialized: function() { owlDotsA11y(this.$element); },
			onChanged: function() { owlDotsA11y(this.$element); }
		});
		$(".owl-mobile").owlCarousel({
			items: 3,
			margin: 30,
			loop: true,
			nav: false,
			dots: true,
			rtl: false,
			ltr: true,
			autoplay: true,
			autoplayTimeout: 5000,
			touchDrag: true,
			mouseDrag: true,
			onInitialized: function() { owlDotsA11y(this.$element); },
			onChanged: function() { owlDotsA11y(this.$element); },
			responsive: {
				0: {
					items: 1, // Для экранов шириной 0px и больше (например, для мобильных устройств)
					margin: 10, // Меньший отступ
				},
				600: {
					items: 2, // Для экранов шириной 600px и больше (например, для планшетов)
					margin: 20, // Средний отступ
				},
				1000: {
					items: 3, // Для экранов шириной 1000px и больше (например, для десктопов)
					margin: 30, // Стандартный отступ
				}
			}
		});
		// Исключаем клонированные элементы из выборки
$('[data-fancybox="gallery"]:not(.owl-item.cloned [data-fancybox="gallery"])').fancybox({
    buttons: [
        "zoom",
        "share",
        "slideShow",
        "fullScreen",
        "download",
        "thumbs",
        "close"
    ],
    loop: true,
    protect: true,
    animationEffect: "zoom",
    transitionEffect: "fade"
});

// То же самое для мобильной версии
$('[data-fancybox="gallerymob"]:not(.owl-item.cloned [data-fancybox="gallerymob"])').fancybox({
    buttons: [
        "zoom",
        "share",
        "slideShow",
        "fullScreen",
        "download",
        "thumbs",
        "close"
    ],
    loop: true,
    protect: true,
    animationEffect: "zoom",
    transitionEffect: "fade"
});


		<?php if (get_field('visibility_baner_main')) { ?>
		
		$(".slider-888-slider").owlCarousel({
			items: 1,
			margin: 30,
			loop: true,
			nav: false,
			dots: true,
			rtl: false,
			ltr: true,
			autoplay: true,
			autoplayTimeout: 5000,
			touchDrag: true,
			mouseDrag: true,
			onInitialized: function() { owlDotsA11y(this.$element); },
			onChanged: function() { owlDotsA11y(this.$element); }
		});
		<?php } ?>
	});
</script>


    <!-- end: #wrapper --> 

    <!-- W3TC-include-css -->
    <!-- W3TC-include-js-head -->

    <?php wp_footer(); ?>     

    <?php if( function_exists('epcl_render_demo_button') ) epcl_render_demo_button(); ?>
    
    </body>
</html>
