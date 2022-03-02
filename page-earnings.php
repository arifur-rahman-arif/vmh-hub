<?php

if (!is_user_logged_in()) {
    wp_safe_redirect('/login');
}

?>


<?php get_header('header.php')?>

<!--======================== Start Mixxer Earning Popup Page ========================-->
<section class="login_main">
    <div class="container" style="min-height: 65vh;display: flex;justify-content: center;align-items: center;">
        <!-- Start Background Overly -->
        <div class="mixxer_learning_popup_background">
            <img src="<?php echo esc_url(VMH_URL . 'Assets/images/mixxer_earning_popup/circle.png') ?>" alt="images" />
        </div>
        <!-- End Background Overly -->

        <div class="mixxer_earning_popup">
            <div class="userdash_profile mixxer_earning_popup_profile">
                <div class="thank_profile_img">
                    <a href="<?php echo get_avatar_url(get_current_user_id()) ?>"><img
                            src="<?php echo get_avatar_url(get_current_user_id()) ?>" alt="images" /></a>
                </div>
                <div class="userdash_profile_title mixxer_earning_popup_title">
                    <a><?php echo get_the_author_meta('user_login', get_current_user_id()); ?></a>
                </div>
            </div>
            <div class="mixxer_earning_popup_content">
                <?php echo showEarningSectionHtml() ?>
            </div>

            <div class="mixxer_hide_icon">
                <a href="<?php echo site_url('/') ?>"><img
                        src="<?php echo esc_url(VMH_URL . 'Assets/images/mixxer_earning_popup/icon.png') ?>"
                        alt="images" /></a>
            </div>

            <!-- backround overly -->
            <!-- <div class="mixxer_earning_popup_overly">
                <img src="<?php echo esc_url(VMH_URL . 'Assets/images/mixxer_earning_popup/background_img.png') ?>"
                    alt="images" />
            </div> -->
        </div>
    </div>
</section>
<!--======================== End Mixxer Earning Popup Page ========================-->

<?php get_footer('footer.php')?>