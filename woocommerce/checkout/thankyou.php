<?php
    /**
     * Thankyou page
     *
     * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
     *
     * HOWEVER, on occasion WooCommerce will need to update template files and you
     * (the theme developer) will need to copy the new files to your theme to
     * maintain compatibility. We try to do this as little as possible, but it does
     * happen. When this occurs the version of the template file will be bumped and
     * the readme will list any important changes.
     *
     * @version 3.7.0
     * @package WooCommerce\Templates
     *
     * @see https://docs.woocommerce.com/document/template-structure/
     */

    defined('ABSPATH') || exit;
?>


<div class="woocommerce-order">

    <?php
        if ($order):

            do_action('woocommerce_before_thankyou', $order->get_id());
        ?>

    <?php if ($order->has_status('failed')): ?>

    <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed">
        <?php esc_html_e('Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce');?>
    </p>

    <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
        <a href="<?php echo esc_url($order->get_checkout_payment_url()); ?>"
            class="button pay"><?php esc_html_e('Pay', 'woocommerce');?></a>
        <?php if (is_user_logged_in()): ?>
        <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"
            class="button pay"><?php esc_html_e('My account', 'woocommerce');?></a>
        <?php endif;?>
    </p>

    <?php else: ?>

    <section class="login_main">
        <div class="container vmh_thankyoucontainer" style="padding-top: 50px;">

            <!-- <div class="thank_you2_main_background_img">
                <img src="<?php echo esc_url(VMH_URL.'Assets/images/thank_you2/circle.png') ?>" alt="images" />
            </div> -->


            <div class="mixxer_earning_popup thank_you2">
                <div class="thank_you2_content">
                    <h4>Thank you</h4>
                    <p>Your order is complete, We will soon get down to mixing</p>
                </div>

                <div class="mixxer_hide_icon thank_you2_hide_btn">
                    <a href="<?php echo site_url('/') ?>"><img
                            src="<?php echo esc_url(VMH_URL.'Assets/images/mixxer_earning_popup/icon.png') ?>"
                            alt="images" /></a>
                </div>


                <div class="mixxer_earning_popup_overly thank_you2_overly_background">
                    <!-- <img src="<?php echo esc_url(VMH_URL.'Assets/images/thank_you2/lab.png') ?>" alt="images" /> -->
                </div>
            </div>



            <?php endif;?>

            <?php do_action('woocommerce_thankyou_'.$order->get_payment_method(), $order->get_id());?>
            <?php do_action('woocommerce_thankyou', $order->get_id());?>

            <?php else: ?>

            <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
                <?php echo apply_filters('woocommerce_thankyou_order_received_text', esc_html__('Thank you. Your order has been received.', 'woocommerce'), null); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped                         ?>
            </p>

            <?php endif;?>
        </div>

    </section>

</div>

<script>
setTimeout(() => {
    window.location.href = "<?php echo site_url('/') ?>";
}, 4000)
</script>