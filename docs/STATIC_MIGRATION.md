# Статическая миграция — выполненный объём

## Цель (из ТЗ)

Перенос сайта с localhost:8080 в статический HTML без потери визуала и функционала; высокий PageSpeed (особенно мобайл).

## Что сделано

### Этап 1. Инвентаризация

- Пройдены все URL из списка (главная + 13 страниц) на http://localhost:8080.
- Сохранён финальный HTML в `public/<path>/index.html`.
- Список ресурсов (CSS/JS/изображения) зафиксирован в `docs/inventory.md`.

### Этап 2–3. Структура public/

- **Корень:** `public/index.html` (главная).
- **Страницы:** `public/contacts/index.html`, `public/about/index.html`, … (все 13 URL).
- **Ассеты:** пути как на эталоне:
  - `public/wp-content/uploads/`
  - `public/wp-content/themes/zento/`
  - `public/wp-content/plugins/flexy-breadcrumb/`
  - `public/wp-includes/js/`
- **Единый скрипт инициализации:** `public/assets/js/app.js` (слайдеры Owl Carousel, Fancybox, slider-888).

### Этап 4. Перенос и пути

- HTML взят с эталона (Playwright, `networkidle`).
- Внутренние ссылки — без localhost; пути к ассетам вида `/wp-content/...` сохранены.
- Выполнен `scripts/copy-wp-assets.js` — копирование uploads, темы, плагинов, jquery в `public/`.
- Проверка путей: `scripts/fix-html-paths.js`. Ожидаемые 404 (некритичные): `/favicon-96x96.png`, `/apple-touch-icon.png`, `/game/` (внешняя ссылка).

### Этап 5. JS и слайдеры

- В `public/assets/js/app.js`: инициализация `.owl-carousel`, `.owl-mobile`, `.slider-888-slider`, Fancybox для `[data-fancybox="gallery"]` и `gallerymob`.
- Во все `index.html` добавлен `<script src="/assets/js/app.js" defer></script>` (скрипт `inject-static-pages.js`).
- В сохранённом HTML разметка уже после работы Owl (owl-stage, owl-item); при загрузке статики подключаются jQuery и Owl с CDN, затем app.js повторно инициализирует слайдеры.

### Этап 6. Оптимизация (частично)

- Подключение app.js с `defer`.
- Удалены скрипты Яндекс.Метрики из сохранённого HTML; canonical на прод убран.
- Полная оптимизация под PageSpeed (критический CSS, минификация, webp, font-display) — в следующих итерациях (см. OPTIMIZATION_REPORT.md).

### Этап 7. Проверки

- Все 14 URL отдают 200 при раздаче `public/` через `npx serve public`.
- Проверка путей: `node scripts/fix-html-paths.js` — список возможных 404 (см. выше).

### Этап 8. Деплой и GitHub

- Инструкция по запуску: `public/README.md` и раздел в `BUILD_AND_DEPLOY.md`.
- Локальный запуск: из корня репозитория `npm run serve:static` или `npx serve public`, открыть http://localhost:3000.
- Репозиторий и PR: создать репозиторий на GitHub, ветку `feature/static-migration`, коммиты по этапам, затем merge в `main` и инструкция в README — выполняется вручную (см. ниже).

## Скрипты (корень репозитория)

| Команда | Назначение |
|--------|------------|
| `npm run capture` | Снять HTML со всех URL (нужен запущенный localhost:8080) |
| `npm run copy-assets` | Копировать wp-content и wp-includes в public/ |
| `npm run inject-app` | Добавить app.js и почистить HTML |
| `npm run check-paths` | Проверить наличие файлов по путям из HTML |
| `npm run serve:static` | Запустить раздачу public/ (npx serve) |

## Обязательные страницы (проверены)

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

## Что сделать вручную после выполнения работ

1. Загрузить проект на GitHub (создать репозиторий, при необходимости — `.gitignore` для `node_modules/`, `public/` коммитить).
2. Создать ветку `feature/static-migration` и оформить коммиты, например:
   - `chore: add static structure (public/, scripts)`
   - `feat: migrate pages (capture + copy assets)`
   - `fix: sliders init (app.js, inject-app)`
   - `docs: BUILD_AND_DEPLOY, STATIC_MIGRATION, public/README`
3. Открыть PR в `main`.
4. После мержа: в README.md в корне указать, как запустить статику локально (`npx serve public`) и при необходимости приложить скриншоты PageSpeed (главная, /apk/, /registration/, /promo-code/).

---

**Просьба, которую нужно передать исполнителю после выполнения работ:**

«Загрузи проект на GitHub, открой PR и пришли ссылку на репозиторий + список страниц, которые проверил + скриншоты PageSpeed».
