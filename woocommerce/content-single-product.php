<?php
    /**
     * The template for displaying product content in the single-product.php template
     *
     * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
     *
     * HOWEVER, on occasion WooCommerce will need to update template files and you
     * (the theme developer) will need to copy the new files to your theme to
     * maintain compatibility. We try to do this as little as possible, but it does
     * happen. When this occurs the version of the template file will be bumped and
     * the readme will list any important changes.
     *
     * @version 3.6.0
     *
     *
     *
     * @package WooCommerce\Templates
     *
     * @see     https://docs.woocommerce.com/document/template-structure/
     */

    defined('ABSPATH') || exit;

    global $product;

    if (post_password_required()) {
        echo get_the_password_form(); // WPCS: XSS ok.
        return;
    }
?>
<div id="product-<?php the_ID();?>" <?php wc_product_class('', $product);?>>

    <?php
        /*
         * Hook: woocommerce_before_single_product_summary.
         *
         * @hooked woocommerce_show_product_sale_flash - 10
         * @hooked woocommerce_show_product_images - 20
         */
        do_action('woocommerce_before_single_product_summary');
    ?>
    <!--======================== Start Recepes Page ========================-->
    <div class="container comon_section_area">

        <div class="alert alert-dark vmh_alert" role="alert" style="display: none;">
            This is a dark alert—check it out!
        </div>

        <?php
            /*
             * Hook: woocommerce_before_single_product.
             *
             * @hooked woocommerce_output_all_notices - 10
             */
            do_action('woocommerce_before_single_product');
        ?>

        <!-- Start Background Overly -->
        <!-- <div class="recepes_background_img">
                <img src="<?php echo esc_url(VMH_URL.'Assets/images/thank_you2/circle.png') ?>" alt="images" />
            </div> -->
        <!-- End Background Overly -->
        <?php if ($product->get_id() == get_option('vmh_create_product_option')) {?>

        <div class="userdash_profile mixxer_earning_popup_profile recepes_profile">
            <div class="thank_profile_img">
                <a href="#"><img src="<?php echo esc_url(get_avatar_url(get_current_user_id())) ?>" alt="images" /></a>
            </div>
            <div class="userdash_profile_title">
                <a href="#"><?php echo wp_get_current_user()->display_name ?></a>
                <p><?php echo vmhEscapeTranslate(getUserDescription()) ?></p>
            </div>
        </div>
        <?php }?>

        <div class="recepes_page">

            <div class="recepes_main_content">
                <div class="recepes_left">

                    <!--                                                     <?php if ($product->get_id() != get_option('vmh_create_product_option')) {?>
                        <div class="recepes_title" style="margin-top: -45px">
                            <h4>Similar Recepies</h4>
                        </div>
                        <?php }?> -->

                    <?php
                                                                                    /*
                         * Hook: woocommerce_after_single_product_summary.
                         *
                         * @hooked woocommerce_output_product_data_tabs - 10
                         * @hooked woocommerce_upsell_display - 15
                         * @hooked woocommerce_output_related_products - 20
                         */
                        do_action('woocommerce_after_single_product_summary');
                    ?>

                </div>

                <div class="recepes_right recipes_order recepes_right_no_paddi">
                    <div class="recepes_right_mini">
                        <!-- Start recepes right left -->
                        <div class="recepes_right_site_left">

                            <?php
                                /*
                                 * Hook: woocommerce_single_product_summary.
                                 *
                                 * @hooked woocommerce_template_single_title - 5
                                 * @hooked woocommerce_template_single_rating - 10
                                 * @hooked woocommerce_template_single_price - 10
                                 * @hooked woocommerce_template_single_excerpt - 20
                                 * @hooked woocommerce_template_single_add_to_cart - 30
                                 * @hooked woocommerce_template_single_meta - 40
                                 * @hooked woocommerce_template_single_sharing - 50
                                 * @hooked WC_Structured_Data::generate_product_data() - 60
                                 */
                                do_action('woocommerce_single_product_summary');
                            ?>

                        </div>
                        <!-- Start recepes right right -->
                        <div class="recepes_right_site_right">
                            <?php if (get_the_ID() != get_option('vmh_create_product_option')) {?>
                            <div class="recepes_right_site_right_content">
                                <h6>Note By
                                    <?php echo get_the_author_meta('user_login', get_post(get_the_ID())->post_author); ?>
                                </h6>

                                <p class="vmh_product_content">
                                    <?php echo get_the_content() ?>
                                </p>


                                <div class="vmh_tag_list">
                                    <div class="deafult_tags">
                                        <?php echo getProductTags() ?>
                                    </div>
                                </div>

                            </div>
                            <?php } else {?>
                            <div class="recepes_right_site_right_content">
                                <h6>Note By Creator</h6>
                                <p class="vmh_product_content">
                                    <textarea name="" id="" cols="30" rows="10"
                                        class="focus-visible vmh_recipe_create_note"></textarea>
                                </p>
                            </div>
                            <!-- Start tags -->
                            <div class="recipes_order_tags">
                                <h6>Recipe's tags</h6>



                                <div class="recipes_order_single_tag_plus">
                                    <div class="recipes_order_single_tag">
                                        <!-- Start Tag name type -->
                                        <div class="recepes_tag_type_input recepes_tag1">

                                            <div class="tags_popup_container">
                                                <img class="add_tag_name"
                                                    src="<?php echo esc_url(VMH_URL.'Assets/images/recipes_order/plus.png') ?>"
                                                    alt="images" />

                                                <div class="input_container">

                                                    <div class="tag_input duplicate_tag" style="display: none;">
                                                        <i class="fas fa-times cut_tag"></i>
                                                        <input type="text" placeholder="Type Tag Name"
                                                            class="vmh_tag_input">
                                                    </div>

                                                    <?php editProductTagsInput()?>

                                                </div>

                                                <button class="save_tag_btn">Save Tags</button>

                                            </div>

                                        </div>
                                        <!-- End Tag name type -->
                                    </div>
                                    <div class="recipes_order_single_tag_plus_img">

                                        <a href="">
                                            <i class="fas fa-edit tag_edit_icon"></i>
                                        </a>

                                    </div>
                                </div>
                            </div>

                            <div class="vmh_tag_list">
                                <?php editProductTagsHTML()?>
                            </div>
                            <!-- End Tags -->
                            <?php }?>
                        </div>
                        <!-- End recepes right right -->
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<?php do_action('woocommerce_after_single_product');?>