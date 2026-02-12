# Аудит: файлы для удаления (сайт без WordPress)

Сайт отдаётся как **статический HTML/CSS/JS** из папки `dist/`. Сборка использует только:
- `wp-content/uploads/` — копируется в dist при сборке
- `wp-content/themes/zento/` — копируется в dist при сборке

Всё остальное, связанное с WordPress, для работы статики **не нужно**.

---

## Удалено (непригодно для статической версии)

### Ядро WordPress
| Путь | Причина |
|------|--------|
| `wp-admin/` | Админка WordPress, не используется |
| `wp-includes/` | Ядро WP (PHP, JS, CSS), не используется |
| `index.php` (корень) | Точка входа WP |
| `wp-activate.php` | Активация мультисайта |
| `wp-blog-header.php` | Загрузка блога |
| `wp-comments-post.php` | Обработка комментариев |
| `wp-cron.php` | Планировщик WP |
| `wp-links-opml.php` | Экспорт ссылок |
| `wp-load.php` | Загрузчик WP |
| `wp-login.php` | Страница входа |
| `wp-mail.php` | Почтовый скрипт |
| `wp-settings.php` | Настройки |
| `wp-signup.php` | Регистрация мультисайта |
| `wp-trackback.php` | Трекбэки |
| `xmlrpc.php` | XML-RPC API |
| `wp-config.php` | Конфиг БД и WP |
| `wp-config-sample.php` | Пример конфига |

### Остальное
| Путь | Причина |
|------|--------|
| `.htaccess` (в корне) | Правила для PHP/WP, для статики не нужны (есть `.htaccess.snippet` для деплоя) |
| `wp_gugum.sql` | Дамп БД, не используется |
| `docker-compose.yml` | Локальный WordPress в Docker, не нужен для статики |
| `readme.html` | Readme WordPress |
| `license.txt` | Лицензия WordPress |
| `favicon (7).zip` | Архив, не используется |

### wp-content (только лишнее)
| Путь | Причина |
|------|--------|
| `wp-content/plugins/` | Плагины WP; статика берёт ресурсы из экспорта/живого сайта и из `uploads` + `themes/zento` |
| `wp-content/languages/` | Переводы админки и плагинов, не нужны |
| `wp-content/maintenance/` | Шаблон режима обслуживания |
| `wp-content/maintenance.php` | Включение режима обслуживания |
| `wp-content/index.php` | Защитный пустой index в каталоге |

---

## Оставлено (нужно для сборки или проекта)

- **`wp-content/uploads/`** — источник картинок для копирования в dist
- **`wp-content/themes/zento/`** — исходники темы, копируются в dist
- **`dist/`** — результат сборки (статический сайт)
- **`scripts/`**, **`package.json`**, **`playwright.config.js`** — сборка и экспорт
- **`tests/`** — тесты
- **Документация** — `AUDIT.md`, `BUILD_AND_DEPLOY.md`, `RUN_LOCAL.md`, `DYNAMIC_REPLACEMENTS.md`, `OPTIMIZATION_REPORT.md`, `nginx.conf.snippet`, `.htaccess.snippet`
- **Иконки/манифест в корне** — `favicon.ico`, `favicon.svg`, `site.webmanifest` и т.д. (могут использоваться или как референс)
- **`888starz.apk`** — файл приложения
- **`.gitignore`**

После удаления перечисленного проект содержит только то, что нужно для статической версии и сборки.
