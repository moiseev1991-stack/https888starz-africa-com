# Аудит проекта: 888starz-africa.com (WordPress → статический HTML)

**Дата:** 2026-02-11  
**Цель:** Подготовка к статическому экспорту с максимальной идентичностью и скоростью.

---

## 1.1 Структура проекта

| Компонент | Путь | Примечание |
|-----------|------|------------|
| Конфиг | `wp-config.php` | Есть |
| Ядро WP | `wp-admin/`, `wp-includes/` | Стандартное |
| Тема | `wp-content/themes/zento` | Zento 1.4.5 (EstudioPatagon), magazine/blog |
| Плагины | `wp-content/plugins/` | См. ниже |
| Загрузки | `wp-content/uploads/` | 2025/, slider/cache/, merlin-wp/ |
| База | `wp_gugum.sql` | Дамп phpMyAdmin, MariaDB 10.3 |

**Префикс таблиц БД:** `TVXFZYUMh_`  
**Текущий домен (из wp_options):** `https://888starz-africa.com`  
**siteurl / home:** оба `https://888starz-africa.com`

---

## 1.2 Тема и билдеры

- **Тема:** Zento (super lightweight blog, customizer, theme options).
- **Кастомные шаблоны:**  
  `page-templates/home.php`, `page-fullwidth.php`, `page-explore-tags.php`; есть AMP-версия (`amp/`).
- **Билдеры:** Elementor/WPBakery в проекте не обнаружены.
- **ACF:** плагин Advanced Custom Fields установлен — возможны кастомные поля в шаблонах.
- **В теме:** Zento использует свои опции (CSF), виджеты, шорткоды (zento-functions), masonry, slick, tocbot, magnific-popup, Prism.

---

## 1.3 Плагины (критичные для фронта и экспорта)

| Плагин | Назначение | Критичность для статики |
|--------|------------|--------------------------|
| **advanced-custom-fields** | Кастомные поля | Данные уже в HTML при рендере |
| **all-in-one-seo-pack** | SEO, sitemap, мета | Нужны: title/description/og в HTML, sitemap.xml в экспорт |
| **broken-link-checker-seo** | Проверка ссылок | Не нужен для статики |
| **classic-editor** | Редактор записей | Не влияет на фронт |
| **flexy-breadcrumb** | Хлебные крошки | Уже в HTML |
| **google-analytics-for-wordpress** | Аналитика | В статике — тот же код/тег в head |
| **header-footer-code-manager** | Код в head/footer | Нужно сохранить вывод в статике |
| **optinmonster** | Попапы/подписки | Можно заменить на статический скрипт или убрать |
| **polylang** | Мультиязычность | Критично: разные URL по языкам (например /en/, /fr/) |
| **wps-hide-login** | Скрытие wp-login | Не нужен для статики |

**Итог:** Для фронта важны вывод ACF, Polylang (языковые URL), AIOSEO (мета, sitemap), HFCM (код в head/footer). Остальное либо уже «запекается» в HTML, либо отключается.

---

## 1.4 База данных (краткий анализ)

- **Таблицы:** стандартные WP + `TVXFZYUMh_actionscheduler_*` (All in One SEO / задачи).
- **Типы контента:** из структуры `TVXFZYUMh_posts` используются стандартные `post`, `page`, а также `nav_menu_item`, `attachment`, `revision` и др.
- **Мультиязычность:** Polylang — в БД есть связь записей с языками (термины/мета); URL обычно с префиксом языка.
- **Число URL для выгрузки:** точный подсчёт — по списку из WP (см. шаг 3). Ориентир: все опубликованные страницы (pages), записи (posts), архивы категорий/тегов, главная, кастомные типы (если есть), языковые варианты Polylang, 404, sitemap.xml, robots.txt.

---

## 1.5 Динамические функции (что потребует замены/удаления)

| Функция | Где используется | Рекомендация для статики |
|---------|-------------------|---------------------------|
| **Формы** | Contact Form 7 / WPForms не найдены в списке плагинов | Если появятся — Formspree/Netlify Forms или mailto |
| **Поиск** | Тема (searchform, search-lightbox) | Статический индекс + Lunr.js / Fuse.js или отключить |
| **Комментарии** | Тема (comments.php, comment-reply) | Скрыть блок или заменить на внешний виджет (Disqus и т.п.) |
| **Счётчик просмотров** | `ajax_var.url` → admin-ajax.php (epcl_views_counter) | Убрать или заменить статическим числом/скрыть |
| **Логин/кабинет** | Нет WooCommerce в списке; wps-hide-login только админка | В статике не нужен |
| **Корзина/оплата** | Нет WooCommerce в плагинах | Не применимо |
| **AJAX-эндпоинты** | admin-ajax.php (views counter, возможно другие) | Удалить вызовы или заменить заглушками |

---

## 1.6 Ассеты (ориентир)

- **Тема:** `wp-content/themes/zento/assets/` — dist (style.min.css, scripts.min.js, plugins), js (tocbot, masonry, slick, magnific-popup, preload-css, functions.js, shortcodes.js).
- **Шрифты:** Google Fonts (Urbanist, DM Sans) — при оптимизации желательно self-host woff2.
- **Загрузки:** `wp-content/uploads/2025/`, `wp-content/uploads/slider/` — изображения (webp, jpg, png), кеш слайдера.
- **Favicon/manifest:** в корне и в header темы (favicon-96x96.png, favicon.svg, apple-touch-icon, site.webmanifest).

---

## 1.7 Ограничения и риски

1. **Polylang:** все языковые версии страниц должны попасть в экспорт (отдельные URL).
2. **Счётчик просмотров:** без бэкенда не работает — либо убрать, либо показывать статичное значение.
3. **Поиск:** без бэкенда — только клиентский поиск по предсобранному индексу.
4. **100% идентичность:** возможна по виду и структуре; интерактив (комменты, просмотры, формы) — через замены или отключение.

---

## 1.8 Результат шага 1

- **Типы страниц:** главная (вероятно page с шаблоном home), страницы (page), записи (post), архивы (category, tag), возможно кастомные типы; мультиязык (Polylang).
- **Критичные плагины для фронта:** ACF (данные в HTML), Polylang (URL), AIOSEO (мета, sitemap), HFCM (код).
- **Динамика для замены:** просмотры (ajax), поиск, комментарии (по желанию), формы (если появятся).
- **Приблизительное число URL:** уточняется при экспорте (скрипт/плагин/crawler); ориентир — десятки–сотни страниц с учётом языков.

Далее: **Шаг 2** — поднять WP локально и проверить отображение; **Шаг 3** — статическая выгрузка в `/dist`.
