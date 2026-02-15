<?php if( epcl_get_option( 'enable_global_comments', true ) ): ?>
    <div class="meta absolute hide-on-mobile hide-on-tablet">    
        <a href="<?php the_permalink();?>#comments" class="comments icon mobile tooltip" data-title="<?php echo esc_html__('Go to comments', 'zento'); ?>">
            <svg class="icon large"><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#comments-icon"></use></svg> 
            <?php if( epcl_get_option('hosted_comments') !== '2' && epcl_get_option('hosted_comments') !== '3' ): ?>
                <span class="comment-count"><?php esc_html( printf( _n( '%1$s Comment', '%1$s Comments', get_comments_number(), 'zento'), number_format_i18n( get_comments_number() ) ) ); ?></span>
            <?php elseif( epcl_get_option('hosted_comments') == '3' ): // Facebook commments ?>
                <span class="fb-comments-count" data-href="<?php the_permalink(); ?>">0</span>
            <?php else: // Disqus Comments ?>
                <span class="disqus-comment-count" data-disqus-url="<?php the_permalink(); ?>" data-disqus-identifier="<?php the_ID(); ?>">0</span>
            <?php endif; ?>
        </a>   
    </div>
<?php endif; ?>