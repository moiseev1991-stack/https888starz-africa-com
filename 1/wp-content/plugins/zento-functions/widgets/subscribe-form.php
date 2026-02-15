<?php

$widget_id = 'epcl_subscribe_form';

$args = array(
    'title'       => esc_html_x('(EP) Subscribe Form', 'admin', 'zento'),
    'classname'   => '',
    'description' => esc_html_x('Display a subscribe form, your website logo + a small text description.', 'admin', 'zento'),
    'fields'      => array(
        array(
            'id' => 'title',
            'type' => 'text',
            'title' => esc_html_x('Title (optional):', 'admin', 'zento'),            
            'default' => ''
        ),

        array(
			'id' => 'subscribe_form_text',
			'type' => 'wp_editor',
			'title' => esc_html_x('Small description (optional)', 'admin', 'zento'),
			'subtitle' => esc_html_x('HTML and Shortcodes are allowed', 'admin', 'zento'),
            // 'desc' => ''.esc_html_x('Don\'t forget to properly configure your Subscribe URL', 'admin', 'zento').' <a href="'.admin_url().'admin.php?page=epcl-theme-options#tab=subscribe-settings">'.esc_html_x('here.', 'admin', 'zento').'</a>',
            'media_buttons' => true,
		),
        array(
			'id' => 'info_image_size',
			'type' => 'submessage',
			'style' => 'info',
			'dependency' => array('logo_type', '==', '1'),
			'content' => _x('Don\'t forget to properly configure your <b>Subscribe URL</b> on the Theme Option Panel: ', 'admin', 'zento').' <a href="'.admin_url().'admin.php?page=epcl-theme-options#tab=subscribe-settings">'.esc_html_x('here.', 'admin', 'zento').'</a>',
		),
    )
);

function epcl_subscribe_form( $args, $instance ){
    // WP 5.9 Patch: always disable widget preview in the backend
    if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
        return false;
    }
    // if( epcl_is_amp() ) return;

    extract($args);
    $title = apply_filters('widget_title', $instance['title']);
    echo $before_widget;
        if($title) echo $before_title.$title.$after_title;
?>
    <div class="widget_text">
        <?php //get_template_part('partials/header/site-logo'); ?>
        <div class="textwidget">       
            <?php echo wpautop( wp_kses_post( $instance['subscribe_form_text'] ) ); ?>
        </div>
        <br>
        <form class="subscribe-form" action="<?php echo esc_url( epcl_get_option('subscribe_url') ); ?>" method="<?php echo esc_attr( epcl_get_option_text('subscribe_method', 'POST') ); ?>" target="_blank">
            <?php if( epcl_get_option('subscribe_parameters') && function_exists('epcl_render_subscribe_parameters') ): ?>
                <?php epcl_render_subscribe_parameters( epcl_get_option('subscribe_parameters') ); ?>
            <?php endif; ?>
            <label class="title small" for="email-<?php echo esc_attr($widget_id); ?>"><?php echo epcl_get_option('subscribe_label', "Let's connect"); ?></label>
            <div class="form-group">
                <input type="email" id="email-<?php echo esc_attr($widget_id); ?>" name="<?php echo esc_attr( epcl_get_option_text('subscribe_email_field_name', 'MERGE0') ); ?>" class="inputbox large" required placeholder="<?php esc_attr_e('Enter your email address', 'zento'); ?>">
                <button class="epcl-button submit absolute" type="submit"><?php esc_html_e('Get Started', 'zento'); ?><span class="loader"></span></button>
            </div>                
        </form>   
        <div class="clear"></div>
    </div>
<?php
    echo $after_widget;
}   

$wp_widget_factory->register( EPCL_CreateWidget::instance( $widget_id, $args ) );