<?php

/* Unique options for every EP theme */

$admin_url = EPCL_PLUGIN_URL.'/functions/admin';

$opt_name = EPCL_FRAMEWORK_VAR;

$primary_color = '#6A4EE9';
$secondary_color = '#FF2AAC';
$third_color = '#AEF3FF';
$decoration_color = '#F7DFD4';
$titles_color = '#302d55';
$text_color = '#282424';
$border_color = '#E6EDF6';
$input_bg_color = '#FFFFFF';
$background_color = '#FAF8FF';
$boxes_bg_color = '#FFFFFF';
$boxes_border_color = '#E9E8FF';
$black = '#282424';
$white = '#FFFFFF';

$main_gradient_start_color = '#FC6668';
$main_gradient_end_color = '#E10489';

/* Blog */

CSF::createSection( $opt_name, array(
	'title' => esc_html__('Styling', 'epcl_framework'),
    'icon' => 'fa fa-pencil',
    'id' => 'styling'
) );

/* Main Style */

CSF::createSection( $opt_name, array(
	'title' => esc_html__('Main Style', 'epcl_framework'),
    // 'icon' => 'el-icon-pencil',
    'parent' => 'styling',
	'fields' => array(
        array(
			'id' => 'category_custom_color',
			'type' => 'switcher',
            'title' => esc_html__('Enable Category Custom Color', 'epcl_framework'),
            'subtitle' => esc_html__('Default: ON', 'epcl_framework'),
			'desc' => __('This will apply a custom background color to every article according to the primary category color. Example: <a href="https://prnt.sc/6edOo84VXHm0" target="_blank">https://prnt.sc/6edOo84VXHm0</a>', 'epcl_framework'),
			'default' => true
        ),
        array(
			'id' => 'enable_titles_decoration',
			'type' => 'switcher',
			'title' => esc_html__('Enable decoration for Titles', 'epcl_framework'),
			'desc' => 'The decoration is displayed before every main title.',
			'default' => true
        ),
        array(
			'id' => 'enable_header_decoration',
			'type' => 'switcher',
			'title' => esc_html__('Enable decoration for Main Menu', 'epcl_framework'),
			'desc' => 'The decoration is displayed before current menu item.',
			'default' => true
        ),
	)
) );

/* Background */

CSF::createSection( $opt_name, array(
	'title' => esc_html__('Body Background', 'epcl_framework'),
    // 'icon' => 'el-icon-pencil',
    'parent' => 'styling',
	'fields' => array(
        array(
			'id' => 'background_type',
			'type' => 'button_set',
			'title' => esc_html__('Background Type', 'epcl_framework'),
			'subtitle' => '',
			'desc' => '',
            'options' => array(
                '1' => 'Image Pattern',
                '2' => 'Solid Color',
                '3' => 'Fullscreen Image',
                '4' => 'Gradient',
                // '5' => 'Default Gradient',
            ),
			'default' => '2'
		),
		array(
			'id' => 'bg_body_color',
			'type' => 'color',
			'dependency' => array('background_type', '==', '2'),
			'title' => esc_html__('Body Background Color', 'epcl_framework'),
			'desc' => esc_html__('Pick a background color for the theme.', 'epcl_framework'),
			'default' => $background_color,
			// 'validate' => 'color',
            'class' => 'epcl-hide-transparent',
            'validate' => 'csf_validate_hex_color_transparent',
		),
		array(
			'id' => 'bg_body_pattern',
			'type' => 'media',
			'dependency' => array('background_type', '==', '1'),
			'url' => true,
			'preview'=> true,
			'title' => esc_html__('Pattern Uploader', 'epcl_framework'),
			'desc' => esc_html__('You can get a lot of free patterns on http://subtlepatterns.com', 'epcl_framework'),
		),
		array(
			'id' => 'bg_body_full',
			'type' => 'media',
			'dependency' => array('background_type', '==', '3'),
			'url' => true,
			'preview'=> true,
			'title' => esc_html__('Fullscreen Image', 'epcl_framework'),
			'desc' => esc_html__('Recommended size: 1920x1080 pixels', 'epcl_framework'),
        ),
        array(
			'id' => 'bg_body_gradient',
			'type' => 'color_group',
			'dependency' => array('background_type', '==', '4'),
			'title' => esc_html__('Gradient Color', 'epcl_framework'),
			'desc' => esc_html__('Pick 2 different colors for the gradient.', 'epcl_framework'),
			// 'default' => '#485DA6',
			//// 'validate' => 'color',
            'transparent' => false,
            'options'   => array(
                'from' => esc_html__('From', 'epcl_framework'),
                'to' => esc_html__('To', 'epcl_framework'),
            ),
            'default'  => array(
                'from' => '#485DA6',
                'to'   => '#32b37b', 
            ),
        ),
	)
) );

