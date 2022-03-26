<?php

namespace VmhHub\Includes\Classes;

class HookCallbacks {

    public function enqueueAssetFiles() {
        $this->loadStyles();
        $this->loadScripts();
        $this->localizeFile();
    }

    // Load all the css files here
    public function loadStyles() {
        wp_enqueue_style('vmh-googleFont', '//fonts.googleapis.com/css2?family=Montserrat:wght@800&family=Open+Sans:wght@300;400;600;700;800&display=swap');
        wp_enqueue_style('vmh-font', VMH_URL.'Assets/Fonts/font.css', [], VMH_VERSION, 'all');
        wp_enqueue_style('vmh-normalize', VMH_URL.'Assets/css/normalize.css', [], VMH_VERSION, 'all');
        wp_enqueue_style('vmh-bootstrap', VMH_URL.'Assets/css/bootstrap.min.css', [], VMH_VERSION, 'all');
        wp_enqueue_style('vmh-fontawesome', '//pro.fontawesome.com/releases/v5.10.0/css/all.css');
        wp_enqueue_style('vmh-slim-select', VMH_URL.'Assets/css/slimselect.min.css', [], VMH_VERSION, 'all');
        wp_enqueue_style('vmh-style', VMH_URL.'Assets/css/style.css', [], VMH_VERSION, 'all');
        wp_enqueue_style('vmh-responsive', VMH_URL.'Assets/css/responsive.css', [], VMH_VERSION, 'all');
        wp_enqueue_style('vmh-datepickercss', VMH_URL.'Assets/scripts/datetimepicker/build/jquery.datetimepicker.min.css', [], VMH_VERSION, 'all');
        wp_enqueue_style('vmh-custom', VMH_URL.'Assets/css/custom.css', [], VMH_VERSION, 'all');
        wp_enqueue_style('vmh-themeCss', get_stylesheet_uri());
    }

    // Load all the javascript files here
    public function loadScripts() {
        wp_enqueue_script('jquery');
        wp_enqueue_script('vmh-modernizr', VMH_URL.'Assets/scripts/modernizr-3.11.2.min.js', [], VMH_VERSION, true);
        wp_enqueue_script('vmh-slim-select', VMH_URL.'Assets/scripts/slimselect.min.js', ['jquery'], VMH_VERSION, false);
        wp_enqueue_script('vmh-popper', VMH_URL.'Assets/scripts/popper.min.js', [], VMH_VERSION, true);
        wp_enqueue_script('vmh-bootstrap', VMH_URL.'Assets/scripts/bootstrap.min.js', [], VMH_VERSION, true);
        wp_enqueue_script('vmh-custom', VMH_URL.'Assets/scripts/custom.js', ['jquery'], VMH_VERSION, true);
        wp_enqueue_script('vmh-datepicker', VMH_URL.'Assets/scripts/datetimepicker/build/jquery.datetimepicker.full.min.js', ['jquery'], VMH_VERSION, true);
        wp_enqueue_script('vmh-sweetalert', VMH_URL.'Assets/scripts/sweetalert.js', [], VMH_VERSION, true);
        wp_enqueue_script('vmh-main', VMH_URL.'Assets/scripts/main.js', ['jquery'], VMH_VERSION, true);
    }

    // Localize javascript files
    public function localizeFile() {
        wp_localize_script('vmh-main', 'vmhLocal', [
            'ajaxUrl'                  => admin_url('admin-ajax.php'),
            'vmhProductAttributes'     => vmhProductAttributes(),
            'hideNicotineValue'        => esc_html(get_option('vmh_hide_nicotine')),
            'nicotineShotPer10mlPrice' => NICOTINE_SHOT_PRICE,
            'currencySymbol'           => get_woocommerce_currency_symbol(),
            'siteUrl'                  => site_url('/'),
            'templateProductUrl'       => get_permalink(get_option('vmh_create_product_option'))
        ]);
    }

    // Load admin asset files
    public function loadAdminFiles() {
        $this->loadAdminScripts();
        $this->loadAdminStyles();
    }

