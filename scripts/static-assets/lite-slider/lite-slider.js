/**
 * Lite slider — vanilla JS, no jQuery. Touch swipe, dots, RTL.
 * Use only on /apk/ page. IIFE, no globals.
 */
(function () {
  'use strict';

  function findSliders() {
    return document.querySelectorAll('[data-lite-slider]');
  }

  function initSlider(root) {
    var viewport = root.querySelector('.lite-slider__viewport');
    var track = root.querySelector('.lite-slider__track');
    var slides = root.querySelectorAll('.lite-slider__slide');
    var dotsContainer = root.querySelector('.lite-slider__dots');
    if (!viewport || !track || slides.length === 0) return;

    var count = slides.length;
    var index = 0;
    var rtl = document.documentElement.getAttribute('dir') === 'rtl';
    var touchStartX = 0;
    var touchEndX = 0;

    function getOffset() {
      var slideWidth = viewport.offsetWidth;
      return rtl ? index * slideWidth : -index * slideWidth;
    }

    function goTo(i) {
      index = Math.max(0, Math.min(i, count - 1));
      track.style.transform = 'translateX(' + getOffset() + 'px)';
      dotsContainer.querySelectorAll('.lite-slider__dot').forEach(function (dot, d) {
        dot.setAttribute('aria-current', d === index ? 'true' : 'false');
      });
    }

    function buildDots() {
      dotsContainer.setAttribute('role', 'tablist');
      dotsContainer.setAttribute('aria-label', 'Скриншоты приложения');
      for (var i = 0; i < count; i++) {
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'lite-slider__dot';
        btn.setAttribute('aria-label', 'Слайд ' + (i + 1));
        btn.setAttribute('aria-current', i === 0 ? 'true' : 'false');
        btn.addEventListener('click', function (j) {
          return function () { goTo(j); };
        }(i));
        dotsContainer.appendChild(btn);
      }
    }

    function onTouchStart(e) {
      touchStartX = e.changedTouches ? e.changedTouches[0].screenX : e.screenX;
    }
    function onTouchEnd(e) {
      touchEndX = e.changedTouches ? e.changedTouches[0].screenX : e.screenX;
      var diff = touchStartX - touchEndX;
      var threshold = 50;
      if (Math.abs(diff) < threshold) return;
      if (rtl) {
        if (diff > 0) goTo(index + 1); else goTo(index - 1);
      } else {
        if (diff > 0) goTo(index + 1); else goTo(index - 1);
      }
    }

    buildDots();
    track.style.transform = 'translateX(' + getOffset() + 'px)';
    viewport.addEventListener('touchstart', onTouchStart, { passive: true });
    viewport.addEventListener('touchend', onTouchEnd, { passive: true });
  }

  function run() {
    findSliders().forEach(initSlider);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', run);
  } else {
    run();
  }
})();
