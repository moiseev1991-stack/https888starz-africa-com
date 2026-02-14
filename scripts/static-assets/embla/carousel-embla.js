/**
 * Embla Carousel init for static site: .owl-mobile, .owl-carousel, .slider-888-slider.
 * Wraps in viewport/container, adds dots (clickable), autoplay, RTL. No jQuery.
 */
(function () {
  'use strict';

  var ROOT_SELECTORS = ['.owl-carousel', '.owl-mobile', '.slider-888-slider'];
  var SLIDE_CLASSES = { '.owl-carousel': 'slide', '.owl-mobile': 'mobile-slide', '.slider-888-slider': 'slider-888-slide' };
  var AUTOPLAY_MS = 3500;
  var REDUCED_MOTION = typeof window !== 'undefined' && window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function qsa(el, sel) { return Array.prototype.slice.call(el.querySelectorAll(sel)); }

  function getSlideClass(root) {
    for (var i = 0; i < ROOT_SELECTORS.length; i++) {
      if (root.classList.contains(ROOT_SELECTORS[i].slice(1))) return SLIDE_CLASSES[ROOT_SELECTORS[i]];
    }
    return null;
  }

  function wrapAndInit(root) {
    if (root.getAttribute('data-embla-inited')) return;
    var slideClass = getSlideClass(root);
    if (!slideClass) return;
    var slides = qsa(root, '.' + slideClass);
    if (slides.length === 0) return;

    var rtl = root.getAttribute('dir') === 'rtl' || document.documentElement.getAttribute('dir') === 'rtl';

    var viewport = document.createElement('div');
    viewport.className = 'embla__viewport';
    var container = document.createElement('div');
    container.className = 'embla__container';

    slides.forEach(function (slide) {
      var wrap = document.createElement('div');
      wrap.className = 'embla__slide';
      wrap.appendChild(slide);
      container.appendChild(wrap);
    });
    viewport.appendChild(container);

    while (root.firstChild) root.removeChild(root.firstChild);
    root.appendChild(viewport);

    var dotsWrap = document.createElement('div');
    dotsWrap.className = 'embla__dots';
    dotsWrap.setAttribute('role', 'tablist');
    dotsWrap.setAttribute('aria-label', 'Slides');
    root.appendChild(dotsWrap);

    if (typeof EmblaCarousel === 'undefined') return;
    var embla = EmblaCarousel(viewport, {
      axis: 'x',
      direction: rtl ? 'rtl' : 'ltr',
      loop: true,
      align: 'start',
      containScroll: 'trimSnaps'
    });

    for (var i = 0; i < slides.length; i++) {
      var btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'embla__dot';
      btn.setAttribute('aria-label', 'Slide ' + (i + 1));
      btn.setAttribute('aria-current', i === 0 ? 'true' : 'false');
      (function (j) {
        btn.addEventListener('click', function () { embla.scrollTo(j); });
      })(i);
      dotsWrap.appendChild(btn);
    }

    function updateDots() {
      var idx = embla.selectedScrollSnap();
      dotsWrap.querySelectorAll('.embla__dot').forEach(function (dot, d) {
        dot.setAttribute('aria-current', d === idx ? 'true' : 'false');
      });
    }
    embla.on('select', updateDots);

    var autoplayTimer = null;
    if (!REDUCED_MOTION) {
      function play() {
        autoplayTimer = setInterval(function () { embla.scrollNext(); }, AUTOPLAY_MS);
      }
      function stop() {
        if (autoplayTimer) { clearInterval(autoplayTimer); autoplayTimer = null; }
      }
      viewport.addEventListener('mouseenter', stop);
      viewport.addEventListener('mouseleave', play);
      document.addEventListener('visibilitychange', function () {
        if (document.hidden) stop(); else play();
      });
      play();
    }

    root.classList.add('embla');
    root.setAttribute('data-embla-inited', 'true');
  }

  function run() {
    ROOT_SELECTORS.forEach(function (sel) {
      qsa(document, sel).forEach(function (el) {
        wrapAndInit(el);
      });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () { run(); setTimeout(run, 150); });
  } else {
    run();
    setTimeout(run, 150);
  }
  window.addEventListener('load', run);
})();
