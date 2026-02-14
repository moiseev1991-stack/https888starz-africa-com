<?php
/*
 * Variable usages:
 *
 * EPCL_ABSPATH: template folder, includes files inside the theme
 * EPCL_THEMEPATH: includes relative file by http prottocol (url)
 * EPCL_THEMEPREFIX:  Used for metaboxes and theme options panel. Must be equal to text domain.
 * EPCL_FRAMEWORK_VAR: Used to storage information into wp database global variable, eg: $epcl_theme['carousel_category'].
 *
 */

if (!defined('EPCL_ABSPATH'))
     define('EPCL_ABSPATH', get_template_directory());
if (!defined('EPCL_THEMEPATH'))
     define('EPCL_THEMEPATH', get_template_directory_uri());
if (!defined('EPCL_THEMEPREFIX'))
     define('EPCL_THEMEPREFIX', 'epcl');
if (!defined('EPCL_FRAMEWORK_VAR'))
     define('EPCL_FRAMEWORK_VAR', 'epcl_theme');
if (!defined('EPCL_THEMENAME'))
     define('EPCL_THEMENAME', 'Zento');
if (!defined('EPCL_THEMESLUG'))
     define('EPCL_THEMESLUG', 'zento'); // Do not change
if (!defined('EPCL_APIKEY'))
     define('EPCL_APIKEY', 'A081B273A16DABAA7341'); // Do not change
if (!isset($content_width))
     $content_width = 726; // oembed width

/* Main class function for all Estudio Patagon themes, avoids plugins errors with a unique name  */

if (!class_exists('EPCL_Theme_Setup')) {

     class EPCL_Theme_Setup
     {

          public function __construct()
          {

               /* Theme Includes */

               add_action('after_setup_theme', array($this, 'includes'), 4);

               /* Main Theme Options */

               add_action('after_setup_theme', array($this, 'theme_support'));

          }

          public function includes()
          {

               /* Main Includes */

               require_once(get_theme_file_path('functions/post-formats.php'));
               require_once(EPCL_ABSPATH . '/functions/enqueue-scripts.php');
               require_once(EPCL_ABSPATH . '/functions/color-helper.php');
               require_once(EPCL_ABSPATH . '/functions/custom-styles.php');
               require_once(get_theme_file_path('functions/theme-functions.php')); // Specific functions for this particular theme
               require_once(EPCL_ABSPATH . '/functions/core.php'); // Common functions for all EP themes

               /* Plugins */

               require_once(EPCL_ABSPATH . '/functions/plugins/class-tgm-plugin-activation.php');
               require_once(EPCL_ABSPATH . '/functions/plugins/recommended-plugins.php');

               /* Theme Wizard */

               if (!is_customize_preview() && is_admin()) {
                    require_once(EPCL_ABSPATH . '/functions/merlin/vendor/autoload.php');
                    require_once(EPCL_ABSPATH . '/functions/merlin/class-merlin.php');
                    require_once(EPCL_ABSPATH . '/functions/merlin/merlin-config.php');
                    require_once(EPCL_ABSPATH . '/functions/merlin/merlin-import-demo.php');
               }

          }

          public function theme_support()
          {

               /* Languages */

               load_theme_textdomain('zento', EPCL_ABSPATH . '/languages');

               /* Thumbs */

               if (function_exists('add_theme_support')) {
                    add_theme_support('post-formats', array('video', 'gallery', 'audio'));
                    add_theme_support('post-thumbnails');
                    add_theme_support('automatic-feed-links');
                    add_theme_support('html5', array('style', 'script'));
                    add_theme_support('title-tag');
                    add_theme_support('editor-styles'); // Gutenberg Support      
                    add_theme_support('align-wide');
                    add_theme_support('responsive-embeds');
                    add_theme_support('amp', array(
                         'paired' => true,
                         'template_dir' => 'amp'
                    ));
                    add_theme_support('woocommerce');

                    if (epcl_get_option('enable_gutenberg_admin', true)) {
                         add_editor_style(epcl_gutenberg_fonts_url()); // Enqueue fonts in the gutenberg editor               
                         add_editor_style('assets/dist/gutenberg.min.css'); // Enqueue custom styles in the Gutenberg editor
                    }

                    $prefix = EPCL_THEMEPREFIX . '_';

                    add_image_size($prefix . 'classic', 660, 660, true); // Classic Style Post
                    add_image_size($prefix . 'fullcover', 1700, 700, true); // Fullcover or without sidebar post, also for Slider module

               }

               /* Menus */

               register_nav_menus(array(
                    'epcl_header' => esc_html__('Header', 'zento')
               ));

               /* Register Sidebars */

               require_once(get_theme_file_path('functions/sidebars.php'));

          }

     }

     new EPCL_Theme_Setup;
}
add_filter('upload_mimes', 'svg_upload_allow');

# Добавляет SVG в список разрешенных для загрузки файлов.
function svg_upload_allow($mimes)
{
     $mimes['svg'] = 'image/svg+xml';

     return $mimes;
}


add_shortcode('banner_content', function () {
     return '<div class="game-section">
		  <div class="game-card">
			<img src="/wp-content/uploads/2025/01/casino.webp" alt="Казино">
			<div class="card-content">
			  <div class="caption_h3">' . get_translation('Casino') . '</div>
			  <p>' . get_translation('The best board games') . '</p>
			  <a href="/game/" rel="_nofollow">' . get_translation('PLAY') . '</a>
			</div>
		  </div>
		  <div class="game-card">
			<img src="/wp-content/uploads/2025/01/live.webp" alt="Live">
			<div class="card-content">
			  <div class="caption_h3">Live</div>
			  <p>' . get_translation('Play with dealers') . '</p>
			  <a href="/game/" rel="_nofollow">' . get_translation('PLAY') . '</a>
			</div>
		  </div>
		  <div class="game-card">
			<img src="/wp-content/uploads/2025/01/slots.webp" alt="Слоты">
			<div class="card-content">
			  <div class="caption_h3">Slots</div>
			  <p>' . get_translation('Good luck awaits you!') . '</p>
			  <a href="/game/" rel="_nofollow">' . get_translation('ROTATE') . '</a>
			</div>
		  </div>
		  <div class="game-card">
			<img src="/wp-content/uploads/2025/01/888gamesS.webp" alt="888Games">
			<div class="card-content">
			  <div class="caption_h3">888Games</div>
			  <p>' . get_translation('Exclusive games') . '</p>
			  <a href="/game/" rel="_nofollow">' . get_translation('LAUNCH') . '</a>
			</div>
		  </div>
		</div>';
});


/*EN*/
function text_shorten()
{
     return '
    <section class="prilka">
    <div class="grid-prilka" dir="ltr">
    <div class="prilkalogo">
    <img decoding="async" alt="888Starz" src="/wp-content/uploads/2025/01/favicon.png">
    <div class="prilkatext">
    <div class="prilkatext1">888Starz</div>
    <div class="prilkatext2"><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star stargray" aria-hidden="true"></i> 5.0</div>
    <div class="prilkatext3"><span class="prilkarazrab">' . get_translation('Developer') . ' 888Starz</span></div>
    </div>
    </div>
    <div class="prilkaicon">
    <div class="prilkaicon1">
    <div class="prilkazagolovok">' . get_translation('Size') . '</div>
    <div class="prilkazagolovok2">67.50 MB</div>
    </div>
    <div class="prilkaicon2">
    <div class="prilkazagolovok">' . get_translation('Downloads') . '</div>
    <div class="prilkazagolovok2">26873</div>
    </div>
    <div class="prilkaicon3">
    <div class="prilkazagolovok">' . get_translation('Version') . '</div>
    <div class="prilkazagolovok2">2.03.23</div>
    </div>
    </div>
    <div class="prilkaicon">
    <div class="prilkaicon1">
    <div class="prilkazagolovok">' . get_translation('Language') . '</div>
    <div class="prilkazagolovok2">' . get_translation('English') . '</div>
    </div>
    <div class="prilkaicon2">
    <div class="prilkazagolovok">Android</div>
    <div class="prilkazagolovok2">6.0+</div>
    </div>
    <div class="prilkaicon3">
    <div class="prilkazagolovok">' . get_translation('Coupon') . '</div>
    <div class="prilkazagolovok2">1500€</div>
    </div>
    </div>
    <noindex><a target="_blank" rel="nofollow noopener" href="/game/" class="button3 filled-green3"><br>
    <span class="text-overflow1">' . get_translation('Download the app <br> <small>we guarantee security</small>') . '</span><br></a></noindex>
    </div>
    </section>';
}
add_shortcode('skachaten', 'text_shorten');

/*EN*/
function text_promokoden()
{
     return '<section ' . get_translation('class=" section-wrapper2 grid-wrapper2 d-grid2 padding-horizontal2 js-grid-wrapper2"') . '>
            <div class="grid-item2">
                <div class="item1 d-flex2 fd-column2">
                    <div class="content2 d-flex2 fd-column2 align-top2">
                        <div class="ribbon-2">' . get_translation('Promo') . '</div>
                        <div class="card-head1 d-flex2 ai-center1 align-top2">
                            <div class="timer-img">
                                <noindex>
                                    <a target="_blank" rel="nofollow noopener" href="/game/" class="ui-link">
                                        <img decoding="async" alt="888Starz" src="/wp-content/uploads/2025/01/favicon.png"
                                            class="loaded2">
                                    </a>
                                </noindex>
                                <div class="timer" dir="ltr">
                                    <div class="timer__items">
                                        <div class="timer__item timer__hours">00</div>
                                        <div class="timer__item timer__minutes">00</div>
                                        <div class="timer__item timer__seconds">00</div>
                                    </div>
                                </div>
                            </div>
                            <p class="bonustitle">' . get_translation('Get this <span style="border-bottom: 2px solid #f37021; padding-bottom: 3px;">before 1500€</span> by promo code!') . '</p>
                            <div class="promo" id="promoCode" onclick="copyPromoCode()">' . get_translation('888EN') . '
                                <div class="copy-icon">
                                    <i class="fa fa-clone" aria-hidden="true"></i>
                                    <div class="copy-notification" id="copyNotification">' . get_translation('Copied') . '</div>
                                </div>
                            </div>
                            <noindex><a target="_blank" rel="nofollow noopener" href="/game/"
                                    class="button2 filled-green2 radius-41 ui-link"><br>
                                    <span class="text-overflow1">' . get_translation('Go to 888Starz') . '</span><br> </a></noindex>
                            <div class="promotext">' . get_translation('You can get the bonus only when you go from our website') . '</div>
                        </div>
                    </div>
                </div>
        </section>';
}
add_shortcode('promokoden', 'text_promokoden');

