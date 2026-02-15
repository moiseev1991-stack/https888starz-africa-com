<?php
if ( post_password_required() )
    return;

add_filter('comment_reply_link', 'epcl_replace_reply_link_class');

function epcl_replace_reply_link_class($class){
    $class = str_replace("class='comment-reply-link", "class='comment-reply-link epcl-button small", $class);
    return $class;
}    
$count = 0;
function epcl_comments_callback($comment, $args, $depth) {
	global $count;
	$count++;

    $avatar = get_avatar($comment); 

    $class = (!$avatar) ? ' no-avatar' : '';
?>
    <li <?php comment_class('count-'.$count.$class); ?> id="comment-<?php comment_ID() ?>">
    	<?php if($avatar): ?>
            <div class="author-avatar"><?php echo wp_kses_post($avatar) ; ?></div> 
        <?php endif; ?>
        <div class="right">
            <cite class="comment-author"><?php comment_author_link(); ?></cite>
            <span class="date"><?php esc_html_e('on', 'zento'); ?> <?php comment_date(); ?></span>
            <div class="clear"></div>
            <div class="text">
                <?php if ($comment->comment_approved == '0') : ?>
                    <p><?php esc_html_e( 'Your comment is awaiting moderation.', 'zento');?></p>
                <?php endif; ?>
                <?php comment_text(); ?>			
            </div>
            <?php comment_reply_link( array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']) ) ); ?>
        </div>
        <div class="clear"></div>
<?php
}
?>

<?php if( comments_open() || have_comments() ): ?>
    <!-- start: #comments -->
    <div id="comments" class="hosted <?php if( have_comments() ) echo 'have-comments'; else echo 'no-comments'; ?>">
        <?php if( have_comments() ): ?>
            <h3 class="title medium bordered gray-border"><svg class="decoration"><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#title-decoration"></use></svg><span><?php esc_html_e('Comments', 'zento'); ?></span></h3>
        <?php endif; ?>
        <?php if ( have_comments() ) : ?>
            
            <!-- start: .commentlist -->
            <ol class="commentlist">
                <?php wp_list_comments( array( 'callback' => 'epcl_comments_callback' ) ); ?>
            </ol>
            <!-- end: .commentlist  -->

            <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
                <div class="clear"></div>
                <!-- start: #comment-nav -->
                <nav id="comment-nav" class="pagination section">
                    <div class="nav-previous alignleft"><?php previous_comments_link( esc_html__('Older Comments', 'zento') ); ?></div>
                    <div class="nav-next alignright"><?php next_comments_link( esc_html__('Newer Comments', 'zento') ); ?></div>
                    <div class="clear"></div>
                </nav>
                <!-- end: #comment-nav -->
            <?php endif; ?>

            <?php if ( ! comments_open() && get_comments_number() ) : ?>
                <h4 class="title np-bottom no-margin textcenter"><?php esc_html_e('Comments are closed.', 'zento'); ?></h4>
            <?php endif; ?>

        <?php endif; // have_comments() ?>
        <?php
            $commenter = wp_get_current_commenter();
            
            $req = get_option( 'require_name_email' );
            $aria_req = ( $req ? " aria-required='true' required" : '' );
            $fields =  array(
                'author' => '<input class="form-author" name="author" type="text" placeholder="' . esc_attr__('Name', 'zento') . '" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' />',
                'email' => '<input class="form-email" name="email" type="email" placeholder="' . esc_attr__('Email',  'zento') . '" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /><div class="clear"></div>',
                'url' => '<input class="form-website" name="url" type="text" placeholder="' . esc_attr__('Website',  'zento'). '" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" />',
            );
            $fields = apply_filters('comment_form_default_fields', $fields);
            $comments_args = array(
                'fields' => $fields,
                'comment_field' => '<textarea id="comment" name="comment" aria-required="true" rows="10" placeholder="'.esc_attr__( 'Comment', 'zento').'"></textarea>',
                'must_log_in' => '<p class="must-log-in"><a href="' . wp_login_url( apply_filters( 'the_permalink', get_permalink() ) ) . '">'.  esc_html__('Log In', 'zento') .'</a></p>',
                'comment_form_top' => '',
                'comment_notes_after' => '',
                'comment_notes_before' => '',
                'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title title small bordered"><span>',
                'title_reply_after' => '</span></h3>',
                'class_submit' => 'epcl-button',
                'class_form' => 'comment-form underline-effect',
                'submit_button' => '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s</button>'
            );           
            
            comment_form($comments_args);
        ?>
        <div class="clear"></div>
    </div>
    <!-- end: #comments -->
<?php endif; ?>