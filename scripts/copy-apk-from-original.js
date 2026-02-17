const fs = require('fs');
const path = require('path');
const { chromium } = require('playwright');

const ROOT = path.resolve(__dirname, '..');
const PUBLIC = path.join(ROOT, 'public');
const apkHtmlPath = path.join(PUBLIC, 'apk', 'index.html');
const ORIGINAL_URL = 'https://888starz-africa.com/apk/';

async function copyFullPageFromOriginal() {
    console.log('Получаю полный HTML с оригинального сайта...');
    console.log(`URL: ${ORIGINAL_URL}`);
    
    const browser = await chromium.launch({ headless: true });
    const context = await browser.newContext({ 
        userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36' 
    });
    
    try {
        const page = await context.newPage();
        console.log('Загружаю страницу...');
        await page.goto(ORIGINAL_URL, { waitUntil: 'networkidle', timeout: 60000 });
        await page.waitForTimeout(3000); // Дополнительное ожидание для загрузки динамического контента
        
        console.log('Получаю HTML...');
        let html = await page.content();
        
        // Заменяем абсолютные URL на относительные пути
        html = html.replace(/https:\/\/888starz-africa\.com/g, '');
        html = html.replace(/http:\/\/888starz-africa\.com/g, '');
        
        // Сохраняем полный HTML
        fs.writeFileSync(apkHtmlPath, html, 'utf8');
        console.log(`✓ Полный HTML сохранен: ${apkHtmlPath}`);
        
        await page.close();
    } catch (err) {
        console.error('Ошибка при получении HTML:', err.message);
        throw err;
    } finally {
        await browser.close();
    }
}

copyFullPageFromOriginal()
    .then(() => {
        console.log('Готово! Полный HTML скопирован с оригинального сайта.');
    })
    .catch((err) => {
        console.error('Ошибка:', err);
        process.exit(1);
    });