function text_three()
{
     return '<section class="section-wrapper1 grid-wrapper1 d-grid1 grid-gap-201 padding-horizontal1 js-grid-wrapper1">
     <div class="grid-item1">
      <div class="item1 d-flex1 fd-column1">
       <div class="content1 d-flex1 fd-column1 align-top1">
            <div class="card-head1 d-flex1 ai-center1 align-top1">
                  <img alt="" src="/wp-content/uploads/2025/01/favicon.png" class="icon-thee-short">
    <div class="title3">' . get_translation('Stocks') . '</div>
             </div>
               <a href="/game/" target="_blank" rel="noopener">
    <div class="title2">100€<i class="fa fa-chevron-right"></i></div>
               <div class="title1">' . get_translation('Welcome bonus on 1st deposit') . '</div>
               </a>
            <div class="category1 d-flex1 ai-center1">
                <div class="category-align1 mg-right-81">
                    <a href="/game/">' . get_translation('SPORT') . '</a>          
                </div>
             </div>
            <a href="/game/" target="_blank" class=" button1 filled-green1 radius-41" rel="noopener">
                <span class="text-overflow1">' . get_translation('Take away') . '</span>
            </a>
        </div>
       <a href="/game/"></a>
      </div>
     </div>
    <div class="grid-item1">
      <div class="item1 d-flex1 fd-column1">
       <div class="content1 d-flex1 fd-column1 align-top1">
            <div class="card-head1 d-flex1 ai-center1 align-top1">
                  <img alt="888Starz" src="/wp-content/uploads/2025/01/favicon.png" class="icon-thee-short">
    <div class="title3">' . get_translation('Stocks') . '</div>
             </div>
             <div class="dotted-line1"></div>
               <a href="/game/" target="_blank" rel="noopener">
    <div class="title2">1500€ + 150 FS <i class="fa fa-chevron-right"></i></div>
               <div class="title1">' . get_translation('Welcome package') . '</div>
               </a>
            <div class="category1 d-flex1 ai-center1">
                <div class="category-align1 mg-right-81">
                    <a href="/game/">' . get_translation('CASINO') . '</a>          
                </div>
             </div>
            <a href="/game/" target="_blank" class=" button1 filled-green1 radius-41" rel="noopener">
                <span class="text-overflow1">' . get_translation('Take away') . '</span>
            </a>
        </div>
       <a href="/game/"></a>
      </div>
     </div>
    <div class="grid-item1">
      <div class="item1 d-flex1 fd-column1">
       <div class="content1 d-flex1 fd-column1 align-top1">
            <div class="card-head1 d-flex1 ai-center1 align-top1">
                  <img alt="" src="/wp-content/uploads/2025/01/favicon.png" class="icon-thee-short">
    <div class="title3">' . get_translation('Stocks') . '</div>
             </div>
             <div class="dotted-line1"></div>
               <a href="/game/" target="_blank" rel="noopener">
    <div class="title2">20% of the amount <i class="fa fa-chevron-right"></i></div>
               <div class="title1">' . get_translation('Welcome freebet') . '</div>
               </a>
            <div class="category1 d-flex1 ai-center1">
                <div class="category-align1 mg-right-81">
                    <a href="/game/">' . get_translation('FREEBET') . '</a>          
                </div>
             </div>
            <a href="/game/" target="_blank" class=" button1 filled-green1 radius-41" rel="noopener">
                <span class="text-overflow1">' . get_translation('Take away') . '</span>
            </a>
        </div>
       <a href="/game/"></a>
      </div>
     </div>
    </section>';
}
add_shortcode('promothreelight', 'text_three');

function text_main()
{
     return '
	<section class="cover-main">
    <img src="/wp-content/uploads/2025/01/astronaut.png" alt="888Starz" class="logo-big"/>
    <div class="container-cover-main">
          <h2 class="caption-h2-cover">
                ' . get_translation('GET 100% BONUS UP TO €122') . '
          </h2>
          <h3 class="caption-h3-cover">
                ' . get_translation('888Starz - one of the largest bookmakers operating internationally.') . ' 
          </h3>
		  <p>
          <a href="/game/" class="link-ref-cover">' . get_translation('Get a bonus') . '</a></p>
          <p class="small-text">18+ T&C apply | begambleaware.org | Play Responsibly</p>       
    </div>   
	</section>';
}
add_shortcode('bonusmain', 'text_main');



function toplay_func()
{
     return '<div class="container-banner-main-22">
        <div class="banner">
            <div class="banner__logo"><img src="/wp-content/uploads/2025/01/c2d1291cb995ce2eb60df24214bfcfdd.svg" alt="888Starz" width="191px"/></div>
            <div class=banner__caption-h1>' . get_translation('Win with us!') . '</div>
            <p>' . get_translation('Bet on the best events right now.') . '</p>
            <a href="/game/" class="cta-button">' . get_translation('Play now') . '</a>
        </div>
</div>';
}
add_shortcode('toplay', 'toplay_func');

