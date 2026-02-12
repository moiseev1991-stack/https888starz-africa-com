# Контент системных страниц — источник и действия

Для каждой из 9 страниц: источник контента и что сделано.

---

## Источники

- **PROD** — страница скачана с https://888starz-africa.com/ при экспорте (краулер с seed URL).
- **DB** — страница уже есть в локальной WP, контент из `post_content`.
- **WAYBACK** — использован архив (fallback при недоступности прод).

---

## По страницам

| slug | источник | дата | что сделано |
|------|----------|------|-------------|
| about | PROD | 2025-02-12 | На проде есть, H1 «نبذة عن شركتنا». Seed URL добавлен в export-crawler; при `npm run build:live` страница скачивается и сохраняется в dist/about/index.html. |
| contacts | PROD | 2025-02-12 | На проде есть, H1 «معلومات الاتصال». Seed URL в краулере; экспорт в dist/contacts/index.html. |
| terms | PROD | 2025-02-12 | На проде есть, H1 «الشروط والأحكام». Seed URL в краулере; экспорт в dist/terms/index.html. |
| responsible | PROD | 2025-02-12 | Seed URL в краулере; экспорт из прод в dist/responsible/index.html. |
| privacy-policy | PROD | 2025-02-12 | На проде есть, H1 «السرية وإدارة البيانات الشخصية». Seed URL в краулере; экспорт в dist/privacy-policy/index.html. |
| self-exclusion | PROD | 2025-02-12 | Ожидаемый H1 «الإقصاء الذاتي». Seed URL в краулере; экспорт в dist/self-exclusion/index.html. |
| dispute-resolution | PROD | 2025-02-12 | Ожидаемый H1 «حل النزاع». Seed URL в краулере; экспорт в dist/dispute-resolution/index.html. |
| fairness-rng-testing-methods | PROD | 2025-02-12 | Ожидаемый H1 «الإنصاف وطرق اختبار RNG». Seed URL в краулере; экспорт в dist/fairness-rng-testing-methods/index.html. |
| accounts-withdrawals-and-bonuses | PROD | 2025-02-12 | Ожидаемый H1 «الحسابات والعوائد والمكافآت». Seed URL в краулере; экспорт в dist/accounts-withdrawals-and-bonuses/index.html. |

---

## Восстановление в локальной WP (если страниц нет в БД)

При необходимости создать или обновить страницы вручную в админке WP:

1. Создать страницу с нужным slug (post_name).
2. Заголовок (post_title) — по желанию совпадает с H1 из таблицы в SYSTEM_PAGES_AUDIT.md.
3. Контент можно взять из сохранённого после экспорта `dist/<slug>/index.html`: скопировать разметку из основного контентного блока (без header/footer), вставить в редактор страницы.
4. После сохранения пересобрать статику: при экспорте с локального WP (BASE_URL=http://localhost:8080) эти страницы попадут в dist по текущим ссылкам из футера.

Текущая схема: экспорт по умолчанию идёт с **прод-сайта** (STATIC_BASE_URL=https://888starz-africa.com), поэтому контент системных страниц в dist берётся с прода; футер в шаблоне исправлен так, чтобы в собранной статике ссылки «حول الموقع» вели на эти 9 страниц.