/* Basic Styling */

CSF::createSection( $opt_name, array(
	'title' => esc_html__('Basic Styling', 'epcl_framework'),
    // 'icon' => 'el-icon-pencil',
    'parent' => 'styling',
	'fields' => array(
        array(
			'id' => 'primary_color',
			'type' => 'color',
			'title' => esc_html__('Primary color ', 'epcl_framework'),
			'default' => $primary_color,
			'subtitle' =>  esc_html__("Default: ".$primary_color, 'epcl_framework'),
            'desc' =>  esc_html__('Accent color', 'epcl_framework'),
            'transparent' => false,
            'class' => 'epcl-hide-transparent',
            'validate' => 'csf_validate_hex_color_transparent',
		),
        // array(
		// 	'id' => 'main_gradient_color',
		// 	'type' => 'color_group',
		// 	'title' => esc_html__('Main Gradient Color', 'epcl_framework'),
		// 	'desc' => __('Pick 2 different colors for the gradient.<br>This will be used for the Titles decoration and primary buttons.', 'epcl_framework'),
        //     'transparent' => false,
        //     'options'   => array(
        //         'from' => esc_html__('From', 'epcl_framework'),
        //         'to' => esc_html__('To', 'epcl_framework'),
        //     ),
        //     'default'  => array(
        //         'from' => '#FC6668',
        //         'to'   => '#E10489', 
        //     ),
        // ),
		array(
			'id' => 'secondary_color',
			'type' => 'color',
			'title' => esc_html__('Secondary color ', 'epcl_framework'),
			'default' => $secondary_color,
			'subtitle' =>  esc_html__("Default: ".$secondary_color, 'epcl_framework'),
            'desc' =>  esc_html__('Some form decorations.', 'epcl_framework'),
			// 'validate' => 'color',
            'transparent' => false,
            'class' => 'epcl-hide-transparent',
            'validate' => 'csf_validate_hex_color_transparent',
		),
		// array(
		// 	'id' => 'third_color',
		// 	'type' => 'color',
		// 	'title' => esc_html__('Third color (complementary) ', 'epcl_framework'),
		// 	'default' => $third_color,
		// 	'subtitle' =>  esc_html__("Default: ".$third_color, 'epcl_framework'),
        //     'desc' =>  esc_html__('Main links underline color', 'epcl_framework'),
		// 	// 'validate' => 'color',
        //     'transparent' => false,
        //     'class' => 'epcl-hide-transparent',
        //     'validate' => 'csf_validate_hex_color_transparent',
		// ),
        array(
			'id' => 'titles_color',
			'type' => 'color',
			'title' => esc_html__('Titles Color', 'epcl_framework'),
			'default' => $titles_color,
			'subtitle' =>  esc_html__("Default: ".$titles_color, 'epcl_framework'),
            // 'desc' =>  esc_html__('Buttons, main links, etc', 'epcl_framework'),
			// 'validate' => 'color',
            'transparent' => false,
            'class' => 'epcl-hide-transparent',
            'validate' => 'csf_validate_hex_color_transparent',
		),
		array(
			'id' => 'text_color',
			'type' => 'color',
			'title' => esc_html__('Text Color', 'epcl_framework'),
			'default' => $text_color,
			'subtitle' =>  esc_html__("Default: ".$text_color, 'epcl_framework'),
			//'validate' => 'color',
            'transparent' => false,
            'class' => 'epcl-hide-transparent',
            'validate' => 'csf_validate_hex_color_transparent',
        ),
	)
) );

/* Header */

