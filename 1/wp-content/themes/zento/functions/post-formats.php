<?php
function epcl_display_post_format($format = '', $post_id = '', $single_post = false){

	if( !$format ) // If not custom metaboxes, always uses format image
		$format = 'image';

	$prefix = EPCL_THEMEPREFIX.'_';

	switch($format){

        default: // Standard and Image post format
		case 'image':
			return epcl_get_image_format($post_id, $single_post );
        break;
        
		case 'video':
            return epcl_get_video_format($post_id);
        break;
        
		case 'gallery':
            return epcl_get_gallery_format($post_id);
        break;
        
		case 'audio':
            return epcl_get_audio_format($post_id);
        break;
        
	}
}

function epcl_get_image_format_single_post($post_id){

    $post_meta = get_post_meta( $post_id, 'epcl_post', true );
    $post_style = 'classic';
    
    if( !empty($post_meta) && isset($post_meta['style']) ){
        $post_style = $post_meta['style'];
    }    
    $single_post_layout = epcl_get_option('single_post_layout');
    if( $single_post_layout !== 'inherit' ){
        $post_style = $single_post_layout;
    }
    
    $single_size = 'large';
    if( is_single() && $post_style == 'fullcover' ){
        $single_size = 'epcl_fullcover';
    }

    $image_id = get_post_thumbnail_id( get_the_ID() );
    $image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true);

    // On mobile always force Classic image size (even on fullcover style)
    if( wp_is_mobile() ){
        $single_size = 'medium_large';
    }    

    if( !$image_alt ){
        $image_alt = get_the_title();
    }
    if( !has_post_thumbnail() ) return;
?>
    <!-- start: .post-format-image -->
	<div class="post-format-image post-format-wrapper">
        <?php if( has_post_thumbnail() ): ?>
            <div class="featured-image">
                <div class="epcl-loader">
                    <?php the_post_thumbnail( $single_size, array('class' => 'fullwidth cover', 'data-lazy' => 'false') ); ?>
                </div>
                <?php echo epcl_render_image_caption($post_id); ?>
            </div>
        <?php endif; ?>		
    </div>
    <!-- end: .post-format-image -->
<?php
}

