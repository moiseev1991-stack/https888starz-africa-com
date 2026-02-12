<?php
$epcl_theme = epcl_get_theme_options();

$max_size = 300;
// Force Mobile logo optimization
if( !empty($epcl_theme) && !empty( $epcl_theme['logo_image']) && $epcl_theme['logo_image']['url'] != '' ){
    $mobile_logo_url  =  $epcl_theme['logo_image']['url'];
    $mobile_logo_width = $epcl_theme['logo_image']['width'];
    $mobile_logo_height = $epcl_theme['logo_image']['height'];
    if( $epcl_theme['logo_image']['width'] > $max_size ){
        $image_url = wp_get_attachment_image_src($epcl_theme['logo_image']['id'], 'medium' );
        if ( !empty($image_url) ) {
            $mobile_logo_url = $image_url[0];
            $mobile_logo_width = $image_url[1];
            $mobile_logo_height = $image_url[2];
        }            
    } 
}
?>
<?php if( epcl_get_option('logo_type') == 1 && !empty($epcl_theme['logo_image']['url']) ): ?>
    <div class="logo">
        <a href="<?php echo home_url('/'); ?>">
            <!-- Desktop Logo -->
            <img class="hide-on-mobile" src="<?php echo esc_url( $epcl_theme['logo_image']['url'] ); ?>" alt="<?php bloginfo('name'); ?>" width="<?php echo esc_attr( $epcl_theme['logo_image']['width'] ); ?>" style="width: <?php echo esc_attr($epcl_theme['logo_width']); ?>px;">
            <!-- Mobile Logo -->
            <img class="hide-on-desktop hide-on-tablet" src="<?php echo esc_url( $mobile_logo_url ); ?>" alt="<?php bloginfo('name'); ?>" width="<?php echo esc_attr( $mobile_logo_width ); ?>" height="<?php echo esc_attr( $mobile_logo_height ); ?>">
        </a>
        <?php if( epcl_get_option('header_type') == 'classic' && epcl_get_option('logo_tagline', false) ): ?>
            <div class="tagline"><small><?php bloginfo('description'); ?></small></div>
        <?php endif; ?>
    </div>
    <?php if( !empty($epcl_theme['sticky_logo_image']['url']) ): ?>
        <div class="logo sticky-logo hide-on-mobile hide-on-tablet hide-on-desktop-sm">
            <a href="<?php echo home_url('/'); ?>"><img src="<?php echo esc_url( $epcl_theme['sticky_logo_image']['url'] ); ?>" alt="<?php bloginfo('name'); ?>" width="<?php echo epcl_get_option('sticky_logo_width'); ?>" /></a>
        </div>                
    <?php endif; ?>
<?php else: ?>
    <div class="logo text-logo">
        <a href="<?php echo home_url('/'); ?>" class="title ularge black no-margin">
            <?php if( isset( $epcl_theme['logo_icon'] ) && $epcl_theme['logo_icon'] ): ?>
                <span class="icon"><?php echo wp_kses( $epcl_theme['logo_icon'], get_kses_svg_ruleset() ); ?></span>
            <?php endif; ?>
            <span class="name"><?php bloginfo('name'); ?></span>
        </a>
        <?php if( epcl_get_option('header_type') == 'classic' && epcl_get_option('logo_tagline', false) ): ?>
            <div class="tagline"><small><?php bloginfo('description'); ?></small></div>
        <?php endif; ?>
    </div>
<?php endif; ?>