CSF::createSection( $opt_name, array(
	'title' => esc_html__('Header', 'epcl_framework'),
    // 'icon' => 'el-icon-edit',
    'parent' => 'styling',
	'fields' => array(
        // array(
		// 	'id' => 'header_bg_color',
		// 	'type' => 'color',
		// 	'title' => esc_html__('Header Background color', 'epcl_framework'),
		// 	'default' => 'transparent',
		// 	'subtitle' =>  esc_html__('Default: transparent', 'epcl_framework'),
		// 	// 'validate' => 'color',
		// 	// 'transparent' => true
        // ),
        array(
			'id' => 'header_menu_bg_color',
			'type' => 'color',
			'title' => esc_html__('Header Menu background color', 'epcl_framework'),
			'default' => $white,
			'subtitle' =>  __("Default: $white", 'epcl_framework'),
			// 'validate' => 'color',
			// 'transparent' => true
        ),
        array(
			'id' => 'header_menu_link_color',
			'type' => 'link_color',
			'title' => esc_html__('Menu links color', 'epcl_framework'),
			'desc' => '',
			// 'validate' => 'color',
			'active' => true,
			'default' => array(
				'color' => $black,
                'hover' => $black,
                'active' => $primary_color,
			),
        ),
        array(
			'id' => 'header_submenu_link_color',
			'type' => 'link_color',
			'title' => esc_html__('Submenu links color', 'epcl_framework'),
			'desc' => '',
			// 'validate' => 'color',
			'active' => true,
			'default' => array(
				'color' => $black,
                'hover' => $primary_color,
                'active' => $primary_color,
			),
        ),
        array(
			'id' => 'header_submenu_bg_color',
			'type' => 'color',
			'title' => esc_html__('Header Submenu Background color', 'epcl_framework'),
			'default' => $white,
			'subtitle' =>  esc_html__("Default: ".$white, 'epcl_framework'),
			// 'validate' => 'color',
			'transparent' => false
        ),
        array(
			'id' => 'header_sticky_bg_color',
			'type' => 'color',
			'title' => esc_html__('Header Sticky Background color', 'epcl_framework'),
			'default' => $white,
			'subtitle' =>  esc_html__("Default: ".$white, 'epcl_framework'),
			// 'validate' => 'color',
			'transparent' => false
		),
        array(
			'id' => 'header_mobile_bg_color',
			'type' => 'color',
			'title' => esc_html__('Mobile Menu background color', 'epcl_framework'),
			'default' => $white,
			'subtitle' =>  esc_html__('Default: '.$white, 'epcl_framework'),
            'desc' =>  esc_html__('This is the mobile menu container, only appears on mobile devices.', 'epcl_framework'),
			// 'validate' => 'color',
			// 'transparent' => true
        ),
		array(
			'id' => 'header_mobile_icon_color',
			'type' => 'color',
			'title' => esc_html__('Mobile Icon color', 'epcl_framework'),
			'default' => $black,
			'subtitle' =>  esc_html__("Default: ".$black, 'epcl_framework'),
			'desc' =>  esc_html__('This is the menu bar icon, only appears on mobile to show/hide the main menu.', 'epcl_framework'),
			// 'validate' => 'color',
			'transparent' => false
		),
        array(
			'id' => 'header_mobile_link_color',
			'type' => 'color',
			'title' => esc_html__('Mobile Links color', 'epcl_framework'),
			'default' => $black,
			'subtitle' =>  esc_html__("Default: ".$black, 'epcl_framework'),
			'desc' =>  esc_html__('This is the menu bar links, only appears on mobile devices.', 'epcl_framework'),
			// 'validate' => 'color',
			'transparent' => false
		),
	)
) );

/* Content */

