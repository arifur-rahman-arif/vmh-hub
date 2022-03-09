<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @version 5.2.0
 * @package WooCommerce\Templates
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 */

defined('ABSPATH') || exit;
?>

<script>
var url = new URL(window.location);
var params = new URLSearchParams(url.search);
var retrieveParam = params.get('form-step');
if (retrieveParam == 'vmh_review_container') {
    if (document.querySelector('.vmh_review_container')) {
        document.querySelector('.vmh_review_container').classList.add('checkout_form_active')
    }
}
</script>
<section
    class="shop_table woocommerce-checkout-review-order-table login_main create_account shopping_method_main_padd vmh_review_container vmh_checkout_form">

    <div class="container" style="padding-top: 50px;">

        <!-- <div class="shipping_background">
            <img src="<?php echo esc_url(VMH_URL . 'Assets/images/shipping_address/circle.png') ?>" alt="images">
        </div> -->
        <div class="shipping_menu">
            <ul>
                <li><a href="#">Cart ></a></li>
                <li><a href="#">Billing Address></a></li>
                <li><a href="#">Shipping Address ></a></li>
                <li><a href="#" class="shipping_menu_active">Shipping Method ></a></li>
                <li><a href="#">Payment</a></li>
            </ul>
        </div>

        <div class="sign_in_box shipping_address_box">

            <div class="shipping_address_header shipping_method_header">
                <h4>Shipping Method</h4>
            </div>

            <?php foreach (WC()->cart->get_coupons() as $code => $coupon): ?>
            <div class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
                <th><?php wc_cart_totals_coupon_label($coupon);?></th>
                <td><?php wc_cart_totals_coupon_html($coupon);?></td>
            </div>
            <?php endforeach;?>

            <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()): ?>

            <?php do_action('woocommerce_review_order_before_shipping');?>

            <?php wc_cart_totals_shipping_html();?>


            <?php do_action('woocommerce_review_order_after_shipping');?>

            <?php endif;?>

            <?php foreach (WC()->cart->get_fees() as $fee): ?>
            <div class="fee">
                <th><?php echo esc_html($fee->name); ?></th>
                <td><?php wc_cart_totals_fee_html($fee);?></td>
            </div>
            <?php endforeach;?>

            <?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()): ?>
            <?php if ('itemized' === get_option('woocommerce_tax_total_display')): ?>
            <?php foreach (WC()->cart->get_tax_totals() as $code => $tax): // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
	            <div class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
	                <th><?php echo esc_html($tax->label); ?></th>
	                <td><?php echo wp_kses_post($tax->formatted_amount); ?></td>
	            </div>
	            <?php endforeach;?>
            <?php else: ?>
            <div class="tax-total">
                <th><?php echo esc_html(WC()->countries->tax_or_vat()); ?></th>
                <td><?php wc_cart_totals_taxes_total_html();?></td>
            </div>
            <?php endif;?>
            <?php endif;?>

            <?php do_action('woocommerce_review_order_before_order_total');?>


            <br>
            <div class="cart-subtotal">
                <span class="vmh_subtotal"><?php esc_html_e('Subtotal', 'woocommerce');?>:</span>
                <strong><?php wc_cart_totals_subtotal_html();?></strong>
            </div>
            <br>
            <div class="order-total">
                <span class="vmh_subtotal"><?php esc_html_e('Total', 'woocommerce');?></span>
                <td><?php wc_cart_totals_order_total_html();?></td>
            </div>

            <?php do_action('woocommerce_review_order_after_order_total');?>



            <div class="logon_input_btn logon_input_btn2 shipping_address_btn">
                <a href="#" class="vmh_previous_btn" data-target="vmh_shipping_address_container"><i
                        class="fas fa-less-than"></i>Go Back</a>

                <div class="vmh_checkout_next_btn" data-target="vmh_payment_container">Next</div>

            </div>

        </div>


    </div>


</section>