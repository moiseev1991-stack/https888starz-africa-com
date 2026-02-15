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
<!-- start: #footer -->
<footer id="footer" class="<?php echo esc_attr($footer_class); ?>">

    <?php if( epcl_get_option('enable_amp_footer_widgets', true) ): ?>
        <?php if( is_active_sidebar('epcl_sidebar_footer_mobile') ): ?>
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