CSF::createSection( $opt_name, array(
	'title' => esc_html__('Content', 'epcl_framework'),
    // 'icon' => 'el-icon-edit',
    'parent' => 'styling',
	'fields' => array(
        array(
			'id' => 'selection_bg_color',
			'type' => 'color',
			'title' => esc_html__('Selection background color', 'epcl_framework'),
			'default' => '#F8F2C6',
            'subtitle' =>  esc_html__('Default: #F8F2C6', 'epcl_framework'),
            'desc' => esc_html__('You will see this event whenever a user make a text a selection.', 'epcl_framework'),
			// 'validate' => 'color',
			'transparent' => false
        ),
        array(
			'id' => 'selection_text_color',
			'type' => 'color',
			'title' => esc_html__('Selection text color', 'epcl_framework'),
			'default' => $text_color,
            'subtitle' =>  esc_html__("Default: ".$text_color, 'epcl_framework'),
            'desc' => esc_html__('You will see this event whenever a user make a text a selection.', 'epcl_framework'),
			// 'validate' => 'color',
			'transparent' => false
        ),
        array(
			'id' => 'content_border_color',
			'type' => 'color',
			'title' => esc_html__('Content border color', 'epcl_framework'),
			'default' => $border_color,
            'subtitle' =>  esc_html__("Default: ".$border_color, 'epcl_framework'),
            'desc' => esc_html__('Border color for tags, main buttons and titles.', 'epcl_framework'),
			// 'validate' => 'color',
			'transparent' => false
        ),    
        // array(
		// 	'id' => 'main_shadow_color',
		// 	'type' => 'color',
		// 	'title' => esc_html__('Main Effect Shadow color', 'epcl_framework'),
		// 	'default' => $black,
        //     'subtitle' =>  esc_html__("Default: ".$black, 'epcl_framework'),
        //     'desc' => esc_html__('Some elements has a solid black shadow, example: Featured images on loops.', 'epcl_framework'),
		// 	// 'validate' => 'color',
		// 	'transparent' => false,
        //     'class' => 'epcl-hide-transparent',
        // ), 
        array(
			'id' => 'main_boxes_color',
			'type' => 'color',
			'title' => esc_html__('Main Boxes Background color', 'epcl_framework'),
			'default' => $boxes_bg_color,
            'subtitle' =>  esc_html__("Default: ".$boxes_bg_color, 'epcl_framework'),
            'desc' => esc_html__('Sidebar uses a yellowish background to generate contrast.', 'epcl_framework'),
			// 'validate' => 'color',
			'transparent' => false,
        ),   
        array(
			'id' => 'main_boxes_border_color',
			'type' => 'color',
			'title' => esc_html__('Main Boxes Border color', 'epcl_framework'),
			'default' => $boxes_border_color,
            'subtitle' =>  esc_html__("Default: ".$boxes_border_color, 'epcl_framework'),
            'desc' => esc_html__('Sidebar uses a yellowish background to generate contrast.', 'epcl_framework'),
			// 'validate' => 'color',
			'transparent' => false,
        ),
        // array(
		// 	'id' => 'main_title_color',
		// 	'type' => 'color',
		// 	'title' => esc_html__('Main titles color', 'epcl_framework'),
		// 	'default' => $black,
		// 	'subtitle' =>  esc_html__("Default: ".$black, 'epcl_framework'),
		// 	// 'validate' => 'color',
		// 	'transparent' => false
        // ),
        // array(
		// 	'id' => 'main_title_decoration_color',
		// 	'type' => 'color',
		// 	'title' => esc_html__('Main Titles dot color', 'epcl_framework'),
		// 	'default' => $primary_color,
		// 	'subtitle' =>  esc_html__("Default: ".$primary_color, 'epcl_framework'),
		// 	// 'validate' => 'color',
		// 	'transparent' => false
		// ),
	)
) );

/* Content Width */

CSF::createSection( $opt_name, array(
	'title' => esc_html__('Content Width', 'epcl_framework'),
    // 'icon' => 'el-icon-edit',
    'parent' => 'styling',
	'fields' => array(
        array(
			'id' => 'content_width_info',
            'type' => 'subheading',
            'notice' => false,
            'title' => __('Important Information:', 'epcl_framework'),
            'subtitle' => __( 'All these options are global and will affect your whole website width. Please note, always add the correct unit at the end, e.g <b>1300px</b>.<br><br>Percentage is supported but not recommended.', 'epcl_framework'),
		),
        array(
			'id' => 'grid_container_width',
			'type' => 'text',
            'title' => esc_html__('Main container Width', 'epcl_framework'),
            'subtitle' => esc_html__('Default: 1190px', 'epcl_framework'),
            'desc' => __('The max amount of space used in the screen <b>(in pixels)</b> for your whole website.'),
            'default    ' => '1190px',
            'placeholder' => '1190px',
            'attributes'  => array(
                'style' => 'width: 100px',
            ),
        ),
        array(
			'id' => 'grid_container_large_width',
			'type' => 'text',
            'title' => esc_html__('Large Container Width', 'epcl_framework'),
            'subtitle' => esc_html__('Default: 1400px', 'epcl_framework'),
            'desc' => __('Some modules uses larger width, example: <b>Footer Widget container</b>'),
            'default    ' => '1400px',
            'placeholder' => '1400px',
            'attributes'  => array(
                'style' => 'width: 100px',
            ),
        ),
        array(
			'id' => 'grid_container_ularge_width',
			'type' => 'text',
            'title' => esc_html__('Extra Large Container Width', 'epcl_framework'),
            'subtitle' => esc_html__('Default: 1600px', 'epcl_framework'),
            'desc' => __('Some modules uses extra large width, example: <b>Category &amp; Posts carousels.</b>'),
            'default    ' => '1600px',
            'placeholder' => '1600px',
            'attributes'  => array(
                'style' => 'width: 100px',
            ),
        ),
	)
) );

