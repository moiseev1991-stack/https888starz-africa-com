# Запуск WordPress локально (для экспорта в статику)

Нужен установленный **Docker** и **Docker Compose**.

---

## 1. Запуск контейнеров

Из корня проекта:

```bash
docker-compose up -d
```

- Первый запуск может занять несколько минут: MariaDB создаёт БД `wp_gugum` и выполняет `wp_gugum.sql` из `docker-entrypoint-initdb.d`.
- Сайт будет доступен по **http://localhost:8080** после смены URL в БД (шаг 2).

---

## 2. Смена siteurl и home на локальный адрес

В дампе прописаны `https://888starz-africa.com`. Для локальной работы их нужно заменить на `http://localhost:8080`.

### Вариант A: WP-CLI (если установлен локально)

Указать корректный хост БД (порт 3306 проброшен на хост):

```bash
docker-compose exec wp wp option update siteurl http://localhost:8080 --allow-root
docker-compose exec wp wp option update home http://localhost:8080 --allow-root
```

Если WP-CLI не установлен в образе:

```bash
docker-compose exec wp bash -c "curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && chmod +x wp-cli.phar && php wp-cli.phar option update siteurl http://localhost:8080 --allow-root && php wp-cli.phar option update home http://localhost:8080 --allow-root"
```

### Вариант B: Прямое обновление в БД

Подключиться к MySQL и выполнить:

```sql
UPDATE TVXFZYUMh_options SET option_value = 'http://localhost:8080' WHERE option_name IN ('siteurl', 'home');
```

Через Docker:

```bash
docker-compose exec db mysql -u wp_rbje6 -p'Wfy7u!Xw~1hgh34a' wp_gugum -e "UPDATE TVXFZYUMh_options SET option_value = 'http://localhost:8080' WHERE option_name IN ('siteurl', 'home');"
```

---

## 3. Проверка

1. Открыть в браузере: **http://localhost:8080**
2. Проверить: главная, несколько страниц и записей, архивы категорий/тегов, мультиязычные URL (Polylang), медиа.
3. Убедиться, что стили и скрипты грузятся без 404.

При необходимости сбросить пароль админа:

```bash
docker-compose exec wp wp user list --allow-root
docker-compose exec wp wp user update 1 --user_pass=NEW_PASSWORD --allow-root
```

(если WP-CLI доступен в контейнере).

---

## 4. Остановка

```bash
docker-compose down
```

Данные БД сохраняются в volume `wp_db_data`. Чтобы начать с чистой БД:

```bash
docker-compose down -v
docker-compose up -d
```

и снова выполнить шаг 2 (смена siteurl/home).

---

## 5. Дальнейшие шаги

После того как сайт открывается локально по http://localhost:8080:

- Запустить статический экспорт (см. **BUILD_AND_DEPLOY.md**, шаг экспорта).
- Экспорт делается обходом сайта (wget/crawler) с базовым URL `http://localhost:8080`.
