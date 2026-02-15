<?php get_template_part('amp/header'); ?>
<?php
$layout = epcl_get_option( 'amp_archives_layout', 'classic-posts' );
add_filter('excerpt_length', 'epcl_small_excerpt_length', 999);
$module_class = '';
if( !is_active_sidebar('epcl_sidebar_home') ){
    $module_class .= ' no-active-sidebar';
}
if( !get_the_author_meta('description') ){
    // $module_class .= ' content';
}

$author_id = get_the_author_meta('ID');
$author_name = get_the_author();
$user_meta = get_user_meta( $author_id, 'epcl_user', true );
$author_avatar = epcl_get_author_avatar($user_meta, $author_id, 512);
$website = get_the_author_meta('user_url');
$class = '';
if($author_avatar) $class .= ' with-avatar'; else $class .= ' no-avatar';
?>

<!-- start: #archives-->
<main id="archives" class="main">

    <!-- start: .content-wrapper -->
    <div class="content-wrapper <?php echo esc_attr($module_class); ?>">

    <div class="grid-container grid-medium">
            <!-- start: .author -->
            <section id="author" class="author epcl-flex <?php echo esc_attr($class); ?>">

                <div class="left">
                    <h1 class="title author-name large">
                        <?php echo esc_html( $author_name ); ?>                           
                    </h1>
                    <div class="info">
                        <p><?php the_author_meta('description'); ?></p>      
                    </div> 
                    <?php if( !empty($user_meta) && ($user_meta['facebook'] || $user_meta['twitter'] || $website || !empty($user_meta['custom_social']) ) ): ?>
                        <div class="social epcl-social-fill-color">                        
                            <?php if($user_meta['twitter']): ?>
                                <a href="<?php echo esc_url($user_meta['twitter']); ?>" class="twitter tooltip" data-title="<?php echo esc_attr__('Follow me on Twitter', 'zento'); ?>" target="_blank">
                                    <svg class="icon"><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#twitter-icon"></use></svg><span class="name"><?php echo esc_html__('Twitter', 'zento'); ?></span>
                                </a>
                            <?php endif; ?>
                            <?php if($user_meta['facebook']): ?>
                                <a href="<?php echo esc_url($user_meta['facebook']); ?>" class="facebook tooltip" data-title="<?php echo esc_attr__('Follow me on Facebook', 'zento'); ?>" target="_blank">
                                    <svg class="icon"><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#facebook-icon"></use></svg><span class="name"><?php echo esc_html__('Facebook', 'zento'); ?></span>
                                </a>
                            <?php endif; ?>
                            <?php if($website): ?>
                                <a href="<?php echo esc_url($website); ?>" class="website tooltip" data-title="<?php echo esc_attr__('Website', 'zento'); ?>: <?php echo esc_url($website); ?>" target="_blank">
                                    <svg class="icon main-color"><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#website-icon"></use></svg><span class="name"><?php echo esc_html__('Website', 'zento'); ?></span>
                                </a>
                            <?php endif; ?>
                            <?php
                            if( !empty($user_meta['custom_social']) ){
                                foreach( $user_meta['custom_social'] as $cs ){
                                    $cs_id = 'custom-'.sanitize_title($cs['social_name']);
                                    if( !empty($cs['social_icon']['url']) ){
                                        $id = sanitize_title($cs['social_name']);
                                        $icon = '<img src="'.esc_url( $cs['social_icon']['url'] ).'" alt="'.sprintf( esc_attr__('Follow me on %s', 'zento'), $cs['social_name'] ).'" loading="lazy">';
                                        $url = $cs['social_url'];
                                        echo '<a href="'.$url.'" class="custom-social tooltip '.$cs_id.'" target="_blank" rel="nofollow noopener" data-title="'.sprintf( esc_attr__('Follow me on %s', 'zento'), $cs['social_name'] ).'"><span class="icon '.$cs_id.'">'.$icon.'</span><span class="name">'.esc_html($cs['social_name']).'</span></a>';
                                    }
                                }
                            }
                        ?>
                        </div> 
                    <?php endif; ?>
                </div>

                <?php if( $author_avatar ): ?>
                    <div class="right">
                        <span class="author-avatar">
                            <img class="author-image cover" fetchpriority="high" decoding="async" src="<?php echo esc_url($author_avatar); ?>" alt="<?php echo esc_attr( $author_name ); ?>">
                        </span>  
                    </div>
                <?php endif; ?>

            </section>
            <!-- end: .author -->
        </div>

        <div id="post-list">
            <?php get_template_part( 'partials/home-blocks/'.$layout ); ?>
        </div>

    </div>
    <!-- end: .content-wrapper -->

</main>
<!-- end: #archives -->

<?php get_template_part('amp/footer'); ?>