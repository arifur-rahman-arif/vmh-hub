<?php get_header('header.php')?>

<?php $cartItems = getCartItems();?>

<?php $combinedNicotineShot = getCalculatedNicotineShots($cartItems)?>

<!--======================== Start Cart Page ========================-->
<section class="login_main create_account shopping_method_main_padd">
    <div class="container">
        <!-- Start Background Overly -->
        <div class="shipping_background">
            <img src="<?php echo VMH_URL . 'Assets/images/shipping_address/circle.png' ?>" alt="images" />
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
                <?php $item['cart_key'] = $key?>
                <input type="hidden" name="cart_key" class="cart_key" data-key="<?php echo esc_attr($key) ?>">
                <?php load_template(VMH_PATH . 'Includes/Templates/single-cart.php', false, $item)?>
                <?php }?>
                <?php }?>
            </div>

            <?php $freebaseSaltCalculatedValue = $combinedNicotineShot['freebase-nicotine']['calculatedValue']?>
            <?php $freebaseSalt = $combinedNicotineShot['freebase-nicotine']['shotValue']?>
            <?php $freebaseTypeCount = $combinedNicotineShot['freebase-nicotine']['typeCount']?>

            <?php $nicotineSaltCalculatedValue = $combinedNicotineShot['nicotine-salt']['calculatedValue']?>
            <?php $nicotineSalt = $combinedNicotineShot['nicotine-salt']['shotValue']?>
            <?php $nicotineSaltTypeCount = $combinedNicotineShot['nicotine-salt']['typeCount']?>

            <?php if ($freebaseSalt || $freebaseSaltCalculatedValue) {?>

            <div class="card vmh_nicotine_shot_card">
                <div class="card-body">
                    <label>Freebase Nicotine:&nbsp;(ml)</label>

                    <div class="input_container">
                        <input type="hidden" class="cart_nicotine_shot_hidden_value" min="0"
                            max="<?php echo esc_attr($freebaseSalt) ?>"
                            value="<?php echo esc_attr($freebaseSaltCalculatedValue) ?>">
                        <input type="number" class="cart_nicotine_shot_value" min="0"
                            max="<?php echo esc_attr($freebaseSaltCalculatedValue) ?>" step="10"
                            value="<?php echo esc_attr($freebaseSalt) ?>" data-type="Freebase Nicotine">
                        <i class="far fa-save nicotineshot_save_btn" data-type="freebase-nicotine"
                            data-type-count="<?php echo esc_attr($freebaseTypeCount) ?>"></i>
                    </div>

                    <span>Price:
                        <?php echo get_woocommerce_currency_symbol() ?>
                        <span class="calculatedPrice"><?php echo getIndividualShotPrice($freebaseSalt) ?></span>
                    </span>

                </div>
            </div>

            <?php }?>


            <?php if ($nicotineSalt || $nicotineSaltCalculatedValue) {?>

            <div class="card vmh_nicotine_shot_card">
                <div class="card-body">
                    <label>Nicotine Salt:&nbsp;(ml)</label>
                    <div class="input_container">
                        <input type="hidden" class="cart_nicotine_shot_hidden_value" min="0"
                            max="<?php echo esc_attr($nicotineSalt) ?>"
                            value="<?php echo esc_attr($nicotineSaltCalculatedValue) ?>">
                        <input type="number" class="cart_nicotine_shot_value" min="0"
                            max="<?php echo esc_attr($nicotineSaltCalculatedValue) ?>" step="10"
                            value="<?php echo esc_attr($nicotineSalt) ?>" data-type="Nicotine Salt">
                        <i class="far fa-save nicotineshot_save_btn" data-type="nicotine-salt"
                            data-type-count="<?php echo esc_attr($nicotineSaltTypeCount) ?>"></i>
                    </div>
                    <span>Price:
                        <?php echo get_woocommerce_currency_symbol() ?>
                        <span class="calculatedPrice"><?php echo getIndividualShotPrice($nicotineSalt) ?></span>
                    </span>

                </div>
            </div>

            <?php }?>

            <div class="cart_header cart_header2 vmh_cart_total_container">
                <input type="hidden" id="hidden_total_price" value="<?php echo getTotalCartPrice() ?>">
                <h4>Total: <?php echo get_woocommerce_currency_symbol() ?><span class="vmh_bottom_cart_total">
                        <?php echo getTotalCartPrice() ?></span>
                </h4>
            </div>
            <!-- Start Button -->
            <div class="logon_input_btn logon_input_btn2 shipping_address_btn cart_btn" style="margin-top: 10px;">
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