<?php
$epcl_theme = epcl_get_theme_options();
?>
<!-- start: .epcl-comments -->
<div id="show-comments" class="epcl-comments section np-bottom">

    <?php if( empty($epcl_theme) || $epcl_theme['hosted_comments'] == 1 ): // Self Hosted ?>
        <?php comments_template(); ?>
    <?php endif; ?>

    <?php if( !empty($epcl_theme) && $epcl_theme['hosted_comments'] == 2 && $epcl_theme['disqus_id'] ): // Disqus Comments ?>
        <!-- start: disqus integration -->
        <div id="comments" class="epcl-disqus-comments">
            <h3 class="title medium bordered gray-border"><svg class="decoration"><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#title-decoration"></use></svg><span><?php esc_html_e('Comments', 'zento'); ?></span></h3>
            <div id="disqus_thread"></div>
        </div>
        <noscript><?php esc_html_e('Please enable JavaScript to view the', 'zento'); ?> <a href="https://disqus.com/?ref_noscript" rel="nofollow"><?php esc_html_e('comments powered by Disqus.', 'zento'); ?></a></noscript>
        <!-- end: disqus integration -->
    <?php endif; ?>

    <?php if( !empty($epcl_theme) && $epcl_theme['hosted_comments'] == 3 ): // Facebook Comments ?>
        <!-- start: facebook comments -->
        <div id="comments" class="epcl-facebook-comments">
            <h3 class="title medium bordered gray-border"><svg class="decoration"><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#title-decoration"></use></svg><span><?php esc_html_e('Comments', 'zento'); ?></span></h3>
            <div class="clear"></div>
            <div class="fb-comments" data-href="<?php the_permalink(); ?>" data-width="100%" data-numposts="5" data-colorscheme="light"></div>
        </div>
        <!-- end: facebook comments -->
        <div id="fb-root"></div>                        
    <?php endif; ?>

    <div class="clear"></div>
</div>
<!-- end: .epcl-comments -->  