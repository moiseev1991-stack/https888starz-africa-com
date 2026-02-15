<?php
/*
* Functions just for this particular theme
*
*/

if ( ! function_exists( 'wp_body_open' ) ) {
    function wp_body_open() {
        do_action( 'wp_body_open' );
    }
}

function epcl_is_amp() {
    $amp_enabled = epcl_get_option('amp_enabled', false);
    return function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() && $amp_enabled && !is_admin();
}

// Fix CSF framework on Customizer
if( !function_exists('epcl_customizer_preview') ) {
    function epcl_customizer_preview() {
        global $epcl_theme;
        $epcl_theme = get_option( EPCL_FRAMEWORK_VAR );
    }
    add_action('customize_preview_init', 'epcl_customizer_preview');
}

// Replace global $epcl_theme with function (security)
if( !function_exists('epcl_get_theme_options') ){
    function epcl_get_theme_options() {
        if( !defined('EPCL_PLUGIN_PATH') ) return false;
        global $epcl_theme;
        if( empty($epcl_theme) ){
            $epcl_theme = get_option( EPCL_FRAMEWORK_VAR );
        }
        if( !empty($epcl_theme) ){
            return $epcl_theme;
        }else{
            return false;
        }     
    }
}

// Replace global $epcl_module with function (security)
if( !function_exists('epcl_get_module_options') ){
    function epcl_get_module_options() {
        global $epcl_module;
        if( !empty($epcl_module) && defined('EPCL_PLUGIN_PATH') ){
            return $epcl_module;
        }else{
            return false;
        }     
    }
}

if( !function_exists('epcl_get_option') ){
    function epcl_get_option( $option = '', $default = '' ) {
        global $epcl_theme;
        if( empty($epcl_theme) && defined('EPCL_PLUGIN_PATH') ){
            $epcl_theme = get_option( EPCL_FRAMEWORK_VAR );
        }
        if( !empty($epcl_theme) && isset( $epcl_theme[ $option ] ) && defined('EPCL_PLUGIN_PATH') ){
            return $epcl_theme[ $option ];
        }else{
            if( $default !== '' ){
                return $default;
            }
            return false;
        }
    }
}

if( !function_exists('epcl_get_option_text') ){
    function epcl_get_option_text( $option = '', $default = '' ) {
        global $epcl_theme;
        if( empty($epcl_theme) && defined('EPCL_PLUGIN_PATH') ){
            $epcl_theme = get_option( EPCL_FRAMEWORK_VAR );
        }
        if( !empty($epcl_theme) && isset( $epcl_theme[ $option ] ) && defined('EPCL_PLUGIN_PATH') && $epcl_theme[ $option ] !== ''  ){
            return $epcl_theme[ $option ];
        }else{
            if( $default !== '' ){
                return $default;
            }
            return false;
        }
    }
}

// Gutenberg fonts on admin
function epcl_gutenberg_fonts_url() {
    $epcl_theme = epcl_get_theme_options();
    $fonts_url = '';
    $font_families[] = 'Urbanist:400,500,600,700,700i,800';
    $font_families[] = 'DM Sans:400,400i,700,700i';

    // Customs fonts from Theme options
    if( !empty($epcl_theme) && ( !empty($epcl_theme['body_font']['font-family']) || !empty($epcl_theme['primary_titles_font']['font-family']) ) ){
        if( $epcl_theme['body_font']['font-family'] != '' && $epcl_theme['body_font']['font-weight'] != '' ){
            $font_families[] = $epcl_theme['body_font']['font-family'].':'.$epcl_theme['body_font']['font-weight'];   
        }else if( $epcl_theme['body_font']['font-family'] != '' ){
            $font_families[] = $epcl_theme['body_font']['font-family'];
        }
        if( !empty( $epcl_theme['primary_titles_font'] ) ){            
            if( $epcl_theme['primary_titles_font']['font-family'] != '' && $epcl_theme['primary_titles_font']['font-weight'] != '' ){
                $font_families[] = $epcl_theme['primary_titles_font']['font-family'].':'.$epcl_theme['primary_titles_font']['font-weight'];   
            }else if( $epcl_theme['primary_titles_font']['font-family'] != '' ){
                $font_families[] = $epcl_theme['primary_titles_font']['font-family'];
            }
        }
    }

    $query_args = array(
        'family' => rawurlencode( implode( '|', $font_families ) ),
        'subset' => rawurlencode( 'latin,latin-ext' ),
    );
    $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
    
    return esc_url_raw( $fonts_url );
}

