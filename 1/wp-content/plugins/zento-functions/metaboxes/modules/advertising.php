<?php
$options = array(
    // Advertising
    array(
        'id' => 'advertising_type',
        'type' => 'button_set',
        'title' => esc_html__('Advertising Type', 'epcl_framework'),
        'options'  => array(
            'image' => esc_html__('Image', 'epcl_framework'),
            'code' => esc_html__('External Code', 'epcl_framework'),             
        ),
        'default' => 'image',
        'dependency' => array('layout', '==', 'advertising'),
    ),
    array(
        'id' => 'advertising_image',
        'title' => esc_html__('Image', 'epcl_framework'),
        'desc' => esc_html__('Recommended size: 728x90', 'epcl_framework'),
        'type' => 'media',                    
        'url' => true,
        'preview'=> true,
        'dependency' => array(
            array('layout', '==', 'advertising'),
            array('advertising_type', '==', 'image')
        )
    ),
    array (
        'id' => 'advertising_url',
        'title' => esc_html__('URL', 'epcl_framework'),
        'desc' => esc_html__('Where the user will be redirected on click.', 'epcl_framework'),
        'type' => 'text', 
        // 'validate' => 'csf_validate_url',                   
        'dependency' => array(
            array('layout', '==', 'advertising'),
            array('advertising_type', '==', 'image')
        )
    ),
    array(                  
        'id' => 'advertising_code',
        'title' => esc_html__('Advertising Code', 'epcl_framework'),
        'desc' => esc_html__('Add custom code for your banner for example Google Adsense <script>', 'epcl_framework'),
        'type' => 'code_editor',
        'settings' => array(
            'theme'  => 'dracula',
            'mode'   => 'htmlmixed',
            'tabSize' => 4
        ),
        'dependency' => array(
            array('layout', '==', 'advertising'),
            array('advertising_type', '==', 'code')
        )
    ),
);

return $options;