<?php

$widget_id = 'epcl_ads_125';

$args = array(
    'title'       => esc_html_x('(EP) 125x125 Ads', 'admin', 'zento'),
    'classname'   => '',
    'description' => esc_html_x('Display 125x125 grid ads.', 'admin', 'zento'),
    'fields'      => array(
        array(
            'id' => 'title',
            'type' => 'text',
            'title' => esc_html_x('Title:', 'admin', 'zento'),
            'desc' => esc_html_x('Copy and paste your ads code:', 'admin', 'zento'),
            'default' => 'Advertising',
        ),
        array(
			'id' => 'ads_1',
			'type' => 'code_editor',
			'title' => esc_html_x( 'Ads Block n&ordm; 1:', 'admin', 'zento'),
            'settings' => array(
                'theme'  => 'dracula',
                'mode'   => 'htmlmixed',
                'tabSize' => 4,
                // 'lineWrapping' => true
            ),
            'sanitize' => false
        ),
        array(
			'id' => 'ads_2',
			'type' => 'code_editor',
			'title' => esc_html_x( 'Ads Block n&ordm; 2:', 'admin', 'zento'),
            'settings' => array(
                'theme'  => 'dracula',
                'mode'   => 'htmlmixed',
                'tabSize' => 4,
                // 'lineWrapping' => true
            ),
            'sanitize' => false
        ),
        array(
			'id' => 'ads_3',
			'type' => 'code_editor',
			'title' => esc_html_x( 'Ads Block n&ordm; 3:', 'admin', 'zento'),
            'settings' => array(
                'theme'  => 'dracula',
                'mode'   => 'htmlmixed',
                'tabSize' => 4,
                // 'lineWrapping' => true
            ),
            'sanitize' => false
        ),
        array(
			'id' => 'ads_4',
			'type' => 'code_editor',
			'title' => esc_html_x( 'Ads Block n&ordm; 4:', 'admin', 'zento'),
            'settings' => array(
                'theme'  => 'dracula',
                'mode'   => 'htmlmixed',
                'tabSize' => 4,
                // 'lineWrapping' => true
            ),
            'sanitize' => false
        ),
    )
);

function epcl_ads_125( $args, $instance ){
    // WP 5.9 Patch: always disable widget preview in the backend
    if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
        return false;
    }
    extract($args);
    $title = apply_filters('widget_title', $instance['title']);
    echo $before_widget;
        if($title) echo $before_title.$title.$after_title;
        echo '<div class="epcl-banner-wrapper">';
            if($instance['ads_1'])
                echo '<div class="epcl-banner-1 epcl-banner">'.$instance['ads_1'].'</div>';
            if($instance['ads_2'])
                echo '<div class="epcl-banner-2 epcl-banner">'.$instance['ads_2'].'</div>';
            if($instance['ads_3'])
                echo '<div class="epcl-banner-3 epcl-banner">'.$instance['ads_3'].'</div>';
            if($instance['ads_4'])
                echo '<div class="epcl-banner-4 epcl-banner">'.$instance['ads_4'].'</div>';
        echo '</div>';

    echo $after_widget;
}   

$wp_widget_factory->register( EPCL_CreateWidget::instance( $widget_id, $args ) );