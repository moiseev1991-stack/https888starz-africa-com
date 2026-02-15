<?php

$widget_id = 'epcl_posts_thumbs';

$args = array(
    'title'       => esc_html_x('(EP) Recent Posts with image', 'admin', 'zento'),
    'classname'   => '',
    'description' => esc_html_x('Display Random or Recent posts with a small image.', 'admin', 'zento'),
    'fields'      => array(
        array(
            'id' => 'title',
            'type' => 'text',
            'title' => esc_html_x('Title:', 'admin', 'zento'),
            'default' => 'Recent Posts'
        ),
        array(
            'id' => 'number',
            'type' => 'spinner',
            'title' => esc_html_x( 'Max number of elements to display:', 'admin', 'zento'),
            'default' => '4',
            'min' => '1',
            'step' => '1',
            'max' => '40',
            // 'unit' => 'Tweets'
        ),
        array(
			'id' => 'orderby',
			'type' => 'button_set',
            'inline' => true,
			'title' => esc_html_x( 'Order by:', 'admin', 'zento'),
			'options'   => array(
				'date' => esc_html_x('Recent Posts', 'admin', 'zento'),
                'rand' => esc_html_x('Random Posts', 'admin', 'zento'),
                // 'views' => esc_html_x('Post views', 'admin', 'zento'),
			),
			'default' => 'date'
        ),
        array(
			'id' => 'orderdate',
			'type' => 'select',
            'inline' => true,
			'title' => esc_html_x( 'Date:', 'admin', 'zento'),
			'options'   => array(
				'alltime' => esc_html_x('All Time', 'admin', 'zento'),
                'pastyear' => esc_html_x('Past Year', 'admin', 'zento'),
                'pastmonth' => esc_html_x('Past Month', 'admin', 'zento'),
                'pastweek' => esc_html_x('Past Week', 'admin', 'zento'),
			),
			'default' => 'alltime'
        ),
    )
);

function epcl_posts_thumbs( $args, $instance ){
    // WP 5.9 Patch: always disable widget preview in the backend
    if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
        return false;
    }
    global $epcl_theme;
    extract($args);
    $prefix = EPCL_THEMEPREFIX.'_';
    $title = apply_filters('widget_title', $instance['title']);
    $args = array(
        'posts_per_page' => $instance['number'],
        'post_type' => 'post',
        'order' => 'DESC',
        'orderby' => $instance['orderby'],
        'ignore_sticky_posts' => true
    );

    if( $instance['orderby'] == 'views' ){
        $args = array(
            'posts_per_page' => $instance['number'],
            'post_type' => 'post',
            'order' => 'DESC',
            'orderby' => 'meta_value_num',
            'meta_key' => 'views_counter',
            'ignore_sticky_posts' => true
        );
    }

    if( isset($instance['orderdate']) && $instance['orderdate'] != 'alltime' ){
        $year = date('Y');
        $month = absint( date('m') );
        $week = absint( date('W') );

        $args['year'] = $year;

        if( $instance['orderdate'] == 'pastmonth' ){
            $args['monthnum'] = $month - 1;
        }
        if( $instance['orderdate'] == 'pastweek' ){
            $args['w'] = $week - 1;
        }
        if( $instance['orderdate'] == 'pastyear' ){
            unset( $args['year'] );
            $today = getdate();
            $args['date_query'] = array(
                array(
                    'after' => $today[ 'month' ] . ' 1st, ' . ($today[ 'year' ] - 2)
                )
            );
        }
    }

    if( is_single() ){
        $args['post__not_in'] = array( get_the_ID() );
    }

    $query = new WP_Query($args);
    if( !$query->have_posts() ) return;
    echo $before_widget;
        if($title) echo $before_title.$title.$after_title;
        if(!$instance['number']) $instance['number'] = 4;

        if( $query->have_posts() ):
            while( $query->have_posts() ): $query->the_post();
                include( 'partials/loop-article.php' );
            endwhile;
            wp_reset_postdata();
        endif;

    echo $after_widget;
}   

$wp_widget_factory->register( EPCL_CreateWidget::instance( $widget_id, $args ) );