<?php if( has_nav_menu('epcl_header') ): ?>

    <!-- start: .main-nav -->
    <nav class="main-navigate">
        <?php
        $args = array(
            'theme_location' => 'epcl_header',
            'container' => false,
            'menu_class' => 'menu underline-effect',
        );
        wp_nav_menu($args);
        ?>
        <?php if( function_exists('epcl_render_header_social_buttons') ): ?>
            <?php epcl_render_header_social_buttons(); ?>
        <?php endif; ?>
    </nav>
    <!-- end: .main-nav -->

<?php endif; ?>