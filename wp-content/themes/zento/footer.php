        <?php get_template_part('partials/footer-cta'); ?>
        
        <?php get_template_part('partials/footer'); ?>

        <div class="clear"></div>
    </div>

<?php // PageSpeed: font-display swap для Font Awesome (fonts/fontawesome-webfont.woff2) ?>
<style>@font-face{font-family:'FontAwesome';font-display:swap}@font-face{font-family:'Font Awesome 5 Free';font-display:swap}@font-face{font-family:'Font Awesome 5 Brands';font-display:swap}</style>
<style>.table-wrapper{min-height:0 !important}.table-wrapper:empty{display:none !important}</style>

<!-- SEO/tag block (mobile): expand on click. Vanilla so it works without jQuery (e.g. static export). -->
<script>
(function(){
	var q = document.querySelector('.caption-seo-module.faq-question');
	var ans = document.getElementById('seo-module');
	if (q && ans) {
		q.setAttribute('role', 'button');
		q.setAttribute('aria-expanded', 'false');
		q.addEventListener('click', function(e) {
			e.stopImmediatePropagation();
			var open = ans.style.display === 'block';
			ans.style.display = open ? 'none' : 'block';
			q.setAttribute('aria-expanded', !open);
			q.classList.toggle('open', !open);
			var rot = q.querySelector('.ico_rotater_footer');
			if (rot) rot.classList.toggle('rotate', !open);
		}, true);
	}
})();
</script>
<!-- Carousel Lite loaded via wp_enqueue (replaces Owl). Fancybox for galleries. -->
<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" /></noscript>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
<script>
	$(document).ready(function(){
		$('[data-fancybox="gallery"]').fancybox({
    buttons: [
        "zoom",
        "share",
        "slideShow",
        "fullScreen",
        "download",
        "thumbs",
        "close"
    ],
    loop: true,
    protect: true,
    animationEffect: "zoom",
    transitionEffect: "fade"
});

		// То же самое для мобильной версии
		$('[data-fancybox="gallerymob"]').fancybox({
			buttons: ["zoom","share","slideShow","fullScreen","download","thumbs","close"],
			loop: true,
			protect: true,
			animationEffect: "zoom",
			transitionEffect: "fade"
		});
	});
</script>


    <!-- end: #wrapper -->

    <?php wp_footer(); ?>     

    <?php if( function_exists('epcl_render_demo_button') ) epcl_render_demo_button(); ?>
    
    </body>
</html>
