#!/usr/bin/env node
// Ensure relative paths from subdirs work: replace href="/ with href="../../ for depth
// Actually we use root-relative paths (/wp-content/...) so no change needed when served from root.
// Just list any local path that might 404.
const fs = require('fs');
const path = require('path');
const PUBLIC = path.resolve(__dirname, '..', 'public');

function getLocalPaths(html) {
  const out = new Set();
  const r = /(?:href|src)=["'](\/(?!\/)[^"']+)["']/g;
  let m;
  while ((m = r.exec(html)) !== null) {
    const p = m[1].split('?')[0];
    if (p.startsWith('/') && !p.startsWith('//')) out.add(p);
  }
  return [...out];
}

const seen = new Set();
const missing = [];
const dirs = [''];
while (dirs.length) {
  const d = dirs.pop();
  const full = path.join(PUBLIC, d);
  if (!fs.existsSync(full)) continue;
  for (const name of fs.readdirSync(full)) {
    const rel = path.join(d, name);
    const abs = path.join(PUBLIC, rel);
    if (fs.statSync(abs).isDirectory()) dirs.push(rel);
    else if (name === 'index.html') {
      const html = fs.readFileSync(abs, 'utf8');
      for (const p of getLocalPaths(html)) {
        if (seen.has(p)) continue;
        seen.add(p);
        const clean = p.replace(/^\//, '').replace(/\?.*/, '').replace(/#.*/, '');
        const filePath = path.join(PUBLIC, clean);
        const isDir = clean.endsWith('/') || !path.extname(clean);
        if (!clean || clean === 'game' || clean === '888starz.apk') continue;
        if (!fs.existsSync(filePath) && !fs.existsSync(filePath + '.html') && !p.includes('wp-json') && !p.includes('feed') && !p.includes('xmlrpc')) {
          missing.push(p);
        }
      }
    }
  }
}

if (missing.length) {
  console.log('Paths that may 404 (file not found):');
  missing.slice(0, 30).forEach((p) => console.log(' ', p));
  if (missing.length > 30) console.log(' ... and', missing.length - 30, 'more');
} else {
  console.log('All collected local paths exist.');
}