// Get image format for Loops (Grid or Classic post lists)
function epcl_get_image_format($post_id, $single_post = false){
    if( $single_post ){
        epcl_get_image_format_single_post($post_id);
        return;
    }
    $index = get_query_var('index');
    $epcl_theme = epcl_get_theme_options();
   
    $module_style = get_query_var('epcl_module_style'); // Grid or Classic
    $epcl_module = epcl_get_module_options();
    $post_meta = get_post_meta( $post_id, 'epcl_post', true );

    $class =  $image_alt = $thumb_url = '';
    $loop_post_style = 'small-image';

    // Pendiente: mejorar estilo si no hay post_meta
    if( !empty($post_meta) && !isset($post_meta['loop_style']) ){
        $loop_post_style = 'small-image'; // text-only
        if(  $module_style == 'grid' && epcl_get_option('grid_loop_style', 'inherit') !== 'inherit' ){
            $loop_post_style = epcl_get_option('grid_loop_style', 'inherit');
        }
        if( $module_style == 'classic' && epcl_get_option('classic_loop_style', 'inherit') !== 'inherit' ){
            $loop_post_style = epcl_get_option('classic_loop_style', 'inherit');
        }
        $class = 'post-style-'.$loop_post_style;
    }
    
    // Loop
    $optimized_image = '';
    $size = 'epcl_classic';
    
    if( !empty( $epcl_module) && $epcl_module['layout'] == 'grid_posts' ){
        $size = 'epcl_classic';
    }

    // If is AMP loop, always force small-image layout for better speed results
    if( epcl_is_amp() ){
        $size = 'epcl_classic';
        if( !empty($post_meta) && isset($post_meta['loop_style']) && $post_meta['loop_style'] == 'classic-image' ){
            $post_meta['loop_style'] = 'small-image';
        }            
    }else{
        if( !empty($post_meta) && isset($post_meta['loop_style']) ){
            if(  $module_style == 'grid' && epcl_get_option('grid_loop_style', 'inherit') !== 'inherit' ){
                $post_meta['loop_style'] = epcl_get_option('grid_loop_style', 'inherit');
            }
            if( $module_style == 'classic' && epcl_get_option('classic_loop_style', 'inherit') !== 'inherit' ){
                $post_meta['loop_style'] = epcl_get_option('classic_loop_style', 'inherit');
            }
        }
    }

    // Get optimized image if is assigned one
    if( defined('EPCL_PLUGIN_PATH') && !empty($post_meta) && isset($post_meta['optimized_image']) ){
        $optimized_image = $post_meta['optimized_image'];
        if( !empty($optimized_image) && isset($optimized_image['alt']) ){
            $image_alt = $optimized_image['alt'];
        }            
    }  
    // Get loop style (small image or standard)
    if( !empty($post_meta) && isset($post_meta['loop_style']) && $post_meta['loop_style'] != '' ){
        $loop_post_style = $post_meta['loop_style'];
        if( get_post_format() == 'audio' || get_post_format() == 'video' ){
            $loop_post_style = 'classic-image';
        }
        $class .= 'post-style-'.$post_meta['loop_style'];
    } 

    // Change size if the image is larger
    if( $loop_post_style == 'classic-image' ){
        $size = 'large';
    }
    
    if( $module_style == 'grid' ){
        $size = 'medium_large';
    }

    // $size = 'epcl_classic';

    $thumb_url = get_the_post_thumbnail_url($post_id, $size);

    // Overwrite default img if there is any optimized image
    if( !empty($optimized_image) && $optimized_image['url'] ){
        $thumb_url = $optimized_image['url'];
    }

    if( !$image_alt ){
        $image_alt = get_the_title();
    }

    if( $index == 0 && isset($epcl_theme['enable_lazyload']) ){
        $epcl_theme['enable_lazyload'] = false;
    }

    // if( !$thumb_url ) return;

?>
    <!-- start: .post-format-image -->
	<div class="post-format-image post-format-wrapper <?php echo esc_attr($class); ?>">
        <?php if( $thumb_url ): ?>
        <!-- start: .featured-image -->
        <div class="featured-image">
            <?php
            $loop_class = '';
            if( !empty($epcl_theme) && $epcl_theme['enable_lazyload']  == 1 && !epcl_is_amp() ){
                $loop_class .= ' epcl-loader';
            }
            if( (!empty($epcl_theme) && $epcl_theme['enable_lazyload'] == 0) || empty($epcl_theme) ){
                $loop_class .= 'loaded';
            }
            ?>

            <!-- start: Classic module -->
            <?php if( $module_style == 'classic'): ?>

                <!-- Loop: Small/Vertical Image Style -->
                <?php if( $thumb_url && $loop_post_style == 'small-image' ): ?>
                    <a href="<?php the_permalink(); ?>" class="thumb epcl-loader opacity-effect <?php echo esc_attr($loop_class); ?>">
                        <?php if( epcl_is_amp() ): ?>
                            <amp-img class="cover" layout="fill" src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr($image_alt); ?>"></amp-img>
                        <?php else: ?>
                            <?php if( !empty($epcl_theme) && $epcl_theme['enable_lazyload'] == '1' ): ?>
                                <img class="post-image fullimage cover lazy" decoding="async" fetchpriority="low"  data-src="<?php echo esc_url($thumb_url); ?>" src="<?php echo epcl_placeholder(); ?>" alt="<?php echo esc_attr($image_alt); ?>" src="<?php echo esc_url($thumb_url); ?>" width="660" height="660">
                            <?php else: ?>
                                <img class="post-image fullimage cover" decoding="async" fetchpriority="low" alt="<?php echo esc_attr($image_alt); ?>" src="<?php echo esc_url($thumb_url); ?>" width="660" height="660">
                            <?php endif; ?>
                        <?php endif; ?>
                    </a>
                    <?php if( get_the_category() ): ?>
                        <div class="tags fill-color absolute">
                            <?php echo epcl_get_primary_category_link($post_meta, $post_id); ?>
                        </div> 
                    <?php endif; ?>
                <?php endif; ?> 
    
                <!-- Loop: Classic Image Style -->
                <?php if( $thumb_url && $loop_post_style == 'classic-image' ): ?>
                    <a href="<?php the_permalink(); ?>" class="thumb opacity-effect epcl-loader <?php echo esc_attr($loop_class); ?>">
                        <?php if( epcl_is_amp() ): ?>
                            <amp-img class="cover" layout="fill" src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr($image_alt); ?>"></amp-img>
                        <?php else: ?>
                            <?php if( !empty($epcl_theme) && $epcl_theme['enable_lazyload'] == '1' ): ?>
                                <img class="post-image lazy fullimage cover" alt="<?php echo esc_attr($image_alt); ?>" data-src="<?php echo esc_url($thumb_url); ?>" src="<?php echo EPCL_THEMEPATH; ?>/assets/images/transparent.gif" width="818" height="450">
                            <?php else: ?>
                                <img class="post-image fullimage cover" alt="<?php echo esc_attr($image_alt); ?>" src="<?php echo esc_url($thumb_url); ?>" width="818" height="450">
                            <?php endif; ?>
                        <?php endif; ?>
                    </a>   
                    <?php if( get_the_category() ): ?>
                        <div class="tags fill-color absolute">
                            <?php echo epcl_get_primary_category_link($post_meta, $post_id); ?>
                        </div> 
                    <?php endif; ?>              
                <?php endif; ?> 

            <?php endif; ?>
            <!-- end: Classic module -->

            <!-- start: Grid module -->
            <?php if( $module_style == 'grid'): ?>
                <?php if( $thumb_url ): ?>
                    <a href="<?php the_permalink(); ?>" class="thumb fullwidth epcl-loader <?php echo esc_attr($loop_class); ?>">
                        <?php if( epcl_is_amp() ): ?>
                            <amp-img class="cover" layout="fill" src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr($image_alt); ?>"></amp-img>
                        <?php else: ?>
                            <?php if( !empty($epcl_theme) && $epcl_theme['enable_lazyload'] == '1' ): ?>
                                <img class="post-image fullimage lazy cover" alt="<?php echo esc_attr($image_alt); ?>" data-src="<?php echo esc_url($thumb_url); ?>" src="<?php echo EPCL_THEMEPATH; ?>/assets/images/transparent.gif" width="100%" height="380">
                            <?php else: ?>
                                <img class="post-image fullimage cover" alt="<?php echo esc_attr($image_alt); ?>" src="<?php echo esc_url($thumb_url); ?>" width="100%" height="380">
                            <?php endif; ?>
                        <?php endif; ?>
                    </a>
                    <?php if( get_the_category() ): ?>
                        <div class="tags fill-color absolute">
                            <?php echo epcl_get_primary_category_link($post_meta, $post_id); ?>
                        </div> 
                    <?php endif; ?>  
                <?php endif; ?>
            <?php endif; ?>
            <!-- end: Grid module -->                       

        </div>
        <!-- end: .featured-image -->

        <div class="clear"></div>
        <?php endif; ?>

    </div>
    <!-- end: .post-format-image -->
<?php
}

