# Диагностика слайдера на /apk/ (Egypt)

## Этап 1. Проблема
На https://888starzeg-egypt.com/apk/ блок с фото (карусель с точками) не работал. Типичные причины на статическом экспорте:
- **$ is not a function** — jQuery перенесён в конец body, инлайн init выполняется до загрузки jQuery.
- **owlCarousel is not a function** — Owl Carousel (cdnjs) не успел загрузиться до init или порядок скриптов нарушен.
- 404 на owl.carousel.min.js / owl.carousel.min.css — ассеты не попали в dist или путь неверный.

## Решение: замена на lite-slider (vanilla)
По ТЗ: если починить существующий слайдер не удаётся или библиотека тяжёлая — заменить на лёгкий собственный слайдер без jQuery/плагинов.

Реализовано:
1. **lite-slider.js** — vanilla JS: точки, translateX, touch-swipe, RTL, без глобальных переменных (IIFE).
2. **lite-slider.css** — минимальные стили, will-change: transform, без forced reflow.
3. **inject-lite-slider-apk.js** — на этапе сборки для `dist/apk/index.html`:
   - находит блок с классом `owl-carousel`;
   - извлекает из него все `<img src alt>`;
   - заменяет блок на разметку `.lite-slider` с теми же изображениями (первое — `fetchpriority="high"`, `loading="eager"`, остальные — `loading="lazy"`, `width`/`height` для CLS);
   - подключает `/assets/lite-slider/lite-slider.css` и `lite-slider.js` только на этой странице.
4. Ассеты копируются в `dist/assets/lite-slider/`, в деплой попадают через `dist/`.

## Проверки после деплоя
- Слайдер листается (точки, свайп на мобилке).
- RTL: при `html[dir="rtl"]` направление корректное.
- Console без ошибок, Network без 404 на lite-slider.* и изображениях.
- PageSpeed: без тяжёлых библиотек, CLS не ухудшен (width/height у img).