/* Add small excerpt length */

function epcl_usmall_excerpt_length($length){
    $length = 11;

	return $length;
}

function epcl_small_excerpt_length($length){
    $epcl_theme = epcl_get_theme_options();
    $length = 17;

    if( !empty($epcl_theme) && $epcl_theme['small_excerpt_length'] ){
        $length = absint( $epcl_theme['small_excerpt_length'] );
    }
	return $length;
}

function epcl_large_excerpt_length($length){
    $epcl_theme = epcl_get_theme_options();
    $length = 20;

    if( !empty($epcl_theme) && $epcl_theme['large_excerpt_length'] ){
        $length = absint( $epcl_theme['large_excerpt_length'] );
    }
	return $length;
}

// Custom title length Grid Posts

function epcl_grid_title_length( $title, $id ){
    $epcl_theme = epcl_get_theme_options();
    $length = '';
    if( !empty($epcl_theme) && isset($epcl_theme['grid_title_length']) && $epcl_theme['grid_title_length'] != '' && get_post_type($id) == 'post' ){
        $length = absint( $epcl_theme['grid_title_length'] );
        if( mb_strlen($title) > $length){
            return mb_substr( $title, 0, $length ).'...';
        }        
    }    
    return $title;    
}

// Custom title length Classic Posts

function epcl_classic_title_length( $title, $id ){
    $epcl_theme = epcl_get_theme_options();
    $length = '';
    if( !empty($epcl_theme) && isset($epcl_theme['classic_title_length']) && $epcl_theme['classic_title_length'] != '' && get_post_type($id) == 'post' ){
        $length = absint( $epcl_theme['classic_title_length'] );
        if( mb_strlen($title) > $length){
            return mb_substr( $title, 0, $length ).'...';
        }    
    }
    return $title;
}

/* Custom Pagination */

function epcl_pagination($query = NULL){
	global $wp_query, $paged;
    if($query) $wp_query = $query;
    if( !empty($wp_query->query['paged']) ){
        $paged = $wp_query->query['paged'];
    }
    $current_escaped = max(1, get_query_var('paged') );
    $total_escaped = intval($wp_query->max_num_pages);
?>
    <div class="separator last hide-on-tablet hide-on-mobile"></div>
    <div class="clear"></div>
    <!-- start: .epcl-pagination -->
    <div class="epcl-pagination section np-bottom">
        <div class="nav">
            <?php echo get_previous_posts_link( esc_html__('Previous', 'zento') ); ?>
            <span class="page-number">
                <?php echo sprintf(esc_html__('Page %d of %d', 'zento'), $current_escaped, $total_escaped); ?>
            </span>
            <?php echo get_next_posts_link( esc_html__('Next', 'zento') ); ?>
        </div>
    </div>
    <!-- end: .epcl-pagination -->
<?php
}

add_filter('next_posts_link_attributes', 'epcl_next_posts_link_attributes' );
add_filter('previous_posts_link_attributes', 'epcl_prev_posts_link_attributes' );

function epcl_next_posts_link_attributes() {
    return 'class="epcl-button" data-title="'.esc_attr__('Next', 'zento').'"';
}

function epcl_prev_posts_link_attributes() {
    return 'class="epcl-button" data-title="'.esc_attr__('Previous', 'zento').'"';
}

add_filter( 'image_size_names_choose', 'epcl_media_settings_custom_sizes' );

function epcl_media_settings_custom_sizes( $sizes ) {
	return array_merge( $sizes, array(
		'medium_large' => esc_attr_x('(EP) Medium (768x768px)', 'admin', 'zento'),
	) );
}

add_filter('wp_list_categories', 'epcl_at_count_span');
add_filter('get_archives_link', 'epcl_archives_count');

function epcl_at_count_span($links) {
    $links = str_replace('</a> (', '</a> <span class="count">', $links);
    $links = str_replace(')', '</span>', $links);
    return $links;
}

function epcl_archives_count($links){
    $links = str_replace('</a>&nbsp;(', '</a> <span class="count">', $links);
    $links = str_replace(')</li>', '</span></li>', $links);
    return $links;
}

