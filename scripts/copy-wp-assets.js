#!/usr/bin/env node
/**
 * Copy WP assets to public/ so /wp-content/... and /wp-includes/... resolve.
 * Run from repo root. Source: 1/, target: public/
 */

const fs = require('fs');
const path = require('path');

const ROOT = path.resolve(__dirname, '..');
const SRC = path.join(ROOT, '1');
const PUB = path.join(ROOT, 'public');

function copyDir(src, dest) {
  if (!fs.existsSync(src)) return;
  fs.mkdirSync(dest, { recursive: true });
  for (const name of fs.readdirSync(src)) {
    const s = path.join(src, name);
    const d = path.join(dest, name);
    if (fs.statSync(s).isDirectory()) copyDir(s, d);
    else fs.copyFileSync(s, d);
  }
}

const copies = [
  ['wp-content/uploads', 'wp-content/uploads'],
  ['wp-content/themes/zento', 'wp-content/themes/zento'],
  ['wp-content/plugins/flexy-breadcrumb', 'wp-content/plugins/flexy-breadcrumb'],
  ['wp-includes/js/jquery', 'wp-includes/js/jquery'],
];

const files = [
  ['wp-includes/js/wp-emoji-release.min.js', 'wp-includes/js/wp-emoji-release.min.js'],
  ['favicon.ico', 'favicon.ico'],
  ['favicon.svg', 'favicon.svg'],
  ['site.webmanifest', 'site.webmanifest'],
];

for (const [from, to] of copies) {
  const src = path.join(SRC, from);
  const dest = path.join(PUB, to);
  if (fs.existsSync(src)) {
    copyDir(src, dest);
    console.log('Copied', from, '->', to);
  }
}

for (const [from, to] of files) {
  const src = path.join(SRC, from);
  const dest = path.join(PUB, to);
  if (fs.existsSync(src)) {
    fs.mkdirSync(path.dirname(dest), { recursive: true });
    fs.copyFileSync(src, dest);
    console.log('Copied', from, '->', to);
  }
}

console.log('Done.');
