<?php

/* Unique options for every EP theme */

$opt_name = EPCL_FRAMEWORK_VAR;

/* Advertising Settings */

CSF::createSection( $opt_name, array(
    'id' => 'blog',
    'title' => esc_html__('Blog', 'epcl_framework'),
    'icon' => 'fa fa-pencil-square-o'
) );

CSF::createSection( $opt_name, array(
    'title' => esc_html__('Global Options', 'epcl_framework'),
    // 'icon' => 'fa fa-dollar',
    'parent' => 'blog',
	'fields' => array(
        // array(
		// 	'id' => 'post_title_layout',
		// 	'type' => 'button_set',
		// 	'title' => esc_html__('Post Titles layout', 'epcl_framework'),
        //     'desc' => esc_html__('By default titles are inside images (with the brush effect).', 'epcl_framework'),
        //     'options'  => array(
        //         'inside_images' => esc_html__('Inside Images', 'epcl_framework'),
        //         'below_images' => esc_html__('Below Images (no effect)', 'epcl_framework'),            
        //     ),
		// 	'default' => 'inside_images'
        // ),
        array(
			'id' => 'enable_breadcrumbs',
			'type' => 'switcher',
			'title' => esc_html__('Display Breadcrumbs on posts/pages', 'epcl_framework'),
			'desc' => esc_html__('This is compatible with Yoast SEO or Breadcrumbs NavXT plugins if installed.', 'epcl_framework'),
			'default' => 0
        ),
        array (
			'id' => 'breadcrumbs_type',
			'title' => esc_html__('Breadcrumbs Plugin', 'epcl_framework'),
            'subtitle' => esc_html__('Default: Inherit', 'epcl_framework'),
			'desc' => __('You can check how to enable breadcrumbs for Yoast SEO: <a href="https://kb.yoast.com/kb/implement-wordpress-seo-breadcrumbs/#enable-and-configure-the-yoast-breadcrumbs" target="_blank">here</a>', 'epcl_framework'),
			'type' => 'button_set',
            'dependency' => array('enable_breadcrumbs', '==', '1'),
			'options' => array(
                'yoast' => 'Yoast SEO',
                'navxt' => 'NavXT',
                'rankmath' => 'Rank Math',
			),
			'default' => 'yoast',
		),
        array(
			'id' => 'enable_global_date',
			'type' => 'switcher',
			'title' => esc_html__('Display Date', 'epcl_framework'),
			'desc' => esc_html__('If is OFF, it will disable dates on posts, lists, carousel, widgets, etc.', 'epcl_framework'),
			'default' => 1
        ),
        // array(
		// 	'id' => 'enable_global_modified_date',
		// 	'type' => 'switcher',
		// 	'title' => esc_html__('Display Last Modified Date', 'epcl_framework'),
		// 	'desc' => esc_html__('If is OFF, it will disable last update on posts, lists, carousel, widgets, etc.', 'epcl_framework'),
		// 	'default' => 1
        // ),
        // array(
		// 	'id' => 'enable_global_comments',
		// 	'type' => 'switcher',
		// 	'title' => esc_html__('Display Comments Counter', 'epcl_framework'),
		// 	'desc' => esc_html__('If is OFF, it will disable comments on posts, lists, etc.', 'epcl_framework'),
		// 	'default' => 1
        // ),
        array(
			'id' => 'enable_global_views',
			'type' => 'switcher',
			'title' => esc_html__('Display Views Counter', 'epcl_framework'),
			'desc' => esc_html__('If is ON, it will enable views counter on Posts Lists (Homepage, archives, etc).', 'epcl_framework'),
			'default' => 0
        ),
        array(
			'id' => 'enable_global_reading_time',
			'type' => 'switcher',
			'title' => esc_html__('Display Reading Time (in minutes)', 'epcl_framework'),
			'desc' => esc_html__('If is OFF, it will disable reading counter on posts, lists, etc.', 'epcl_framework'),
			'default' => 1
        ),
        array(
			'id' => 'words_per_minute',
			'type' => 'spinner',
            'title' => esc_html__('Reading Time: Words per minute', 'epcl_framework'),
            'subtitle' => esc_html__('Default: 225', 'epcl_framework'),
			'desc' => esc_html__('For languages like Chinese, Korean or Japanese a value of 500 is recommended.', 'epcl_framework'),
            'default'  => '225',
            'min'      => '50',
            'step'     => '25',
            'max'      => '800',
            'dependency' => array('enable_global_reading_time', '==', '1'),
        ),
        array(
			'id' => 'enable_global_category',
			'type' => 'switcher',
			'title' => esc_html__('Display Categories', 'epcl_framework'),
			'desc' => esc_html__('If is OFF, it will disable categories on posts, lists, carousel, widgets, etc.', 'epcl_framework'),
			'default' => 1
        ),
        // array(
		// 	'id' => 'category_limit',
		// 	'type' => 'spinner',
		// 	'title' => esc_html__('Number of visible categories', 'epcl_framework'),
        //     'subtitle' => esc_html__('Default: 2', 'epcl_framework'),
		// 	'desc' => esc_html__('By default there is a max amount of 2 category buttons displayed, you can increase/reduce this amount.', 'epcl_framework'),
        //     'default'  => '2',
        //     'min'      => '0',
        //     'step'     => '1',
        //     'max'      => '20',
        // ),
        array(
			'id' => 'hosted_comments',
			'type' => 'radio',
			'title' => esc_html__('Comments Support', 'epcl_framework'),
			'desc' => esc_html__('This option affects posts and pages.', 'epcl_framework'),
			'options' => array('1' => 'Self Hosted', '2' => 'Disqus', '3' => 'Facebook'),
			'default' => 1
		),
		array(
			'id' => 'disqus_id',
			'type' => 'text',
			'title' => esc_html__('Disqus ID', 'epcl_framework'),
			'desc' => esc_html__('This option affects posts and pages.', 'epcl_framework'),
			'dependency' => array('hosted_comments', '==', '2'),
        ),
        array(
			'id' => 'facebook_lang_code',
			'type' => 'text',
			'title' => esc_html__('Facebook Language Code', 'epcl_framework'),
			'desc' => __('<b>es_ES</b> = spanish, <b>en_US</b> = English, <b>fr_FR</b> = French.<br>You can search for your <a href="https://gist.github.com/mechastorm/3626739">code here.</a>.', 'epcl_framework'),
			'dependency' => array('hosted_comments', '==', '3'),
		),
        array(
			'id' => 'facebook_app_id',
			'type' => 'text',
			'title' => esc_html__('Facebook Moderation APP ID', 'epcl_framework'),
			'desc' => __('It is recommended to create an APP and link to your FB comments to moderate it, <a href="https://developers.facebook.com/docs/plugins/comments/#moderation">check all information here.</a>', 'epcl_framework'),
			'dependency' => array('hosted_comments', '==', '3'),
		),
	)
) );

