<?php

$widget_id = 'epcl_toc';

$args = array(
    'title'       => esc_html_x('(EP) Table of Contents', 'admin', 'zento'),
    'classname'   => '',
    'description' => esc_html_x('Display TOC on Single Post.', 'admin', 'zento'),
    'fields'      => array(
        array(
            'id' => 'title',
            'type' => 'text',
            'title' => esc_html_x('Title:', 'admin', 'zento'),
            'default' => 'Table of Contents'
        ),
        // array(
		// 	'id' => 'insert_anchors',
		// 	'type' => 'switcher',
		// 	'title' => esc_html_x( 'Automatically Add Anchors', 'admin', 'zento'),
        //     'desc' => sprintf( esc_html_x( 'This will search for any H1, H2, H3 and add a safe anchor (ID). You can disable this and add them manually, ref: %s', 'admin', 'zento'), '<a href="https://prnt.sc/9ii-AG1U3VJj" target="_blank">Check Example</a>'),
		// 	'default' => true
        // ),
        array(
			'id' => 'max_height',
			'type' => 'number',
			'title' => esc_html__('Max Height in pixels (Optional)', 'epcl_framework'),
			'subtitle' => esc_html__('Default: none', 'epcl_framework'),
			'desc' => esc_html__('This will automatically add a Scroll Bar if max height is reached. e.g. 250', 'epcl_framework'),
            'default' => '',
            'unit' => 'px'
        ),
    )
);

function epcl_toc( $args, $instance ){
// WP 5.9 Patch: always disable widget preview in the backend
if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
    return false;
}
global $epcl_theme;
    extract($args);
    $title = apply_filters('widget_title', $instance['title']);   
    if(isset($instance['insert_anchors']) && $instance['insert_anchors'] ) $instance['insert_anchors'] = $instance['insert_anchors'];
    else $instance['insert_anchors'] = true;
    if(isset($instance['max_height']) && $instance['max_height'] !== '' ) $instance['max_height'] = $instance['max_height'];
    else $instance['max_height'] = '';
    // Don't display the widget if it's not a single post or a default page without template
    if ( !is_single() && ( is_page_template() || is_home() ) ) {
        return;
    }
    echo $before_widget;
        if($title) echo $before_title.$title.$after_title;          
        ?>
        <div class="toc" data-heading-selector="<?php echo esc_attr( epcl_get_option('toc_heading_selector', "h1, h2, h3, h4, h5") ); ?>" <?php if($instance['max_height']): ?>style="max-height: <?php echo esc_attr($instance['max_height']); ?>px;"<?php endif; ?>></div>
    <?php  
    echo $after_widget;
}   

$wp_widget_factory->register( EPCL_CreateWidget::instance( $widget_id, $args ) );