/**
 * Remove script tags that load from /wp-includes/ and /wp-content/ (return 500 on server).
 * Fixes "Unexpected token '<'" and owlCarousel not a function.
 */
const fs = require('fs');
const path = require('path');

const publicDir = path.join(__dirname, '..', 'public');
const htmlFiles = [
  'index.html',
  'about/index.html', 'contacts/index.html', 'terms/index.html',
  'responsible/index.html', 'privacy-policy/index.html', 'self-exclusion/index.html',
  'dispute-resolution/index.html', 'fairness-rng-testing-methods/index.html',
  'accounts-withdrawals-and-bonuses/index.html', 'cookies/index.html',
  'registration/index.html', 'apk/index.html', 'promo-code/index.html'
];

const REMOVALS = [
  [ /<script src="\/wp-includes\/js\/wp-emoji-release\.min\.js\?ver=[^"]+" defer=""><\/script><\/head>/g, '</head>' ],
  [ /<script src="\/wp-content\/plugins\/flexy-breadcrumb\/public\/js\/flexy-breadcrumb-public\.js\?ver=[^"]+" id="flexy-breadcrumb-js" defer><\/script>\s*\n?/g, '' ],
  [ /<script src="\/wp-content\/themes\/zento\/assets\/dist\/scripts\.min\.js\?ver=[^"]+" id="epcl-scripts-js" defer><\/script>\s*\n?/g, '' ],
  [ /<script src="\/wp-content\/themes\/zento\/assets\/dist\/prism-plugins\.min\.js\?ver=[^"]+" id="prismjs-plugins-js" defer><\/script>\s*\n?/g, '' ],
  [ /<script id="wp-emoji-settings" type="application\/json">\s*\{[^<]*\}\s*<\/script>\s*<script type="module">[\s\S]*?\/wp-includes\/js\/wp-emoji-loader\.min\.js\s*<\/script>\s*(?=<script>\(function\(\)\{function init\(\))/g, '' ],
];

htmlFiles.forEach((file) => {
  const filePath = path.join(publicDir, file);
  if (!fs.existsSync(filePath)) {
    console.warn('Skip (not found):', file);
    return;
  }
  let html = fs.readFileSync(filePath, 'utf8');
  let changed = false;
  REMOVALS.forEach(([regex, replacement]) => {
    const next = html.replace(regex, replacement);
    if (next !== html) {
      html = next;
      changed = true;
    }
  });
  if (changed) {
    fs.writeFileSync(filePath, html);
    console.log('Updated:', file);
  }
});
console.log('Done.');
