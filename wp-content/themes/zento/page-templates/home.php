<?php /* Template Name: Home */ ?>

<?php get_header(); ?>

<?php // Note: do not add .grid-container, some modules use a larger container ?>
<!-- start: #home -->
<main id="home" class="main">

	<?php
        $post_meta = get_post_meta( get_the_ID(), 'epcl_home', true );
		if( !empty($post_meta) && isset($post_meta['modules']) && defined('EPCL_PLUGIN_PATH') ){
                foreach ( $post_meta['modules'] as $epcl_module ):
                    $file_name = str_replace( array('_', 'module-'), array( '-', '' ), esc_attr( $epcl_module['layout'] ) );
                    get_template_part( 'partials/home-blocks/'. $file_name );
				endforeach;
        }else{
            echo '<div class="title textcenter section">'.esc_html__('You must add some module before publish this page', 'zento').'</div>';
        }
	?>

</main>
<!-- end: #home -->

<?php get_footer(); ?>