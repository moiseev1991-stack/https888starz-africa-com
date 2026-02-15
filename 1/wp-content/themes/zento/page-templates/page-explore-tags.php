<?php /* Template Name: Explore our Categories */ ?>

<?php get_header(); ?>

<?php
$post_meta = get_post_meta( get_the_ID(), 'epcl_page_fullwidth', true );
$args = array(
	'taxonomy' => 'category',
    'orderby' => 'count',
    'order' => 'DESC',
);
$categories = get_terms($args);
?>
<!-- start: #tags-archive -->
<main id="tags-archive" class="main grid-container">
	<?php if(have_posts()): the_post(); ?>

        <!-- start: .intro-text -->
        <div class="intro-text text textcenter section np-bottom grid-container grid-medium np-mobile">
            <h1 class="title ularge"><?php the_title(); ?></h1>
            <div class="text">
                <?php the_content(); ?>
            </div>
        </div>
        <!-- end: .intro-text -->

        <?php if( !empty($categories) ): ?>
            <!-- start: .epcl-popular-categories -->
            <section class="epcl-tags-archive medium-section">
                <div class="epcl-flex bg-box section">
                    <div class="left epcl-flex grid-60 np-mobile">
                           <?php foreach($categories as $term): ?>   
                            <?php
                                $term_meta = '';
                                $thumb_url = ''; 
                                if( defined('EPCL_PLUGIN_PATH') && !empty($term) ){
                                    $term_meta = get_term_meta( $term->term_id, 'epcl_post_categories', true );  
                                    
                                    if( !empty($term_meta) && !empty($term_meta['archives_image']) ){
                                        $thumb_url = wp_get_attachment_image_src($term_meta['archives_image']['id']);
                                    }             
                                }
                            ?>         
                            <div class="category-info item overlay-effect grid-25 mobile-grid-33 tablet-grid-33">
                                <div class="image-container ctag-<?php echo esc_html($term->term_id); ?>">
                                    <?php if( !empty($thumb_url) ): ?>  
                                        <span class="category-image ctag ctag-<?php echo esc_html($term->term_id); ?>"><img fetchpriority="low" decoding="async" loading="lazy" src="<?php echo esc_url($thumb_url[0]); ?>" alt="<?php echo esc_html($term->name); ?>" class="cover" /></span>           
                                    <?php else: ?>
                                        <span class="category-image ctag ctag-<?php echo esc_html($term->term_id); ?>"><svg class="icon"><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#fill-tag-icon"></use></svg></span>        
                                    <?php endif; ?>
                                </div>
                                <h3 class="title usmall fw-medium"><span class="title small fw-bold"><?php echo esc_html($term->name); ?></span><?php esc_html( printf( _n( '1 Article', '%1$s Articles', $term->count, 'zento'), number_format_i18n( $term->count ) ) ); ?></h3>    
                                <a href="<?php echo get_term_link($term); ?>" class="full-link"><span class="screen-reader-text"><?php echo esc_html($term->name); ?></span></a> 
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="clear"></div>    
            </section>
            <!-- end: .epcl-popular-categories -->
        <?php endif; ?>
    <?php endif; ?>
</main>
<!-- end: #page -->
<?php get_footer(); ?>
