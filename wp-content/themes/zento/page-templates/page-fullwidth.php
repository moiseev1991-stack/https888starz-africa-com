<?php /* Template Name: Fullwidth (no sidebar) */ ?>

<?php get_header(); ?>

<?php
$post_meta = get_post_meta( get_the_ID(), 'epcl_page_fullwidth', true );
?>
<?php if (get_field('visibility_baner_main')) { ?>

<div class="slider-888-container" dir="ltr">
        <div class="slider-888-slider">
            <!-- Slide 1 -->
            <div class="slider-888-slide">
                <div class="slider-888-slide-content">
                    <div class="slider-888-slide-title"><?php echo starz888_get_text('slider', 'slide1', 'title'); ?></div>
                    <p class="slider-888-slide-description"><?php echo starz888_get_text('slider', 'slide1', 'description'); ?></p>
                    <a href="/game/" rel="nofollow" class="slider-888-register-btn"><?php echo starz888_get_text('slider', 'slide1', 'button'); ?></a>
                </div>
				<img src="/wp-content/uploads/2025/02/1st-slider_-1-.webp" alt="888Starz" class="img-slide-after">
            </div>
            
            <!-- Slide 2 -->
            <div class="slider-888-slide">
                <div class="slider-888-slide-content">
                    <div class="slider-888-slide-title"><?php echo starz888_get_text('slider', 'slide2', 'title'); ?></div>
                    <p class="slider-888-slide-description"><?php echo starz888_get_text('slider', 'slide2', 'description'); ?></p>
                    <a href="/game/" rel="nofollow" class="slider-888-register-btn"><?php echo starz888_get_text('slider', 'slide2', 'button'); ?></a>
                </div>
				<img src="/wp-content/uploads/2025/02/3-_Tuesday_945x370_1.png" alt="888Starz" class="img-slide-after">
            </div>
            
            <!-- Slide 3 -->
            <div class="slider-888-slide">
                <div class="slider-888-slide-content">
                    <div class="slider-888-slide-title"><?php echo starz888_get_text('slider', 'slide3', 'title'); ?></div>
                    <p class="slider-888-slide-description"><?php echo starz888_get_text('slider', 'slide3', 'description'); ?></p>
                    <a href="/game/" rel="nofollow" class="slider-888-register-btn"><?php echo starz888_get_text('slider', 'slide3', 'button'); ?></a>
					
                </div>
				<img src="/wp-content/uploads/2025/02/slot-first-deposit-slider.webp" alt="888Starz" class="img-slide-after">
            </div>
        </div>
	</div>
			<?php } ?>
<!-- start: #page -->
<main id="page" class="main grid-container">
	<?php if(have_posts()): the_post(); ?>
		<!-- start: .center -->
	    <div id="single" class="content">
			
			<div class="epcl-breadcrumbs">
                <?php echo do_shortcode( '[flexy_breadcrumb]'); ?> 
            </div>
            <?php if( has_post_thumbnail() ): ?>
                <div class="fullcover-wrapper">
                    <div class="featured-image">
                        <img loading="lazy" src="<?php the_post_thumbnail_url('epcl_fullcover'); ?>" alt="<?php the_title(); ?>" class="fullwidth">
                    </div>
                    <div class="info grid-container grid-small">
                        <?php if( !isset($post_meta['enable_title']) || (defined('EPCL_PLUGIN_PATH') && $post_meta['enable_title'] ) || !defined('EPCL_PLUGIN_PATH') ): ?>   
                            <h1 class="title ularge"><?php the_title(); ?></h1>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>      

	        <!-- start: .left-content -->
	        <div class="left-content np-mobile">
	            <article <?php post_class(); ?>>

	                <section class="post-content">
                        <?php if( !has_post_thumbnail() && !isset($post_meta['enable_title']) ): ?>
                            <h1 class="title ularge textcenter"><?php the_title(); ?></h1>
                        <?php elseif( !has_post_thumbnail() && defined('EPCL_PLUGIN_PATH') && $post_meta['enable_title'] ): ?>   
                            <h1 class="title ularge textcenter"><?php the_title(); ?></h1>
                        <?php endif; ?>

	                    <div class="text">
	                        <?php the_content(); ?>
	                    </div>
	                    <div class="clear"></div>
	                </section>

	            </article>
				<!--comment:start -->
				<?php 
                            if (is_active_sidebar('jl_ads_before_comment')) : echo '<div class="jl_ads_section jl_before_comment">'; dynamic_sidebar('jl_ads_before_comment');echo '</div>'; endif;
                            comments_template('', true);
                            ?>
				<!--comment:end -->
	        </div>
	        <!-- end: .content -->

	        <div class="clear"></div>

	    </div>
	    <!-- end: .center -->
    <?php endif; ?>
</main>
<!-- end: #page -->
<?php get_footer(); ?>