    public function loadAdminScripts() {
        wp_enqueue_script('vmh-admin-select', VMH_URL.'Assets/scripts/slimselect.min.js', ['jquery'], VMH_VERSION, true);
        wp_enqueue_script('vmh-admin', VMH_URL.'Assets/scripts/admin.js', ['jquery', 'vmh-admin-select'], VMH_VERSION, true);
        wp_localize_script('vmh-admin', 'vmhLocal', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'siteUrl' => site_url('/')
        ]);
    }

    public function loadAdminStyles() {
        wp_enqueue_style('vmh-select', VMH_URL.'Assets/css/slimselect.min.css', [], VMH_VERSION, 'all');
        wp_enqueue_style('vmh-admin', VMH_URL.'Assets/css/admin.css', [], VMH_VERSION, 'all');
    }

    // These are the callback functions after theme initialization
    public function themeInitCallbacks() {
        $this->themeSupport();
        $this->registerMenus();
    }

    // Add all required theme support for wordpress
    public function themeSupport() {
        add_theme_support('menus');
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('custom-background');
        add_theme_support('custom-header');
        add_theme_support('custom-logo');
        add_theme_support('automatic-feed-links');
        add_theme_support('html5', array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script'));
        add_theme_support('customize-selective-refresh-widgets');
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }

    public static function registerMenus() {
        register_nav_menus(array(
            'headerMenu' => __('Header Menu'),
            'footerMenu' => __('Footer Menu')
        ));
    }

    // Redirect the user to custom login page
    public function redirect_to_login() {
        wp_redirect(site_url('/login'));
        exit;
    }

    // Redirect wp login page to custom login page
    public function redirectWpLogin() {
        global $pagenow;

        if ($pagenow === "wp-login.php") {
            wp_redirect(site_url('/login'));
            wp_logout();
            exit;
        }
    }

    // Redirect the subscriber to home page
    public function redirectSubscriber() {
        if (get_userdata(get_current_user_id())->roles[0] == 'subscriber') {
            wp_redirect(site_url('/'));
            exit;
        }
    }

    // Registering admin menus to control product options
    public function adminMenus() {
        add_menu_page(
            __('VMH Options', 'sheetstowptable'),
            __('VMH Options', 'sheetstowptable'),
            'manage_options',
            'vmh-product-options',
            [$this, 'adminPage'],
            'dashicons-tickets',
            5
        );
        // add_submenu_page(
        //     'vmh-product-options',
        //     __('Dashboard', 'sheetstowptable'),
        //     __('Dashboard', 'sheetstowptable'),
        //     'manage_options',
        //     'gswpts-dashboard',
        //     [$this, 'adminPage']
        // );
    }

    // Load admin page in the backend of this website
    public static function adminPage() {
        load_template(VMH_PATH.'Includes/Templates/admin-menu.php', true);
    }

    // Register settings for product options
    public function addOptionSettings() {

        $settingsKey = vmhProductAttributes();

        foreach ($settingsKey as $key => $settingKey) {
            register_setting(
                'vmh_options_key',
                $key
            );
        }

        // ============================
        // Register additional settings
        // ============================

        // Register create product options ID
        register_setting(
            'vmh_options_key',
            'vmh_create_product_option'
        );
        // Register product commsion for user
        register_setting(
            'vmh_options_key',
            'vmh_product_commission'
        );

        // Register main admin for this site
        register_setting(
            'vmh_options_key',
            'vmh_main_admin'
        );

        // Register hiding option for nicotine shot
        register_setting(
            'vmh_options_key',
            'vmh_hide_nicotine'
        );

        // Register price of 10ml nicotine shot
        register_setting(
            'vmh_options_key',
            'vmh_10ml_nicotineshot_price'
        );

        // Register constant price for 10ml bottle size
        register_setting(
            'vmh_options_key',
            'vmh_10ml_bottle_size_price'
        );

        // Register constant price for 50ml bottle size
        register_setting(
            'vmh_options_key',
            'vmh_50ml_bottle_size_price'
        );

        // ============================
        // End of additional settings
        // ============================

        add_settings_section(
            'vmh_settings_section_id',
            'Product Options',
            null,
            'vmh-product-options'
        );
        add_settings_field(
            'vmh_settings_field_id',
            "",
            [$this, 'optionsFieldHTML'],
            'vmh-product-options',
            'vmh_settings_section_id'
        );
    }

    // Display the html of of product admin menu option page
    public function optionsFieldHTML() {
        load_template(VMH_PATH.'Includes/Templates/options-settings.php');
    }

    // Set new attribute to woocommerce product attributes
    /**
     * @return null
     */
    public function setProductAttributes() {

        $attributes = wc_get_attribute_taxonomies();

        $settingsKey = vmhProductAttributes();

        $slugs = wp_list_pluck($attributes, 'attribute_name');

        if (!$settingsKey) {
            return;
        }

        foreach ($settingsKey as $key => $setting) {

            // if current attribute array is not in saved attributes than create a new one
            if (!in_array($key, $slugs)) {

                $args = array(
                    'slug'         => $key,
                    'name'         => __($setting, 'vmh-hub'),
                    'type'         => 'select',
                    'orderby'      => 'menu_order',
                    'has_archives' => false
                );

                wc_create_attribute($args);
            }
        }
    }

    // Generate custom taxonomy for custom product attributes
    /**
     * @return null
     */
    public function generateCustomTaxonomy() {

        $settingsKey = vmhProductAttributes();

        if (!$settingsKey) {
            return;
        }

        foreach ($settingsKey as $key => $setting) {
            $settingsValues = get_option($key);

            if ($settingsValues) {
                $this->saveTaxonomyValues($settingsValues, $key);
            }
        }

    }

    // Save attribute values for custom taxonomy
    /**
     * @param  $values
     * @return null
     */
    public function saveTaxonomyValues(string $values, string $taxonomy) {
        if (!$values) {
            return;
        }

        $splitedValues = explode('|', $values);

        if (!$splitedValues) {
            return;
        }

        foreach ($splitedValues as $key => $value) {
            $response = wp_insert_term(
                $value, // the term
                'pa_'.$taxonomy.'', // the taxonomy
                [
                    'slug' => $value
                ]
            );

        }

    }

    /**
     * Add commission to user profile
     * @param $orderID
     */
    public function addCommisionToUser($orderID) {

        $order = wc_get_order($orderID);
        if (!$order) {
            return;
        }
        $items = $order->get_items();

        if (!$items) {
            return;
        }

        foreach ($items as $item) {
            $productID = $item->get_product_id();
            $total = $item->get_total();
            $product = wc_get_product($productID);

            if (!$product) {
                return;
            }

            $originalProductID = (int) get_post_meta($productID, 'original_product_id', true);

            $userID = $originalProductID ? get_post($originalProductID)->post_author : get_post($productID)->post_author;

            $user = get_userdata($userID);
            // Get all the user roles as an array.
            $user_roles = $user->roles;
            // Check if the role you're interested in, is present in the array.
            if (in_array('subscriber', $user_roles) && $order->get_status() === 'completed') {

                // $originalShotValue = (float) $item->get_meta('_nicotine_shot_value');

                // $shotValue = (ceil($originalShotValue / 10) * 10);

                // $nicotineShotPrice = (float) getIndividualShotPrice($shotValue) * $item->get_quantity();

                $total = (float) $item->get_total();

                $percentageValue = $this->getTotalPercentage($total);

                $oldCommission = get_user_meta($userID, 'user_commission', true) ? get_user_meta($userID, 'user_commission', true) : 0;

                $newCommission = (float) $percentageValue + (float) $oldCommission;

                update_user_meta($userID, 'user_commission', $newCommission);

                $this->sendCommissionMailToCreator([
                    'postID'     => $productID,
                    'user'       => $user,
                    'commission' => $percentageValue
                ]);
            }
        }
    }

    /**
     * @param string  $mail
     * @param $user
     */
    public function sendCommissionMailToCreator($args) {

        if (!isset($args['postID']) || !isset($args['user']) || !isset($args['commission'])) {
            return;
        }

        $user = $args['user'];
        $postID = $args['postID'];
        $post = get_post($postID);
        $productCommissionPercentage = get_option('vmh_product_commission');
        $commission = $args['commission'];

        $to = $user->data->user_email;
        $displayName = $user->data->display_name;
        $subject = 'Congratulations! You have earned a commission';

        $message = '
                    Hello '.$displayName.' you have got '.$productCommissionPercentage.'% a commission for <b>'.$post->post_title.'</b> <br/>
                    <b>Commission Value:</b> '.get_woocommerce_currency_symbol().' '.$commission.'
                ';
        $headers = ['Content-Type: text/html; charset=UTF-8'];
        wp_mail($to, $subject, $message, $headers);
    }

    /**
     * Reduce the ingredients stock on successfull purchase
     * @param  $orderID
     * @return null
     */
    public function reduceIngredientsStock($orderID) {

        $order = wc_get_order($orderID);

        if (!$order) {
            return;
        }
        $items = $order->get_items();

        if (!$items) {
            return;
        }

        foreach ($items as $itemID => $item) {
            $productID = $item->get_product_id();

            $productIngredients = get_post_meta($productID, 'product_ingredients', true);
            $ingredientsPercentageValues = get_post_meta($productID, 'ingredients_percentage_values', true);

            if (!$productIngredients) {
                return;
            }

            $bottleSize = wc_get_order_item_meta($itemID, 'pa_vmh_bottle_size', true);

            $bottleSizeInteger = null;

            if ($bottleSize == '10ml') {
                $bottleSizeInteger = 10;
            }

            if ($bottleSize == '50ml') {
                $bottleSizeInteger = 50;
            }

            if (!$bottleSizeInteger) {
                continue;
            }

            foreach ($productIngredients as $key => $ingredientID) {
                $ingredientStock = (float) get_post_meta($ingredientID, 'ingredients_stock', true);

                $ingredientPercentage = $ingredientsPercentageValues[$key];

                $stockValue = (float) $bottleSizeInteger * ((float) $ingredientPercentage / 100);

                $newStock = $ingredientStock - $stockValue;

                update_post_meta($ingredientID, 'ingredients_stock', $newStock);
            }
        }

    }

    /**
     * Get the percentage of total
     * @param  $total
     * @return mixed
     */
    public function getTotalPercentage($total) {
        $productCommissionPercentage = get_option('vmh_product_commission');

        if (!$productCommissionPercentage) {
            return $total;
        }

        $percentageValue = ($productCommissionPercentage / 100) * $total;
        return $percentageValue;
    }

    /**
     * Send mail to product author on approve by admin
     * @param $postID
     * @param $post
     */
    public function sendMailOnProductApprove($postID, $post) {
        if (get_post_type($postID) == 'product' && wc_get_product($postID)->get_type() == 'simple') {
            $userID = get_post($postID)->post_author;
            $user = get_userdata($userID);
            // Get all the user roles as an array.
            $user_roles = $user->roles;
            if (in_array('subscriber', $user_roles)) {
                $postStatus = get_post_status($postID);
                if ($postStatus == 'publish') {
                    $to = $user->data->user_email;
                    $displayName = $user->data->display_name;
                    $subject = 'Your newly created product is approved';
                    $message = '
                        Hello '.$displayName.' your product '.$post->post_title.' is now approved my admin. <a href="'.get_permalink($postID).'">Click Here</a> to view your product
                    ';
                    $headers = ['Content-Type: text/html; charset=UTF-8'];
                    wp_mail($to, $subject, $message, $headers);
                }
            }
        }
    }

    // Register custom post type for ingredients
    public function ingredientsPostType() {
        $labels = array(
            'name'                  => _x('Ingredients', 'Post Type General Name', 'vmh'),
            'singular_name'         => _x('Ingredient', 'Post Type Singular Name', 'vmh'),
            'menu_name'             => _x('Ingredients', 'Admin Menu text', 'vmh'),
            'name_admin_bar'        => _x('Ingredient', 'Add New on Toolbar', 'vmh'),
            'archives'              => __('Ingredient Archives', 'vmh'),
            'attributes'            => __('Ingredient Attributes', 'vmh'),
            'parent_item_colon'     => __('Parent Ingredient:', 'vmh'),
            'all_items'             => __('All Ingredients', 'vmh'),
            'add_new_item'          => __('Add New Ingredient', 'vmh'),
            'add_new'               => __('Add New', 'vmh'),
            'new_item'              => __('New Ingredient', 'vmh'),
            'edit_item'             => __('Edit Ingredient', 'vmh'),
            'update_item'           => __('Update Ingredient', 'vmh'),
            'view_item'             => __('View Ingredient', 'vmh'),
            'view_items'            => __('View Ingredients', 'vmh'),
            'search_items'          => __('Search Ingredient', 'vmh'),
            'not_found'             => __('Not found', 'vmh'),
            'not_found_in_trash'    => __('Not found in Trash', 'vmh'),
            'featured_image'        => __('Featured Image', 'vmh'),
            'set_featured_image'    => __('Set featured image', 'vmh'),
            'remove_featured_image' => __('Remove featured image', 'vmh'),
            'use_featured_image'    => __('Use as featured image', 'vmh'),
            'insert_into_item'      => __('Insert into Ingredient', 'vmh'),
            'uploaded_to_this_item' => __('Uploaded to this Ingredient', 'vmh'),
            'items_list'            => __('Ingredients list', 'vmh'),
            'items_list_navigation' => __('Ingredients list navigation', 'vmh'),
            'filter_items_list'     => __('Filter Ingredients list', 'vmh')
        );
        $args = array(
            'label'               => __('Ingredient', 'vmh'),
            'description'         => __('', 'vmh'),
            'labels'              => $labels,
            'menu_icon'           => 'dashicons-archive',
            'supports'            => array('title', 'custom-fields'),
            'taxonomies'          => array(),
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 5,
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => true,
            'hierarchical'        => false,
            'exclude_from_search' => false,
            'show_in_rest'        => true,
            'publicly_queryable'  => true,
            'capability_type'     => 'post'
        );
        register_post_type('ingredient', $args);
    }

    // Register custom post type for subscriber
    public function subscriberPostType() {
        // Register Custom Post Type Subscriber
        $labels = array(
            'name'                  => _x('Subscribers', 'Post Type General Name', 'textdomain'),
            'singular_name'         => _x('Subscriber', 'Post Type Singular Name', 'textdomain'),
            'menu_name'             => _x('Subscribers', 'Admin Menu text', 'textdomain'),
            'name_admin_bar'        => _x('Subscriber', 'Add New on Toolbar', 'textdomain'),
            'archives'              => __('Subscriber Archives', 'textdomain'),
            'attributes'            => __('Subscriber Attributes', 'textdomain'),
            'parent_item_colon'     => __('Parent Subscriber:', 'textdomain'),
            'all_items'             => __('All Subscribers', 'textdomain'),
            'add_new_item'          => __('Add New Subscriber', 'textdomain'),
            'add_new'               => __('Add New', 'textdomain'),
            'new_item'              => __('New Subscriber', 'textdomain'),
            'edit_item'             => __('Edit Subscriber', 'textdomain'),
            'update_item'           => __('Update Subscriber', 'textdomain'),
            'view_item'             => __('View Subscriber', 'textdomain'),
            'view_items'            => __('View Subscribers', 'textdomain'),
            'search_items'          => __('Search Subscriber', 'textdomain'),
            'not_found'             => __('Not found', 'textdomain'),
            'not_found_in_trash'    => __('Not found in Trash', 'textdomain'),
            'featured_image'        => __('Featured Image', 'textdomain'),
            'set_featured_image'    => __('Set featured image', 'textdomain'),
            'remove_featured_image' => __('Remove featured image', 'textdomain'),
            'use_featured_image'    => __('Use as featured image', 'textdomain'),
            'insert_into_item'      => __('Insert into Subscriber', 'textdomain'),
            'uploaded_to_this_item' => __('Uploaded to this Subscriber', 'textdomain'),
            'items_list'            => __('Subscribers list', 'textdomain'),
            'items_list_navigation' => __('Subscribers list navigation', 'textdomain'),
            'filter_items_list'     => __('Filter Subscribers list', 'textdomain')
        );
        $args = array(
            'label'               => __('Subscriber', 'textdomain'),
            'description'         => __('', 'textdomain'),
            'labels'              => $labels,
            'menu_icon'           => '',
            'supports'            => array('title'),
            'taxonomies'          => array(),
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-email',
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => true,
            'hierarchical'        => false,
            'exclude_from_search' => false,
            'show_in_rest'        => true,
            'publicly_queryable'  => true,
            'capability_type'     => 'post'
        );
        register_post_type('subscriber', $args);
    }

    public function registerMetaBox() {
        add_meta_box(
            'ingredients_stock',
            'Ingredients Stock Quantity',
            [$this, 'metaBoxHTML'],
            ['ingredient'],
            'normal',
            'high'
        );
    }

    /**
     * @param $post
     */
    public function metaBoxHTML($post) {
        wp_nonce_field('vmh_ingredients_stock_action', 'vmh_ingredients_nonce');
        $metaValue = get_post_meta($post->ID, 'ingredients_stock', true);

        if ($metaValue == 0) {
            $metaValue = '0';
        }

        echo '
            <div>
                <strong>
                    <label for="ingredients_stock">Stock Quantity/ml :</label>
                    <br/>
                </strong>
                <br />
                <input type="text" name="ingredients_stock" id="ingredients_stock" value="'.$metaValue.'"/>
            </div>
       ';
    }

    /**
     * @param  int     $postID
     * @param  object  $postObject
     * @return mixed
     */
    public function saveMetaValue(int $postID, object $postObject) {
        if (!isset($_POST['vmh_ingredients_nonce']) || !wp_verify_nonce($_POST['vmh_ingredients_nonce'], 'vmh_ingredients_stock_action')) {
            return $postID;
        }

        /* Does current user have capabitlity to edit post */
        $postType = get_post_type_object($postObject->post_type);

        if (!current_user_can($postType->cap->edit_post, $postID)) {
            return $postID;
        }

        /* Get the posted data and check it for uses. */
        $new_meta_value = (isset($_POST['ingredients_stock']) ? $_POST['ingredients_stock'] : "");

        /* Get the meta key. */
        $meta_key = 'ingredients_stock';

        /* Get the meta value of the custom field key. */
        // $meta_value = get_post_meta($postID, $meta_key, true);

        update_post_meta($postID, $meta_key, $new_meta_value);

    }

    // Register a dropdown meta field for product post type
    /**
     * @param $postType
     * @param $post
     */
    public function registerIngredientsMeta($post) {
        if ($post->ID != get_option('vmh_create_product_option')) {
            add_meta_box(
                'product_ingredients',
                'Product Ingredients',
                [$this, 'productIngredientsHTML'],
                ['product'],
                'normal',
                'high'
            );
        }
    }

    // Register a dropdown meta field for product post type for ingredients percentage
    /**
     * @param $postType
     * @param $post
     */
    public function registerIngredientsPercentage($post) {
        if ($post->ID != get_option('vmh_create_product_option')) {

            $metaValue = get_post_meta($post->ID, 'ingredients_percentage_values', true);

            if (!$metaValue) {
                return;
            }

            add_meta_box(
                'product_ingredients_percentage',
                'Product Ingredients Percentage',
                [$this, 'productIngredientsPercentageHTML'],
                ['product'],
                'normal',
                'high'
            );
        }
    }

    // The html of product ingredeints
    /**
     * @param $post
     */
    public function productIngredientsHTML($post) {
        wp_nonce_field('vmh_product_ingredients_action', 'vmh_product_ingredients_nonce');
        $metaValue = get_post_meta($post->ID, 'product_ingredients', true);

        $metaValue = $metaValue ? $metaValue : "";

        echo '
            <div>
                <strong>
                    <label for="product_ingredients">Select Ingredients :</label>
                    <br/>
                </strong>
                <br />
                <select name="product_ingredients[]" multiple="multiple" style="min-width: 300px" id="product_ingredients" class="product_ingredients">
                    '.$this->getIngredients($metaValue).'
                </select>
            </div>
       ';
    }

    /**
     * The html of product ingredeints
     * @param $post
     */
    public function productIngredientsPercentageHTML($post) {
        // wp_nonce_field('vmh_product_ingredients_action', 'vmh_product_ingredients_nonce');
        $metaValue = get_post_meta($post->ID, 'ingredients_percentage_values', true);

        $metaValue = $metaValue ? $metaValue : "";

        echo '
                <div>
                    <br />
                    <select name="ingredients_percentage_values[]" multiple="multiple" style="min-width: 300px" id="ingredients_percentage_values" class="ingredients_percentage_values">
                        '.$this->getIngredientsPercentage($metaValue, $post->ID).'
                    </select>
                </div>
           ';
    }

    /**
     * @param $metaValues
     */
    public function getIngredients($metaValues) {

        $args = [
            'post_type'      => 'ingredient',
            'posts_per_page' => -1,
            'post_status'    => 'publish'
        ];

        $ingredients = get_posts($args);

        if (!$ingredients) {
            return '';
        }

        $options = '';

        foreach ($ingredients as $key => $ingredient) {
            $options .= '<option '.$this->echo_select($metaValues, esc_attr($ingredient->ID)).' value="'.esc_attr($ingredient->ID).'" >
                            '.esc_html($ingredient->post_title).' ('.get_post_meta($ingredient->ID, 'ingredients_stock', true).')
                        </option>';
        }

        return $options;
    }

    /**
     * Get the percentage of ingredients
     * @param $ingredientsPercentage
     */
    public function getIngredientsPercentage($ingredientsPercentage, $postID) {
        if (!$ingredientsPercentage) {
            return '';
        }

        $ingredients = get_post_meta($postID, 'product_ingredients', true);

        $options = '';

        foreach ($ingredientsPercentage as $key => $ingredientPercentage) {

            if (isset($ingredients[$key]) && $ingredients[$key]) {
                $options .= '<option selected value="'.$key.'_'.esc_attr($ingredientPercentage).'" >
                                '.get_the_title($ingredients[$key]).' '.$ingredientPercentage.'%
                            </option>';
            }
        }

        return $options;
    }

    /**
     * @param  $metaValues
     * @param  $ingredient
     * @return null
     */
    public function echo_select($metaValues, $ingredient) {
        if (!is_array($metaValues)) {
            return;
        }
        if (in_array($ingredient, $metaValues)) {
            return 'selected';
        } else {
            return '';
        }
    }

    /**
     * @param  int     $postID
     * @param  object  $postObject
     * @return mixed
     */
    public function saveIngredientsMetaValue(int $postID, object $postObject) {

        if (!isset($_POST['vmh_product_ingredients_nonce']) || !wp_verify_nonce($_POST['vmh_product_ingredients_nonce'], 'vmh_product_ingredients_action')) {
            return $postID;
        }

        /* Does current user have capabitlity to edit post */
        $post_type = get_post_type_object($postObject->post_type);

        if (!current_user_can($post_type->cap->edit_post, $postID)) {
            return $postID;
        }

        /* Get the posted data and check it for uses. */
        $new_meta_value = (isset($_POST['product_ingredients']) ? $_POST['product_ingredients'] : "");

        /* Get the meta key. */
        $meta_key = 'product_ingredients';

        /* Get the meta value of the custom field key. */
        $meta_value = get_post_meta($postID, $meta_key, true);

        if ($new_meta_value && "" == $meta_value) {
            /* If a new meta value was added and there was no previous value, add it. */
            add_post_meta($postID, $meta_key, $new_meta_value);
        } elseif ($new_meta_value && $new_meta_value != $meta_value) {
            /* If the new meta value does not match the old value, update it. */
            update_post_meta($postID, $meta_key, $new_meta_value);
        } elseif ("" == $new_meta_value && $meta_value) {
            /* If there is no new meta value but an old value exists, delete it. */
            delete_post_meta($postID, $meta_key, $meta_value);
        }
    }

    // Register the ingredients price meta box in ingredient post type
    public function registerIngredientPriceMeta() {
        add_meta_box(
            'ingredients_price',
            'Ingredients Price',
            [$this, 'ingredientsPriceHtml'],
            ['ingredient'],
            'normal',
            'high'
        );
    }

    /**
     * The HTML of ingredient price meta box
     * @param $post
     */
    public function ingredientsPriceHtml($post) {
        wp_nonce_field('vmh_ingredients_price_action', 'vmh_ingredients_price_nonce');

        $metaValue = get_post_meta($post->ID, 'ingredients_price', true);

        if ($metaValue == 0) {
            $metaValue = '0';
        }

        echo '
            <div>
                <strong>
                    <label for="ingredients_price">Price/ml: (<span>'.get_woocommerce_currency_symbol().'</span>)</label>
                    <br/>
                </strong>
                <br />
                <input type="text" name="ingredients_price" id="ingredients_price" value="'.$metaValue.'"/>
            </div>
       ';
    }

    /**
     * Save the meta value of ingredients price in ingredient post type
     * @param  int     $postID
     * @param  object  $postObject
     * @return mixed
     */
    public function saveIngredientPriceMeta(int $postID, object $postObject) {

        if (!isset($_POST['vmh_ingredients_price_nonce']) || !wp_verify_nonce($_POST['vmh_ingredients_price_nonce'], 'vmh_ingredients_price_action')) {
            return $postID;
        }

        /* Does current user have capabitlity to edit post */
        $post_type = get_post_type_object($postObject->post_type);

        if (!current_user_can($post_type->cap->edit_post, $postID)) {
            return $postID;
        }

        /* Get the meta key. */
        $meta_key = 'ingredients_price';

        /* Get the posted data and check it for uses. */
        $new_meta_value = (isset($_POST[$meta_key]) ? $_POST[$meta_key] : "");

        /* Get the meta value of the custom field key. */
        $meta_value = get_post_meta($postID, $meta_key, true);

        if ($new_meta_value && "" == $meta_value) {
            /* If a new meta value was added and there was no previous value, add it. */
            add_post_meta($postID, $meta_key, $new_meta_value);
        } elseif ($new_meta_value && $new_meta_value != $meta_value) {
            /* If the new meta value does not match the old value, update it. */
            update_post_meta($postID, $meta_key, $new_meta_value);
        } elseif ("" == $new_meta_value && $meta_value) {
            /* If there is no new meta value but an old value exists, delete it. */
            delete_post_meta($postID, $meta_key, $meta_value);
        }
    }

    /**
     * @param Type $var
     */
    public function addNicotineshotfield() {
        if (wc_get_product(get_the_ID())->get_type() === 'variable' && get_the_ID() != get_option('vmh_create_product_option')) {
            echo '
                <input type="hidden" name="nicotine_shot_value" id="nicotine_shot_value" />
                <div class="price_box">
                    <input type="hidden" id="created_recipe_id" value="'.get_the_ID().'">
                </div>
                ';
        }
    }

    /**
     * Validate custom field ( nicotine shot value & ingredients price )
     * @param  $passed
     * @param  $productID
     * @param  $quantity
     * @param  $variationID
     * @return mixed
     */
    public function validatedCustomField($passed, $productID, $quantity, $variationID = null) {
        if (empty($_POST['nicotine_shot_value'])) {
            $passed = false;
            wc_add_notice(__('Nicotine shot value is required', 'vmh-hub'), 'error');
        }
        return $passed;
    }

    /**
     * Add custom nicotine shot data to cart item
     * @param  $cartItemData
     * @param  $productID
     * @param  $variationID
     * @return mixed
     */
    public function addNicotineshotToCart($cartItemData, $productID, $variationID) {

        if (isset($_POST['nicotine_shot_value']) && sanitize_text_field($_POST['nicotine_shot_value'])) {
            $cartItemData['nicotine_shot_value'] = sanitize_text_field($_POST['nicotine_shot_value']);
            $cartItemData['nicotine_shot_calculated_value'] = sanitize_text_field($_POST['nicotine_shot_value']);
        }

        // Add the ingredients total price to cart
        if ($productID && $variationID) {

            $attributes = wc_get_product_variation_attributes($variationID);
            $productIngredients = get_post_meta($productID, 'product_ingredients', true);
            $ingredientsPercentageValues = get_post_meta($productID, 'ingredients_percentage_values', true);
            $bottleSize = $attributes['attribute_pa_vmh_bottle_size'];

            $ingredientsTotalPrice = getIngredientsTotalPrice([
                'productIngredients'          => $productIngredients,
                'ingredientsPercentageValues' => $ingredientsPercentageValues,
                'bottleSize'                  => $bottleSize
            ]);

            $cartItemData['ingredientsTotalPrice'] = $ingredientsTotalPrice;
        }

        return $cartItemData;
    }

    /**
     * Increase the cart item price based on nicotine shot
     * @param  $cartObject
     * @return null
     */
    public function addNicotineshotprice($cart) {

        // Avoiding hook repetition (when using price calculations for example | optional)
        if (did_action('woocommerce_before_calculate_totals') >= 2) {
            return;
        }

        $cartItems = $cart->get_cart();

        if (!$cartItems || count($cartItems) < 1) {
            return;
        }

        // Loop through cart items
        foreach ($cartItems as $cartItem) {

            // $newPrice = (($cartItem['nicotine_shot_value'] / 10) * NICOTINE_SHOT_PRICE);
            $newPrice = $cartItem['ingredientsTotalPrice'];
            $cartItem['data']->set_price($newPrice);
        }

    }

    /**
     * @param $cart
     */
    public function calculateCartTotal($cart) {
        $freebasePrice = 0;
        $nicotineSaltPrice = 0;

        $cartItems = $cart->get_cart();

        $combinedNicotineShot = getCalculatedNicotineShots($cartItems);

        $freebaseSalt = $combinedNicotineShot['freebase-nicotine']['shotValue'];
        $nicotineSalt = $combinedNicotineShot['nicotine-salt']['shotValue'];

        $freebasePrice = getIndividualShotPrice($freebaseSalt);
        $nicotineSaltPrice = getIndividualShotPrice($nicotineSalt);

        $total = $cart->total + ($freebasePrice + $nicotineSaltPrice);

        $cart->add_fee(__('Total Nicotine shot price:', 'vmh-hub'), $total);
    }

    /**
     * Add the nicotineshot amount data to order item in admin dashboard
     * @param $item
     * @param $cart_item_key
     * @param $values
     * @param $order
     */
    public function addNicotineshotToItem($item, $cart_item_key, $values, $order) {
        if (isset($values['nicotine_shot_value'])) {
            $item->add_meta_data(
                '_nicotine_shot_value',
                $values['nicotine_shot_value'],
                false
            );

            $item->add_meta_data(
                'Nicotine Shot (ml)',
                (ceil($values['nicotine_shot_value'] / 10) * 10),
                false
            );
        }
    }

    /**
     * Display the calculated nicotine shot value after order details
     * @param $order
     */
    public function displayCalculatedNicotineShot($order) {

        $shotCalculationData = [
            'freebase-nicotine' => [
                'name'      => 'Freebase Nicotine',
                'shotValue' => 0
            ],
            'nicotine-salt'     => [
                'name'      => 'Nicotine Salt',
                'shotValue' => 0
            ]
        ];

        foreach ($order->get_items() as $item_id => $item) {

            $nicotineType = $item->get_meta('pa_vmh_nicotine_type', true);
            $nicotineShotValue = $item->get_meta('_nicotine_shot_value', true);

            // Add up the nicotine shot value
            $shotCalculationData[$nicotineType]['shotValue'] += $nicotineShotValue * $item->get_quantity();
        }

        // Rounding the number to nearest integer & than rounding the number to 10 times of its value
        $shotCalculationData['freebase-nicotine']['shotValue'] = ceil($shotCalculationData['freebase-nicotine']['shotValue'] / 10) * 10;
        $shotCalculationData['nicotine-salt']['shotValue'] = ceil($shotCalculationData['nicotine-salt']['shotValue'] / 10) * 10;

        echo $this->displayShotValueHtml($shotCalculationData);
    }

    /**
     * @param  array   $shotCalculationData
     * @return mixed
     */
    public function displayShotValueHtml(array $shotCalculationData) {
        if (count($shotCalculationData) < 1) {
            return '';
        }

        $shotHtml = '<div class="order_data_column" style="width: 90%;margin-top: 20px;border: 2px solid #324b4b;padding: 0px 10px 15px 10px;">';

        foreach ($shotCalculationData as $key => $value) {
            if ($value['shotValue'] > 0) {
                $shotHtml .= '<h3 style="font-weight: bold">'.esc_html($value['name']).': '.esc_html($value['shotValue'] / 10).' shot</h3>';
            }
        }

        $shotHtml .= '</div>';

        return $shotHtml;
    }

    /**
     * Save user description field
     * @param $userID
     */
    public function saveUserDescriptionField($userID) {

        if (isset($_POST['description'])) {
            update_user_meta($userID, 'description', sanitize_text_field($_POST['description']));
        }
    }

    /**
     * Redirect the user into same edit account page
     * @param $ID
     */
    public function redirectToEditAccountPage($ID) {
        wp_safe_redirect(wc_get_endpoint_url('edit-account'));
        exit();
    }

    /**
     * Set a minimum order amount for checkout
     */
    public function setMinimumOrderAmount() {
        $minimum = 20;

        if (WC()->cart->total < $minimum) {

            if (is_cart()) {

                wc_print_notice(
                    sprintf('Your current order total is %s — you must have an order with a minimum of %s to place your order ',
                        wc_price(WC()->cart->total),
                        wc_price($minimum)
                    ), 'error'
                );

            } else {

                wc_add_notice(
                    sprintf('Your current order total is %s — you must have an order with a minimum of %s to place your order',
                        wc_price(WC()->cart->total),
                        wc_price($minimum)
                    ), 'error'
                );

            }
        }
    }
}