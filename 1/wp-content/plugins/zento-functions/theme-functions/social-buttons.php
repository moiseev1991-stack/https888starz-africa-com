<?php
function epcl_render_meta_info_comments(){
    $views = 0;
    $post_meta = get_post_meta( get_the_ID(), 'epcl_post', true );
    if( isset( $post_meta['views_counter'] ) && $post_meta['views_counter'] > 0 ){
        $views = $post_meta['views_counter'];
    } 
    $content = get_the_content();
    if( !$content ) return;       
    $reading_time = epcl_reading_time( get_the_content() );
?>
    <?php if( epcl_get_option( 'enable_global_views', false ) ): ?>
        <span class="views-counter meta-info mobile comments" title="<?php echo absint( $views ); ?> <?php esc_attr_e('Views', 'zento'); ?>"><svg><use xlink:href="#views-icon"></use></svg> <?php echo absint( $views ); ?></span>
    <?php endif; ?>

    <?php if( epcl_get_option( 'enable_global_reading_time', false ) ): ?>
        <div class="min-read meta-info" title="<?php printf( esc_attr__( '%d Min Read', 'zento' ), $reading_time ); ?>"><svg><use xlink:href="#clock-fill-icon"></use></svg> <?php echo esc_attr( $reading_time ); ?></div>
    <?php endif; ?>
<?php
}

function epcl_render_meta_info(){
    $content = get_the_content();
    if( !$content ) return;
    $reading_time = epcl_reading_time( get_the_content() );
?>
    <?php if( epcl_get_option( 'enable_global_reading_time', false ) ): ?>
        <div class="min-read"><span class="count"><?php echo esc_attr( $reading_time ); ?></span> <?php esc_html_e('Min Read', 'zento'); ?></div>
    <?php endif; ?>
<?php
}

function epcl_render_share_buttons( $position = '' ){
    global $post;
    
    $epcl_theme = epcl_get_theme_options();
    $button_class = '';
    $wrapper_class = 'epcl-share epcl-social-fill-color';
    if( $position == 'bottom' ){
        $wrapper_class = 'epcl-share-bottom epcl-social-buttons fill-color';
        $button_class = '';
    }

    $share_summary = get_the_excerpt();        
    $share_url = get_permalink();
    // $share_url = wp_get_shortlink();
    $share_title = html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8');
    $share_media = wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'large' );

    $networks = array(
        'facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . esc_url($share_url),
        'twitter' => 'http://twitter.com/share?text=' . urlencode($share_title) . '&url=' . esc_url($share_url),
        'linkedin' => 'https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode($share_url) . '&title=' . urlencode($share_title) . '&summary=' . urlencode($share_summary),
        'pinterest' => '//pinterest.com/pin/create/link/?url=' . esc_url($share_url) . '&media=' . esc_url($share_media) . '&description=' . urlencode($share_title),
        'telegram' => 'https://telegram.me/share/url?url=' . esc_url($share_url) . '&text=' . urlencode($share_title),
        'vk' => 'http://vk.com/share.php?url=' . esc_url($share_url) . '&title=' . urlencode($share_title) . '&comment=' . urlencode($share_summary),
        'email' => 'mailto:?subject=' . urlencode($share_title) . '&body=' . esc_url($share_url),
        'whatsapp' => 'https://api.whatsapp.com/send?text=' . esc_url($share_url),
    );
    ?>
        <div class="<?php echo esc_attr($wrapper_class); ?>">
            <div class="epcl-share-inner">
                <?php foreach ($networks as $network => $url) : ?>
                    <?php if (isset($epcl_theme['enable_single_' . $network]) && $epcl_theme['enable_single_' . $network] !== '0') : ?>
                        <a class="tooltip <?php echo esc_attr($network); ?> <?php echo esc_attr($button_class); ?>" rel="nofollow noopener" href="<?php echo esc_url($url); ?>" target="_blank" data-tooltip-position="top" data-title="<?php printf(esc_html__('Share on %s', 'zento'), ucfirst($network) ); ?>">
                            <svg class="icon ularge">
                                <use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#<?php echo esc_attr($network); ?>-icon"></use>
                            </svg>
                            <span class="screen-reader-text"><?php printf(esc_html__('Share on %s', 'zento'), ucfirst($network) ); ?></span>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php
}

