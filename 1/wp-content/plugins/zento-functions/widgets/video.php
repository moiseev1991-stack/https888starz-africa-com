<?php

$widget_id = 'epcl_video';

$args = array(
    'title'       => esc_html_x('(EP) Video', 'admin', 'zento'),
    'classname'   => '',
    'description' => esc_html_x('Display a Youtube or Vimeo video.', 'admin', 'zento'),
    'fields'      => array(
        array(
            'id' => 'title',
            'type' => 'text',
            'title' => esc_html_x('Title:', 'admin', 'zento'),
            'default' => '',
        ),
        array(
			'id' => 'type',
			'type' => 'button_set',
            'inline' => true,
			'title' => esc_html_x( 'Type:', 'admin', 'zento'),
			'options'   => array(
				'youtube' => esc_html_x('Youtube', 'admin', 'zento'),
                'vimeo' => esc_html_x('Vimeo', 'admin', 'zento'),
			),
			'default' => 'youtube'
        ),
        array(
            'id' => 'url',
            'type' => 'text',
            'title' => esc_html_x('URL:', 'admin', 'zento'),
            'default' => '',
        ),
        array(
            'id' => 'height',
            'type' => 'number',
            'title' => esc_html_x( 'Height:', 'admin', 'zento'),
            'default' => '250',
            'min' => '100',
            'step' => '10',
            'max' => '999',
            'unit' => 'px'
        ),

    )
);

function epcl_video( $args, $instance ){
    // WP 5.9 Patch: always disable widget preview in the backend
    if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
        return false;
    }
    extract($args);
    $title = apply_filters('widget_title', $instance['title']);
    $width = '100%';
    $height = 250;
    if($instance['height']) $height = $instance['height'];
    echo $before_widget;
        if($title) echo $before_title.$title.$after_title;
        if($instance['type'] == 'youtube'){
            $url = $instance['url'];
            preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $url, $matches);
            $video_url ='https://www.youtube.com/embed/'.$matches[0].'?rel=0&showinfo=0';
                $media_code = '<div class="ep-shortcode ep-video"><iframe width="'.$width.'" height="'.$height.'" src="'.$video_url.'" frameborder="0" allowfullscreen></iframe></div>';

        }elseif($instance['type'] == 'vimeo'){
            $result = preg_match('/(\d+)/', $instance['url'], $matches);
            if($result) $vimeo_id = $matches[0]; else $vimeo_id = $instance['url'];
            $media_code = '<div class="ep-shortcode ep-video"><iframe src="https://player.vimeo.com/video/'.$vimeo_id.'" width="'.$width.'" height="'.$height.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>';
        }
        echo $media_code;
    echo $after_widget;
}   

$wp_widget_factory->register( EPCL_CreateWidget::instance( $widget_id, $args ) );