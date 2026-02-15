<?php

function epcl_render_breadcrumbs(){

    if ( function_exists('yoast_breadcrumb') && epcl_get_option('breadcrumbs_type') == 'yoast' ) {
        yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
    } elseif( function_exists('bcn_display') && epcl_get_option('breadcrumbs_type') == 'navxt' ){
        bcn_display();
    } elseif( function_exists('rank_math_the_breadcrumbs') && epcl_get_option('breadcrumbs_type') == 'rankmath' ){
        rank_math_the_breadcrumbs();
    }

}

function epcl_custom_scripts_body() {
    global $epcl_theme;
    if( empty($epcl_theme) || epcl_is_amp() ) return;

    if( isset( $epcl_theme['custom_scripts_body'] ) && $epcl_theme['custom_scripts_body'] ){
        echo $epcl_theme['custom_scripts_body'];
    }
}

add_action('wp_body_open', 'epcl_custom_scripts_body', 1);

function epcl_render_reading_time(){
    $content = get_the_content();
    if( !$content ) return;
    $reading_time = epcl_reading_time( get_the_content() );
?>
    <?php if( epcl_get_option( 'enable_global_reading_time', false ) ): ?>
        <div class="min-read meta-info">
            <svg class="icon main-color"><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#reading-icon"></use></svg> <?php printf( esc_attr__( '%d Min Read', 'zento' ), $reading_time ); ?>
        </div>
    <?php endif; ?>
<?php
}

function epcl_render_views_counter($extra_classes = ''){
    $views = 0;
    $post_meta = get_post_meta( get_the_ID(), 'epcl_post', true );
    if( isset( $post_meta['views_counter'] ) && $post_meta['views_counter'] > 0 ){
        $views = $post_meta['views_counter'];
    } 
?>
    <?php if( epcl_get_option( 'enable_global_views', false ) ): ?>
        <div class="views-counter meta-info <?php echo esc_attr($extra_classes); ?>" title="<?php echo absint( $views ); ?> <?php esc_attr_e('Views', 'zento'); ?>"><svg class="icon main-color" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2ZM12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4ZM12 7C14.7614 7 17 9.23858 17 12C17 14.7614 14.7614 17 12 17C9.23858 17 7 14.7614 7 12C7 11.4872 7.07719 10.9925 7.22057 10.5268C7.61175 11.3954 8.48527 12 9.5 12C10.8807 12 12 10.8807 12 9.5C12 8.48527 11.3954 7.61175 10.5269 7.21995C10.9925 7.07719 11.4872 7 12 7Z"></path></svg> <?php echo absint( $views ); ?> <span class="hidden"><?php echo esc_html__('Views', 'zento'); ?></span></div>  
    <?php endif; ?>
    
<?php
}

// Customs fonts to match Gutenberg with Front-End, only enabled by theme options
add_action('admin_footer', 'epcl_admin_custom_css', 20);
function epcl_admin_custom_css() {
    $custom_css = '';
    if( epcl_get_option('enable_gutenberg_admin', true) ){
        $custom_css = epcl_generate_gutenberg_custom_styles();
    }    
    echo '<style id="epcl-custom-css-admin">.column-epcl_post_image, .column-epcl_post_order { width: 75px; }'.$custom_css.'</style>';
}

function epcl_render_theme_author( $class = ''){
    return '<p class="published underline-effect '.esc_attr($class).'"><a href="https://estudiopatagon.com/projects/zento-for-wordpress?ref=zento-wp-footer" target="_blank">Zento</a> Theme by <a href="https://estudiopatagon.com?ref=zento-wp-footer" target="_blank">EstudioPatagon</a> <span class="dot"></span> Powered by <a href="https://wordpress.org" target="_blank">WordPress</a></p>';
}

// add_action('wp_footer', 'epcl_render_demo_button', 100);
function epcl_render_demo_button(){
    if( epcl_is_amp() ) return;
    $site_url = site_url();
    if( (strpos($site_url, 'estudiopatagon.com') < 1) && (strpos($site_url, 'localhost/zento-wp') < 1) ) return;
?>
<div class="epcl-demo-tool hide-on-mobile hide-on-tablet hide-on-desktop-sm">
    <div class="tool" title="Demo options">
        <svg class="icon ularge" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <g fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M14 21h-4l-.551-2.48a6.991 6.991 0 0 1-1.819-1.05l-2.424.763l-2-3.464l1.872-1.718a7.055 7.055 0 0 1 0-2.1L3.206 9.232l2-3.464l2.424.763A6.992 6.992 0 0 1 9.45 5.48L10 3h4l.551 2.48a6.992 6.992 0 0 1 1.819 1.05l2.424-.763l2 3.464l-1.872 1.718a7.05 7.05 0 0 1 0 2.1l1.872 1.718l-2 3.464l-2.424-.763a6.99 6.99 0 0 1-1.819 1.052L14 21z">
                </path>
                <circle cx="12" cy="12" r="3"></circle>
            </g>
        </svg>
    </div>
    <div class="title usmall">Change Styling:</div>
    <label><input type="color" value="#6A4EE9" data-target="--epcl-main-color"> Accent Color</label>
    <label><input type="color" value="#FF2AAC" data-target="--epcl-secondary-color"> Decoration Color</label>
    <label><input type="color" value="#FAF8FF" data-class="body" data-attr="background"> Background Color</label>
    <label><input type="color" value="#E9E8FF" data-target="--epcl-boxes-border-color"> Boxes Border Color</label>
    <label><input type="color" value="#E9E8FF" data-target="--epcl-border-color"> General Border Color</label>
    <p style="font-size: 12px; padding-top: 5px;"><b>Note:</b> All these options are included in the WordPress Admin.</p>
</div>
<?php
}