// Rating Options

CSF::createSection( $opt_name, array(
	'title' => esc_html__('Post Rating', 'epcl_framework'),
    // 'icon' => 'el-icon-edit',
    'parent' => 'blog',
	'fields' => array(
        array(
            'id'   => 'info_normal',
            'type' => 'subheading',
            'title' => esc_html__('Important:', 'epcl_framework'),
            'subtitle' => __("The purpose of the rating is <b>purely informational</b>, such as assigning a <b>higher difficulty, showing an author's recommendation,</b> etc. It is not related to the rating made by visitors or customers.", 'epcl_framework')
        ),
        array(
			'id' => 'enable_rating',
			'type' => 'switcher',
			'title' => esc_html__('Enable Rating', 'epcl_framework'),
			// 'desc' => esc_html__('If disabled, this option will hide author.', 'epcl_framework'),
			'default' => 1
        ),
        array(
			'id' => 'rating_wording',
			'type' => 'text',
			'title' => esc_html__('Rating Wording', 'epcl_framework'),
			'desc' => esc_html__('e.g. Difficulty, Rating, Recommended, etc.', 'epcl_framework'),
            'default' => 'Rating'
        ),
	)
) );

// Grid Posts Options

CSF::createSection( $opt_name, array(
	'title' => esc_html__('Grid Posts', 'epcl_framework'),
    // 'icon' => 'el-icon-edit',
    'parent' => 'blog',
	'fields' => array(
        array(
            'id'   => 'info_normal',
            'type' => 'subheading',
            'title' => esc_html__('Important:', 'epcl_framework'),
            'subtitle' => esc_html__('All these options affects grid post with 3 columns and grid with sidebar (2 columns)', 'epcl_framework')
        ),
        // array (
		// 	'id' => 'grid_loop_style',
		// 	'title' => esc_html__('Loop Post Style', 'epcl_framework'),
        //     'subtitle' => esc_html__('Default: Inherit', 'epcl_framework'),
		// 	'desc' => __('This is a global option, it will override any article layout (only apply for grid modules).', 'epcl_framework'),
		// 	'type' => 'button_set',
		// 	'options' => array(
        //         'inherit' => esc_html__('Inherit', 'epcl_framework'),
        //         'text-only' => esc_html__('Text Only', 'epcl_framework'),
		// 		'small-image' => esc_html__('Small Image', 'epcl_framework'),
		// 		'standard-image' => esc_html__('Standard Image', 'epcl_framework'),
		// 	),
		// 	'default' => 'inherit',
		// ),
        array(
			'id' => 'grid_display_author',
			'type' => 'switcher',
			'title' => esc_html__('Display Author', 'epcl_framework'),
			'desc' => esc_html__('If disabled, this option will hide author.', 'epcl_framework'),
			'default' => 1
        ),
        array(
			'id' => 'grid_title_length',
			'type' => 'number',
            'title' => esc_html__('Title length', 'epcl_framework'),
            'subtitle' => esc_html__('(Optional)', 'epcl_framework'),
            'desc' => __('The max number of <b>Characters</b> to show on post titles (only applies on post lists). e.g. 40'),
            'validate' => 'no_special_chars',
            'default' => '',
            'unit' => 'char'
        ),
        array(
			'id' => 'grid_display_excerpt',
			'type' => 'switcher',
			'title' => esc_html__('Display Excerpt', 'epcl_framework'),
			'desc' => esc_html__('If disabled, all words after your posts titles will be removed.', 'epcl_framework'),
			'default' => 1
        ),
        array(
			'id' => 'small_excerpt_length',
			'type' => 'number',
			'title' => esc_html__('Excerpt length', 'epcl_framework'),
            'desc' => __('The max number of words to show after post titles. Recommended: 17', 'epcl_framework'),
            'validate' => 'no_special_chars',
            'default' => 17,
            'unit' => 'words'
        ),
        // array(
		// 	'id' => 'enable_meta_data',
		// 	'type' => 'switcher',
		// 	'title' => esc_html__('Display Meta data', 'epcl_framework'),
		// 	'desc' => esc_html__('This option will enable the date, comments and views counter.', 'epcl_framework'),
		// 	'default' => 1
        // ),
	)
) );

