<?php

if( !function_exists('register_sidebar') ) return;

add_action( 'widgets_init', 'epcl_widgets_init' );

function epcl_widgets_init() {

	/* Default Sidebar Widgets (left) */

    register_sidebar(array(
        'name' => esc_html_x('Article Sidebar','admin', 'zento'),
        'id' => 'epcl_sidebar_default',
        'description' => esc_html_x('Right sidebar inside single posts.', 'admin', 'zento'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '<div class="clear"></div></section>',
        'before_title' => '<h3 class="widget-title title medium bordered"><svg class="decoration"><use xlink:href="'.EPCL_THEMEPATH.'/assets/images/svg-icons.svg#title-decoration"></use></svg>',
        'after_title' => '</h3>',
    ));

	/* Home Widgets */

	register_sidebar(array(
		'name' => esc_html_x('Home Sidebar','admin', 'zento'),
		'id' => 'epcl_sidebar_home',
		'description' => esc_html_x('Sidebar for home, archives and results','admin', 'zento'),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget' => '<div class="clear"></div></section>',
        'before_title' => '<h3 class="widget-title title medium bordered"><svg class="decoration"><use xlink:href="'.EPCL_THEMEPATH.'/assets/images/svg-icons.svg#title-decoration"></use></svg>',
        'after_title' => '</h3>',
    ));

	/* Footer Widgets */

	register_sidebar(array(
		'name' => esc_html_x('Footer','admin', 'zento'),
		'id' => 'epcl_sidebar_footer',
		'description' => esc_html_x('Footer Sidebar','admin', 'zento'),
		'before_widget' => '<section id="%1$s" class="widget %2$s grid-30 tablet-grid-50 mobile-grid-100">',
		'after_widget' => '<div class="clear"></div></section>',
        'before_title' => '<h3 class="widget-title title medium bordered"><svg class="decoration"><use xlink:href="'.EPCL_THEMEPATH.'/assets/images/svg-icons.svg#title-decoration"></use></svg>',
        'after_title' => '</h3>',
	));

    /* Footer Mobile Widgets */

	register_sidebar(array(
		'name' => esc_html_x('Footer Mobile','admin', 'zento'),
		'id' => 'epcl_sidebar_footer_mobile',
		'description' => esc_html_x('Footer Mobile Sidebar','admin', 'zento'),
		'before_widget' => '<section id="%1$s" class="widget %2$s grid-30">',
		'after_widget' => '<div class="clear"></div></section>',
        'before_title' => '<h3 class="widget-title title medium bordered"><svg class="decoration"><use xlink:href="'.EPCL_THEMEPATH.'/assets/images/svg-icons.svg#title-decoration"></use></svg>',
        'after_title' => '</h3>',
	));

	/* Dynamic Sidebars */

	$epcl_theme = epcl_get_theme_options();
    if( !empty($epcl_theme['custom_sidebar']) ){

        if( !isset($epcl_theme['custom_sidebar'][0]) && !is_array( $epcl_theme['custom_sidebar'][0] ) ){
            array_unique($epcl_theme['custom_sidebar']);
        }

        foreach( $epcl_theme['custom_sidebar'] as $name ){

            if( is_array($name) ){
                foreach($name as $item){
                    $id = sanitize_title($item);
                    register_sidebar(array(
                        'name' => $item,
                        'id' => $id,
                        'before_widget' => '<section id="%1$s" class="widget %2$s">',
                        'after_widget' => '<div class="clear"></div></section>',
                        'before_title' => '<h3 class="widget-title title medium bordered"><svg class="decoration"><use xlink:href="'.EPCL_THEMEPATH.'/assets/images/svg-icons.svg#title-decoration"></use></svg>',
                        'after_title' => '</h3>',
                    ));
                }
            } elseif( !empty($name) ){
                $id = sanitize_title($name);
                register_sidebar(array(
                    'name' => $name,
                    'id' => $id,
                    'before_widget' => '<section id="%1$s" class="widget %2$s">',
                    'after_widget' => '<div class="clear"></div></section>',
                    'before_title' => '<h3 class="widget-title title medium bordered"><svg class="decoration"><use xlink:href="'.EPCL_THEMEPATH.'/assets/images/svg-icons.svg#title-decoration"></use></svg>',
                    'after_title' => '</h3>',
                ));
            }
        }
    }

}
