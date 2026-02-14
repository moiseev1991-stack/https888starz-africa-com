<?php
/**
 * Template Name: Registration
 * Template for page slug: registration
 * Content: how to register, requirements, FAQ, CTA to terms/privacy.
 */
get_header();
?>
<main id="page" class="main grid-container">
	<div id="single" class="content no-sidebar no-thumb">
		<div class="epcl-page-wrapper content clearfix">
			<div class="left-content grid-100">
				<article class="main-article no-bg">
					<section class="post-content">
						<h1 class="title ularge textcenter bordered"><?php echo esc_html( get_translation('Registration at 888Starz') ); ?></h1>
						<div class="text">
							<p><?php echo esc_html( get_translation('Creating an account at 888Starz is quick. You can register with email, phone, or in one click. Below we explain the steps and requirements.') ); ?></p>

							<h2><?php echo esc_html( get_translation('Step-by-step registration') ); ?></h2>
							<ol>
								<li><?php echo esc_html( get_translation('Open the 888Starz website and click Registration.') ); ?></li>
								<li><?php echo esc_html( get_translation('Choose the registration method (email, phone, or one click).') ); ?></li>
								<li><?php echo esc_html( get_translation('Enter the required data and set a password.') ); ?></li>
								<li><?php echo esc_html( get_translation('Confirm your account via the link or code you receive.') ); ?></li>
								<li><?php echo esc_html( get_translation('Log in and complete your profile if needed.') ); ?></li>
							</ol>

							<h2><?php echo esc_html( get_translation('Age and eligibility') ); ?></h2>
							<p><?php echo esc_html( get_translation('You must be at least 18 years old (or the legal age in your country) to register. Registration may be restricted in some jurisdictions.') ); ?></p>

							<h2><?php echo esc_html( get_translation('Account confirmation') ); ?></h2>
							<p><?php echo esc_html( get_translation('After registration you may need to confirm your email or phone. Check your inbox or messages and follow the link or enter the code.') ); ?></p>

							<h2><?php echo esc_html( get_translation('FAQ – Registration and access') ); ?></h2>
							<p><strong><?php echo esc_html( get_translation('I did not receive the confirmation email.') ); ?></strong> <?php echo esc_html( get_translation('Check the spam folder. Add the sender to contacts and request the email again from the site.') ); ?></p>
							<p><strong><?php echo esc_html( get_translation('I forgot my password.') ); ?></strong> <?php echo esc_html( get_translation('Use the Forgot password link on the login page and follow the instructions sent to your email or phone.') ); ?></p>

							<p class="related-links">
								<a href="/terms/"><?php echo esc_html( get_translation('Terms and Conditions') ); ?></a> |
								<a href="/privacy-policy/"><?php echo esc_html( get_translation('Privacy Policy') ); ?></a> |
								<a href="/apk/"><?php echo esc_html( get_translation('Download App (APK)') ); ?></a> |
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
