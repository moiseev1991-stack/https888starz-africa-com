<?php
$epcl_theme = epcl_get_theme_options();
// Just demo purposes
if( isset($_GET['header']) && $_GET['header'] == 'notice' ){
	$header_type = sanitize_text_field( $_GET['header'] );
    $epcl_theme['enable_notice'] = true;
}
?>
<?php if( !empty($epcl_theme) && $epcl_theme['enable_notice']  == true && epcl_get_option('notice_text') ): ?>
    <?php if( epcl_get_option('enable_notice_close')  == false || (!isset($_COOKIE['epcl_show_notice']) || $_COOKIE['epcl_show_notice'] != 'false') ): ?>
        <div class="notice text">
            <div class="grid-container">
                <div class="info">
                    <span class="border-effect small-effect white">
                        <?php echo wp_kses_post( wpautop( do_shortcode( $epcl_theme['notice_text'] ) ) ); ?>
                    </span>
                    <?php if( epcl_get_option('enable_notice_close')  == true ): ?>
                        <a href="<?php echo esc_url( home_url( $wp->request ) ); ?>?epcl-action=remove-notice" class="close">&times;</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>