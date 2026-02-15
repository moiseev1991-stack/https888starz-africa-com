<?php
/*
* Common functionalities for all EP themes (static functions).
* These functions add or extends WordPress functiontionalities.
*
*/

if ( ! class_exists( 'EPCL_Static_Functions' ) ) {

	class EPCL_Static_Functions {

		public function __construct() {

            /* Body Classes */
            
            add_filter( 'body_class', array( $this, 'custom_body_classes'), 5 );

			/* Front-End: Custom Excerpt */

			add_filter('excerpt_more', array( $this, 'new_excerpt_more'));
            add_filter('excerpt_length', array( $this, 'custom_excerpt_length'), 999);

        }
        
        public function custom_body_classes( $classes ) {
            $epcl_theme = epcl_get_theme_options();
            
            if( empty($epcl_theme) ) return $classes;

            if( isset($_GET['bg']) ){
                $epcl_theme['background_type'] = 3;
            }

            if($epcl_theme['background_type'] == 1 && isset($epcl_theme['bg_body_pattern']['url']) && $epcl_theme['bg_body_pattern']['url']) $classes[] = ' pattern bg-image';
            if($epcl_theme['background_type'] == 3 && isset($epcl_theme['bg_body_full']['url']) && $epcl_theme['bg_body_full']['url']) $classes[] = ' cover bg-image';
            
            // Lazy Load for adsense
            if( isset($epcl_theme['enable_lazyload_adsense']) && $epcl_theme['enable_lazyload_adsense'] === '1' ) $classes[] = ' enable-lazy-adsense';
              
            // Theme Optimization enabled
            if( isset($epcl_theme['enable_optimization']) && $epcl_theme['enable_optimization'] === '1' ) $classes[] = ' enable-optimization';

            // Fullwidth Mobile layout
            if( isset($epcl_theme['mobile_layout']) && $epcl_theme['mobile_layout'] === 'fullwidth' ) $classes[] = ' mobile-fullwidth';

            // Detect if Sticky header is enabled
            if( isset($epcl_theme['enable_sticky_header']) && $epcl_theme['enable_sticky_header'] == true ) $classes[] = ' sticky-header-enabled';

            // Sidebar Location
            if( isset($epcl_theme['sidebar_location']) && $epcl_theme['sidebar_location'] == 'right' ) $classes[] = ' epcl-right-sidebar';

            // Disable Built-in Lightbox
            if( isset($epcl_theme['enable_built_in_lightbox']) && $epcl_theme['enable_built_in_lightbox'] == false ) $classes[] = ' epcl-disable-lightbox';

            if( is_404() ){
                $classes[] = ' epcl-fullscreen';
            }
            
            return $classes;
        }

		/* Replace [...] excerpt with a new one */

		public function new_excerpt_more($more){
			return '...';
		}

		/* Change excerpt length */

		public function custom_excerpt_length($length){
			return 25;
        }

	}

	new EPCL_Static_Functions();
}

