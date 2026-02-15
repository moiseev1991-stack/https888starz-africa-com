        <?php get_template_part('partials/footer-cta'); ?>
        
        <?php get_template_part('partials/footer'); ?>

        <div class="clear"></div>
    </div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
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
		$(".owl-carousel").owlCarousel({
			items: 1, // Количество отображаемых слайдов
			margin: 30, // Отступ между слайдами
			loop: true, // Бесконечный цикл
			nav: false, // Кнопки навигации
			dots: true, // Точки навигации
			autoplay: true, // Автовоспроизведение
			autoplayTimeout: 5000, // Интервал автопрокрутки
			touchDrag: true, // Это можно использовать для включения и отключения drag
			mouseDrag: true,
			dots: true,
			rtl:false,
			ltr:true,
		});
		$(".owl-mobile").owlCarousel({
			items: 3, // Количество отображаемых слайдов по умолчанию
			margin: 30, // Отступ между слайдами
			loop: true, // Бесконечный цикл
			nav: false, // Кнопки навигации
			dots: true, // Точки навигации
			rtl:false,
			ltr:true,
			autoplay: true, // Автовоспроизведение
			autoplayTimeout: 5000, // Интервал автопрокрутки
			touchDrag: true, // Включение возможности перетаскивания
			mouseDrag: true, // Включение перетаскивания мышью
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
			items: 1, // Количество отображаемых слайдов
			margin: 30, // Отступ между слайдами
			loop: true, // Бесконечный цикл
			nav: false, // Кнопки навигации
			rtl:false,
			ltr:true,
			dots: true, // Точки навигации
			autoplay: true, // Автовоспроизведение
			autoplayTimeout: 5000, // Интервал автопрокрутки
			touchDrag: true, // Это можно использовать для включения и отключения drag
			mouseDrag: true,
			dots: true,
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
