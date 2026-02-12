/**
 * Запуск локального сервера для папки dist.
 * Если dist нет или пустой — создаётся заглушка с подсказкой.
 */

const path = require('path');
const fs = require('fs');
const { spawn } = require('child_process');

const PROJECT_ROOT = path.resolve(__dirname, '..');
const DIST = path.join(PROJECT_ROOT, 'dist');

if (!fs.existsSync(DIST) || !fs.existsSync(path.join(DIST, 'index.html'))) {
  fs.mkdirSync(DIST, { recursive: true });
  fs.writeFileSync(
    path.join(DIST, 'index.html'),
    `<!DOCTYPE html><html><head><meta charset="utf-8"><title>888starz — статика</title></head>
<body style="font-family:sans-serif;max-width:600px;margin:4rem auto;padding:0 1rem;">
  <h1>Папка dist пуста</h1>
  <p>Сначала соберите статику:</p>
  <ol>
    <li>Запустите WordPress: <code>docker-compose up -d</code></li>
    <li>В БД установите siteurl/home = <code>http://localhost:8080</code></li>
    <li>Соберите: <code>npm run build</code></li>
  </ol>
  <p>Подробнее: BUILD_AND_DEPLOY.md в корне проекта.</p>
</body></html>`,
    'utf8'
  );
  console.log('Создана заглушка в dist/. Запустите "npm run build" (при работающем WP на :8080), затем снова "npm run serve".');
}

const PORT = process.env.SERVE_PORT || '3080';
const serveMain = path.join(PROJECT_ROOT, 'node_modules', 'serve', 'build', 'main.js');
const useLocal = fs.existsSync(serveMain);
const cmd = useLocal ? process.execPath : 'npx';
const args = useLocal
  ? [serveMain, '-l', PORT, '--no-port-switching', 'dist']
  : ['serve', '-l', PORT, '--no-port-switching', 'dist'];
const child = spawn(cmd, args, {
  cwd: PROJECT_ROOT,
  stdio: 'inherit',
  shell: false,
});

child.on('error', (err) => {
  console.error(err);
  process.exit(1);
});

child.on('exit', (code) => process.exit(code || 0));