// Add search button to the end of the main menu
function epcl_search_nav_item($items, $args) {
    if ($args->theme_location == 'epcl_header') {
        $url = '#search-lightbox';
        if( epcl_is_amp() ){
            $url = home_url('/').'?s=';
        }
        return $items .= '<li class="search-menu-item hide-on-mobile hide-on-tablet"><a href="'.esc_url($url).'" class="link mfp-inline" aria-label="'.esc_attr__('Search', 'zento').'"><svg class="icon"><use xlink:href="'.EPCL_THEMEPATH.'/assets/images/svg-icons.svg#search-icon"></use></svg></a></li>';
    }
    return $items;
}

// Add subscribe button to the header
function epcl_get_subscribe_button( $class = '') {
    $subscribe_title = esc_html__("Subscribe", 'zento');
    if( epcl_get_option('title_subscribe_button') !== '' ){
        $subscribe_title = epcl_get_option('title_subscribe_button');
    }

    $subscribe_url = epcl_get_option('subscribe_url');
    if( epcl_get_option('subscribe_url_header') ){
        $subscribe_url = epcl_get_option('subscribe_url_header');
    }     
    
    $html = '
    <a href="'.esc_url($subscribe_url).'" class="epcl-button subscribe-button '.esc_attr($class).'" target="_blank">
        '.$subscribe_title.'
    </a>';

    return $html;
}

function epcl_posts_lists_args( $epcl_module ){
    if( empty($epcl_module) ) return;

    // Categories filters
    if( isset($epcl_module['featured_categories']) && $epcl_module['featured_categories'] != '' ){
        $args['cat'] = $epcl_module['featured_categories'];
    }
    if( isset($epcl_module['excluded_categories']) && $epcl_module['excluded_categories'] != '' ){
        $args['category__not_in'] = $epcl_module['excluded_categories'];
    }

    // Posts per page
    if( isset($epcl_module['posts_per_page']) && $epcl_module['posts_per_page'] != '' ){
        $args['posts_per_page'] = $epcl_module['posts_per_page'];
    }

    // Order by: Date, Views, Name
    if( isset($epcl_module['orderby']) && $epcl_module['orderby'] != '' ){
        $args['orderby'] = $epcl_module['orderby'];
        if( $epcl_module['orderby'] == 'views' ){
            $args['orderby'] = 'meta_value_num';
            $args['meta_key'] = 'views_counter';
        }
    }

    // Posts order: ASC, DESC
    if( isset($epcl_module['posts_order']) && $epcl_module['posts_order'] != '' ){
        $args['order'] = $epcl_module['posts_order'];
    }

    // Filter by date (year, month, etc)
    if( isset($epcl_module['date']) && $epcl_module['date'] != 'alltime' ){
        $year = date('Y');
        $month = absint( date('m') );
        $week = absint( date('W') );
    
        $args['year'] = $year;
    
        if( $epcl_module['date'] == 'pastmonth' ){
            $args['monthnum'] = $month - 1;
        }
        if( $epcl_module['date'] == 'pastweek' ){
            $args['w'] = $week - 1;
        }
        if( $epcl_module['date'] == 'pastyear' ){
            unset( $args['year'] );
            $today = getdate();
            $args['date_query'] = array(
                array(
                    'after' => $today[ 'month' ] . ' 1st, ' . ($today[ 'year' ] - 2)
                )
            );
        }
    }

    // Add offset only for the first pagination
    if( isset($epcl_module['offset']) && $epcl_module['offset'] != '0'){
        $var = is_front_page() ? 'page' : 'paged';
        $paged = ( get_query_var($var) ) ? get_query_var($var) : 1;

        if( $paged == 1){
            $args['offset'] = intval($epcl_module['offset']);
        }else{
            $posts_per_page = get_option('posts_per_page');
            if( isset($epcl_module['posts_per_page']) && $epcl_module['posts_per_page'] != '' ){
                $posts_per_page = $epcl_module['posts_per_page'];
            }
            $args['offset'] = intval($epcl_module['offset']) + ( ($paged-1) * $posts_per_page );
        }

    }
    
    return $args;
}

// Fix to calculate the real amount of max pages when offset option is active
function epcl_calculate_offset_pages( $custom_query, $epcl_module ){

    if( !empty($epcl_module) && isset($epcl_module['offset']) && $epcl_module['offset'] != '0'){
        $custom_query->found_posts = $custom_query->found_posts  - intval($epcl_module['offset']);
        $total_pages = ceil( intval($custom_query->found_posts) / intval($custom_query->query_vars['posts_per_page']) );    
        $custom_query->max_num_pages = $total_pages;
    }

    return $custom_query;
}

