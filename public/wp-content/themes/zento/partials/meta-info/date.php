<?php if( epcl_get_option('enable_global_date') !== '0' ): ?>
    <time class="meta-info" datetime="<?php the_time('Y-m-d'); ?>">
        <svg class="icon main-color small"><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#calendar-icon"></use></svg>
        <?php the_time( get_option('date_format') ); ?>
    </time>  
<?php endif; ?>