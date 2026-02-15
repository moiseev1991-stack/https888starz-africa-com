<?php

$widget_id = 'epcl_about';

$args = array(
    'title'       => esc_html_x('(EP) About me', 'admin', 'zento'),
    'classname'   => '',
    'description' => esc_html_x('Display about author section.', 'admin', 'zento'),
    'fields'      => array(
        array(
            'id' => 'title',
            'type' => 'text',
            'title' => esc_html_x('Title:', 'admin', 'zento'),
            'default' => 'About Me'
        ),
        array(
            'id' => 'position',
            'type' => 'text',
            'title' => esc_html_x('Position:', 'admin', 'zento'),
            'desc' => esc_html_x('e.g. Founder & Editor', 'admin', 'zento'),
        ),
        array(
			'id' => 'author',
			'type' => 'select',
            'inline' => true,
			'title' => esc_html_x( 'Author:', 'admin', 'zento'),
            'desc' => esc_html_x('Select an author to display all his information. Remember to fill your profile (website, Twitter and Facebook.)', 'admin', 'zento'),
			'options' => 'users'
        ),
    )
);

function epcl_about( $args, $instance ){
// WP 5.9 Patch: always disable widget preview in the backend
if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
    return false;
}
global $epcl_theme;
    extract($args);
    $title = apply_filters('widget_title', $instance['title']);
    $author_id =  $instance['author'];
    echo $before_widget;
        if($title) echo $before_title.$title.$after_title;
        if( $author_id ):
            
            $user_meta = get_user_meta( $author_id, 'epcl_user', true );       
            $author_name = get_the_author_meta('display_name', $author_id);
            $author_url = get_author_posts_url($author_id);
            $author_avatar = epcl_get_author_avatar($user_meta, $author_id);
            $website = get_the_author_meta('user_url', $author_id);             

        ?>
        <div class="bg-box section">
            <div class="epcl-flex">
                <?php if($author_avatar): ?>
                    <div class="avatar">                            
                        <a href="<?php echo esc_url( $author_url ); ?>" class="thumb"><img class="fullimage cover lazy" src="<?php echo epcl_placeholder(); ?>" data-src="<?php echo esc_url( $author_avatar ); ?>" alt="<?php echo sprintf( esc_attr__('Avatar Image of %s', 'zento'), $author_name ); ?>"><span class="screen-reader-text"><?php echo esc_html($author_name); ?></span></a>                            
                    </div>
                <?php endif; ?>
                <div class="info">
                    <h3 class="title usmall author-name underline-effect no-margin"><a href="<?php echo esc_url( $author_url ); ?>"><?php echo esc_html( $author_name ); ?></a></h3>
                    <?php if( isset( $instance['position'] ) ): ?>
                        <p class="founder"><?php echo esc_html( $instance['position'] ); ?></p>
                    <?php endif; ?>             
                </div>
            </div>
            <div class="bio">
                <p><?php the_author_meta('description', $author_id); ?></p>
            </div> 
            
            <div class="social">                                
                <?php if( isset($user_meta['twitter']) && $user_meta['twitter'] ): ?>
                    <a href="<?php echo esc_url( $user_meta['twitter'] ); ?>" class="twitter tooltip" data-title="<?php esc_attr_e('Follow me on Twitter', 'zento'); ?>" target="_blank">
                        <svg class="icon"><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#twitter-icon"></use></svg>
                        <?php esc_html_e('Twitter', 'zento'); ?>
                        <span class="screen-reader-text"><?php esc_html_e('Follow me on Twitter', 'zento'); ?></span>
                    </a>
                <?php endif; ?>
                <?php if( isset($user_meta['facebook']) && $user_meta['facebook'] ): ?>
                    <a href="<?php echo esc_url( $user_meta['facebook'] ); ?>" class="facebook tooltip" data-title="<?php esc_attr_e('Follow me on Facebook', 'zento'); ?>" target="_blank">
                        <svg class="icon"><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#facebook-icon"></use></svg>
                        <?php esc_html_e('Facebook', 'zento'); ?>
                        <span class="screen-reader-text"><?php esc_html_e('Follow me on Facebook', 'zento'); ?></span>
                    </a>
                <?php endif; ?>
                <?php if($website): ?>
                    <a href="<?php echo esc_url($website); ?>" class="website tooltip" data-title="<?php esc_attr_e('Website', 'zento'); ?>: <?php echo esc_url($website); ?>" target="_blank">
                        <svg class="icon main-color"><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#website-icon"></use></svg>
                        <?php esc_html_e('Website', 'zento'); ?>
                        <span class="screen-reader-text"><?php esc_html_e('Website', 'zento'); ?></span>
                    </a>
                <?php endif; ?>
                <?php
                    if( !empty($user_meta['custom_social']) ){
                        foreach( $user_meta['custom_social'] as $cs ){
                            $cs_id = 'custom-'.sanitize_title($cs['social_name']);
                            if( !empty($cs['social_icon']['url']) ){
                                $id = sanitize_title($cs['social_name']);
                                $icon = '<img src="'.esc_url( $cs['social_icon']['url'] ).'" alt="'.sprintf( esc_attr__('Follow me on %s', 'zento'), $cs['social_name'] ).'" loading="lazy">';
                                $url = $cs['social_url'];
                                echo '<a href="'.$url.'" class="custom-social tooltip '.$cs_id.'" target="_blank" rel="nofollow noopener" data-title="'.sprintf( esc_attr__('Follow me on %s', 'zento'), $cs['social_name'] ).'"><span class="icon '.$cs_id.'">'.$icon.'</span><span class="name">'.esc_html($cs['social_name']).'</span></a>';
                            }
                        }
                    }
                ?>
            </div>
        </div>
        <?php
        endif;
        
    echo $after_widget;
}   

$wp_widget_factory->register( EPCL_CreateWidget::instance( $widget_id, $args ) );