function epcl_get_video_format($post_id){
    $type = 'youtube';
    $height = 450;
    $url = '';

    $epcl_theme = epcl_get_theme_options();
    $post_meta = get_post_meta( $post_id, 'epcl_post_video', true );

	$width = '100%';
    $video_id = $video_url = '';
    $show_featured_image = '';
    if( !empty($post_meta) ){
        $show_featured_image = $post_meta['show_featured_image'];
        $type = $post_meta['video_type'];
        $url = $post_meta['video_url'];
    }    

    if( !is_single() && $show_featured_image ){
        return epcl_get_image_format($post_id);
    }

    $class = '';

	if ($type == 'youtube') {
        preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $url, $matches);
        if( !$url ) return;
		$video_url ='https://www.youtube.com/embed/'.$matches[0].'?rel=0&showinfo=0';
	} elseif ($type == 'vimeo') {
        $result = preg_match('/(\d+)/', $url, $matches);
        if( !$url ) return;
		if($result){
			$video_id = $matches[0];
		}else{
			$video_id = $url;
		}
		$video_url = 'https://player.vimeo.com/video/'.$video_id;
    } elseif ($type == 'custom') {
        $custom_embed = $post_meta['custom_embed'];
        if( !$custom_embed ) return;
        preg_match('/src="([^"]+)"/', $custom_embed, $match);
        $video_url = $match[1];
    }

