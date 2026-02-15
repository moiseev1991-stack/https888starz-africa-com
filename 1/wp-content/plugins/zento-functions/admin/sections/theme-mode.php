<?php


/* Text Mode Settings */

CSF::createSection( $opt_name, array(
    'id' => 'text_mode',
    'title' => esc_html__('Text Mode', 'epcl_framework'),
    'subtitle' => esc_html__('Default: Inherit', 'epcl_framework'),
    'icon' => 'fa fa-paint-brush',
    'fields' => array(
        array(
			'id' => 'title_text_mode',
            'type' => 'subheading',
            'notice' => false,
            'title' => __('Important Information:', 'epcl_framework'),
            'subtitle' => __( 'This section will help you enable Text Mode globally (for all articles in your website), please note the text mode will <b>affect only the featured image</b>. All images inside single post content will remain without changes.', 'epcl_framework'),
		),
        array(
			'id' => 'theme_mode',
			'type' => 'button_set',
			'title' => esc_html__('Global Theme Mode', 'epcl_framework'),
			'subtitle' => esc_html__('Default: Inherit', 'epcl_framework'),
			'desc' => __('This is a global option (comments avatar included), so it will override any article setting. Useful if you want to display all your articles in text mode.<br><b>Note :</b> if you want to force text mode in <b>"Post Lists only"</b>, check <b>Blog -> Classic/Grid Posts</b> layout option instead.<br><b>Note 2:</b> this option exclude <b>Post Author Avatar</b> (Check the option below if you want to force text mode for them).', 'epcl_framework'),
			'options' => array('inherit' => 'Inherit', 'image' => 'Image (if available)', 'text' => 'Text (force text mode)'),
			'default' => 'inherit'
		),
        array(
			'id' => 'author_avatar_mode',
			'type' => 'button_set',
			'title' => esc_html__('Author Avatar Mode', 'epcl_framework'),
			'subtitle' => esc_html__('Default: Inherit', 'epcl_framework'),
			'desc' => __('This is a global option, so it will override any Author Avatar setting. Useful if you want to force text or image mode for all your <b>Authors, Editors or Collaborators</b>.', 'epcl_framework'),
			'options' => array('inherit' => 'Inherit', 'image' => 'Image (if available)', 'text' => 'Text (force text mode)'),
			'default' => 'inherit'
		),
        array(
			'id' => 'comments_mode',
			'type' => 'button_set',
			'title' => esc_html__('Comments Mode', 'epcl_framework'),
			'subtitle' => esc_html__('Default: Inherit', 'epcl_framework'),
			'desc' => __('This will work for Single Post Only (self hosted comments excluding Post Author Avatar).<br>By default <b>Mystery Person</b> Avatar is displayed, if "Text" is selected, the first letter of the comment author will be used.', 'epcl_framework'),
			'options' => array( 'image' => 'Image (Gravatar)', 'text' => 'Text (force text mode)'),
			'default' => 'image'
		),
        array(
			'id' => 'dropcap_custom_color',
			'type' => 'switcher',
            'title' => esc_html__('Enable Dropcap Custom Color', 'epcl_framework'),
            'subtitle' => esc_html__('Default: OFF', 'epcl_framework'),
			'desc' => __('This will apply your custom category color to your article Dropcaps. In case of using primary categories, that will be the applied color, example: <a href="https://prnt.sc/T8KyWriyLuus" target="_blank">https://prnt.sc/T8KyWriyLuus</a>', 'epcl_framework'),
			'default' => false
        ),
		// array(
		// 	'id' => 'main_effect_border_width',
		// 	'type' => 'slider',
		// 	'title' => esc_html__('Main title border width', 'epcl_framework'),
		// 	'subtitle' => 'Default: 14 pixels.',
		// 	'desc' => __('This will generate blank space on titles, e.g. <a href="https://prnt.sc/s2kk07" target="_blank">https://prnt.sc/s2kk07</a>', 'epcl_framework'),
		// 	'default' => '14',
		// 	'min' => '3',
		// 	'step' => '1',
        //     'max' => '30',
        //     'unit' => 'px',
        // ),
        array(
			'id' => 'dropcap_padding_top',
			'type' => 'slider',
			'title' => esc_html__('Main DropCap Alignment', 'epcl_framework'),
			'subtitle' => 'Default: 10%',
			'desc' => __('Every Custom Google Font has it own line-height per letter, this could generate the next issue: <a href="https://prnt.sc/Af0JXMIkKW3W" target="_blank">https://prnt.sc/Af0JXMIkKW3W</a><br> The solution is to increase/reduce the padding on top.', 'epcl_framework'),
			'default' => '10',
			'min' => '0',
			'step' => '1',
            'max' => '25',
            'unit' => '%',
		),
    )
) );