/* Buttons */

CSF::createSection( $opt_name, array(
	'title' => esc_html__('Buttons/Links', 'epcl_framework'),
    // 'icon' => 'el-icon-edit',
    'parent' => 'styling',
	'fields' => array(
        array(
			'id' => 'content_link_color',
			'type' => 'link_color',
			'title' => esc_html__('General link color', 'epcl_framework'),
			'subtitle' => esc_html__("Default: $primary_color, Hover: $primary_color", 'epcl_framework'),
            'desc' => esc_html__("e.g. Single Post Links", 'epcl_framework'),
			// 'validate' => 'color',
			'active' => false,
			'default' => array(
				'color' => $primary_color,
                'hover' => $black,
			),
            'class' => 'epcl-hide-transparent',
        ),
        array(
			'id' => 'primary_button',
			'type' => 'color_group',
			'title' => esc_html__('Primary Button', 'epcl_framework'),
			'default' => $white,
            'subtitle' => esc_html__("Default: (same as main color)", 'epcl_framework'),
            'transparent' => false,
            // 'validate' => 'csf_validate_hex_color',
            'class' => 'epcl-hide-transparent',
            'options' => array(
                'color' => 'Normal',
                'hover' => 'Hover',
                'text_color' => 'Text Color',
            ),
            'default' => array(
                'color' => $primary_color,
                'hover' => $black,
                'text_color' => $white,
            )
        ),
        // array(
		// 	'id' => 'secondary_button',
		// 	'type' => 'color_group',
		// 	'title' => esc_html__('Secondary Button', 'epcl_framework'),
		// 	'default' => $black,
        //     'subtitle' => esc_html__("Default: $primary_color, Text: $white", 'epcl_framework'),
        //     'transparent' => false,
        //     // 'validate' => 'csf_validate_hex_color',
        //     'class' => 'epcl-hide-transparent',
        //     'options' => array(
        //         'background' => 'Background',
        //         'text_color' => 'Text Color',
        //     ),
        //     'default' => array(
        //         'background' => $primary_color,
        //         'text_color' => $white,
        //     )
        // ),
        // array(
		// 	'id' => 'tag_text_color',
		// 	'type' => 'link_color',
		// 	'title' => esc_html__('Default Tag button text color', 'epcl_framework'),
		// 	'subtitle' => esc_html__("Default: $primary_color, Hover: $black", 'epcl_framework'),
		// 	// 'validate' => 'color',
		// 	'active' => false,
		// 	'default' => array(
		// 		'color' => $primary_color,
        //         'hover' => $black,
		// 	),
        // ),
        // array(
		// 	'id' => 'tag_bg_color',
		// 	'type' => 'link_color',
		// 	'title' => esc_html__('Default Tag button background color', 'epcl_framework'),
		// 	'subtitle' => esc_html__("Default: $white, hover: $white", 'epcl_framework'),
		// 	// 'validate' => 'color',
		// 	'active' => false,
		// 	'default' => array(
		// 		'color' => $white,
        //         'hover' => $white,
		// 	),
        // ),
	)
) );

/* Sidebar */

