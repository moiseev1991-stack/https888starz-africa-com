# Static site — 888starz-africa

Собранная статическая версия сайта (HTML/CSS/JS). Все страницы в виде `index.html` в папках.

## Запуск локально

Из корня репозитория:

```bash
npx serve public
```

Или из этой папки:

```bash
npx serve .
```

Откройте в браузере: **http://localhost:3000**

## Обязательные страницы (должны отдавать 200)

- /
- /contacts/
- /about/
- /terms/
- /responsible/
- /privacy-policy/
- /self-exclusion/
- /dispute-resolution/
- /fairness-rng-testing-methods/
- /accounts-withdrawals-and-bonuses/
- /cookies/
- /registration/
- /apk/
- /promo-code/

## Структура

- `index.html` — главная
- `<slug>/index.html` — страницы
- `wp-content/` — темы, плагины, uploads (пути как на эталоне)
- `wp-includes/js/` — jQuery, emoji
- `assets/js/app.js` — инициализация слайдеров и Fancybox

Слайдеры (Owl Carousel) и галереи (Fancybox) подключаются с CDN; инициализация — в `assets/js/app.js`.
