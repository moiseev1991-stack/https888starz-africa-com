# Run WordPress locally (888starz-africa)

## Требования

- Docker и Docker Compose  
- Или: PHP 8.x, MariaDB/MySQL, веб-сервер (Apache/Nginx)

---

## Вариант A: Docker Compose

1. В корне проекта создайте `docker-compose.yml` (см. ниже).
2. Импорт БД:
   - Поднять контейнеры: `docker compose up -d`
   - Импортировать дамп:  
     `docker compose exec db mysql -u root -p<MYSQL_ROOT_PASSWORD> wp_gugum < wp_gugum.sql`  
     или скопировать `wp_gugum.sql` в контейнер и выполнить импорт там.
3. Настроить `siteurl` и `home` в БД под локальный URL, например:
   - `http://localhost:8080` или `http://wp.local`
   - Через WP-CLI:  
     `docker compose exec wp wp option update siteurl 'http://localhost:8080' --allow-root`  
     `docker compose exec wp wp option update home 'http://localhost:8080' --allow-root`
4. Указать в `1/wp-config.php` доступ к БД:
   - DB_HOST = `db` (в Docker) или `localhost` (локально)
   - DB_USER, DB_PASSWORD, DB_NAME — как в `docker-compose.yml` / локальной MySQL.

### Пример docker-compose.yml

```yaml
version: '3'
services:
  db:
    image: mariadb:10.6
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: wp_gugum
      MYSQL_USER: wp_user
      MYSQL_PASSWORD: wp_pass
    volumes:
      - db_data:/var/lib/mysql
    ports:
      - "3306:3306"
  php:
    image: php:8.2-fpm-alpine
    volumes:
      - ./1:/var/www/html
  nginx:
    image: nginx:alpine
    ports:
      - "8080:80"
    volumes:
      - ./1:/var/www/html
      - ./config/nginx.conf:/etc/nginx/conf.d/default.conf
volumes:
  db_data:
```

При использовании этого примера корень сайта — `./1` (каталог WordPress). Nginx должен указывать document root на `/var/www/html`.

---

## Вариант B: Локальный PHP + MariaDB

1. Установить MariaDB, создать БД `wp_gugum` и пользователя с правами на неё.
2. Импортировать дамп:  
   `mysql -u <user> -p wp_gugum < wp_gugum.sql`
3. В `1/wp-config.php` задать DB_NAME, DB_USER, DB_PASSWORD, DB_HOST (например `localhost` или `127.0.0.1:3306`).
4. Поднять PHP встроенным сервером из каталога `1`:  
   `php -S localhost:8080 -t 1`  
   или настроить Apache/Nginx с document root на папку `1`.
5. В БД обновить siteurl/home на выбранный URL (например `http://localhost:8080`).

---

## После запуска

- Открыть в браузере выбранный URL (например http://localhost:8080).
- Проверить: главная, системные страницы (/about/, /contacts/, /terms/, …), медиа, стили/скрипты без 404.
- Для экспорта в статику: использовать crawler (wget/httrack/playwright) или плагин экспорта с этого локального URL.