CSF::createSection( $opt_name, array(
	'title' => esc_html__('Sidebar', 'epcl_framework'),
    // 'icon' => 'el-icon-edit',
    'parent' => 'styling',
	'fields' => array(
        array(
			'id' => 'sidebar_bg_color',
			'type' => 'color',
			'title' => esc_html__('Sidebar background color', 'epcl_framework'),
			'default' => 'transparent',
            'subtitle' =>  esc_html__("Default: transparent", 'epcl_framework'),
            // 'desc' => '',
			// 'validate' => 'color',
			'transparent' => true
        ),
        array(
			'id' => 'sidebar_border_color',
			'type' => 'color',
			'title' => esc_html__('Sidebar widgets border color', 'epcl_framework'),
			'default' => 'transparent',
            'subtitle' =>  esc_html__("Default: transparent", 'epcl_framework'),
            // 'desc' => '',
			// 'validate' => 'color',
			'transparent' => true
        ),
        array(
			'id' => 'sidebar_text_color',
			'type' => 'color',
			'title' => esc_html__('Sidebar text color', 'epcl_framework'),
			'default' => $text_color,
			'subtitle' =>  esc_html__("Default: ".$text_color, 'epcl_framework'),
			// 'validate' => 'color',
			'transparent' => false
		),
        array(
			'id' => 'sidebar_link_color',
			'type' => 'link_color',
			'title' => esc_html__('Sidebar links color', 'epcl_framework'),
			'subtitle' => esc_html__("Default: ".$text_color, 'epcl_framework'),
			// 'validate' => 'color',
			'active' => false,
			'default' => array(
				'color' => $text_color,
                'hover' => $primary_color,
			),
        ),

        array(
			'id' => 'sidebar_title_color',
			'type' => 'color',
			'title' => esc_html__('Sidebar Titles color', 'epcl_framework'),
			'default' => $titles_color,
			'subtitle' =>  esc_html__("Default: ".$titles_color, 'epcl_framework'),
			// 'validate' => 'color',
			'transparent' => false
		),
        // array(
		// 	'id' => 'sidebar_title_color',
		// 	'type' => 'color_group',
		// 	'title' => esc_html__('Sidebar titles color', 'epcl_framework'),
		// 	'default' => $black,
        //     'subtitle' => esc_html__("Default: $white, Text: $titles_color", 'epcl_framework'),
        //     'transparent' => false,
        //     // 'validate' => 'csf_validate_hex_color',
        //     'class' => 'epcl-hide-transparent',
        //     'options' => array(
        //         'background' => 'Background',
        //         'text_color' => 'Text Color',
        //     ),
        //     'default' => array(
        //         'background' => $white,
        //         'text_color' => $titles_color,
        //     )
        // ),
	)
) );

/* Forms */

CSF::createSection( $opt_name, array(
	'title' => esc_html__('Forms', 'epcl_framework'),
    // 'icon' => 'el-icon-edit',
    'parent' => 'styling',
	'fields' => array(
        array(
            'id'   => 'info_forms',
            'type' => 'subheading',
            'title' => esc_html__('Important:', 'epcl_framework'),
            'subtitle' => esc_html__('All these options affects contact and comments form.', 'epcl_framework')
        ),
        array(
			'id' => 'input_bg_color',
			'type' => 'color',
			'title' => esc_html__('Input box background color', 'epcl_framework'),
			'default' => $input_bg_color,
            'subtitle' =>  esc_html__('Default: '.$input_bg_color, 'epcl_framework'),
			// 'validate' => 'color',
			'transparent' => false
        ),
        array(
			'id' => 'input_text_color',
			'type' => 'color',
			'title' => esc_html__('Input box text color', 'epcl_framework'),
			'default' => $text_color,
            'subtitle' =>  esc_html__('Default: '.$text_color, 'epcl_framework'),
			// 'validate' => 'color',
			'transparent' => false
        ),
        array(
			'id' => 'label_text_color',
			'type' => 'color',
			'title' => esc_html__('Label text color', 'epcl_framework'),
			'default' => $text_color,
            'subtitle' =>  esc_html__('Default: '.$text_color, 'epcl_framework'),
			// 'validate' => 'color',
			'transparent' => false
        ),
        // array(
		// 	'id' => 'submit_bg_color',
		// 	'type' => 'color',
		// 	'title' => esc_html__('Submit button background color', 'epcl_framework'),
		// 	'default' => $primary_color,
        //     'subtitle' =>  esc_html__("Default: ".$primary_color, 'epcl_framework'),
		// 	// 'validate' => 'color',
        //     'transparent' => false,
        //     // 'class' => 'epcl-hide-transparent',
        //     'validate' => 'csf_validate_hex_color',
        // ),
        // array(
		// 	'id' => 'submit_text_color',
		// 	'type' => 'color',
		// 	'title' => esc_html__('Submit button text color', 'epcl_framework'),
		// 	'default' => $white,
        //     'subtitle' =>  esc_html__('Default: '.$white, 'epcl_framework'),
		// 	// 'validate' => 'color',
		// 	'transparent' => false
        // ),
	)
) );

