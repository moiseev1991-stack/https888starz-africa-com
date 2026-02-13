# Error 521 (Web server is down) — чеклист и план действий

521 значит: **Cloudflare не может установить TCP-соединение с origin (Beget)**. Файлы в `public_html` при этом могут лежать — проблема в сети/DNS/SSL/привязке домена, а не в содержимом деплоя.

---

## Быстрый тест (сразу после 521)

1. В **Cloudflare** → домен **888starzeg-egypt.com** → **DNS** → запись типа **A** или **AAAA** для `@` (или `888starzeg-egypt.com`).
2. Включи **DNS only** (серую тучу) для этой записи — отключи прокси Cloudflare.
3. Открой в браузере: `https://888starzeg-egypt.com` или `http://888starzeg-egypt.com`.

- **Если сайт открылся** → проблема в настройках Cloudflare (прокси/SSL). Дальше раздел «Cloudflare».
- **Если не открылся (таймаут / другая ошибка)** → проблема на стороне Beget (домен/папка/сервер). Дальше раздел «Beget».

После проверки можно снова включить оранжевую тучу (прокси).

---

## 1. Cloudflare

| Что проверить | Где | Действие |
|---------------|-----|----------|
| **A/AAAA запись** | DNS → записи для 888starzeg-egypt.com | A должна указывать на **IP Beget** (см. письмо от Beget или панель «Домены» → «DNS-записи»). Не на Cloudflare IP. При прокси (оранжевая тучка) можно оставить A → любой (часто 192.0.2.1), но **без прокси** запись должна быть именно IP Beget. |
| **Прокси (оранжевая тучка)** | DNS → включена ли «Proxied» | Временно отключить (DNS only) для проверки — см. «Быстрый тест» выше. |
| **SSL/TLS режим** | SSL/TLS → Overview | Для shared-хостинга часто подходит **Flexible** (трафик Cloudflare↔клиент HTTPS, Cloudflare↔origin HTTP). Если ставите Full/Full (Strict) — на Beget должен быть валидный сертификат на 888starzeg-egypt.com. |
| **Origin reachability** | В панели Cloudflare нет прямой кнопки | Проверяется с вашей стороны: см. «Команды проверки» ниже. |

---

## 2. Beget

| Что проверить | Где / как | Действие |
|---------------|-----------|----------|
| **Домен привязан к сайту** | Панель Beget → «Домены» / «Сайты» | Домен **888starzeg-egypt.com** должен быть привязан к хостингу и к папке **public_html** (или к нужной подпапке). Без привязки сервер не будет отдавать сайт по этому домену. |
| **Путь к сайту** | Тот же раздел | Корень сайта = `public_html` для этого домена (у вас: `/home/s/smirno34/888starzeg-egypt.com/public_html`). Должно совпадать с тем, куда деплоит workflow. |
| **Порты 80 / 443** | Обычно включены на shared | Если есть «Веб-сервер» / «Apache» — убедиться, что сервис запущен. На shared Beget обычно уже всё включено. |
| **Файлы на месте** | SSH или FileZilla | В `public_html` должны быть `index.html`, `.htaccess` и остальные файлы сайта. |

---

## 3. Команды проверки (без PowerShell)

### Вариант A: Терминал Beget (SSH)

Подключение: `ssh smirno34_ssh@smirno34.beget.tech` (логин/пароль из панели Beget).

```bash
# Есть ли index и .htaccess
ls -la /home/s/smirno34/888starzeg-egypt.com/public_html/ | head -20

# Локальная проверка: отдаёт ли Apache что-то по localhost (если есть curl)
curl -sI http://127.0.0.1/ 2>/dev/null || true
```

### Вариант B: С любой машины (curl / wget)

Подставьте **реальный IP Beget** (из панели Beget или письма при создании хостинга):

```bash
# Заменить YOUR_BEGET_IP на IP из панели Beget
curl -sI --connect-timeout 5 http://YOUR_BEGET_IP/
curl -sI --connect-timeout 5 -H "Host: 888starzeg-egypt.com" http://YOUR_BEGET_IP/
```

- Если с `Host: 888starzeg-egypt.com` приходит 200/301/302 — origin жив, проблема в DNS/Cloudflare.
- Если connection refused / timeout — проблема на стороне Beget (фаервол, не тот IP, сервис не слушает 80).

### Вариант C: FileZilla (SFTP)

- Хост: из панели Beget (типа `smirno34.beget.tech` или указанный для SFTP).
- Логин/пароль: те же, что в Secrets (SSH_USERNAME / SSH_PASSWORD).
- Перейти в `/home/s/smirno34/888starzeg-egypt.com/public_html` и убедиться, что есть `index.html`, `.htaccess` и папки сайта.

---

## 4. План «сделай раз-два-три»

1. **Проверить Beget**  
   - В панели: домен **888starzeg-egypt.com** привязан к сайту, корень сайта = `public_html`.  
   - По SSH или FileZilla: в `public_html` есть `index.html` и `.htaccess`.

2. **Проверить доступность origin**  
   - Узнать IP Beget (панель / письмо).  
   - Выполнить:  
     `curl -sI -H "Host: 888starzeg-egypt.com" http://IP_BEGET/`  
   - Если ответ 200/301/302 — Beget отдаёт сайт, идём в п.3.  
   - Если нет — править привязку домена/папку/настройки сервера на Beget.

3. **Настроить Cloudflare**  
   - **DNS**: при оранжевой тучке A-запись может быть «dummy» (например 192.0.2.1). При серой (DNS only) — обязательно **реальный IP Beget**.  
   - **SSL/TLS**: поставить **Flexible** (если на Beget нет своего HTTPS), затем при необходимости перейти на Full/Full (Strict) после установки сертификата на Beget.

После этого открыть в браузере `https://888starzeg-egypt.com` — сайт должен открываться.

---

## 5. Важно по безопасности

Пароль SSH (и SFTP) для Beget уже использовался в настройках GitHub Actions. После того как всё заработает, **смени пароль** в панели Beget и обнови секрет **SSH_PASSWORD** в GitHub → Settings → Secrets and variables → Actions.
