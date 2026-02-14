# Верификация слайдеров и облака тегов (fix/sliders)

## Что сделано

1. **Исходный HTML без owl-item/owl-stage/cloned**  
   В `scripts/optimize-html-performance.js` добавлен шаг 5a2: при наличии в HTML разметки Owl (owl-stage-outer, owl-stage, owl-item) она заменяется на чистую разметку — только контейнер `.owl-carousel`/`.owl-mobile` и дочерние `.slide`/`.mobile-slide`. Удаляются inline `transform: translate3d` на owl-stage.

2. **Один слайдер, без Owl**  
   - В теме: Carousel Lite (vanilla JS), подключается в `enqueue-scripts.php`, инициализация по `DOMContentLoaded` и `load`, защита от повторного init по `data-carousel-lite`.  
   - На статике: Embla, инжектируется в `inject-lite-slider-apk.js`, один init по `DOMContentLoaded` и `load`.  
   - Скрипт Owl удалён в optimize (шаг 5a).

3. **Лёгкий слайдер (dots + autoplay + swipe)**  
   Carousel Lite и Embla: точки генерируются JS, autoplay 4 с / 3.5 с, touch swipe, RTL из разметки.

4. **Чёрное облако тегов**  
   Toggle по клику: в теме — инлайн-скрипт в `footer.php`; на статике — инжект в `optimize-html-performance.js` (шаг 14) с маркером `seo-module-toggle-inited`, чтобы не вставлять скрипт дважды.

## Обязательная проверка (доказательства)

Выполнить на **главной странице** после деплоя или локально (`npm run build:live` затем `npm run serve` → открыть http://localhost:3080).

### 1. Network (картинки .webp)

- Открыть DevTools → вкладка **Network**.
- Включить фильтр **Img** (или оставить All).
- Обновить страницу (Ctrl+F5).
- Найти запросы к `/wp-content/uploads/2025/03/*.webp` (в т.ч. URL с `%D8%A7%D9%84%D8%A5%D8%AD%D8%B5%D8%A7%D8%A1%D8%A7%D8%AA` для الإحصاءات.webp).
- **Зафиксировать:** все такие запросы должны быть со статусом **200** и при необходимости `Content-Type: image/webp`. Если есть 404/403 — проверить наличие файлов в `dist/wp-content/uploads/2025/03/` и шаг `download-slider-assets.js` в сборке.

### 2. Console

- DevTools → вкладка **Console**.
- Обновить страницу.
- **Зафиксировать:** **0 ошибок** (красные сообщения). Предупреждения (жёлтые) допускаются.

### 3. Исходный HTML

- DevTools → вкладка **Elements** (или View Page Source).
- Найти блок слайдера (по классу `owl-carousel` или `owl-mobile`).
- **Зафиксировать:** внутри контейнера только элементы с классом `.slide` или `.mobile-slide`. Не должно быть узлов с классами `owl-item`, `owl-stage`, `owl-stage-outer`, `cloned` и inline-стилей `transform: translate3d` в разметке слайдера. (В CSS может остаться селектор `.owl-stage` — это допустимо.)

### 4. Слайдер

- Автопрокрутка каждые 3–5 с.
- Клик по точкам (dots) переключает слайды.
- На мобильном (или эмуляция) свайп переключает слайды.
- **Скрин:** сделать скриншот главной с видимым слайдером и точками.

### 5. Облако тегов

- Прокрутить до футера, найти чёрный блок с заголовком (например, «SEO» / облако тегов).
- Клик по заголовку: блок должен раскрываться/сворачиваться, иконка-стрелка поворачивается.
- **Зафиксировать:** toggle срабатывает без ошибок в Console.

## Итог

- Network: все .webp по `/wp-content/uploads/...` — **200**.  
- Console: **0 ошибок**.  
- Исходный HTML слайдера: **без owl-item/owl-stage/cloned/transform**.  
- Слайдер: автопрокрутка, dots, swipe работают; есть **скрин работающего слайдера**.  
- Облако тегов: **toggle по клику работает**.

После проверки зафиксировать результат (скрин + кратко статусы Network/Console) и при необходимости приложить к PR или задаче.
