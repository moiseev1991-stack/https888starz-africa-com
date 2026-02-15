<?php
// Popular Categories

$options =  array(
    array (
        'id' => 'popular_categories_or_text',
        'title' => esc_html__('Text before button (or..)', 'epcl_framework'),
        'subtitle' => esc_html__('Default: or...', 'epcl_framework'),
        'desc' => esc_html__('Leavy empty to remove.', 'epcl_framework'),
        'type' => 'text',
        'default' => 'or...',
        'dependency' => array('layout', '==', 'popular_categories'),  
    ),
    array (
        'id' => 'popular_categories_button_name',
        'title' => esc_html__('Button Title', 'epcl_framework'),
        'subtitle' => esc_html__('Default: Explore All', 'epcl_framework'),
        // 'desc' => esc_html__('Leavy empty to remove.', 'epcl_framework'),
        'type' => 'text',
        'default' => 'Explore All',
        'dependency' => array('layout', '==', 'popular_categories'),  
    ),
    array (
        'id' => 'popular_categories_button_url',
        'title' => esc_html__('Button URL', 'epcl_framework'),
        'desc' => esc_html__('Where the user will be redirected on click.', 'epcl_framework'),
        'type' => 'text', 
        // 'validate' => 'csf_validate_url',  
        'default' => '#',                 
        'dependency' => array('layout', '==', 'popular_categories'),
    ),
);

return $options;