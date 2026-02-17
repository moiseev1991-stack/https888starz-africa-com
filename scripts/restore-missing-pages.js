const fs = require('fs');
const path = require('path');

// Пути к файлам
const indexHtmlPath = path.join(__dirname, '..', 'public', 'index.html');
const apkHtmlPath = path.join(__dirname, '..', 'public', 'apk', 'index.html');
const promoCodeHtmlPath = path.join(__dirname, '..', 'public', 'promo-code', 'index.html');
const registrationHtmlPath = path.join(__dirname, '..', 'public', 'registration', 'index.html');

// Читаем index.html как шаблон
const indexHtml = fs.readFileSync(indexHtmlPath, 'utf8');

// Находим начало body (после закрывающего тега </head>)
const headEndIndex = indexHtml.indexOf('</head>');
if (headEndIndex === -1) {
    console.error('Не найден закрывающий тег </head> в index.html');
    process.exit(1);
}

// Находим начало wrapper
const wrapperStartIndex = indexHtml.indexOf('<div id="wrapper">');
if (wrapperStartIndex === -1) {
    console.error('Не найден <div id="wrapper"> в index.html');
    process.exit(1);
}

// Находим конец wrapper
const wrapperEndIndex = indexHtml.indexOf('<!-- end: #wrapper -->');
if (wrapperEndIndex === -1) {
    console.error('Не найден <!-- end: #wrapper --> в index.html');
    process.exit(1);
}

// Находим начало main content (после wrapper)
const mainStartIndex = indexHtml.indexOf('<main id="page"', wrapperStartIndex);
if (mainStartIndex === -1) {
    console.error('Не найден <main id="page"> в index.html');
    process.exit(1);
}

// Находим конец main content (перед закрытием wrapper)
const mainEndIndex = indexHtml.indexOf('</main>', wrapperStartIndex);
if (mainEndIndex === -1) {
    console.error('Не найден </main> в index.html');
    process.exit(1);
}

// Извлекаем части шаблона
const headPart = indexHtml.substring(0, headEndIndex + 7); // включая </head>
const bodyStartPart = indexHtml.substring(headEndIndex + 7, wrapperStartIndex); // от </head> до wrapper
const wrapperStartPart = indexHtml.substring(wrapperStartIndex, mainStartIndex); // от wrapper до main
const wrapperEndPart = indexHtml.substring(mainEndIndex + 7, wrapperEndIndex + 23); // от </main> до <!-- end: #wrapper -->
const footerPart = indexHtml.substring(wrapperEndIndex + 23); // после wrapper

// Контент для каждой страницы (заглушки - нужно будет заменить на реальный контент)
const pageContents = {
    apk: `
        <!-- start: #page -->
        <main id="page" class="main grid-container">
            <!-- start: .center -->
            <div id="single" class="content">
                <div class="epcl-breadcrumbs"></div>
                <!-- start: .left-content -->
                <div class="left-content np-mobile">
                    <article class="post-644 page type-page status-publish hentry">
                        <section class="post-content">
                            <h1 class="title ularge textcenter">888starz تحميل للاندرويد APK عربي Egypt app</h1>
                            <div class="text">
                                <p>يقدم 888Starz تطبيقًا جوالًا ملائمًا للاعبين من الدول العربية بما في ذلك مصر والمملكة العربية السعودية والإمارات العربية المتحدة ومناطق أخرى. يتيح لك التطبيق وضع الرهانات على الرياضة واللعب في الكازينوهات عبر الإنترنت وشحن حسابك وسحب الأرباح مباشرة من جهازك المحمول.</p>
                                <p>مهم! يمكنك تنزيل تطبيق الهاتف المحمول من موقعنا على الإنترنت في أي وقت والحصول على مكافآت إضافية مقابل ذلك!</p>
                                <!-- Здесь будет полный контент страницы APK -->
                            </div>
                        </section>
                    </article>
                </div>
            </div>
        </main>
    `,
    'promo-code': `
        <!-- start: #page -->
        <main id="page" class="main grid-container">
            <!-- start: .center -->
            <div id="single" class="content">
                <div class="epcl-breadcrumbs"></div>
                <!-- start: .left-content -->
                <div class="left-content np-mobile">
                    <article class="post-644 page type-page status-publish hentry">
                        <section class="post-content">
                            <h1 class="title ularge textcenter">رمز ترويجي 888Starz في مصر</h1>
                            <div class="text">
                                <p>يقدم موقع 888Starz في العالم العربي مكافأة ترحيبية حصرية للاعبين الجدد والتي يمكن تفعيلها باستخدام رمز ترويجي. من خلال استخدام رمز خاص أثناء التسجيل، يمكن للمستخدمين زيادة مبلغ إيداعهم الأول والحصول على مكافآت إضافية في شكل رهانات مجانية أو دورات مجانية في الكازينو.</p>
                                <p>مهم! يمكنك دائمًا العثور على أكواد الخصم الحالية على موقعنا.</p>
                                <!-- Здесь будет полный контент страницы Promo Code -->
                            </div>
                        </section>
                    </article>
                </div>
            </div>
        </main>
    `,
    registration: `
        <!-- start: #page -->
        <main id="page" class="main grid-container">
            <!-- start: .center -->
            <div id="single" class="content">
                <div class="epcl-breadcrumbs"></div>
                <!-- start: .left-content -->
                <div class="left-content np-mobile">
                    <article class="post-644 page type-page status-publish hentry">
                        <section class="post-content">
                            <h1 class="title ularge textcenter">تسجيل 888Starz مع مكافأة في مصر</h1>
                            <div class="text">
                                <p>يقدم موقع 888Starz عملية تسجيل مريحة وسريعة، مما يسمح للمستخدمين الجدد من موريتانيا ومصر ودول عربية أخرى بإنشاء حساب والبدء في المراهنة في بضع دقائق فقط. ومع ذلك، قد يكون التحقق من الحساب مطلوبًا لسحب الأرباح.</p>
                                <p>مهم! أدخل رمز العرض الترويجي في نموذج التسجيل للحصول على المزيد من المكافآت!</p>
                                <!-- Здесь будет полный контент страницы Registration -->
                            </div>
                        </section>
                    </article>
                </div>
            </div>
        </main>
    `
};

// Функция для восстановления страницы
function restorePage(pageName, outputPath) {
    console.log(`Восстанавливаю страницу ${pageName}...`);
    
    // Читаем существующий файл для сохранения head части
    let existingHtml = '';
    if (fs.existsSync(outputPath)) {
        existingHtml = fs.readFileSync(outputPath, 'utf8');
    }
    
    // Если в существующем файле есть head, используем его, иначе используем из index.html
    let headToUse = headPart;
    const existingHeadEnd = existingHtml.indexOf('</head>');
    if (existingHeadEnd !== -1) {
        headToUse = existingHtml.substring(0, existingHeadEnd + 7);
    }
    
    // Собираем новую страницу
    const newPage = headToUse + 
                   bodyStartPart + 
                   wrapperStartPart + 
                   pageContents[pageName] + 
                   wrapperEndPart + 
                   footerPart;
    
    // Сохраняем
    fs.writeFileSync(outputPath, newPage, 'utf8');
    console.log(`Страница ${pageName} восстановлена: ${outputPath}`);
}

// Восстанавливаем все страницы
restorePage('apk', apkHtmlPath);
restorePage('promo-code', promoCodeHtmlPath);
restorePage('registration', registrationHtmlPath);

console.log('Все страницы восстановлены!');
