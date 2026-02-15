<?php

/* Custom ADS */

function epcl_custom_ads($atts, $content = null) {
	global $epcl_theme;
    if( empty($epcl_theme) ) return;
    
    extract(shortcode_atts(array(
		'id' => '1'
    ), $atts));
    
    $section = 'custom_shortcode';
    
    if( $id > 1 ){
        $section = 'custom_shortcode_'.$id; 
    }

    if( $epcl_theme['ads_enabled_'.$section] !== '1' ) return;
    
    if( isset($epcl_theme['ads_mobile_'.$section]) && $epcl_theme['ads_mobile_'.$section] == '0' && wp_is_mobile() ) return;

	$margin_top = '0';
	$margin_bottom = '0';
	if( $epcl_theme['ads_mt_'.$section] ){
		$margin_top = $epcl_theme['ads_mt_'.$section];
	}
	if( $epcl_theme['ads_mb_'.$section] ){
		$margin_bottom = $epcl_theme['ads_mb_'.$section];
	}
	$html = '<!-- start: .epcl-banner -->
	    <div class="epcl-banner mobile-grid-100 textcenter epcl-banner-'.$section.'" style="margin-top: '.esc_attr($margin_top).'px; margin-bottom: '.esc_attr($margin_bottom). 'px;">';
	if( !empty($epcl_theme['ads_image_'.$section]) && $epcl_theme['ads_type_'.$section] == 'image' ) {
		$html .= '		    
	            <a href="'.esc_url( $epcl_theme['ads_url_'.$section] ).'" target="_blank">
	                <img src="'.esc_attr( $epcl_theme['ads_image_'.$section]['url'] ).'" class="custom-image" alt="'.esc_attr__('Banner', 'zento').'">
	            </a>';
    }else{
		$html .= $epcl_theme['ads_code_'.$section];
    }

    $html .= '
		</div>
	    <!-- end: .epcl-banner -->
	    <div class="clear"></div>';

	return $html;
}
add_shortcode('epcl_custom_ads', 'epcl_custom_ads');

/* Columns */

function epcl_shortcodes_columns($atts, $content = null) {
	extract(shortcode_atts(array(
		'structure' => ''
	), $atts));
		
	return '<div class="epcl-shortcode epcl-columns">'.do_shortcode($content).'<div class="clear"></div></div>';
	 
}
add_shortcode('epcl_columns', 'epcl_shortcodes_columns');

/* Column */

function epcl_shortcodes_column($atts, $content = null) {
	extract(shortcode_atts(array(
		'width' => '50'
    ), $atts));
    
    $width = intval($width);
	
	return '<div class="epcl-shortcode epcl-col grid-'.$width.'">'.wpautop( do_shortcode($content) ).'</div>';

}
add_shortcode('epcl_col', 'epcl_shortcodes_column');

/* Button */

function epcl_button_shortcode($atts, $content = NULL) {
	extract( shortcode_atts( array(
		'label' => '',
		'url' => '',
		'color' => 'primary-color',
		'type' => 'primary-style',
		'size' => 'regular',
		'icon' => '',
        'target' => '_self',
        'rel' => ''
	), $atts ) );
	// if($icon) $icon = '<i class="epcl-icon fa '.$icon.'"></i>';
    // else $icon = '';
    $rel_attr = '';
    if( $rel == 'nofollow' ) $rel_attr = 'rel="nofollow"';
	return '<a href="'.$url.'" class="epcl-shortcode epcl-button '.$size.' '.$type.' '.$color.'" target="'.$target.'" '.$rel_attr.'>'.$icon.$label.'</a>';
	
}
add_shortcode('epcl_button', 'epcl_button_shortcode');

/* Boxes/Alerts */

function epcl_box_shortcode($atts, $content = NULL){
	extract( shortcode_atts( array(
		'type' => 'error',
		'color' => ''
	), $atts ) );
	if($type == 'custom' && $color){
		return '<div class="epcl-shortcode epcl-box custom" style="background: '.$color.';">'.do_shortcode($content).'</div>';
	}else{
		switch($type){
			default: case 'error': $icon = '❌'; break;
			case 'success': $icon = '✔️'; break;
			case 'notice': $icon = '💪'; break;
			case 'information': $icon = '💡'; break;
		}
		return '<div class="epcl-shortcode epcl-box '.$type.'"><span class="epcl-icon">'.$icon.'</span><div class="epcl-box-content">'.do_shortcode($content).'</div></div>';	
	}
}