// Установите текущий язык (например, 'en', 'ru', 'es' и т.д.)
$current_language = get_current_language_from_url();
function get_current_language_from_url()
{
     // Список поддерживаемых языков
     $supported_languages = ['en', 'ru', 'es', 'fr', 'pl', 'pt', 'jv', 'ar']; // добавлены все нужные языки

     // Извлекаем сегмент пути, который указывает на язык
     $url_path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
     $url_segments = explode('/', $url_path);

     // Проверяем первый сегмент URL (язык)
     $current_language = isset($url_segments[0]) && in_array($url_segments[0], $supported_languages) ? $url_segments[0] : 'ar'; // язык по умолчанию 'en'

     return $current_language;
}
// Функция для получения перевода текста
function get_translation($text)
{
     global $current_language;

     // Ассоциативный массив переводов
     $translations = [
          'All Bonuses' => [
               'en' => 'All Bonuses',
               'ru' => 'Все бонусы',
               'es' => 'Todos los bonos',
               'fr' => 'Tous les bonus',
               'pl' => 'Wszystkie bonusy',
               'pt' => 'Todos os bônus',
               'jv' => 'Kabeh Bonus',
               'ar' => 'جميع المكافآت'
          ],
          'Find out more' => [
               'en' => 'Find out more',
               'ru' => 'Узнать больше',
               'es' => 'Descubre más',
               'fr' => 'En savoir plus',
               'pl' => 'Dowiedz się więcej',
               'pt' => 'Saiba mais',
               'jv' => 'Temokake liyane',
               'ar' => 'اكتشف المزيد'
          ],
          '+100% bonus on your first deposit' => [
               'en' => '+100% bonus on your first deposit',
               'ru' => '+100% бонус на первый депозит',
               'es' => '+100% de bonificación en tu primer depósito',
               'fr' => '+100% de bonus sur votre premier dépôt',
               'pl' => '+100% bonusu na pierwszą wpłatę',
               'pt' => '+100% de bônus no seu primeiro depósito',
               'jv' => '+100% bonus ing simpenan pisanan',
               'ar' => '+100% مكافأة على أول إيداع لك'
          ],
          'Welcome package up to 1500 EUR + 150 FS' => [
               'en' => 'Welcome package up to 1500 EUR + 150 FS',
               'ru' => 'Приветственный пакет до 1500 EUR + 150 FS',
               'es' => 'Paquete de bienvenida hasta 1500 EUR + 150 FS',
               'fr' => 'Pack de bienvenue jusqu’à 1500 EUR + 150 FS',
               'pl' => 'Pakiet powitalny do 1500 EUR + 150 FS',
               'pt' => 'Pacote de boas-vindas até 1500 EUR + 150 FS',
               'jv' => 'Paket sugeng tekan 1500 EUR + 150 FS',
               'ar' => 'باقة ترحيبية تصل إلى 1500 يورو + 150 FS'
          ],
          '50% SECOND DEPOSIT BONUS' => [
               'en' => '50% SECOND DEPOSIT BONUS',
               'ru' => '50% БОНУС НА ВТОРОЙ ДЕПОЗИТ',
               'es' => 'BONO DEL 50% EN EL SEGUNDO DEPÓSITO',
               'fr' => '50% DE BONUS SUR LE DEUXIÈME DÉPÔT',
               'pl' => '50% BONUSU NA DRUGI DEPOZYT',
               'pt' => 'BÔNUS DE 50% NO SEGUNDO DEPÓSITO',
               'jv' => '50% BONUS SETORAN KEDUA',
               'ar' => '50% مكافأة الإيداع الثاني'
          ],
          'WINNERS LOTTERY' => [
               'en' => 'WINNERS LOTTERY',
               'ru' => 'ЛОТЕРЕЯ ПОБЕДИТЕЛЕЙ',
               'es' => 'LOTERÍA DE GANADORES',
               'fr' => 'LOTERIE DES GAGNANTS',
               'pl' => 'LOTERIA ZWYCIĘZCÓW',
               'pt' => 'LOTERIA DOS VENCEDORES',
               'jv' => 'LOTRE PARA PEMENANG',
               'ar' => 'يانصيب الفائزين'
          ],
          'TOP FOOTBALL LEAGUES' => [
               'en' => 'TOP FOOTBALL LEAGUES',
               'ru' => 'ТОП ФУТБОЛЬНЫЕ ЛИГИ',
               'es' => 'PRINCIPALES LIGAS DE FÚTBOL',
               'fr' => 'TOP DES LIGUES DE FOOTBALL',
               'pl' => 'TOP LIGI PIŁKARSKIE',
               'pt' => 'MELHORES LIGAS DE FUTEBOL',
               'jv' => 'LIGA SEPABOLA TOP',
               'ar' => 'أفضل دوريات كرة القدم'
          ],
          'Ice Win' => [
               'en' => 'Ice Win',
               'ru' => 'Ледовая Победа',
               'es' => 'Victoria de Hielo',
               'fr' => 'Victoire Glacée',
               'pl' => 'Lodowa Wygrana',
               'pt' => 'Vitória Gelada',
               'jv' => 'Kemenangan Es',
               'ar' => 'فوز الجليد'
          ],
          'Casino VIP Cashback' => [
               'en' => 'Casino VIP Cashback',
               'ru' => 'Кэшбэк для VIP казино',
               'es' => 'Reembolso VIP de Casino',
               'fr' => 'Cashback VIP Casino',
               'pl' => 'Kasyno VIP Cashback',
               'pt' => 'Cashback VIP de Cassino',
               'jv' => 'Cashback VIP Kasino',
               'ar' => 'استرداد نقدي VIP كازينو'
          ],
          '3% cashback every Tuesday' => [
               'en' => '3% cashback every Tuesday',
               'ru' => '3% кэшбэк каждую среду',
               'es' => '3% de reembolso cada martes',
               'fr' => '3% de cashback chaque mardi',
               'pl' => '3% cashback każdego wtorku',
               'pt' => '3% de cashback toda terça-feira',
               'jv' => '3% cashback saben Selasa',
               'ar' => 'استرداد نقدي بنسبة 3% كل يوم ثلاثاء'
          ],
          'Great prizes up for grabs every day!' => [
               'en' => 'Great prizes up for grabs every day!',
               'ru' => 'Отличные призы каждый день!',
               'es' => '¡Grandes premios para ganar cada día!',
               'fr' => 'De superbes prix à gagner chaque jour!',
               'pl' => 'Wielkie nagrody do zdobycia każdego dnia!',
               'pt' => 'Ótimos prêmios para ganhar todos os dias!',
               'jv' => 'Hadiah hebat bisa didapatkan setiap hari!',
               'ar' => 'جوائز رائعة في انتظارك كل يوم!'
          ],
          '50% BONUS FOR SPORTS BETS EVERY TUESDAY' => [
               'en' => '50% BONUS FOR SPORTS BETS EVERY TUESDAY',
               'ru' => '50% БОНУС НА СТАВКИ НА СПОРТ КАЖДУЮ СРЕДУ',
               'es' => 'BONO DEL 50% PARA APUESTAS DEPORTIVAS CADA MARTES',
               'fr' => 'BONUS DE 50% POUR LES PARIS SPORTIFS CHAQUE MARDI',
               'pl' => '50% BONUS NA STAWKI SPORTOWE KAŻDEGO WTORKU',
               'pt' => 'BÔNUS DE 50% EM APOSTAS ESPORTIVAS TODA TERÇA-FEIRA',
               'jv' => '50% BONUS PERTARUHAN OLAHRAGA SETIAP SELASA',
               'ar' => '50% مكافأة على الرهانات الرياضية كل يوم ثلاثاء'
          ],
          '100% SPORTS BETTING BONUS EVERY THURSDAY' => [
               'en' => '100% SPORTS BETTING BONUS EVERY THURSDAY',
               'ru' => '100% БОНУС НА СТАВКИ НА СПОРТ КАЖДЫЙ ЧЕТВЕРГ',
               'es' => 'BONO DEL 100% PARA APUESTAS DEPORTIVAS CADA JUEVES',
               'fr' => 'BONUS DE 100% POUR LES PARIS SPORTIFS CHAQUE JEUDI',
               'pl' => '100% BONUS NA STAWKI SPORTOWE KAŻDEGO CZWARTKU',
               'pt' => 'BÔNUS DE 100% EM APOSTAS ESPORTIVAS TODA QUINTA-FEIRA',
               'jv' => '100% BONUS PERTARUHAN OLAHRAGA SETIAP KAMIS',
               'ar' => '100% مكافأة على الرهانات الرياضية كل يوم خميس'
          ],
          'ACCUMULATOR INSURANCE' => [
               'en' => 'ACCUMULATOR INSURANCE',
               'ru' => 'СТРАХОВКА АККУМУЛЯТОРА',
               'es' => 'SEGURO DE ACUMULADOR',
               'fr' => 'ASSURANCE ACCUMULATEUR',
               'pl' => 'UBEZPIECZENIE AKUMULATORA',
               'pt' => 'SEGURO DE ACUMULADOR',
               'jv' => 'ASURANSI AKUMULATOR',
               'ar' => 'تأمين المجمع'
          ],
          'ACCUMULATOR OF THE DAY' => [
               'en' => 'ACCUMULATOR OF THE DAY',
               'ru' => 'АККУМУЛЯТОР ДНЯ',
               'es' => 'ACUMULADOR DEL DÍA',
               'fr' => 'ACCUMULATEUR DU JOUR',
               'pl' => 'AKUMULATOR DNIA',
               'pt' => 'ACUMULADOR DO DIA',
               'jv' => 'AKUMULATOR HARIAN',
               'ar' => 'مجمع اليوم'
          ],
          '888GAMES DAY' => [
               'en' => '888GAMES DAY',
               'ru' => 'ДЕНЬ 888GAMES',
               'es' => 'DÍA 888GAMES',
               'fr' => 'JOUR 888GAMES',
               'pl' => 'DZIEŃ 888GAMES',
               'pt' => 'DIA 888GAMES',
               'jv' => 'HARI 888GAMES',
               'ar' => 'يوم 888GAMES'
          ],
          'Lucky ticket' => [
               'en' => 'Lucky ticket',
               'ru' => 'Счастливый билет',
               'es' => 'Boleto de la suerte',
               'fr' => 'Billet chanceux',
               'pl' => 'Szczęśliwy bilet',
               'pt' => 'Bilhete da sorte',
               'jv' => 'Tiket Lucky',
               'ar' => 'تذكرة الحظ'
          ],
          'Play now' => [
               'en' => 'Play now',
               'ru' => 'Играть сейчас',
               'es' => 'Jugar ahora',
               'fr' => 'Jouer maintenant',
               'pl' => 'Graj teraz',
               'pt' => 'Jogar agora',
               'jv' => 'Main saiki',
               'ar' => 'العب الآن'
          ],
          'Bet on the best events right now.' => [
               'en' => 'Bet on the best events right now.',
               'ru' => 'Ставьте на лучшие события прямо сейчас.',
               'es' => 'Apuesta en los mejores eventos ahora mismo.',
               'fr' => 'Pariez sur les meilleurs événements dès maintenant.',
               'pl' => 'Zagraj na najlepsze wydarzenia teraz.',
               'pt' => 'Aposte nos melhores eventos agora mesmo.',
               'jv' => 'Pasang taruhan ing acara paling apik saiki.',
               'ar' => 'راهن على أفضل الأحداث الآن.'
          ],
          'Win with us!' => [
               'en' => 'Win with us!',
               'ru' => 'Выигрывайте с нами!',
               'es' => '¡Gana con nosotros!',
               'fr' => 'Gagnez avec nous !',
               'pl' => 'Wygraj z nami!',
               'pt' => 'Ganhe conosco!',
               'jv' => 'Menang karo kita!',
               'ar' => 'افز معانا!'
          ],
          'Get a bonus' => [
               'en' => 'Get a bonus',
               'ru' => 'Получить бонус',
               'es' => 'Obtén un bono',
               'fr' => 'Obtenez un bonus',
               'pl' => 'Odbierz bonus',
               'pt' => 'Receba um bônus',
               'jv' => 'Entuk bonus',
               'ar' => 'احصل على مكافأة'
          ],
          'GET 100% BONUS UP TO €122' => [
               'en' => 'GET 100% BONUS UP TO €122',
               'ru' => 'ПОЛУЧИТЕ 100% БОНУС ДО 122 €',
               'es' => 'OBTÉN UN BONO DEL 100% HASTA 122 €',
               'fr' => 'OBTENEZ UN BONUS DE 100% JUSQU’À 122 €',
               'pl' => 'ZDOBĄDŹ 100% BONUS DO 122 €',
               'pt' => 'OBTENHA 100% DE BÔNUS ATÉ 122 €',
               'jv' => 'DAPATKAN BONUS 100% HINGGA €122',
               'ar' => 'احصل على 100% مكافأة حتى 122 €'
          ],
          '888Starz - one of the largest bookmakers operating internationally.' => [
               'en' => '888Starz - one of the largest bookmakers operating internationally.',
               'ru' => '888Starz — один из крупнейших букмекеров, работающих на международном уровне.',
               'es' => '888Starz - uno de los mayores casas de apuestas que operan internacionalmente.',
               'fr' => '888Starz - l’un des plus grands bookmakers opérant à l’international.',
               'pl' => '888Starz - jeden z największych bukmacherów działających międzynarodowo.',
               'pt' => '888Starz - um dos maiores sites de apostas que operam internacionalmente.',
               'jv' => '888Starz - salah siji saka bookmaker paling gedhé sing beroperasi sacara internasional.',
               'ar' => '888Starz - واحدة من أكبر شركات المراهنات التي تعمل دوليًا.'
          ],
          'Take away' => [
               'en' => 'Take away',
               'ru' => 'Забрать',
               'es' => 'Para llevar',
               'fr' => 'À emporter',
               'pl' => 'Na wynos',
               'pt' => 'Levar',
               'jv' => 'Bawa pulang',
               'ar' => 'خذها'
          ],
          'FREEBET' => [
               'en' => 'FREEBET',
               'ru' => 'БЕСПЛАТНАЯ СТАВКА',
               'es' => 'APUESTA GRATIS',
               'fr' => 'PARI GRATUIT',
               'pl' => 'BEZPŁATNY ZAKŁAD',
               'pt' => 'APOSTA GRÁTIS',
               'jv' => 'BET GRATIS',
               'ar' => 'رهان مجاني'
          ],
          'Welcome freebet' => [
               'en' => 'Welcome freebet',
               'ru' => 'Приветственная бесплатная ставка',
               'es' => 'Apuesta gratis de bienvenida',
               'fr' => 'Paris gratuit de bienvenue',
               'pl' => 'Powitalny zakład gratis',
               'pt' => 'Aposta grátis de boas-vindas',
               'jv' => 'Bet gratis sambutan',
               'ar' => 'رهان مجاني ترحيبي'
          ],
          'Stocks' => [
               'en' => 'Stocks',
               'ru' => 'Акции',
               'es' => 'Acciones',
               'fr' => 'Stocks',
               'pl' => 'Akcje',
               'pt' => 'Ações',
               'jv' => 'Saham',
               'ar' => 'الأسهم'
          ],
          'CASINO' => [
               'en' => 'CASINO',
               'ru' => 'КАЗИНО',
               'es' => 'CASINO',
               'fr' => 'CASINO',
               'pl' => 'KASYN0',
               'pt' => 'CASINO',
               'jv' => 'KASINO',
               'ar' => 'كازينو'
          ],
          'Welcome package' => [
               'en' => 'Welcome package',
               'ru' => 'Приветственный пакет',
               'es' => 'Paquete de bienvenida',
               'fr' => 'Pack de bienvenue',
               'pl' => 'Pakiet powitalny',
               'pt' => 'Pacote de boas-vindas',
               'jv' => 'Paket sambutan',
               'ar' => 'باقة الترحيب'
          ],
          'SPORT' => [
               'en' => 'SPORT',
               'ru' => 'СПОРТ',
               'es' => 'DEPORTE',
               'fr' => 'SPORT',
               'pl' => 'SPORT',
               'pt' => 'ESPORTES',
               'jv' => 'OLAHRAGA',
               'ar' => 'رياضة'
          ],
          'Welcome bonus on 1st deposit' => [
               'en' => 'Welcome bonus on 1st deposit',
               'ru' => 'Приветственный бонус на 1 депозит',
               'es' => 'Bono de bienvenida en el primer depósito',
               'fr' => 'Bonus de bienvenue sur le 1er dépôt',
               'pl' => 'Bonus powitalny na pierwszy depozyt',
               'pt' => 'Bônus de boas-vindas no primeiro depósito',
               'jv' => 'Bonus sambutan ing setoran pertama',
               'ar' => 'مكافأة ترحيبية على الإيداع الأول'
          ],
          'You can get the bonus only when you go from our website' => [
               'en' => 'You can get the bonus only when you go from our website',
               'ru' => 'Вы можете получить бонус только при переходе с нашего сайта',
               'es' => 'Puedes obtener el bono solo cuando vayas desde nuestro sitio web',
               'fr' => 'Vous ne pouvez obtenir le bonus que lorsque vous venez de notre site web',
               'pl' => 'Możesz otrzymać bonus tylko, gdy przejdziesz z naszej strony internetowej',
               'pt' => 'Você só pode obter o bônus quando acessar nosso site',
               'jv' => 'Sampeyan bisa entuk bonus mung nalika sampeyan mlebu saka situs web kita',
               'ar' => 'يمكنك الحصول على المكافأة فقط عند الانتقال من موقعنا'
          ],
          'Go to 888Starz' => [
               'en' => 'Go to 888Starz',
               'ru' => 'Перейти на 888Starz',
               'es' => 'Ir a 888Starz',
               'fr' => 'Aller sur 888Starz',
               'pl' => 'Przejdź do 888Starz',
               'pt' => 'Ir para 888Starz',
               'jv' => 'Pindhah menyang 888Starz',
               'ar' => 'اذهب إلى 888Starz'
          ],
          '888EN' => [
               'en' => 'AR888BONUS',
               'ru' => 'AR888BONUS',
               'es' => 'AR888BONUS',
               'fr' => 'AR888BONUS',
               'pl' => 'AR888BONUS',
               'pt' => 'AR888BONUS',
               'jv' => 'AR888BONUS',
               'ar' => 'AR888BONUS'
          ],
          'Copied' => [
               'en' => 'Copied',
               'ru' => 'Скопировано',
               'es' => 'Copiado',
               'fr' => 'Copié',
               'pl' => 'Skopiowane',
               'pt' => 'Copiado',
               'jv' => 'Disalin',
               'ar' => 'تم النسخ'
          ],
          'Get this <span style="border-bottom: 2px solid #f37021; padding-bottom: 3px;">before 1500€</span> by promo code!' => [
               'en' => 'Get this <span style="border-bottom: 2px solid #f37021; padding-bottom: 3px;">before 1500€</span> by promo code!',
               'ru' => 'Получите это <span style="border-bottom: 2px solid #f37021; padding-bottom: 3px;">до 1500€</span> с промокодом!',
               'es' => '¡Obtén esto <span style="border-bottom: 2px solid #f37021; padding-bottom: 3px;">antes de 1500€</span> con el código promocional!',
               'fr' => 'Obtenez ceci <span style="border-bottom: 2px solid #f37021; padding-bottom: 3px;">avant 1500€</span> avec le code promo!',
               'pl' => 'Zdobądź to <span style="border-bottom: 2px solid #f37021; padding-bottom: 3px;">przed 1500€</span> za pomocą kodu promocyjnego!',
               'pt' => 'Obtenha isso <span style="border-bottom: 2px solid #f37021; padding-bottom: 3px;">antes de 1500€</span> com o código promocional!',
               'jv' => 'Entuk iki <span style="border-bottom: 2px solid #f37021; padding-bottom: 3px;">sadurunge 1500€</span> kanthi kode promosi!',
               'ar' => 'احصل على هذا <span style="border-bottom: 2px solid #f37021; padding-bottom: 3px;">قبل 1500€</span> باستخدام الرمز الترويجي!'
          ],
          'Promo' => [
               'en' => 'Promo',
               'ru' => 'Промо',
               'es' => 'Promoción',
               'fr' => 'Promo',
               'pl' => 'Promocja',
               'pt' => 'Promo',
               'jv' => 'Promo',
               'ar' => 'عروض'
          ],
          'Download the app <br> <small>we guarantee security</small>' => [
               'en' => 'Download the app <br> <small>we guarantee security</small>',
               'ru' => 'Скачайте приложение <br> <small>мы гарантируем безопасность</small>',
               'es' => 'Descarga la aplicación <br> <small>garantizamos seguridad</small>',
               'fr' => 'Téléchargez l\'application <br> <small>nous garantissons la sécurité</small>',
               'pl' => 'Pobierz aplikację <br> <small>gwarantujemy bezpieczeństwo</small>',
               'pt' => 'Baixe o aplicativo <br> <small>garantimos segurança</small>',
               'jv' => 'Unduh aplikasi <br> <small>kami menjamin keamanan</small>',
               'ar' => 'قم بتنزيل التطبيق <br> <small>نحن نضمن الأمان</small>'
          ],
          'Coupon' => [
               'en' => 'Coupon',
               'ru' => 'Купон',
               'es' => 'Cupón',
               'fr' => 'Coupon',
               'pl' => 'Kupon',
               'pt' => 'Cupom',
               'jv' => 'Kupon',
               'ar' => 'قسيمة'
          ],
          'English' => [
               'ru' => 'Русский',
               'en' => 'English',
               'es' => 'Español',
               'fr' => 'Français',
               'pl' => 'Polski',
               'pt' => 'Português',
               'jv' => 'Basa Jawa',
               'ar' => 'العربية'
          ],
          'Language' => [
               'en' => 'Language',
               'ru' => 'Язык',
               'es' => 'Idioma',
               'fr' => 'Langue',
               'pl' => 'Język',
               'pt' => 'Idioma',
               'jv' => 'Basa',
               'ar' => 'اللغة'
          ],
          'Version' => [
               'en' => 'Version',
               'ru' => 'Версия',
               'es' => 'Versión',
               'fr' => 'Version',
               'pl' => 'Wersja',
               'pt' => 'Versão',
               'jv' => 'Versi',
               'ar' => 'الإصدار'
          ],
          'Downloads' => [
               'en' => 'Downloads',
               'ru' => 'Загрузки',
               'es' => 'Descargas',
               'fr' => 'Téléchargements',
               'pl' => 'Pobierania',
               'pt' => 'Downloads',
               'jv' => 'Unduhan',
               'ar' => 'التنزيلات'
          ],
          'Size' => [
               'en' => 'Size',
               'ru' => 'Размер',
               'es' => 'Tamaño',
               'fr' => 'Taille',
               'pl' => 'Rozmiar',
               'pt' => 'Tamanho',
               'jv' => 'Ukuran',
               'ar' => 'الحجم'
          ],
          'Developer' => [
               'en' => 'Developer',
               'ru' => 'Разработчик',
               'es' => 'Desarrollador',
               'fr' => 'Développeur',
               'pl' => 'Deweloper',
               'pt' => 'Desenvolvedor',
               'jv' => 'Pangembang',
               'ar' => 'مطور'
          ],
          'LAUNCH' => [
               'en' => 'LAUNCH',
               'ru' => 'ИГРАТЬ',
               'es' => 'LANZAMIENTO',
               'fr' => 'LANCER',
               'pl' => 'URUCHOMIENIE',
               'pt' => 'LANÇAMENTO',
               'jv' => 'PELUNCURAN',
               'ar' => 'إطلاق'
          ],
          'Exclusive games' => [
               'en' => 'Exclusive games',
               'ru' => 'Эксклюзивные игры',
               'es' => 'Juegos exclusivos',
               'fr' => 'Jeux exclusifs',
               'pl' => 'Gry ekskluzywne',
               'pt' => 'Jogos exclusivos',
               'jv' => 'Game eksklusif',
               'ar' => 'ألعاب حصرية'
          ],

          'Good luck awaits you!' => [
               'en' => 'Good luck awaits you!',
               'ru' => 'Удачи вам!',
               'es' => '¡Buena suerte te espera!',
               'fr' => 'Bonne chance vous attend!',
               'pl' => 'Powodzenia czeka na Ciebie!',
               'pt' => 'Boa sorte te espera!',
               'jv' => 'Muga sukses ngenteni sampeyan!',
               'ar' => 'حظًا سعيدًا في انتظارك!'
          ],

          'Slots' => [
               'en' => 'Slots',
               'ru' => 'Слоты',
               'es' => 'Tragamonedas',
               'fr' => 'Machines à sous',
               'pl' => 'Automaty',
               'pt' => 'Caça-níqueis',
               'jv' => 'Slot',
               'ar' => 'الآلات الميكانيكية'
          ],

          'ROTATE' => [
               'en' => 'ROTATE',
               'ru' => 'ПОВОРОТ',
               'es' => 'GIRAR',
               'fr' => 'TOURNER',
               'pl' => 'OBRÓĆ',
               'pt' => 'ROTEAR',
               'jv' => 'PUTERAN',
               'ar' => 'دَوَّار'
          ],

          'PLAY' => [
               'en' => 'PLAY',
               'ru' => 'ИГРАТЬ',
               'es' => 'JUGAR',
               'fr' => 'JOUER',
               'pl' => 'GRAJ',
               'pt' => 'JOGAR',
               'jv' => 'MAIN',
               'ar' => 'اللعب'
          ],

          'Play with dealers' => [
               'en' => 'Play with dealers',
               'ru' => 'Играйте с дилерами',
               'es' => 'Juega con los crupieres',
               'fr' => 'Jouez avec des croupiers',
               'pl' => 'Graj z dealerami',
               'pt' => 'Jogue com os dealers',
               'jv' => 'Main karo dealer',
               'ar' => 'العب مع الموزعين'
          ],

          'The best board games' => [
               'en' => 'The best board games',
               'ru' => 'Лучшие настольные игры',
               'es' => 'Los mejores juegos de mesa',
               'fr' => 'Les meilleurs jeux de société',
               'pl' => 'Najlepsze gry planszowe',
               'pt' => 'Os melhores jogos de tabuleiro',
               'jv' => 'Game papan paling apik',
               'ar' => 'أفضل ألعاب الطاولة'
          ],

          'Casino' => [
               'en' => 'Casino',
               'ru' => 'Казино',
               'es' => 'Casino',
               'fr' => 'Casino',
               'pl' => 'Kasyno',
               'pt' => 'Cassino',
               'jv' => 'Kasino',
               'ar' => 'كازينو'
          ],
          'Download Android' => [
               'en' => 'Download Android',
               'ru' => 'Скачать для Android',
               'es' => 'Descargar Android',
               'fr' => 'Télécharger Android',
               'pl' => 'Pobierz na Androida',
               'pt' => 'Baixar para Android',
               'jv' => 'Unduh Android',
               'ar' => 'تحميل على أندرويد'
          ],

          'Download IOS' => [
               'en' => 'Download IOS',
               'ru' => 'Скачать для IOS',
               'es' => 'Descargar IOS',
               'fr' => 'Télécharger IOS',
               'pl' => 'Pobierz na IOS',
               'pt' => 'Baixar para IOS',
               'jv' => 'Unduh IOS',
               'ar' => 'تحميل على آي أو إس'
          ],
          'class=" section-wrapper2 grid-wrapper2 d-grid2 padding-horizontal2 js-grid-wrapper2"' => [
               'en' => 'class=" section-wrapper2 grid-wrapper2 d-grid2 padding-horizontal2 js-grid-wrapper2"',
               'ru' => 'class=" section-wrapper2 grid-wrapper2 d-grid2 padding-horizontal2 js-grid-wrapper2"',
               'es' => 'class=" section-wrapper2 grid-wrapper2 d-grid2 padding-horizontal2 js-grid-wrapper2"',
               'fr' => 'class=" section-wrapper2 grid-wrapper2 d-grid2 padding-horizontal2 js-grid-wrapper2"',
               'pl' => 'class=" section-wrapper2 grid-wrapper2 d-grid2 padding-horizontal2 js-grid-wrapper2"',
               'pt' => 'class=" section-wrapper2 grid-wrapper2 d-grid2 padding-horizontal2 js-grid-wrapper2"',
               'jv' => 'class=" section-wrapper2 grid-wrapper2 d-grid2 padding-horizontal2 js-grid-wrapper2"',
               'ar' => 'class=" section-wrapper2-ar section-wrapper2 grid-wrapper2 d-grid2 padding-horizontal2 js-grid-wrapper2"',
          ],
          'Sports' => [
               'en' => 'Sports',
               'ru' => 'Спорт',
               'es' => 'Deportes',
               'fr' => 'Sports',
               'pl' => 'Sport',
               'pt' => 'Esportes',
               'jv' => 'Olahraga',
               'ar' => 'رياضات'
          ],

          'Log in' => [
               'en' => 'Log in',
               'ru' => 'Войти',
               'es' => 'Iniciar sesión',
               'fr' => 'Se connecter',
               'pl' => 'Zaloguj się',
               'pt' => 'Entrar',
               'jv' => 'Mlebu',
               'ar' => 'تسجيل الدخول'
          ],

          'Registration' => [
               'en' => 'Registration',
               'ru' => 'Регистрация',
               'es' => 'Registro',
               'fr' => 'Inscription',
               'pl' => 'Rejestracja',
               'pt' => 'Cadastro',
               'jv' => 'Pendaftaran',
               'ar' => 'التسجيل'
          ],
          'Menu' => [
               'en' => 'Menu',
               'ru' => 'Меню',
               'es' => 'Menú',
               'fr' => 'Menu',
               'pl' => 'Menu',
               'pt' => 'Menu',
               'jv' => 'Menu',
               'ar' => 'القائمة'
          ],
          'Show more' => [
               'en' => 'Show more',
               'ru' => 'Показать больше',
               'es' => 'Mostrar más',
               'fr' => 'Afficher plus',
               'pl' => 'Pokaż więcej',
               'pt' => 'Mostrar mais',
               'jv' => 'Tampilake liyane',
               'ar' => 'عرض المزيد'
          ],

          'Bonuses' => [
               'en' => 'Bonuses',
               'ru' => 'Бонусы',
               'es' => 'Bonificaciones',
               'fr' => 'Bonus',
               'pl' => 'Bonusy',
               'pt' => 'Bônus',
               'jv' => 'Bonus',
               'ar' => 'المكافآت'
          ],

          'Live' => [
               'en' => 'Live',
               'ru' => 'Лайв',
               'es' => 'En vivo',
               'fr' => 'En direct',
               'pl' => 'Na żywo',
               'pt' => 'Ao vivo',
               'jv' => 'Langsung',
               'ar' => 'مباشر'
          ],

          'Live-Casino' => [
               'en' => 'Live-Casino',
               'ru' => 'Лайв-Казино',
               'es' => 'Casino en vivo',
               'fr' => 'Casino en direct',
               'pl' => 'Kasyno na żywo',
               'pt' => 'Cassino ao vivo',
               'jv' => 'Kasino langsung',
               'ar' => 'كازينو مباشر'
          ],

          'More' => [
               'en' => 'More',
               'ru' => 'Ещё',
               'es' => 'Más',
               'fr' => 'Plus',
               'pl' => 'Więcej',
               'pt' => 'Mais',
               'jv' => 'Liyane',
               'ar' => 'المزيد'
          ],

          'Cybersport' => [
               'en' => 'Cybersport',
               'ru' => 'Киберспорт',
               'es' => 'Deportes electrónicos',
               'fr' => 'E-sport',
               'pl' => 'E-sport',
               'pt' => 'E-sports',
               'jv' => 'E-sport',
               'ar' => 'الرياضات الإلكترونية'
          ],
          'Line' => [
               'en' => 'Line',
               'ru' => 'Линия',
               'es' => 'Línea',
               'fr' => 'Ligne',
               'pl' => 'Linia',
               'pt' => 'Linha',
               'jv' => 'Garis',
               'ar' => 'الخط'
          ],

          'Favorites' => [
               'en' => 'Favorites',
               'ru' => 'Избранное',
               'es' => 'Favoritos',
               'fr' => 'Favoris',
               'pl' => 'Ulubione',
               'pt' => 'Favoritos',
               'jv' => 'Favorit',
               'ar' => 'المفضلة'
          ],

          'Search' => [
               'en' => 'Search',
               'ru' => 'Поиск',
               'es' => 'Búsqueda',
               'fr' => 'Recherche',
               'pl' => 'Szukaj',
               'pt' => 'Pesquisa',
               'jv' => 'Telusuri',
               'ar' => 'بحث'
          ],
          'TV Games' => [
               'en' => 'TV Games',
               'ru' => 'ТВ Игры',
               'es' => 'Juegos de TV',
               'fr' => 'Jeux TV',
               'pl' => 'Gry TV',
               'pt' => 'Jogos de TV',
               'jv' => 'Game TV',
               'ar' => 'ألعاب التلفزيون'
          ],
          'More TV Games' => [
               'en' => 'More TV Games',
               'ru' => 'Больше ТВ Игр',
               'es' => 'Más Juegos de TV',
               'fr' => 'Plus de Jeux TV',
               'pl' => 'Więcej Gier TV',
               'pt' => 'Mais Jogos de TV',
               'jv' => 'Luwih Akeh Game TV',
               'ar' => 'المزيد من ألعاب التلفزيون'
          ],
          'More Live' => [
               'en' => 'More Live',
               'ru' => 'Больше Прямых эфиров',
               'es' => 'Más en Vivo',
               'fr' => 'Plus de Direct',
               'pl' => 'Więcej Na Żywo',
               'pt' => 'Mais Ao Vivo',
               'jv' => 'Luwih Akeh Langsung',
               'ar' => 'المزيد من البث المباشر'
          ],
          'More Casino' => [
               'en' => 'More Casino',
               'ru' => 'Больше Казино',
               'es' => 'Más Casino',
               'fr' => 'Plus de Casino',
               'pl' => 'Więcej Kasyna',
               'pt' => 'Mais Cassino',
               'jv' => 'Luwih Akeh Kasino',
               'ar' => 'المزيد من الكازينو'
          ],
          'Games' => [
               'en' => 'Games',
               'ru' => 'Игры',
               'es' => 'Juegos',
               'fr' => 'Jeux',
               'pl' => 'Gry',
               'pt' => 'Jogos',
               'jv' => 'Game',
               'ar' => 'ألعاب'
          ],
          'More Games' => [
               'en' => 'More Games',
               'ru' => 'Больше Игр',
               'es' => 'Más Juegos',
               'fr' => 'Plus de Jeux',
               'pl' => 'Więcej Gier',
               'pt' => 'Mais Jogos',
               'jv' => 'Luwih Akeh Game',
               'ar' => 'المزيد من الألعاب'
          ]



     ];

     return $translations[$text][$current_language] ?? $text;
}

