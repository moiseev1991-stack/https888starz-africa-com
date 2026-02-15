<?php
if( epcl_get_option('enable_rating', true) == false ) return;
$post_meta = get_post_meta( get_the_ID(), 'epcl_post', true );
$rating_class = '';
if( isset( $post_meta['rating'] ) && $post_meta['rating'] && $post_meta['rating'] != 'disabled' ){
    $rating_class = 'star-' . $post_meta['rating'];
}else{
   return;
} 
?>
<div class="difficulty meta-info">
    <svg class="icon main-color"><use xlink:href="<?php echo EPCL_THEMEPATH; ?>/assets/images/svg-icons.svg#medal-icon"></use></svg> <span class="name"><?php echo epcl_get_option('rating_wording', 'Rating'); ?></span>
    <div class="rating <?php echo esc_attr($rating_class); ?>">
        <span class="star">★</span>
        <span class="star">★</span>
        <span class="star">★</span>
    </div>
</div>