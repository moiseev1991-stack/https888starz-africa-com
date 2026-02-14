<?php
/**
 * Template Name: APK / Download App
 * Template for page slug: apk
 * Content: app download info, system requirements, security, FAQ.
 */
get_header();
?>
<main id="page" class="main grid-container">
	<div id="single" class="content no-sidebar no-thumb">
		<div class="epcl-page-wrapper content clearfix">
			<div class="left-content grid-100">
				<article class="main-article no-bg">
					<section class="post-content">
						<h1 class="title ularge textcenter bordered"><?php echo esc_html( get_translation('Download 888Starz App (APK)') ); ?></h1>
						<div class="text">
							<p><?php echo esc_html( get_translation('The 888Starz Android app (APK) lets you bet and play casino on your phone. Download only from the official website. Below you find system requirements, installation steps, and security tips.') ); ?></p>

							<h2><?php echo esc_html( get_translation('What is the APK?') ); ?></h2>
							<p><?php echo esc_html( get_translation('The APK file is the Android application package. Installing it gives you quick access to 888Starz without using the browser.') ); ?></p>

							<h2><?php echo esc_html( get_translation('System requirements') ); ?></h2>
							<p><?php echo esc_html( get_translation('Android 6.0 or higher. Free storage space (about 70 MB). Stable internet connection.') ); ?></p>

							<h2><?php echo esc_html( get_translation('Installation steps') ); ?></h2>
							<ol>
								<li><?php echo esc_html( get_translation('Open this site on your Android device.') ); ?></li>
								<li><?php echo esc_html( get_translation('Go to the app download section and tap the download link.') ); ?></li>
								<li><?php echo esc_html( get_translation('Allow installation from this source in device settings if prompted.') ); ?></li>
								<li><?php echo esc_html( get_translation('Open the downloaded APK and follow the installer.') ); ?></li>
							</ol>

							<h2><?php echo esc_html( get_translation('Security') ); ?></h2>
							<p><?php echo esc_html( get_translation('Download the APK only from the official 888Starz website. Do not use third-party sites to avoid modified or unsafe files.') ); ?></p>

							<h2><?php echo esc_html( get_translation('FAQ') ); ?></h2>
							<p><strong><?php echo esc_html( get_translation('Is the app free?') ); ?></strong> <?php echo esc_html( get_translation('Yes. Download and use of the app is free.') ); ?></p>
							<p><strong><?php echo esc_html( get_translation('Can I use the same account as on the website?') ); ?></strong> <?php echo esc_html( get_translation('Yes. Log in with your existing 888Starz account.') ); ?></p>

							<p class="related-links">
								<a href="/registration/"><?php echo esc_html( get_translation('Registration') ); ?></a> |
								<a href="/terms/"><?php echo esc_html( get_translation('Terms and Conditions') ); ?></a> |
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
