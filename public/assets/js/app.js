/**
 * Static site: init sliders and Fancybox (one source of truth).
 * Load after jQuery, Owl Carousel, Fancybox.
 */
(function () {
  function init() {
    if (typeof jQuery === 'undefined') return;
    var $ = jQuery;

    // Passive listeners fix for Owl
    var originalAddEventListener = EventTarget.prototype.addEventListener;
    EventTarget.prototype.addEventListener = function (type, listener, options) {
      if (typeof options === 'object' && options.passive === true) {
        options.passive = false;
      }
      originalAddEventListener.call(this, type, listener, options);
    };

    $('.owl-carousel').owlCarousel({
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
      ltr: true
    });

    $('.owl-mobile').owlCarousel({
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
      responsive: {
        0: { items: 1, margin: 10 },
        600: { items: 2, margin: 20 },
        1000: { items: 3, margin: 30 }
      }
    });

    $('.slider-888-slider').owlCarousel({
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
      ltr: true
    });

    if ($.fancybox) {
      $('[data-fancybox="gallery"]:not(.owl-item.cloned [data-fancybox="gallery"])').fancybox({
        buttons: ['zoom', 'share', 'slideShow', 'fullScreen', 'download', 'thumbs', 'close'],
        loop: true,
        protect: true,
        animationEffect: 'zoom',
        transitionEffect: 'fade'
      });
      $('[data-fancybox="gallerymob"]:not(.owl-item.cloned [data-fancybox="gallerymob"])').fancybox({
        buttons: ['zoom', 'share', 'slideShow', 'fullScreen', 'download', 'thumbs', 'close'],
        loop: true,
        protect: true,
        animationEffect: 'zoom',
        transitionEffect: 'fade'
      });
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
