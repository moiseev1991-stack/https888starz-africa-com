<?php
$epcl_theme = epcl_get_theme_options();
$epcl_module = epcl_get_module_options();

$index = absint( get_query_var('index') );

$column_class = 'grid-33';
$grid_posts_column = 3;
$post_class = $thumb_url = '';

$post_id = get_the_ID();
$post_meta = get_post_meta( $post_id, 'epcl_post', true );

if( isset($_GET['ads']) ){
    $epcl_theme['ads_enabled_grid_loop'] = '1';
}
// Ads integration
if( !empty($epcl_theme) && function_exists( 'epcl_render_global_ads' ) && $epcl_theme['ads_enabled_grid_loop'] == '1' && $index == ( absint($epcl_theme['ads_position_grid_loop']) - 1  ) ){
    if( $epcl_theme['ads_mobile_grid_loop'] == '0' && wp_is_mobile() ){

    }else{
        echo '<article class="index-'.esc_attr($index).' '.esc_attr($column_class).' tablet-grid-50 np-mobile">';
            epcl_render_global_ads('grid_loop');
        echo '</article>';
        $index++;
    }
}

?>

<article <?php post_class('default index-'.$index.' '.$column_class.$post_class.' tablet-grid-50 mobile-grid-100 np-mobile'); ?>>
    <div class="bg-box">

        <div class="meta small epcl-flex">
            <?php if( get_the_category() ): ?>
                <div class="tags fill-color">
                    <?php echo epcl_get_primary_category_link($post_meta, $post_id); ?>
                </div> 
            <?php endif; ?>
            <?php get_template_part('partials/meta-info/rating'); ?>
        </div>

        <header>
            <div class="info">
                <h2 class="main-title title underline-effect"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            </div>
        </header>

        <?php if( epcl_get_option('grid_display_excerpt') !== '0'): ?>
            <div class="post-excerpt">                
                <?php the_excerpt(); ?>               
                <div class="clear"></div>
            </div>  
        <?php endif; ?>
        
        <div class="clear"></div>
        
        <footer class="bottom">
            <div class="meta inline small">
                <?php if( epcl_get_option('grid_display_author') !== '0' ): ?>
                    <?php get_template_part('partials/meta-info/author'); ?>
                <?php endif; ?>
                <?php get_template_part('partials/meta-info/reading-time'); ?>
                <?php get_template_part('partials/meta-info/views-counter'); ?>
            </div>
        </footer>

        <div class="clear"></div>

    </div>

</article>

<?php $index++; set_query_var('index', $index); ?>