/* Footer */

CSF::createSection( $opt_name, array(
	'title' => esc_html__('Footer', 'epcl_framework'),
    // 'icon' => 'el-icon-edit',
    'parent' => 'styling',
	'fields' => array(
        array(
			'id' => 'footer_bg_color',
			'type' => 'color',
			'title' => esc_html__('Footer background color', 'epcl_framework'),
			'default' => 'transparent',
            'subtitle' =>  esc_html__("Default: transparent", 'epcl_framework'),
            // 'desc' => '',
			// 'validate' => 'color',
			'transparent' => true
        ),
        // array(
		// 	'id' => 'footer_border_color',
		// 	'type' => 'color',
		// 	'title' => esc_html__('Footer border color', 'epcl_framework'),
		// 	'default' => $boxes_bg_color,
        //     'subtitle' =>  esc_html__("Default: $boxes_bg_color", 'epcl_framework'),
        //     // 'desc' => '',
		// 	// 'validate' => 'color',
		// 	'transparent' => true
        // ),
        array(
			'id' => 'footer_text_color',
			'type' => 'color',
			'title' => esc_html__('Footer text color', 'epcl_framework'),
			'default' => $text_color,
			'subtitle' =>  esc_html__('Default: '.$text_color, 'epcl_framework'),
			// 'validate' => 'color',
			'transparent' => false
		),
        array(
			'id' => 'footer_link_color',
			'type' => 'link_color',
			'title' => esc_html__('Footer links color', 'epcl_framework'),
			'subtitle' => esc_html__('Default: '.$text_color, 'epcl_framework'),
			// 'validate' => 'color',
			'active' => false,
			'default' => array(
				'color' => $text_color,
                'hover' => $primary_color,
			),
        ),
        array(
			'id' => 'footer_title_color',
			'type' => 'color',
			'title' => esc_html__('Footer Titles color', 'epcl_framework'),
			'default' => $titles_color,
			'subtitle' =>  esc_html__("Default: ".$titles_color, 'epcl_framework'),
			// 'validate' => 'color',
			'transparent' => false
		),
        // array(
		// 	'id' => 'footer_title_color',
		// 	'type' => 'color_group',
		// 	'title' => esc_html__('Footer titles color', 'epcl_framework'),
		// 	'default' => $black,
        //     'subtitle' => esc_html__("Default: $white, Text: $titles_color", 'epcl_framework'),
        //     'transparent' => false,
        //     // 'validate' => 'csf_validate_hex_color',
        //     'class' => 'epcl-hide-transparent',
        //     'options' => array(
        //         'background' => 'Background',
        //         'text_color' => 'Text Color',
        //     ),
        //     'default' => array(
        //         'background' => $white,
        //         'text_color' => $titles_color,
        //     )
        // ),
        array(
			'id' => 'footer_copyright_color',
			'type' => 'color',
			'title' => esc_html__('Footer copyright text color', 'epcl_framework'),
			'default' => $text_color,
			'subtitle' =>  esc_html__('Default: '.$text_color, 'epcl_framework'),
			// 'validate' => 'color',
			'transparent' => false
        ),
        array(
			'id' => 'footer_copyright_link_color',
			'type' => 'color',
			'title' => esc_html__('Footer copyright links color', 'epcl_framework'),
			'subtitle' => esc_html__('Default: '.$text_color, 'epcl_framework'),
			// 'validate' => 'color',
			'transparent' => false,
			'default' => $text_color
        ),
	)
) );
