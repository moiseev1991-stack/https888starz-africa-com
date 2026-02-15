<?php

add_action('wp_footer', 'epcl_async_scripts');

function epcl_async_scripts() {

    $ajax_scripts = epcl_get_option('custom_ajax_scripts');

    if ( !empty($ajax_scripts) ): ?>

        <div id="epcl-ajax-scripts" style="display: none;">
            <?php foreach( $ajax_scripts as $item ): ?>
                <?php if( $item['script_src'] !== ''): ?>
                    <div
                        data-src="<?php echo esc_attr( $item['script_src'] ); ?>"
                        data-cache="<?php echo esc_attr( $item['script_cache'] ); ?>"
                        data-timeout="<?php echo absint( $item['script_timeout'] ); ?>">
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

    <?php endif;   
}

add_action('wp', 'epcl_disable_cf7_home');

function epcl_disable_cf7_home() {
    $epcl_theme = epcl_get_theme_options();
    if( empty($epcl_theme) ) return;
    if( epcl_get_option('enable_optimization') || defined('W3TC') ){
        if( is_front_page() || is_home() ){
            add_filter( 'wpcf7_load_css', '__return_false' );
            add_filter( 'wpcf7_load_js', '__return_false' );
        }
    }        
}

add_action('wp_enqueue_scripts', 'epcl_enqueue_scripts_plugin', 11);

function epcl_enqueue_scripts_plugin() {
    $epcl_theme = epcl_get_theme_options();

    $assets_folder = EPCL_THEMEPATH.'/assets';
    $prefix = EPCL_THEMEPREFIX.'-';

    $theme = wp_get_theme( EPCL_THEMESLUG );
    $ver = $theme->version;   

    /* CSS */

    $fonts = array(
        $epcl_theme['primary_titles_font'], $epcl_theme['body_font'],
        $epcl_theme['sidebar_titles_font'], $epcl_theme['sidebar_font'],
        $epcl_theme['footer_titles_font'], $epcl_theme['footer_font'],
    );

    wp_register_style( $prefix . 'theme-options-google-fonts' , epcl_theme_options_google_fonts( $fonts ), NULL, NULL );
    wp_enqueue_style( $prefix . 'theme-options-google-fonts' );

    /* Scripts */
    
    // W3 Total Cache optimization

    if( !empty($epcl_theme) && $epcl_theme['move_jquery_footer'] ){ // Only enabled by panel
        wp_scripts()->add_data( 'jquery', 'group', 1 );
        wp_scripts()->add_data( 'jquery-core', 'group', 1 );
        wp_scripts()->add_data( 'jquery-migrate', 'group', 1 );
    }		

    // Disqus inline JS

    if( !empty($epcl_theme) && $epcl_theme['hosted_comments'] == 2 && $epcl_theme['disqus_id'] ){
        $custom_js = epcl_add_disqus_scripts();
        if( epcl_get_option('enable_optimization') == '1'){
            wp_add_inline_script($prefix.'scripts', $custom_js);
        }else{
            wp_add_inline_script($prefix.'functions', $custom_js);
        }
    }

    // Facebook Comments
    if( !empty($epcl_theme) && $epcl_theme['hosted_comments'] == 3 ){
        $fb_lang_code = 'en_US';
        $app_id = '';
        if( epcl_get_option('facebook_lang_code') !== '' ){
            $fb_lang_code = epcl_get_option('facebook_lang_code');
        }
        
        if( epcl_get_option('facebook_app_id') !== '' ){
            $app_id = '&appId='.epcl_get_option('facebook_app_id');
        }
        wp_enqueue_script( $prefix.'facebook-comments', 'https://connect.facebook.net/'.esc_attr($fb_lang_code).'/sdk.js#xfbml=1&version=v3.3'.$app_id, array(), false, true ); 
    }

    
    // Syntax Highlighter PrismJS (enabled only by theme options), default: false
    if( epcl_get_option('enable_syntax_highlighter') !== '0' ){            
        wp_enqueue_script('prismjs-core', 'https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js', array('jquery'), $ver, true);
        wp_enqueue_script('prismjs-autoloader', 'https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js', array('prismjs-core'), $ver, true);
        wp_enqueue_script('prismjs-plugins', $assets_folder.'/dist/prism-plugins.min.js', array('prismjs-core', 'prismjs-autoloader'), $ver, true);
    }

}

