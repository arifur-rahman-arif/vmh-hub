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
        add_action('admin_init', [$this, 'setProductAttributes']);

        // Register Custom Taxonomy
        add_action('admin_init', [$this, 'generateCustomTaxonomy'], 0);

        // add user commsion on purchase of simple product
        add_action('woocommerce_order_status_completed', [$this, 'addCommisionToUser']);

        add_action('woocommerce_order_status_completed', [$this, 'reduceIngredientsStock']);

        // update post on order status update
        // add_action('save_post', [$this, 'addCommisionToUser'], 10, 1);

        // update post on order status update
        add_action('save_post', [$this, 'sendMailOnProductApprove'], 10, 2);

        add_action('init', [$this, 'sessionStart']);

        // Intitialize custom post type for ingredients
        add_action('init', [$this, 'ingredientsPostType']);

        // Add meta box to control the map zoom option
        add_action('add_meta_boxes_ingredient', [$this, 'registerMetaBox']);
        // Save the post meta on saving the post
        add_action('save_post_ingredient', [$this, 'saveMetaValue'], 10, 2);

        // Add meta box to control the map zoom option
        add_action('add_meta_boxes_product', [$this, 'registerIngredientsMeta'], 10, 1);
        // Save the post meta on saving the post
        add_action('save_post_product', [$this, 'saveIngredientsMetaValue'], 10, 2);

        // Add meta box to control the map zoom option
        add_action('add_meta_boxes_product', [$this, 'registerIngredientsPercentage'], 10, 1);
        // Save the post meta on saving the post
        // add_action('save_post_product', [$this, 'saveIngredientsPercentage'], 10, 2);
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

    }

    // Remove functions that are hooked with functions
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