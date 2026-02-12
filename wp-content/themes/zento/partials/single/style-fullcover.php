<?php
$post_id = get_the_ID();
$post_format = get_post_format();
$post_meta = get_post_meta( $post_id, 'epcl_post', true );
?>
<div class="fullcover-wrapper">
    
    <?php if( epcl_get_option('enable_breadcrumbs') == '1' && function_exists('epcl_render_breadcrumbs') ): ?>
        <div class="epcl-breadcrumbs no-padding">
            <?php epcl_render_breadcrumbs(); ?>
        </div>
    <?php endif; ?>

    <?php echo epcl_display_post_format( $post_format, $post_id, true ); ?>  

    <div class="clear"></div>

</div>