function banner_games()
{
     return '
        <h2 class="all-bonuses">' . get_translation('All Bonuses') . '</h2>
        <div class="container-game-888">
            <a href="/game/" class="link-game-888" rel="nofollow">
                <img decoding="async" src="/wp-content/uploads/2025/01/1st.webp" alt="' . get_translation('+100% bonus on your first deposit') . '">
                <span class="desc-game">' . get_translation('+100% bonus on your first deposit') . '</span>
                <div class="out-more">' . get_translation('Find out more') . '</div>
            </a>
            <a href="/game/" class="link-game-888 " rel="nofollow">
                <img decoding="async" src="/wp-content/uploads/2025/01/slot-first-deposit.webp" alt="' . get_translation('Welcome package up to 1500 EUR + 150 FS') . '">
                <span class="desc-game">' . get_translation('Welcome package up to 1500 EUR + 150 FS') . '</span>
                <div class="out-more">' . get_translation('Find out more') . '</div>
            </a>
			
            <a href="/game/" class="link-game-888" rel="nofollow">
                <img decoding="async" src="/wp-content/uploads/2025/01/50-_second_deposit_-1-.webp" alt="' . pll__('50% SECOND DEPOSIT BONUS') . '">
                <span class="desc-game">' . get_translation('50% SECOND DEPOSIT BONUS') . '</span>
                <div class="out-more">' . get_translation('Find out more') . '</div>
            </a>
            <a href="/game/" class="link-game-888" rel="nofollow">
                <img decoding="async" src="/wp-content/uploads/2025/01/320-250.webp" alt="' . pll__('WINNERS LOTTERY') . '">
                <span class="desc-game">' . get_translation('WINNERS LOTTERY') . '</span>
                <div class="out-more">' . get_translation('Find out more') . '</div>
            </a>
            <a href="/game/" class="link-game-888" rel="nofollow">
                <img decoding="async" src="/wp-content/uploads/2025/01/315x250.webp" alt="' . pll__('TOP FOOTBALL LEAGUES') . '">
                <span class="desc-game">' . get_translation('TOP FOOTBALL LEAGUES') . '</span>
                <div class="out-more">' . get_translation('Find out more') . '</div>
            </a>
            <a href="/game/" class="link-game-888" rel="nofollow">
                <img decoding="async" src="/wp-content/uploads/2025/01/Bonus_NHL_2024_315x250.webp" alt="' . pll__('Ice Win') . '">
                <span class="desc-game">' . get_translation('Ice Win') . '</span>
                <div class="out-more">' . get_translation('Find out more') . '</div>
            </a>
            <a href="/game/" class="link-game-888" rel="nofollow">
                <img decoding="async" src="/wp-content/uploads/2025/01/vip-cashback.webp" alt="' . pll__('Casino VIP Cashback') . '">
                <span class="desc-game">' . get_translation('Casino VIP Cashback') . '</span>
                <div class="out-more">' . get_translation('Find out more') . '</div>
            </a>
            <a href="/game/" class="link-game-888" rel="nofollow">
                <img decoding="async" src="/wp-content/uploads/2025/01/3-_Tuesday.webp" alt="' . pll__('3% cashback every Tuesday') . '">
                <span class="desc-game">' . get_translation('3% cashback every Tuesday') . '</span>
                <div class="out-more">' . get_translation('Find out more') . '</div>
            </a>
            <a href="/game/" class="link-game-888" rel="nofollow">
                <img decoding="async" src="/wp-content/uploads/2025/01/dailytournament_23.webp" alt="' . pll__('Great prizes up for grabs every day!') . '">
                <span class="desc-game">' . get_translation('Great prizes up for grabs every day!') . '</span>
                <div class="out-more">' . get_translation('Find out more') . '</div>
            </a>
            <a href="/game/" class="link-game-888" rel="nofollow">
                <img decoding="async" src="/wp-content/uploads/2025/01/50-_TUESDAY_-1-.webp" alt="' . pll__('50% BONUS FOR SPORTS BETS EVERY TUESDAY') . '">
                <span class="desc-game">' . get_translation('50% BONUS FOR SPORTS BETS EVERY TUESDAY') . '</span>
                <div class="out-more">' . get_translation('Find out more') . '</div>
            </a>
            <a href="/game/" class="link-game-888" rel="nofollow">
                <img decoding="async" src="/wp-content/uploads/2025/01/100-_thursday_946.webp" alt="' . pll__('100% SPORTS BETTING BONUS EVERY THURSDAY') . '">
                <span class="desc-game">' . get_translation('100% SPORTS BETTING BONUS EVERY THURSDAY') . '</span>
                <div class="out-more">' . get_translation('Find out more') . '</div>
            </a>
            <a href="/game/" class="link-game-888" rel="nofollow">
                <img decoding="async" src="/wp-content/uploads/2025/01/accumulator_insurance.webp" alt="' . pll__('ACCUMULATOR INSURANCE') . '">
                <span class="desc-game">' . get_translation('ACCUMULATOR INSURANCE') . '</span>
                <div class="out-more">' . get_translation('Find out more') . '</div>
            </a>
            <a href="/game/" class="link-game-888" rel="nofollow">
                <img decoding="async" src="/wp-content/uploads/2025/01/accumulator_of_the_day.webp" alt="' . pll__('ACCUMULATOR OF THE DAY') . '">
                <span class="desc-game">' . get_translation('ACCUMULATOR OF THE DAY') . '</span>
                <div class="out-more">' . get_translation('Find out more') . '</div>
            </a>
            <a href="/game/" class="link-game-888" rel="nofollow">
                <img decoding="async" src="/wp-content/uploads/2025/01/888games-day.webp" alt="' . pll__('888GAMES DAY') . '">
                <span class="desc-game">' . get_translation('888GAMES DAY') . '</span>
                <div class="out-more">' . get_translation('Find out more') . '</div>
            </a>
            <a href="/game/" class="link-game-888" rel="nofollow">
                <img decoding="async" src="/wp-content/uploads/2025/01/lucky-ticket.webp" alt="' . pll__('Lucky ticket') . '">
                <span class="desc-game">' . get_translation('Lucky ticket') . '</span>
                <div class="out-more">' . get_translation('Find out more') . '</div>
            </a>
        </div>
        <button id="toggle-btn" class="toggle-btn">' . get_translation('Find out more') . '</button>
    ';
}
add_shortcode('bannergames', 'banner_games');

