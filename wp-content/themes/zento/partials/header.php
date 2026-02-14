<?php
$epcl_theme = epcl_get_theme_options();
if( function_exists('icl_get_home_url') ) $home = icl_get_home_url();
else $home = home_url('/');
// Just demo purposes
if( isset($_GET['header']) ){
	$header_type = sanitize_text_field( $_GET['header'] );
	switch($header_type){
		default:
			$epcl_theme['header_type'] = 'compact';
		break;
        case 'minimalist':
			$epcl_theme['header_type'] = 'minimalist';
		break;
		case 'classic':
			$epcl_theme['header_type'] = 'classic';
		break;
		case 'notice':
			$epcl_theme['enable_notice'] = true;
        break;
        case 'advertising':
            $epcl_theme['header_type'] = 'advertising';
		break;
	}
}

// Only if theme options data has been created
$header_class = '';
if( !empty( $epcl_theme ) ){
    $header_class = $epcl_theme['header_type'];
    if( isset( $epcl_theme['enable_sticky_header'] ) && $epcl_theme['enable_sticky_header'] != false ){
        $header_class .=' enable-sticky';
    }
    if( epcl_get_option('enable_sticky_header_mobile', true) == false ){
        $header_class .=' disable-sticky-mobile';
    }
    if( isset($epcl_theme['sticky_logo_image']['url'] ) && $epcl_theme['sticky_logo_image']['url'] ){
        $header_class .=' has-sticky-logo'; 
    }
    if( isset($epcl_theme['enable_search_header']) && $epcl_theme['enable_search_header'] == '1'  ){
        add_filter('wp_nav_menu_items','epcl_search_nav_item', 10, 2);
    }
}else{
    $header_class .= 'compact';
}

?>

<?php get_template_part('partials/header/notice-text'); ?>

