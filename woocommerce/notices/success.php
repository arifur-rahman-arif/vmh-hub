<?php
/**
 * Show messages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/notices/success.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @version 3.9.0
 * @package WooCommerce\Templates
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!$notices) {
    return;
}

?>

<?php foreach ($notices as $notice): ?>


<div class="sign_in_box vmh_woocommerce_notice thank_you_mu create_acc_pages">
    <!-- Hide Btn Overly -->
    <div class="thank_hide_btn">
        <a href="#"><img src="<?php echo VMH_URL . 'Assets/images/thank-you/hide.png' ?>" alt="" /></a>
    </div>

    <div class="sing_in_header sing_in_header2 vmh-response-msg">
        <h3>Thank you</h3>
        <p><?php echo wc_kses_notice($notice['notice']); ?></p>
        <div class="create_recipe_add_to_cart_popup">
            <a class="vmh_checkout_btn" href="<?php echo site_url('/cart') ?>">Go to cart</a>
            <a class="vmh_checkout_btn" href="<?php echo site_url('/') ?>">Continue shopping</a>
        </div>
    </div>
</div>

<?php endforeach;?>