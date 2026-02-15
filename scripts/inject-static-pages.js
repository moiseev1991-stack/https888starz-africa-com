#!/usr/bin/env node
// Inject app.js before </body> in all public index.html; strip Yandex, fix canonical

const fs = require('fs');
const path = require('path');

const ROOT = path.resolve(__dirname, '..');
const PUBLIC = path.join(ROOT, 'public');
const APP_SCRIPT = '<script src="/assets/js/app.js" defer></script>';

function walkDir(dir, callback) {
  if (!fs.existsSync(dir)) return;
  for (const name of fs.readdirSync(dir)) {
    const full = path.join(dir, name);
    if (fs.statSync(full).isDirectory()) walkDir(full, callback);
    else callback(full);
  }
}

walkDir(PUBLIC, (file) => {
  if (path.basename(file) !== 'index.html') return;
  let html = fs.readFileSync(file, 'utf8');

  if (html.includes('/assets/js/app.js')) return;

  html = html.replace(/\s*<script[^>]*src="https:\/\/mc\.yandex\.ru[^"]*"[^>]*><\/script>\s*/gi, '\n');
  html = html.replace(/<link rel="canonical" href="https:\/\/888starz-africa\.com[^"]*"\/?>/, '');
  html = html.replace('</body>', APP_SCRIPT + '\n</body>');

  fs.writeFileSync(file, html);
  console.log('Updated', path.relative(PUBLIC, file));
});

console.log('Done.');
