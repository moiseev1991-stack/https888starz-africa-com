# Fix: слайдеры на Egypt (в т.ч. /apk/, главная)

## Проблема
На https://888starzeg-egypt.com слайдеры не работали. Ошибки в консоли: `$ is not a function`, `owlCarousel is not a function`, `Unexpected token '<'`, 500 на `site.webmanifest`.

## Причина
- На статике скрипт Owl Carousel удалён (используется Embla), но инлайн-код из темы по-прежнему вызывает `$(...).owlCarousel(...)` → ошибка.
- Скрипты с путями `wp-includes/...` или отсутствующий `site.webmanifest` дают 404/500 (HTML вместо JS) → `Unexpected token '<'`.

## Решение (optimize-html-performance.js + copy-wp-assets.js)
1. **Полифилл owlCarousel:** сразу после jQuery в `<head>` вставляется скрипт: если `$.fn.owlCarousel` нет, задаётся no-op `function(){ return this; }`. Инлайн-инициализация больше не падает; слайдер подхватывает **Embla** (carousel-embla.js).
2. **owl-dots-click-fix** не инжектируется, если Owl удалён и на странице есть Embla (точки вешает carousel-embla.js).
3. Удаляются скрипты с `src` на `wp-includes/...` (на статике отдают HTML → SyntaxError).
4. **site.webmanifest** копируется в `dist/` при сборке (copy-wp-assets.js), чтобы GET /site.webmanifest не отдавал 500.

## Проверка
- Открыть https://888starzeg-egypt.com и https://888starzeg-egypt.com/apk/ → слайдеры листаются, точки работают.
- Консоль без ошибок `owlCarousel is not a function` и без `Unexpected token '<'`.
- GET https://888starzeg-egypt.com/site.webmanifest → 200, JSON.
