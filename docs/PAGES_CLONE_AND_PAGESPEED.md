# Перенос страниц Africa → Egypt и PageSpeed

## Цель
Полный перенос 1:1 страниц `/apk/`, `/registration/`, `/promo-code/` с https://888starz-africa.com на https://888starzeg-egypt.com и оптимизация PageSpeed/Lighthouse.

## Этапы

### Этап 1 — Захват эталона (Playwright)
- **Скрипт:** `scripts/capture-pages-playwright.js`
- **Команда:** `npm run capture:pages`
- Снимает для Africa и Egypt: HTML (после рендера), список ресурсов (CSS/JS/img/font), fullpage screenshot.
- Результат: `scripts/capture-output/africa-{apk|registration|promo-code}.html`, `-resources.json`, `.png` и то же для `egypt-*`, плюс `summary.json` с diff по ресурсам.

### Этап 2 — Клонирование страниц 1:1
- **Скрипт:** `scripts/fetch-africa-pages.js` (в сборке после `inject-static-pages.js`)
- Загружает с Africa полный HTML для: `apk`, `registration`, `promo-code`, а также страницы футера: `contacts`, `dispute-resolution`, `responsible`, `terms`, `about`, `privacy-policy`, `self-exclusion`, `accounts-withdrawals-and-bonuses`, `fairness-rng-testing-methods`.
- Подмена домена: `888starz-africa.com` → `888starzeg-egypt.com`.
- Результат: `dist/<slug>/index.html` для каждого slug.

### P0 Footer
- Страницы футера создаются тем же `fetch-africa-pages.js` (загрузка с Africa).
- В `optimize-html-performance.js`: добавлены ссылки на системные страницы в блок «حول الموقع», `aria-label="روابط الموقع"` для навигации футера.

### Этап 3 — Ассеты (планируется)
- Скачать изображения/иконки со страниц Africa, сохранить локально в dist.
- Оптимизация: WebP/AVIF, srcset/sizes, для LCP — без lazy, `fetchpriority="high"`.

### Этап 4 — PageSpeed
- Уже в `optimize-html-performance.js`: preconnect (макс. 4, без пустого href), удаление cdn-cgi/rum, font-display: swap, отложенная загрузка некритичного CSS, LCP (первое изображение с fetchpriority="high"), width/height для CLS, canonical, футер/меню.
- Кеш: `config/.htaccess` — 1 год для статики (css/js/woff2/svg/webp/png).

### Этап 5 — QA
- Lighthouse Mobile/Desktop для главной и трёх страниц.
- Визуальный diff Africa vs Egypt (скриншоты из capture).
- Консоль без критических ошибок.

## Ветка и PR
- Ветка: `feature/pages-clone-and-pagespeed`
- Коммиты по этапам. PR в `main` с метриками Lighthouse до/после и списком проверенных страниц.