// Classic Posts Option

CSF::createSection( $opt_name, array(
	'title' => esc_html__('Classic Posts', 'epcl_framework'),
    // 'icon' => 'el-icon-edit',
    'parent' => 'blog',
	'fields' => array(
        // array (
		// 	'id' => 'classic_loop_style',
		// 	'title' => esc_html__('Loop Post Style', 'epcl_framework'),
        //     'subtitle' => esc_html__('Default: Inherit', 'epcl_framework'),
		// 	'desc' => __('This is a global option, it will override any article layout (only apply for classic modules).', 'epcl_framework'),
		// 	'type' => 'button_set',
		// 	'options' => array(
        //         'inherit' => esc_html__('Inherit', 'epcl_framework'),
        //         'text-only' => esc_html__('Text Only', 'epcl_framework'),
		// 		'small-image' => esc_html__('Small Image', 'epcl_framework'),
		// 		'standard-image' => esc_html__('Standard Image', 'epcl_framework'),
		// 	),
		// 	'default' => 'inherit',
		// ),
        array(
			'id' => 'classic_display_author',
			'type' => 'switcher',
			'title' => esc_html__('Display Author', 'epcl_framework'),
			'desc' => esc_html__('If disabled, this option will hide author.', 'epcl_framework'),
			'default' => 1
        ),
        array(
			'id' => 'classic_title_length',
			'type' => 'number',
            'title' => esc_html__('Title length', 'epcl_framework'),
            'subtitle' => esc_html__('(Optional)', 'epcl_framework'),
            'desc' => __('The max number of <b>Characters</b> to show on post titles (only applies on post lists). e.g. 40'),
            'validate' => 'no_special_chars',
            'default' => '',
            'unit' => 'char'
        ),
        array(
			'id' => 'classic_display_excerpt',
			'type' => 'switcher',
			'title' => esc_html__('Display Excerpt', 'epcl_framework'),
			'desc' => esc_html__('If disabled, all words after your posts titles will be removed.', 'epcl_framework'),
			'default' => 1
        ),
        array(
			'id' => 'large_excerpt_length',
			'type' => 'number',
			'title' => esc_html__('Excerpt length', 'epcl_framework'),
            'desc' => __('The max number of words to show after post titles. Recommended: 20', 'epcl_framework'),
            'validate' => 'no_special_chars',
            'default' => 20,
            'unit' => 'words'
        ),
        // array(
		// 	'id' => 'classic_enable_meta_data',
		// 	'type' => 'switcher',
		// 	'title' => esc_html__('Display Meta data on Classic Posts', 'epcl_framework'),
		// 	'desc' => esc_html__('Enable the date, comments and views counter.', 'epcl_framework'),
		// 	'default' => 1
        // ),
	)
) );

