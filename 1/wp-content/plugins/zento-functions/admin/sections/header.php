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

/* Header */

CSF::createSection( $opt_name, array(
	'title' => esc_html__('Header', 'epcl_framework'),
	'icon' => 'fa fa-columns',
	'fields' => array(
		array(
			'id' => 'title_header',
            'type' => 'subheading',
			'title' => __( 'Headers', 'epcl_framework')
		),
		array(
			'id' => 'header_type',
			'type' => 'radio',
			'title' => esc_html__('Header Layout', 'epcl_framework'),
			'desc' => '',
			'options'   => array(
                'compact' => 'Compact',
				'minimalist' => 'Minimalist',
                'classic' => 'Classic',
                'advertising' => 'Advertising Area',
			),
			'default' => 'compact',
			'desc' => __( 'Important: if advertising area is selected, you must add your banner on <a href="#tab=advertising/header-section">Advertising section -> header.</a>', 'epcl_framework')
        ),
        array(
			'id' => 'logo_tagline',
			'type' => 'switcher',
			'title' => esc_html__('Enable Tagline Below logo', 'epcl_framework'),
			'desc' => __('Example: Just another WordPress Site.. (for classic header)', 'epcl_framework'),
			'default' => '0',
            'dependency' => array('header_type', '==', 'classic'),
        ),
        // array(
		// 	'id' => 'enable_preloader',
		// 	'type' => 'switcher',
		// 	'title' => esc_html__('Enable Preloader', 'epcl_framework'),
		// 	'desc' => __('Every time a server request is sent, a spinning circle <b>(loading status)</b> will appear close to Header section.', 'epcl_framework'),
		// 	'default' => true
        // ),
        array(
			'id' => 'enable_sticky_header',
			'type' => 'switcher',
			'title' => esc_html__('Enable sticky header', 'epcl_framework'),
			'desc' => esc_html__('Desktop and Notebooks', 'epcl_framework'),
			'default' => '1'
        ),
        array(
			'id' => 'enable_sticky_header_mobile',
			'type' => 'switcher',
			'title' => esc_html__('Enable sticky header on Mobile', 'epcl_framework'),
			'desc' => esc_html__('Mobile phones and Tablets', 'epcl_framework'),
			'default' => '1'
        ),
        array(
			'id' => 'enable_share_header',
			'type' => 'switcher',
			'title' => esc_html__('Enable Share Buttons on Header', 'epcl_framework'),
			'desc' => esc_html__('Don\'t forget to fill your social profiles', 'epcl_framework').' <a href="'.admin_url().'admin.php?page=epcl-theme-options#tab=social-profiles">'.esc_html__('here', 'epcl_framework').'.</a>',
			'default' => '0'
        ),
        array(
			'id' => 'enable_search_header',
			'type' => 'switcher',
			'title' => esc_html__('Enable Search Button on main menu', 'epcl_framework'),			
			'default' => '1'
        ),
        // array(
		// 	'id' => 'enable_scroll_submenu',
		// 	'type' => 'switcher',
		// 	'title' => esc_html__('Enable Scroll on Sub Menus', 'epcl_framework'),
		// 	'desc' => __('If you have large sub menus, it is recommendable to enable this option, but you can\'t use 2nd level menus <a href="http://prntscr.com/lal72g" target="_blank">Example Here</a>', 'epcl_framework'),
		// 	'default' => '0'
        // ),
		array(
			'id' => 'title_logo',
            'type' => 'subheading',
			'title' => __( 'Logo', 'epcl_framework')
		),
		array(
			'id' => 'logo_type',
			'type' => 'button_set',
			'title' => esc_html__('Logo Type', 'epcl_framework'),
			'subtitle' => '',
			'desc' => esc_html__('Select image if you want to upload a custom logo.', 'epcl_framework'),
			'options' => array('1' => 'Image', '2' => 'Text'),
			'default' => '2'
		),
		array(
			'id' => 'logo_icon',
			'type' => 'textarea',
            'sanitize' => false,
			'dependency' => array('logo_type', '==', '2'),
			'title' => esc_html__('Logo SVG icon (optional)', 'epcl_framework'),
			'desc' => __('You can get more SVG icons from here: <a href="https://remixicon.com/" target="_blank">https://remixicon.com/</a><br>Just click "Copy SVG icon" and paste it here.', 'epcl_framework'),
            // 'attributes' => array(
            //     'cols' => 1,
            // )
		),
		array(
			'id' => 'logo_icon_color',
			'type' => 'color',
			'dependency' => array('logo_type', '==', '2'),
			'title' => esc_html__('Logo Icon Color', 'epcl_framework'),
			'default' => $primary_color,
			// 'validate' => 'color',
			'transparent' => false
		),
        array(
			'id' => 'logo_icon_size',
            'type' => 'slider',
            'dependency' => array('logo_type', '==', '2'),
			'title' => esc_html__('Logo Icon Size', 'epcl_framework'),
			// 'subtitle' => esc_html__('Paragraphs and general content.', 'epcl_framework'),
			'desc' => esc_html__('Default: 30 pixels.', 'epcl_framework'),
			'default' => '30',
			'min' => '9',
			'step' => '1',
            'max' => '100',
            'unit' => 'px',
        ),
		array(
			'id' => 'logo_text_color',
			'type' => 'color',
			'dependency' => array('logo_type', '==', '2'),
			'title' => esc_html__('Logo Text Color', 'epcl_framework'),
			'default' => $black,
			// 'validate' => 'color',
			'transparent' => false
        ),
        array(
			'id' => 'logo_font_size_desktop',
            'type' => 'slider',
            'dependency' => array('logo_type', '==', '2'),
			'title' => esc_html__('Desktop Logo Size', 'epcl_framework'),
			// 'subtitle' => esc_html__('Paragraphs and general content.', 'epcl_framework'),
			'desc' => esc_html__('Default: 40 pixels.', 'epcl_framework'),
			'default' => '40',
			'min' => '9',
			'step' => '1',
            'max' => '100',
            'unit' => 'px',
        ),
        array(
			'id' => 'logo_font_size_mobile',
            'type' => 'slider',
            'dependency' => array('logo_type', '==', '2'),
			'title' => esc_html__('Mobile Logo Size', 'epcl_framework'),
			// 'subtitle' => esc_html__('Paragraphs and general content.', 'epcl_framework'),
			'desc' => esc_html__('Default: 40 pixels.', 'epcl_framework'),
			'default' => '40',
			'min' => '9',
			'step' => '1',
            'max' => '100',
            'unit' => 'px',
		),
        // Image logo
		array(
			'id' => 'logo_image',
			'type' => 'media',
			'dependency' => array('logo_type', '==', '1'),
			'url' => true,
			'preview'=> true,
			'title' => esc_html__('Logo Uploader', 'epcl_framework'),
			'desc' => esc_html__('Recommended sizes - width: 320px, height: 120px.', 'epcl_framework'),
		),
		array(
			'id' => 'info_image_size',
			'type' => 'submessage',
			'style' => 'info',
			'dependency' => array('logo_type', '==', '1'),
			// 'title' => esc_html__('Important!', 'epcl_framework'),
			'content' => __('You must set the half width and height of your uploaded logo.<br> Example, if your logo is 500x200 you must enter 250 in the width input field and 100 in the next one.', 'epcl_framework')
		),
		array(
			'id' => 'logo_width',
			'type' => 'number',
			// 'validate' => 'numeric',
			'dependency' => array('logo_type', '==', '1'),
			'title' => esc_html__('Logo width (Optional)', 'epcl_framework'),
			'subtitle' => esc_html__('Default: 160 (pixels)', 'epcl_framework'),
			'desc' => esc_html__('Note: this is the half width of your uploaded logo for retina display purposes.', 'epcl_framework'),
            'default' => '',
            'unit' => 'px'
        ),
        array(
			'id' => 'sticky_logo_image',
			'type' => 'media',
			'dependency' => array('logo_type', '==', '1'),
			'url' => true,
			'preview'=> true,
			'title' => esc_html__('Sticky Logo Uploader (Optional)', 'epcl_framework'),
			'desc' => esc_html__('If blank, logo image will be used. Recommended size - width: 160px, height: 40px.', 'epcl_framework'),
        ),
        array(
			'id' => 'sticky_logo_width',
			'type' => 'number',
			// 'validate' => 'numeric',
			'dependency' => array('logo_type', '==', '1'),
			'title' => esc_html__('Sticky Logo width (Optional)', 'epcl_framework'),
			'subtitle' => esc_html__('Default: 160 (pixels)', 'epcl_framework'),
			// 'desc' => esc_html__('Note: this is the half width of your uploaded logo for retina display purposes.', 'epcl_framework'),
            'default' => '',
            'unit' => 'px'
        ),
		// array(
		// 	'id' => 'sticky_logo',
		// 	'type' => 'media',
		// 	'dependency' => array('logo_type', '==', '1'),
		// 	'url' => true,
		// 	'preview'=> true,
		// 	'title' => esc_html__('Small Logo Uploader', 'epcl_framework'),
		// 	'subtitle' => esc_html__('Used like sticky logo and minimalist header type.', 'epcl_framework'),
		// 	'desc' => esc_html__('Recommended sizes - <b>width: 448px</b>, <b>height: 48px</b>. (For retina display purposes)', 'epcl_framework'),
		// ),
		// array(
		// 	'id' => 'sticky_logo_width',
		// 	'type' => 'text',
		// 	'validate' => 'numeric',
		// 	'dependency' => array('logo_type', '==', '1'),
		// 	'title' => esc_html__('Small Logo width (Optional)', 'epcl_framework'),
		// 	'subtitle' => esc_html__('Default: <b>224</b> (pixels)', 'epcl_framework'),
		// 	'desc' => esc_html__('Note: this is the half width of your uploaded logo for retina display purposes.', 'epcl_framework'),
		// 	'default' => '224'
		// ),
		array(
			'id' => 'title_notice',
            'type' => 'subheading',
            'notice' => false,
			'title' => __( 'Notice / Advertise', 'epcl_framework')
		),
		array(
			'id' => 'enable_notice',
			'type' => 'switcher',
			'title' => esc_html__('Display Header Notice', 'epcl_framework'),
			'desc' => '',
			'default' => 0
        ),
        array(
			'id' => 'enable_notice_close',
			'type' => 'switcher',
            'title' => esc_html__('Display Notice close button', 'epcl_framework'),
            'subtitle' => esc_html__('If an user click the close button, the notice will be removed for 5 days.', 'epcl_framework'),
			'desc' => '',
			'default' => 0
		),
		array(
			'id' => 'notice_text',
			'type' => 'wp_editor',
			'title' => esc_html__('Notice text', 'epcl_framework'),
			'subtitle' => esc_html__('HTML and Shortcodes are allowed', 'epcl_framework'),
            'desc' => '',
            'media_buttons' => false,
		),
	)
) );
