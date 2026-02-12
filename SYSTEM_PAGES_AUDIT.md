# Аудит системных страниц (9 страниц)

## Назначение документа

Проверка наличия в локальной WordPress-копии страниц со slug:
`about`, `contacts`, `terms`, `responsible`, `privacy-policy`, `self-exclusion`, `dispute-resolution`, `fairness-rng-testing-methods`, `accounts-withdrawals-and-bonuses`.

---

## 1. Проверка в базе WordPress (wp_posts)

**Примечание:** В репозитории нет доступа к БД (нет docker-compose, нет SQL-дампов). При наличии запущенной локальной WP выполните запрос ниже.

### SQL-запрос для проверки

```sql
SELECT ID, post_name, post_title, post_status, post_type,
       LENGTH(post_content) AS content_length,
       (SELECT meta_value FROM wp_postmeta WHERE post_id = p.ID AND meta_key = '_wp_page_template' LIMIT 1) AS page_template
FROM wp_posts p
WHERE p.post_type = 'page'
  AND p.post_name IN (
    'about',
    'contacts',
    'terms',
    'responsible',
    'privacy-policy',
    'self-exclusion',
    'dispute-resolution',
    'fairness-rng-testing-methods',
    'accounts-withdrawals-and-bonuses'
  )
ORDER BY FIELD(p.post_name,
  'about', 'contacts', 'terms', 'responsible', 'privacy-policy',
  'self-exclusion', 'dispute-resolution', 'fairness-rng-testing-methods', 'accounts-withdrawals-and-bonuses');
```

Если в вашей установке префикс таблиц не `wp_`, замените `wp_posts` и `wp_postmeta` на актуальные имена (например `TVXFZYUMh_posts` по RUN_LOCAL.md).

### Ожидаемый результат (шаблон для заполнения)

| slug | найдено | ID | content_length | page_template |
|------|---------|-----|----------------|---------------|
| about | да/нет | — | — | — |
| contacts | да/нет | — | — | — |
| terms | да/нет | — | — | — |
| responsible | да/нет | — | — | — |
| privacy-policy | да/нет | — | — | — |
| self-exclusion | да/нет | — | — | — |
| dispute-resolution | да/нет | — | — | — |
| fairness-rng-testing-methods | да/нет | — | — | — |
| accounts-withdrawals-and-bonuses | да/нет | — | — | — |

---

## 2. Состояние в /dist (статический экспорт)

На момент аудита в каталоге `dist/` **не было** физических папок/файлов для этих 9 страниц, потому что:

- футер в шаблоне `footer.php` содержал ссылки на `/game/` вместо системных slug;
- краулер при экспорте с прод-сайта не получал ссылок на эти страницы из футера.

После внесённых изменений:

- в `scripts/export-crawler.js` добавлены **seed URL** для всех 9 страниц — они гарантированно попадают в обход при `npm run build:live`;
- в `wp-content/themes/zento/partials/footer.php` восстановлены ссылки на правильные slug.

Проверка после пересборки: скрипт `node scripts/verify_system_pages.js` проверяет наличие файлов и ожидаемых H1 в `dist/`.

При экспорте с прода футер в сохранённом HTML изначально содержит ссылки на `/game/`. Скрипт `fix-html-paths.js` (шаг после краулера в `build:live`) заменяет в футере ссылки для этих 9 пунктов на правильные относительные пути (`/about/`, `/contacts/` и т.д.), так что в собранной статике пункты «حول الموقع» ведут на системные страницы.

---

## 3. Ожидаемые H1 и пункты меню (reference)

| slug | ожидаемый H1 | label в меню (حول الموقع) |
|------|----------------|----------------------------|
| about | نبذة عن شركتنا | من نحن |
| contacts | معلومات الاتصال | معلومات الاتصال |
| terms | الشروط والأحكام | شروط الاستخدام |
| responsible | اللعب المسؤول | اللعب المسؤول |
| privacy-policy | السرية وإدارة البيانات الشخصية | الخصوصية وإدارة البيانات |
| self-exclusion | الإقصاء الذاتي | الاستبعاد الذاتي |
| dispute-resolution | حل النزاع | حل النزاعات |
| fairness-rng-testing-methods | الإنصاف وطرق اختبار RNG | طرق اختبار النزاهة والعشوائية |
| accounts-withdrawals-and-bonuses | الحسابات والعوائد والمكافآت | الحسابات والمدفوعات والمكافآت |
