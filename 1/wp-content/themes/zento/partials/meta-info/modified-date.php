<?php if( epcl_get_option( 'enable_global_modified_date', true ) ): ?>
    <time class="meta-info last-updated" datetime="<?php echo get_the_modified_time( 'Y-m-d h:ia' ); ?>">
        <svg class="icon main-color"><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#clock-icon"></use></svg> <span class="name"><?php esc_html_e('Updated:', 'zento'); ?></span>
        <?php echo get_the_modified_time( get_option('date_format') ); ?>
    </time>
<?php endif; ?>