function banner_games_header()
{
     return '
	<div class="mobile-header-widget">
		<div class="overflow-game-header-top">
			<div class="top__games-header">
						<a href="/game/" class="left-header__link-game-top-games crystal-games-header" rel="nofollow">
							<img src="/wp-content/uploads/2025/01/game-249-animation.svg" alt="Crystal">
							<span>Crystal</span>
						</a>
						<a href="/game/" class="left-header__link-game-top-games" rel="nofollow">
							<img src="/wp-content/uploads/2025/01/740a96629ce88bbf075e4e3003a3ff1e.svg" alt="Aviator" width="70" height="32">
						</a>
						<a href="/game/" class="left-header__link-game-top-games" rel="nofollow">
							<img src="/wp-content/uploads/2025/01/3ca73a69e5275a2051000fb92ce2e288.webp" alt="League Champions" width="32" height="32">
						</a>
						<a href="/game/" class="left-header__link-game-top-games" rel="nofollow">
							<img src="/wp-content/uploads/2025/01/dd6d3a947117b4b8e79c5159c091ada3.webp" alt="League Champions" width="32" height="32">
						</a>
						<a href="/game/" class="left-header__link-game-top-games" rel="nofollow">
							<img src="/wp-content/uploads/2025/01/e9c65966664d9c2e4e0ba094a0760d40.svg" alt="Premier-league" width="32" height="32">
						</a>
						<a href="/game/" class="left-header__link-game-top-games" rel="nofollow">
							<img src="/wp-content/uploads/2025/01/ef82153ad04426601aca85827938d210.svg" alt="La Liga" width="32" height="32">
						</a>
						<a href="/game/" class="left-header__link-game-top-games" rel="nofollow">
							<img src="/wp-content/uploads/2025/01/a88c2145994b996d246eade556be38b0.svg" alt="Seria A" width="32" height="32">
						</a>
			</div>
		</div>
		<div class="overflow-888-game-header">
			<div class="container-game-888-header">
				<a href="/game/" class="link-game-888-header item" rel="nofollow">
					<img decoding="async" src="/wp-content/uploads/2025/01/1st.webp" alt="' . get_translation('+100% bonus on your first deposit') . '">
					<span class="desc-game-header">' . get_translation('+100% bonus on your first deposit') . '</span>
				</a>
				<a href="/game/" class="link-game-888-header item" rel="nofollow">
					<img decoding="async" src="/wp-content/uploads/2025/01/slot-first-deposit.webp" alt="' . get_translation('1500 EUR + 150 FS') . '">
					<span class="desc-game-header">' . get_translation('1500 EUR + 150 FS') . '</span>
				</a>
				<a href="/game/" class="link-game-888-header item" rel="nofollow">
					<img decoding="async" src="/wp-content/uploads/2025/01/50-_second_deposit_-1-.webp" alt="' . pll__('50% SECOND DEPOSIT BONUS') . '">
					<span class="desc-game-header">' . get_translation('50% SECOND DEPOSIT BONUS') . '</span>
				</a>
				<a href="/game/" class="link-game-888-header item" rel="nofollow">
					<img decoding="async" src="/wp-content/uploads/2025/01/320-250.webp" alt="' . pll__('WINNERS LOTTERY') . '">
					<span class="desc-game-header">' . get_translation('WINNERS LOTTERY') . '</span>
				</a>
				<a href="/game/" class="link-game-888-header item" rel="nofollow">
					<img decoding="async" src="/wp-content/uploads/2025/01/Bonus_NHL_2024_315x250.webp" alt="' . pll__('Ice Win') . '">
					<span class="desc-game-header">' . get_translation('Ice Win') . '</span>
				</a>
				<a href="/game/" class="link-game-888-header item" rel="nofollow">
					<img decoding="async" src="/wp-content/uploads/2025/01/vip-cashback.webp" alt="' . pll__('Casino VIP Cashback') . '">
					<span class="desc-game-header">' . get_translation('Casino VIP Cashback') . '</span>
				</a>
				<a href="/game/" class="link-game-888-header item" rel="nofollow">
					<img decoding="async" src="/wp-content/uploads/2025/01/3-_Tuesday.webp" alt="' . pll__('3% cashback every Tuesday') . '">
					<span class="desc-game-header">' . get_translation('3% cashback every Tuesday') . '</span>
				</a>


				<a href="/game/" class="link-game-888-header item" rel="nofollow">
					<img decoding="async" src="/wp-content/uploads/2025/01/accumulator_insurance.webp" alt="' . pll__('ACCUMULATOR INSURANCE') . '">
					<span class="desc-game-header">' . get_translation('ACCUMULATOR INSURANCE') . '</span>
				</a>
				<a href="/game/" class="link-game-888-header item" rel="nofollow">
					<img decoding="async" src="/wp-content/uploads/2025/01/accumulator_of_the_day.webp" alt="' . pll__('ACCUMULATOR OF THE DAY') . '">
					<span class="desc-game-header">' . get_translation('ACCUMULATOR OF THE DAY') . '</span>
				</a>

			</div>
		</div>
		<nav class="home-navigation betting-main__navigation">
			<ul class="home-navigation__menu">
				<li class="home-navigation__item">
					<a href="/game/" rel="nofollow" title="' . get_translation('Live') . '" class="home-navigation__link home-navigation__link--live">
						' . get_translation('Live') . '
					</a>
				</li>
				<li class="home-navigation__item">
					<a href="/game/" rel="nofollow" title="' . get_translation('Line') . '" class="home-navigation__link">
						' . get_translation('Line') . '
					</a>
				</li>
				<li class="home-navigation__item">
					<a href="/game/" rel="nofollow title="' . get_translation('Favorites') . '" class="home-navigation__link home-navigation__link--favorite" style="display: flex;">
						<svg width="22px" height="19px" fill="#ff0000" viewBox="0 0 576 512" class="ico__svg" focusable="false" role="img" data-v-ico="common|star"><path d="M259.3 17.8L194 150.2 47.9 171.5a32 32 0 00-17.7 54.6l105.7 103-25 145.5a32 32 0 0046.4 33.7L288 439.6l130.7 68.7a32 32 0 0046.4-33.7l-25-145.5 105.7-103a32 32 0 00-17.7-54.6L382 150.2 316.7 17.8a32 32 0 00-57.4 0z"></path></svg>
					</a>
				</li>
				<li class="home-navigation__item">
					<a href="/game/" rel="nofollow title="' . get_translation('Search') . '" class="home-navigation__link home-navigation__link--search" style="display: flex;">
						<svg width="16px" height="16px" fill="#424242" viewBox="0 0 512 512" class="ico__svg" focusable="false" role="img" data-v-ico="common|search"><path d="M505 443L405 343c-4-4-10-7-17-7h-16a208 208 0 10-36 36v16c0 7 3 13 7 17l100 100c9 9 24 9 34 0l28-28c9-10 9-25 0-34zM208 336a128 128 0 110-256 128 128 0 010 256z"></path></svg>
					</a>
				</li>
			</ul>
		</nav>
	</div>
    ';
}
add_shortcode('bannergamesheader', 'banner_games_header');


