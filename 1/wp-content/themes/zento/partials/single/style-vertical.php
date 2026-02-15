<?php 
$post_id = get_the_ID();
$post_meta = get_post_meta( get_the_ID(), 'epcl_post', true );
$single_size = 'epcl_classic';
if( wp_is_mobile() ){
    $single_size = 'medium_large';
} 
?>
<header>

    <?php if( has_post_thumbnail() ): ?>

        <?php get_template_part('partials/single/single-post-info'); ?>    

        <div class="featured-image">
            <?php if( epcl_is_amp() ): ?>
                <?php the_post_thumbnail( $single_size, array('data-lazy' => 'false') ); ?>
            <?php else: ?>
                <?php the_post_thumbnail( $single_size, array('data-lazy' => 'false', 'loading' => 'eager') ); ?>
            <?php endif; ?>
            <?php echo epcl_render_image_caption($post_id); ?>            
        </div>
    <?php else: ?>
        <?php get_template_part('partials/single/single-post-info', false, array('title_class' => 'ularge') ); ?> 
    <?php endif; ?>

	<div class="clear"></div>

</header>