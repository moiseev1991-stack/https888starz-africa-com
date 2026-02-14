<?php
$epcl_theme = epcl_get_theme_options();
if(function_exists('icl_get_home_url')) $home = icl_get_home_url();
else $home = home_url('/');

$footer_class = '';
if( epcl_get_option('enable_mobile_footer_sidebar') == false ){
    $footer_class = 'no-sidebar';
}
if( epcl_get_option('enable_mobile_footer_sidebar') == true && epcl_get_option('mobile_footer_sidebar') ){
    $footer_class = 'hide-default';
}
?>
<div class="container-games-all-888">
	<div class="continaer-item-all-888-games">
		<div class="continaer-item-all-888-games__caption">
			<?php echo get_translation('Games') ?>
			<a href="/game/" rel="nofollow"><?php echo get_translation('More Games') ?><svg data-v-72c25320="" viewBox="0 0 448 512" class="ico__svg" focusable="false" role="img" data-v-ico="common|chevron-double-right"><path d="M58 38l209 210c5 4 5 12 0 17L58 474c-5 5-13 5-17 0l-20-20c-5-5-5-12 0-17l181-181L21 75c-5-5-5-12 0-17l20-20c4-5 12-5 17 0zm143 0l-20 20c-5 5-5 12 0 17l181 181-181 181c-5 5-5 12 0 17l20 20c4 5 12 5 17 0l209-209c5-5 5-13 0-17L218 38c-5-5-13-5-17 0z"></path></svg></a>
		</div>
		<div class="container-item-all-888-games__line">
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/game-360.webp" alt="Treasure Tomb" class="item-game__image" width="174" height="104">
				<span>Treasure Tomb</span>
			</a>
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/game-371.webp" alt="Crash" class="item-game__image" width="174" height="104">
				<span>Crash</span>
			</a>
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/game-249.webp" alt="Crystal" class="item-game__image" width="174" height="104">
				<span>Crystal</span>
			</a>
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/game-169.webp" alt="Western slot" class="item-game__image" width="174" height="104">
				<span>Western slot</span>
			</a>
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/game-58.webp" alt="21" class="item-game__image" width="174" height="104">
				<span>21</span>
			</a>
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/game-69.webp" alt="Under and over 7" class="item-game__image" width="174" height="104">
				<span>Under and over 7</span>
			</a>
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/game-158.webp" alt="Solitaire" class="item-game__image" width="174" height="104">
				<span>Solitaire</span>
			</a>
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/game-373.webp" alt="Burning Hot" class="item-game__image" width="174" height="104">
				<span>Burning Hot</span>
			</a>
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/game-456.webp" alt="Vampier Curse" class="item-game__image" width="174" height="104">
				<span>Vampier Curse</span>
			</a>
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/game-572.webp" alt="Midgard Zombies" class="item-game__image" width="174" height="104">
				<span>Midgard Zombies</span>
			</a>
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/game-189.webp" alt="Scratch Card" class="item-game__image" width="174" height="104">
				<span>Scratch Card</span>
			</a>
		</div>
		<div class="continaer-item-all-888-games__caption">
			 <?php echo get_translation('Casino')  ?>
			<a href="/game/" rel="nofollow"><?php echo get_translation('More Casino') ?> <svg data-v-72c25320="" viewBox="0 0 448 512" class="ico__svg" focusable="false" role="img" data-v-ico="common|chevron-double-right"><path d="M58 38l209 210c5 4 5 12 0 17L58 474c-5 5-13 5-17 0l-20-20c-5-5-5-12 0-17l181-181L21 75c-5-5-5-12 0-17l20-20c4-5 12-5 17 0zm143 0l-20 20c-5 5-5 12 0 17l181 181-181 181c-5 5-5 12 0 17l20 20c4 5 12 5 17 0l209-209c5-5 5-13 0-17L218 38c-5-5-13-5-17 0z"></path></svg></a>
		</div>
		<div class="container-item-all-888-games__line">
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/img78166.jpg" alt="Little Farm" class="item-game__image" width="174" height="104">
				<span>Little Farm</span>
			</a>
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/img65045.jpg" alt="777" class="item-game__image" width="174" height="104">
				<span>777</span>
			</a>
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/101185.jpg" alt="Fortune" class="item-game__image" width="174" height="104">
				<span>Fortune</span>
			</a>
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/Magic-Pipes_301180.jpg" alt="Magic Pipes" class="item-game__image" width="174" height="104">
				<span>Magic Pipes</span>
			</a>
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/img85493.jpg" alt="Big Wild Cats" class="item-game__image" width="174" height="104">
				<span>Big Wild Cats</span>
			</a>
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/img50962.jpg" alt="Treasure" class="item-game__image" width="174" height="104">
				<span>Treasure</span>
			</a>
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/img50962.jpg" alt="Treasure" class="item-game__image" width="174" height="104">
				<span>Treasure</span>
			</a>
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/img75413.jpg" alt="AirPlane" class="item-game__image" width="174" height="104">
				<span>AirPlane</span>
			</a>
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/img79359.jpg" alt="Sun Goddess" class="item-game__image" width="174" height="104">
				<span>Sun Goddess</span>
			</a>
		</div>
		<div class="continaer-item-all-888-games__caption">
			<?php echo get_translation('Live')  ?> 
			<a href="/game/" rel="nofollow"><?php echo get_translation('More Live')  ?> <svg data-v-72c25320="" viewBox="0 0 448 512" class="ico__svg" focusable="false" role="img" data-v-ico="common|chevron-double-right"><path d="M58 38l209 210c5 4 5 12 0 17L58 474c-5 5-13 5-17 0l-20-20c-5-5-5-12 0-17l181-181L21 75c-5-5-5-12 0-17l20-20c4-5 12-5 17 0zm143 0l-20 20c-5 5-5 12 0 17l181 181-181 181c-5 5-5 12 0 17l20 20c4 5 12 5 17 0l209-209c5-5 5-13 0-17L218 38c-5-5-13-5-17 0z"></path></svg></a>
		</div>
		<div class="container-item-all-888-games__line">
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/img94769.jpg" alt="Top Card" class="item-game__image" width="174" height="104">
				<span>Top Card</span>
			</a>
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/img55372.jpg" alt="Mega Wheel" class="item-game__image" width="174" height="104">
				<span>Mega Wheel</span>
			</a>
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/img97191.jpg" alt="Football Auto Roulette" class="item-game__image" width="174" height="104">
				<span>Football Auto Roulette</span>
			</a>
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/img80299.jpg" alt="Cabaret Roulette" class="item-game__image" width="174" height="104">
				<span>Cabaret Roulette</span>
			</a>
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/img32973.jpg" alt="Dragon Tiger D60" class="item-game__image" width="174" height="104">
				<span>Dragon Tiger D60</span>
			</a>
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/img67366.jpg" alt="Speed Baccarat C08" class="item-game__image" width="174" height="104">
				<span>Speed Baccarat C08</span>
			</a>
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/img106494.jpg" alt="Oasis Blackjack" class="item-game__image" width="174" height="104">
				<span>Oasis Blackjack</span>
			</a>
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/129845.jpg" alt="Horse racing Auto Roulette" class="item-game__image" width="174" height="104">
				<span>Horse racing Auto Roulette</span>
			</a>
		</div>
		<div class="continaer-item-all-888-games__caption">
			<?php echo get_translation('TV Games')  ?>
			<a href="/game/" rel="nofollow"><?php echo get_translation('More TV Games')  ?> <svg data-v-72c25320="" viewBox="0 0 448 512" class="ico__svg" focusable="false" role="img" data-v-ico="common|chevron-double-right"><path d="M58 38l209 210c5 4 5 12 0 17L58 474c-5 5-13 5-17 0l-20-20c-5-5-5-12 0-17l181-181L21 75c-5-5-5-12 0-17l20-20c4-5 12-5 17 0zm143 0l-20 20c-5 5-5 12 0 17l181 181-181 181c-5 5-5 12 0 17l20 20c4 5 12 5 17 0l209-209c5-5 5-13 0-17L218 38c-5-5-13-5-17 0z"></path></svg></a>
		</div>
		<div class="container-item-all-888-games__line">
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/img71534.jpg" alt="Fast Keno" class="item-game__image" width="174" height="104">
				<span>Fast Keno</span>
			</a>
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/img61608.jpg" alt="Wheel" class="item-game__image" width="174" height="104">
				<span>Wheel</span>
			</a>
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/img61609.jpg" alt="Poker" class="item-game__image" width="174" height="104">
				<span>Poker</span>
			</a>
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/47521.jpg" alt="TVBET" class="item-game__image" width="174" height="104">
				<span>TVBET</span>
			</a>
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/img61611.jpg" alt="War" class="item-game__image" width="174" height="104">
				<span>War</span>
			</a>
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/img61612.jpg" alt="5Bet" class="item-game__image" width="174" height="104">
				<span>5Bet</span>
			</a>
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/img61613.jpg" alt="7Bet" class="item-game__image" width="174" height="104">
				<span>7Bet</span>
			</a>
			<a href="/game/" rel="nofollow" class="line__item-game">
				<img src="/wp-content/uploads/2025/03/img61615.jpg" alt="Keno" class="item-game__image" width="174" height="104">
				<span>Keno</span>
			</a>
		</div>
		
	</div>