function twobutton_func()
{
     return '
	<div class="contaienr-button-two">
		<a href="/game/" rel="nofollow" class="link-twobutton-game" aria-label="' . esc_attr( get_translation('Download Android') ) . '">
			<svg fill="#ffffff" height="32px" width="32px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-146 129 218 256" xml:space="preserve" stroke="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <path d="M-2.9,150.4l2.8-4.2l2.8-4.1l6.2-9.3c0.8-1.1,0.5-2.7-0.7-3.4c-1.1-0.8-2.7-0.5-3.4,0.7l-6.6,9.9l-2.8,4.2l-2.8,4.2 c-9-3.5-18.9-5.4-29.5-5.4c-10.5,0-20.5,1.9-29.5,5.4l-2.8-4.2L-72,140l-6.6-9.9c-0.8-1.1-2.3-1.4-3.4-0.7 c-1.1,0.8-1.4,2.3-0.7,3.4l6.2,9.3l2.8,4.1l2.8,4.2c-21,9.8-35.3,28.3-35.3,49.6H32.5C32.4,178.7,18.2,160.2-2.9,150.4z M-66.7,180.1c-4.1,0-7.4-3.3-7.4-7.4c0-4.1,3.3-7.4,7.4-7.4c4.1,0,7.4,3.3,7.4,7.4S-62.6,180.1-66.7,180.1z M-7.3,180.1 c-4.1,0-7.4-3.3-7.4-7.4c0-4.1,3.3-7.4,7.4-7.4c4.1,0,7.4,3.3,7.4,7.4C0.2,176.8-3.1,180.1-7.3,180.1z"></path> <path d="M-105.3,209.8l-1.1,0.1v12.3v10.1v86.6c0,8.7,7,15.7,15.7,15.7h11.3c-0.4,1.3-0.6,2.7-0.6,4.1v0.8v5v25.6 c0,8.2,6.7,14.9,14.9,14.9s14.9-6.7,14.9-14.9v-25.6v-5v-0.8c0-1.4-0.2-2.8-0.6-4.1h27.6c-0.4,1.3-0.6,2.7-0.6,4.1v0.8v5v25.6 c0,8.2,6.7,14.9,14.9,14.9c8.2,0,14.9-6.7,14.9-14.9v-25.6v-5v-0.8c0-1.4-0.2-2.8-0.6-4.1h11.3c8.7,0,15.7-7,15.7-15.7v-86.6v-10.1 v-12.4h-1.1H-105.3z"></path> <path d="M-131.1,209.9c-8.2,0-14.9,6.7-14.9,14.9v63.6c0,8.2,6.7,14.9,14.9,14.9c8.2,0,14.9-6.7,14.9-14.9v-63.6 C-116.3,216.5-122.9,209.9-131.1,209.9z"></path> <path d="M57.2,209.9c-8.2,0-14.9,6.7-14.9,14.9v63.6c0,8.2,6.7,14.9,14.9,14.9s14.9-6.7,14.9-14.9v-63.6 C72,216.5,65.4,209.9,57.2,209.9z"></path> </g> </g></svg>
			' . get_translation('Download Android') . ' 
		</a>
		<a href="/game/" rel="nofollow" class="link-twobutton-game" aria-label="' . esc_attr( get_translation('Download IOS') ) . '">
			<svg width="32px" height="32px" viewBox="-1.5 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#ffffff" stroke="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>apple [#173]</title> <desc>Created with Sketch.</desc> <defs> </defs> <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Dribbble-Light-Preview" transform="translate(-102.000000, -7439.000000)" fill="#ffffff"> <g id="icons" transform="translate(56.000000, 160.000000)"> <path d="M57.5708873,7282.19296 C58.2999598,7281.34797 58.7914012,7280.17098 58.6569121,7279 C57.6062792,7279.04 56.3352055,7279.67099 55.5818643,7280.51498 C54.905374,7281.26397 54.3148354,7282.46095 54.4735932,7283.60894 C55.6455696,7283.69593 56.8418148,7283.03894 57.5708873,7282.19296 M60.1989864,7289.62485 C60.2283111,7292.65181 62.9696641,7293.65879 63,7293.67179 C62.9777537,7293.74279 62.562152,7295.10677 61.5560117,7296.51675 C60.6853718,7297.73474 59.7823735,7298.94772 58.3596204,7298.97372 C56.9621472,7298.99872 56.5121648,7298.17973 54.9134635,7298.17973 C53.3157735,7298.17973 52.8162425,7298.94772 51.4935978,7298.99872 C50.1203933,7299.04772 49.0738052,7297.68074 48.197098,7296.46676 C46.4032359,7293.98379 45.0330649,7289.44985 46.8734421,7286.3899 C47.7875635,7284.87092 49.4206455,7283.90793 51.1942837,7283.88393 C52.5422083,7283.85893 53.8153044,7284.75292 54.6394294,7284.75292 C55.4635543,7284.75292 57.0106846,7283.67793 58.6366882,7283.83593 C59.3172232,7283.86293 61.2283842,7284.09893 62.4549652,7285.8199 C62.355868,7285.8789 60.1747177,7287.09489 60.1989864,7289.62485" id="apple-[#173]"> </path> </g> </g> </g> </g></svg>
			
			' . get_translation('Download IOS') . '
		</a>
	</div>
    ';
}
add_shortcode('twobutton', 'twobutton_func');
function generate_toc_shortcode($atts, $content = null)
{
     global $post;
     $content = $post->post_content;

     // Извлекаем заголовки (h2 и h3) из контента
     preg_match_all('/<h([2-3])>(.*?)<\/h[2-3]>/', $content, $matches, PREG_SET_ORDER);

     if (empty($matches)) {
          return ''; // Если нет заголовков, не создаем оглавление
     }

     $toc = '<div class="custom-toc"><div class="ez-question">المحتوى <svg class="checkmark" width="32px" height="32px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M10 7L15 12L10 17" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg> </div><ul class="ez-answer">';

     // Счетчик элементов для отображения первых 10
     $counter = 0;

     foreach ($matches as $match) {
          // Удаляем HTML-теги из заголовка
          $text = wp_strip_all_tags($match[2]);
          // Генерируем ID
          $id = remove_accents($text);
          $id = preg_replace('/[^\p{L}\p{N}\s]/u', '', $id); // Удаление спецсимволов
          $id = str_replace(' ', '-', trim($id)); // Преобразование пробелов в дефисы
          $id = strtolower($id);

          // Добавляем класс для скрытия элементов с 11-го
          $toc .= '<li class="toc-item toc-level-' . $match[1] . ($counter >= 10 ? ' toc-hidden' : '') . '" style="list-style-type: none;">';
          $toc .= '<a href="#' . esc_attr($id) . '">' . esc_html($text) . '</a></li>';
          $counter++;
     }

     $toc .= '</ul><button id="show-more" class="show-more-btn" type="button" aria-label="اعرض المزيد">اعرض المزيد</button></div>';

     return $toc;
}


add_shortcode('toc', 'generate_toc_shortcode');

