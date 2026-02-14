/**
 * Download Embla Carousel UMD build to static-assets/embla/ for use in static build.
 * Run once or when updating: node scripts/download-embla.js
 */
const fs = require('fs');
const path = require('path');
const https = require('https');

const EMBLA_VERSION = '8.5.1';
const EMBLA_UMD_URL = `https://unpkg.com/embla-carousel@${EMBLA_VERSION}/embla-carousel.umd.js`;
const STATIC_ASSETS = path.join(__dirname, 'static-assets', 'embla');
const DEST_FILE = path.join(STATIC_ASSETS, 'embla-carousel.umd.js');

function download(url) {
  return new Promise((resolve, reject) => {
    https.get(url, (res) => {
      if (res.statusCode !== 200) {
        reject(new Error(`HTTP ${res.statusCode}`));
        return;
      }
      const chunks = [];
      res.on('data', (c) => chunks.push(c));
      res.on('end', () => resolve(Buffer.concat(chunks)));
      res.on('error', reject);
    }).on('error', reject);
  });
}

async function main() {
  if (!fs.existsSync(STATIC_ASSETS)) {
    fs.mkdirSync(STATIC_ASSETS, { recursive: true });
  }
  console.log('Downloading Embla Carousel', EMBLA_VERSION, '...');
  const buf = await download(EMBLA_UMD_URL);
  fs.writeFileSync(DEST_FILE, buf);
  console.log('Saved to', DEST_FILE);
}

main().catch((err) => {
  console.error(err);
  process.exit(1);
});