// Layouts

CSF::createSection( $opt_name, array(
	'title' => esc_html__('Layouts', 'epcl_framework'),
    // 'icon' => 'el-icon-edit',
    'parent' => 'blog',
	'fields' => array(
        // array(
		// 	'id' => 'mobile_layout',
		// 	'type' => 'button_set',
		// 	'title' => esc_html__('Mobile Layout', 'epcl_framework'),
		// 	'subtitle' => '',
		// 	'desc' => __('By default is added some space on mobile devices, if you want to remove those spaces select fullwidth.', 'epcl_framework'),
		// 	'options' => array(
        //         'boxed' => 'Boxed (with margins)',
        //         'fullwidth' => 'Fullwidth'
        //     ),
		// 	'default' => 'boxed'
        // ),
        array(
			'id' => 'title_home_layout',
            'type' => 'subheading',
			'title' => __( 'Home', 'epcl_framework'),
            'subtitle' => __( 'Without modules, no "Home" template selected.', 'epcl_framework')
		),
        array(
            'id' => 'layout_style_posts_page',
            'type' => 'button_set',
            'title' => esc_html__('Layout Style', 'epcl_framework'),
            'desc' => esc_html__('Display text only or enable images on Post Lists.', 'epcl_framework'),
            'options'  => array(
                'image' => esc_html__('Image (if available)', 'epcl_framework'),
                'text' => esc_html__('Text Only', 'epcl_framework'),             
            ),
            'default' => 'text',
        ),        
        array(
			'id' => 'posts_page_layout',
			'type' => 'button_set',
			'title' => esc_html__('Articles layout on posts page (homepage without modules).', 'epcl_framework'),
            'desc' => esc_html__('By default is used grid posts (3 columns), some customers uses 1 page as homepage and another one for Blog.', 'epcl_framework'),
            'options'  => array(
                'classic' => esc_html__('Classic Posts', 'epcl_framework'),
                'classic_sidebar' => esc_html__('Classic with Sidebar', 'epcl_framework'),
                'grid_3_cols' => esc_html__('Grid 3 columns', 'epcl_framework'),
                // 'grid_4_cols' => esc_html__('Grid 4 columns', 'epcl_framework'),
                'grid_sidebar' => esc_html__('Grid with sidebar', 'epcl_framework'),                
            ),
			'default' => 'classic_sidebar'
        ),   
        array(
			'id' => 'title_archives_layout',
            'type' => 'subheading',
			'title' => __( 'Archives Standard Pages:', 'epcl_framework'),
            'subtitle' => __( 'Category, Tags, Search results and Author.', 'epcl_framework')
		),    
        array(
            'id' => 'layout_style_archives',
            'type' => 'button_set',
            'title' => esc_html__('Layout Style', 'epcl_framework'),
            'desc' => esc_html__('Display text only or enable images on Post Lists.', 'epcl_framework'),
            'options'  => array(
                'image' => esc_html__('Image (if available)', 'epcl_framework'),
                'text' => esc_html__('Text Only', 'epcl_framework'),             
            ),
            'default' => 'text',
        ), 
        array(
			'id' => 'archive_layout',
			'type' => 'button_set',
			'title' => esc_html__('Articles layout on archives page.', 'epcl_framework'),
            'desc' => esc_html__('By default is used classic posts.', 'epcl_framework'),
            'options'  => array(
                'classic' => esc_html__('Classic Posts', 'epcl_framework'),
                'classic_sidebar' => esc_html__('Classic with Sidebar', 'epcl_framework'),
                'grid_3_cols' => esc_html__('Grid 3 columns', 'epcl_framework'),
                // 'grid_4_cols' => esc_html__('Grid 4 columns', 'epcl_framework'),
                'grid_sidebar' => esc_html__('Grid with sidebar', 'epcl_framework'),                
            ),
			'default' => 'grid_3_cols'
        ),        
        array(
			'id' => 'search_layout',
			'type' => 'button_set',
			'title' => esc_html__('Articles layout on search results page.', 'epcl_framework'),
            'desc' => esc_html__('By default is used classic posts.', 'epcl_framework'),
            'options'  => array(
                'classic' => esc_html__('Classic Posts', 'epcl_framework'),
                'classic_sidebar' => esc_html__('Classic with Sidebar', 'epcl_framework'),
                'grid_3_cols' => esc_html__('Grid 3 columns', 'epcl_framework'),
                // 'grid_4_cols' => esc_html__('Grid 4 columns', 'epcl_framework'),
                'grid_sidebar' => esc_html__('Grid with sidebar', 'epcl_framework'),
            ),
			'default' => 'classic_sidebar'
        ),
        array(
			'id' => 'author_layout',
			'type' => 'button_set',
			'title' => esc_html__('Articles layout on Author results page.', 'epcl_framework'),
            'desc' => esc_html__('By default is used classic with sidebar.', 'epcl_framework'),
            'options'  => array(
                'classic' => esc_html__('Classic Posts', 'epcl_framework'),
                'classic_sidebar' => esc_html__('Classic with Sidebar', 'epcl_framework'),
                'grid_3_cols' => esc_html__('Grid 3 columns', 'epcl_framework'),
                // 'grid_4_cols' => esc_html__('Grid 4 columns', 'epcl_framework'),
                'grid_sidebar' => esc_html__('Grid with sidebar', 'epcl_framework'),
            ),
			'default' => 'classic_sidebar'
        ),
	)
) );

