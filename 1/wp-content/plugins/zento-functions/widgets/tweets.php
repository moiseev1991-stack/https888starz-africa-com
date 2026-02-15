<?php

$widget_id = 'epcl_tweets';

$args = array(
    'title'       => esc_html_x('(EP) Recent Tweets', 'admin', 'zento'),
    'classname'   => '',
    'description' => esc_html_x('Display recent tweets.', 'admin', 'zento'),
    'fields'      => array(
        array(
            'id' => 'title',
            'type' => 'text',
            'title' => esc_html_x('Title:', 'admin', 'zento'),
        ),
        array(
            'id' => 'number',
            'type' => 'spinner',
            'title' => esc_html_x( 'Number of tweets to show:', 'admin', 'zento'),
            'default' => '3',
            'min' => '1',
            'step' => '1',
            'max' => '20',
            // 'unit' => 'Tweets'
        ),
        array(
            'id' => 'twitter_id',
            'type' => 'text',
            'title' => esc_html_x('Twitter ID: (without @)', 'admin', 'zento'),
        ),
        array(
			'id' => 'exclude_replies',
			'type' => 'switcher',
			'title' => esc_html_x( 'Exclude Replies', 'admin', 'zento'),
			'default' => 1
        ),
    )
);

function epcl_tweets( $args, $instance ){
    // WP 5.9 Patch: always disable widget preview in the backend
    if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
        return false;
    }

    extract($args);
    $title = apply_filters('widget_title', $instance['title']);
    $exclude_replies = isset( $instance[ 'exclude_replies' ] ) && $instance[ 'exclude_replies' ] ? true : false;
    echo $before_widget;
        if($title) echo $before_title.$title.$after_title;
        if(!$instance['number']) $instance['number'] = 3;
        require_once(EPCL_PLUGIN_PATH.'/twitter_api/Creare_Twitter.php');

        $twitter = new Creare_Twitter();
        $twitter->screen_name = $instance['twitter_id'];
        $twitter->not = $instance['number'];

        $twitter->consumerkey = "yxwpQgf60mGvGqHmUm3NxMc0e";
        $twitter->consumersecret = "UF97WKH1JstjGeoTuH8Ns2U9kA0H5XjJGyHnmkWQYafqE4Wswt";
        $twitter->accesstoken = "1318265592433004544-sSxwZSRz9qnx42nqdea1QI6yigqrWz";
        $twitter->accesstokensecret = "phkgLgDf4NreSHrXGXKts4uOrpbO5rKNgC9ym6Xm4szZs";
        $tweets = $twitter->getLatestTweets( $exclude_replies );
        $i = 0;
        
        if( !empty($tweets) && is_array($tweets) ){
            foreach($tweets as $t){
                if( $instance['number'] == $i ){
                    break;
                }
                echo '<p class="underline-effect"><svg class="icon"><use xlink:href="'.EPCL_THEMEPATH.'assets/images/svg-icons.svg#twitter-icon"></use></svg>'.$t['tweet'].'<br><small>'.$t['time'].'</small></p>';  
                $i++;
            }
        }else{
            echo "<p>Tweets cant be loaded.</p>";
        }

    echo $after_widget;
}   

$wp_widget_factory->register( EPCL_CreateWidget::instance( $widget_id, $args ) );