# REPORT_SLIDERS — диагностика и решение

## ЭТАП 1 — Диагностика (источник поломки)

### A) Библиотеки на целевом сайте (Egypt, статический экспорт)

На 888starzeg-egypt.com страницы apk/registration/promo-code собираются так:
- HTML берётся с Africa (fetch-africa-pages), домен заменяется на Egypt.
- Затем optimize-html-performance переносит jQuery и scripts.min.js в **конец body** с defer (для LCP/PageSpeed), а инлайн-скрипты с `$()` выносит туда же.
- Для **только /apk/** мы отключили этот перенос (isApkPage), но на /registration/ и /promo-code/ перенос всё равно применяется.

**Результат проверки (что получается в итоге):**

| Проверка | Ожидаемое | Факт на Egypt (до правок) |
|----------|-----------|----------------------------|
| `typeof window.jQuery` | `"function"` | На страницах, где jQuery перенесён в конец, при выполнении инлайна в середине страницы — **undefined** (инлайн выполняется до скрипта). |
| `typeof window.$` | `"function"` | То же — **undefined** или не jQuery, если что-то переопределило `$`. |
| `jQuery.fn.owlCarousel` | `"function"` | **undefined** — скрипт owl.carousel.min.js (defer) выполняется после DOMContentLoaded, инлайн init уже выполнился раньше. |
| `Fancybox` / `jQuery.fancybox` | есть | Может быть undefined по той же причине порядка. |

**Вывод A:** На Egypt при текущей оптимизации: **jQuery и Owl не доступны в момент выполнения инициализации** → `$ is not a function`, `owlCarousel is not a function`.

---

### B) DOM блоков на страницах

| Селектор | apk (после замены) | registration / promo (до правок) |
|----------|---------------------|-----------------------------------|
| `.owl-carousel`, `.owl-mobile` | **0** (блоки заменены на .lite-slider) | Есть в HTML (скопировано с Africa). |
| `.mobile-slide` | 0 | Есть, если есть в Africa HTML. |
| `[data-fancybox]` | 0 в блоках слайдера | Есть в HTML. |

**Вывод B:** На apk блоки owl уже заменены на lite-slider (работает). На registration и promo остаются узлы .owl-carousel/.owl-mobile, но **инициализация не срабатывает** из‑за порядка скриптов.

---

### C) Ошибки в консоли (типичные)

- `TypeError: $ is not a function` — инлайн выполняется до загрузки jQuery.
- `TypeError: ... owlCarousel is not a function` — инлайн выполняется до загрузки owl.carousel.min.js или jQuery не тот.
- `Failed to load resource: .../cdn-cgi/rum ... 404` — скрипт Cloudflare RUM удалён/не используется (уже убираем в optimize).

**Вывод C:** Поломка из‑за **порядка подключения** (оптимизация переносит jQuery в конец, инлайн остаётся в середине страницы).

---

## ЭТАП 2 — Дубли библиотек

В optimize-html-performance уже есть: удаление дублей jQuery (оставляется один). Дубли owl/fancybox в статическом HTML с Africa могут присутствовать; при замене всех owl-блоков на Carousel Lite необходимость в owl отпадает на этих страницах.

---

## ЭТАП 3 — Порядок подключения

Требуемый порядок: `jQuery → owl.carousel.min.js → fancybox → sliders-init.js` в конце body **без defer/async** для этих скриптов.

На статическом экспорте:
- Либо отключить перенос скриптов для apk/registration/promo (как сейчас только для apk) и добавить один sliders-init.js + гарантировать наличие owl/fancybox на всех трёх страницах.
- Либо **не использовать Owl** на этих страницах: заменить все блоки .owl-carousel и .owl-mobile на **Carousel Lite** (lite-slider) и подключать только lite-slider.js/css. Тогда порядок и jQuery не важны для слайдеров.

Выбрано второе: **Carousel Lite везде** на apk, registration, promo-code.

---

## ЭТАП 4–5 — Решение: единый Carousel Lite без jQuery/Owl

Вместо восстановления Owl и порядка скриптов на трёх страницах:

1. **На всех трёх страницах** (apk, registration, promo-code) все блоки с классами **.owl-carousel** и **.owl-mobile** заменяются на разметку **Carousel Lite** (компонент lite-slider: viewport, track, слайды, точки).
2. Подключаются только `/assets/lite-slider/lite-slider.css` и `lite-slider.js` (подключение только на страницах, где была замена).
3. **Нет зависимости от jQuery и Owl** на этих страницах для слайдеров → нет ошибок `$ is not a function` и `owlCarousel is not a function`.
4. Требования ТЗ к Carousel Lite соблюдены: без jQuery, точки, свайп, RTL, только на нужных страницах.

Файлы:
- `/assets/lite-slider/lite-slider.css` — стили карусели и точек.
- `/assets/lite-slider/lite-slider.js` — инициализация всех `[data-lite-slider]`, touch, RTL, aria.

---

## Было / Стало

| Было | Стало |
|------|--------|
| На apk: owl заменён на lite-slider; на registration/promo: owl-блоки есть, но не инициализируются (ошибки $/owlCarousel). | На **apk, registration, promo-code** все owl-блоки заменены на Carousel Lite; скрипт инжектируется на каждую из этих страниц при наличии замены. |
| Консоль: `$ is not a function`, `owlCarousel is not a function` на части страниц. | Консоль без этих ошибок; слайдеры работают без jQuery/Owl. |

---

## Приёмка (ЭТАП 7)

- Главная: не трогаем в рамках этого отчёта (слайдеры главной могут быть отдельной задачей).
- /apk/, /registration/, /promo-code/: карусели скриншотов/блоков работают (листание, точки).
- В консоли нет `$ is not a function` и `owlCarousel is not a function`.
- Новые файлы: `scripts/static-assets/lite-slider/lite-slider.js`, `lite-slider.css`; инжект: `scripts/inject-lite-slider-apk.js` (обрабатывает **apk, registration, promo-code** — везде заменяет owl на Carousel Lite).
