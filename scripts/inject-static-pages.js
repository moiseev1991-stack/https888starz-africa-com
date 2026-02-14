/**
 * After crawl: create /apk/, /registration/, /promo-code/ in dist from template.
 * Static deploy does not run WordPress — these pages must exist as HTML.
 */

const fs = require('fs');
const path = require('path');

const PROJECT_ROOT = path.resolve(__dirname, '..');
const OUT_DIR = process.env.STATIC_OUT_DIR || 'dist';
const DIST = path.join(PROJECT_ROOT, OUT_DIR);

const PAGES = {
  'apk': {
    title: 'تحميل التطبيق (APK) | 888Starz',
    h1: 'تحميل تطبيق 888Starz (APK)',
    content: `
<p>تطبيق 888Starz لأندرويد (APK) يتيح المراهنة ولعب الكازينو من هاتفك. حمّله فقط من الموقع الرسمي. أدناه المتطلبات وخطوات التثبيت ونصائح الأمان.</p>
<h2>ما هو ملف APK؟</h2>
<p>ملف APK هو حزمة تطبيق أندرويد. تثبيته يعطيك وصولاً سريعاً إلى 888Starz دون المتصفح.</p>
<h2>متطلبات النظام</h2>
<p>أندرويد 6.0 أو أحدث، مساحة تخزين حرة (حوالي 70 ميجابايت)، اتصال إنترنت مستقر.</p>
<h2>خطوات التثبيت</h2>
<ol><li>افتح هذا الموقع من جهاز أندرويد.</li><li>اذهب إلى قسم التطبيق واضغط رابط التحميل.</li><li>اسمح بالتثبيت من هذا المصدر في إعدادات الجهاز إن طُلِب.</li><li>افتح ملف APK المحمّل واتبع المثبّت.</li></ol>
<h2>الأمان</h2>
<p>حمّل APK فقط من الموقع الرسمي 888Starz. لا تستخدم مواقع طرف ثالث لتجنب ملفات معدّلة أو غير آمنة.</p>
<p><a href="/registration/">التسجيل</a> | <a href="/terms/">الشروط</a> | <a href="/contacts/">اتصل بنا</a></p>`
  },
  'registration': {
    title: 'التسجيل | 888Starz',
    h1: 'التسجيل في 888Starz',
    content: `
<p>إنشاء حساب في 888Starz سريع. يمكنك التسجيل بالبريد أو الهاتف أو بنقرة واحدة. أدناه الخطوات والمتطلبات.</p>
<h2>التسجيل خطوة بخطوة</h2>
<ol><li>افتح موقع 888Starz واضغط التسجيل.</li><li>اختر طريقة التسجيل (بريد، هاتف، أو نقرة واحدة).</li><li>أدخل البيانات المطلوبة وحدد كلمة المرور.</li><li>أكّد حسابك عبر الرابط أو الرمز المرسل.</li><li>سجّل الدخول وأكمل الملف إن لزم.</li></ol>
<h2>العمر والأهلية</h2>
<p>يجب أن يكون عمرك 18 عاماً على الأقل (أو السن القانوني في بلدك) للتسجيل. قد يكون التسجيل مقيّداً في بعض الدول.</p>
<p><a href="/terms/">الشروط</a> | <a href="/privacy-policy/">الخصوصية</a> | <a href="/apk/">تحميل التطبيق</a> | <a href="/contacts/">اتصل بنا</a></p>`
  },
  'promo-code': {
    title: 'الرمز الترويجي | 888Starz',
    h1: 'الرمز الترويجي',
    content: `
<p>الرمز الترويجي يعطيك مكافأة أو عرض عند التسجيل أو الإيداع. أدخله في الحقل المخصص على موقع 888Starz أو في التطبيق.</p>
<h2>ما هو الرمز الترويجي؟</h2>
<p>رموز ترويجية تصدرها 888Starz أو الشركاء. قد تفتح مكافآت ترحيب أو دورات مجانية أو عروضاً أخرى. لكل رمز شروطه.</p>
<h2>أين أدخل الرمز؟</h2>
<p>عند التسجيل أو الإيداع ابحث عن حقل "الرمز الترويجي" أو "رمز المكافأة" وأدخل الرمز كما هو (انتبه لحالة الأحرف).</p>
<h2>الرمز لا يعمل؟</h2>
<ul><li>تأكد أن الرمز ساري ولم ينته.</li><li>تحقق من الشروط (مثلاً أول إيداع، حد أدنى).</li><li>جرّب دون مسافات زائدة. إن استمرت المشكلة تواصل مع الدعم.</li></ul>
<p><a href="/accounts-withdrawals-and-bonuses/">الحسابات والمكافآت</a> | <a href="/terms/">الشروط</a> | <a href="/registration/">التسجيل</a> | <a href="/contacts/">اتصل بنا</a></p>`
  }
};

function main() {
  if (!fs.existsSync(DIST)) {
    console.error('dist/ not found. Run export first.');
    process.exit(1);
  }
  const templatePath = path.join(DIST, 'about', 'index.html');
  const indexPath = path.join(DIST, 'index.html');
  const srcPath = fs.existsSync(templatePath) ? templatePath : indexPath;
  if (!fs.existsSync(srcPath)) {
    console.error('No template found (about or index).');
    process.exit(1);
  }
  let baseHtml = fs.readFileSync(srcPath, 'utf8');
  for (const [slug, { title, h1, content }] of Object.entries(PAGES)) {
    let html = baseHtml
      .replace(/<title>[^<]*<\/title>/i, '<title>' + title + '</title>')
      .replace(/<link\s+rel=["']canonical["'][^>]*>/i, '<link rel="canonical" href="' + (process.env.STATIC_BASE_URL || 'https://888starzeg-egypt.com').replace(/\/$/, '') + '/' + slug + '/">');
    const mainMatch = html.match(/<main\s+id=["']page["'][^>]*>[\s\S]*?<\/main>/i);
    if (mainMatch) {
      const inner = '<div id="single" class="content no-sidebar no-thumb"><div class="epcl-page-wrapper content clearfix"><div class="left-content grid-100"><article class="main-article no-bg"><section class="post-content"><h1 class="title ularge textcenter bordered">' + h1 + '</h1><div class="text">' + content.trim() + '</div></section></article></div><div class="clear"></div></div></div>';
      html = html.replace(/<main\s+id=["']page["'][^>]*>[\s\S]*?<\/main>/i, '<main id="page" class="main grid-container">' + inner + '</main>');
    }
    const dir = path.join(DIST, slug);
    if (!fs.existsSync(dir)) fs.mkdirSync(dir, { recursive: true });
    fs.writeFileSync(path.join(dir, 'index.html'), html, 'utf8');
    console.log('Created:', slug + '/index.html');
  }
}

main();
