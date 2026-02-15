<?php

function mytheme_add_post_attributes(){
    add_post_type_support('post', 'page-attributes');
}
add_action('init', 'mytheme_add_post_attributes', 500);

/**
 * Add the menu_order property to the post object being saved
 *
 * @param \WP_Post|\stdClass $post
 * @param WP_REST_Request $request
 *
 * @return \WP_Post
 */
function epcl_pre_insert_post($post, \WP_REST_Request $request){
    $body = $request->get_body();
    if ($body) {
        $body = json_decode($body);
        if (isset($body->menu_order)) {
            $post->menu_order = $body->menu_order;
        }
    }

    return $post;
}
add_filter('rest_pre_insert_post', 'epcl_pre_insert_post', 12, 2);


/**
 * Load the menu_order property for frontend display in the admin
 *
 * @param \WP_REST_Response $response
 * @param \WP_Post $post
 * @param \WP_REST_Request $request
 *
 * @return \WP_REST_Response
 */
function epcl_prepare_post(\WP_REST_Response $response, $post, $request){
    $response->data['menu_order'] = $post->menu_order;

    return $response;
}
add_filter('rest_prepare_post', 'epcl_prepare_post', 12, 3);

$widget_id = 'epcl_topics_index';

$args = array(
    'title'       => esc_html_x('(EP) Topics Index', 'admin', 'zento'),
    'classname'   => '',
    'description' => esc_html_x('A widget that displays multiple articles, organized by categories for easy and quick navigation.', 'admin', 'zento'),
    'fields'      => array(
        array(
            'id' => 'title',
            'type' => 'text',
            'title' => esc_html_x('Title:', 'admin', 'zento'),
            'default' => 'Topics Index'
        ),
        array(
            'id' => 'cat_limit',
            'title' => esc_html_x( 'Category Limit:', 'admin', 'zento'),
            'type' => 'spinner',
            'desc' => esc_html_x( 'Max number of Categories to display.', 'admin', 'zento'),
            'default' => '10',
            'min' => '1',
            'step' => '1',
            'max' => '40',
            // 'unit' => 'Tweets'
        ),
        array(
            'id' => 'cat_exclude',
            'title' => esc_html_x('Excluded Categories', 'admin', 'zento'),
            'type' => 'select',
            'desc' => esc_html_x('(Optional) Usefull if you dont want specific categories. Ctrl (CMD on Mac) + Click to select multiple categories.', 'admin', 'zento'),
            'chosen' => false,
            'multiple' => true,
            'sortable' => true,
            'options' => 'categories'
        ),
        array(
			'id' => 'cat_orderby',
			'type' => 'radio',
            'inline' => true,
			'title' => esc_html_x( 'Category Order by:', 'admin', 'zento'),
			'options'   => array(
				'name' => esc_html_x('Name', 'admin', 'zento'),
                'slug' => esc_html_x('Slug', 'admin', 'zento'),
                'count' => esc_html_x('Count', 'admin', 'zento'),
			),
			'default' => 'name'
        ),
        array(
			'id' => 'cat_order',
			'type' => 'radio',
            'inline' => true,
			'title' => esc_html_x( 'Category Order:', 'admin', 'zento'),
			'options'   => array(
				'ASC' => esc_html_x('Ascendant', 'admin', 'zento'),
                'DESC' => esc_html_x('Descendant', 'admin', 'zento'),
			),
			'default' => 'ASC'
        ),
        array(
            'id' => 'post_limit',
            'type' => 'spinner',
            'title' => esc_html_x( 'Posts Limit:', 'admin', 'zento'),
            'desc' => esc_html_x( 'Max number of articles per category to display:', 'admin', 'zento'),
            'default' => '10',
            'min' => '1',
            'step' => '1',
            'max' => '40',
            // 'unit' => 'Posts'
        ),
        array(
			'id' => 'post_orderby',
			'type' => 'radio',
            'inline' => true,
			'title' => esc_html_x( 'Posts Order by:', 'admin', 'zento'),
			'options'   => array(
                'date' => esc_html_x('Recent Posts', 'admin', 'zento'),
                'post_name' => esc_html_x('Slug', 'admin', 'zento'),
                'menu_order' => esc_html_x('Menu Order', 'admin', 'zento'),
                'rand' => esc_html_x('Random Posts', 'admin', 'zento'),
                // 'views' => esc_html_x('Post views', 'admin', 'zento'),             
			),
			'default' => 'date'
        ),
        array(
			'id' => 'post_order',
			'type' => 'radio',
            'inline' => true,
			'title' => esc_html_x( 'Post Order:', 'admin', 'zento'),
			'options'   => array(
				'ASC' => esc_html_x('Ascendant', 'admin', 'zento'),
                'DESC' => esc_html_x('Descendant', 'admin', 'zento'),
			),
			'default' => 'DESC'
        ),

        // array(
		// 	'id' => 'count',
		// 	'type' => 'switcher',
		// 	'title' => esc_html_x( 'Show tag counts:', 'admin', 'zento'),
		// 	'default' => 0
        // ),
    )
);

