<?php get_template_part('amp/header'); ?>

<?php
$layout = epcl_get_option( 'amp_archives_layout', 'classic-posts' );
?>

<!-- start: #archives-->
<main id="archives" class="main">

    <div class="content">
        <?php get_template_part( 'partials/home-blocks/'.$layout ); ?>
    </div>

</main>
<!-- end: #archives -->

<?php get_template_part('amp/footer'); ?>
