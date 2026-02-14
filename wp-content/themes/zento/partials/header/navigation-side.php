<!-- start: .mobile.main-nav -->
<nav class="mobile side-nav">

    <div class="close"><svg class="icon"><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#close-icon"></use></svg></div>

    <?php get_template_part('partials/header/site-logo'); ?>
    <?php echo do_shortcode("[dropdown_language_switcher]"); ?>
	<div class="link-reg-mobile">
			<a href="/registration/" class="link-game-button ink-game-button-reg-mobiles">
				<?php echo get_translation('Registration') ?>
			</a>
			<a href="/apk/" class="link-game-button">
				<?php echo get_translation('Download 888Starz App (APK)'); ?>
			</a>
			<a href="/promo-code/" class="link-game-button">
				<?php echo get_translation('Promo code'); ?>
			</a>
			<a href="/game/" class="link-game-button" rel="nofollow">
				<?php echo get_translation('Log in') ?>
			</a>
	</div>

    <?php
    $args = array( 'theme_location' => 'epcl_header', 'container' => false, 'menu_class' => 'menu underline-effect' );
    if( has_nav_menu('epcl_header') ){
        wp_nav_menu( $args );
    } 
    ?>  
    <?php if( epcl_get_option('enable_subscribe') == true ): ?>
        <div class="epcl-buttons">
            <?php echo epcl_get_subscribe_button(); ?> 
        </div>
    <?php endif ;?>   
	
</nav>
<!-- end: .mobile.main-nav -->
<div class="menu-overlay"></div>