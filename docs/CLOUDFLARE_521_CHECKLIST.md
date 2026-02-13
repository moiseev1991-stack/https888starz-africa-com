# Чек-лист: Cloudflare 521 (Web server is down)

521 значит: Cloudflare достучался до origin, но не получил ответ. Проблема на стороне origin (Beget) или между Cloudflare и Beget.

## В Cloudflare
- **SSL/TLS** → режим «Full» или «Full (strict)» (не «Flexible», если у Beget есть сертификат).
- **DNS** → A/AAAA записи указывают на IP Beget (не на proxy-only если 521).
- **Firewall / WAF** → нет правил, блокирующих запросы к вашему домену или к Beget IP.
- **Under Attack Mode** → выключить для проверки (может давать задержки/блоки).

## На Beget
- Сайт реально открывается по домену на Beget (без Cloudflare): подставьте в hosts IP сервера или отключите proxy в Cloudflare (серое облако) и проверьте.
- **PHP/веб-сервер** не падает на запрос к корню (проверить логи ошибок в панели Beget).
- В **public_html** есть **index.html** (или index.php), и веб-сервер отдаёт его по запросу к `/`.
- Лимиты/анти-DDoS Beget не режут запросы от IP Cloudflare (при необходимости уточнить в поддержке Beget).

## Отдельно от деплоя
Деплой только заливает файлы. 521 — это доступность origin: после успешного деплоя проверьте сайт напрямую (без Cloudflare), затем с Cloudflare.
