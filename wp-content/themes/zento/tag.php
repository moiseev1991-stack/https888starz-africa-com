<?php get_header(); ?>
<?php
$obj = get_queried_object();
?>
<!-- start: #archives-->
<main id="archives" class="main">

    <div class="grid-container grid-medium">

        <div class="tag-description epcl-flex <?php if( !$image_url) echo 'no-image'; ?>">
            <div class="left epcl-flex">
                <div class="category-image ctag-<?php echo esc_attr($obj->term_id); ?>"><svg class="icon"><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#fill-tag-icon"></use></svg></div>
                <h1 class="title usmall fw-normal no-margin"><span class="title large no-margin"><?php single_cat_title(); ?></span><?php esc_html( printf( _n( 'A collection of <b>1 post</b>', 'A collection of <b>%1$s posts</b>', $obj->count, 'zento'), number_format_i18n( $obj->count ) ) ); ?></h1>
            </div>
            <?php if( term_description() ): ?>
                <div class="tag-descripcion right">
                    <?php echo term_description(); ?>
                </div>
            <?php endif; ?>
            <div class="clear"></div>
        </div>

    </div>

    <div id="post-list">
        <?php if( empty($epcl_theme) || !$epcl_theme['archive_layout'] || $epcl_theme['archive_layout'] == 'classic' ): ?>
            <?php get_template_part('partials/home-blocks/classic-posts'); ?>
        <?php elseif( $epcl_theme['archive_layout'] == 'classic_sidebar'  ): ?>
            <?php get_template_part('partials/home-blocks/classic-posts-sidebar'); ?>
        <?php elseif( $epcl_theme['archive_layout'] == 'grid_3_cols' || $epcl_theme['archive_layout'] == 'grid_4_cols' ):  ?>
            <?php get_template_part('partials/home-blocks/grid-posts'); ?>
        <?php elseif( $epcl_theme['archive_layout'] == 'grid_sidebar'  ): ?>
            <?php get_template_part('partials/home-blocks/grid-sidebar'); ?>
        <?php endif; ?>
    </div>

</main>
<!-- end: #archives -->

<?php get_footer(); ?>