// TOC Options

CSF::createSection( $opt_name, array(
	'title' => esc_html__('Table of Contents', 'epcl_framework'),
    // 'icon' => 'el-icon-edit',
    'parent' => 'blog',
	'fields' => array(
        // array(
        //     'id'   => 'info_normal',
        //     'type' => 'subheading',
        //     'title' => esc_html__('Important:', 'epcl_framework'),
        //     'subtitle' => __("The purpose of the rating is <b>purely informational</b>, such as assigning a <b>higher difficulty, showing an author's recommendation,</b> etc. It is not related to the rating made by visitors or customers.", 'epcl_framework')
        // ),
        array(
			'id' => 'enable_single_toc',
			'type' => 'switcher',
			'title' => esc_html__('Display TOC (before content)', 'epcl_framework'),
			'desc' => esc_html__('Display Table of Contents before Single Article content.', 'epcl_framework'),
			'default' => 0
        ),
        array(
			'id' => 'single_toc_state',
			'type' => 'button_set',
			'title' => esc_html__('TOC State', 'epcl_framework'),
            'desc' => esc_html__('By default the TOC toggle is closed, this option is globally applied to all posts.', 'epcl_framework'),
            'options'  => array(
                'closed' => esc_html__('Closed', 'epcl_framework'),
                'open' => esc_html__('Open', 'epcl_framework'),
            ),
			'default' => 'closed'
        ),
        array(
			'id' => 'toc_heading_selector',
			'type' => 'text',
			'title' => esc_html__('Heading Selector', 'epcl_framework'),
            'subtitle' => 'Default: h1, h2, h3, h4, h5',
			'desc' => esc_html__('Select which heading levels will be used to create the TOC.', 'epcl_framework'),
            'default' => 'h1, h2, h3, h4, h5'
        ),
	)
) );

// Single Post

