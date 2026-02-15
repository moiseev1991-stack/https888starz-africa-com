<?php
// With Sidebar
 
$options = array(
    array(
        'id' => 'posts_carousel_limit',
        'title' => esc_html__('Posts Limit', 'epcl_framework'),                    
        'desc' => esc_html__('Max number of posts to retrieve.', 'epcl_framework'),
        'type' => 'spinner',
        'min' => '3',
        'max' => '30',
        'step' => '1',
        'default' => '12',
        'dependency' => array('layout', '==', 'posts_carousel'),
    ),
    // array(
    //     'id' => 'posts_carousel_show_limit',
    //     'title' => esc_html__('Visible Items', 'epcl_framework'),                    
    //     'desc' => esc_html__('Number of visible elements, recommended: 4', 'epcl_framework'),
    //     'type' => 'spinner',
    //     'min' => '2',
    //     'max' => '6',
    //     'step' => '1',
    //     'default' => '3',
    //     'dependency' => array('layout', '==', 'posts_carousel'),
    // ),
    array (
        'id' => 'posts_carousel_enable_author',
        'title' => esc_html__('Display Author', 'epcl_framework'),
        'type' => 'switcher',
        'default' => true,
        'dependency' => array('layout', '==', 'posts_carousel'),
    ),
    array (
        'id' => 'posts_carousel_enable_reading_time',
        'title' => esc_html__('Display Reading Time', 'epcl_framework'),
        'type' => 'switcher',
        'default' => true,
        'dependency' => array('layout', '==', 'posts_carousel'),
    ),
    array (
        'id' => 'posts_carousel_enable_rating',
        'title' => esc_html__('Display Post Rating', 'epcl_framework'),
        'type' => 'switcher',
        'default' => true,
        'dependency' => array('layout', '==', 'posts_carousel'),
    ),
);

return $options;