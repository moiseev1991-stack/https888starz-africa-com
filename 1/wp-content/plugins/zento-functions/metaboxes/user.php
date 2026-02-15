<?php

$prefix = EPCL_THEMEPREFIX.'_';
$prefix_key = 'epcl_user_';

$page_key = 'epcl_user';

// Create profile options
CSF::createProfileOptions( $page_key );

CSF::createSection( $page_key, array(
    'title'  => esc_html__('(EP) Custom Fields'),
    'icon'   => 'fa fa-rocket',
    'fields' => array(
        array (
			'id' => 'facebook',
			'title' => esc_html__('Facebook URL', 'epcl_framework'),
			'desc' => esc_html__('e.g. http://www.facebook.com/estudiopatagon', 'epcl_framework'),
			'type' => 'text',
		),
		array (
			'id' => 'twitter',
			'title' => esc_html__('Twitter URL', 'epcl_framework'),
			'desc' => esc_html__('e.g: https://twitter.com/wordpress', 'epcl_framework'),
			'type' => 'text',
        ),
        array(
			'id' => 'custom_social',
			'type' => 'repeater',
			'button_title' => esc_html__('Add Social Profile', 'epcl_framework'),
			'title' => esc_html__('Custom Social Profile', 'epcl_framework'),
			// 'subtitle' => esc_html__('Enter an unique name', 'epcl_framework'),
			'desc' => esc_html__('All fields are required to upload your own custom social profile.', 'epcl_framework'),
            'fields' => array(
                array(
                    'id'    => 'social_name',
                    'type'  => 'text',
                    'title' => esc_attr__('Network Name', 'epcl_framework'),
                    'desc' => esc_html__('e.g. Github', 'epcl_framework')
                ),
                // array(
                //     'id' => 'social_icon_svg',
                //     'type' => 'code_editor',
                //     'title' => esc_html__('Icon SVG code', 'epcl_framework'),
                //     'desc' => __('You can get social icons from: <a href="https://remixicon.com/" target="_blank">Remix Icons</a> or from: <a href="https://www.svgrepo.com/" target="_blank">SVG Repo</a>, here is <a href="https://prnt.sc/sXd_OUkoey3u" target="_blank">an example</a> how it should look like.', 'epcl_framework'),
                //     'settings' => array(
                //         'theme'  => 'dracula',
                //         'mode'   => 'htmlmixed',
                //         'tabSize' => 4
                //     ),
                //     'sanitize' => false
                // ),
                array(
                    'id' => 'social_icon',
                    'type' => 'media',
                    'url' => false,
                    'preview'=> true,
                    'title' => esc_html__('Icon Colored Version', 'epcl_framework'),
                    'subtitle' => esc_html__('Recommended Size: 64x64px or greater', 'epcl_framework'),
                    'desc' => __('You can get Social Icons from: <a href="https://remixicon.com/" target="_blank">Remix Icons</a> or from: <a href="https://www.svgrepo.com/" target="_blank">SVG Repo</a>, here is <a href="https://prnt.sc/sXd_OUkoey3u" target="_blank">an example</a> how it should look like.', 'epcl_framework'),
                ),
                array(
                    'id'    => 'social_url',
                    'type'  => 'text',
                    'title' => esc_attr__('URL', 'epcl_framework'),
                    'desc' => esc_html__('e.g. https://github.com/estudiopatagon', 'epcl_framework')
                ),        
            ),
        ),
        array (
			'id' => 'position',
			'title' => esc_html__('Position (tagline)', 'epcl_framework'),
			'desc' => esc_html__('e.g: Founder & Editor', 'epcl_framework'),
			'type' => 'text',
        ),
        // array(
		// 	'id' => 'avatar_mode',
		// 	'type' => 'button_set',
		// 	'title' => esc_html__('Avatar Mode', 'epcl_framework'),
		// 	'subtitle' => esc_html__('Default: Inherit', 'epcl_framework'),
		// 	'desc' => esc_html__('In case you dont want to display any Gravatar or Image, just select "text"', 'epcl_framework'),
		// 	'options' => array('inherit' => 'Inherit', 'image' => 'Gravatar/Optimized Avatar', 'text' => 'Text (force text mode)'),
		// 	'default' => 'inherit'
		// ),
        array (
            'id' => 'avatar',
            'title' => esc_html__('Optimized Avatar', 'epcl_framework'),
            'desc' => esc_html__('Recommended size: 150x150. This step is totally optional, it\'s just boost a little the web speed rendering the image directly from your hosting, instead of gravatar.', 'epcl_framework'),
            'type' => 'media',                    
            'url' => false,
            'preview'=> true,
        ),
        
    )
) );
