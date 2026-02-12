# Восстановление пунктов меню (футер «حول الموقع»)

## Где формируется футер

Блок «حول الموقع» (About the site) **захардкожен** в шаблоне темы, а не выводится через `wp_nav_menu`.

- **Файл:** `wp-content/themes/zento/partials/footer.php`
- **Блок:** первый `<div class="block-ul-footer">` внутри `<nav class="navigation-footer">` (desktop-версия).
- **Меню WordPress:** в футере не используется; верхнее меню выводится через `wp_nav_menu('epcl_header')` в `partials/header/navigation.php`.

## Что сделано

В `footer.php` восстановлены ссылки для **9 системных страниц** в том же блоке «حول الموقع», с сохранением текста пунктов (labels). Остальные пункты (برنامج الشراكة, سياسة ملفات تعريف الارتباط, سياسة مكافحة غسل الأموال, سياسة اعرف عميلك) по-прежнему ведут на `/game/` (вне текущего задания).

## Соответствие: label → URL

| Пункт меню (label) | URL |
|--------------------|-----|
| من نحن | /about/ |
| معلومات الاتصال | /contacts/ |
| شروط الاستخدام | /terms/ |
| اللعب المسؤول | /responsible/ |
| الخصوصية وإدارة البيانات | /privacy-policy/ |
| الاستبعاد الذاتي | /self-exclusion/ |
| حل النزاعات | /dispute-resolution/ |
| طرق اختبار النزاهة والعشوائية | /fairness-rng-testing-methods/ |
| الحسابات والمدفوعات والمكافآت | /accounts-withdrawals-and-bonuses/ |

## Порядок в разметке

Порядок ссылок в блоке сохранён как на проде: about → (partnership) → contacts → terms → responsible → privacy-policy → (cookies, AML, KYC) → self-exclusion → dispute-resolution → fairness-rng → accounts-withdrawals-and-bonuses.

## Проверка

После сборки статики (`npm run build:live` или аналог):

- На любой странице в `dist/` в футере блок «حول الموقع» содержит эти 9 ссылок с указанными labels.
- Каждая ссылка ведёт на соответствующий slug и открывается (в dist — `/<slug>/index.html` с ожидаемым H1). Проверка автоматизирована в `node scripts/verify_system_pages.js`.
