<?php get_template_part('amp/header'); ?>

<?php
$layout = epcl_get_option( 'amp_archives_layout', 'classic-posts' );
?>

<!-- start: #search-results -->
<main id="archives" class="main">

    <div class="content grid-container grid-container grid-usmall">
        <div class="epcl-search-box">
            <h1 class="title textcenter large"><?php esc_html_e("Search results for:", 'zento'); ?> <strong>"<?php echo get_search_query(); ?>"</strong></h1>
            <div class="textcenter">
                <?php get_search_form(); ?>
            </div>
        </div>
    </div>

    <?php get_template_part( 'partials/home-blocks/'.$layout ); ?>

    <div class="clear"></div>

</main>
<!-- end: #search-results -->

<?php get_template_part('amp/footer'); ?>