CSF::createSection( $opt_name, array(
	'title' => esc_html__('Single Post Content', 'epcl_framework'),
    // 'icon' => 'el-icon-edit',
    'parent' => 'blog',
	'fields' => array(
        array(
			'id' => 'single_post_layout',
			'type' => 'button_set',
			'title' => esc_html__('Post Layout', 'epcl_framework'),
			'subtitle' => '',
			'desc' => __('This option will override any post setting, it is useful if you want to show all your post with the same layout.', 'epcl_framework'),
            'options' => array(
                'inherit' => 'Inherit from every Post',
                'vertical' => 'Vertical',
                'classic' => 'Classic',
                'fullcover' => 'Fullcover',
            ),
			'default' => 'inherit'
        ),
        array(
			'id' => 'enable_featured_image',
			'type' => 'switcher',
			'title' => esc_html__('Display Featured image globally', 'epcl_framework'),
			'desc' => esc_html__('If is OFF, all featured images from all posts will be hidden.', 'epcl_framework'),
			'default' => 1
		),
        array(
			'id' => 'enable_single_meta_data',
			'type' => 'switcher',
			'title' => esc_html__('Display Date (below main title)', 'epcl_framework'),
			'desc' => esc_html__('This option only affects single posts.', 'epcl_framework'),
			'default' => 1
        ),
        // array(
		// 	'id' => 'enable_single_modified_date',
		// 	'type' => 'switcher',
		// 	'title' => esc_html__('Display Last Modified Date', 'epcl_framework'),
		// 	'desc' => esc_html__('This info is located below post near the Post Categories.', 'epcl_framework'),
		// 	'default' => 1
        // ),
        array(
			'id' => 'enable_single_author_top',
			'type' => 'switcher',
			'title' => esc_html__('Display Author (below main title)', 'epcl_framework'),
			'desc' => esc_html__('This option only affects single posts.', 'epcl_framework'),
			'default' => 0
        ),
        array(
			'id' => 'enable_single_reading_time',
			'type' => 'switcher',
			'title' => esc_html__('Display Reading Time (below main title)', 'epcl_framework'),
			'desc' => esc_html__('This option only affects single posts.', 'epcl_framework'),
			'default' => 0
        ),
        array(
			'id' => 'enable_single_views_counter',
			'type' => 'switcher',
			'title' => esc_html__('Display Views Counter (below main title)', 'epcl_framework'),
			'desc' => esc_html__('This option only affects single posts.', 'epcl_framework'),
			'default' => 0
        ),
        array(
			'id' => 'enable_single_rating',
			'type' => 'switcher',
			'title' => esc_html__('Display Rating (below main title)', 'epcl_framework'),
			'desc' => esc_html__('This option only affects single posts.', 'epcl_framework'),
			'default' => 0
        ),
        array(
			'id' => 'enable_single_category',
			'type' => 'switcher',
			'title' => esc_html__('Display Categories', 'epcl_framework'),
			'desc' => esc_html__('If is OFF, it will disable categories only for single post.', 'epcl_framework'),
			'default' => 1
        ),
        array(
			'id' => 'enable_single_tags',
			'type' => 'switcher',
			'title' => esc_html__('Display Tags', 'epcl_framework'),
			'desc' => esc_html__('If is OFF, it will disable tags only for single post.', 'epcl_framework'),
			'default' => 1
        ),
		// array(
		// 	'id' => 'enable_single_author',
		// 	'type' => 'switcher',
		// 	'title' => esc_html__('Display Author of Article', 'epcl_framework'),
		// 	'desc' => esc_html__('This option only affects posts.', 'epcl_framework'),
		// 	'default' => 1
		// ),
        array(
			'id' => 'siblings_posts',
			'type' => 'switcher',
			'title' => esc_html__('Display next/prev posts', 'epcl_framework'),
			'desc' => esc_html__('This option will enable siblings posts after main article.', 'epcl_framework'),
			'default' => 1
		),
        array(
            'id' => 'related_posts',
            'type' => 'switcher',
            'title' => esc_html__('Display related posts', 'epcl_framework'),
            'desc' => esc_html__('This option will enable related posts after the author information.', 'epcl_framework'),
            'default' => 1
        ),
        array(
			'id' => 'related_posts_limit',
			'type' => 'spinner',
			'title' => esc_html__('Related Posts Limit', 'epcl_framework'),
			'subtitle' => esc_html__('Recommended: 4', 'epcl_framework'),
			'desc' => esc_html__('Set the max amount of articles to display.', 'epcl_framework'),
			'default' => 4,
			'min' => 1,
			'step' => 1,
			'max' => 10,
			// 'dependency' => array('related_posts', '==', '1')
		),
	)
) );

