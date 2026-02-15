<?php
$next_post = get_next_post();
$prev_post = get_previous_post();
if( empty($next_post) && empty($prev_post) ) return;
$thumb_size = 'medium_large';
?>
<section class="siblings epcl-flex" id="epcl-other-stories">
    <?php    
    if( !empty($prev_post) ){
        $prev_url = get_the_permalink($prev_post->ID);
        $prev_thumb = get_the_post_thumbnail_url($prev_post->ID, $thumb_size);
        $prev_post_meta = get_post_meta( $prev_post->ID, 'epcl_post', true );
        $post_class = epcl_get_primary_category( '', $prev_post_meta, $prev_post->ID );
    }
    ?>
    <?php if( !empty($prev_post) ): ?>
        <article <?php post_class('prev epcl-flex '.$post_class); ?>>
            <div class="info">
                <p><?php echo esc_html__('Previous Article', 'zento'); ?></p>
                <h4 class="title usmall white fw-semibold"><?php echo get_the_title($prev_post); ?></h4>
            </div>
            <span class="arrow"><svg class="icon large"><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#left-arrow"></use></svg></span>
            <a href="<?php echo esc_url($prev_url); ?>" class="full-link" aria-label="<?php echo esc_attr__('Previous Article', 'zento'); ?>"></a>
        </article>
    <?php endif; ?>

    <?php
    if( !empty($next_post) ){
        $next_url = get_the_permalink($next_post->ID);
        $next_thumb = get_the_post_thumbnail_url($next_post->ID, $thumb_size);
        $next_post_meta = get_post_meta( $next_post->ID, 'epcl_post', true );
        $post_class = epcl_get_primary_category( '', $next_post_meta, $next_post->ID );
    }
    ?>

    <?php if( !empty($next_post) ): ?>
        <?php if( !empty($prev_post) ): ?>
            <div class="separator hide-on-mobile"></div>
        <?php endif; ?>
        <article <?php post_class('next epcl-flex '.$post_class); ?>>
            <div class="info">
                <p><?php echo esc_html__('Next Article', 'zento'); ?></p>
                <h4 class="title usmall white fw-semibold"><?php echo get_the_title($next_post); ?></h4>
            </div>
            <span class="arrow"><svg class="icon large"><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#right-arrow"></use></svg></span>
            <a href="<?php echo esc_url($next_url); ?>" class="full-link" aria-label="<?php echo esc_attr__('Next Article', 'zento'); ?>"></a>
        </article>
    <?php endif; ?>

    <div class="clear"></div>

</section>