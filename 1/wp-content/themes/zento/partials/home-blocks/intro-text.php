<?php
$epcl_module = epcl_get_module_options();
if( empty($epcl_module) ) return;
$image_url = '';
$module_class = '';
if( !empty($epcl_module['intro_image']) && $epcl_module['intro_image']['url'] !== '' ){
    $image_url = $epcl_module['intro_image']['url'];
}else{
    $module_class .= ' no-image';
}
if( $epcl_module['intro_enable_morph_effect'] == false ){
    $module_class .= ' no-morph';
}
?>
<!-- start: .intro-text -->
<div class="intro-text large-section np-bottom grid-container np-mobile epcl-flex <?php echo esc_attr($module_class); ?>" id="<?php echo wp_unique_id('epcl-intro-text-'); ?>">
    <div class="left <?php if($image_url) echo 'grid-50 tablet-grid-55'; ?>">
        <div class="text">
            <?php echo apply_filters('the_content', $epcl_module['intro_content']); ?>
        </div>
        <?php if( $epcl_module['intro_enable_subscribe'] ): ?>
            <?php get_template_part('partials/subscribe-form'); ?>
        <?php endif; ?>
    </div>
    <?php if ( $image_url ): ?>
        <div class="right grid-45 tablet-grid-45 mobile-grid-60">
            <img src="<?php echo esc_url( $epcl_module['intro_image']['url']); ?>" fetchpriority="high" decoding="async" alt="<?php echo esc_attr($epcl_module['intro_image']['alt']); ?>" class="hero-image fullwidth" width="442" height="442">
        </div>
    <?php endif; ?>
</div>
<!-- end: .intro-text -->