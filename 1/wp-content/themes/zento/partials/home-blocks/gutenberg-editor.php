<?php
$epcl_module = epcl_get_module_options();
if( empty($epcl_module) ) return;
$module_class = '';
$style = '';
if( !empty($epcl_module['container_width']) && $epcl_module['container_width'] !== '' ){
    $style = 'style="max-width: '.$epcl_module['container_width'].';"';
}
    
?>
<div class="epcl-gutenberg-editor section np-bottom grid-container" id="<?php echo wp_unique_id('epcl-gutenberg-editor-'); ?>" <?php echo strip_tags($style); ?>>
    <!-- start: .content-wrapper -->
    <div class="content-wrapper <?php echo esc_attr($module_class); ?>">
        <!-- start: .center -->
        <div class="center">
            <div class="text">
                <?php the_content(); ?>
            </div>
        </div>
        <!-- end: .center -->
    </div>
    <!-- end: .content-wrapper -->
</div>