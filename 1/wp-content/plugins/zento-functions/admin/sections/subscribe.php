<?php

/* Unique options for every EP theme */

$opt_name = EPCL_FRAMEWORK_VAR;
$bg_color = '#FFFFFF';

/* General Settings */

CSF::createSection( $opt_name, array(
	'title' => esc_html__('Subscribe Settings', 'epcl_framework'),
	'icon' => ' fa fa-envelope',
	'fields' => array(
        array(
			'id' => 'title_subscribe_global',
            'type' => 'subheading',
			'title' => __( 'Global Options', 'epcl_framework')
		),
        array(
			'id' => 'subscribe_url',
			'type' => 'text',
			// 'validate' => 'url',
			'title' => esc_html__('Subscribe Url', 'epcl_framework'),
			'subtitle' => esc_html__('You can use a Mailchimp Form or any mailing system that generate a public Url. (Mailchimp is recommended, check the documentation).', 'epcl_framework'),
			'fullwidth' => true,
			'desc' => esc_html__('e.g. https://eepurl.com/dxHIUz', 'epcl_framework')
        ),
        // array(
		// 	'id' => 'subscribe_title',
		// 	'type' => 'text',
		// 	'title' => esc_html__('Title of subscribe section (Optional)', 'epcl_framework'),
        //     'desc' => esc_html__('Default: Subscribe to our Newsletter', 'epcl_framework'),
        //     // 'dependency' => array('footer_enable_subscribe', '==', '1'),
        // ),
        array(
			'id' => 'subscribe_label',
			'type' => 'text',
			'title' => esc_html__('Title of subscribe label (Optional)', 'epcl_framework'),
            'desc' => esc_html__("Default: Let's connect", 'epcl_framework'),
            'default' => esc_html__("Let's connect", 'epcl_framework')
            // 'dependency' => array('footer_enable_subscribe', '==', '1'),
        ),
        // array(
		// 	'id' => 'single_subscribe_title',
		// 	'type' => 'text',
		// 	'title' => esc_html__('Subtitle inside Article (Single Post)', 'epcl_framework'),
        //     'desc' => esc_html__('Default: Like what you read?', 'epcl_framework'),
        //     // 'dependency' => array('footer_enable_subscribe', '==', '1'),
        // ),
        // array(
		// 	'id' => 'subscribe_description',
		// 	'type' => 'text',
		// 	'title' => esc_html__('Description of subscribe section (Optional)', 'epcl_framework'),
        //     'desc' => esc_html__('e.g. Get the latest posts delivered right to your email.', 'epcl_framework'),
        //     // 'dependency' => array('footer_enable_subscribe', '==', '1'),
        // ),
        // array(
		// 	'id' => 'title_subscribe_footer',
        //     'type' => 'subheading',
		// 	'title' => __( 'Enable/Disable Subscribe Form', 'epcl_framework')
		// ),
        // array(
		// 	'id' => 'loop_classic_enable_subscribe',
		// 	'type' => 'switcher',
		// 	'title' => esc_html__('Display Subscribe Form on Classic Post List', 'epcl_framework'),
        //     'subtitle' => esc_html__('(Between Posts)', 'epcl_framework'),
		// 	'desc' => esc_html__('You must enter a valid Subscribe url to use this section.', 'epcl_framework'),
		// 	'default' => true
        // ),
        // array(
		// 	'id' => 'single_enable_subscribe',
		// 	'type' => 'switcher',
		// 	'title' => esc_html__('Display below Post Content (Single Post)', 'epcl_framework'),
		// 	'desc' => esc_html__('You must enter a valid Subscribe url to use this section.', 'epcl_framework'),
		// 	'default' => true
        // ),
        // array(
		// 	'id' => 'footer_enable_subscribe',
		// 	'type' => 'switcher',
		// 	'title' => esc_html__('Display on Footer', 'epcl_framework'),
		// 	'desc' => esc_html__('You must enter a valid Subscribe url to use this section.', 'epcl_framework'),
		// 	'default' => true
        // ),
        array(
			'id' => 'title_subscribe_header',
            'type' => 'subheading',
			'title' => __( 'Header', 'epcl_framework')
		),
        array(
			'id' => 'enable_subscribe',
			'type' => 'switcher',
			'title' => esc_html__('Display subscribe button on Header', 'epcl_framework'),
			'desc' => esc_html__('You must enter a valid Mailchimp url to use this section.', 'epcl_framework'),
			'default' => false
        ),
        array(
			'id' => 'title_subscribe_button',
			'type' => 'text',
			'title' => esc_html__('Title of subscribe button', 'epcl_framework'),
			'desc' => esc_html__('e.g. Subscribe', 'epcl_framework'),
        ),
        array(
			'id' => 'subscribe_url_header',
			'type' => 'text',
			// 'validate' => 'url',
			'title' => esc_html__('Subscribe Url for Header (optional)', 'epcl_framework'),
			'subtitle' => esc_html__('By default is used the Global Option "Subscribe Url"', 'epcl_framework'),
			'fullwidth' => true,
			'desc' => esc_html__('e.g. https://eepurl.com/dxHIUz', 'epcl_framework')
        ),
        array(
			'id' => 'title_subscribe_adv',
            'type' => 'subheading',
			'title' => __( 'Advanced Options', 'epcl_framework'),
            'subtitle' => __( 'These options are recommended for developers or if you know how to connect with another mailing service.', 'epcl_framework'),
		),
        array(
			'id' => 'subscribe_email_field_name',
			'type' => 'text',
			'title' => esc_html__('Email HTML field name', 'epcl_framework'),
            'subtitle' => esc_html__('Default: MERGE0', 'epcl_framework'),
            'desc' => esc_html__('Mailchimp form usually assign this as "MERGE0"', 'epcl_framework'),
            'default' => 'MERGE0'
        ),
        array(
			'id' => 'subscribe_method',
			'type' => 'button_set',
			'title' => esc_html__('Form Submit Method', 'epcl_framework'),
            'inline' => true,
			'options'   => array(
				'POST' => 'Post',
                'GET' => 'Get',
			),
			'default' => 'POST',
			'subtitle' => __( 'Default: Post', 'epcl_framework'),
            'desc' => esc_html__('If you are using CF7 plugin, change to GET instead.', 'epcl_framework'),
        ),
        array(
			'id' => 'subscribe_parameters',
			'type' => 'code_editor',
            'title' => esc_html__('Extra Parameters or Custom HTML', 'epcl_framework'),
            'subtitle' =>  esc_html__('(Optional)', 'epcl_framework'),
            'desc' => __('You can add Custom HTML like input fields, to send extra parameters to Mailchimp, example: <a href="https://prnt.sc/rshj91" target="_blank">https://prnt.sc/rshj91</a> <a href="https://prnt.sc/rshqv6" target="_blank">https://prnt.sc/rshqv6</a>', 'epcl_framework'),
            // 'dependency' => array('footer_enable_subscribe', '==', '1'),
            'settings' => array(
                'theme'  => 'dracula',
                'mode'   => 'htmlmixed',
                'tabSize' => 4
            ),
            'sanitize' => false
        ),
	)
) );
