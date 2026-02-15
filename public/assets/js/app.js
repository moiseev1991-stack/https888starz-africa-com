/**
 * Static site: Fancybox only. Owl sliders (.owl-carousel, .owl-mobile, .slider-888-slider)
 * are initialized once by the inline script in each page (same as local) so dots work on server.
 */
(function () {
  function init() {
    if (typeof jQuery === 'undefined') return;
    var $ = jQuery;

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
