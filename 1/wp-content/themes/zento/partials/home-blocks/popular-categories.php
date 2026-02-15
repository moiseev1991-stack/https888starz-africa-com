<?php
$epcl_module = epcl_get_module_options();
if( empty($epcl_module) ) return;
$args = array(
	'taxonomy' => 'category',
    'orderby' => 'count',
    'order' => 'DESC',
    'number' => '6'
);

if( !empty($epcl_module) ){
    // Categories filters
    if( isset($epcl_module['featured_categories']) && $epcl_module['featured_categories'] != '' ){
        $args['term_taxonomy_id'] = $epcl_module['featured_categories'];
    }
    if( isset($epcl_module['excluded_categories']) && $epcl_module['excluded_categories'] != '' ){
        $args['exclude'] = $epcl_module['excluded_categories'];
    }
}
$popular_categories = get_terms($args);
?>

<div class="section section np-bottom">
    <!-- start: .epcl-popular-categories -->
    <section class="epcl-popular-categories" id="<?php echo wp_unique_id('epcl-popular-categories-'); ?>">
        <div class="grid-container grid-medium np-mobile">
            <?php if( isset($epcl_module['module_title']) && $epcl_module['module_title'] != '' ): ?>
                <h2 class="title bordered medium textcenter"><svg class="icon large secondary-color"><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#trending-icon"></use></svg> <?php echo esc_html( $epcl_module['module_title'] ); ?></h2>
            <?php endif; ?>
            <div class="epcl-flex bg-box section">
                <div class="left epcl-flex grid-60 np-mobile">
                <?php foreach($popular_categories as $term): ?>
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
                        <div class="item grid-20 mobile-grid-33 overlay-effect">  
                            <div class="image-container ctag-<?php echo esc_html($term->term_id); ?>">
                                <?php if( !empty($thumb_url) ): ?>  
                                    <span class="category-image ctag ctag-<?php echo esc_html($term->term_id); ?>"><img fetchpriority="low" decoding="async" loading="lazy" src="<?php echo esc_url($thumb_url[0]); ?>" alt="<?php echo esc_html($term->name); ?>" class="cover" /></span>           
                                <?php else: ?>
                                    <span class="category-image ctag ctag-<?php echo esc_html($term->term_id); ?>"><svg class="icon"><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#fill-tag-icon"></use></svg></span>        
                                <?php endif; ?>
                                <span class="epcl-decoration-counter"><?php echo esc_html($term->count); ?></span>    
                                <span class="overlay"></span>                                      
                            </div> 
                            <h3 class="title usmall"><?php echo esc_html($term->name); ?></h3>    
                            <a href="<?php echo get_term_link($term); ?>" class="full-link"><span class="screen-reader-text">{{name}}</span></a> 
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if( $epcl_module['popular_categories_button_url'] ): ?>
                    <div class="right grid-40 hide-on-mobile hide-on-tablet">
                        <?php if( $epcl_module['popular_categories_or_text'] ): ?>
                            <span class="fw-bold"><?php echo esc_html($epcl_module['popular_categories_or_text']); ?></span>
                        <?php endif; ?>
                        <a href="<?php echo esc_url( $epcl_module['popular_categories_button_url'] ); ?>" class="epcl-button"><?php echo esc_html( $epcl_module['popular_categories_button_name'] ); ?></a>
                    </div>
                <?php endif; ?>
                <div class="clear"></div>
            </div>
            
        </div>
    </section>
    <!-- end: .epcl-popular-categories -->
</div>