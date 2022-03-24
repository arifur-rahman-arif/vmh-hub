<?php
    /**
     * Shipping Methods Display
     *
     * In 2.1 we show methods per package. This allows for multiple methods per order if so desired.
     *
     * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-shipping.php.
     *
     * HOWEVER, on occasion WooCommerce will need to update template files and you
     * (the theme developer) will need to copy the new files to your theme to
     * maintain compatibility. We try to do this as little as possible, but it does
     * happen. When this occurs the version of the template file will be bumped and
     * the readme will list any important changes.
     *
     * @version 3.6.0
     * @package WooCommerce\Templates
     *
     * @see https://docs.woocommerce.com/document/template-structure/
     */

    defined('ABSPATH') || exit;

    $formatted_destination = isset($formatted_destination) ? $formatted_destination : WC()->countries->get_formatted_address($package['destination'], ', ');
    $has_calculated_shipping = !empty($has_calculated_shipping);
    $show_shipping_calculator = !empty($show_shipping_calculator);
    $calculator_text = '';
?>
<div class="woocommerce-shipping-totals shipping">
    <?php if ($available_methods): ?>
    <ul id="shipping_method" class="woocommerce-shipping-methods">
        <?php foreach ($available_methods as $method): ?>
        <li class="shipping_method_first">
            <?php if (count($available_methods)) {?>

            <label class="shipping_method_first_left"
                for="shipping_method_<?php echo $index ?>_<?php echo esc_attr(sanitize_title($method->id)) ?>">
                <div class="custom-radio-buttons">
                    <div class="radio-wrapper">

                        <input class="vmh_shipping_method_input" type="radio"
                            name="shipping_method[<?php echo $index ?>]" data-index="<?php echo $index ?>"
                            id="shipping_method_<?php echo $index ?>_<?php echo esc_attr(sanitize_title($method->id)) ?>"
                            value="<?php echo esc_attr($method->id) ?>"
                            <?php echo checked($method->id, $chosen_method, false) ?> />


                        <div class="shipping_method_second_hbold">
                            <h4><?php echo wc_cart_totals_shipping_method_label($method) ?></h4>
                        </div>

                    </div>
                </div>

            </label>

            <!-- <div class="shipping_method_first_right">
                <p><?php echo wc_cart_totals_shipping_method_label($method) ?></p>
            </div> -->

            <?php } else {?>
            <?php printf('<input type="hidden" name="shipping_method[%1$d]" data-index="%1$d" id="shipping_method_%1$d_%2$s" value="%3$s" class="shipping_method" />', $index, esc_attr(sanitize_title($method->id)), esc_attr($method->id)); // WPCS: XSS ok.?>
            <?php }?>
            <?php

    do_action('woocommerce_after_shipping_rate', $method, $index);
?>
        </li>
        <?php endforeach;?>
    </ul>
    <?php if (is_cart()): ?>
    <p class="woocommerce-shipping-destination">
        <?php
            if ($formatted_destination) {
                // Translators: $s shipping destination.
                printf(esc_html__('Shipping to %s.', 'woocommerce').' ', '<strong>'.esc_html($formatted_destination).'</strong>');
                $calculator_text = esc_html__('Change address', 'woocommerce');
            } else {
                echo wp_kses_post(apply_filters('woocommerce_shipping_estimate_html', __('Shipping options will be updated during checkout.', 'woocommerce')));
            }
        ?>
    </p>
    <?php endif;?>
    <?php
    elseif (!$has_calculated_shipping || !$formatted_destination):
        if (is_cart() && 'no' === get_option('woocommerce_enable_shipping_calc')) {
            echo wp_kses_post(apply_filters('woocommerce_shipping_not_enabled_on_cart_html', __('Shipping costs are calculated during checkout.', 'woocommerce')));
        } else {
            echo wp_kses_post(apply_filters('woocommerce_shipping_may_be_available_html', __('Enter your address to view shipping options.', 'woocommerce')));
        } elseif (!is_cart()):
        echo wp_kses_post(apply_filters('woocommerce_no_shipping_available_html', __('There are no shipping options available. Please ensure that your address has been entered correctly, or contact us if you need any help.', 'woocommerce')));
    else:
        // Translators: $s shipping destination.
        echo wp_kses_post(apply_filters('woocommerce_cart_no_shipping_available_html', sprintf(esc_html__('No shipping options were found for %s.', 'woocommerce').' ', '<strong>'.esc_html($formatted_destination).'</strong>')));
        $calculator_text = esc_html__('Enter a different address', 'woocommerce');
    endif;
?>

    <?php if ($show_package_details): ?>
    <?php echo '<p class="woocommerce-shipping-contents"><small>'.esc_html($package_details).'</small></p>'; ?>
    <?php endif;?>

    <?php if ($show_shipping_calculator): ?>
    <?php woocommerce_shipping_calculator($calculator_text);?>
    <?php endif;?>
</div>