/**
 * Carousel Lite — vanilla JS, no jQuery/Owl. Works with existing .owl-carousel, .owl-mobile, .slider-888-slider.
 * Autoplay, touch swipe, clickable dots, RTL. Keeps current HTML (mobile-slide, slide, slider-888-slide).
 */
(function () {
  'use strict';

  var SLIDE_SELECTORS = [
    '.owl-carousel .slide',
    '.owl-mobile .mobile-slide',
    '.slider-888-slider .slider-888-slide'
  ];
  var ROOT_SELECTORS = ['.owl-carousel', '.owl-mobile', '.slider-888-slider'];
  var DEFAULT_INTERVAL = 5000;
  var TRANSITION_MS = 280;

  function qs(el, sel) { return el.querySelector(sel); }
  function qsa(el, sel) { return Array.prototype.slice.call(el.querySelectorAll(sel)); }

  function getSlideSelector(root) {
    if (root.classList.contains('owl-carousel')) return '.slide';
    if (root.classList.contains('owl-mobile')) return '.mobile-slide';
    if (root.classList.contains('slider-888-slider')) return '.slider-888-slide';
    return null;
  }

  function initCarousel(root) {
    if (root.getAttribute('data-carousel-lite')) return;
    var slideSel = getSlideSelector(root);
    if (!slideSel) return;
    var slides = qsa(root, slideSel);
    if (slides.length === 0) return;

    var rtl = root.getAttribute('dir') === 'rtl' || document.documentElement.getAttribute('dir') === 'rtl';
    var count = slides.length;
    var index = 0;
    var autoplayTimer = null;
    var touchStartX = 0;
    var touchEndX = 0;

    var viewport = document.createElement('div');
    viewport.className = 'carousel-lite__viewport';
    viewport.style.overflow = 'hidden';

    var track = document.createElement('div');
    track.className = 'carousel-lite__track';
    track.style.display = 'flex';
    track.style.willChange = 'transform';
    track.style.transition = 'transform ' + TRANSITION_MS + 'ms ease';
    track.style.touchAction = 'pan-y';

    slides.forEach(function (slide) {
      var wrap = document.createElement('div');
      wrap.className = 'carousel-lite__slide';
      wrap.style.flex = '0 0 100%';
      wrap.style.minWidth = '0';
      wrap.style.margin = '0';
      wrap.appendChild(slide);
      track.appendChild(wrap);
    });

    viewport.appendChild(track);
    while (root.firstChild) root.removeChild(root.firstChild);
    root.appendChild(viewport);

    var dotsWrap = document.createElement('div');
    dotsWrap.className = 'carousel-lite__dots';
    dotsWrap.setAttribute('role', 'tablist');
    dotsWrap.setAttribute('aria-label', 'Slides');

    function getOffset() {
      var w = viewport.offsetWidth;
      return rtl ? index * w : -index * w;
    }

    function goTo(i) {
      index = (i % count + count) % count;
      track.style.transform = 'translateX(' + getOffset() + 'px)';
      dotsWrap.querySelectorAll('.carousel-lite__dot').forEach(function (dot, d) {
        dot.setAttribute('aria-current', d === index ? 'true' : 'false');
      });
    }

    for (var i = 0; i < count; i++) {
      var btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'carousel-lite__dot';
      btn.setAttribute('aria-label', 'Slide ' + (i + 1));
      btn.setAttribute('aria-current', i === 0 ? 'true' : 'false');
      btn.addEventListener('click', (function (j) { return function () { goTo(j); }; })(i));
      dotsWrap.appendChild(btn);
    }

    root.appendChild(dotsWrap);
    track.style.transform = 'translateX(' + getOffset() + 'px)';

    viewport.addEventListener('touchstart', function (e) {
      touchStartX = e.changedTouches ? e.changedTouches[0].screenX : e.screenX;
    }, { passive: true });
    viewport.addEventListener('touchend', function (e) {
      touchEndX = e.changedTouches ? e.changedTouches[0].screenX : e.screenX;
      var diff = touchStartX - touchEndX;
      if (Math.abs(diff) < 50) return;
      goTo(rtl ? index - (diff > 0 ? 1 : -1) : index + (diff > 0 ? 1 : -1));
    }, { passive: true });

    viewport.addEventListener('mouseenter', function () {
      if (autoplayTimer) { clearInterval(autoplayTimer); autoplayTimer = null; }
    });
    viewport.addEventListener('mouseleave', function () {
      autoplayTimer = setInterval(function () { goTo(index + 1); }, DEFAULT_INTERVAL);
    });
    autoplayTimer = setInterval(function () { goTo(index + 1); }, DEFAULT_INTERVAL);

    root.setAttribute('data-carousel-lite', 'inited');
  }

  function run() {
    ROOT_SELECTORS.forEach(function (sel) {
      qsa(document, sel).forEach(function (el) {
        initCarousel(el);
      });
    });
  }

  function runWhenReady() {
    run();
    window.setTimeout(run, 100);
    window.setTimeout(run, 500);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', runWhenReady);
  } else {
    runWhenReady();
  }
  window.addEventListener('load', run);
})();
