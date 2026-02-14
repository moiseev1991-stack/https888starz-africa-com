# PR: feature/pages-clone-and-pagespeed → main

## Описание
Полный перенос страниц `/apk/`, `/registration/`, `/promo-code/` с 888starz-africa.com на 888starzeg-egypt.com (1:1) и оптимизация PageSpeed/Lighthouse.

## Что сделано

### P0 Footer
- Страницы футера создаются из Africa: `contacts`, `dispute-resolution`, `responsible`, `terms`, `about`, `privacy-policy`, `self-exclusion`, `accounts-withdrawals-and-bonuses`, `fairness-rng-testing-methods`.
- В футер добавлены ссылки на эти страницы; для навигации задан `aria-label="روابط الموقع"`.

### Клонирование страниц (Africa → Egypt)
- `scripts/fetch-africa-pages.js`: загрузка полного HTML для apk, registration, promo-code и всех страниц футера с Africa, замена домена на Egypt, сохранение в `dist/`.
- Сборка: `inject-static-pages` больше не генерирует apk/registration/promo-code из шаблона — их заполняет fetch.

### Этап 1 — Захват
- `scripts/capture-pages-playwright.js`: снятие HTML, списка ресурсов и fullpage screenshot для Africa и Egypt (`npm run capture:pages`). Результат в `scripts/capture-output/` (в .gitignore).

### Этап 3 — Ассеты
- `scripts/download-africa-page-assets.js`: после fetch скачиваются недостающие ассеты (img/css/js/font) со страниц apk/registration/promo-code с Africa в `dist/`, чтобы не было внешних хостингов (ibb.co и т.п.) на финале.

### PageSpeed (Stage 4)
- Preconnect: не более 4, удаление пустого `href`.
- Удаление cdn-cgi/rum, font-display: swap, отложенная загрузка некритичного CSS (font-awesome, flexy-breadcrumb и др.).
- LCP: первое изображение с `fetchpriority="high"`, без lazy.
- CLS: width/height из имени файла где возможно.
- A11y: `aria-label` для кнопок карусели (owl-dot).
- Кеш: `config/.htaccess` — 1 год для статики (уже в проекте).

## Проверенные страницы
- [ ] Главная /
- [ ] /apk/
- [ ] /registration/
- [ ] /promo-code/
- [ ] Страницы футера: /about/, /contacts/, /terms/, /responsible/, /privacy-policy/, /self-exclusion/, /dispute-resolution/, /accounts-withdrawals-and-bonuses/, /fairness-rng-testing-methods/

## Метрики Lighthouse (заполнить до/после)

| Страница     | До (Perf / A11y / SEO) | После (Perf / A11y / SEO) |
|-------------|------------------------|----------------------------|
| Главная     |                        |                            |
| /apk/       |                        |                            |
| /registration/ |                     |                            |
| /promo-code/   |                     |                            |

Цель: Performance ≥90 (лучше 95+), A11y ≥90, SEO 95–100.

## Визуальный diff
После деплоя сравнить скриншоты Africa и Egypt для /apk/, /registration/, /promo-code/ (например из `npm run capture:pages`).

## Консоль
Убедиться, что в консоли нет критических ошибок (404 /cdn-cgi/rum, TypeError: $ is not a function — по возможности устранены в optimize-html-performance).
