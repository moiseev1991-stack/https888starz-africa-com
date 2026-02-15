<?php

$widget_id = 'epcl_flickr';

$args = array(
    'title'       => esc_html_x('(EP) Flickr Gallery', 'admin', 'zento'),
    'classname'   => '',
    'description' => esc_html_x('Display recent photos from Flickr.', 'admin', 'zento'),
    'fields'      => array(
        array(
            'id' => 'title',
            'type' => 'text',
            'title' => esc_html_x('Title:', 'admin', 'zento'),
            // 'desc' => esc_html_x('Copy and paste your ads code:', 'admin', 'zento'),
            'default' => 'Flickr Gallery',
        ),
        array(
            'id' => 'flickr_id',
            'type' => 'text',
            'title' => esc_html_x('Flickr id:', 'admin', 'zento'),
            'desc' => sprintf( esc_html_x('You can find your Flickr id on: %s', 'admin', 'zento') , '<a href="http://idgettr.com/" target="_blank">http://idgettr.com/</a>' ),
            'default' => '',
        ),
        array(
            'id' => 'number',
            'type' => 'spinner',
            'title' => esc_html_x( 'Max number of elements to display:', 'admin', 'zento'),
            'default' => '6',
            'min' => '1',
            'step' => '1',
            'max' => '40',
            // 'unit' => 'Tweets'
        ),

    )
);

function epcl_flickr( $args, $instance ){
    // WP 5.9 Patch: always disable widget preview in the backend
    if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
        return false;
    }
    extract($args);
    $title = apply_filters('widget_title', $instance['title']);
    echo $before_widget;
        if($title) echo $before_title.$title.$after_title;
        if(!$instance['number']) $instance['number'] = 6;
        if(!$instance['flickr_id']) esc_html_x('You must enter a valid Flickr id', 'admin', 'zento');
        if($instance['flickr_id']):
?>
            <div class="epcl-flickr-gallery" id="epcl-flickr-<?php echo wp_unique_id(); ?>" data-limit="<?php echo absint($instance['number']); ?>" data-flickr-id="<?php echo esc_attr($instance['flickr_id']); ?>">
                <div class="loading"><?php echo esc_html_x('Loading...', 'admin', 'zento'); ?></div>
                <ul class="grid-parent np-mobile"></ul>
            </div>
<?php
        endif;
    echo $after_widget;
}   

$wp_widget_factory->register( EPCL_CreateWidget::instance( $widget_id, $args ) );