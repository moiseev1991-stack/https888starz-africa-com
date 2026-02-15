<header>

    <?php get_template_part('partials/single/single-post-info', false, array('title_class' => 'ularge') ); ?>

    <?php echo epcl_display_post_format( get_post_format(), get_the_ID(), true ); ?>

	<div class="clear"></div>

</header>