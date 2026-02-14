# Критерии приёмки: Egypt 1:1 с Africa

## Цель
Страницы https://888starzeg-egypt.com/apk/, /registration/, /promo-code/ не должны отличаться визуально и структурно от https://888starz-africa.com (те же блоки, тексты, кнопки, галереи, слайдеры, отступы, порядок секций).

## Реализовано в сборке

### 1. Восстановление страниц и футер
- **fetch-africa-pages.js** загружает полный HTML с Africa для: apk, registration, promo-code, contacts, dispute-resolution, responsible, terms, about, privacy-policy, self-exclusion, accounts-withdrawals-and-bonuses, fairness-rng-testing-methods. Домен заменяется на Egypt.
- **optimize-html-performance.js** добавляет в футер ссылки на все перечисленные страницы (если отсутствуют), задаёт `aria-label="روابط الموقع"` для навигации.

### 2. Стратегия 1:1
- Контент страниц apk/registration/promo-code — полная копия HTML с Africa (без ручного копирования блоков).
- **download-africa-page-assets.js** подтягивает недостающие ассеты (CSS/JS/img) с Africa в dist.
- Слайдер на /apk/: блоки owl-carousel и owl-mobile заменяются на **lite-slider** (vanilla JS), чтобы не зависеть от jQuery/Owl и избежать ошибок в консоли.

### 3. Слайдер на /apk/
- **inject-lite-slider-apk.js** заменяет все блоки с классами owl-carousel и owl-mobile на разметку lite-slider (точки, touch-swipe, RTL).
- Подключаются только на /apk/: `/assets/lite-slider/lite-slider.css`, `lite-slider.js`.

### 4. PageSpeed
- **Кеш:** config/.htaccess — Cache-Control: public, max-age=31536000, immutable для .css, .js, .woff2, .svg, .webp, .png, .jpg и т.д.
- **Preconnect:** не более 3, пустой href удаляется.
- **Render-blocking CSS:** font-awesome, flexy-breadcrumb, fancybox — preload + onload (неблокирующая загрузка).
- **font-display: swap** для Font Awesome и инлайн @font-face.
- **LCP:** первое изображение с fetchpriority="high", без lazy; у img при необходимости проставляются width/height (CLS).
- **cdn-cgi/rum:** скрипты с таким путём удаляются (404).

### 5. Консоль
- Удаление скриптов cdn-cgi/rum.
- На /apk/ слайдер не использует jQuery — нет ошибки "$ is not a function" от Owl.

### 6. Доступность
- Точки слайдера: aria-label="Slide N", aria-current="true" у активной.
- Футер: nav с aria-label.

## Чек-лист перед релизом

- [ ] **Визуальная сверка:** mobile 390×844, desktop 1440×900 — скриншоты Africa vs Egypt для /apk/, /registration/, /promo-code/.
- [ ] **Интерактивность:** свайп слайдера, клик по точкам, RTL (dir="rtl") на арабских страницах.
- [ ] **Lighthouse:** запуск для /apk/, /registration/, /promo-code/ и главной; целевые показатели mobile 90+ где возможно.
- [ ] **Консоль:** 0 ошибок, 0 404 на нужных ассетах.

## Изменённые файлы (типичный набор)

- scripts/fetch-africa-pages.js
- scripts/inject-static-pages.js
- scripts/inject-lite-slider-apk.js
- scripts/download-africa-page-assets.js
- scripts/optimize-html-performance.js
- scripts/static-assets/lite-slider/*.js, *.css
- package.json (build:live)
- config/.htaccess
