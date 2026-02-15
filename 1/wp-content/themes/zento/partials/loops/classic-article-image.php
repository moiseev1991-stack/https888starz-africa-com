<?php
$epcl_theme = epcl_get_theme_options();
$epcl_module = epcl_get_module_options();

$index = absint( get_query_var('index') );
$post_class = $optimized_image = '';
$post_id = get_the_ID();
$post_meta = get_post_meta( $post_id, 'epcl_post', true );
$post_gallery = get_post_meta( $post_id, 'epcl_post_gallery', true );
$post_meta_audio = get_post_meta( $post_id, 'epcl_post_audio', true );
$post_meta_video = get_post_meta( $post_id, 'epcl_post_video', true );

$loop_post_style = 'small-image'; // Small/Vertical image

// Override loop style if set in theme options
if( !empty($post_meta) && isset($post_meta['loop_style']) ){
    if( epcl_get_option('classic_loop_style', 'inherit') !== 'inherit' ){
        $post_meta['loop_style'] = epcl_get_option('classic_loop_style', 'inherit');
    }
}
$post_format = get_post_format();
if( defined('EPCL_PLUGIN_PATH') ){
    if( $post_format == 'gallery' || $post_format == 'video' || $post_format == 'audio' ){
        $post_meta['loop_style'] = 'classic-image'; 
    }
}

if( $post_format == 'gallery' && empty($post_gallery) ){
    $post_class .= ' no-thumb';
}

// Get loop style (small image or standard)
if( !empty($post_meta) && isset($post_meta['loop_style']) && $post_meta['loop_style'] != '' ){
    $loop_post_style = $post_meta['loop_style'];
    $post_class .= ' post-style-'.$post_meta['loop_style'];
} 
if( ($post_format !== 'gallery' ) && !has_post_thumbnail() ){
    $optimized_image = '';
    if( defined('EPCL_PLUGIN_PATH') && !empty($post_meta['optimized_image']['url']) && $post_meta['optimized_image']['url'] != ''  ){
        $optimized_image = $post_meta['optimized_image'];
    }
    if( !$optimized_image ){
        $post_class .= ' no-thumb';
    }    
}

set_query_var( 'epcl_post_style', 'classic' );
$post_class .= ( $index % 2 ) ? ' even' : ' odd';
$reading_time = epcl_reading_time( get_the_content() );
$enable_author = true;
if( !is_single() && epcl_get_option('classic_display_author', true) == '0'){
    $enable_author = false;
}
if( is_single() && epcl_get_option('enable_author_top', true) == '0'){
    $enable_author = false;
}
if( is_sticky() ){
    $post_class .= ' featured';
}

// Primary category (optional)
$post_class .= epcl_get_primary_category( ' ', $post_meta, $post_id );

?>

<article <?php post_class('default classic-large bg-box epcl-flex index-'.$index.' '.$post_class); ?>>

    <?php epcl_display_post_format( get_post_format(), $post_id );  ?> 

    <div class="info"> 
        <header>        
            <div class="meta inline small">
                <span class="hide-on-desktop-sm hide-on-tablet hide-on-mobile"><?php get_template_part('partials/meta-info/date'); ?></span>   
                <span class="hide-on-mobile"><?php get_template_part('partials/meta-info/reading-time'); ?></span>
                <span class="hide-on-desktop-sm hide-on-tablet hide-on-mobile"><?php get_template_part('partials/meta-info/views-counter'); ?></span>
                <?php get_template_part('partials/meta-info/rating'); ?>                 
            </div> 
            <h2 class="main-title title underline-effect"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <?php if( is_sticky() ): ?>
                <a href="<?php the_permalink(); ?>" class="access-icon visibility-members meta-info tooltip hide-on-mobile" data-title="<?php echo esc_attr__('Featured Article', 'zento'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M11.9998 17L6.12197 20.5902L7.72007 13.8906L2.48926 9.40983L9.35479 8.85942L11.9998 2.5L14.6449 8.85942L21.5104 9.40983L16.2796 13.8906L17.8777 20.5902L11.9998 17Z"></path></svg>      
                    <span class="screen-reader-text"><?php the_title(); ?></span>     
                </a>
            <?php endif; ?>
        </header>
        <?php if( empty($epcl_theme) || $epcl_theme['classic_display_excerpt'] !== '0' ): ?>
            <div class="post-excerpt">                    
                <?php the_excerpt(); ?>        
                <div class="clear"></div>              
            </div>  
        <?php else: ?>
            <div class="epcl-spacing"></div>
        <?php endif; ?>
        <footer class="bottom">
            <div class="meta bottom epcl-flex hide-on-mobile">
                <?php if( get_the_category() ): ?>
                    <div class="tags">
                        <?php echo epcl_render_categories(3, false, $post_meta); ?>
                    </div>                    
                <?php endif; ?>
                <?php if( epcl_get_option('classic_display_author') !== '0' ): ?>
                    <?php get_template_part('partials/meta-info/author'); ?>
                <?php endif; ?> 
            </div>
            <div class="meta inline hide-on-tablet hide-on-desktop">
                <?php if( epcl_get_option('classic_display_author') !== '0' ): ?>
                    <?php get_template_part('partials/meta-info/author'); ?>
                <?php endif; ?> 
                <?php get_template_part('partials/meta-info/date'); ?>
                <?php get_template_part('partials/meta-info/reading-time'); ?>
                <?php get_template_part('partials/meta-info/views-counter'); ?>
            </div>
        </footer>
    </div>

    <div class="clear"></div>

</article>