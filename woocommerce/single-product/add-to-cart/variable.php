<?php
/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 * @version 3.5.5
 * @see https://docs.woocommerce.com/document/template-structure/
 */

defined('ABSPATH') || exit;

global $product;

$btnText = '';
$btnAttribute = '';

if (isset($_GET['edit_product']) && $_GET['edit_product']) {
    if (isset($_GET['update_product']) && $_GET['update_product'] === '1') {
        $btnText = 'Update Recepie';
        $btnAttribute = 'update-recepie';
    } else {
        $btnText = 'Save Recepie';
        $btnAttribute = 'save-recepie';
    }
} else {
    $btnText = 'Add to cart';
    $btnAttribute = 'add-to-cart';
}

// If this product id is not = to production option than display the normal variable product template or else display the create
// product template
if ($product->get_id() != get_option('vmh_create_product_option')) {
    $attribute_keys = array_keys($attributes);
    $variations_json = wp_json_encode($available_variations);
    $variations_attr = function_exists('wc_esc_json') ? wc_esc_json($variations_json) : _wp_specialchars($variations_json,
        ENT_QUOTES, 'UTF-8', true);

    do_action('woocommerce_before_add_to_cart_form');?>



<div class="recepes_right_left_input_code">
    <div class="recepes_right_left_input_code_header">
        <h5>Ingredients</h5>
    </div>

    <?php echo singleProductIngredientsHTML(get_the_ID()) ?>

    <!-- End Single ingredient item -->

    <div class="recepes_choose_option">

        <form class="variations_form cart"
            action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>"
            method="post" enctype='multipart/form-data' data-product_id="<?php echo absint($product->get_id()); ?>"
            data-product_variations="<?php echo $variations_attr; // WPCS: XSS ok.                                                                                                                                                                                                                                ?>">
            <?php do_action('woocommerce_before_variations_form');?>

            <?php if (empty($available_variations) && false !== $available_variations): ?>
            <p class="stock out-of-stock">
                <?php echo esc_html(apply_filters('woocommerce_out_of_stock_message', __('This product is currently out of stock and unavailable.', 'woocommerce'))); ?>
            </p>
            <?php else: ?>
            <table class="variations" cellspacing="0">
                <tbody>
                    <?php foreach ($attributes as $attribute_name => $options): ?>
                    <?php
$attributeLabel = '';
    if (!preg_match('/\:/i', wc_attribute_label($attribute_name))) {
        $attributeLabel = wc_attribute_label($attribute_name) . ' :';
    } else {
        $attributeLabel = wc_attribute_label($attribute_name);
    }
    ?>
                    <tr>
                        <td class="label"><label
                                for="<?php echo esc_attr(sanitize_title($attribute_name)); ?>"><?php echo $attributeLabel; ?>
                            </label>
                        </td>
                        <td class="value vmh_variable_select">
                            <?php
wc_dropdown_variation_attribute_options(
        array(
            'options'   => $options,
            'attribute' => $attribute_name,
            'product'   => $product
        )
    );
    echo end($attribute_keys) === $attribute_name ? wp_kses_post(apply_filters('woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__('Clear', 'woocommerce') . '</a>')) : '';
    ?>
                        </td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>


            <!-- Display the nicotine shot calculation here -->
            <!-- <div class="nicotine_shot mb-3">
                <strong class="shot_name">Nicotine Shot:</strong>
                <strong class="shot_amount"></strong><b>ml</b>
            </div> -->
            <!-- End of nicotine shot -->


            <div class="single_variation_wrap">
                <?php
/*
     * Hook: woocommerce_before_single_variation.
     */
    do_action('woocommerce_before_single_variation');

/*
 * Hook: woocommerce_single_variation. Used to output the cart button and placeholder for variation data.
 *
 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
 * @since 2.4.0
 */
    do_action('woocommerce_single_variation');

/*
 * Hook: woocommerce_after_single_variation.
 */
    do_action('woocommerce_after_single_variation');
    ?>
            </div>
            <?php endif;?>

            <?php do_action('woocommerce_after_variations_form');?>
        </form>

    </div>

</div>



<?php
do_action('woocommerce_after_add_to_cart_form');

} else {?>


<!-- =========================================
Customize here
========================================= -->


<?php
$attribute_keys = array_keys($attributes);
    $variations_json = wp_json_encode($available_variations);
    $variations_attr = function_exists('wc_esc_json') ? wc_esc_json($variations_json) : _wp_specialchars($variations_json,
        ENT_QUOTES, 'UTF-8', true);

    do_action('woocommerce_before_add_to_cart_form');?>

<div class="recepes_right_left_input_code">

    <div class="recepes_right_left_input_code_header mt-3">
        <h5>Ingredients</h5>
    </div>


    <div class="ingredients_container">

        <!-- ==================== -->
        <!-- root element for copy -->
        <!-- ==================== -->

        <!-- Start Single ingredient item -->
        <div class="ingredients_wrapper" id="ingredients_wrapper_0" style="display: none;">

            <i class="fas fa-times cut_selectbox"></i>

            <select name="product_ingredients" style="width: 300px" class="product_ingredients"
                id="product_ingredients_0">
                <?php echo getAllIngredients() ?>
            </select>

            <input required type="number" min="0" max="30" name="ingredient_percentage" class="ingredient_percentage">

            <img class="add_ingredients_icon"
                src="<?php echo esc_url(VMH_URL . 'Assets/images/recipes_order/plus.png') ?>" width="50px" height="50px"
                alt="images" />
        </div>

        <!-- End Single ingredient item -->



        <!-- if edit_product is set to browser url then hide this section -->
        <?php if (!(isset($_GET['edit_product']) && $_GET['edit_product'])) {?>

        <!-- Start Single ingredient item -->
        <div class="ingredients_wrapper" id="ingredients_wrapper_1">

            <select name="product_ingredients" style="width: 300px" class="product_ingredients"
                id="product_ingredients_1">
                <?php echo getAllIngredients() ?>
            </select>

            <input required type="number" min="0" max="30" name="ingredient_percentage" class="ingredient_percentage"
                placeholder="5%">

            <img class="add_ingredients_icon"
                src="<?php echo esc_url(VMH_URL . 'Assets/images/recipes_order/plus.png') ?>" width="50px" height="50px"
                alt="images" />
        </div>
        <!-- End Single ingredient item -->

        <?php }?>

        <?php echo getIngredientsOnProductEdit() ?>

    </div>


    <div class="recepes_choose_option create_recipe_option">

        <form class="variations_form cart"
            action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>"
            method="post" enctype='multipart/form-data' data-product_id="<?php echo absint($product->get_id()); ?>"
            data-product_variations="<?php echo $variations_attr; // WPCS: XSS ok.                                                                                                                                                                                                                                ?>">
            <?php do_action('woocommerce_before_variations_form');?>

            <?php if (empty($available_variations) && false !== $available_variations): ?>
            <p class="stock out-of-stock">
                <?php echo esc_html(apply_filters('woocommerce_out_of_stock_message', __('This product is currently out of stock and unavailable.', 'woocommerce'))); ?>
            </p>
            <?php else: ?>
            <table class="variations" cellspacing="0">
                <tbody>
                    <?php foreach ($attributes as $attribute_name => $options): ?>
                    <tr>
                        <td class="label"><label
                                for="<?php echo esc_attr(sanitize_title($attribute_name)); ?>"><?php echo wc_attribute_label($attribute_name) . ' :'; ?>
                            </label>
                        </td>
                        <td class="value vmh_variable_select">
                            <?php
wc_dropdown_variation_attribute_options(
        array(
            'options'   => $options,
            'attribute' => $attribute_name,
            'product'   => $product
        )
    );
    echo end($attribute_keys) === $attribute_name ? wp_kses_post(apply_filters('woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__('Clear', 'woocommerce') . '</a>')) : '';
    ?>
                        </td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>

            <div class="single_variation_wrap">

                <!-- Show custom price box -->

                <div class="price_box">
                    <input type="hidden" id="created_recipe_id">
                    <input type="hidden" id="vmh_variation_id">
                    <!-- <span class="symbol"><?php echo get_woocommerce_currency_symbol() ?></span>
                    <span class="price"></span> -->
                </div>
                <?php

/*
 * Hook: woocommerce_before_single_variation.
 */
    do_action('woocommerce_before_single_variation');

/*
 * Hook: woocommerce_single_variation. Used to output the cart button and placeholder for variation data.
 *
 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
 * @since 2.4.0
 */
    do_action('woocommerce_single_variation');

/*
 * Hook: woocommerce_after_single_variation.
 */
    // do_action('woocommerce_after_single_variation');
    ?>
            </div>
            <?php endif;?>

            <?php do_action('woocommerce_after_variations_form');?>

            <input type="hidden" name="nicotine_shot_value" id="nicotine_shot_value">
        </form>

    </div>

    <button class="vmh_button vmh_save_recipe_btn recipie_create_next_btn" data-action="save-recepie">
        Save Recipe
    </button>

    <!-- Start Button -->
    <div class="logon_input_btn logon_input_btn2 shipping_address_btn recepes_btn">
        <div class="recepes_btn_content recipes_order_btn_content">
            <button class="vmh_button save_update_add_to_cart_btn" data-action="<?php echo $btnAttribute ?>">
                <?php echo $btnText ?>
            </button>
            <br>
            <a class="vmh_discard_recipe" href="<?php echo esc_url(get_permalink(get_the_ID())) ?>">Discard</a>

        </div>
    </div>
    <!-- End Button -->


    <!-- Start Save Recieved Popup -->
    <div class="subscribe_mail_popup save_recieved_hde vmh_create_recipe_popup">
        <div class="subscribe_mail_popup_header">
            <img class="vmh_checkbox_image" src="<?php echo esc_url(VMH_URL . 'Assets/images/check.png') ?>"
                alt="images">
            <img class="vmh_checkbox_image_warning" style="display: none;"
                src="<?php echo esc_url(VMH_URL . 'Assets/images/warning.png') ?>" alt="images">
            <div class="vmh_alert_text"
                style="text-align: center;font-size: 20px;font-weight: bolder;word-break: break-word; padding: 10px">
                Recepie
                saved</div>
        </div>
        <div class="subscribe_hide_icon">
            <a href="#"><img src="<?php echo esc_url(VMH_URL . 'Assets/images/subscribe_hide.png') ?>" alt="images"></a>
        </div>
    </div>
    <!-- End Save Recieved Popup -->


</div>


<?php }?>