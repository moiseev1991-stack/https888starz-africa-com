<?php get_header(); ?>
<?php
$wrapper_class = '';
$prefix = EPCL_THEMEPREFIX.'_';
$enable_sidebar = false;
$page_class = 'no-sidebar';
$article_class = 'no-bg';
$post_meta = get_post_meta( get_the_ID(), 'epcl_page', true );
if( !empty($post_meta) && defined('EPCL_PLUGIN_PATH') ){
    $enable_sidebar = $post_meta['enable_sidebar'];
    if( $enable_sidebar ){
        $page_class = '';
    }
    if( isset($post_meta['enable_bg_box']) && $post_meta['enable_bg_box'] ){
        $article_class = 'bg-box';
    }
}
if( !is_active_sidebar('epcl_sidebar_default') ){
    $enable_sidebar = false;
    $page_class .= ' no-sidebar';
}
if( !has_post_thumbnail() ){
    $page_class .= ' no-thumb';
}
if( !empty($epcl_theme) && $epcl_theme['enable_page_sidebar'] === 'enabled'){
    $enable_sidebar = true;
    $page_class = '';
}
if( !empty($epcl_theme) && $epcl_theme['enable_page_sidebar'] === 'disabled'){
    $enable_sidebar = false;
    $page_class .= ' no-sidebar';
}
if( has_post_thumbnail() ){
    $page_class .= ' fullcover';
}
?>
<!-- start: #page -->
<main id="page" class="main grid-container">
	<?php if( have_posts() ): the_post(); ?>
		<!-- start: #single -->
        <div id="single" class="content <?php echo esc_attr($page_class); ?>">  
        
            <?php if( has_post_thumbnail() ): ?>
                <div class="fullcover-wrapper">
                    <?php if( epcl_get_option('enable_breadcrumbs') == '1' && function_exists('epcl_render_breadcrumbs') ): ?>
                        <div class="epcl-breadcrumbs">
                            <?php epcl_render_breadcrumbs(); ?>
                        </div>
                    <?php endif; ?>
                    <div class="featured-image">
                        <img src="<?php the_post_thumbnail_url('epcl_fullcover'); ?>" alt="<?php the_title(); ?>" class="fullwidth">
                    </div>
                    <div class="info grid-container grid-small">
                        <?php if( !isset($post_meta['enable_title']) || (defined('EPCL_PLUGIN_PATH') && $post_meta['enable_title'] ) || !defined('EPCL_PLUGIN_PATH') ): ?>   
                            <h1 class="main-title title ularge no-margin"><?php the_title(); ?></h1>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>                

            <!-- start: .epcl-page-wrapper -->
            <div class="epcl-page-wrapper content clearfix">

                <!-- start: .left-content -->
                <div class="left-content grid-70 np-mobile">
                    <article <?php post_class('main-article '.$article_class); ?>>

                        <section class="post-content">
                            <?php if( !empty($epcl_theme) && isset($epcl_theme['enable_sticky_share_buttons_page']) && $epcl_theme['enable_sticky_share_buttons_page'] == '1' && function_exists('epcl_render_share_buttons') ): ?>
                                <div class="epcl-share-container hide-on-mobile">
                                    <?php epcl_render_share_buttons('top'); ?>
                                </div>
                            <?php endif; ?>

                            <?php if( !has_post_thumbnail() && !isset($post_meta['enable_title']) ): ?>
                                <h1 class="title ularge textcenter bordered"><?php the_title(); ?></h1>
                            <?php elseif( !has_post_thumbnail() && defined('EPCL_PLUGIN_PATH') && $post_meta['enable_title'] ): ?>   
                                <h1 class="title ularge textcenter bordered"><?php the_title(); ?></h1>
                            <?php endif; ?>

                            <div class="text">
                                <?php the_content(); ?>
                            </div>
                            
                            <div class="clear"></div>

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
                            
                            <?php if( !empty($epcl_theme) && isset( $epcl_theme['enable_share_buttons_page'] ) && $epcl_theme['enable_share_buttons_page'] == '1' && function_exists('epcl_render_share_buttons') ): ?>
                                <?php epcl_render_copy_permalink(); ?>
                            <?php endif; ?>
                        </section>   

                    </article>

                    <?php if( ( comments_open() || get_comments_number() ) && !post_password_required() ): ?>
                        <?php get_template_part('partials/single/comments-area'); ?>
                    <?php endif; ?>
                    
                </div>
                <!-- end: .left-content -->

                <?php
                if( $enable_sidebar !== false ){
                    get_sidebar();
                }
                ?>

                <div class="clear"></div>

            </div>
            <!-- end: .epcl-page-wrapper -->

        </div>
        <!-- end: #single -->
    <?php endif; ?>
</main>
<!-- end: #page -->

<?php get_footer(); ?>
