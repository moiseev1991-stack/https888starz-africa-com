<?php

$widget_id = 'epcl_article_info';

$args = array(
    'title'       => esc_html_x('(EP) Article Information', 'admin', 'zento'),
    'classname'   => '',
    'description' => esc_html_x('Display Detailed Article Info on Single Post.', 'admin', 'zento'),
    'fields'      => array(
        array(
            'id' => 'title',
            'type' => 'text',
            'title' => esc_html_x('Title:', 'admin', 'zento'),
            'default' => 'Article Information'
        ),
        array(
			'id' => 'display_category',
			'type' => 'switcher',
			'title' => esc_html_x( 'Display Category:', 'admin', 'zento'),
			'default' => true
        ),
        array(
			'id' => 'display_last_update',
			'type' => 'switcher',
			'title' => esc_html_x( 'Display Modified Date:', 'admin', 'zento'),
			'default' => true
        ),
        array(
			'id' => 'display_author',
			'type' => 'switcher',
			'title' => esc_html_x( 'Display Author:', 'admin', 'zento'),
			'default' => true
        ),
        array(
			'id' => 'display_read_time',
			'type' => 'switcher',
			'title' => esc_html_x( 'Display Reading Time:', 'admin', 'zento'),
			'default' => true
        ),
        array(
			'id' => 'display_rating',
			'type' => 'switcher',
			'title' => esc_html_x( 'Display Rating:', 'admin', 'zento'),
			'default' => true
        ),
    )
);

function epcl_article_info( $args, $instance ){
// WP 5.9 Patch: always disable widget preview in the backend
if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
    return false;
}
global $epcl_theme;
    extract($args);
    $title = apply_filters('widget_title', $instance['title']);
    if ( !is_single() ) {
        return;
    }
    echo $before_widget;
        if($title) echo $before_title.$title.$after_title;          
        ?>
        <div class="info meta bg-box">
            
            <?php if( $instance['display_category'] ): ?>
                <?php
                    $primary_category_id = epcl_get_primary_category_id( get_the_ID() );
                    $primary_category_name = get_the_category_by_ID($primary_category_id);
                    $primary_category_url = get_category_link($primary_category_id);
                ?>
                <div class="item meta-info">
                    <svg class="icon main-color"><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#tag-icon"></use></svg>
                    <span class="fw-semibold"><?php echo esc_html__("Category:", 'zento'); ?></span>
                    <a href="<?php echo esc_url($primary_category_url); ?>" class="tag-name"><?php echo esc_html($primary_category_name); ?></a>
                </div>
            <?php endif; ?>

            <?php if( $instance['display_last_update'] ): ?>
                <?php get_template_part( 'partials/meta-info/modified-date' ); ?>
            <?php endif; ?>

            <?php if( $instance['display_author'] ): ?>
                <?php
                    $author_id = get_the_author_meta('ID');
                    $author_name = get_the_author();
                ?>
                <div class="item meta-info">
                    <svg class="icon main-color"><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#author-icon"></use></svg>
                    <span class="fw-semibold"><?php echo esc_html__("Author:", 'zento'); ?></span>
                    <a href="<?php echo get_author_posts_url($author_id); ?>" class="author-name"><?php echo esc_html($author_name); ?></a>
                </div>
            <?php endif; ?>

            <?php if( $instance['display_read_time'] ): ?>
                <div class="min-read meta-info">
                    <svg class="icon main-color"><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#reading-icon"></use></svg>
                    <span class="fw-semibold"><?php echo esc_html__("Reading time:", 'zento'); ?></span>
                    <?php
                    $reading_time = epcl_reading_time( get_the_content() );
                    printf( esc_html__( '%d Min', 'zento' ), $reading_time );
                    ?>
                </div>
            <?php endif; ?>

            <?php if( $instance['display_rating'] ): ?>
                <?php get_template_part( 'partials/meta-info/rating' ); ?>
            <?php endif; ?>

        </div>
    <?php  
    echo $after_widget;
}   

$wp_widget_factory->register( EPCL_CreateWidget::instance( $widget_id, $args ) );

/*
<div class="item meta-info">
        <!-- <svg class="icon main-color"><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#author-icon"></use></svg>  -->
        
        <a href="<?php echo get_author_posts_url($author_id); ?>" class="author"><?php if($author_avatar): ?>
<img class="author-image cover" loading="lazy" fetchpriority="low" decoding="async" src="<?php echo esc_url($author_avatar); ?>" alt="<?php echo esc_attr($author_name); ?>">                     
<?php endif; ?> <span class="fw-semibold"><?php echo esc_html__("Author:", 'zento'); ?></span> <?php echo esc_html($author_name); ?></a>
    </div>

    */