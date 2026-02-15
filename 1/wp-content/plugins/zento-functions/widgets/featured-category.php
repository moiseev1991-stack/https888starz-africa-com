<?php

$widget_id = 'epcl_featured_category';

$args = array(
    'title'       => esc_html_x('(EP) Posts by Category', 'admin', 'zento'),
    'classname'   => '',
    'description' => esc_html_x('Display posts from a certain category.', 'admin', 'zento'),
    'fields'      => array(
        array(
            'id' => 'title',
            'type' => 'text',
            'title' => esc_html_x('Title:', 'admin', 'zento'),
            'default' => 'Featured Posts'
        ),
        array(
            'id' => 'number',
            'type' => 'spinner',
            'title' => esc_html_x( 'Max number of elements to display:', 'admin', 'zento'),
            'default' => '5',
            'min' => '1',
            'step' => '1',
            'max' => '40',
            // 'unit' => 'Tweets'
        ),
        array(
			'id' => 'category',
			'type' => 'select',
            'inline' => true,
			'title' => esc_html_x( 'Category:', 'admin', 'zento'),
			'options' => 'categories',
            'chosen' => false,
            'ajax' => false,            
            'settings' => array(
                'width' => '100%',
            )
        ),
    )
);

function epcl_featured_category( $args, $instance ){
    // WP 5.9 Patch: always disable widget preview in the backend
    if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
        return false;
    }
    global $epcl_theme;
    extract($args);
    $title = apply_filters('widget_title', $instance['title']);
    $args = array(
        'posts_per_page' => $instance['number'],
        'cat' => $instance['category'],
        'post_type' => 'post',
        'order' => 'DESC',
        'ignore_sticky_posts' => true
    );
    
    if( is_single() ){
        $args['post__not_in'] = array( get_the_ID() );
    }
    
    $query = new WP_Query($args);
    if( !$query->have_posts() ) return;
    echo $before_widget;
        if($title) echo $before_title.$title.$after_title;
        if(!$instance['number']) $instance['number'] = 5;
        if(!$instance['category']) $instance['category'] = '';
        if( $query->have_posts() ):
            while( $query->have_posts() ): $query->the_post();
                include( 'partials/loop-article.php' );
            endwhile;
            wp_reset_postdata();
        endif;
    echo $after_widget;
}   

$wp_widget_factory->register( EPCL_CreateWidget::instance( $widget_id, $args ) );