add_shortcode('epcl_box', 'epcl_box_shortcode');

/* Icon */

function epcl_icon_shortcode($atts, $content = NULL){
	extract( shortcode_atts( array(
		'size' => '16px',
		'color' => '#999999',
		'icon' => 'icon-circlepath',
	), $atts ) );
    $size = str_replace('px', '', $size);
	return '<i class="epcl-shortcode epcl-icon fa '.$icon.'" style="font-size: '.$size.'px;color: '.$color.';"></i>';	
}
add_shortcode('epcl_icon', 'epcl_icon_shortcode');

/* Elements */

function epcl_clear_shortcode($atts, $content = NULL){
	return '<div class="clear"></div>';	
}
add_shortcode('clear', 'epcl_clear_shortcode');

/* Toggles */

function epcl_toggle_shortcode($atts, $content = NULL){
	extract( shortcode_atts( array(
		'title' => '',
        'show' => 'closed',
        'custom_class' => ''
	), $atts ) );
	$active = '';
	if($show == 'opened') $active = 'active';
	return '<div class="epcl-shortcode epcl-toggle epcl-toggle-elem '.$show.' '.esc_attr($custom_class).'"><h3 class="toggle-title">'.$title.'<svg class="epcl-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M11.9997 13.1714L16.9495 8.22168L18.3637 9.63589L11.9997 15.9999L5.63574 9.63589L7.04996 8.22168L11.9997 13.1714Z"></path></svg></h3><div class="toggle-content">'.do_shortcode($content).'</div></div>';
}

add_shortcode('epcl_toggle', 'epcl_toggle_shortcode');

/* Accordion */

function epcl_accordions_shortcode($atts, $content = NULL){
	return '<div class="epcl-shortcode epcl-accordions">'.do_shortcode($content).'</div>';
}

add_shortcode('epcl_accordions', 'epcl_accordions_shortcode');

function epcl_accordion_shortcode($atts, $content = NULL){
	extract( shortcode_atts( array(
        'title' => '',
        'custom_class' => ''
	), $atts ) );
	return '<div class="epcl-shortcode epcl-toggle accordion-elem '.esc_attr($custom_class).'"><h3 class="toggle-title">'.$title.'<svg class="epcl-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M11.9997 13.1714L16.9495 8.22168L18.3637 9.63589L11.9997 15.9999L5.63574 9.63589L7.04996 8.22168L11.9997 13.1714Z"></path></svg></h3><div class="toggle-content">'.do_shortcode($content).'</div></div>';
}

add_shortcode('epcl_accordion', 'epcl_accordion_shortcode');

/* Tabs */

$tabs_divs = '';

function epcl_tabs_shortcode($atts, $content = NULL ) {
    global $tabs_divs;
	extract(shortcode_atts(array(  
        'mode' => 'horizontal'
    ), $atts)); 
    $tabs_divs = '';

    $output = '<div class="epcl-shortcode epcl-tabs">';
		$output.= '<ul class="tab-links">'.do_shortcode($content).'</ul>';
		$output.= '<div class="tab-container">'.do_shortcode($tabs_divs).'</div>';
		$output.= '<div class="clear"></div>';
	$output.= '</div>';
    return $output;  
}
add_shortcode('epcl_tabs', 'epcl_tabs_shortcode');

function epcl_tab_shortcode($atts, $content = NULL) {  
    global $tabs_divs;
    extract(shortcode_atts(array(  
        'title' => ''
    ), $atts));  
	$id = 'tab-'.sanitize_title($title).rand(100, 999);
    $output = '<li><a href="javascript:void(0)" data-id="'.$id.'">'.$title.'</a></li>';
    $tabs_divs .= '<div id="'.$id.'" class="tab-item">'.$content.'</div>';
    return $output;
}
add_shortcode('epcl_tab', 'epcl_tab_shortcode');