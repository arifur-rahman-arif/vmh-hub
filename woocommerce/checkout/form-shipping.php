<?php
/**
 * Checkout shipping information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-shipping.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @version 3.6.0
 * @global WC_Checkout $checkout
 * @package WooCommerce\Templates
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 */

defined('ABSPATH') || exit;
?>



<!--======================== Start Shipping Address Page ========================-->
<section class="login_main create_account shopping_method_main_padd vmh_shipping_address_container vmh_checkout_form">
    <div class="container" style="padding-top: 50px;">
        <!-- <div class="shipping_background">
            <img src="<?php echo esc_url(VMH_URL . 'Assets/images/shipping_address/circle.png') ?>" alt="images">
        </div> -->

        <div class="shipping_menu">
            <ul>
                <li><a href="#">Cart ></a></li>
                <li><a href="#">Billing Address></a></li>
                <li><a href="#" class="shipping_menu_active">Shipping Address ></a></li>
                <li><a href="#">Shipping Method ></a></li>
                <li><a href="#">Payment</a></li>
            </ul>
        </div>

        <div class="sign_in_box shipping_address_box">
            <div class="sing_in_header shipping_address_header">
                <h4>Shipping Address</h4>
            </div>

            <div class="woocommerce-shipping-fields">
                <?php if (true === WC()->cart->needs_shipping_address()): ?>

                <div class="shipping_address">

                    <?php do_action('woocommerce_before_checkout_shipping_form', $checkout);?>

                    <div class="woocommerce-shipping-fields__field-wrapper">
                        <?php
$fields = $checkout->get_checkout_fields('shipping');

foreach ($fields as $key => $field) {
    woocommerce_form_field($key, $field, $checkout->get_value($key));
}
?>
                    </div>

                    <?php do_action('woocommerce_after_checkout_shipping_form', $checkout);?>

                </div>

                <?php endif;?>
            </div>
            <div class="woocommerce-additional-fields">
                <?php do_action('woocommerce_before_order_notes', $checkout);?>

                <?php if (apply_filters('woocommerce_enable_order_notes_field', 'yes' === get_option('woocommerce_enable_order_comments', 'yes'))): ?>

                <?php if (!WC()->cart->needs_shipping() || wc_ship_to_billing_address_only()): ?>

                <h4><?php esc_html_e('Additional information', 'woocommerce');?></h4>

                <?php endif;?>

                <div class="woocommerce-additional-fields__field-wrapper">
                    <?php foreach ($checkout->get_checkout_fields('order') as $key => $field): ?>
                    <?php woocommerce_form_field($key, $field, $checkout->get_value($key));?>
                    <?php endforeach;?>
                </div>

                <?php endif;?>

                <?php do_action('woocommerce_after_order_notes', $checkout);?>
            </div>


            <div class="logon_input_btn logon_input_btn2 shipping_address_btn">
                <a href="" class="vmh_previous_btn" data-target="vmh_billing_address_container"><i
                        class="fas fa-less-than"></i>Go Back</a>
                <div class="vmh_checkout_next_btn" data-target="vmh_review_container">Next</div>
            </div>

        </div>
    </div>
</section>
<!--======================== End Shipping Address Page ========================-->