<?php
$epcl_theme = epcl_get_theme_options();
if( function_exists('icl_get_home_url') ) $home = icl_get_home_url();
else $home = home_url('/');
// Just demo purposes
if( isset($_GET['header']) ){
	$header_type = sanitize_text_field( $_GET['header'] );
	switch($header_type){
		default:
			$epcl_theme['header_type'] = 'compact';
		break;
        case 'minimalist':
			$epcl_theme['header_type'] = 'minimalist';
		break;
		case 'classic':
			$epcl_theme['header_type'] = 'classic';
		break;
		case 'notice':
			$epcl_theme['enable_notice'] = true;
        break;
        case 'advertising':
            $epcl_theme['header_type'] = 'advertising';
		break;
	}
}

// Only if theme options data has been created
$header_class = '';
if( !empty( $epcl_theme ) ){
    $header_class = $epcl_theme['header_type'];
    if( isset( $epcl_theme['enable_sticky_header'] ) && $epcl_theme['enable_sticky_header'] != false ){
        $header_class .=' enable-sticky';
    }
    if( epcl_get_option('enable_sticky_header_mobile', true) == false ){
        $header_class .=' disable-sticky-mobile';
    }
    if( isset($epcl_theme['sticky_logo_image']['url'] ) && $epcl_theme['sticky_logo_image']['url'] ){
        $header_class .=' has-sticky-logo'; 
    }
    if( isset($epcl_theme['enable_search_header']) && $epcl_theme['enable_search_header'] == '1'  ){
        add_filter('wp_nav_menu_items','epcl_search_nav_item', 10, 2);
    }
}else{
    $header_class .= 'minimalist';
}

?>

<?php get_template_part('partials/header/notice-text'); ?>

<!-- start: #header -->
<header id="header" class="<?php echo esc_attr($header_class); ?>">

    <!-- start: .menu-wrapper -->
    <div class="menu-wrapper">
        
        <div class="grid-container">
            <div class="epcl-flex grid-wrapper">

            <?php if( !empty($epcl_theme) && $epcl_theme['header_type'] == 'compact' ): ?>
                    <!-- start: .main-nav -->
                    <nav class="main-nav">      
                        <ul class="menu">
                            <li class="search-menu-item"><a href="<?php echo home_url('/'); ?>?s=" class="link"><svg class="icon main-color large"><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#search-icon"></use></svg> <span class="hide-on-mobile"><?php echo esc_html__('Quick Search...', 'zento'); ?></span></a></li> 
                        </ul>                  
                    </nav>
                    <!-- end: .main-nav -->
                <?php endif; ?>

                <?php get_template_part('partials/header/site-logo'); ?>
                
                <?php if( !empty($epcl_theme) && $epcl_theme['header_type'] == 'advertising' && function_exists('epcl_render_header_ads') ): ?>
                    <?php epcl_render_header_ads(); ?>
                <?php endif; ?>
                
                <?php if( !empty($epcl_theme) && $epcl_theme['header_type'] != 'compact' ): ?>
                    <?php get_template_part('partials/header/navigation'); ?>
                <?php endif; ?>

                <div class="account underline-effect hide-on-mobile hide-on-tablet hide-on-desktop-sm"> 
                    <?php if( epcl_get_option('enable_subscribe') == true ): ?>
                        <?php echo epcl_get_subscribe_button('hide-on-mobile hide-on-tablet hide-on-desktop-sm'); ?>
                    <?php endif ;?>
                </div>
                
                <div class="open-menu" on="tap:sidenav.open" role="button" tabindex="0">
                    <svg class="open icon ularge"><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#menu-icon"></use></svg>
                </div>

                <?php if( epcl_get_option('enable_search_header') == '1' || empty($epcl_theme) ): ?>
                    <a href="<?php echo home_url('/'); ?>?s=" class="link epcl-search-button hide-on-desktop-lg"><svg class="icon main-color large"><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#search-icon"></use></svg></a>
                <?php endif; ?>

                <div class="clear"></div>
            
            </div>		
            
            <div class="clear"></div>
        </div>
        
    </div>
    <!-- end: .menu-wrapper -->

</header>
<!-- end: #header -->

<div class="clear"></div>   

<?php
if( function_exists( 'epcl_render_global_ads' ) ){
	epcl_render_global_ads('below_header');
}
?>