/**
 * Apply PageSpeed/Google recommendations to static HTML in public/:
 * - Non-blocking CSS (flexy-breadcrumb, font-awesome, fancybox)
 * - Remove duplicate jQuery (head + code.jquery.com), keep one with defer
 * - Defer non-critical scripts
 * - loading="lazy" on images that don't have it
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

function optimize(html) {
  let out = html;

  // 1) Non-blocking: flexy-breadcrumb CSS
  out = out.replace(
    /<link rel="stylesheet" id="flexy-breadcrumb-css" href="\/wp-content\/plugins\/flexy-breadcrumb\/public\/css\/flexy-breadcrumb-public\.css\?ver=1\.2\.1" media="all">/g,
    '<link rel="preload" id="flexy-breadcrumb-css" href="/wp-content/plugins/flexy-breadcrumb/public/css/flexy-breadcrumb-public.css?ver=1.2.1" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">'
  );

  // 2) Non-blocking: font-awesome CSS
  out = out.replace(
    /<link rel="stylesheet" id="flexy-breadcrumb-font-awesome-css" href="\/wp-content\/plugins\/flexy-breadcrumb\/public\/css\/font-awesome\.min\.css\?ver=4\.7\.0" media="all">/g,
    '<link rel="preload" id="flexy-breadcrumb-font-awesome-css" href="/wp-content/plugins/flexy-breadcrumb/public/css/font-awesome.min.css?ver=4.7.0" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">'
  );

  // 3) Remove blocking jQuery from head (keep one later with defer)
  out = out.replace(
    /<script src="\/wp-includes\/js\/jquery\/jquery\.min\.js\?ver=3\.7\.1" id="jquery-core-js"><\/script>\s*<script src="\/wp-includes\/js\/jquery\/jquery-migrate\.min\.js\?ver=3\.4\.1" id="jquery-migrate-js"><\/script>/g,
    ''
  );

  // 4) Remove duplicate code.jquery.com (we use cdnjs jQuery before Owl)
  out = out.replace(/\s*<script src="https:\/\/code\.jquery\.com\/jquery-3\.6\.4\.min\.js"><\/script>\s*/g, '\n');

  // 5) Non-blocking Fancybox CSS
  out = out.replace(
    /<link rel="stylesheet" href="https:\/\/cdnjs\.cloudflare\.com\/ajax\/libs\/fancybox\/3\.5\.7\/jquery\.fancybox\.min\.css">/g,
    '<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">'
  );

  // 6) Ensure jQuery/Owl/Fancybox are NOT deferred (inline script depends on them)
  out = out.replace(
    /<script src="https:\/\/cdnjs\.cloudflare\.com\/ajax\/libs\/jquery\/3\.6\.0\/jquery\.min\.js" defer><\/script>/g,
    '<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>'
  );
  out = out.replace(
    /<script src="https:\/\/cdnjs\.cloudflare\.com\/ajax\/libs\/OwlCarousel2\/2\.3\.4\/owl\.carousel\.min\.js" defer><\/script>/g,
    '<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>'
  );
  out = out.replace(
    /<script src="https:\/\/cdnjs\.cloudflare\.com\/ajax\/libs\/fancybox\/3\.5\.7\/jquery\.fancybox\.min\.js" defer><\/script>/g,
    '<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>'
  );
  // Defer scripts that run after DOM (flexy-breadcrumb, theme scripts, prism)
  out = out.replace(
    /<script src="\/wp-content\/plugins\/flexy-breadcrumb\/public\/js\/flexy-breadcrumb-public\.js\?ver=1\.2\.1" id="flexy-breadcrumb-js"><\/script>/g,
    '<script src="/wp-content/plugins/flexy-breadcrumb/public/js/flexy-breadcrumb-public.js?ver=1.2.1" id="flexy-breadcrumb-js" defer><\/script>'
  );
  out = out.replace(
    /<script src="\/wp-content\/themes\/zento\/assets\/dist\/scripts\.min\.js\?ver=1\.4\.5" id="epcl-scripts-js"><\/script>/g,
    '<script src="/wp-content/themes/zento/assets/dist/scripts.min.js?ver=1.4.5" id="epcl-scripts-js" defer><\/script>'
  );
  out = out.replace(
    /<script src="https:\/\/cdnjs\.cloudflare\.com\/ajax\/libs\/prism\/1\.29\.0\/components\/prism-core\.min\.js\?ver=1\.4\.5" id="prismjs-core-js"><\/script>/g,
    '<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js?ver=1.4.5" id="prismjs-core-js" defer><\/script>'
  );
  out = out.replace(
    /<script src="https:\/\/cdnjs\.cloudflare\.com\/ajax\/libs\/prism\/1\.29\.0\/plugins\/autoloader\/prism-autoloader\.min\.js\?ver=1\.4\.5" id="prismjs-autoloader-js"><\/script>/g,
    '<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js?ver=1.4.5" id="prismjs-autoloader-js" defer><\/script>'
  );
  out = out.replace(
    /<script src="\/wp-content\/themes\/zento\/assets\/dist\/prism-plugins\.min\.js\?ver=1\.4\.5" id="prismjs-plugins-js"><\/script>/g,
    '<script src="/wp-content/themes/zento/assets/dist/prism-plugins.min.js?ver=1.4.5" id="prismjs-plugins-js" defer><\/script>'
  );

  // 7) Lazy load images: add loading="lazy" to <img> that don't have loading=
  out = out.replace(
    /<img(?![^>]*\sloading=)(\s[^>]*)>/gi,
    (m) => m.replace(/<img(\s)/i, '<img loading="lazy"$1')
  );

  // 8) Remove duplicate inline Owl/Fancybox init (only app.js should init — fixes broken dots on server)
  out = out.replace(
    /<script>\s*\/\/ Отключение пассивных[\s\S]*?\.slider-888-slider[\s\S]*?\}\);\s*\}\);\s*<\/script>/,
    ''
  );

  // 9) Load app.js right after Fancybox (no defer) so Owl dots work on server — same order as localhost
  if (out.includes('jquery.fancybox.min.js"></script>') && !out.includes('jquery.fancybox.min.js"></script>\n<script src="/assets/js/app.js"></script>')) {
    out = out.replace(
      /<script src="https:\/\/cdnjs\.cloudflare\.com\/ajax\/libs\/fancybox\/3\.5\.7\/jquery\.fancybox\.min\.js"><\/script>\s*\n/,
      '<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>\n<script src="/assets/js/app.js"></script>\n'
    );
  }
  // Remove duplicate app.js from end (with defer)
  out = out.replace(
    /<script src="\/assets\/js\/app\.js" defer><\/script>\s*\n\s*<\/body>/,
    '</body>'
  );

  return out;
}

htmlFiles.forEach((file) => {
  const filePath = path.join(publicDir, file);
  if (!fs.existsSync(filePath)) {
    console.warn('Skip (not found):', file);
    return;
  }
  const html = fs.readFileSync(filePath, 'utf8');
  const optimized = optimize(html);
  if (optimized !== html) {
    fs.writeFileSync(filePath, optimized, 'utf8');
    console.log('Updated:', file);
  }
});
console.log('Done.');
