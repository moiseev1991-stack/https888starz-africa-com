<?php
$title_class = '';
if( isset($args['title_class']) ){
    $title_class = $args['title_class'];
}
?>
<div class="info textcenter">    
    <h1 class="main-title title <?php echo esc_attr($title_class); ?>"><?php the_title(); ?></h1>
    <!-- start: .meta -->
    <div class="meta small inline">
        <?php if( epcl_get_option( 'enable_single_meta_data', true ) ): ?>
            <time datetime="<?php the_time('Y-m-d'); ?>">
                <svg class="icon main-color"><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#calendar-icon"></use></svg> 
                <span class="fw-semibold"><?php echo esc_html__("Published:", 'zento'); ?></span> <?php the_time( get_option('date_format') ); ?>
            </time>
        <?php endif; ?>   
        <?php if( epcl_get_option( 'enable_single_author_top', false ) ): ?>
            <span class="meta-info"><?php get_template_part('partials/meta-info/author'); ?></span>
        <?php endif; ?>
        <?php if( epcl_get_option( 'enable_single_reading_time', false ) ): ?>
            <?php get_template_part('partials/meta-info/reading-time'); ?>
        <?php endif; ?>
        <?php if( epcl_get_option( 'enable_single_views_counter', false ) ): ?>
            <?php get_template_part('partials/meta-info/views-counter'); ?>
        <?php endif; ?>
        <?php if( epcl_get_option( 'enable_single_rating', false ) ): ?>
            <?php get_template_part('partials/meta-info/rating'); ?> 
        <?php endif; ?>        
        <div class="clear"></div>
    </div>
    <!-- end: .meta -->             
</div>