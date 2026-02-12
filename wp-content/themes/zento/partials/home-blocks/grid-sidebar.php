<?php
$epcl_theme = epcl_get_theme_options();
$epcl_module = epcl_get_module_options();

add_filter( 'excerpt_length', 'epcl_small_excerpt_length', 999 );
add_filter( 'the_title', 'epcl_grid_title_length', 999, 2 );

$args = array('post_type' => 'post', 'paged' => get_query_var('paged') );

// Assign image layout from Theme Options
$layout_style = '';
if( is_home() && epcl_get_option('layout_style_posts_page', 'text') == 'image' ){
    $layout_style = 'image';
}
if( !is_home() && epcl_get_option('layout_style_archives', 'text') == 'image' ){
    $layout_style = 'image';
}
if( isset($epcl_module['layout_style']) && $epcl_module['layout_style'] == 'image' ){
    $layout_style = 'image';
}

if( is_page_template('page-templates/home.php') ){

    $var = is_front_page() ? 'page' : 'paged';
    $paged = ( get_query_var($var) ) ? get_query_var($var) : 1;
    $args['paged'] = $paged;

    // Check common arguments from EPCL Module
    $extra_args = epcl_posts_lists_args( $epcl_module );

    if( !empty($extra_args) ){
        $args = array_merge( $args, $extra_args );
    } 

}

$custom_query = new WP_Query($args);
$custom_query = epcl_calculate_offset_pages($custom_query, $epcl_module);

if( !is_page_template('page-templates/home.php') ){
    $custom_query = $wp_query;
}

$grid_posts_column = 2;
$module_class = '';
if( !empty($epcl_module) ){  
    if( $epcl_module['grid_enable_masonry'] || !isset($epcl_module['grid_enable_masonry']) ){
		$module_class .= ' enable-masonry';
    } 
// If is not a module always enable masonry
}else{
    $module_class .= ' enable-masonry';
}

if( !is_active_sidebar('epcl_sidebar_home') ){
    $module_class .= ' no-sidebar';
}

$enable_pagination = true;
if( isset($epcl_module['enable_pagination']) && $epcl_module['enable_pagination'] == false ){
    $enable_pagination = false;
}

set_query_var('epcl_module_style', 'grid');

?>

<div class="module-wrapper content grid-container <?php echo esc_attr($module_class); ?>" id="<?php echo wp_unique_id('epcl-grid-sidebar-'); ?>" data-aos="fade-up">
   
    <!-- start: .content-wrapper -->
    <div class="content-wrapper grid-sidebar">

        <!-- start: .left-content -->
        <div class="left-content grid-70">

            <?php if( isset($epcl_module['module_title']) && $epcl_module['module_title'] != '' ): ?>
                <h2 class="title bordered medium"><svg class="decoration"><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#title-decoration"></use></svg><?php echo esc_html( $epcl_module['module_title'] ); ?></h2>
            <?php endif; ?>

            <?php if( $custom_query->have_posts() ): ?>
                <!-- start: .articles -->
                <div class="articles grid-posts grid-sidebar epcl-row" data-columns="<?php echo absint($grid_posts_column); ?>">
                    <?php while( $custom_query->have_posts() ): $custom_query->the_post(); ?>
                        <?php if( $layout_style =='image'): ?>
                            <?php get_template_part('partials/loops/grid-article-image'); ?>
                        <?php else: ?>
                            <?php get_template_part('partials/loops/grid-article'); ?>
                        <?php endif; ?>         
                    <?php endwhile; ?>
                </div>
                <!-- end: .articles -->

                <?php if( $enable_pagination ): ?>
                    <?php epcl_pagination($custom_query); ?>
                <?php endif; ?>

                <?php wp_reset_postdata(); ?>

            <?php else: ?>
                <?php get_template_part('partials/no-results'); ?>                    
            <?php endif; ?>    

        </div>
        <!-- end: .left-content -->

        <?php get_sidebar(); ?>

        <div class="clear"></div>

    </div>
    <!-- end: .content-wrapper -->

</div>
