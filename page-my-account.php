<?php get_header('header.php')?>

<section class="comon_section_area" style="min-height: 60vh;">
    <div class="container">
        <!-- Start Background Overly -->
        <div class="login_background"
            style="background-image: url(<?php echo VMH_URL . 'Assets/images/login/circle.png' ?>); height: 100%">
        </div>
        <!-- End Background Overly -->
        <?php the_content()?>
    </div>
</section>

<?php get_footer('header.php')?>