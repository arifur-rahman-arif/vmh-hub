<?php

namespace VmhHub\Includes\Classes;

use VmhHub\Includes\Classes\AjaxCallbacks;
use VmhHub\Includes\Classes\HookCallbacks;

class Hooks extends HookCallbacks {

    use AjaxCallbacks;

    public function __construct() {
        $this->initHooks();
        $this->ajaxHooks();
        $this->removeHookedFunctions();
    }

    // Load all required hooks in this method
    /**
     * @return mixed
     */
    public function initHooks() {
        add_action('wp_enqueue_scripts', [$this, 'enqueueAssetFiles']);

        add_action('admin_enqueue_scripts', [$this, 'loadAdminFiles']);

        add_action('after_setup_theme', [$this, 'themeInitCallbacks']);

        // add_action('wp_logout', [$this, 'redirectLoginPage']);
        add_action('init', [$this, 'redirectWpLogin']);

        // Add admin menu to control product fields
        add_action('admin_menu', [$this, 'adminMenus']);
        add_action('admin_init', [$this, 'addOptionSettings']);

        // Set vmh product attributes
        add_action('vmh_create_product_attribute_action', [$this, 'setProductAttributes']);

        // Register Custom Taxonomy
        add_action('vmh_create_product_taxonomy_action', [$this, 'generateCustomTaxonomy']);

        // add user commsion on purchase of simple product
        add_action('woocommerce_order_status_completed', [$this, 'addCommisionToUser']);

        // Reduce the ingredients stock on successfull order complete
        // add_action('woocommerce_order_status_completed', [$this, 'reduceIngredientsStock']);

        // update post on order status update
        // add_action('save_post', [$this, 'addCommisionToUser'], 10, 1);

        // update post on order status update
        add_action('save_post', [$this, 'sendMailOnProductApprove'], 10, 2);

        add_action('init', [$this, 'sessionStart']);

        // Intitialize custom post type for ingredients
        add_action('init', [$this, 'ingredientsPostType']);

        // Intitialize custom post type for ingredients
        add_action('init', [$this, 'subscriberPostType']);

        // Add meta box to control the map zoom option
        add_action('add_meta_boxes_ingredient', [$this, 'registerMetaBox']);
        // Save the post meta on saving the post
        add_action('save_post_ingredient', [$this, 'saveMetaValue'], 10, 2);

        // Add meta box to control the map zoom option
        add_action('add_meta_boxes_ingredient', [$this, 'registerIngredientPriceMeta']);
        // Save the post meta on saving the post
        add_action('save_post_ingredient', [$this, 'saveIngredientPriceMeta'], 10, 2);

        // Add meta box to control the map zoom option
        add_action('add_meta_boxes_product', [$this, 'registerIngredientsMeta'], 10, 1);
        // Save the post meta on saving the post
        add_action('save_post_product', [$this, 'saveIngredientsMetaValue'], 10, 2);

        // Add meta box to control the map zoom option
        add_action('add_meta_boxes_product', [$this, 'registerIngredientsPercentage'], 10, 1);

        // Add nicotineshot field in in product cart
        add_action('woocommerce_before_add_to_cart_button', [$this, 'addNicotineshotfield'], 10);

        // Validate custom field ( nicotine shot value & ingredients price )
        // add_action('woocommerce_add_to_cart_validation', [$this, 'validatedCustomField'], 10, 4);

        // Add custom nicotine shot data to cart item
        add_action('woocommerce_add_cart_item_data', [$this, 'addNicotineshotToCart'], 10, 3);

        // Add the nicotine shot price to the cart
        add_action('woocommerce_before_calculate_totals', [$this, 'addNicotineshotprice'], 10);

        // Calculate the cart total (ingredients price + nicotine shot price)
        add_action('woocommerce_cart_calculate_fees', [$this, 'calculateCartTotal']);

        // Add the nicotineshot amount data to order item in admin dashboard
        add_action('woocommerce_checkout_create_order_line_item', [$this, 'addNicotineshotToItem'], 10, 4);

        // Action hook after order details in woocomerce order edit page
        add_action('woocommerce_admin_order_data_after_order_details', [$this, 'displayCalculatedNicotineShot'], 10);

        add_action('woocommerce_save_account_details', [$this, 'saveUserDescriptionField'], 10, 1);

        add_action('woocommerce_save_account_details', [$this, 'redirectToEditAccountPage'], 90, 1);

    }

