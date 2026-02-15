<?php

$widget_id = 'epcl_tag_cloud';

$args = array(
    'title'       => esc_html_x('(EP) Tag Cloud', 'admin', 'zento'),
    'classname'   => '',
    'description' => esc_html_x('Display tags or categories with limit and special filters.', 'admin', 'zento'),
    'fields'      => array(
        array(
            'id' => 'title',
            'type' => 'text',
            'title' => esc_html_x('Title:', 'admin', 'zento'),
            'default' => 'Tag Cloud'
        ),
        array(
            'id' => 'limit',
            'type' => 'spinner',
            'title' => esc_html_x( 'Max number of elements to display:', 'admin', 'zento'),
            'default' => '10',
            'min' => '1',
            'step' => '1',
            'max' => '40',
            // 'unit' => 'Tweets'
        ),
		array(
			'id' => 'taxonomy',
			'type' => 'radio',
            'inline' => true,
			'title' => esc_html_x( 'Taxonomy (mode):', 'admin', 'zento'),
			'options'   => array(
				'category' => esc_html_x('Post Category', 'admin', 'zento'),
                'post_tag' => esc_html_x('Post Tags', 'admin', 'zento'),
			),
			'default' => 'category'
        ),
        array(
			'id' => 'orderby',
			'type' => 'radio',
            'inline' => true,
			'title' => esc_html_x( 'Order by:', 'admin', 'zento'),
			'options'   => array(
				'name' => esc_html_x('Name', 'admin', 'zento'),
                'count' => esc_html_x('Count', 'admin', 'zento'),
			),
			'default' => 'name'
        ),
        array(
			'id' => 'order',
			'type' => 'radio',
            'inline' => true,
			'title' => esc_html_x( 'Order:', 'admin', 'zento'),
			'options'   => array(
				'ASC' => esc_html_x('Ascendant', 'admin', 'zento'),
                'DESC' => esc_html_x('Descendant', 'admin', 'zento'),
			),
			'default' => 'ASC'
        ),
        array(
			'id' => 'count',
			'type' => 'switcher',
			'title' => esc_html_x( 'Show tag counts:', 'admin', 'zento'),
			'default' => 0
        ),
    )
);

function epcl_tag_cloud( $args, $instance ){
    // WP 5.9 Patch: always disable widget preview in the backend
    if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
        return false;
    }
    global $epcl_theme;
    extract($args);
    $title = apply_filters('widget_title', $instance['title']); 

    echo $before_widget;
        if($title) echo $before_title.$title.$after_title;
        if(!$instance['limit']) $instance['limit'] = 15;
        if(!$instance['orderby']) $instance['orderby'] = 'name';
        if(!$instance['order']) $instance['order'] = 'ASC';
        if(!$instance['taxonomy']) $instance['taxonomy'] = 'category';

        $categories = get_terms(array(
            'taxonomy' => $instance['taxonomy'],
            'orderby' => $instance['orderby'],
            'order' => $instance['order'],
            'number' => $instance['limit'],
        ));

        
        $html = '<div class="tagcloud '.$class.'">';
        $i = 0;
        foreach($categories as $c){
            $count = '';
            if( $instance['count'] ){
                $count = ' ('.$c->count.')';
            }
            $html .= '<a href="'.get_category_link($c).'" class="tag-link-'.$c->term_id.' tag-cloud-link">'.$c->name.$count.'</a>';
            $i++;
        }
        $html .= '</div>';

        echo $html;

    echo $after_widget;
}   

$wp_widget_factory->register( EPCL_CreateWidget::instance( $widget_id, $args ) );