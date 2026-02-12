<?php
$epcl_module = epcl_get_module_options();
if( empty($epcl_module) ) return; // no data from carousel module
$prefix = EPCL_THEMEPREFIX.'_';
$args = array(
	'post_type' => 'post',
	'showposts' => $epcl_module['posts_carousel_limit'],
	'suppress_filters' => false,
);

if( !empty($epcl_module) ){
    // Categories filters
    if( isset($epcl_module['featured_categories']) && $epcl_module['featured_categories'] != '' ){
        $args['cat'] = $epcl_module['featured_categories'];
    }
    if( isset($epcl_module['excluded_categories']) && $epcl_module['excluded_categories'] != '' ){
        $args['category__not_in'] = $epcl_module['excluded_categories'];
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
}

$carousel = get_posts($args);
$thumbnail_size = 'epcl_classic';
?>

<?php if( !empty($carousel) ): ?>
    <section class="epcl-carousel epcl-post-carousel medium-section np-bottom" data-aos="fade" id="<?php echo wp_unique_id('epcl-post-carousel-'); ?>">
        <div class="grid-container grid-large np-tablet np-mobile">
                <?php if( isset($epcl_module['module_title']) && $epcl_module['module_title'] != '' ): ?>
                    <h2 class="title bordered medium textcenter"><svg class="icon large secondary-color"><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#trending-icon"></use></svg> <?php echo esc_html( $epcl_module['module_title'] ); ?></h2>
                <?php endif; ?>
                <!-- start: .slick-slider -->
                <div class="slick-slider" data-show="3" data-rtl="false">       
                <?php foreach($carousel as $post): setup_postdata($post); ?>
                    <?php
                        $post_id = $post->ID;
                        $post_meta = get_post_meta( $post->ID, 'epcl_post', true );
                        $primary_category_id = epcl_get_primary_category_id($post_id);
                    ?>     
                        <div class="item slick-slide">
                            <article class="default classic-large bg-box ctag ctag-<?php echo esc_attr($primary_category_id); ?>">

                                <div class="epcl-flex">
                                    <div class="meta meta-data small">
                                        <?php if( get_the_category() ): ?>
                                            <div class="tags fill-color">
                                                <?php echo epcl_get_primary_category_link($post_meta, $post_id); ?>
                                            </div> 
                                        <?php endif; ?>
                                        <?php if( isset($epcl_module['posts_carousel_enable_author']) && $epcl_module['posts_carousel_enable_author'] == true ): ?>
                                            <?php get_template_part('partials/meta-info/author'); ?>
                                        <?php endif; ?>
                                        <?php if( isset($epcl_module['posts_carousel_enable_reading_time']) && $epcl_module['posts_carousel_enable_reading_time'] == true ): ?>
                                            <?php get_template_part('partials/meta-info/reading-time'); ?>
                                        <?php endif; ?>
                                        <?php if( isset($epcl_module['posts_carousel_enable_rating']) && $epcl_module['posts_carousel_enable_rating'] == true ): ?>
                                            <?php get_template_part('partials/meta-info/rating'); ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="info"> 
                                        <header>        
                                            <h2 class="main-title title medium no-margin fw-bold underline-effect white"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                            <a href="<?php the_permalink(); ?>" class="epcl-button black"><?php echo esc_html__("Read More", 'zento'); ?></a>
                                        </header>
                                    </div>
                                </div>
                                <div class="clear"></div>

                            </article>
                        </div>                
                    <?php endforeach; wp_reset_postdata(); ?>                        
                </div>
                <!-- end: .slick-slider -->
   
            <div class="clear"></div>       
        </div>
    </section>
    
<?php endif; ?>