function epcl_theme_options_google_fonts( $google_fonts ) {
    $link = $fonts_url = "";
    $subsets = array();
    $fonts = array();

    foreach ( $google_fonts as $font ) {
        $link = '';
        if(  isset($font['type']) && $font['type'] == 'google' ){

            $link .= $font['font-family'];
            if( !empty($font['font-family']) && !empty($font['font-weight']) ){
                $link .= ':'.$font['font-weight'] ;
            }

            if( $link ){
                $fonts[] = $link;
            }

            if ( ! empty( $font['subsets'] ) ) {
                if ( ! in_array( $font['subsets'], $subsets ) ) {
                    array_push( $subsets, $font['subsets'] );
                }
            }
        }

    }

    if ( !empty($fonts) ) {
        $fonts_url = add_query_arg( array(
            'family' => urlencode( implode( '|', $fonts ) ),
            'subset' => urlencode( implode( ',', $subsets ) ),
            'display' => 'swap',
        ), '//fonts.googleapis.com/css' );
    }

    return $fonts_url;
}

function epcl_add_disqus_scripts(){
    $epcl_theme = epcl_get_theme_options();

    $js = 
    '
    var disqus_shortname = "'.esc_attr( $epcl_theme['disqus_id']).'";
    
    !function(){var e=document.createElement("script");e.async=!0,e.type="text/javascript",e.src="//"+disqus_shortname+".disqus.com/count.js",document.getElementsByTagName("BODY")[0].appendChild(e)}();
    ';
    if( is_single() || ( is_page() && !is_page_template() ) ){
        if( !comments_open() ){
            return;
        }
        $js .= '
        var disqus_config = function () {
            this.page.url = "'.get_the_permalink().'"; 
            this.page.identifier = "'.get_the_ID().'";
        };
        (function() { 
            var d = document, s = d.createElement("script");
            s.src = "//" + disqus_shortname + ".disqus.com/embed.js";
            s.setAttribute("data-timestamp", +new Date());
            (d.head || d.body).appendChild(s);
        })();';
    }

    return $js;
        
}

if( function_exists('autoptimize') ){
    // add_filter('autoptimize_filter_css_exclude','epcl_autoptimize_css_exclude');
    // function epcl_autoptimize_css_exclude($in) {
    //     return $in.',fontawesome.min.css';
    // } 
    // add_filter('autoptimize_html_after_minify','preload_to_aodeferload');
    // function preload_to_aodeferload($htmlIn) {
    //     return str_replace('<link rel="preload"','<link rel="prefetch"',$htmlIn);
    // }
}

function add_defer_attribute($tag, $handle) {
    if( is_admin() ) return $tag;

    if( epcl_get_option('enable_defer_scripts', false) !== '1' ){
        return $tag;
    }

    return str_replace(' src', ' defer src', $tag);

    // // agregar los handles de los scripts que no deben tener defer
    // $scripts_to_exclude = array('script-handle1', 'script-handle2');

    // if (!in_array($handle, $scripts_to_exclude)) {
    //     return str_replace(' src', ' defer src', $tag);
    // }
    // return $tag;
}
add_filter('script_loader_tag', 'add_defer_attribute', 10, 2);


function epcl_styles_footer() {
    $epcl_theme = epcl_get_theme_options();

    $assets_folder = EPCL_THEMEPATH.'/assets';
    $prefix = EPCL_THEMEPREFIX.'-';

    $theme = wp_get_theme( EPCL_THEMESLUG );
    $ver = $theme->version;

    if( epcl_get_option('fonts_icons_method', 'footer') == 'javascript' ){
        $custom_js = epcl_font_icons_scripts();
        if( epcl_get_option('enable_optimization') == '1'){
            wp_add_inline_script($prefix.'scripts', $custom_js);
        }else{
            wp_add_inline_script($prefix.'functions', $custom_js);
        }
    }

}

add_action( 'get_footer', 'epcl_styles_footer' );

function epcl_font_icons_scripts(){
    $delay = absint( epcl_get_option('font_icons_delay', '500') );
    $assets_folder = EPCL_THEMEPATH.'/assets';
    $js = "
    setTimeout(function(){
        epcl_load_css_file('$assets_folder/dist/fontawesome.min.css');
    }, $delay);
    function epcl_load_css_file(filename){
        var head = document.getElementsByTagName('head')[0];
        var style = document.createElement('link');
        style.href = filename;
        style.type = 'text/css';
        style.rel = 'stylesheet';
        head.appendChild(style);
    }
    ";
    return $js;
}

function epcl_disable_emojis() {
    if( !epcl_get_option('remove_emojis', false ) ) return;
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    add_filter('tiny_mce_plugins', 'epcl_disable_emojis_tinymce');
    add_filter('wp_resource_hints', 'epcl_disable_emojis_remove_dns_prefetch', 10, 2);
}
add_action('init', 'epcl_disable_emojis');

function epcl_disable_emojis_tinymce($plugins) {
    if (is_array($plugins)) {
        return array_diff($plugins, array('wpemoji'));
    } else {
        return array();
    }
}
function epcl_disable_emojis_remove_dns_prefetch($urls, $relation_type) {
    if ('dns-prefetch' == $relation_type) {
        /** This filter is documented in wp-includes/formatting.php */
        $emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/');
        $urls = array_diff($urls, array($emoji_svg_url));
    }
    return $urls;
}