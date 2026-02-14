<?php
/**
 * Template Name: Promo Code
 * Template for page slug: promo-code
 * Content: what is promo code, where to enter, conditions, links to bonuses/terms.
 */
get_header();
?>
<main id="page" class="main grid-container">
	<div id="single" class="content no-sidebar no-thumb">
		<div class="epcl-page-wrapper content clearfix">
			<div class="left-content grid-100">
				<article class="main-article no-bg">
					<section class="post-content">
						<h1 class="title ularge textcenter bordered"><?php echo esc_html( get_translation('Promo Code') ); ?></h1>
						<div class="text">
							<p><?php echo esc_html( get_translation('A promo code is a special code that can give you a bonus or offer when you register or deposit. Enter it in the correct field on the 888Starz website or in the app.') ); ?></p>

							<h2><?php echo esc_html( get_translation('What is a promo code?') ); ?></h2>
							<p><?php echo esc_html( get_translation('Promo codes are issued by 888Starz or partners. They may unlock welcome bonuses, free spins, or other promotions. Each code has its own terms.') ); ?></p>

							<h2><?php echo esc_html( get_translation('Where to enter the code') ); ?></h2>
							<p><?php echo esc_html( get_translation('When registering or making a deposit, look for the Promo code or Bonus code field. Enter the code exactly as given (case may matter).') ); ?></p>

							<h2><?php echo esc_html( get_translation('Code not working?') ); ?></h2>
							<ul>
								<li><?php echo esc_html( get_translation('Check that the code is valid and not expired.') ); ?></li>
								<li><?php echo esc_html( get_translation('Ensure you meet the conditions (e.g. first deposit, minimum amount).') ); ?></li>
								<li><?php echo esc_html( get_translation('Try without extra spaces. If it still fails, contact support.') ); ?></li>
							</ul>

							<h2><?php echo esc_html( get_translation('Terms and conditions') ); ?></h2>
							<p><?php echo esc_html( get_translation('Promotions and codes are subject to the general terms and the specific offer terms. Wagering and other requirements may apply.') ); ?></p>

							<p class="related-links">
								<a href="/accounts-withdrawals-and-bonuses/"><?php echo esc_html( get_translation('Accounts, Withdrawals & Bonuses') ); ?></a> |
								<a href="/terms/"><?php echo esc_html( get_translation('Terms and Conditions') ); ?></a> |
								<a href="/registration/"><?php echo esc_html( get_translation('Registration') ); ?></a> |
								<a href="/contacts/"><?php echo esc_html( get_translation('Contact Us') ); ?></a>
							</p>
						</div>
					</section>
				</article>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</main>
<?php get_footer(); ?>