?>
    <div class="post-format-video post-format-wrapper epcl-loader">        
        <?php if( epcl_is_amp() ): ?>
            <amp-iframe layout="responsive" width="480" height="250" sandbox="allow-scripts allow-same-origin allow-popups" title="<?php the_title(); ?>" src="<?php echo esc_url($video_url); ?>" allowfullscreen>
                <amp-img layout="fill" src="<?php echo EPCL_THEMEPATH; ?>/assets/images/transparent.gif" placeholder></amp-img>
            </amp-iframe>
        <?php else: ?>    
            <iframe title="<?php the_title(); ?>" loading="lazy" src="<?php echo esc_url($video_url); ?>" allowfullscreen height="<?php echo esc_attr($height); ?>" style="width: <?php echo esc_attr($width); ?>"></iframe>
        <?php endif; ?>
    </div>
<?php
}

function epcl_get_gallery_format($post_id){
    $post_gallery = $post_meta = array();
    if( defined('EPCL_PLUGIN_PATH') ){
        $post_meta = get_post_meta( $post_id, 'epcl_post', true );
        $post_gallery = get_post_meta( $post_id, 'epcl_post_gallery', true );
    }
    
    // If no images and is Single Post, just return nothing
    if( empty($post_gallery['gallery']) ) return;

    $gallery_images = explode(',', $post_gallery['gallery'] );
    $epcl_theme = epcl_get_theme_options();
    $module_style = get_query_var('epcl_module_style'); // Grid or Classic
    $class = $post_style = '';

    if( !empty($post_meta) ){
        $post_style = $post_meta['style'];
    }    
    
    $size = 'large';
    if( is_single() && $post_style == 'fullcover' ){
        $size = 'epcl_fullcover';
    }
    $height = 225;
    if( !wp_is_mobile() ){
        $height = 400;
    }
    
?>
    <!-- In case of loop return just the title and meta info -->
    <?php if( empty($post_gallery['gallery']) && !is_single() ): ?>
        <div class="info below-image textcenter">                
            <h1 class="main-title title underline-effect large"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
            <?php get_template_part('partials/meta-info'); ?>
        </div>
    <?php return; endif; ?>

    <div class="post-format-gallery post-format-wrapper <?php echo esc_attr($class); ?>">
        <?php if( epcl_is_amp() ): ?>
            <amp-carousel height="<?php echo absint($height); ?>" layout="fill" type="slides">
                <?php foreach($gallery_images as $id): ?>
                    <?php
                    $image_url = wp_get_attachment_image_src($id, $size);
                    $image_alt = get_post_meta($id, '_wp_attachment_image_alt', TRUE);
                    ?>
                    <amp-img class="cover" src="<?php echo esc_url( $image_url[0] ); ?>" layout="fill" alt="<?php echo esc_attr( $image_alt ); ?>">
                        <?php if( !is_single() ): ?>
                            <a href="<?php the_permalink(); ?>" class="full-link"><span class="screen-reader-text"><?php the_title(); ?></span></a>
                        <?php endif; ?>
                    </amp-img>
                <?php endforeach; ?>
                
            </amp-carousel>
        <?php else: ?>
            <div class="slick-slider" data-rtl="<?php echo is_rtl(); ?>">
                <?php foreach($gallery_images as $id): ?>
                    <?php
                    $image_url = wp_get_attachment_image_src($id, $size);
                    $image_alt = get_post_meta($id, '_wp_attachment_image_alt', TRUE);
                    ?>
                    <div class="item">
                        <?php if( !empty($epcl_theme) && $epcl_theme['enable_lazyload'] == '1' ): ?>
                            <img class="thumb fullimage cover" src="<?php echo epcl_placeholder(); ?>" data-lazy="<?php echo esc_url( $image_url[0] ); ?>" alt="<?php echo esc_attr($image_alt); ?>">
                        <?php else: ?>
                            <img class="thumb fullimage cover" src="<?php echo esc_url( $image_url[0] ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>">
                        <?php endif; ?>
                        <?php if( !is_single() ): ?>
                            <a href="<?php the_permalink(); ?>" class="full-link"><span class="screen-reader-text"><?php the_title(); ?></span></a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if( get_the_category() ): ?>
            <div class="tags fill-color absolute">
                <?php echo epcl_get_primary_category_link($post_meta, $post_id); ?>
            </div> 
        <?php endif; ?>  
    </div>
<?php
}

