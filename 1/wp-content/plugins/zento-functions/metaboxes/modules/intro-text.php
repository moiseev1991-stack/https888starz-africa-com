<?php
// Intro Text
$dependency = array('layout', '==', 'intro_text');
$options =  array(
    array(
        'id' => 'intro_content',
        'title' => esc_html__('Add your content', 'epcl_framework'),
        'subtitle' => esc_html__('Shortcodes are allowed', 'epcl_framework'),
        'desc' => '',
        'type' => 'wp_editor',                    
        'media_buttons' => true,
        'dependency' => $dependency,
    ),
    array(
        'id' => 'intro_image',
        'title' => esc_html__('Right Image', 'epcl_framework'),
        'desc' => esc_html__('Recommended size: 1024x1024', 'epcl_framework'),
        'type' => 'media',                    
        'url' => true,
        'preview'=> true,
        'dependency' => $dependency
    ),
    array (
        'id' => 'intro_enable_morph_effect',
        'title' => esc_html__('Enable Image Animation', 'epcl_framework'),
        'desc' => esc_html__('Animate the hero image with a morph effect.', 'epcl_framework'),
        'type' => 'switcher',
        'default' => true,
        'dependency' => $dependency
    ),
    array (
        'id' => 'intro_enable_subscribe',
        'title' => esc_html__('Enable subscribe form', 'epcl_framework'),
        'desc' => esc_html__('Display the subscribe form below the content.', 'epcl_framework'),
        'type' => 'switcher',
        'default' => true,
        'dependency' => $dependency
    ),
    array(
        'id'   => 'intro_info',
        'type' => 'submessage',
        'style' => 'warning',
        'title' => '',
        'content' => _x('Don\'t forget to properly configure your <b>Subscribe URL</b> on the Theme Option Panel: ', 'admin', 'zento').' <a href="'.admin_url().'admin.php?page=epcl-theme-options#tab=subscribe-settings" target="_blank">'.esc_html_x('here.', 'admin', 'zento').'</a>',
        'dependency' => array(
            array('layout', '==', 'intro_text'),
            array('intro_enable_subscribe', '==', '1'),
        )
    )
);

return $options;