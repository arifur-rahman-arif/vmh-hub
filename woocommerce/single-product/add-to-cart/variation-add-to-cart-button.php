<?php
/**
 * Single variation cart button
 *
 * @version 3.4.0
 *
 * @package WooCommerce\Templates
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 */

defined('ABSPATH') || exit;

global $product;

?>
<div class="woocommerce-variation-add-to-cart variations_button">
    <?php do_action('woocommerce_before_add_to_cart_button');?>

    <?php
do_action('woocommerce_before_add_to_cart_quantity');

// Disabling the quantity input
// woocommerce_quantity_input(
//     array(
//         'min_value'   => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
//         'max_value'   => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
//         'input_value' => isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $product->get_min_purchase_quantity() // WPCS: CSRF ok, input var ok.
//     )
// );

do_action('woocommerce_after_add_to_cart_quantity');
?>

    <?php if ($product->get_id() != get_option('vmh_create_product_option')) {?>
    <div class="logon_input_btn logon_input_btn2 shipping_address_btn recepes_btn">
        <div class="recepes_btn_content">
            <!-- <button data-id="<?php echo get_the_ID() ?>" class="vmh_single_add_to_cart">Add To Cart</button> -->
            <button type="submit"
                class="vmh_single_add_to_cart single_add_to_cart_button button alt"><?php echo esc_html($product->single_add_to_cart_text()); ?></button>
            <!-- <a href="#">Add To Subscription</a> -->
        </div>
    </div>

    <?php }?>

    <?php do_action('woocommerce_after_add_to_cart_button');?>

    <input type="hidden" name="add-to-cart" value="<?php echo absint($product->get_id()); ?>" />
    <input type="hidden" name="product_id" value="<?php echo absint($product->get_id()); ?>" />
    <input type="hidden" name="variation_id" class="variation_id" value="0" />
</div>