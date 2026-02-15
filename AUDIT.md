# Audit — WordPress → Static Site (888starz-africa)

## 1.1 Структура проекта

| Элемент | Путь | Примечание |
|---------|------|------------|
| WordPress core | `1/` | wp-config.php, wp-includes, wp-admin |
| wp-config.php | `1/wp-config.php` | DB: wp_gugum, prefix: TVXFZYUMh_ |
| Тема | `1/wp-content/themes/zento` | Zento (blog/magazine style) |
| Плагины | `1/wp-content/plugins/` | ACF, Polylang, zento-functions, All in One SEO, Header/Footer Code Manager, Classic Editor, MonsterInsights, Broken Link Checker (AIOSEO), и др. |
| Uploads | `1/wp-content/uploads/` | Медиа (из дампа/файлов) |
| База данных | `wp_gugum.sql` | Дамп MariaDB, префикс TVXFZYUMh_ |

## Типы страниц и контент

- **Типы записей**: page, post, wp_navigation, nav_menu_item, revision, attachment; возможны custom post types от плагинов.
- **Мультиязычность**: Polylang (default_lang: ar), домены/языки в options.
- **Главная**: show_on_front = page (в wp_options).
- **Системные страницы**: 9 policy/legal страниц (about, contacts, terms, responsible, privacy-policy, self-exclusion, dispute-resolution, fairness-rng-testing-methods, accounts-withdrawals-and-bonuses) — все найдены в БД, контент заполнен (см. SYSTEM_PAGES_AUDIT.md).

## Плагины, критичные для фронта

- **ACF** — кастомные поля (например в футере: footer_column_1_seo, footer_column_2_seo, visibility_baner_main).
- **Polylang** — переключение языка, меню по языкам.
- **Zento-functions** — тема-зависимая логика, shortcodes.
- **All in One SEO** — meta, sitemap (при экспорте можно зафиксировать meta в HTML).
- Остальные (кеш, аналитика, Classic Editor) для статики не обязательны.

## Билдеры

- Кастомные шаблоны в теме (page.php, partials/footer.php и т.д.). ACF используется для контента и опций. Elementor/WPBakery в списке плагинов не обнаружены.

## Кеш / SEO / Sitemap

- AIOSEO — sitemap, отчёты (ActionScheduler в БД). При статике: сгенерировать свой sitemap.xml по списку URL.
- Кеш-плагины в дампе не выделены; для статики не требуются.

## Динамические функции (что заменить в статике)

| Функция | Сейчас | Рекомендация для статики |
|---------|--------|---------------------------|
| Формы | Не выявлены явно в футере/контенте | При появлении: Formspree/Netlify Forms или mailto |
| Поиск | WP search | Статический индекс + lunr.js/fuse.js или отключить |
| Комментарии | Возможно отключены | Не включать или внешний виджет (с предупреждением о весе) |
| Личный кабинет / логин | Нет в статике | Не входит в статику; зафиксировать в документации |
| Корзина / оплата | Нет WooCommerce в аудите | Не применимо |
| admin-ajax.php | Может использоваться плагинами/темой | При экспорте crawler'ом все ответы уже в HTML; убрать вызовы или заменить заглушками |

## Приблизительное число URL для выгрузки

- Главная: 1  
- Системные страницы: 9  
- Остальные страницы (pages) и посты (posts) — из БД (TVXFZYUMh_posts, post_type in ('page','post'), post_status='publish').  
- Категории/теги/архивы — по наличию в меню и sitemap.  
Оценка: от ~20 до сотен URL в зависимости от количества постов и архивов. Точный список — после генерации URL из БД или обхода по sitemap/crawler.

## Итог

- Типы страниц: главная, страницы (в т.ч. 9 системных), посты, архивы (если используются).
- Критичные для фронта: тема Zento, ACF, Polylang, zento-functions.
- Динамика: формы/поиск/комментарии/кабинет — либо заменить, либо исключить (см. DYNAMIC_REPLACEMENTS.md после шага 4).
- 100% идентичность без сервера недостижима для логина/кабинета/оплаты; статическая копия — информационный сайт с корректными ссылками и контентом.