function add_ids_to_headings($content)
{
     return preg_replace_callback(
          '/<h([2-3])>(.*?)<\/h[2-3]>/u',
          function ($matches) {
               // Удаляем HTML-теги из заголовка
               $text = wp_strip_all_tags($matches[2]);
               // Генерируем ID
               $id = remove_accents($text);
               $id = preg_replace('/[^\p{L}\p{N}\s]/u', '', $id); // Удаление спецсимволов
               $id = str_replace(' ', '-', trim($id)); // Преобразование пробелов в дефисы
               $id = strtolower($id); // Приведение к нижнему регистру
               return '<h' . $matches[1] . ' id="' . esc_attr($id) . '">' . $matches[2] . '</h' . $matches[1] . '>';
          },
          $content
     );
}


add_filter('the_content', 'add_ids_to_headings');
function insert_shortcode_before_second_h2($content)
{
     // Проверяем, есть ли шорткод [toc] на странице
     if (strpos($content, '[toc]') === false) {
          // Ищем все заголовки <h2> в контенте
          preg_match_all('/<h2.*?>(.*?)<\/h2>/', $content, $matches, PREG_OFFSET_CAPTURE);

          // Если найдено хотя бы два заголовка <h2>
          if (isset($matches[0][1])) {
               // Позиция второго <h2> в контенте
               $second_h2_position = $matches[0][1][1];

               // Вставляем шорткод перед вторым <h2>
               $content = substr_replace($content, '[toc]', $second_h2_position, 0);
          }
     }

     return $content;
}
add_filter('the_content', 'insert_shortcode_before_second_h2');

function fix_table_width_zero($content) {
    $content = preg_replace('/<table(\s+[^>]*)\s+width=["\']0["\']/i', '<table$1 width="100%"', $content);
    $content = preg_replace('/<table\s+width=["\']0["\'](\s+[^>]*)/i', '<table width="100%"$1', $content);
    return $content;
}
add_filter('the_content', 'fix_table_width_zero', 20);

function custom_slider_shortcode()
{
     ob_start();
     ?>
     <div class="owl-mobile owl-theme" dir="ltr">
          <div class="mobile-slide">
               <a href="/wp-content/uploads/2025/03/888starz.com_.webp" data-fancybox="gallerymob">
                    <img src="/wp-content/uploads/2025/03/888starz.com_.webp" alt="888starz.com" title="888starz.com">
               </a>
          </div>

          <div class="mobile-slide">
               <a href="/wp-content/uploads/2025/03/888ألعاب-888GAMES.webp" data-fancybox="gallerymob">
                    <img src="/wp-content/uploads/2025/03/888ألعاب-888GAMES.webp" alt="ألعاب-888GAMES"
                         title="ألعاب-888GAMES">
               </a>
          </div>

          <div class="mobile-slide">
               <a href="/wp-content/uploads/2025/03/الألعاب-التلفزيونية.webp" data-fancybox="gallerymob">
                    <img src="/wp-content/uploads/2025/03/الألعاب-التلفزيونية.webp" alt="الألعاب-التلفزيونية"
                         title="الألعاب-التلفزيونية">
               </a>
          </div>

          <div class="mobile-slide">
               <a href="/wp-content/uploads/2025/03/الرمز-الترويجي-1500-و150-دورة-مجانية.webp" data-fancybox="gallerymob">
                    <img src="/wp-content/uploads/2025/03/الرمز-الترويجي-1500-و150-دورة-مجانية.webp"
                         alt="الرمز-الترويجي-1500-و150-دورة-مجانية" title="الرمز-الترويجي-1500-و150-دورة-مجانية">
               </a>
          </div>

          <div class="mobile-slide">
               <a href="/wp-content/uploads/2025/03/الرهان-على-الرياضات-الإلكترونية.webp" data-fancybox="gallerymob">
                    <img src="/wp-content/uploads/2025/03/الرهان-على-الرياضات-الإلكترونية.webp"
                         alt="الرهان-على-الرياضات-الإلكترونية" title="الرهان-على-الرياضات-الإلكترونية">
               </a>
          </div>

          <div class="mobile-slide">
               <a href="/wp-content/uploads/2025/03/ألعاب-الكازينو.webp" data-fancybox="gallerymob">
                    <img src="/wp-content/uploads/2025/03/ألعاب-الكازينو.webp" alt="ألعاب-الكازينو" title="ألعاب-الكازينو">
               </a>
          </div>

          <div class="mobile-slide">
               <a href="/wp-content/uploads/2025/03/حساب-تجريبي-888-ستارز.webp" data-fancybox="gallerymob">
                    <img src="/wp-content/uploads/2025/03/حساب-تجريبي-888-ستارز.webp" alt="حساب-تجريبي-888-ستارز"
                         title="حساب-تجريبي-888-ستارز">
               </a>
          </div>

          <div class="mobile-slide">
               <a href="/wp-content/uploads/2025/03/لعبة-الطيار.webp" data-fancybox="gallerymob">
                    <img src="/wp-content/uploads/2025/03/لعبة-الطيار.webp" alt="لعبة-الطيار" title="لعبة-الطيار">
               </a>
          </div>

          <div class="mobile-slide">
               <a href="/wp-content/uploads/2025/03/ماكينات-القمار-888ستارز.webp" data-fancybox="gallerymob">
                    <img src="/wp-content/uploads/2025/03/ماكينات-القمار-888ستارز.webp" alt="ماكينات-القمار-888ستارز"
                         title="ماكينات-القمار-888ستارز">
               </a>
          </div>

          <div class="mobile-slide">
               <a href="/wp-content/uploads/2025/03/نوع-الألعاب-في-الكازينو.webp" data-fancybox="gallerymob">
                    <img src="/wp-content/uploads/2025/03/نوع-الألعاب-في-الكازينو.webp" alt="نوع-الألعاب-في-الكازينو"
                         title="نوع-الألعاب-في-الكازينو">
               </a>
          </div>

     </div>


     <?php
     return ob_get_clean();
}
add_shortcode('slidermob', 'custom_slider_shortcode');

add_shortcode('sliderdesc', 'deck_slider_shortcode');

function deck_slider_shortcode()
{
     ob_start();
     ?>

     <div class="owl-carousel owl-theme" dir="ltr">
          <div class="slide">
               <a href="/wp-content/uploads/2025/03/الإحصاءات.webp" data-fancybox="gallery">
                    <img src="/wp-content/uploads/2025/03/الإحصاءات.webp" alt="الإحصاءات" title="الإحصاءات">
               </a>
          </div>

          <div class="slide">
               <a href="/wp-content/uploads/2025/03/البنغو.webp" data-fancybox="gallery">
                    <img src="/wp-content/uploads/2025/03/البنغو.webp" alt="البنغو" title="البنغو">
               </a>
          </div>

          <div class="slide">
               <a href="/wp-content/uploads/2025/03/الرياضة-الإلكترونية.webp" data-fancybox="gallery">
                    <img src="/wp-content/uploads/2025/03/الرياضة-الإلكترونية.webp" alt="الرياضة-الإلكترونية"
                         title="الرياضة-الإلكترونية">
               </a>
          </div>

          <div class="slide">
               <a href="/wp-content/uploads/2025/03/الصفحة-الرئيسية-888ستارز.webp" data-fancybox="gallery">
                    <img src="/wp-content/uploads/2025/03/الصفحة-الرئيسية-888ستارز.webp" alt="الصفحة-الرئيسية-888ستارز"
                         title="الصفحة-الرئيسية-888ستارز">
               </a>
          </div>

          <div class="slide">
               <a href="/wp-content/uploads/2025/03/ألعاب-الكازينو-1.webp" data-fancybox="gallery">
                    <img src="/wp-content/uploads/2025/03/ألعاب-الكازينو-1.webp" alt="ألعاب-الكازينو-1"
                         title="ألعاب-الكازينو-1">
               </a>
          </div>

          <div class="slide">
               <a href="/wp-content/uploads/2025/03/النتائج.webp" data-fancybox="gallery">
                    <img src="/wp-content/uploads/2025/03/النتائج.webp" alt="النتائج" title="النتائج">
               </a>
          </div>

          <div class="slide">
               <a href="/wp-content/uploads/2025/03/بوكر.webp" data-fancybox="gallery">
                    <img src="/wp-content/uploads/2025/03/بوكر.webp" alt="بوكر" title="بوكر">
               </a>
          </div>

          <div class="slide">
               <a href="/wp-content/uploads/2025/03/جميع-الألعاب-888.webp" data-fancybox="gallery">
                    <img src="/wp-content/uploads/2025/03/جميع-الألعاب-888.webp" alt="جميع-الألعاب-888"
                         title="جميع-الألعاب-888">
               </a>
          </div>

          <div class="slide">
               <a href="/wp-content/uploads/2025/03/جميع-المكافآت.webp" data-fancybox="gallery">
                    <img src="/wp-content/uploads/2025/03/جميع-المكافآت.webp" alt="جميع-المكافآت" title="جميع-المكافآت">
               </a>
          </div>

          <div class="slide">
               <a href="/wp-content/uploads/2025/03/ماكينات-سلوتس-888-ستارز.webp" data-fancybox="gallery">
                    <img src="/wp-content/uploads/2025/03/ماكينات-سلوتس-888-ستارز.webp" alt="ماكينات-سلوتس-888-ستارز"
                         title="ماكينات-سلوتس-888-ستارز">
               </a>
          </div>
     </div>


     <?php
     return ob_get_clean();
}
function smart_slider_mobile_shortcode()
{
     if (wp_is_mobile()) {
          return do_shortcode('[smartslider3 slider="3"]');
     }
     return ''; // Пустая строка, если не мобильное устройство
}
add_shortcode('mobile_smart_slider', 'smart_slider_mobile_shortcode');

function smart_slider_desktop_shortcode()
{
     if (!wp_is_mobile()) {
          return do_shortcode('[smartslider3 slider="2"]');
     }
     return ''; // Пустая строка, если устройство мобильное
}
add_shortcode('desktop_smart_slider', 'smart_slider_desktop_shortcode');


if (!function_exists('starz888_get_translations')) {
     /**
      * Get all translations for 888STARZ website
      * 
      * @return array Multidimensional array of all translations
      */
     function starz888_get_translations()
     {
          return array(
               'en' => array(
                    'slider' => array(
                         'slide1' => array(
                              'title' => 'مكافأة 100% على الإيداع الأول',
                              'description' => 'سجل في 888STARZ واحصل على مكافأة مضمونة لإيداعك الأول',
                              'button' => 'سجل الآن'
                         ),
                         'slide2' => array(
                              'title' => 'استرداد نقدي أسبوعي 3%',
                              'description' => 'ضع رهاناتك خلال الأسبوع واحصل على استرداد نقدي بنسبة 3% من مبلغ الرهانات كل يوم ثلاثاء',
                              'button' => 'المزيد من التفاصيل'
                         ),
                         'slide3' => array(
                              'title' => 'باقة الكازينو الترحيبية',
                              'description' => 'باقة ترحيبية لـ4 إيداعات + 150 دورة مجانية',
                              'button' => 'سجل الآن'
                         )
                    ),
                    // Добавьте здесь другие секции сайта по мере необходимости
                    'header' => array(
                         'login' => 'Log in',
                         'register' => 'Register',
                         'menu' => array(
                              'sports' => 'Sports',
                              'live' => 'Live',
                              'casino' => 'Casino',
                              'bonuses' => 'Bonuses',
                              'support' => 'Support'
                         )
                    ),
                    'footer' => array(
                         'about' => 'About us',
                         'terms' => 'Terms and Conditions',
                         'privacy' => 'Privacy Policy',
                         'support' => 'Support 24/7'
                    )
               ),
               'jv' => array(
                    'slider' => array(
                         'slide1' => array(
                              'title' => '100% BONUS KANGGO DEPOSIT PERTAMA',
                              'description' => 'Daftar ing 888STARZ lan entuk bonus dijamin kanggo deposit pertama sampeyan',
                              'button' => 'NDAFTAR'
                         ),
                         'slide2' => array(
                              'title' => 'CASHBACK MINGGUAN 3%',
                              'description' => 'Pasang taruhan sajrone minggu lan entuk cashback 3% saka jumlah taruhan saben Selasa',
                              'button' => 'RINCIAN LIYANE'
                         ),
                         'slide3' => array(
                              'title' => 'PAKET SAMBUTAN KASINO',
                              'description' => 'Paket sambutan kanggo 4 deposit + 150FS',
                              'button' => 'NDAFTAR'
                         )
                    ),
                    // Добавьте здесь другие секции сайта
               ),
               'ru' => array(
                    'slider' => array(
                         'slide1' => array(
                              'title' => '100% БОНУС ЗА ПЕРВЫЙ ДЕПОЗИТ',
                              'description' => 'Регистрируйся на 888STARZ и получи гарантированный бонус за первый депозит',
                              'button' => 'ЗАРЕГИСТРИРОВАТЬСЯ'
                         ),
                         'slide2' => array(
                              'title' => 'ЕЖЕНЕДЕЛЬНЫЙ КЕШБЭК 3%',
                              'description' => 'Делай ставки в течение недели и получай кешбэк 3% от суммы ставок каждый вторник',
                              'button' => 'ПОДРОБНЕЕ'
                         ),
                         'slide3' => array(
                              'title' => 'ПРИВЕТСТВЕННЫЙ ПАКЕТ КАЗИНО',
                              'description' => 'Приветственный пакет для 4 депозитов + 150FS',
                              'button' => 'ЗАРЕГИСТРИРОВАТЬСЯ'
                         )
                    ),
                    // Другие секции
               ),
               // Остальные языки...
          );
     }
}