function epcl_render_copy_permalink(){
?>
    <!-- start: .share-buttons -->
    <div class="share-buttons section np-bottom">
        <div class="title usmall fw-semibold"><?php esc_html_e('Share Article:', 'zento'); ?></div>  
        <?php epcl_render_share_buttons('bottom'); ?>
        <div class="clear"></div>
        <?php if( !epcl_is_amp() ): ?>
            <div class="permalink">
                <div class="form-group">
                    <input type="text" name="shortlink" value="<?php echo urldecode( get_the_permalink() ); ?>" class="inputbox large" id="copy-link" readonly aria-labelledby="epcl-copy-button">
                    <button id="epcl-copy-button" class="copy epcl-button submit absolute" type="submit" data-copied="<?php esc_attr_e('Copied!', 'zento'); ?>"><?php esc_html_e('Copy Link', 'zento'); ?></button>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <!-- end: .share-buttons -->
<?php
}

function epcl_render_header_social_buttons(){
    $epcl_theme = epcl_get_theme_options();
    if(empty( $epcl_theme) ) return; 
    

    $container_class = 'share-buttons';
    $button_class = '';

    if( isset($_GET['header']) ){
        $epcl_theme['header_type'] = $_GET['header'];
        if( $_GET['header'] == 'classic' ){
            $epcl_theme['enable_share_header'] = true;
        }
    }
    
    if( $epcl_theme['enable_share_header'] == false ) return;

    $container_class = 'epcl-social-buttons fill-color hide-on-desktop-sm';
    $button_class = '';
    
    if( $epcl_theme['header_type'] == 'classic' ){
        $container_class = 'epcl-social-buttons fill-color hide-on-desktop-sm';
        $button_class = '';
    }

    $networks = array(
        'facebook' => 'facebook_url',
        'twitter' => 'twitter_url',
        'linkedin' => 'linkedin_url',
        'instagram' => 'instagram_url',
        'pinterest' => 'pinterest_url',
        'dribbble' => 'dribbble_url',
        'tumblr' => 'tumblr_url',
        'youtube' => 'youtube_url',
        'flickr' => 'flickr_url',
        'vk' => 'vk_url',
        'tiktok' => 'tiktok_url',
        'telegram' => 'telegram_url',
        'rss' => 'rss_url',
        'whatsapp' => 'whatsapp_url',
        'discord' => 'discord_url',
        'twitch' => 'twitch_url',
        'email' => 'email_url'
    );

    $custom_social = epcl_get_option('custom_social');

    ?>

    <div class="hide-on-mobile hide-on-tablet <?php echo esc_attr($container_class); ?>">
        <?php
            foreach ($networks as $network => $option):
                if( epcl_get_option($option) ):
                    $value = epcl_get_option($option);
                    $url = esc_url( $value );
                    if( is_email($value) ){
                        $url = antispambot('mailto:'.$value);
                    }
                    ?>
                    <a href="<?php echo $url; ?>" class="<?php echo esc_attr($button_class); ?> <?php echo $network; ?> tooltip" data-title="<?php printf(esc_html__('Follow me on %s', 'zento'), ucfirst($network) ); ?>" target="_blank" aria-label="<?php echo ucfirst($network); ?>" rel="nofollow noopener"><svg><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#<?php echo esc_html($network); ?>-icon"></use></svg></a>
                    <?php
                endif;
            endforeach;

            if( !empty($custom_social) ):
                foreach( $custom_social as $cs ):
                    if( isset( $cs['social_name']) && !empty($cs['social_icon'])  ){
                        $id = sanitize_title($cs['social_name']);
                        $label = 'Enable '.$cs['social_name'];
                        $option = 'custom-'.$id;
                        $icon = '<img src="'.esc_url( $cs['social_icon']['url'] ).'" alt="'.$cs['social_name'].'" loading="lazy">';
                        $url = $cs['social_url'];
                        ?>
                            <a href="<?php echo $url; ?>" class="custom-icon <?php echo esc_attr($button_class); ?> <?php echo $id; ?> tooltip" data-title="<?php printf(esc_html__('Follow me on %s', 'zento'), esc_attr( $cs['social_name']) ); ?>" target="_blank" aria-label="<?php echo esc_attr( $cs['social_name']); ?>" rel="nofollow noopener"><?php echo $icon ; ?></a>
                        <?php
                        
                    }
                endforeach;
            endif;
        ?>    
    </div>
<?php
}