<?php $epcl_theme = epcl_get_theme_options(); ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <?php if ( ! ( function_exists( 'has_site_icon' ) && has_site_icon() ) ): ?>
        <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.png" />
    <?php endif; ?>

    <?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>
    
    <?php if( epcl_get_option('amp_auto_ads_enabled') && epcl_get_option('amp_auto_ads_client') ): ?>
        <amp-auto-ads type="adsense" data-ad-client="<?php echo epcl_get_option('amp_auto_ads_client'); ?>">
        </amp-auto-ads>
    <?php endif; ?>

    <?php if( epcl_get_option('amp_custom_code', false) ): ?>
        <?php echo epcl_get_option('amp_custom_code'); ?>
    <?php endif; ?>

    <?php if( has_nav_menu('epcl_header') ): ?>
        <!-- start: .main-nav -->
        <amp-sidebar id="sidenav" layout="nodisplay" side="right">
            <div class="close"><svg class="icon"><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#close-icon"></use></svg></div>

            <?php get_template_part('partials/header/site-logo'); ?>
            <div class="tagline"><small><?php bloginfo('description'); ?></small></div>

            <?php
            $args = array(
                'theme_location' => 'epcl_header',
                'container' => false
            );
            wp_nav_menu($args);
            ?>

            <?php if( epcl_get_option('enable_subscribe') == true ): ?>
                <div class="epcl-buttons">
                    <?php echo epcl_get_subscribe_button(); ?> 
                </div>
            <?php endif ;?> 
        </amp-sidebar>
    <?php endif; ?>

    <!-- start: #wrapper -->
    <div id="wrapper">
		<?php get_template_part('amp/partials/header'); ?>
