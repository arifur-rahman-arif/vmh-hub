<?php get_header('header.php')?>

<?php $cartItems = getCartItems();?>

<!--======================== Start Cart Page ========================-->
<section class="login_main create_account shopping_method_main_padd">
    <div class="container">
        <!-- Start Background Overly -->
        <div class="shipping_background">
            <img src="<?php echo VMH_URL.'Assets/images/shipping_address/circle.png' ?>" alt="images" />
        </div>
        <!-- End Background Overly -->

        <div class="shipping_menu">
            <ul>
                <li><a href="#" class="shipping_menu_active">Cart ></a></li>
                <li><a href="#">Information ></a></li>
                <li><a href="#">Shipping ></a></li>
                <li><a href="#">payment </a></li>
            </ul>
        </div>

        <div class="cart_main">
            <div class="cart_header">
                <div class="alert alert-dark vmh_alert" role="alert" style="display: none;">
                    This is a dark alert—check it out!
                </div>
                <h4>Your Cart</h4>
            </div>

            <div class="cart_single_boxs">
                <?php if ($cartItems) {?>

                <?php foreach ($cartItems as $key => $item) {?>
                <?php load_template(VMH_PATH.'Includes/Templates/single-cart.php', false, $item)?>
                <?php }?>
                <?php }?>
            </div>

            <div class="cart_header cart_header2">
                <h4>Total: <?php echo get_woocommerce_currency_symbol() ?><span class="vmh_bottom_cart_total">
                        <?php echo getTotalCartPrice() ?></span>
                </h4>
            </div>
            <!-- Start Button -->
            <div class="logon_input_btn logon_input_btn2 shipping_address_btn cart_btn">
                <div class="recepes_btn_content">
                    <a href="<?php echo esc_url(site_url('/checkout')) ?>" class="vmh_checkout_btn">Checkout</a>
                </div>
            </div>
            <!-- End Button -->
        </div>
    </div>
</section>
<!--======================== End Cart Page ========================-->

<?php get_footer('footer.php')?>