// Render categories with colors

function epcl_render_categories( $cat_limit = '', $class = '', $post_meta = ''){
    $categories = get_the_category();

    if( empty($categories) ) return;

    if( function_exists('epcl_get_option') && epcl_get_option('enable_single_category') === '0' && is_single() ){
        return;
    }

    $html = '';
    $i = 0;
    $limit = 2;
    if( epcl_get_option('category_limit') ){
        $limit = absint( epcl_get_option('category_limit') );
    }
    if( $cat_limit != '' ){
        $limit = absint($cat_limit);
    }
    $primary_class = '';
    if( count($categories) > 0){
        $primary_class = epcl_get_primary_category( '', $post_meta, get_the_ID() );
    }
   
    foreach($categories as $c){
        if( $i == $limit ) break;
        if( $primary_class == 'primary-cat-'.$c->term_id )
            $html .= '<a href="'.get_category_link($c).'" class="ctag tag-link-'.$c->term_id.' primary-cat">'.$c->name.'</a><span class="sep">, </span>';
        else
            $html .= '<a href="'.get_category_link($c).'" class="ctag tag-link-'.$c->term_id.'">'.$c->name.'</a><span class="sep">, </span>';
        $i++;
    }

    return $html;
}

// Return primary category class or the fist category of the post
function epcl_get_primary_category( $post_class = '', $post_meta = '', $post_id = '' ){

    if( isset($post_meta['primary_category']) && $post_meta['primary_category'] != ''){
        $c = get_category_by_slug( $post_meta['primary_category'] );
    }    
    if( !empty($c) ){
        $post_class .= 'primary-cat-'.$c->term_id;
    }else{
        $cat = get_the_category( $post_id );
        if( !empty($cat) ){
            $post_class .= 'primary-cat-'.$cat[0]->term_id;
        }        
    }
    
    return $post_class;    
}

// Return primary ID
function epcl_get_primary_category_id( $post_id = '' ){

    $post_meta = get_post_meta( $post_id, 'epcl_post', true );

    if( isset($post_meta['primary_category']) && $post_meta['primary_category'] != ''){
        $c = get_category_by_slug( $post_meta['primary_category'] );
    }
    if( !empty($c) ){
        $post_id = $c->term_id;
    }else{
        $cat = get_the_category( $post_id );
        if( !empty($cat) ){
            $post_id = $cat[0]->term_id;
        }        
    }
    
    return $post_id;    
}

// Return primary category as link button
function epcl_get_primary_category_link( $post_meta, $post_id ){
    if( isset($post_meta['primary_category']) && $post_meta['primary_category'] != ''){
        $c = get_category_by_slug( $post_meta['primary_category'] );
    }
    if( !empty($c) ){
        $html = '<a href="'.get_category_link($c).'" class="primary-tag tag-link-'.$c->term_id.'">'.$c->name.'</a>';
    }else{
        $cat = get_the_category( $post_id );
        $html = '<a href="'.get_category_link($cat[0]).'" class="primary-tag tag-link-'.$cat[0]->term_id.'">'.$cat[0]->name.'</a>';
    }    
    return $html;    
}

// Calculate Reading Time

