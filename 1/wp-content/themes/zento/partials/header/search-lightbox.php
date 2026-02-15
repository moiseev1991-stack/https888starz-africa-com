<?php if( epcl_get_option('enable_search_header') == '1' || empty($epcl_theme) ): ?>
    <?php $total_posts = wp_count_posts(); ?>
    <div class="hide-on-mobile hide-on-tablet hide-on-desktop">
        <div id="search-lightbox" class="mfp-hide grid-container grid-usmall grid-parent">
            <h4 class="title textcenter white fw-bold hide-on-mobile hide-on-tablet"><?php printf( esc_html__('Press %s to close', 'zento'), '<span>ESC</span>' ); ?></h4>
            <div class="search-wrapper">                
                <?php get_search_form(); ?>
            </div>            
        </div>
    </div>
<?php endif; ?>