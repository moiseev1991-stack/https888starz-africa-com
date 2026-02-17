const fs = require('fs');
const path = require('path');

// Пути к файлам
const apkHtmlPath = path.join(__dirname, '..', 'public', 'apk', 'index.html');
const promoCodeHtmlPath = path.join(__dirname, '..', 'public', 'promo-code', 'index.html');
const registrationHtmlPath = path.join(__dirname, '..', 'public', 'registration', 'index.html');

// Контент-слайдер с таймером и промо-кодом
const contentSliderHTML = `
                        <section class=" section-wrapper2-ar section-wrapper2 grid-wrapper2 d-grid2 padding-horizontal2 js-grid-wrapper2">
            <div class="grid-item2">
                <div class="item1 d-flex2 fd-column2">
                    <div class="content2 d-flex2 fd-column2 align-top2">
                        <div class="ribbon-2">عروض</div>
                        <div class="card-head1 d-flex2 ai-center1 align-top2">
                            <div class="timer-img">
                                <noindex>
                                    <a target="_blank" rel="nofollow noopener" href="/game/" class="ui-link">
                                        <img loading="lazy" decoding="async" alt="888Starz" src="/wp-content/uploads/2025/01/favicon.png" class="loaded2">
                                    </a>
                                </noindex>
                                <div class="timer" dir="ltr">
                                    <div class="timer__items">
                                        <div class="timer__item timer__hours">00</div>
                                        <div class="timer__item timer__minutes">02</div>
                                        <div class="timer__item timer__seconds">59</div>
                                    </div>
                                </div>
                            </div>
                            <p class="bonustitle">احصل على هذا <span style="border-bottom: 2px solid #f37021; padding-bottom: 3px;">قبل 1500€</span> باستخدام الرمز الترويجي!</p>
                            <div class="promo" id="promoCode" onclick="copyPromoCode()">AR888BONUS
                                <div class="copy-icon">
                                    <i class="fa fa-clone" aria-hidden="true"></i>
                                    <div class="copy-notification" id="copyNotification">تم النسخ</div>
                                </div>
                            </div>
                            <noindex><a target="_blank" rel="nofollow noopener" href="/game/" class="button2 filled-green2 radius-41 ui-link"><br>
                                    <span class="text-overflow1">اذهب إلى 888Starz</span><br> </a></noindex>
                            <div class="promotext">يمكنك الحصول على المكافأة فقط عند الانتقال من موقعنا</div>
                        </div>
                    </div>
                </div>
        </div></section>
`;

// Функция для добавления контент-слайдера
function addContentSlider(filePath) {
    console.log(`Добавляю контент-слайдер в ${path.basename(filePath)}...`);
    
    let html = fs.readFileSync(filePath, 'utf8');
    
    // Находим место после <div class="text"> и перед первым <p>
    const textDivPattern = /(<div class="text">\s*)/;
    const match = html.match(textDivPattern);
    
    if (match) {
        const insertPosition = match.index + match[0].length;
        html = html.substring(0, insertPosition) + contentSliderHTML + html.substring(insertPosition);
        fs.writeFileSync(filePath, html, 'utf8');
        console.log(`✓ Контент-слайдер добавлен в ${path.basename(filePath)}`);
    } else {
        console.error(`✗ Не найдено место для вставки в ${path.basename(filePath)}`);
    }
}

// Добавляем контент-слайдер во все страницы
addContentSlider(apkHtmlPath);
addContentSlider(promoCodeHtmlPath);
addContentSlider(registrationHtmlPath);

console.log('Готово! Контент-слайдер добавлен во все страницы.');