// Share buttons

CSF::createSection( $opt_name, array(
	'title' => esc_html__('Share Buttons', 'epcl_framework'),
    // 'icon' => 'el-icon-edit',
    'parent' => 'blog',
	'fields' => array(
        array(
			'id' => 'enable_share_buttons',
			'type' => 'switcher',
			'title' => esc_html__('Display Share Buttons', 'epcl_framework'),
			'desc' => esc_html__('Show share buttons on the bottom of the post.', 'epcl_framework'),
			'default' => 1
        ),
        array(
			'id' => 'enable_share_buttons_page',
			'type' => 'switcher',
			'title' => esc_html__('Display Share Buttons on Pages', 'epcl_framework'),
			'desc' => esc_html__('Show share buttons on the bottom of the page.', 'epcl_framework'),
			'default' => 0
        ),
        // array(
		// 	'id' => 'enable_sticky_share_buttons',
		// 	'type' => 'switcher',
		// 	'title' => esc_html__('Display Sticky Share Buttons', 'epcl_framework'),
		// 	'desc' => esc_html__('Show floating share buttons at the left of the post.', 'epcl_framework'),
        //     'default' => 1,
        // ),
        // array(
		// 	'id' => 'enable_sticky_share_buttons_page',
		// 	'type' => 'switcher',
		// 	'title' => esc_html__('Display Sticky Share Buttons on Pages', 'epcl_framework'),
		// 	'desc' => esc_html__('Show floating share buttons at the left of all pages.', 'epcl_framework'),
        //     'default' => 0,
        // ),
        array(
			'id' => 'enable_single_facebook',
			'type' => 'switcher',
			'title' => '<i class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-facebook fa-stack-1x"></i></i> '.__('Display Facebook', 'epcl_framework'),
			'desc' => esc_html__('Enable or disable this specific network.', 'epcl_framework'),
            'default' => 1,
        ),
        array(
			'id' => 'enable_single_twitter',
			'type' => 'switcher',
			'title' => '<i class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-twitter fa-stack-1x"></i></i> '.__('Display Twitter', 'epcl_framework'),
			'desc' => esc_html__('Enable or disable this specific network.', 'epcl_framework'),
            'default' => 1,
        ),
        array(
			'id' => 'enable_single_whatsapp',
			'type' => 'switcher',
			'title' => '<i class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-whatsapp fa-stack-1x"></i></i> '.__('Display Whatsapp', 'epcl_framework'),
			'desc' => esc_html__('Enable or disable this specific network (only visible on mobile).', 'epcl_framework'),
            'default' => 1,
        ),
        array(
			'id' => 'enable_single_email',
			'type' => 'switcher',
			'title' => '<i class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-envelope fa-stack-1x"></i></i> '.__('Display Email', 'epcl_framework'),
			'desc' => esc_html__('Enable or disable share by email.', 'epcl_framework'),
            'default' => 1,
        ),
        array(
			'id' => 'enable_single_pinterest',
			'type' => 'switcher',
			'title' => '<i class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-pinterest fa-stack-1x"></i></i> '.__('Display Pinterest', 'epcl_framework'),
			'desc' => esc_html__('Enable or disable this specific network.', 'epcl_framework'),
            'default' => 0,
        ),
        array(
			'id' => 'enable_single_linkedin',
			'type' => 'switcher',
			'title' => '<i class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-linkedin fa-stack-1x"></i></i> '.__('Display Linkedin', 'epcl_framework'),
			'desc' => esc_html__('Enable or disable this specific network.', 'epcl_framework'),
            'default' => 0,
        ),
        array(
			'id' => 'enable_single_telegram',
			'type' => 'switcher',
			'title' => '<i class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-telegram fa-stack-1x"></i></i> '.__('Display Telegram', 'epcl_framework'),
			'desc' => esc_html__('Enable or disable this specific network.', 'epcl_framework'),
            'default' => 0,
        ),
        array(
			'id' => 'enable_single_vk',
			'type' => 'switcher',
			'title' => '<i class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-vk fa-stack-1x"></i></i> '.__('Display VKontakte', 'epcl_framework'),
			'desc' => esc_html__('Enable or disable this specific network (popular on Russia).', 'epcl_framework'),
            'default' => 0,
        ),
	)
) );
