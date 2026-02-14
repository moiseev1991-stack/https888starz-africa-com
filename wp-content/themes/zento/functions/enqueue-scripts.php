<?php

/* Scripts and Styles */

if( !function_exists('epcl_enqueue_scripts') ){

    add_action('wp_enqueue_scripts', 'epcl_enqueue_scripts');

	function epcl_enqueue_scripts() {
		$epcl_theme = epcl_get_theme_options();

		$assets_folder = EPCL_THEMEPATH.'/assets';
        $prefix = EPCL_THEMEPREFIX.'-';

        $theme = wp_get_theme( EPCL_THEMESLUG );
        $ver = $theme->version;
        
        /* Styles */

        wp_register_style($prefix.'google-fonts', epcl_google_fonts_url(), NULL, NULL);

        // AMP styles
        if( epcl_is_amp() ){   
            if( epcl_get_option('amp_enable_google_fonts') !== '0' ){
                wp_enqueue_style($prefix.'google-fonts');     
            }
            wp_enqueue_style($prefix.'theme', $assets_folder.'/dist/style.min.css', NULL, $ver);            
            wp_enqueue_style($prefix.'plugins', $assets_folder.'/dist/plugins.min.css', NULL, $ver);  
            wp_enqueue_style($prefix.'amp', $assets_folder.'/dist/amp.min.css', NULL, $ver); 
            $custom_css = epcl_generate_custom_styles();
            wp_add_inline_style( $prefix.'amp', $custom_css );            
            return;
        }

        // If theme options is installed and optimization is ON, the theme will load combined and minified CSS and JS
        if( epcl_get_option('enable_optimization') == '1' ){
            wp_enqueue_style($prefix.'plugins', $assets_folder.'/dist/plugins.min.css', NULL, $ver);            
            wp_enqueue_script($prefix.'scripts', $assets_folder.'/dist/scripts.min.js', array('jquery'), $ver, true);
            wp_localize_script($prefix.'scripts', 'ajax_var', array(
                'url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('epcl_views_counter'),
                'assets_folder' => $assets_folder
            ));
        }else{
            // Not combined libraries

            /* Scripts */
            wp_enqueue_script('tocbot', $assets_folder.'/js/tocbot.min.js', array('jquery'), $ver, true);
            wp_enqueue_script('masonry', $assets_folder.'/js/masonry.pkgd.min.js', array('jquery'), $ver, true);
            wp_enqueue_script('slick-slider', $assets_folder.'/js/slick.min.js', array('jquery'), $ver, true);      
            wp_enqueue_script('magnific-popup', $assets_folder.'/js/jquery.magnific-popup.min.js', array('jquery'), $ver, true);            
            wp_enqueue_script('preload-css', $assets_folder.'/js/preload-css.min.js', array('jquery'), $ver, true);            
            wp_enqueue_script($prefix.'functions', $assets_folder.'/js/functions.js', array('jquery'), $ver, true);
            wp_enqueue_script($prefix.'shortcodes', $assets_folder.'/js/shortcodes.js', array('jquery'), $ver, true);
            wp_localize_script($prefix.'functions', 'ajax_var', array(
                'url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('epcl_views_counter'),
                'assets_folder' => $assets_folder
            )); 
            
            /* Styles */
            wp_enqueue_style($prefix.'theme', $assets_folder.'/dist/style.min.css', NULL, $ver); // There is a dist/style.css (unminified) file if needed 
            wp_enqueue_style('slick-slider', $assets_folder.'/dist/plugin-slick-slider.css', NULL, $ver);
            wp_enqueue_style('slick-theme', $assets_folder.'/dist/plugin-slick-theme.css', NULL, $ver);
            wp_enqueue_style('tocbot', $assets_folder.'/dist/plugin-tocbot.css', NULL, $ver);
            wp_enqueue_style('magnific-popup', $assets_folder.'/dist/plugin-magnific-popup.css', NULL, $ver);
            wp_enqueue_style('prismjs-theme', $assets_folder.'/dist/plugin-prism.css', NULL, $ver);
            wp_enqueue_style($prefix.'shortcodes', $assets_folder.'/dist/plugin-shortcodes.css', NULL, $ver);
        }

        if( empty($epcl_theme) || epcl_get_option('enable_google_fonts') !== '0' ){
            wp_enqueue_style($prefix.'google-fonts');
        }

        if ( class_exists( 'WooCommerce' ) && !epcl_is_amp() ) { // Do not load WC styles if not installed or AMP page
            wp_enqueue_style($prefix.'woocommerce', $assets_folder.'/dist/woocommerce.min.css', NULL, $ver);
        }

        /* Carousel Lite — replaces Owl, no jQuery; for .owl-carousel, .owl-mobile, .slider-888-slider */
        wp_enqueue_style('carousel-lite', $assets_folder.'/dist/carousel-lite.css', NULL, $ver);
        wp_enqueue_script('carousel-lite', $assets_folder.'/js/carousel-lite.js', array(), $ver, true);

        if( !defined('W3TC') ){
            $custom_css = epcl_generate_custom_styles();
            if( epcl_get_option('enable_optimization') !== '1' && $custom_css){
                wp_add_inline_style( $prefix.'shortcodes', $custom_css );
            }
        }  

        /* Scripts */	

		if( is_singular() && comments_open() && ( get_option( 'thread_comments' ) == 1) ) {
			wp_enqueue_script( 'comment-reply', 'wp-includes/js/comment-reply', array(), false, true );
        }

    }

    function epcl_google_fonts_url() {
        $fonts_url = '';
        $fonts     = array();
        $subsets   = 'latin,latin-ext';

        /* Translators: If there are characters in your language that are not supported by Poppins, translate this to 'off'. Do not translate into your own language. */

        if ( 'off' !== _x( 'on', 'Urbanist font: on or off', 'zento' ) ) {
            $fonts[] = 'Urbanist:400,500,600,700,700i,800';
        }

        if ( 'off' !== _x( 'on', 'DM Sans font: on or off', 'zento' ) ) {
            $fonts[] = 'DM Sans:400,400i,700,700i';
        }
        
        if ( $fonts ) {
            $fonts_url = add_query_arg( array(
                'family' => urlencode( implode( '|', $fonts ) ),
                'subset' => urlencode( $subsets ),
                'display' => 'swap',
            ), 'https://fonts.googleapis.com/css' );
        }
        return $fonts_url;
    }
    
}

