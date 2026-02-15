<?php get_header(); ?>

<?php while(have_posts()): the_post();  ?>
    <?php
	$post_id = get_the_ID();
	$post_format = get_post_format();

    // Defaults - Post Style: classic, Sidebar: enabled
	$post_style = 'classic';
	$single_class = '';
    $enable_sidebar = true;
    $single_class = '';
    $post_meta = get_post_meta( get_the_ID(), 'epcl_post', true );
    $views = 0;

	if( !empty($post_meta) && isset($post_meta['style']) && defined('EPCL_PLUGIN_PATH') ){

		$post_style = $post_meta['style'];
		if( $post_style === '' ) $post_style = 'vertical';

		$enable_sidebar = $post_meta['enable_sidebar'];
		if( !$enable_sidebar ){
            $enable_sidebar = false;
            $single_class = ' no-sidebar';
        }

	}

    if( !is_active_sidebar('epcl_sidebar_default') ){
        $enable_sidebar = false;
        $single_class .= ' no-sidebar';
    }
    if( !$post_style ){
        $post_style = 'vertical';
    }
    
    if( !empty($epcl_theme) && $epcl_theme['single_post_layout'] === 'classic' ){
        $post_style = 'classic';
    }
    if( !empty($epcl_theme) && $epcl_theme['single_post_layout'] === 'fullcover' ){
        $post_style = 'fullcover';
    }
    if( !empty($epcl_theme) && $epcl_theme['single_post_layout'] === 'vertical' ){
        $post_style = 'vertical';
    }
    if( !empty($epcl_theme) && $epcl_theme['enable_post_sidebar'] === 'enabled'){
        $enable_sidebar = true;
        $single_class = '';
    }
    if( !empty($epcl_theme) && $epcl_theme['enable_post_sidebar'] === 'disabled'){
        $enable_sidebar = false;
        $single_class .= ' no-sidebar';
    }
    
    // Disable featured image globally
    if( !empty($epcl_theme) && isset($epcl_theme['enable_featured_image']) && $epcl_theme['enable_featured_image'] == '0'){
        $post_style = 'classic';
    }

    // Primary category (optional)
    $post_class = epcl_get_primary_category( '', $post_meta, $post_id );

    // Only allows Fullcover or Classic style when using Gallery Format
    if( ( $post_format == 'gallery' || $post_format == 'video' || $post_format == 'audio' ) && $post_style !== 'fullcover' && $post_style !== 'classic' ){
        $post_style = 'classic';
    }

    if( $post_style == 'vertical' && has_post_thumbnail() ){
        $post_style_class = 'classic epcl-vertical';
    } elseif( $post_style == 'fullcover' ) {
        $post_style_class = 'fullcover';
    } else {
        $post_style_class = 'classic';
    }

    if( $post_style == 'fullcover' && !has_post_thumbnail() ){
        $post_style = 'classic';
        $post_style_class = 'classic';
    }
    
	?>
	<!-- start: #single -->
    <main id="single" class="main grid-container <?php echo esc_attr($post_style_class.$single_class); ?>" data-post-id="<?php the_ID(); ?>">

        <!-- Fullcover Style -->
        <?php if( $post_style == 'fullcover' ): ?>
            <?php get_template_part('partials/single/style-fullcover'); ?>
        <?php endif; ?>

		<!-- start: .center -->
	    <div class="content">

            <!-- start: .epcl-page-wrapper -->
            <div class="epcl-page-wrapper">

                <!-- start: .content -->
                <div class="left-content grid-70 np-mobile">

                    <?php if( $post_style !== 'fullcover' && epcl_get_option('enable_breadcrumbs') == '1' && function_exists('epcl_render_breadcrumbs') ): ?>
                        <div class="epcl-breadcrumbs">
                            <?php epcl_render_breadcrumbs(); ?>
                        </div>
                    <?php endif; ?>

                    <article <?php post_class('main-article bg-box section '.$post_class); ?>>

                        <?php if( $post_style == 'classic' ): ?>
                            <?php get_template_part('partials/single/style-classic'); ?>
                        <?php elseif( $post_style == 'vertical'): ?>
                            <?php get_template_part('partials/single/style-vertical'); ?>
                        <?php endif; ?>  
                        
                        <?php if( $post_style == 'fullcover' ): ?>
                            <?php get_template_part('partials/single/single-post-info', false, array('title_class' => 'ularge') ); ?>
                        <?php endif; ?>
    
                        <section class="post-content">

                            <?php
                            if( function_exists( 'epcl_render_global_ads' ) ){
                                epcl_render_global_ads('single_top');
                            }
                            ?>

                            <div class="text">
                                <?php if( epcl_get_option('enable_single_toc', false) ): ?>
                                    <div class="epcl-single-toc <?php if( epcl_get_option('single_toc_state', 'closed') == 'open') echo 'active open'; ?>" data-heading-selector="<?php echo esc_attr( epcl_get_option('toc_heading_selector', "h1, h2, h3, h4, h5") ); ?>">
                                        <div class="toggle-title"><?php echo esc_html__('Table of Contents', 'zento'); ?><svg class="epcl-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M11.9997 13.1714L16.9495 8.22168L18.3637 9.63589L11.9997 15.9999L5.63574 9.63589L7.04996 8.22168L11.9997 13.1714Z"></path></svg></div>
                                        <div class="toggle-content epcl-toc" <?php if( epcl_get_option('single_toc_state', 'closed') == 'open') echo 'style="display:block;"'; ?>></div>
                                    </div>
                                <?php endif; ?>
                                <?php the_content(); ?>
                                <?php
                                    if ( is_singular( 'attachment' ) ) {
                                        echo '<h2 class="title usmall">'.esc_html__('Published in:', 'zento').'</h2>';
                                        // Parent post navigation.
                                        the_post_navigation();
                                        echo '<br>';
                                    }
                                ?>
                            </div>
                            <div class="clear"></div>   

                            <?php if( epcl_get_option('enable_single_category', true) ||  ( epcl_get_option('enable_single_tags', true) && get_the_tags() ) ): ?>
                                <div class="bottom-tags tags-list textcenter">
                                    <?php if( epcl_get_option('enable_single_category', true) && get_the_category()  ): ?>
                                        <div <?php if( epcl_get_option('enable_single_tags', true) && get_the_tags() ) echo 'class="grid-50 tablet-grid-50"'; ?>>
                                            <p class="title usmall"><?php esc_html_e('Categorized in:', 'zento'); ?></p>
                                            <?php echo epcl_render_categories(99); ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if( epcl_get_option('enable_single_tags', true) && get_the_tags() ): ?>
                                        <div class="tagged-in grid-50 tablet-grid-50">
                                            <p class="title usmall"><?php esc_html_e('Tagged in:', 'zento'); ?></p>                                           
                                            <?php the_tags('', ', ', ''); ?>                              
                                        </div>    
                                    <?php endif; ?>    
                                    <div class="clear"></div>                              
                                </div>                                                     
                            <?php endif; ?>

                            <?php
                                $link_pages_args = array(
                                    'before'           => '<div class="epcl-pagination link-pages section"><div class="nav"><span class="page-number title">'.esc_html__('Pages', 'zento').'</span>',
                                    'after'            => '</div></div>',
                                    'link_before'      => '',
                                    'link_after'       => '',
                                    'next_or_number'   => 'number',
                                    'separator'        => '',
                                    'nextpagelink'     => esc_html__('Next', 'zento'),
                                    'previouspagelink' => esc_html__('Previous', 'zento'),
                                    'pagelink'         => '<span class="page-number">%</span>',
                                    'echo'             => 1
                                );
                                wp_link_pages( $link_pages_args );
                            ?>                                                          

                            <?php
                            if( function_exists( 'epcl_render_global_ads' ) ){
                                epcl_render_global_ads('single_bottom');
                            }
                            ?>    
                            
                            <?php if( function_exists('epcl_render_copy_permalink') && epcl_get_option('enable_share_buttons', true)  ): ?>
                                <?php epcl_render_copy_permalink(); ?>
                            <?php endif; ?>     

                        </section>                       
                        
                    </article>

                    <div class="clear"></div>

                    <?php if( epcl_get_option('siblings_posts', true) ): ?>
                        <?php get_template_part('partials/single/siblings-articles'); ?>
                    <?php endif; ?>

                    <?php if( epcl_get_option('single_enable_subscribe') ): ?>
                        <?php get_template_part('partials/subscribe-form'); ?>
                    <?php endif; ?>

                    <?php if( epcl_get_option('related_posts', true) ): ?>
                        <?php get_template_part('partials/single/related-articles'); ?>
                        <div class="clear"></div> 
                    <?php endif; ?>    
                    
                    <?php get_template_part('partials/single/comments-area'); ?>

                    <div class="clear"></div>

                </div>
                <!-- end: .content -->

                <?php
                if( $enable_sidebar ){
                    get_sidebar();
                }
                ?>

                <div class="clear"></div>

            </div>
            <!-- end: .center -->
        
        </div>
        <!-- end: .epcl-page-wrapper -->

	</main>
	<!-- end: #single -->

<?php endwhile; ?>

<?php get_footer(); ?>