</div>
<!-- start: #footer -->
<footer id="footer" class="<?php echo esc_attr($footer_class); ?>">
			<div class="desktop-version" dir="rtl">
				<nav class="navigation-footer">
					<div class="block-ul-footer">
						<strong>حول الموقع</strong>
						<a href="/about/">من نحن</a>
						<a href="/game/" target="_blank">برنامج الشراكة</a>
						<a href="/contacts/">معلومات الاتصال</a>
						<a href="/terms/">شروط الاستخدام</a>
						<a href="/responsible/">اللعب المسؤول</a>
						<a href="/privacy-policy/">الخصوصية وإدارة البيانات</a>
						<a href="/game/">سياسة ملفات تعريف الارتباط</a>
						<a href="/game/">سياسة مكافحة غسل الأموال</a>
						<a href="/game/">سياسة اعرف عميلك</a>
						<a href="/self-exclusion/">الاستبعاد الذاتي</a>
						<a href="/dispute-resolution/">حل النزاعات</a>
						<a href="/fairness-rng-testing-methods/">طرق اختبار النزاهة والعشوائية</a>
						<a href="/accounts-withdrawals-and-bonuses/">الحسابات والمدفوعات والمكافآت</a>
						<a href="/promo-code/">عرض الرمز الترويجي</a>
						<a href="/registration/">التسجيل</a>
						<a href="/apk/">تحميل التطبيق (APK)</a>
					</div>
			<div class="block-ul-footer">
   <strong>الرهان</strong>
   <a href="/game/">الرياضة</a>
   <a href="/game/">مباشر</a>
   <a href="/game/">كرة القدم</a>
   <a href="/game/">الكريكيت</a>
   <a href="/game/">الرياضات الإلكترونية</a>
   <a href="/game/">هوكي الجليد</a>
   <a href="/game/">التنس</a>
