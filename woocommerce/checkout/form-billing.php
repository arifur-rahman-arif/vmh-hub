<?php
    /**
     * Checkout billing information form
     *
     * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-billing.php.
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

<!--======================== Start Billing Address Page ========================-->
<section
    class="login_main create_account shopping_method_main_padd vmh_billing_address_container checkout_form_active vmh_checkout_form">
    <div class="container p-0" style="padding-top: 50px;">
        <!-- Start Background Overly -->
        <!-- <div class="shipping_background">
            <img src="<?php echo esc_url(VMH_URL.'Assets/images/shipping_address/circle.png') ?>" alt="images">
        </div> -->
        <!-- End Background Overly -->

        <div class="shipping_menu">
            <ul>
                <li><a href="#">Cart ></a></li>
                <li><a href="#" class="shipping_menu_active">Billing Address ></a></li>
                <li><a href="#">Shipping Address ></a></li>
                <li><a href="#">Shipping Method ></a></li>
                <li><a href="#">Payment</a></li>
            </ul>
        </div>

        <div class="sign_in_box shipping_address_box">
            <div class="sing_in_header shipping_address_header">
                <h4>Billing Address</h4>
            </div>

            <div class="woocommerce-billing-fields">


                <?php do_action('woocommerce_before_checkout_billing_form', $checkout);?>

                <div class="woocommerce-billing-fields__field-wrapper">
                    <?php
                        $fields = $checkout->get_checkout_fields('billing');

                        foreach ($fields as $key => $field) {
                            woocommerce_form_field($key, $field, $checkout->get_value($key));
                        }
                    ?>
                </div>

                <?php do_action('woocommerce_after_checkout_billing_form', $checkout);?>
            </div>

            <div class="logon_input_btn logon_input_btn2 shipping_address_btn">
                <a href="<?php echo esc_url(site_url('/cart')) ?>"><i class="fas fa-less-than"></i>Back to Cart</a>
                <div class="vmh_checkout_next_btn" data-target="vmh_shipping_address_container">Next</div>
            </div>

        </div>
    </div>
</section>
<!--======================== End billing Address Page ========================-->