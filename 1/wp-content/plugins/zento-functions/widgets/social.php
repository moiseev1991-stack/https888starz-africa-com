<?php

$widget_id = 'epcl_social';

$args = array(
    'title'       => esc_html_x('(EP) Social', 'admin', 'zento'),
    'classname'   => 'widget_epcl_social widget_nav_menu',
    'description' => esc_html_x('Display your social profiles.', 'admin', 'zento'),
    'fields'      => array(
        array(
            'id' => 'title',
            'type' => 'text',
            'title' => esc_html_x('Title:', 'admin', 'zento'),
            'desc' => ''.esc_html_x('Don\'t forget to fill your social profiles', 'admin', 'zento').' <a href="'.admin_url().'admin.php?page=epcl-theme-options#tab=social-profiles">'.esc_html_x('here.', 'admin', 'zento').'</a>',
            'default' => 'Follow me!'
        ),
    )
);

$defaults = array(
    'enable_twitter' => true,
    'enable_facebook' => true,
    'enable_instagram' => true,
    'enable_linkedin' => true,
    'enable_pinterest' => true,
    'enable_dribbble' => true,
    'enable_tumblr' => true,
    'enable_youtube' => true,
    'enable_flickr' => true,
    'enable_twitch' => true,
    'enable_vk' => true,
    'enable_telegram' => true,
    'enable_tiktok' => true,
    'enable_whatsapp' => true,
    'enable_discord' => true,
    'enable_email' => false,
    'enable_rss' => true
);

$custom_social = epcl_get_option('custom_social');

foreach( $defaults as $key => $value ){
    $label = 'Enable '.ucfirst( str_replace('enable_', '', $key) );
    $args['fields'][] = array(
        'id' => $key,
        'type' => 'checkbox',
        'class' => 'epcl-compact',
        // 'title' => esc_html_x( 'Twitter', 'admin', 'zento'),
        'label'   => $label,
        'default' => $value
    );
}

if( !empty($custom_social) ){
    foreach( $custom_social as $cs ):
        // var_dump($cs);
        if( isset( $cs['social_name']) && !empty($cs['social_icon'])  ){
            $id = sanitize_title($cs['social_name']);
            $label = 'Enable '.$cs['social_name'];
            $args['fields'][] = array(
                'id' => 'custom-'.$id,
                'type' => 'checkbox',
                'class' => 'epcl-compact',
                // 'title' => esc_html_x( 'Twitter', 'admin', 'zento'),
                'label'   => $label,
                'default' => false
            );    
        }                       
    endforeach;
}

function epcl_social( $args, $instance ){
    // WP 5.9 Patch: always disable widget preview in the backend
    if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
        return false;
    }
    global $epcl_theme;
    extract($args);
    $title = apply_filters('widget_title', $instance['title']); 
    $custom_social = epcl_get_option('custom_social');

    echo $before_widget;

        if($title) echo $before_title.$title.$after_title;
        echo '<ul class="icons  epcl-social-fill-color">';

            foreach( $instance as $key => $value ){
                if($key != 'title' && $value == '1'){
                    $id = str_replace('enable_', '', $key);
                    $icon = '<svg class="icon small"><use xlink:href="'.EPCL_THEMEPATH.'/assets/images/svg-icons.svg#'.$id.'-icon"></use></svg>';
                    $url = '';
                    if( isset($epcl_theme[$id.'_url']) ){
                        $url = $epcl_theme[$id.'_url'];
                    }                    

                    // Change icon and sanitize email
                    if( $id == 'email' ){
                        if( is_email($url) ){
                            $url = antispambot('mailto:'.$url);
                        }
                    }
                    if( $value && strpos($key, 'custom') > -1 ){
                        if( !empty($custom_social) ){
                            foreach( $custom_social as $cs ){
                                $cs_id = 'custom-'.sanitize_title($cs['social_name']);
                                if( $cs_id == $key ){
                                    $id = sanitize_title($cs['social_name']);
                                    $icon = '<img src="'.esc_url( $cs['social_icon']['url'] ).'" alt="'.$cs['social_name'].'" loading="lazy">';
                                    $url = $cs['social_url'];
                                    echo '<li><a href="'.$url.'" class="'.$cs_id.'" target="_blank" rel="nofollow noopener"><span class="name">'.$cs['social_follow_text'].'</span><span class="icon '.$cs_id.'">'.$icon.'</span></a></li>';
                                }
                            }
                        }
                    }else{
                        echo '<li><a href="'.$url.'" class="'.$id.'" target="_blank" rel="nofollow noopener"><span class="name">'.sprintf( esc_attr__('Follow me on %s', 'zento'), '<b>'.ucfirst($id).'</b>' ).' </span><span class="icon '.$id.'">'.$icon.'</span></a></li>';
                    }
                        
                }                
            }

            // if( !empty($custom_social) ){
            //     $index = 1;
            //     foreach( $custom_social as $cs ):
            //         echo 'asd';
            //         if( $cs['social_url'] != '' && !empty($cs['social_icon_white']) ){
            //             echo '<a href="'.esc_url( $cs['social_url'] ).'" class="custom-social custom-social-'.$index.'" target="_blank" rel="nofollow noopener"><span class="icon"><img src="'.esc_url( $cs['social_icon_white']['url'] ).'"></span><p class="screen-reader-text"><span>'.esc_html__('Follow me!', 'maktub').'</span></p></a>';
            //             $index++;
            //         }                            
            //     endforeach;
            // }
          
        echo '</ul>';

    echo $after_widget;
}   

$wp_widget_factory->register( EPCL_CreateWidget::instance( $widget_id, $args ) );