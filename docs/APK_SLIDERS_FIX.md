# Fix: слайдеры на /apk/ (Egypt)

## Проблема
На https://888starzeg-egypt.com/apk/ слайдеры (Owl Carousel, Fancybox) не работали, хотя на Africa работают. Типичные ошибки в консоли: `$ is not a function`, `owlCarousel is not a function`.

## Причина
Скрипт `optimize-html-performance.js` переносил jQuery и `scripts.min.js` в конец `<body>` и выносил инлайн-скрипты с `$()`/`jQuery()` туда же для ускорения LCP. На странице /apk/ инлайн-скрипт инициализации слайдеров (`$(document).ready(function(){ ... .owlCarousel(...) ... })`) выполнялся до загрузки jQuery (или порядок Owl → init нарушался), из‑за чего возникали ошибки.

## Решение
Для **только** страницы `/apk/` отключены шаги оптимизации, ломающие порядок скриптов:
- **Не** переносить jQuery и `scripts.min.js` в конец body.
- **Не** выносить инлайн-скрипты с `$`/jQuery в конец.
- **Не** оборачивать `jQuery(document).ready` в `DOMContentLoaded`.

В результате на /apk/ сохраняется порядок: **jQuery → Owl Carousel (defer) → Fancybox (defer) → инлайн init**. Остальные страницы по-прежнему оптимизируются (jQuery в конец, PageSpeed не ухудшается).

## Проверка
- Открыть https://888starzeg-egypt.com/apk/ → слайдеры листаются, точки/стрелки работают.
- Консоль без ошибок `$ is not a function` / `owlCarousel is not a function`.
- PageSpeed: оптимизация отключена только для одной страницы (apk), остальные без изменений.
