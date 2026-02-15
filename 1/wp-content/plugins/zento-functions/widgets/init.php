<?php

require_once(EPCL_PLUGIN_PATH.'/widgets/setup.class.php');

function epcl_register_widgets(){
    $wp_widget_factory = new WP_Widget_Factory();
    global $wp_widget_factory;

    if( !function_exists('epcl_get_option') ){
        function epcl_get_option( $option = '', $default = '' ) {
            global $epcl_theme;
            if( empty($epcl_theme) && defined('EPCL_PLUGIN_PATH') ){
                $epcl_theme = get_option( EPCL_FRAMEWORK_VAR );
            }
            if( !empty($epcl_theme) && isset( $epcl_theme[ $option ] ) && defined('EPCL_PLUGIN_PATH') ){
                return $epcl_theme[ $option ];
            }else{
                if( $default !== '' ){
                    return $default;
                }
                return false;
            }
        }
    }
    
    
    require_once(EPCL_PLUGIN_PATH.'/widgets/about.php');
    require_once(EPCL_PLUGIN_PATH.'/widgets/posts-thumbs.php');
    require_once(EPCL_PLUGIN_PATH.'/widgets/featured-category.php');
    require_once(EPCL_PLUGIN_PATH.'/widgets/related-articles.php');
    require_once(EPCL_PLUGIN_PATH.'/widgets/video.php');
    require_once(EPCL_PLUGIN_PATH.'/widgets/flickr.php');
    // require_once(EPCL_PLUGIN_PATH.'/widgets/tweets.php');
    require_once(EPCL_PLUGIN_PATH.'/widgets/ads-125.php');
    require_once(EPCL_PLUGIN_PATH.'/widgets/ads-fluid.php');
    require_once(EPCL_PLUGIN_PATH.'/widgets/social.php');
    require_once(EPCL_PLUGIN_PATH.'/widgets/tag-cloud.php');
    require_once(EPCL_PLUGIN_PATH.'/widgets/subscribe-form.php');
    require_once(EPCL_PLUGIN_PATH.'/widgets/toc.php');
    require_once(EPCL_PLUGIN_PATH.'/widgets/article-info.php');
    require_once(EPCL_PLUGIN_PATH.'/widgets/topics-index.php');
    // require_once(EPCL_PLUGIN_PATH.'/widgets/category-slider.php');

}

add_action('widgets_init', 'epcl_register_widgets');