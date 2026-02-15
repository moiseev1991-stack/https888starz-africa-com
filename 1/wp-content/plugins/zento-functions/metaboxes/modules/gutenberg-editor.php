<?php
// Gutenberg Editor

$options =  array(
    array(
        'id' => 'gutenberg_title',
        'type' => 'subheading',
        'content' => __('Gutenberg Module Usage', 'epcl_framework'),
    ),
    array(
        'id' => 'gutenberg_usage',
        'type' => 'content',
        // 'title' => __('Usage:', 'epcl_framework'),
        'content' => __( 'This module will display&nbsp;<b>all content from the current page</b>, very useful to create more complex layouts, like &nbsp;<b>CTA, Columns, Testimonials</b>&nbsp; or any kind of&nbsp;<b>block created by third party plugins.</b>&nbsp;Example:&nbsp;<a href="https://prnt.sc/Tb7YSspdrYP7" target="_blank">back-end</a>&nbsp;and&nbsp;<a href="https://prnt.sc/q-hlgcGA2Ybq" target="_blank">front-end</a>', 'epcl_framework'),
    ),
    
    array(
        'id' => 'container_width',
        'type' => 'text',
        'title' => esc_html__('Container Max Width', 'epcl_framework'),
        'subtitle' => esc_html__('Default: 1190px', 'epcl_framework'),
        'desc' => __('The max amount of space used in the screen <b>(in pixels)</b> for this module.'),
        'default    ' => '1190px',
        'placeholder' => '1190px',
        'attributes'  => array(
            'style' => 'width: 100px',
        ),
    ),
    // array (
    //     'id' => 'container_width',
    //     'title' => esc_html__('Container Width', 'epcl_framework'),
    //     'desc' => esc_html__('Select the max container width for this module.', 'epcl_framework'),
    //     'type' => 'button_set',
    //     'options' => array(
    //         'standard' => esc_html__('Standard', 'epcl_framework'),
    //         'medium' => esc_html__('Medium', 'epcl_framework'),
    //         'large' => esc_html__('Large', 'epcl_framework'),
    //         'extra-large' => esc_html__('Extra Large', 'epcl_framework'),
    //         'fullwidth' => esc_html__('Full width', 'epcl_framework'),
    //     ),
    //     'default' => 'standard',
    // ),
);

return $options;