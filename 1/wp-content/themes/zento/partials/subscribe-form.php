<?php if( epcl_get_option('subscribe_url') ): ?>
    <?php
    $unique_id = wp_unique_id('epcl-subscribe-form-');
    ?>
    <form class="subscribe-form" action="<?php echo esc_url( epcl_get_option('subscribe_url') ); ?>" method="<?php echo esc_attr( epcl_get_option_text('subscribe_method', 'POST') ); ?>" target="_blank">
            <?php if( epcl_get_option('subscribe_parameters') && function_exists('epcl_render_subscribe_parameters') ): ?>
                <?php epcl_render_subscribe_parameters( epcl_get_option('subscribe_parameters') ); ?>
            <?php endif; ?>
            <label class="title small" for="email-<?php echo esc_attr($unique_id); ?>"><?php echo epcl_get_option('subscribe_label', "Let's connect"); ?></label>
            <div class="form-group">
                <input type="email" id="email-<?php echo esc_attr($unique_id); ?>" name="<?php echo esc_attr( epcl_get_option_text('subscribe_email_field_name', 'MERGE0') ); ?>" class="inputbox large" required placeholder="<?php esc_attr_e('Enter your email address', 'zento'); ?>">
                <button class="epcl-button submit absolute" type="submit"><?php esc_html_e('Get Started', 'zento'); ?><span class="loader"></span></button>
            </div>                
        </form> 
  
<?php endif; ?>