if (!function_exists('starz888_get_current_language')) {
     /**
      * Get current site language
      * 
      * @return string Language code (en, ru, es, etc.)
      */
     function starz888_get_current_language()
     {
          // Option 1: If using WPML
          if (function_exists('icl_object_id')) {
               return ICL_LANGUAGE_CODE;
          }

          // Option 2: If using Polylang
          if (function_exists('pll_current_language')) {
               return pll_current_language();
          }

          // Option 3: Custom implementation using a cookie or session
          if (isset($_COOKIE['site_language'])) {
               return $_COOKIE['site_language'];
          }

          // Default to English
          return 'en';
     }
}

if (!function_exists('starz888_get_text')) {
     /**
      * Get translated text for specific key
      * 
      * @param string $section Section of the website (slider, header, footer, etc.)
      * @param string $key Primary translation key
      * @param string $subkey Optional secondary key for nested translations
      * @param string $language Optional language code (if not provided, current language will be used)
      * @return string Translated text or empty string if not found
      */
     function starz888_get_text($section, $key, $subkey = '', $language = '')
     {
          // Get all translations
          $all_translations = starz888_get_translations();

          // Get current language if not specified
          if (empty($language)) {
               $language = starz888_get_current_language();
          }

          // Fallback to English if specified language not found
          if (!isset($all_translations[$language])) {
               $language = 'en';
          }

          // Return empty string if section doesn't exist
          if (!isset($all_translations[$language][$section])) {
               return '';
          }

          // Handle nested translations
          if (!empty($subkey)) {
               return isset($all_translations[$language][$section][$key][$subkey])
                    ? $all_translations[$language][$section][$key][$subkey]
                    : '';
          }

          // Return translation for single key
          return isset($all_translations[$language][$section][$key])
               ? $all_translations[$language][$section][$key]
               : '';
     }
}

/**
 * Dropdown переключатель языков для WordPress с Polylang
 * 
 * Добавьте этот код в functions.php вашей темы
 */

// Шорткод для вызова переключателя языков [dropdown_language_switcher]
function dropdown_language_switcher_shortcode($atts)
{
     // Проверяем, активирован ли Polylang
     if (!function_exists('pll_the_languages')) {
          return '<div class="error" style="color: red;">Polylang не активирован</div>';
     }

     // Параметры по умолчанию
     $attributes = shortcode_atts(
          array(
               'show_flags' => 'yes',
               'show_names' => 'yes',
               'class' => 'lang-dropdown',
          ),
          $atts
     );

     // Получаем языки через функцию Polylang
     $languages = pll_the_languages(array(
          'show_flags' => 1,
          'show_names' => 1,
          'hide_if_empty' => 0,
          'hide_current' => 0,
          'raw' => 1
     ));

     if (empty($languages)) {
          return '';
     }

     // Находим текущий язык
     $current_language = null;
     foreach ($languages as $language) {
          if ($language['current_lang']) {
               $current_language = $language;
               break;
          }
     }

     if (!$current_language) {
          return '';
     }

     // Формируем HTML структуру
     $show_flags = ($attributes['show_flags'] === 'yes');
     $show_names = ($attributes['show_names'] === 'yes');
     $class = esc_attr($attributes['class']);

     // Текущий язык (элемент, по которому кликают)
     $current_flag = $show_flags && !empty($current_language['flag'])
          ? $current_language['flag']
          : '';

     $current_name = $show_names
          ? '<span class="lang-name">' . $current_language['name'] . '</span>'
          : '';

     // Контейнер для dropdown
     $output = '<div class="' . $class . '">';

     // Текущий выбранный язык (кнопка)
     $output .= '<div class="lang-current">' .
          $current_flag .
          $current_name .
          '<span class="lang-arrow">&#9662;</span>' .
          '</div>';

     // Список языков (dropdown)
     $output .= '<ul class="lang-list">';

     foreach ($languages as $language) {
          $flag = $show_flags && !empty($language['flag'])
               ? $language['flag']
               : '';

          $name = $show_names
               ? '<span class="lang-name">' . $language['name'] . '</span>'
               : '';

          $active_class = $language['current_lang'] ? ' active' : '';

          $output .= '<li class="lang-item' . $active_class . '">' .
               '<a href="' . esc_url($language['url']) . '">' .
               $flag .
               $name .
               '</a>' .
               '</li>';
     }

     $output .= '</ul>';
     $output .= '</div>';

     // Добавляем стили
     $output .= '
    <style>
        /* Стили для переключателя языков */
        .' . $class . ' {
            position: relative;
            display: inline-block;
            min-width: 120px;
            font-family: Arial, sans-serif;
            font-size: 14px;
            z-index: 100;
			direction:ltr;
        }
        
        .' . $class . ' .lang-current {
            display: flex;
            align-items: center;
            padding: 3px 12px;
			background-color: #3d3d3d;
			color: white;
            border-radius: 4px;
            cursor: pointer;
            user-select: none;
        }
        
        .' . $class . ' .lang-arrow {
            margin-left: auto;
            font-size: 12px;
            transition: transform 0.2s;
        }
        
        .' . $class . ':hover .lang-arrow {
            transform: rotate(180deg);
        }
        
        .' . $class . ' img {
            margin-right: 8px;
            width: 16px;
            height: 11px;
            display: inline-block;
            vertical-align: middle;
            border: none;
        }
        
        .' . $class . ' .lang-list {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            padding: 0;
            margin: 0;
            list-style: none;
            background-color: #fff;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 4px 4px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: none;
            overflow: hidden;
        }
        
        .' . $class . ':hover .lang-list {
            display: block;
        }
        
        .' . $class . ' .lang-item {
            display: block;
            margin: 0;
            border-bottom: 1px solid #f1f1f1;
        }
        
        .' . $class . ' .lang-item:last-child {
            border-bottom: none;
        }
        
        .' . $class . ' .lang-item a {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            text-decoration: none;
            color: #333;
            transition: background-color 0.2s;
        }
        
        .' . $class . ' .lang-item a:hover {
            background-color: #f9f9f9;
        }
        
        .' . $class . ' .lang-item.active a {
            font-weight: bold;
            background-color: #f0f0f0;
        }
        
        /* Анимация появления списка */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .' . $class . ':hover .lang-list {
            animation: fadeIn 0.2s ease-out;
        }
    </style>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Чтобы переключатель работал и на мобильных устройствах
            var langSwitchers = document.querySelectorAll(".' . $class . '");
            
            langSwitchers.forEach(function(switcher) {
                var current = switcher.querySelector(".lang-current");
                var list = switcher.querySelector(".lang-list");
                var isOpen = false;
                
                // Открытие/закрытие по клику для мобильных устройств
                current.addEventListener("click", function(e) {
                    if (window.innerWidth <= 768) {
                        e.preventDefault();
                        if (isOpen) {
                            list.style.display = "none";
                            isOpen = false;
                        } else {
                            list.style.display = "block";
                            isOpen = true;
                        }
                    }
                });
                
                // Закрытие при клике вне переключателя
                document.addEventListener("click", function(e) {
                    if (!switcher.contains(e.target) && isOpen) {
                        list.style.display = "none";
                        isOpen = false;
                    }
                });
            });
        });
    </script>';

     return $output;
}
add_shortcode('dropdown_language_switcher', 'dropdown_language_switcher_shortcode');

// Добавляем виджет для переключателя языков
class Dropdown_Language_Switcher_Widget extends WP_Widget
{
     public function __construct()
     {
          parent::__construct(
               'dropdown_language_switcher_widget',
               'Dropdown переключатель языков',
               array('description' => 'Виджет для отображения раскрывающегося списка языков')
          );
     }

     public function widget($args, $instance)
     {
          echo $args['before_widget'];

          if (!empty($instance['title'])) {
               echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
          }

          $show_flags = isset($instance['show_flags']) && $instance['show_flags'] ? 'yes' : 'no';
          $show_names = isset($instance['show_names']) && $instance['show_names'] ? 'yes' : 'no';
          $class = !empty($instance['class']) ? $instance['class'] : 'lang-dropdown';

          echo do_shortcode("[dropdown_language_switcher show_flags='{$show_flags}' show_names='{$show_names}' class='{$class}']");

          echo $args['after_widget'];
     }

     public function form($instance)
     {
          $title = isset($instance['title']) ? $instance['title'] : 'Выбор языка';
          $show_flags = isset($instance['show_flags']) ? (bool) $instance['show_flags'] : true;
          $show_names = isset($instance['show_names']) ? (bool) $instance['show_names'] : true;
          $class = isset($instance['class']) ? $instance['class'] : 'lang-dropdown';
          ?>
          <p>
               <label for="<?php echo $this->get_field_id('title'); ?>">Заголовок:</label>
               <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                    name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
          </p>
          <p>
               <input class="checkbox" type="checkbox" <?php checked($show_flags); ?>
                    id="<?php echo $this->get_field_id('show_flags'); ?>"
                    name="<?php echo $this->get_field_name('show_flags'); ?>">
               <label for="<?php echo $this->get_field_id('show_flags'); ?>">Показывать флаги</label>
          </p>
          <p>
               <input class="checkbox" type="checkbox" <?php checked($show_names); ?>
                    id="<?php echo $this->get_field_id('show_names'); ?>"
                    name="<?php echo $this->get_field_name('show_names'); ?>">
               <label for="<?php echo $this->get_field_id('show_names'); ?>">Показывать названия языков</label>
          </p>
          <p>
               <label for="<?php echo $this->get_field_id('class'); ?>">CSS класс:</label>
               <input class="widefat" id="<?php echo $this->get_field_id('class'); ?>"
                    name="<?php echo $this->get_field_name('class'); ?>" type="text" value="<?php echo esc_attr($class); ?>">
          </p>
          <?php
     }

     public function update($new_instance, $old_instance)
     {
          $instance = array();
          $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
          $instance['show_flags'] = isset($new_instance['show_flags']) ? (bool) $new_instance['show_flags'] : false;
          $instance['show_names'] = isset($new_instance['show_names']) ? (bool) $new_instance['show_names'] : false;
          $instance['class'] = (!empty($new_instance['class'])) ? sanitize_text_field($new_instance['class']) : '';
          return $instance;
     }
}

// Регистрируем виджет
function register_dropdown_language_switcher_widget()
{
     register_widget('Dropdown_Language_Switcher_Widget');
}
add_action('widgets_init', 'register_dropdown_language_switcher_widget');