<!-- start: #header -->
<header id="header" class="<?php echo esc_attr($header_class); ?>">
		<div class="container-header-desc">
		<a href="#" class="logo-img"><?php get_template_part('partials/header/site-logo'); ?></a>
		<div class="container-header-desc__container-right-side-header">
			<div class="container-right-side-header__top">
				<div class="top__left-header">
					<a href="/game/" class="left-header__link-game-top-games crystal-games-header" rel="nofollow">
						<img src="/wp-content/uploads/2025/01/game-249-animation.svg" alt="Crystal">
						<span>Crystal</span>
					</a>
					<a href="/game/" class="left-header__link-game-top-games" rel="nofollow">
						<img src="/wp-content/uploads/2025/01/740a96629ce88bbf075e4e3003a3ff1e.svg" alt="Aviator">
					</a>
					<a href="/game/" class="left-header__link-game-top-games" rel="nofollow">
						<img src="/wp-content/uploads/2025/01/3ca73a69e5275a2051000fb92ce2e288.webp" alt="League Champions">
					</a>
					<a href="/game/" class="left-header__link-game-top-games" rel="nofollow">
						<img src="/wp-content/uploads/2025/01/dd6d3a947117b4b8e79c5159c091ada3.webp" alt="League Champions">
					</a>
					<a href="/game/" class="left-header__link-game-top-games" rel="nofollow">
						<img src="/wp-content/uploads/2025/01/e9c65966664d9c2e4e0ba094a0760d40.svg" alt="Premier-league">
					</a>
					<a href="/game/" class="left-header__link-game-top-games" rel="nofollow">
						<img src="/wp-content/uploads/2025/01/ef82153ad04426601aca85827938d210.svg" alt="La Liga">
					</a>
					<a href="/game/" class="left-header__link-game-top-games" rel="nofollow">
						<img src="/wp-content/uploads/2025/01/a88c2145994b996d246eade556be38b0.svg" alt="Seria A">
					</a>
				</div>
				<div class="top__right-header">
					<a href="/game/" class="link-game-square" rel="nofollow">
						<svg data-v-1655dfd0="" viewBox="0 0 16 16" class="ico__svg" focusable="false" role="img" data-v-ico="common|handshake-circle-dollar"><path d="M7.99 0a3.99 3.99 0 1 1 0 7.98 3.99 3.99 0 0 1 0-7.98ZM6.85 5.76c.16.15.3.28.49.37.1.04.2.08.3.1l.02.26a.35.35 0 0 0 .69-.02v-.24A1.31 1.31 0 0 0 9.4 4.97c0-.77-.69-1.31-1.42-1.31-.6 0-.99-.62-.53-1.03.31-.28.99-.21 1.2.1a.35.35 0 0 0 .57-.39 1.31 1.31 0 0 0-.54-.42 2.2 2.2 0 0 0-.36-.13v-.3a.35.35 0 1 0-.7.02v.27c-.59.14-1.06.64-1.06 1.27 0 .76.69 1.3 1.42 1.3.35 0 .72.24.72.62 0 .37-.37.6-.72.6a.73.73 0 0 1-.34-.07c-.1-.05-.2-.13-.32-.24a.35.35 0 0 0-.48.5Z"></path><path d="m13.28 8.01-.84.19a.52.52 0 0 0-.42.62l1.06 4.35c.06.28.36.45.65.39l.85-.19a.5.5 0 0 0 .4-.62L13.95 8.4a.55.55 0 0 0-.66-.39Zm-10.56 0 .84.19c.3.06.48.34.42.62l-1.06 4.35a.55.55 0 0 1-.65.39l-.85-.19a.52.52 0 0 1-.4-.62L2.05 8.4a.55.55 0 0 1 .66-.39Zm9.93 5.13-.63.26-2.63-2.5a1.44 1.44 0 0 0-1.77-.15c-.43.28-.86.53-1.26.59a1.1 1.1 0 0 1-.97-.32c.43-.37.83-.82 1.2-1.23.5-.57.95-1.07 1.32-1.2.56-.2.97.02 1.81.5l.7.37c.38.2.81.23 1.22.06l.12-.05.89 3.67Z"></path><path d="m6.28 9.54.44-.5H4.34l-.94 3.88 3.14 2.94c.2.19.53.19.72 0 .2-.19.2-.49.01-.68l-.82-.78a.19.19 0 0 1 0-.27c.08-.08.2-.08.29 0L8.5 15.8c.2.19.53.19.72 0 .2-.18.2-.49.01-.68L7.62 13.6a.19.19 0 0 1 0-.28c.08-.07.2-.07.29 0l2.17 2.06c.2.19.52.19.72 0 .2-.18.2-.5 0-.68l-2.1-2a.19.19 0 0 1 0-.27c.08-.08.2-.08.29 0L11 14.35c.2.18.51.18.7 0 .2-.2.24-.48.02-.69L9.1 11.18a1.02 1.02 0 0 0-1.25-.11c-.47.3-.94.58-1.42.65-.51.08-1-.06-1.47-.59a.19.19 0 0 1 .01-.28c.47-.37.9-.86 1.3-1.31Z"></path></svg>
					</a>
					<a href="/game/" class="link-game-square" rel="nofollow">
						<svg data-v-1655dfd0="" viewBox="0 0 288 512" class="ico__svg" focusable="false" role="img" data-v-ico="common|dollar-sign"><path d="M209 233l-108-31c-12-4-21-15-21-28 0-17 13-30 30-30h66c12 0 24 4 34 11 6 4 14 3 20-2l34-34c7-7 6-19-1-25-25-19-56-30-87-30V16c0-9-7-16-16-16h-32c-9 0-16 7-16 16v48h-2C46 64-5 119 1 184c4 46 39 83 83 96l103 30c12 4 21 15 21 28 0 17-13 30-29 30h-67c-12 0-24-4-34-10-6-5-14-4-19 2l-35 34c-7 6-6 18 2 24 24 19 55 30 86 30v48c0 9 7 16 16 16h32c9 0 16-7 16-16v-48c47-1 90-29 106-73 21-61-15-125-73-142z"></path></svg>
					</a>
					<a href="/game/" class="link-game-square" rel="nofollow">
						<svg data-v-1655dfd0="" viewBox="0 0 512 512" class="ico__svg" focusable="false" role="img" data-v-ico="common|bonus"><path d="M479 100h-38a66 66 0 00-30-93c-24-12-51-7-71 11l-47 42a50 50 0 00-74 0l-47-42a65 65 0 00-71-12 66 66 0 00-40 68c1 9 5 18 10 26H33c-18 0-33 15-33 33v50c0 9 7 17 17 17h478c10 0 17-8 17-17v-50c0-18-15-33-33-33zm-273-6v6h-79a33 33 0 1122-58l57 52zm211-24c-1 17-17 30-34 30h-77v-6l56-51c8-8 20-11 31-8 16 5 25 18 24 35zM33 233v245c0 19 15 34 34 34h156V233H33zm256 0v279h156c19 0 34-15 34-34V233H289z"></path></svg>
					</a>
					<a href="/game/" class="link-game-button" rel="nofollow">
						<?php echo get_translation('Registration') ?>
					</a>
					<a href="/game/" class="link-game-button" rel="nofollow">
						<?php echo get_translation('Log in') ?>
					</a>
					<div class="open-menu">
						<?php echo do_shortcode("[dropdown_language_switcher]"); ?>
					</div>
					

					
				</div>
				<div class="mobile-button-top-right-header-888">
						<a href="/game/" class="link-game-square" rel="nofollow">
							<svg data-v-1655dfd0="" viewBox="0 0 512 512" class="ico__svg" focusable="false" role="img" data-v-ico="common|bonus"><path d="M479 100h-38a66 66 0 00-30-93c-24-12-51-7-71 11l-47 42a50 50 0 00-74 0l-47-42a65 65 0 00-71-12 66 66 0 00-40 68c1 9 5 18 10 26H33c-18 0-33 15-33 33v50c0 9 7 17 17 17h478c10 0 17-8 17-17v-50c0-18-15-33-33-33zm-273-6v6h-79a33 33 0 1122-58l57 52zm211-24c-1 17-17 30-34 30h-77v-6l56-51c8-8 20-11 31-8 16 5 25 18 24 35zM33 233v245c0 19 15 34 34 34h156V233H33zm256 0v279h156c19 0 34-15 34-34V233H289z"></path></svg>
						</a>
						<a href="/game/" class="link-game-button" rel="nofollow">
							 <?php echo get_translation('Log in') ?>
						</a>
						<a href="/game/" class="link-game-button reg-button-header-mobile" rel="nofollow">
							<?php echo get_translation('Registration') ?>
						</a>
					</div>
			</div>
			<div class="container-right-side-header__bottom">

				
                <?php  if( has_nav_menu('epcl_header') ): ?>

					<!-- start: .main-nav -->
					<nav class="main-nav bottom__main-navigate">
						<?php
						$args = array(
							'theme_location' => 'epcl_header',
							'container' => false,
							'menu_class' => 'menu underline-effect',
						);
						wp_nav_menu($args);
						?>
						<?php if( function_exists('epcl_render_header_social_buttons') ): ?>
							<?php epcl_render_header_social_buttons(); ?>
						<?php endif; ?>
						<ul id="menu-header-english-2" class="menu underline-effect">
						  <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-666696">
							<a href="/game/" rel="nofollow">
								<svg width="20px" height="16px" viewBox="0 0 576 512" class="ico__svg" focusable="false" role="img" style="fill: white;"><path d="M259.3 17.8L194 150.2 47.9 171.5a32 32 0 00-17.7 54.6l105.7 103-25 145.5a32 32 0 0046.4 33.7L288 439.6l130.7 68.7a32 32 0 0046.4-33.7l-25-145.5 105.7-103a32 32 0 00-17.7-54.6L382 150.2 316.7 17.8a32 32 0 00-57.4 0z"></path></svg>
								<?php echo get_translation('Bonuses') ?>
							</a>
							<ul class="sub-menu header-navigation-promo">
							  <li class="header-navigation-promo__item">
								<a href="/game/" rel="nofollow" class="header-navigation-promo__link header-navigation-promo-link">
								  <span class="ico--flame ico header-navigation-promo-link__ico" aria-hidden="true">
									<svg viewBox="0 0 32 32" class="ico__svg" focusable="false" role="img">
									  <path d="M28 18.7c-.5-3.8-2-7.5-4.6-10.4C21.6 6 20 3.8 20 .7a.7.7 0 00-1-.6 17 17 0 00-6.3 9c-.5 2.3-.7 4.6-.7 6.8-2.7-.6-3.3-4.6-3.3-4.7a.7.7 0 00-1-.5c-.1.1-3.5 1.8-3.7 8.6v.7a12 12 0 0012 12 12 12 0 0012-13.3zm-12 12a4.1 4.1 0 01-4-4.3V26c0-.7.1-1.4.4-2a2.4 2.4 0 002.2 1.5.7.7 0 00.7-.6c0-1 0-2 .2-3 .3-1 .8-1.9 1.4-2.6.3.9.8 1.7 1.4 2.5 1 1.2 1.5 2.6 1.7 4.1v.4a4.1 4.1 0 01-4 4.3z"></path>
									</svg>
								  </span>
								  <span class="header-navigation-promo-link__info">
									<span class="ui-tag ui-tag--size-xs ui-tag--theme-accent ui-tag--rounded-top">جديد</span>
								  </span>
								  <span class="header-navigation-promo-link__title">100% للإيداع الأول</span>
								</a>
							  </li>
							  <li class="header-navigation-promo__item">
								<a href="/game/" rel="nofollow" class="header-navigation-promo__link header-navigation-promo-link">
								  <span class="ico--dollar-round ico header-navigation-promo-link__ico" aria-hidden="true">
									<svg viewBox="0 0 32 32" class="ico__svg" focusable="false" role="img">
									  <path d="M16 0a16 16 0 100 32 16 16 0 000-32zm4.1 21.7c-.6.7-1.4 1.3-2.4 1.5a.7.7 0 00-.5.8v1.2a.5.5 0 01-.6.6h-1.4a.5.5 0 01-.6-.6v-1c0-.6 0-.6-.7-.8a9 9 0 01-2.7-.8.7.7 0 01-.2-1l.4-1.3c.2-.6.3-.6.8-.4a8 8 0 002.7.8c.6.1 1.2 0 1.8-.2A1.4 1.4 0 0017 18c-.3-.3-.6-.5-1-.6l-2.8-1.3a3.9 3.9 0 01-2.2-3.7 4.1 4.1 0 013-3.8c.8-.3.8-.3.8-1v-.8c0-.6.1-.7.7-.7h.5c1.2 0 1.2 0 1.3 1.2 0 .9 0 .9.8 1a7 7 0 012.1.7.6.6 0 01.2.6l-.5 1.6c-.1.5-.3.6-.8.4a6 6 0 00-3-.6l-.8.1a1.2 1.2 0 00-.3 2c.4.4.9.6 1.3.8l2.4 1.1a4.3 4.3 0 011.4 6.6z"></path>
									</svg>
								  </span>
								  <span class="header-navigation-promo-link__info">
									<span class="ui-tag ui-tag--size-xs ui-tag--theme-accent ui-tag--rounded-top">جديد</span>
								  </span>
								  <span class="header-navigation-promo-link__title">كازينو كاش باك VIP</span>
								</a>
							  </li>
							  <li class="header-navigation-promo__item">
								<a href="/game/" rel="nofollow" class="header-navigation-promo__link header-navigation-promo-link">
								  <span class="ico--cherry ico header-navigation-promo-link__ico" aria-hidden="true">
									<svg viewBox="0 0 32 32" class="ico__svg" focusable="false" role="img">
									  <path d="M8.5 9.4c1.4 0 2.7-.4 3.7-1-1 2-1.7 4.4-1.8 6.8-4.2.4-7.5 4-7.5 8.3 0 4.7 3.8 8.5 8.4 8.5 1.4 0 2.8-.4 4-1a10.4 10.4 0 010-14.9c-.9-.5-2-.8-3-1 .4-6.6 5.4-12 11.9-13-1.7 2.2-2.6 5-2.6 7.9v5.2c-4.2.4-7.5 4-7.5 8.3 0 4.7 3.8 8.5 8.5 8.5 4.6 0 8.4-3.8 8.4-8.5 0-4.3-3.3-7.9-7.5-8.3V10c0-3.2 1.2-6.2 3.5-8.4L28.6 0h-2.3C22 0 18 1.8 15 4.7a9.8 9.8 0 00-6.5-2.8c-4 0-6.7 3-6.8 3l-.7.7.7.7c.1.1 2.8 3 6.8 3zm14 11.3a2.8 2.8 0 00-2.8 2.8H18c0-2.6 2-4.7 4.7-4.7v1.9zm-11.2 0a2.8 2.8 0 00-2.8 2.8H6.6c0-2.6 2.1-4.7 4.7-4.7v1.9z"></path>
									</svg>
								  </span>
								  <span class="header-navigation-promo-link__info">
									<span class="ui-tag ui-tag--size-xs ui-tag--theme-accent ui-tag--rounded-top">جديد</span>
								  </span>
								  <span class="header-navigation-promo-link__title">باقة ترحيبية</span>
								</a>
							  </li>
							  <li class="header-navigation-promo__item">
								<a href="/game/" rel="nofollow" class="header-navigation-promo__link header-navigation-promo-link">
								  <span class="ico--flame ico header-navigation-promo-link__ico" aria-hidden="true">
									<svg viewBox="0 0 32 32" class="ico__svg" focusable="false" role="img">
									  <path d="M28 18.7c-.5-3.8-2-7.5-4.6-10.4C21.6 6 20 3.8 20 .7a.7.7 0 00-1-.6 17 17 0 00-6.3 9c-.5 2.3-.7 4.6-.7 6.8-2.7-.6-3.3-4.6-3.3-4.7a.7.7 0 00-1-.5c-.1.1-3.5 1.8-3.7 8.6v.7a12 12 0 0012 12 12 12 0 0012-13.3zm-12 12a4.1 4.1 0 01-4-4.3V26c0-.7.1-1.4.4-2a2.4 2.4 0 002.2 1.5.7.7 0 00.7-.6c0-1 0-2 .2-3 .3-1 .8-1.9 1.4-2.6.3.9.8 1.7 1.4 2.5 1 1.2 1.5 2.6 1.7 4.1v.4a4.1 4.1 0 01-4 4.3z"></path>
									</svg>
								  </span>
								  <span class="header-navigation-promo-link__info">
									<span class="ui-tag ui-tag--size-xs ui-tag--theme-accent ui-tag--rounded-top">جديد</span>
								  </span>
								  <span class="header-navigation-promo-link__title">مكافأة 50% يوم الثلاثاء</span>
								</a>
							  </li>
							  <li class="header-navigation-promo__item">
								<a href="/game/" rel="nofollow" class="header-navigation-promo__link header-navigation-promo-link">
								  <span class="ico--flame ico header-navigation-promo-link__ico" aria-hidden="true">
									<svg viewBox="0 0 32 32" class="ico__svg" focusable="false" role="img">
									  <path d="M28 18.7c-.5-3.8-2-7.5-4.6-10.4C21.6 6 20 3.8 20 .7a.7.7 0 00-1-.6 17 17 0 00-6.3 9c-.5 2.3-.7 4.6-.7 6.8-2.7-.6-3.3-4.6-3.3-4.7a.7.7 0 00-1-.5c-.1.1-3.5 1.8-3.7 8.6v.7a12 12 0 0012 12 12 12 0 0012-13.3zm-12 12a4.1 4.1 0 01-4-4.3V26c0-.7.1-1.4.4-2a2.4 2.4 0 002.2 1.5.7.7 0 00.7-.6c0-1 0-2 .2-3 .3-1 .8-1.9 1.4-2.6.3.9.8 1.7 1.4 2.5 1 1.2 1.5 2.6 1.7 4.1v.4a4.1 4.1 0 01-4 4.3z"></path>
									</svg>
								  </span>
								<span class="header-navigation-promo-link__info">
									<span class="ui-tag ui-tag--size-xs ui-tag--theme-accent ui-tag--rounded-top">جديد</span>
								  </span>
								  <span class="header-navigation-promo-link__title">100٪ مكافأة على الألعاب الرياضية</span>
								</a>
							  </li>
							  <ul id="menu-header-english-3" class="menu underline-effect">
									<li><a href="/game/" rel="nofollow">العروض الترويجية والمكافآت</a></li>  
									<li><a href="/game/" rel="nofollow">عرض الرمز الترويجي</a></li>  
									<li><a href="/game/" rel="nofollow">ألعاب المكافآت</a></li>  
									<li><a href="/game/" rel="nofollow">البطولات</a></li>  
							  </ul>
							  <a href="/game/" rel="nofollow" id="game-show-more-1-header"><?php echo get_translation('Show more') ?></a>
							</ul>
							  
						  </li>
						<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-666696">
							<a href="/game/" rel="nofollow">
								<svg width="20px" height="16px" viewBox="0 0 480 512" class="ico__svg" focusable="false" role="img" data-v-ico="common|cherry" fill="white"><path d="M120 150c23 0 43-6 60-14-17 32-27 68-29 106-68 8-121 64-121 134 0 74 61 136 135 136 23 0 45-6 65-17a167 167 0 010-237c-16-8-32-14-49-16 6-106 86-193 189-209-26 37-40 81-40 127v83c-67 7-120 64-120 133 0 74 61 136 135 136s135-62 135-136c0-69-53-126-120-133v-83c0-51 20-99 56-134l25-26h-36c-70 0-134 29-181 75-12-12-51-45-104-45C57 30 13 77 11 79L0 90l11 11c2 2 46 49 109 49zm225 181c-25 0-45 20-45 45h-30c0-41 34-75 75-75v30zm-180 0c-25 0-45 20-45 45H90c0-41 34-75 75-75v30z"></path></svg>
								<?php echo get_translation('Slots') ?>
							</a>
							<ul class="sub-menu header-navigation-promo">
						      <li class="header-navigation-promo__item promo-slots-item-header-li">
								  <img src="/wp-content/uploads/2025/03/140-9.png" alt="888Starz" width="75">
							  	  <img src="/wp-content/uploads/2025/03/140-8.png" alt="888Starz" width="75">
								  <img src="/wp-content/uploads/2025/03/140-7.png" alt="888Starz" width="75">
								  <img src="/wp-content/uploads/2025/03/140-6.png" alt="888Starz" width="75">
								  <img src="/wp-content/uploads/2025/03/140-5.png" alt="888Starz" width="75">
								  <img src="/wp-content/uploads/2025/03/140-4.png" alt="888Starz" width="75">
								  <img src="/wp-content/uploads/2025/03/140-3.png" alt="888Starz" width="75">
								  <img src="/wp-content/uploads/2025/03/140-2.png" alt="888Starz" width="75">
								  <img src="/wp-content/uploads/2025/03/140-1.png" alt="888Starz" width="75">
								  <img src="/wp-content/uploads/2025/03/140.png" alt="888Starz" width="75">
						      </li>
							  <ul id="menu-header-english-3" class="menu underline-effect">
									<li><a href="/game/" rel="nofollow">Rival</a></li>  
									<li><a href="/game/" rel="nofollow">Spade Gaming</a></li>  
									<li><a href="/game/" rel="nofollow">Evolution</a></li>  
									<li><a href="/game/" rel="nofollow">Spinmatic</a></li>  
								  <li><a href="/game/" rel="nofollow">Simple play</a></li> 
								   <li><a href="/game/" rel="nofollow">Spinthon</a></li>
								  <li><a href="/game/" rel="nofollow">Evoplay</a></li> 
								   <li><a href="/game/" rel="nofollow">Aviator</a></li> 
								  <li><a href="/game/" rel="nofollow">Smartsoft</a></li> 
								  <li><a href="/game/" rel="nofollow">Superlotto games</a></li> 
							  </ul>
							  <a href="/game/" rel="nofollow" id="game-show-more-1-header"><?php echo get_translation('Show more') ?></a>
							</ul> 
							  
						  </li>
							<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-666696">
								<a href="/game/" rel="nofollow">
									<svg data-v-4ba7e595="" width="16px" fill="white" viewBox="0 0 512 512" class="ico__svg" focusable="false" role="img" data-v-ico="common|bonus"><path d="M479 100h-38a66 66 0 00-30-93c-24-12-51-7-71 11l-47 42a50 50 0 00-74 0l-47-42a65 65 0 00-71-12 66 66 0 00-40 68c1 9 5 18 10 26H33c-18 0-33 15-33 33v50c0 9 7 17 17 17h478c10 0 17-8 17-17v-50c0-18-15-33-33-33zm-273-6v6h-79a33 33 0 1122-58l57 52zm211-24c-1 17-17 30-34 30h-77v-6l56-51c8-8 20-11 31-8 16 5 25 18 24 35zM33 233v245c0 19 15 34 34 34h156V233H33zm256 0v279h156c19 0 34-15 34-34V233H289z"></path></svg>
									<?php echo get_translation('Live-Casino') ?>
								</a>
							</li>
							<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-666696">
								<a href="/game/" rel="nofollow">
									888Games
								</a>
							</li>
							<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-666696">
								<a href="/game/" rel="nofollow">
									<?php echo get_translation('Cybersport') ?>
								</a>
							</li>
							<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-666696">
								<a href="/game/" rel="nofollow">
									<?php echo get_translation('More') ?>
								</a>
							</li>
							<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-666696">
								<a href="/apk/"><?php echo get_translation('Download 888Starz App (APK)'); ?></a>
							</li>
							<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-666696">
								<a href="/registration/"><?php echo get_translation('Registration'); ?></a>
							</li>
							<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-666696">
								<a href="/promo-code/"><?php echo get_translation('Promo code'); ?></a>
							</li>
						</ul>
					</nav>
					<!-- end: .main-nav -->

				<?php endif;  ?>
			</div>
		</div>
	</div>
	
	<div class="mobile-bottom-navigator-header">
		<a href="/game/" rel="nofollow" class="item-navigator-mobile">
			<svg data-v-72c25320="" width="19px" height="19px" viewBox="0 0 16 16" class="ico__svg" fill="#707070" focusable="false" role="img" data-v-ico="common|cup"><path clip-rule="evenodd" d="M13 0H3v1H1.75C.78 1 0 1.65 0 2.44v1.04c0 .82.4 1.6 1.1 2.18L3 7.23a5 5 0 0 0 3.72 4.6L6 14H4a1 1 0 0 0-1 1v1h10v-1a1 1 0 0 0-1-1h-2l-.72-2.16A5 5 0 0 0 13 7.1l2-1.7c.64-.56 1-1.3 1-2.07v-.98C16 1.61 15.29 1 14.4 1h-1.38v1.17h1.39c.12 0 .23.08.23.2v.97c0 .46-.22.9-.6 1.24L13 5.46V0ZM1.75 2.24H3v3.24l-.84-.7a1.7 1.7 0 0 1-.66-1.3V2.44c0-.11.11-.2.25-.2Zm6.44 1.35a.2.2 0 0 0-.38 0l-.44 1.34a.2.2 0 0 1-.19.14H5.76a.2.2 0 0 0-.12.36l1.15.84c.07.05.1.14.08.22l-.44 1.35a.2.2 0 0 0 .3.23l1.15-.84a.2.2 0 0 1 .24 0l1.15.84a.2.2 0 0 0 .3-.23L9.13 6.5a.2.2 0 0 1 .08-.22l1.15-.84a.2.2 0 0 0-.12-.36H8.82a.2.2 0 0 1-.2-.14L8.2 3.6Z" fill-rule="evenodd"></path></svg>
		    <?php echo get_translation('Sports') ?>
		</a>
		<a href="/game/" rel="nofollow" class="item-navigator-mobile">
			<svg data-v-72c25320="" width="19px" height="19px" fill="#707070" viewBox="0 0 16 17" class="ico__svg" focusable="false" role="img" data-v-ico="common|casino-chip"><path d="M15.2 11a7.6 7.6 0 0 1-8.5 5A7.6 7.6 0 0 1 1 11C.6 10.2.4 9.3.4 8.5c0-1 .2-1.9.6-2.7A7.6 7.6 0 0 1 9.3.9 7.6 7.6 0 0 1 15 5.7c.4.8.6 1.7.6 2.7 0 .8-.2 1.7-.4 2.5Zm-8.5 4h2.6v-1.6a5 5 0 0 1-1.3.2c-.4 0-.9 0-1.3-.2V15ZM3 12.8l1.3-.8a5 5 0 0 1-1.2-2.2l-1.4.7c.3.8.8 1.6 1.3 2.2v.1Zm.1-8.6c-.5.6-1 1.3-1.3 2.1l1.3.7a5 5 0 0 1 1.3-2.2l-1.3-.6ZM9.3 2H6.7v1.6A5 5 0 0 1 8 3.4c.4 0 .9 0 1.3.2V2ZM8 4.5a4 4 0 1 0 0 8 4 4 0 0 0 0-8ZM13 4l-1.3 1a5 5 0 0 1 1.3 2l1.3-.8c-.3-.8-.7-1.5-1.3-2.2Zm0 5.8a5 5 0 0 1-1.2 2.2l1.3.7c.6-.6 1-1.4 1.3-2.2l-1.4-1v.3Z"></path><path d="M11.1 8.5c0 1.7-1.4 3.2-3 3.2a3.1 3.1 0 0 1-3.2-3.2C5 6.7 6.3 5.3 8 5.3c1.7 0 3.1 1.4 3.1 3.2Z"></path></svg>
			<?php echo get_translation('Casino') ?>
		</a>
		<a href="/promo-code/" class="item-navigator-mobile">
			<div data-v-50587210="" class="bottom-navigation-link-coupon-content__wrapper"><svg data-v-72c25320="" viewBox="0 0 19 20" class="ico__svg" focusable="false" role="img" data-v-ico="common|coupon"><path clip-rule="evenodd" d="M3.12 5.75 2.8 5.5.12 8.2l3.1 3.1L4.5 10l.66.66-1.28 1.29 7.55 7.56 2.68-2.68-.23-.33a1.52 1.52 0 0 1 2.1-2.09l.32.23 2.68-2.68-7.56-7.55-1.28 1.28-.67-.66 1.29-1.29L7.66.65 5 3.33l.23.32a1.52 1.52 0 0 1-2.1 2.1Zm5.64-.02.67.67L8 7.8l-.66-.66 1.41-1.42ZM6.62 7.88l.66.66-1.41 1.42-.67-.67 1.42-1.41Z" fill-rule="evenodd"></path></svg></div>
			<?php echo get_translation('Coupon') ?>
		</a>
		<a href="/game/" rel="nofollow" class="item-navigator-mobile">
			<svg data-v-72c25320="" width="19px" height="19px" fill="#707070" viewBox="0 0 448 512" class="ico__svg" focusable="false" role="img" data-v-ico="common|user"><path d="M224 256a128 128 0 100-256 128 128 0 000 256zm90 32h-17a174 174 0 01-146 0h-17C60 288 0 348 0 422v42c0 27 22 48 48 48h352c27 0 48-21 48-48v-42c0-74-60-134-134-134z"></path></svg>
			<?php echo get_translation('Log in') ?>
		</a>
		
		 <div class="open-menu">
			<svg width="19px" height="14px" viewBox="0 0 12 12" enable-background="new 0 0 12 12" id="Слой_1" version="1.1" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#707070"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <rect fill="#1D1D1B" height="1" width="11" x="0.5" y="5.5"></rect> <rect fill="#1D1D1B" height="1" width="11" x="0.5" y="2.5"></rect> <rect fill="#1D1D1B" height="1" width="11" x="0.5" y="8.5"></rect> </g> </g></svg>
			 <?php echo get_translation('Menu') ?>
		</div>
		
	</div>
</header>
<?php
echo do_shortcode('[bannergamesheader]');
?>





<!-- end: #header -->

<div class="clear"></div>   

<?php
if( function_exists( 'epcl_render_global_ads' ) ){
	epcl_render_global_ads('below_header');
}
?>

<?php if( epcl_get_option('enable_search_header') == '1' || empty($epcl_theme) ): ?>
    <?php get_template_part('partials/header/search-lightbox'); ?>
<?php endif; ?>