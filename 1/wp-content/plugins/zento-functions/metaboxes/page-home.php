<?php
$page_key = 'epcl_home';
$prefix_key = 'epcl_';

CSF::createMetabox( $page_key, array(
    'title'          => 'Home Builder',
    'post_type'      => 'page',
    'page_templates' => 'page-templates/home.php', // Spesific page templates
  ) );

$fields[] = array(
    'id'         => 'module_name',
    'type'       => 'text',
    'title'      => 'Module',
);
$fields[] = array(
    'id'    => 'layout',
    'type'  => 'radio',
    'title' => esc_attr__('Select Layout:', 'epcl_framework'),
    'inline' => true,
    'placeholder' => 'Select an option',
    'options' => array(
        'Post Lists' => array(
            'grid_posts' => esc_html__('Grid Posts', 'epcl_framework'),
            'grid_sidebar' => esc_html__('Grid Posts with Sidebar', 'epcl_framework'),
            'classic_posts' => esc_html__('Classic Posts', 'epcl_framework'),
            'classic_posts_sidebar' => esc_html__('Classic with Sidebar', 'epcl_framework'),
        ),
        'Slider / Carousel' => array(
            // 'posts_slider' => esc_html__('Posts Slider', 'epcl_framework'),
            'posts_carousel' => esc_html__('Posts Carousel', 'epcl_framework'),
            // 'categories_carousel' => esc_html__('Categories Carousel', 'epcl_framework'),
        ),
        'Others' => array(
            'gutenberg_editor' => esc_html__('Gutenberg Editor', 'epcl_framework'),
            'intro_text' => esc_html__('Intro Text', 'epcl_framework'),
            'popular_categories' => esc_html__('Popular Categories', 'epcl_framework'),
            'advertising' => esc_html__('Advertising', 'epcl_framework'),
            'text_editor' => esc_html__('Classic/Text Editor', 'epcl_framework'),
        ),                                               
    ),
);
$fields = array_merge($fields, include('modules/global.php') );
$fields = array_merge($fields, include('modules/posts-carousel.php') );
$fields = array_merge($fields, include('modules/sidebar.php') );
$fields = array_merge($fields, include('modules/popular-categories.php') );
$fields = array_merge($fields, include('modules/advertising.php') );
$fields = array_merge($fields, include('modules/text-editor.php') );
$fields = array_merge($fields, include('modules/intro-text.php') );
$fields = array_merge($fields, include('modules/gutenberg-editor.php') );
  
CSF::createSection( $page_key, array(
    'title'  => 'Modules creator',
    'icon'   => 'fa fa-rocket',
    'fields' => array(
        array(
            'id' => 'modules',
            'type' => 'group',
            'button_title' => esc_html__('Add Row', 'epcl_framework'),
            'title' => esc_html__('Modules *', 'epcl_framework'),
            'subtitle' => __('Add different kinds of layouts.<br><small><b>Important:</b> Only 1 Post List per Page.</small><br><br>You can <b>drag & drop</b> to re-order.', 'epcl_framework'),
            'accordion_title_number' => true,
            'fields' => $fields
        ),
    )
) );