    public function sessionStart() {
        session_start();
    }

    // Leverage all the wordpress ajax action into this method
    public function ajaxHooks() {
        /* Handle login process */
        add_action('wp_ajax_vmh_login_action', [$this, 'handleLoginProcess']);
        add_action('wp_ajax_nopriv_vmh_login_action', [$this, 'handleLoginProcess']);
        /* Handle create user form process */
        add_action('wp_ajax_vmh_create_user', [$this, 'handleCreateUser']);
        add_action('wp_ajax_nopriv_vmh_create_user', [$this, 'handleCreateUser']);
        /* Add product to user cart page */
        add_action('wp_ajax_vmh_add_product_to_cart', [$this, 'addProductToCart']);
        add_action('wp_ajax_nopriv_vmh_add_product_to_cart', [$this, 'addProductToCart']);
        /* remove product from user cart page */
        add_action('wp_ajax_vmh_remove_product_from_cart', [$this, 'removeProductFromCart']);
        add_action('wp_ajax_nopriv_vmh_remove_product_from_cart', [$this, 'removeProductFromCart']);
        /* Toogle a product to favorite state or unfavorite state */
        add_action('wp_ajax_vmh_toggle_favorite_product', [$this, 'toggleProductFavorite']);
        add_action('wp_ajax_nopriv_vmh_toggle_favorite_product', [$this, 'toggleProductFavorite']);
        /* Create a simple product upon user request */
        add_action('wp_ajax_vmh_create_product', [$this, 'createProduct']);
        add_action('wp_ajax_nopriv_vmh_create_product', [$this, 'createProduct']);

        /* Create a simple product upon user request */
        add_action('wp_ajax_vmh_upload_ingredients', [$this, 'uploadIngredients']);

        /* Create post type for subscriber mail list */
        add_action('wp_ajax_vmh_subscriber_action', [$this, 'createSubscriber']);
        add_action('wp_ajax_nopriv_vmh_subscriber_action', [$this, 'createSubscriber']);

        /* Create post type for subscriber mail list */
        add_action('wp_ajax_vmh_remove_product_action', [$this, 'removeProduct']);
        add_action('wp_ajax_nopriv_vmh_remove_product_action', [$this, 'removeProduct']);

        /* Update nicotineshot value via ajax from cart page */
        add_action('wp_ajax_vmh_update_nicotineshot', [$this, 'updateNicotineshotValue']);
        add_action('wp_ajax_nopriv_vmh_update_nicotineshot', [$this, 'updateNicotineshotValue']);

        /* Update nicotineshot value via ajax from cart page */
        add_action('wp_ajax_vmh_create_product_attribute', [$this, 'createProductAttributes']);
        add_action('wp_ajax_vmh_create_product_taxonomy', [$this, 'createProductTaxonomies']);

        /* Create post type for subscriber mail list */
        add_action('wp_ajax_vmh_get_ingredients_price', [$this, 'calculatedIngredientPrice']);
        add_action('wp_ajax_nopriv_vmh_get_ingredients_price', [$this, 'calculatedIngredientPrice']);

        /* Create post type for subscriber mail list */
        add_action('wp_ajax_vmh_first_time_visit', [$this, 'unsetUserFirstTimeVisit']);
        add_action('wp_ajax_nopriv_vmh_first_time_visit', [$this, 'unsetUserFirstTimeVisit']);
    }

    // Remove functions that are hooked with these hooks
    public function removeHookedFunctions() {
        // Remove woocommerce_show_product_sale_flash function from hook
        remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
        // Remove woocommerce_show_product_images function from hook
        remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
        // // Remove woocommerce_template_single_title function from hook
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
        // Remove woocommerce_template_single_rating function from hook
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
        // Remove woocommerce_template_single_excerpt function from hook
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
    }
}