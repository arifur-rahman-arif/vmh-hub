<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @version 3.4.0
 * @package WooCommerce\Templates
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 */

defined('ABSPATH') || exit;

global $product;

if (!$product->is_purchasable()) {
    return;
}

echo wc_get_stock_html($product); // WPCS: XSS ok.

if ($product->is_in_stock()): ?>

<?php do_action('woocommerce_before_add_to_cart_form');?>


<div class="recepes_right_left_input_code">
    <div class="recepes_right_left_input_code_header">
        <h5>Ingredients</h5>
    </div>

    <!-- Display ingredients of this product -->
    <?php echo singleProductIngredientsHTML(get_the_ID()) ?>
    <!-- Start Button -->

    <div class="recepes_choose_option">


        <?php echo simpleProductOptions() ?>

        <form class="cart"
            action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>"
            method="post" enctype='multipart/form-data'>
            <?php do_action('woocommerce_before_add_to_cart_button');?>

            <?php
do_action('woocommerce_before_add_to_cart_quantity');

woocommerce_quantity_input(
    array(
        'min_value'   => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
        'max_value'   => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
        'input_value' => isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $product->get_min_purchase_quantity() // WPCS: CSRF ok, input var ok.
    )
);

do_action('woocommerce_after_add_to_cart_quantity');
?>

            <div class="logon_input_btn logon_input_btn2 shipping_address_btn recepes_btn">
                <div class="recepes_btn_content">
                    <!-- <button data-id="<?php echo get_the_ID() ?>" class="vmh_single_add_to_cart">Add To Cart</button> -->
                    <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>"
                        class="vmh_single_add_to_cart single_add_to_cart_button button alt"><?php echo esc_html($product->single_add_to_cart_text()); ?></button>
                </div>
            </div>

            <?php do_action('woocommerce_after_add_to_cart_button');?>
        </form>


    </div>

</div>

<?php do_action('woocommerce_after_add_to_cart_form');?>

<?php endif;?>