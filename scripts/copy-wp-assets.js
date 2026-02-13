/**
 * Copy WP assets into dist so that /wp-content/... links work.
 * Run after export-crawler.js (which only saves HTML).
 */

const fs = require('fs');
const path = require('path');

const PROJECT_ROOT = path.resolve(__dirname, '..');
const WP_CONTENT = path.join(PROJECT_ROOT, 'wp-content');
const OUT_DIR = process.env.STATIC_OUT_DIR || 'dist';
const DIST = path.join(PROJECT_ROOT, OUT_DIR);

function copyRecursive(src, dest) {
  if (!fs.existsSync(src)) return;
  const stat = fs.statSync(src);
  if (stat.isDirectory()) {
    if (!fs.existsSync(dest)) fs.mkdirSync(dest, { recursive: true });
    fs.readdirSync(src).forEach(name => {
      copyRecursive(path.join(src, name), path.join(dest, name));
    });
  } else {
    fs.mkdirSync(path.dirname(dest), { recursive: true });
    fs.copyFileSync(src, dest);
  }
}

function main() {
  if (!fs.existsSync(DIST)) {
    console.error('dist/ not found. Run export first.');
    process.exit(1);
  }
  const distContent = path.join(DIST, 'wp-content');
  if (!fs.existsSync(WP_CONTENT)) {
    console.log('wp-content not found, skipping assets copy');
  } else {
    console.log('Copying wp-content/uploads and themes/zento/assets to dist...');
    copyRecursive(path.join(WP_CONTENT, 'uploads'), path.join(distContent, 'uploads'));
    const themeAssets = path.join(WP_CONTENT, 'themes', 'zento', 'assets');
    if (fs.existsSync(themeAssets))
      copyRecursive(themeAssets, path.join(distContent, 'themes', 'zento', 'assets'));
    console.log('Assets copy done.');
  }
  
  // Copy .htaccess for caching
  const htaccessSrc = path.join(PROJECT_ROOT, 'dist', '.htaccess');
  const htaccessDest = path.join(DIST, '.htaccess');
  if (fs.existsSync(htaccessSrc)) {
    fs.copyFileSync(htaccessSrc, htaccessDest);
    console.log('Copied .htaccess for caching configuration.');
  }
}

main();
