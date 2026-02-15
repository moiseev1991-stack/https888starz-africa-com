<?php

// All Posts Information

$categories = get_categories();
$options = array();
foreach ($categories as $category) {
    $options[$category->slug] = $category->name;
}

$page_key = 'epcl_post';

CSF::createMetabox( $page_key, array(
    'title'          => 'General Information',
    'post_type'      => 'post',
) );

CSF::createSection( $page_key, array(
    // 'title'  => 'Modules creator',
    // 'icon'   => 'fa fa-rocket',
    'fields' => array(
        array (
			'id' => 'msg_thumbs',
			// 'title' => esc_html__('Important', 'epcl_framework'),
            'content' => __('
            <h3 style="margin-top: 0">Optimized images recommended sizes are:</h3>
            <p><b>Small Images on Home (small vertical image):</b> 270x300px</p>
            <p><b>Grids images on Home (standard style):</b> 365x250px</p>
            <p><b>Classic posts on home (Classic image style):</b> 750x440px</p>
            <h3 style="margin-top: 0">Featured image recommended size (Single Post):</h3>
            <p><b>Standard style:</b> 670x445px or greater</p>
            <p><b>Fullcover style or post without sidebar:</b> 1190x500px or greater</p>
            <h3><b>Recommended Compression Tool:</b> <a href="https://squoosh.app/" target="_blank">Squoosh.app</a></h3>
            ', 'epcl_framework'),
            // 'style' => 'success',
            'type' => 'submessage',
        ),
        array (
			'id' => 'optimized_image',
			'title' => esc_html__('Optimized Image (optional)', 'epcl_framework'),			
            'desc' => __('This image will be used on <b>homepages only</b> and the uploaded image will not be cropped, resized or any other method that can change the size or the quality of the image.<br><br>(This is useful if you have texts over images or you are already compressed the image manually).', 'epcl_framework'),
            'type' => 'media',                    
            'url' => false,
            'preview'=> true,
        ),
        // array (
		// 	'id' => 'optimized_image_slider',
        //     'title' => esc_html__('Optimized Image for Slider (optional)', 'epcl_framework'),
        //     'desc' => __('This image will be used on <b>slider module only</b> and the uploaded image will not be cropped, resized or any other method that can change the size or the quality of the image.<br><br>Recommended Size: 1000x680px.', 'epcl_framework'),
		// 	'type' => 'image',			
        //     'type' => 'media',                    
        //     'url' => false,
        //     'preview'=> true,
        // ),
        array(
            'id'          => 'primary_category',
            'type'        => 'select',
            'title'       => esc_html__('Primary Category', 'epcl_framework'),
            'desc'        => __('This option will select the correct color in case you are using custom color for categories.', 'epcl_framework'),
            'placeholder' => __('Select category', 'epcl_framework'),
            'chosen'      => false,
            'ajax'        => false,
            'multiple'    => false,            
            'options'     => $options,
            'settings' => array(
                'min_length' => 1,
                'width' => '250px'
            )
        ),
        array(
            'id' => 'rating',
            'type' => 'button_set',
            'title' => esc_html__('Rating (optional)', 'epcl_framework'),
            'desc' => __('This setting work as <b>information only</b>, a good example is <b>difficulty</b> or a <b>travel review</b>, not customers/visitors ratings.', 'epcl_framework'),          
            'options'     => array(
                'disabled' => 'Disabled',
                '1'  => '⭐',
                '2'  => '⭐⭐',
                '3'  => '⭐⭐⭐',
            ),
            'default' => 'disabled'
        ),
        array (
			'id' => 'loop_style',
			'title' => esc_html__('Loop Post Style', 'epcl_framework'),
            'subtitle' => esc_html__('Default: Small Image', 'epcl_framework'),
			'desc' => __('How the post should look like on homepages, archives, etc.<br><b>Important:</b> Loop style works with Standard Post Format only.', 'epcl_framework'),
			'type' => 'button_set',
			'options' => array(
                // 'text-only' => esc_html__('Text Only', 'epcl_framework'),
				'small-image' => esc_html__('Small/Vertical Image', 'epcl_framework'),
				'classic-image' => esc_html__('Classic Image', 'epcl_framework'),
			),
			'default' => 'small-image',
		),
		array (
			'id' => 'style',
			'title' => esc_html__('Single Post Style', 'epcl_framework'),
			'desc' => esc_html__('How the featured image should look like inside the post.', 'epcl_framework'),
			'type' => 'button_set',
			'options' => array(
                'vertical' => esc_html__('Vertical', 'epcl_framework'),
				'classic' => esc_html__('Classic', 'epcl_framework'),
				'fullcover' => esc_html__('Full Cover', 'epcl_framework'),
			),
			'default' => 'classic',
		),
        array (
            'id' => 'enable_sidebar',
            'title' => esc_html__('Enable Sidebar', 'epcl_framework'),
            'desc' => esc_html__('Enable/Disable sidebar for this post.', 'epcl_framework'),
            'type' => 'switcher',
            'default' => true,
        ),
        array (
            'id' => 'sidebar',
            'title' => esc_html__('Sidebar (optional)', 'epcl_framework'),
            'subtitle' => esc_html__('Default: Article Sidebar', 'epcl_framework'),
            'desc' => esc_html__('Use a different sidebar for this module.', 'epcl_framework'),       
            'type' => 'select',             
            'chosen' => false,
            'options' => 'sidebars',
            'default' => 'epcl_sidebar_default',    
            'dependency' => array('enable_sidebar', '==', true),        
        ),
        array (
			'id' => 'views_counter',
			'title' => esc_html__('Views Counter', 'epcl_framework'),
			'desc' => esc_html__('How many times this post has been viewed.', 'epcl_framework'),
			'type' => 'number',
			'readonly' => false,
            'default_value' => 0
        ),
    )
) );

// Post Format: Video

$page_key = 'epcl_post_video';

CSF::createMetabox( $page_key, array(
    'title' => 'Video Information',
    'post_type' => 'post',
    'post_formats' => 'video',
    'priority' => 'high'
) );

CSF::createSection( $page_key, array(
    // 'title'  => 'Video Information',
    // 'icon'   => 'fa fa-youtube',    
    'fields' => array(
        array (
			'id' => 'show_featured_image',
			'title' => esc_html__('Show Featured Image', 'epcl_framework'),
			'desc' => esc_html__('By default it will be displayed the video on home pages, archives, etc. Enabling this option will show the featured image instead.', 'epcl_framework'),
			'type' => 'switcher',
			'default' => false,
        ),
		array (
			'id' => 'video_type',
			'title' => esc_html__('Video Type', 'epcl_framework'),
			'desc' => esc_html__('Select platform.', 'epcl_framework'),
			'type' => 'button_set',
			'options' => array(
				'youtube' => esc_html__('Youtube', 'epcl_framework'),
                'vimeo' => esc_html__('Vimeo', 'epcl_framework'),
                'custom' => esc_html__('Custom Embed', 'epcl_framework'),
			),
			'default' => 'youtube',
		),
		array (
			'id' => 'video_url',
			'title' => esc_html__('Video URL', 'epcl_framework'),
			'desc' => esc_html__('eg. https://www.youtube.com/watch?v=v9nBysHSzhE', 'epcl_framework'),
            'type' => 'text',
            // 'validate' => 'csf_validate_url',
            'dependency' => array('video_type', '!=', 'custom'),  
        ),
        array (
			'id' => 'custom_embed',
			'title' => esc_html__('Custom Embed Code', 'epcl_framework'),
			'desc' => esc_html__('eg. <iframe>....</iframe>', 'epcl_framework'),
            'type' => 'textarea',
            'dependency' => array('video_type', '==', 'custom'),  
            'settings' => array(
                'theme'  => 'monokai',
                'mode'   => 'htmlmixed',
                'tabSize' => 4
            ),
            'sanitize' => false
		),
    )
) );

// Post Format: Gallery

$page_key = 'epcl_post_gallery';

CSF::createMetabox( $page_key, array(
    'title' => 'Gallery Information',
    'post_type' => 'post',
    'post_formats' => 'gallery',
    'priority' => 'high'
) );

CSF::createSection( $page_key, array(
    // 'title'  => 'Video Information',
    // 'icon'   => 'fa fa-youtube',    
    'fields' => array(
        array(
            'id' => 'gallery',
            'type' => 'gallery',
            'title' => esc_attr__('Gallery Images', 'epcl_framework'),
            'add_title'   => esc_attr__('Add Images', 'epcl_framework'),
            'desc' => esc_attr__('Note: Gallery Format works only for Classic and Fullcover Styles.', 'epcl_framework'),
        ), 
    )
) );

// Post Format: Audio

$page_key = 'epcl_post_audio';

CSF::createMetabox( $page_key, array(
    'title' => 'Audio Information',
    'post_type' => 'post',
    'post_formats' => 'audio',
    'priority' => 'high'
) );

CSF::createSection( $page_key, array(
    // 'title'  => 'Video Information',
    // 'icon'   => 'fa fa-youtube',    
    'fields' => array(
        array (
			'id' => 'show_featured_image',
			'title' => esc_html__('Show Featured Image', 'epcl_framework'),
			'desc' => esc_html__('By default it will be displayed the audio iframe on home pages, archives, etc. Enabling this option will show the featured image instead.', 'epcl_framework'),
			'type' => 'switcher',
			'default' => false,
        ),
        array (
			'id' => 'audio_type',
			'title' => esc_html__('Audio Type', 'epcl_framework'),
			'desc' => esc_html__('Select platform.', 'epcl_framework'),
			'type' => 'button_set',
			'options' => array(
				'soundcloud' => esc_html__('SoundCloud', 'epcl_framework'),
                'custom' => esc_html__('Custom Embed', 'epcl_framework'),
			),
			'default' => 'soundcloud',
		),
        array (
			'id' => 'audio_height',
			'title' => esc_html__('Embed Height (in pixels)', 'epcl_framework'),
            'subtitle' => esc_html__('Default: 225px', 'epcl_framework'),
			'desc' => esc_html__('e.g. 352 pixels (for Spotify)', 'epcl_framework'),
			'type' => 'number',
			'readonly' => false,
            'default_value' => 225
        ),
		array (
			'id' => 'soundcloud_url',
			'title' => esc_html__('SoundCloud URL', 'epcl_framework'),
			'desc' => esc_html__('eg. https://soundcloud.com/mahu-semo/led-zepellin-stairway-to-heaven', 'epcl_framework'),
			'type' => 'text',
            'dependency' => array('audio_type', '==', 'soundcloud'),  
		),
        array (
			'id' => 'audio_custom_embed',
			'title' => esc_html__('Custom Embed Code', 'epcl_framework'),
            'subtitle' => esc_html__('Spotify, Podcasts, etc.', 'epcl_framework'),
			'desc' => esc_html__('eg. <iframe>....</iframe> If your embed height is not 100% responsive like Spotify, we recommend to enable "Show Featured Image" for your homepage.', 'epcl_framework'),
            'type' => 'textarea',
            'dependency' => array('audio_type', '==', 'custom'),  
            'settings' => array(
                'theme'  => 'monokai',
                'mode'   => 'htmlmixed',
                'tabSize' => 4
            ),
            'sanitize' => false
		),
    )
) );