</div>
<div class="block-ul-footer">
   <strong>الألعاب</strong>
   <a href="/game/">ألعاب السلوت</a>
   <a href="/game/">ألعاب 888</a>
   <a href="/game/">كازينو مباشر</a>
   <a href="/game/">الرياضات الافتراضية</a>
   <a href="/game/">بينغو</a>
   <a href="/game/">ألعاب التلفزيون</a>
</div>
<div class="block-ul-footer">
   <strong>الإحصائيات</strong>
   <a href="/game/">إحصائيات</a>
   <a href="/game/">النتائج</a>
</div>
<div class="block-ul-footer">
   <strong>روابط مفيدة</strong>
   <a href="/game/">طرق الدفع</a>
   <a href="/game/">النسخة المحمولة</a>
   <a href="/registration/">التسجيل</a>
   <a href="/promo-code/">الرمز الترويجي</a>
   <a href="/game/">اكسب مع 888starz</a>
</div>
			<div class="block-ul-footer">
				<strong>التطبيقات</strong>
				<a href="/apk/" class="link-footer-aps">صفحة التطبيق (APK)</a>
				<a href="/888starz.apk" class="link-footer-aps" download aria-label="تحميل تطبيق أندرويد"><svg data-v-06e51396="" viewBox="0 0 16 16" class="ico__svg" focusable="false" role="img">
						<path d="M11.68 10.26a.63.63 0 0 1-.37-.12.7.7 0 0 1-.24-.32.77.77 0 0 1 .14-.79c.1-.1.21-.16.34-.2a.62.62 0 0 1 .39.05c.12.05.22.15.3.26a.76.76 0 0 1-.09.9.64.64 0 0 1-.47.22Zm-7.36 0a.63.63 0 0 1-.37-.12.7.7 0 0 1-.25-.32.77.77 0 0 1 .15-.79c.09-.1.2-.16.34-.2a.62.62 0 0 1 .38.05c.13.05.23.15.3.26a.76.76 0 0 1-.08.9.64.64 0 0 1-.47.22Zm7.6-4.32 1.33-2.48a.31.31 0 0 0 .04-.23.31.31 0 0 0-.04-.1.29.29 0 0 0-.09-.09.26.26 0 0 0-.32.03.3.3 0 0 0-.07.1l-1.35 2.5a7.88 7.88 0 0 0-6.84 0l-1.35-2.5a.3.3 0 0 0-.07-.1.26.26 0 0 0-.32-.03.29.29 0 0 0-.09.08.3.3 0 0 0-.05.23l.05.11 1.33 2.48A8.51 8.51 0 0 0 0 12.71h16a8.51 8.51 0 0 0-4.08-6.77Z">
						</path>
					</svg>أندرويد</a>
			</div>
		</nav>
		<nav class="navigation-footer">
			<div class="container-copyrithing">
			Copyright © 2020 - 2025 «888starz».</div>
			<div class="container-social">
				<div class="container-social-media">
					<a href="https://twitter.com/888Tron" class="link-social-footer" target="_blank" rel="noopener noreferrer" aria-label="Twitter">
						<svg data-v-06e51396="" viewBox="0 0 16 16" class="ico__svg" focusable="false" role="img">
							<path d="M12.6 0h2.45L9.7 6.78 16 16h-4.94L7.2 10.4 2.77 16H.32l5.73-7.25L0 0h5.06l3.5 5.11L12.6 0Zm-.86 14.38h1.36L4.32 1.54H2.87l8.87 12.84Z">
							</path>
						</svg>
					</a>
					<a href="https://www.instagram.com/888starz_en/" class="link-social-footer" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
						<svg data-v-06e51396="" viewBox="0 0 16 16" class="ico__svg" focusable="false" role="img">
							<path d="M10.65 8a2.65 2.65 0 1 1-5.3 0 2.65 2.65 0 0 1 5.3 0Z"></path>
							<path d="M12.47 0H3.53A3.53 3.53 0 0 0 0 3.53v8.94A3.53 3.53 0 0 0 3.53 16h8.94A3.53 3.53 0 0 0 16 12.47V3.53A3.53 3.53 0 0 0 12.47 0ZM8 12.41a4.42 4.42 0 1 1 0-8.83 4.42 4.42 0 0 1 0 8.83Zm5.06-8.59a.88.88 0 1 1 0-1.76.88.88 0 0 1 0 1.76Z">
							</path>
						</svg>
					</a>
					<a href="https://www.facebook.com/888starzEN/" class="link-social-footer" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
						<svg data-v-06e51396="" viewBox="0 0 16 16" class="ico__svg" focusable="false" role="img">
							<path clip-rule="evenodd" d="M11.14 8H8.9v8H5.58V8H4V5.19h1.58V3.35C5.58 2.05 6.2 0 8.93 0h2.47v2.75H9.6a.68.68 0 0 0-.7.77v1.66h2.53l-.29 2.83Z">
							</path>
						</svg>
					</a>
					<a href="https://t.me/starz_official" class="link-social-footer" target="_blank" rel="noopener noreferrer" aria-label="Telegram">
						<svg data-v-06e51396="" viewBox="0 0 16 16" class="ico__svg" focusable="false" role="img">
							<path clip-rule="evenodd" d="m3.85 9.25 1.99 5.04 2.6-2.62 4.44 3.57L16 1 0 7.75l3.85 1.5Zm7.58-4.16-4.9 4.52-.61 2.33-1.13-2.85 6.64-4Z">
							</path>
						</svg>
					</a>
				</div>
				<div class="contaienr-18plus">
					18+
				</div>
			</div>
		</nav>
	</div>
	
	<div class="mobile-version">
		<div class="caption-seo-module faq-question">
			<?php echo get_field('заголовок_для_seo_футера') ?>
			<svg viewBox="0 0 32 19" focusable="false" role="img" class="ico_rotater_footer"><path d="M15.2 18.6.4 4c-.5-.5-.5-1.1 0-1.6l1.9-2c.5-.5 1.3-.5 1.7 0l12 11.8L28 .4c.4-.5 1.2-.5 1.7 0l2 2c.4.5.4 1 0 1.6L17 18.6c-.5.5-1.3.5-1.7 0Z"></path></svg>
		</div>
		<div class="seo-module faq-answer" id="seo-module" style="display: none;">
			<div class="seo-module-column-1">
				<?php echo get_field('footer_column_1_seo') ?>
			</div>
			<div class="seo-module-column-2">
			  <?php echo get_field('footer_column_2_seo') ?>
			</div>
		</div>
		<div class="container-mobile-setion-app">
			<a href="/888starz.apk" rel="nofollow" class="link-game-button-footer">
				<div class="download-app-widget__stores download-app-widget-stores">
					<img alt="888starz" src="/wp-content/uploads/2025/03/image_2025-03-05_22-08-13-1.svg" class="download-app-android">
				</div>
				<div data-v-b7a9987b="" class="download-app-widget__description download-app-widget-description">
					<img alt="888starz" src="/wp-content/uploads/2025/03/12e2ff49a51305fa9bc4edc93e44442f.svg" class="download-app-widget-description__logo">
					<span  class="download-app-widget-description__title">Mobile application</span>
				</div>
			</a>
		</div>
		<div class="container-mobile-footer-social">
			<a href="https://t.me/starz_official" class="container-mobile-item-social">
				<svg width="19px" height="19px" viewBox="0 0 16 16" class="ico__svg" focusable="false" role="img">
							<path d="M12.6 0h2.45L9.7 6.78 16 16h-4.94L7.2 10.4 2.77 16H.32l5.73-7.25L0 0h5.06l3.5 5.11L12.6 0Zm-.86 14.38h1.36L4.32 1.54H2.87l8.87 12.84Z">
							</path>
				</svg>
			</a>
			<a href="https://www.facebook.com/888starzEN/" class="container-mobile-item-social">
				<svg width="19px" height="19px" viewBox="0 0 16 16" class="ico__svg" focusable="false" role="img">
							<path d="M10.65 8a2.65 2.65 0 1 1-5.3 0 2.65 2.65 0 0 1 5.3 0Z"></path>
							<path d="M12.47 0H3.53A3.53 3.53 0 0 0 0 3.53v8.94A3.53 3.53 0 0 0 3.53 16h8.94A3.53 3.53 0 0 0 16 12.47V3.53A3.53 3.53 0 0 0 12.47 0ZM8 12.41a4.42 4.42 0 1 1 0-8.83 4.42 4.42 0 0 1 0 8.83Zm5.06-8.59a.88.88 0 1 1 0-1.76.88.88 0 0 1 0 1.76Z">
							</path>
				</svg>
			</a>
			<a href="https://www.instagram.com/888starz_en/" class="container-mobile-item-social">
				<svg width="19px" height="19px" viewBox="0 0 16 16" class="ico__svg" focusable="false" role="img">
							<path clip-rule="evenodd" d="M11.14 8H8.9v8H5.58V8H4V5.19h1.58V3.35C5.58 2.05 6.2 0 8.93 0h2.47v2.75H9.6a.68.68 0 0 0-.7.77v1.66h2.53l-.29 2.83Z">
							</path>
				</svg>
			</a>
			<a href="https://twitter.com/888Tron" class="container-mobile-item-social">
				<svg width="19px" height="19px" viewBox="0 0 16 16" class="ico__svg" focusable="false" role="img">
							<path clip-rule="evenodd" d="m3.85 9.25 1.99 5.04 2.6-2.62 4.44 3.57L16 1 0 7.75l3.85 1.5Zm7.58-4.16-4.9 4.52-.61 2.33-1.13-2.85 6.64-4Z">
							</path>
				</svg>
			</a>
		</div>
		<div class="container-copyrithing-mobile-footer">
			<p class="read-copy">
				Copyright © 2020 - 2025 «888starz».
			</p>
			<p class="read-desc-copy">
			يستخدم 888starz ملفات تعريف الارتباط لضمان حصولك على أفضل تجربة استخدام. من خلال بقائك على الموقع الإلكتروني، فإنك توافق على استخدام ملفات تعريف الارتباط الخاصة بك على 888starz.<a href="/cookies/">سياسة ملفات تعريف الارتباط</a>
			
			</p>
			<div class="container-horizontal-info">
				<p class="p18plus">
					+18
				</p>
				<?php echo do_shortcode("[dropdown_language_switcher]"); ?>
			</div>
		</div>
	</div>
	
    <?php if( epcl_get_option('enable_footer_widgets', true) ): ?>
        <?php if( is_active_sidebar('epcl_sidebar_footer')  || is_active_sidebar( epcl_get_option('mobile_footer_sidebar') ) ): ?>
            <div class="widgets grid-container">
                <div class="hide-on-mobile hide-on-tablet default-sidebar"><?php dynamic_sidebar('epcl_sidebar_footer'); ?></div>
                <div class="clear"></div>
                <?php if( epcl_get_option('enable_mobile_footer_sidebar') == true && epcl_get_option('mobile_footer_sidebar') ): ?>
                    <div class="mobile-sidebar hide-on-desktop"><?php dynamic_sidebar( epcl_get_option('mobile_footer_sidebar') ); ?></div>
                <?php endif; ?>                
                <div class="clear"></div>                      
            </div>
        <?php endif; ?>
    <?php endif; ?>  
    
    <?php if( epcl_get_option('copyright_text') ): ?>
        <div class="published underline-effect">
            <?php echo wp_kses_post( do_shortcode($epcl_theme['copyright_text']) ); ?>
        </div>
    <?php endif; ?>

    <?php if( epcl_get_option('copyright_theme_author', true) && function_exists('epcl_render_theme_author') ): ?>
        <?php 
        $class = '';
        if( !epcl_get_option('copyright_text') ) $class = 'no-margin-top';
        ?>
        <?php echo epcl_render_theme_author( $class ); ?>
    <?php endif; ?>
    
    <?php if( empty($epcl_theme) || $epcl_theme['enable_back_to_top'] == '1' ): ?>
        <span id="back-to-top" class="epcl-button">
            <svg class="icon large" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m12 5l6 6m-6-6l-6 6m6-6v14"/>
            </svg>
        </span>
    <?php endif; ?>

    <div class="clear"></div>

</footer>

<!-- end: #footer -->