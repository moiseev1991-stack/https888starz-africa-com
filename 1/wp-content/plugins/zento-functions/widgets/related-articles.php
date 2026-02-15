<?php

$widget_id = 'epcl_related_articles';

$args = array(
    'title'       => esc_html_x('(EP) Related Articles', 'admin', 'zento'),
    'classname'   => '',
    'description' => esc_html_x('Display related articles from the current post.', 'admin', 'zento'),
    'fields'      => array(
        array(
            'id' => 'title',
            'type' => 'text',
            'title' => esc_html_x('Title:', 'admin', 'zento'),
            'default' => 'Related Articles'
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
    )
);

function epcl_related_articles( $args, $instance ){
    // WP 5.9 Patch: always disable widget preview in the backend
    if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
        return false;
    }
    global $epcl_theme;
    extract($args);
    $title = apply_filters('widget_title', $instance['title']);
    $post_id = get_the_ID();
    $args = array(
        'posts_per_page' => $instance['number'],
        'category__in' => wp_get_post_categories($post_id),
        'post__not_in' => array($post_id),
        'post_type' => 'post',
        'order' => 'DESC',
        'ignore_sticky_posts' => true
    );
    $query = new WP_Query($args);
    if( !$query->have_posts() || !is_single() ) return;
    echo $before_widget;
    
        if($title) echo $before_title.$title.$after_title;
        if(!$instance['number']) $instance['number'] = 5;

        if( $query->have_posts() ):
            while( $query->have_posts() ): $query->the_post();
                include( 'partials/loop-article.php' );
            endwhile;
            wp_reset_postdata();
        endif;

    echo $after_widget;
}   

$wp_widget_factory->register( EPCL_CreateWidget::instance( $widget_id, $args ) );