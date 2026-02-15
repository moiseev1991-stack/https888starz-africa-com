<?php

/* Unique options for every EP theme */

$admin_url = EPCL_PLUGIN_URL.'/functions/admin';

$opt_name = EPCL_FRAMEWORK_VAR;

/* Social Profiles */

CSF::createSection( $opt_name, array(
	'title' => esc_html__('Social Profiles', 'epcl_framework'),
	'desc' => esc_html__('Social profiles are used in different places inside the theme.', 'epcl_framework'),
	'icon' => 'fa fa-user',
	'customizer' => false,
	'fields' => array(
		// array(
		// 	'id' => 'twitter_id',
		// 	'type' => 'text',
		// 	'title' => '<i class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-twitter fa-stack-1x"></i></i> '.esc_html__('Twitter ID', 'epcl_framework'),
		// 	'desc' => esc_html__('e.g. wordpress', 'epcl_framework')
        // ),
        array(
			'id' => 'twitter_url',
            'type' => 'text',
            'sanitize' => 'sanitize_url',
			'title' => '<i class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-twitter fa-stack-1x"></i></i> '.esc_html__('Twitter URL', 'epcl_framework'),
			'desc' => esc_html__('e.g. https://twitter.com/estudiopatagon', 'epcl_framework')
		),
		array(
			'id' => 'facebook_url',
			'type' => 'text',
			'sanitize' => 'sanitize_url',
			'title' => '<i class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-facebook fa-stack-1x"></i></i> '.esc_html__('Facebook URL', 'epcl_framework'),
			'desc' => esc_html__('e.g. http://www.facebook.com/estudiopatagon', 'epcl_framework')
		),
		array(
			'id' => 'instagram_url',
			'type' => 'text',
			'sanitize' => 'sanitize_url',
			'title' => '<i class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-instagram fa-stack-1x"></i></i> '.esc_html__('Instagram url', 'epcl_framework'),
			'desc' => esc_html__('e.g. https://instagram.com/wordpress', 'epcl_framework')
        ),
        array(
			'id' => 'linkedin_url',
			'type' => 'text',
			'sanitize' => 'sanitize_url',
			'title' => '<i class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-linkedin fa-stack-1x"></i></i> '.esc_html__('Linkedin url', 'epcl_framework'),
			'desc' => esc_html__('e.g. https://www.linkedin.com/in/my-name/', 'epcl_framework')
		),
		array(
			'id' => 'pinterest_url',
			'type' => 'text',
			'sanitize' => 'sanitize_url',
			'title' => '<i class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-pinterest fa-stack-1x"></i></i> '.esc_html__('Pinterest url', 'epcl_framework'),
			'desc' => esc_html__('e.g. https://www.pinterest.com/envato', 'epcl_framework')
		),
		array(
			'id' => 'dribbble_url',
			'type' => 'text',
			'sanitize' => 'sanitize_url',
			'title' => '<i class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-dribbble fa-stack-1x"></i></i> '.esc_html__('Dribbble url', 'epcl_framework'),
			'desc' => esc_html__('e.g. http://dribbble.com/wordpress', 'epcl_framework')
		),
		array(
			'id' => 'tumblr_url',
			'type' => 'text',
			'sanitize' => 'sanitize_url',
			'title' => '<i class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-tumblr fa-stack-1x"></i></i> '.esc_html__('Tumblr URL', 'epcl_framework'),
			'desc' => esc_html__('e.g. http://iamcollis.tumblr.com', 'epcl_framework')
		),
		array(
			'id' => 'youtube_url',
			'type' => 'text',
			'sanitize' => 'sanitize_url',
			'title' => '<i class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-youtube fa-stack-1x"></i></i> '.esc_html__('Youtube url', 'epcl_framework'),
			'desc' => esc_html__('e.g. https://www.youtube.com/user/wordpress', 'epcl_framework')
		),
		array(
			'id' => 'flickr_url',
			'type' => 'text',
			'sanitize' => 'sanitize_url',
			'title' => '<i class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-flickr fa-stack-1x"></i></i> '.esc_html__('Flickr url', 'epcl_framework'),
			'desc' => esc_html__('e.g. http://www.flickr.com/photos/wordpress', 'epcl_framework')
        ),
        array(
			'id' => 'twitch_url',
			'type' => 'text',
			'sanitize' => 'sanitize_url',
			'title' => '<i class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-twitch fa-stack-1x"></i></i> '.esc_html__('Twitch url', 'epcl_framework'),
			'desc' => esc_html__('e.g. https://www.twitch.tv/name', 'epcl_framework')
        ),
        array(
			'id' => 'vk_url',
			'type' => 'text',
			'sanitize' => 'sanitize_url',
			'title' => '<i class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-vk fa-stack-1x"></i></i> '.esc_html__('Vkontakte url', 'epcl_framework'),
			'desc' => esc_html__('e.g. https://www.vk.com/name', 'epcl_framework')
        ),
        array(
			'id' => 'telegram_url',
			'type' => 'text',
			'sanitize' => 'sanitize_url',
			'title' => '<i class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-telegram fa-stack-1x"></i></i> '.esc_html__('Telegram url', 'epcl_framework'),
			'desc' => esc_html__('e.g. https://t.me/username', 'epcl_framework')
        ),
        array(
			'id' => 'tiktok_url',
			'type' => 'text',
			'sanitize' => 'sanitize_url',
			'title' => '<i class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-music fa-stack-1x"></i></i> '.esc_html__('TikTok url', 'epcl_framework'),
			'desc' => esc_html__('e.g. https://www.tiktok.com/@username', 'epcl_framework')
        ),
        array(
			'id' => 'whatsapp_url',
			'type' => 'text',
			// 'sanitize' => 'sanitize_url',
			'title' => '<i class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-whatsapp fa-stack-1x"></i></i> '.esc_html__('WhatsApp url', 'epcl_framework'),
			'desc' => esc_html__('eg. https://wa.me/5492996155777', 'epcl_framework')
		),
        array(
			'id' => 'discord_url',
			'type' => 'text',
			// 'sanitize' => 'sanitize_url',
			'title' => '<i class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-headphones fa-stack-1x"></i></i> '.esc_html__('Discord url', 'epcl_framework'),
			'desc' => esc_html__('eg. https://discord.gg/estudiopatagon', 'epcl_framework')
		),
        array(
			'id' => 'email_url',
			'type' => 'text',
			// 'sanitize' => 'sanitize_url',
			'title' => '<i class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-envelope fa-stack-1x"></i></i> '.esc_html__('Email', 'epcl_framework'),
			'desc' => esc_html__('e.g. https://www.yourwebsite.com/contact OR direct email: johndoe@gmail.com', 'epcl_framework')
		),
		array(
			'id' => 'rss_url',
			'type' => 'text',
			'sanitize' => 'sanitize_url',
			'title' => '<i class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-rss fa-stack-1x"></i></i> '.esc_html__('RSS URL', 'epcl_framework'),
			'desc' => esc_html__('e.g. http://estudiopatagon.com/feed', 'epcl_framework')
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
                array(
                    'id'    => 'social_follow_text',
                    'type'  => 'text',
                    'title' => esc_attr__('Follow Text', 'epcl_framework'),
                    'subtitle' => esc_attr__('HTML allowed', 'epcl_framework'),
                    'desc' => __('e.g. Follow me on Github<br>or with HTML <code>Contribute on &lt;b>Github&lt;/b></code>', 'epcl_framework')
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
                // array(
                //     'id' => 'social_icon_white',
                //     'type' => 'media',
                //     'url' => false,
                //     'preview'=> true,
                //     'title' => esc_html__('Icon White version', 'epcl_framework'),
                //     'subtitle' => esc_html__('Recommended Size: 64x64px or greater', 'epcl_framework'),
                //     'desc' => esc_html__('This icon will be used on Widgets', 'epcl_framework'),
                // ),
                array(
                    'id'    => 'social_url',
                    'type'  => 'text',
                    'title' => esc_attr__('URL', 'epcl_framework'),
                    'desc' => esc_html__('e.g. https://github.com/estudiopatagon', 'epcl_framework')
                ),    
                // array(
                //     'id' => 'social_color',
                //     'type' => 'color',
                //     'title' => esc_html__('Social Accent color', 'epcl_framework'),
                //     'default' => '#000',
                //     'transparent' => false,
                //     'class' => 'epcl-hide-transparent',
                //     'desc' => esc_html__('Background Color for Social Widget', 'epcl_framework'),
                // ),      
            ),
        ),
	)
) );