/* To do: self hosted audio */

function epcl_get_audio_format($post_id){
    $post_meta_audio = get_post_meta( $post_id, 'epcl_post_audio', true );
    if( empty($post_meta_audio) ) return;

    $show_featured_image = $post_meta_audio['show_featured_image'];
    $url = $post_meta_audio['soundcloud_url'];

    $type = '';
    if( isset($post_meta_audio['audio_type']) ){
        $type = $post_meta_audio['audio_type'];
    } 
    
    if( !is_single() && $show_featured_image ){
        return epcl_get_image_format($post_id);
    }

    if( !is_single() && !$show_featured_image && !$url ){
        return;
    }
    
    if( is_single() && !$url ){
        return epcl_get_image_format($post_id);
    }

    $class = '';

	$width = '100%';
	$embed_code = wp_oembed_get( $url );
	preg_match('/src="([^"]+)"/', $embed_code, $match);
	$url = $match[1];
    $url = str_replace('&', '&amp;', $url);
    $height = 225;

    if( isset($post_meta_audio['audio_height']) && $post_meta_audio['audio_height'] != '' ){
        $height = $post_meta_audio['audio_height'];
    }

    if ($type == 'custom') {
        $custom_embed = $post_meta_audio['audio_custom_embed'];
        if( !$custom_embed ) return;
        preg_match('/src="([^"]+)"/', $custom_embed, $match);
        $url = $match[1];
        $class = 'custom-audio';
    }
?>
    <div class="post-format-audio post-format-wrapper epcl-loader <?php echo esc_attr($class); ?>">
        <?php if( epcl_is_amp() ): ?>
            <amp-iframe layout="responsive" width="480" height="<?php echo absint($height); ?>" sandbox="allow-scripts allow-same-origin allow-popups" title="<?php the_title(); ?>" src="<?php echo esc_url($url); ?>" allowfullscreen>
                <amp-img layout="fill" src="<?php echo EPCL_THEMEPATH; ?>/assets/images/transparent.gif" placeholder></amp-img>
            </amp-iframe>
        <?php else: ?>    
            <iframe loading="lazy" src="<?php echo esc_url($url); ?>" allowFullScreen height="<?php echo absint($height); ?>" style="width: <?php echo esc_attr($width); ?>; height: <?php echo absint($height); ?>px;"></iframe>
        <?php endif; ?>
    </div>
<?php
}