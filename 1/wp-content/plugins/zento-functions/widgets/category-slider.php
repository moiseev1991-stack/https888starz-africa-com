<?php

$widget_id = 'epcl_category_slider';

$args = array(
    'title'       => esc_html_x('(EP) Category Slider', 'admin', 'zento'),
    'classname'   => '',
    'description' => esc_html_x('Display a Category Slider.', 'admin', 'zento'),
    'fields'      => array(
        array(
            'id' => 'title',
            'type' => 'text',
            'title' => esc_html_x('Title:', 'admin', 'zento'),
            'default' => 'Categories'
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
			'default' => 'DESC'
        ),
        array(
			'id' => 'count',
			'type' => 'switcher',
			'title' => esc_html_x( 'Show category count:', 'admin', 'zento'),
			'default' => true
        ),
    )
);

function epcl_category_slider( $args, $instance ){
    // WP 5.9 Patch: always disable widget preview in the backend
    if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
        return false;
    }
    if( epcl_is_amp() ) return;
    global $epcl_theme;
    extract($args);
    $title = apply_filters('widget_title', $instance['title']); 

    echo $before_widget;
        if($title) echo $before_title.$title.$after_title;
        if(!$instance['limit']) $instance['limit'] = 15;
        if(!$instance['orderby']) $instance['orderby'] = 'name';
        if(!$instance['order']) $instance['order'] = 'ASC';

        $categories = get_terms(array(
            'taxonomy' => 'category',
            'orderby' => $instance['orderby'],
            'order' => $instance['order'],
            'number' => $instance['limit'],
            'meta_query' => array(
                array(
                    'key' => 'epcl_post_categories',
                    'compare' => 'EXISTS'
                )
            )
        ));

        $thumb_size = 'epcl_classic';
        ?>

            <div class="epcl-carousel slick-slider bottom-arrows" data-show="1" data-tablet="1" data-mobile="1" data-rtl="<?php echo is_rtl(); ?>">
                <?php foreach($categories as $c): ?>
                    <?php
                    $category_meta = get_term_meta( $c->term_id, 'epcl_post_categories', true );
                    $image_url = '';
                    if( !empty($category_meta) && !empty($category_meta['archives_image']) ){
                        $thumb = wp_get_attachment_image_src( $category_meta['archives_image']['id'], $thumb_size );
                        $image_url = '';
                        if( !empty($thumb) ){
                            $image_url = $thumb[0];
                        }    
                    }
                    ?>
                    <?php if( $image_url ): ?>
                        <div class="slick-slide item">
                            <a href="<?php echo get_category_link($c); ?>" class="thumb tag-link-<?php echo esc_attr($c->term_id); ?>">
                                <img class="cover" src="<?php echo epcl_placeholder(); ?>" data-lazy="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_html($c->name); ?>">
                                <span class="name title small"><?php echo esc_html($c->name); ?></span>
                                <?php if( $instance['count'] ):?>
                                    <span class="count"><?php echo( sprintf( _n( '%s <span class="dot small"></span> Article', '%s <span class="dot small"></span> Articles', $c->count, 'zento' ), $c->count ) ); ?></span>
                                <?php endif; ?>
                            </a>
                            <div class="clear"></div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>             
            </div>
 
        <?php

    echo $after_widget;
}   

$wp_widget_factory->register( EPCL_CreateWidget::instance( $widget_id, $args ) );