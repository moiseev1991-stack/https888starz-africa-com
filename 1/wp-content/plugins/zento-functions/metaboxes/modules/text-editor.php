<?php
// Text Editor

$options =  array(
    array(
        'id' => 'text_editor_content',
        'title' => esc_html__('Add your description', 'epcl_framework'),
        'subtitle' => esc_html__('Shortcodes are allowed', 'epcl_framework'),
        'desc' => '',
        'type' => 'wp_editor',                    
        'media_buttons' => true,
        'dependency' => array('layout', '==', 'text_editor'),
    ),
);

return $options;