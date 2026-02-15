<?php
$class = 'item ';
$post_id = get_the_ID();
$thumb_size = 'thumbnail';
$post_meta = get_post_meta( $post_id, 'epcl_post', true );
$class = epcl_get_primary_category( $class, $post_meta, $post_id );
$index = $query->current_post + 1;
?>
<?php if( !has_post_thumbnail() ) $class .= ' no-thumb'; ?>

<article <?php post_class($class); ?>>
    <div class="info epcl-flex">
        <?php if( has_post_thumbnail() ): ?>
            <?php
            $thumb_id = get_post_thumbnail_id($post_id);
            $thumb_type = get_post_mime_type($thumb_id);
            $image_alt = get_post_meta( $thumb_id, '_wp_attachment_image_alt', true);
            if( !$image_alt ){
                $image_alt = get_the_title($post_id);
            }
            if($thumb_type == 'image/gif'){
                $thumb_size = '';
            }
            if( epcl_is_amp() && isset( $epcl_theme['enable_lazyload'] ) ){
                $epcl_theme['enable_lazyload'] = false;
            }
            ?>
            
            <a href="<?php the_permalink(); ?>" class="thumb translate-effect <?php if(!epcl_is_amp()) echo 'epcl-loader'; ?>">
                <span class="screen-reader-text"><?php the_title(); ?></span>
                <?php if( !empty($epcl_theme) && $epcl_theme['enable_lazyload'] == '1' ): ?>
                    <img class="fullimage cover" loading="lazy" fetchpriority="low" decoding="async" src="<?php the_post_thumbnail_url($thumb_size); ?>" alt="<?php the_title(); ?>">
                <?php else: ?>
                    <img class="fullimage cover" src="<?php the_post_thumbnail_url($thumb_size); ?>" alt="<?php the_title(); ?>">
                <?php endif; ?>          
            </a>
        <?php endif; ?>
        
        <div class="right">
            <h4 class="title usmall underline-effect"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
            <div class="meta small">
                <?php get_template_part( 'partials/meta-info/date'); ?>
                <!-- <time datetime="<?php the_time('Y-m-d'); ?>" class="icon"><?php the_time( get_option('date_format') ); ?></time> -->
            </div>  
        </div>

        <div class="clear"></div>
    </div>

    <div class="clear"></div>
</article>