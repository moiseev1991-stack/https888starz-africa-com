# Build and Deploy — статический сайт из WordPress

## Сборка в public/ (полный пайплайн по ТЗ)

1. **Поднять WP:** `docker compose up -d`, импортировать БД, открыть http://localhost:8080
2. **Снять слепок и сохранить HTML:**  
   `node scripts/capture-pages-playwright.js http://localhost:8080`  
   Пишет в `public/<path>/index.html` и `docs/inventory.md`.
3. **Скопировать ассеты:**  
   `node scripts/copy-wp-assets.js`  
   Копирует `wp-content/uploads`, темы, плагины, `wp-includes/js` в `public/`.
4. **Подключить app.js:**  
   `node scripts/inject-static-pages.js`  
   Добавляет `<script src="/assets/js/app.js" defer>` и убирает Yandex/canonical.
5. **Проверка путей:**  
   `node scripts/fix-html-paths.js`  
   Список локальных путей, которых нет в `public/`.

**Локальный просмотр:** из корня репозитория `npx serve public`, затем http://localhost:3000

## Сборка статики (альтернатива: только dist)

1. **Поднять локальный WP**  
   См. [RUN_LOCAL.md](RUN_LOCAL.md). Убедиться, что siteurl/home указывают на URL, с которого будет идти обход (например `http://localhost:8080`).

2. **Экспорт в /dist**  
   Один из вариантов:
   - **Скрипт (Playwright):**  
     `npm install && npx playwright install chromium`  
     затем `npm run export:local` (читает URLS.txt, сохраняет HTML в `dist/<path>/index.html`). Ассеты не скачиваются — для полной копии с медиа используйте wget.
   - **Crawler (wget):**  
     `wget --mirror --convert-links --adjust-extension --page-requisites --no-parent -P dist http://localhost:8080/`
   - **Плагин** (Simply Static / WP2Static): настроить экспорт в папку `dist` в корне проекта.

3. **Структура URL**  
   Сохранять trailing slash: для каждой страницы `/about/` → `dist/about/index.html`. Внутренние ссылки — относительные или на финальный домен (без localhost).

4. **Ассеты**  
   CSS/JS/шрифты/картинки должны лежать внутри проекта (например `dist/wp-content/...` или скопированы в `dist/assets`) и подключаться относительными путями. Проверить отсутствие 404.

5. **Проверка системных страниц**  
   Запустить:  
   `node scripts/verify_system_pages.js`  
   (ожидает папку `dist` в корне; при ином пути: `node scripts/verify_system_pages.js path/to/dist`).

## Деплой

- **Статический хостинг** (Netlify, Vercel, GitHub Pages, S3+CloudFront и т.п.):
  - Корень сайта — каталог `dist` (или папка со статикой).
  - При использовании подкаталога (например `https://site.com/africa/`) задать base path в скрипте экспорта и в конфиге хостинга.
- **Nginx/Apache:**  
  Указать document root на папку `dist`; при необходимости добавить правила для SPA/чистых URL (try_files на index.html для каталогов). Примеры заголовков кеша — в OPTIMIZATION_REPORT.md / nginx.conf.snippet.

## Чек-лист перед деплоем

- [ ] Все 9 системных страниц есть в dist и проходят verify_system_pages.js.
- [ ] В HTML нет ссылок на localhost.
- [ ] Нет битых внутренних ссылок и 404 на ассеты (css/js/img).
- [ ] sitemap.xml и robots.txt при необходимости сгенерированы и лежат в dist.
