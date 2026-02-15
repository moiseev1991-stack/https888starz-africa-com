<?php

$widget_id = 'epcl_ads_fluid';

$args = array(
    'title'       => esc_html_x('(EP) Fluid Ads', 'admin', 'zento'),
    'classname'   => '',
    'description' => esc_html_x('Display a fluid ads. Note: the max ads width is 300px and height is unlimited.', 'admin', 'zento'),
    'fields'      => array(
        array(
            'id' => 'title',
            'type' => 'text',
            'title' => esc_html_x('Title:', 'admin', 'zento'),
            'default' => 'Advertising'
        ),
        array(
            'id' => 'width',
            'type' => 'number',
            'title' => esc_html_x('Width of advertise to show:', 'admin', 'zento'),
            'default' => 250,
            'unit' => 'px'
        ),
        array(
			'id' => 'align',
			'type' => 'button_set',
            'inline' => true,
			'title' => esc_html_x( 'Align:', 'admin', 'zento'),
			'options'   => array(
				'left' => esc_html_x('Left', 'admin', 'zento'),
                'center' => esc_html_x('Center', 'admin', 'zento'),
                'right' => esc_html_x('Right', 'admin', 'zento'),
			),
			'default' => 'center'
        ),
        array(
			'id' => 'ads',
			'type' => 'code_editor',
			'title' => esc_html_x( 'Ads Code:', 'admin', 'zento'),
            'settings' => array(
                'theme'  => 'dracula',
                'mode'   => 'htmlmixed',
                'tabSize' => 4,
                // 'lineWrapping' => true
            ),
            // 'attributes' => array(
            //     'rows' => 5,
            //     'style' => 'width:100%;'
            // ),
            'sanitize' => false
        ),
    )
);

function epcl_ads_fluid( $args, $instance ){
    // WP 5.9 Patch: always disable widget preview in the backend
    if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
        return false;
    }
    if( epcl_is_amp() ) return;
    extract($args);
    $title = apply_filters('widget_title', $instance['title']);
    echo $before_widget;
        if($title) echo $before_title.$title.$after_title;
        if($instance['ads']){
            echo '<div class="epcl-banner-wrapper">';
                echo '<div class="epcl-banner align'.$instance['align'].'" style="max-width: '.$instance['width'].'px;">'.$instance['ads'].'</div>';
            echo '</div>';
        }
    echo $after_widget;
}   

$wp_widget_factory->register( EPCL_CreateWidget::instance( $widget_id, $args ) );

// function mi_admin_script($hook) {
//     $version = '6.65.7';
//     $cdn_url = 'https://cdn.jsdelivr.net/npm/codemirror@';
//     if ('widgets.php' != $hook) {
//         return;
//     }
//     wp_enqueue_script( 'csf-codemirror', esc_url( $cdn_url . $version .'/lib/codemirror.min.js' ), array( 'csf' ), $version, true );
//     wp_enqueue_script( 'csf-codemirror-loadmode', esc_url( $cdn_url . $version .'/addon/mode/loadmode.min.js' ), array( 'csf-codemirror' ), $version, true );
//     wp_enqueue_style( 'csf-codemirror', esc_url( $cdn_url . $version .'/lib/codemirror.min.css' ), array(), $version );

//  }
// add_action('admin_enqueue_scripts', 'mi_admin_script');