function epcl_topics_index( $args, $instance ){
    // WP 5.9 Patch: always disable widget preview in the backend
    if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
        return false;
    }
    global $epcl_theme;
    extract($args);
    $title = apply_filters('widget_title', $instance['title']); 

    echo $before_widget;
        if($title) echo $before_title.$title.$after_title;
        if(!$instance['cat_limit']) $instance['cat_limit'] = 10;
        if(!$instance['cat_exclude']) $instance['cat_exclude'] = '';
        if(!$instance['cat_orderby']) $instance['cat_orderby'] = 'name';
        if(!$instance['cat_order']) $instance['cat_order'] = 'ASC';

        if(!$instance['post_limit']) $instance['post_limit'] = 10;
        if(!$instance['post_orderby']) $instance['post_orderby'] = 'ASC';
        if(!$instance['post_order']) $instance['post_order'] = 'ASC';

        $categories = get_categories(array(
            'exclude' => $instance['cat_exclude'],
            'orderby' => $instance['cat_orderby'],
            'order' => $instance['cat_order'],
            'number' => $instance['cat_limit'],
        ));

    ?>
        <?php $i = 1; foreach($categories as $c): ?>
            <div class="clear"></div>
            <div class="item item-<?php echo esc_attr($i); ?> cat-<?php echo esc_attr($c->term_id); ?> <?php if( $i == 1 ) echo 'open'; ?>">
                <h4 class="toggle-title underline-effect">
                    <span class="epcl-number ctag ctag-<?php echo esc_attr($c->term_id); ?>"><?php echo esc_html($i++); ?></span> 
                    <a href="<?php echo get_category_link($c); ?>" class=" title small" data-id="ctag-<?php echo esc_attr($c->term_id); ?>"><?php echo esc_html($c->name); ?></a>                    
                </h4>
                <?php
                $args = array(
                    'category__in' => array($c->term_id),
                    'posts_per_page' => $instance['post_limit'],
                    // 'orderby' => $instance['post_orderby'],
                    'order' => $instance['post_order'],
                    'ignore_sticky_posts' => true
                );

                // Order by: Date, Views, Name
                if( isset($instance['post_orderby']) && $instance['post_orderby'] != '' ){
                    $args['orderby'] = $instance['post_orderby'];
                   
                    if( $instance['post_orderby'] == 'views' ){
                        $args['orderby'] = 'meta_value_num';
                        $args['meta_key'] = 'views_counter';
                    }
                }
                // echo $instance['post_orderby'].'qwe';
            
                $query = new WP_Query($args);
                if( $query->have_posts() ):                 
                ?>           
                    <span class="toggle-icon"><svg class="icon small" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z" fill="currentColor"></path></svg></span>
                    <ul class="post-list">
                        <?php while( $query->have_posts() ): $query->the_post(); ?>
                            <li><span class="fw-semibold"><?php echo $query->current_post+1; ?>.</span> <a href="<?php the_permalink(); ?>" data-id="<?php the_ID(); ?>"><?php the_title(); ?></a></li>
                        <?php endwhile; ?>
                    </ul>
                <?php wp_reset_postdata(); endif; ?>
            </div>
        <?php endforeach; ?>     
    <?php

    echo $after_widget;
}   

$wp_widget_factory->register( EPCL_CreateWidget::instance( $widget_id, $args ) );