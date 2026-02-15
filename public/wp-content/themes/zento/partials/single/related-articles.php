<?php
$post_id = get_the_ID();
$primary_category_id = epcl_get_primary_category_id($post_id);
$primary_category_name = get_the_category_by_ID($primary_category_id);
$primary_category_url = get_category_link($primary_category_id);
$category_meta = get_term_meta( $primary_category_id, 'epcl_post_categories', true );
$image_url = '';
if( !empty($category_meta) && !empty($category_meta['archives_image']) ){
    $thumb = wp_get_attachment_image_src( $category_meta['archives_image']['id'], 'epcl_classic' );
    $image_url = '';
    if( !empty($thumb) ){
        $image_url = $thumb[0];
    }    
}

$args = array(
    'posts_per_page' => absint( epcl_get_option('related_posts_limit', 4) ),
    'cat' => $primary_category_id,
    'post__not_in' => array($post_id),
    'post_type' => 'post',
    'order' => 'DESC',
);
$query_related = new WP_Query( $args );
?>
<?php if( $query_related->have_posts() ): ?>
    <section class="related bg-box section" id="epcl-related-stories">
                        
        <div class="category-info epcl-flex">                       
            <div class="overlay-effect">
                <?php if($image_url): ?>
                    <span class="image-container category-image ctag ctag-<?php echo esc_attr($primary_category_id); ?>"><img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($primary_category_name); ?>"></span>
                <?php else: ?>
                    <span class="image-container category-image ctag ctag-<?php echo esc_attr($primary_category_id); ?>"><svg class="icon"><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#fill-tag-icon"></use></svg></span>    
                <?php endif; ?>
                <h3 class="title usmall fw-normal"><?php echo __("More in this <b>Category</b>", 'zento'); ?><span class="title large"><?php echo esc_html($primary_category_name); ?></span></h3>  
                <a href="<?php echo esc_url($primary_category_url); ?>" class="full-link"><span class="screen-reader-text"><?php echo esc_html($primary_category_name); ?></span></a>    
            </div>
            <a href="<?php echo esc_url($primary_category_url); ?>" class="epcl-button ctag ctag-<?php echo esc_attr($primary_category_id); ?> hide-on-mobile"><?php echo esc_html__("View All Articles", 'zento'); ?></a>   
            <div class="clear"></div>                                                                       
        </div>

        <div class="clear"></div>
        
        <div class="article-list">                        
            <?php $c = 1; while( $query_related->have_posts() ): $query_related->the_post(); ?>
                <?php
                    $post_id = get_the_ID();
                    $size = 'medium';
                    $thumb_url = get_the_post_thumbnail_url($post_id, $size);
                ?>
                <article class="item {{post_class}}">
                    <div class="epcl-number black"><?php echo esc_html($c++); ?></div>
                    <div class="info">
                        <h4 class="title small underline-effect no-margin"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                    </div>
                </article>
                <div class="clear"></div>
            <?php endwhile; ?>                 
        </div>
        <div class="clear"></div>
        <a href="<?php echo esc_url($primary_category_url); ?>" class="epcl-button ctag ctag-<?php echo esc_attr($primary_category_id); ?> hide-on-tablet hide-on-desktop"><?php echo esc_html__("View All Articles", 'zento'); ?></a>    

    </section>  
    <?php wp_reset_postdata(); ?>
<?php endif; ?>