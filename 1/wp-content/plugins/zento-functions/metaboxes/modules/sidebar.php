<?php
// With Sidebar
 
$options =  array(
    array (
        'id' => 'sidebar',
        'title' => esc_html__('Sidebar (optional)', 'epcl_framework'),
        'subtitle' => esc_html__('Default: Home Sidebar', 'epcl_framework'),
        'desc' => esc_html__('Use a different sidebar for this module.', 'epcl_framework'),       
        'type' => 'select',             
        'chosen' => false,
        'options' => 'sidebars',
        'default' => 'epcl_sidebar_home',
        'dependency' => array('layout', 'any', 'grid_sidebar,classic_posts_sidebar'),          
    )
);

return $options;