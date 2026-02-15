<?php
$author_id = get_the_author_meta('ID');
$user_meta = get_user_meta( $author_id, 'epcl_user', true );
if( !empty($user_meta) && !empty( $user_meta['avatar']) && $user_meta['avatar']['url'] != '' ){
    $author_avatar = $user_meta['avatar']['url'];
}else{
    $author_avatar = get_avatar_url( get_the_author_meta('email'), array( 'size' => 120 ));
}
$author_name = get_the_author();
?>
<a href="/author/" class="author">                                        
    <?php if($author_avatar): ?>
        <img class="author-image cover" loading="lazy" fetchpriority="low" decoding="async" src="<?php echo esc_url($author_avatar); ?>" alt="<?php echo sprintf( esc_attr__('Avatar Image of %s', 'zento'), $author_name ); ?>">                     
    <?php endif; ?>                               
    <span class="author-name"><?php echo esc_html($author_name); ?></span>
</a>