function epcl_reading_time( $content ) {
    if( !$content ) return;

    // Predefined words-per-minute rate.
    $words_per_minute = 225;
    $words_per_second = $words_per_minute / 60;
    $minutes = 1;

    if( epcl_get_option('words_per_minute', 225) ){
        $words_per_minute = epcl_get_option('words_per_minute', 225);
    }

    $words = str_word_count( strip_tags( $content ), 0, 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ' );
    if ( !empty( $words ) ) {
        $minutes = floor( $words / $words_per_minute );
    }
    if( $minutes < 1){
        $minutes = 1;
    }
    
    return $minutes;
}

function epcl_disable_featured_image( $html ) {
    if( is_single() && !epcl_get_option('enable_featured_image', true) ) {
        return '';
    } else {
        return $html;
    }
}
add_filter( 'has_post_thumbnail', 'epcl_disable_featured_image', 10, 1 ); 

function epcl_get_author_avatar( $user_meta, $user_id, $size = 120 ){
    $author_avatar = '';
    if( $user_id == 0 ){
        return $author_avatar;
    }
    if( !empty($user_meta) && !empty( $user_meta['avatar']) && $user_meta['avatar']['url'] != '' ){
        $author_avatar = $user_meta['avatar']['url'];
    }else{        
        $author_avatar = get_avatar_url( get_the_author_meta('email', $user_id), array( 'size' => $size ));
    }

    return $author_avatar;
}

// Custom avatar for comments

function epcl_custom_avatar($avatar, $id_or_email, $size) {
    if( is_admin() ) return $avatar;
    
    $user_id = 0;

    if (is_object($id_or_email) && isset($id_or_email->user_id)) {
        $user_id = $id_or_email->user_id;
    } else {
        $user_id = $id_or_email;
    }

    $custom_avatar_url = get_user_meta($user_id, 'custom_avatar_url', true);
    $user_meta = get_user_meta( $user_id, 'epcl_user', true );
    $custom_avatar_url = epcl_get_author_avatar($user_meta, $user_id);

    if (!$custom_avatar_url) {
        return $avatar;
    }

    $avatar = '<img src="'.epcl_placeholder().'" data-src="'.$custom_avatar_url.'" width="50" height="50" class="avatar lazy cover" alt="'.esc_attr__('Avatar', 'zento').'">'; 

    return $avatar;
}

add_filter('get_avatar', 'epcl_custom_avatar', 10, 3);

// Display the featured image caption with basic HTML (a, br, em, strong, p), if available

function epcl_render_image_caption($post_id){
    if( !$post_id ){
        $post_id = get_the_ID();
    }
    $image_id = get_post_thumbnail_id( $post_id );
    $image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true);
    $image_caption = wp_get_attachment_caption( $image_id );
    $allowed_html = array(
        'a' => array(
            'href' => array(),
            'title' => array(),
            'target' => array()
        ),
        'br' => array(),
        'em' => array(),
        'strong' => array(),
        'b' => array(),
        'p' => array(),
    );
    $html = '';
    if( $image_caption !== '' ){
        $html = '<p class="featured-image-caption textcenter border-effect">'.wp_kses($image_caption, $allowed_html).'</p>'; 
    }
    return $html;
}

/* Ajax for views counter (this will help to show the accurate amount when using heavy cache systems) */

function epcl_views_counter() {

    // Check for nonce security for logged in users
    $nonce = sanitize_text_field( $_POST['nonce'] );

    $post_id = sanitize_text_field( $_POST['post_id'] );    

    if( defined('EPCL_PLUGIN_PATH') ){
        $post_meta = get_post_meta( $post_id, 'epcl_post', true );
        if( !is_array($post_meta) ){
            $post_meta = array();
        }
        if( !isset( $post_meta['views_counter']) ){
            $post_meta['views_counter'] = 0;
        }else{
            $views = $post_meta['views_counter'];
        }        
        if(!$views) $views = 0;
        $post_meta['views_counter'] = ++$views;
        update_post_meta($post_id, 'epcl_post', $post_meta);
        // Views fix
        update_post_meta($post_id, 'views_counter', $views);

        die('success');
    }

    die('error');
}
add_action('wp_ajax_nopriv_epcl_views_counter', 'epcl_views_counter');
add_action('wp_ajax_epcl_views_counter', 'epcl_views_counter');

function get_kses_svg_ruleset() {
    $kses_defaults = wp_kses_allowed_html( 'post' );

    $svg_args = array(
        'svg'   => array(
            'class'           => true,
            'aria-hidden'     => true,
            'aria-labelledby' => true,
            'role'            => true,
            'xmlns'           => true,
            'width'           => true,
            'height'          => true,
            'viewbox'         => true, // <= Must be lower case!
        ),
        'g'     => array( 'fill' => true ),
        'title' => array( 'title' => true ),
        'path'  => array(
            'd'    => true,
            'fill' => true,
        ),
    );
    return array_merge( $kses_defaults, $svg_args );
}

function epcl_svg_icons() {
    if( epcl_is_amp() ){
        echo '<img src="'.EPCL_THEMEPATH.'/assets/images/svg-icons.svg" alt="Social Icons" style="display:none;">';
    }else{
        echo '<img loading="eager" fetchpriority="high" src="'.EPCL_THEMEPATH.'/assets/images/svg-icons.svg" alt="Social Icons" style="display:none;">';
    }    
}

add_action('wp_body_open', 'epcl_svg_icons');

function epcl_placeholder(){
    return EPCL_THEMEPATH.'/assets/images/transparent.gif';
}