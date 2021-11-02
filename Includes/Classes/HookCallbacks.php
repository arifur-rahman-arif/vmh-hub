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
        wp_enqueue_style('vmh-font', VMH_URL . 'Assets/Fonts/font.css', [], VMH_VERSION, 'all');
        wp_enqueue_style('vmh-normalize', VMH_URL . 'Assets/css/normalize.css', [], VMH_VERSION, 'all');
        wp_enqueue_style('vmh-bootstrap', VMH_URL . 'Assets/css/bootstrap.min.css', [], VMH_VERSION, 'all');
        wp_enqueue_style('vmh-fontawesome', '//pro.fontawesome.com/releases/v5.10.0/css/all.css');
        wp_enqueue_style('vmh-slim-select', VMH_URL . 'Assets/css/slimselect.min.css', [], VMH_VERSION, 'all');
        wp_enqueue_style('vmh-style', VMH_URL . 'Assets/css/style.css', [], VMH_VERSION, 'all');
        wp_enqueue_style('vmh-responsive', VMH_URL . 'Assets/css/responsive.css', [], VMH_VERSION, 'all');
        wp_enqueue_style('vmh-custom', VMH_URL . 'Assets/css/custom.css', [], VMH_VERSION, 'all');
        wp_enqueue_style('vmh-themeCss', get_stylesheet_uri());
    }

    // Load all the javascript files here
    public function loadScripts() {
        wp_enqueue_script('jquery');
        wp_enqueue_script('vmh-modernizr', VMH_URL . 'Assets/scripts/modernizr-3.11.2.min.js', [], VMH_VERSION, true);
        wp_enqueue_script('vmh-slim-select', VMH_URL . 'Assets/scripts/slimselect.min.js', ['jquery'], VMH_VERSION, false);
        wp_enqueue_script('vmh-popper', VMH_URL . 'Assets/scripts/popper.min.js', [], VMH_VERSION, true);
        wp_enqueue_script('vmh-bootstrap', VMH_URL . 'Assets/scripts/bootstrap.min.js', [], VMH_VERSION, true);
        wp_enqueue_script('vmh-custom', VMH_URL . 'Assets/scripts/custom.js', ['jquery'], VMH_VERSION, true);
        wp_enqueue_script('vmh-main', VMH_URL . 'Assets/scripts/main.js', ['jquery'], VMH_VERSION, true);
    }

    // Localize javascript files
    public function localizeFile() {
        wp_localize_script('vmh-main', 'vmhLocal', [
            'ajaxUrl'              => admin_url('admin-ajax.php'),
            'vmhProductAttributes' => $this->vmhProductAttributes(),
            'currencySymbol'       => get_woocommerce_currency_symbol(),
            'siteUrl'              => site_url('/')
        ]);
    }

    // Load admin asset files
    public function loadAdminFiles() {
        $this->loadAdminScripts();
        $this->loadAdminStyles();
    }

    public function loadAdminScripts() {
        wp_enqueue_script('vmh-admin-select', VMH_URL . 'Assets/scripts/slimselect.min.js', ['jquery'], VMH_VERSION, true);
        wp_enqueue_script('vmh-admin', VMH_URL . 'Assets/scripts/admin.js', ['jquery', 'vmh-admin-select'], VMH_VERSION, true);
        wp_localize_script('vmh-admin', 'vmhLocal', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'siteUrl' => site_url('/')
        ]);
    }

    public function loadAdminStyles() {
        wp_enqueue_style('vmh-select', VMH_URL . 'Assets/css/slimselect.min.css', [], VMH_VERSION, 'all');
        wp_enqueue_style('vmh-admin', VMH_URL . 'Assets/css/admin.css', [], VMH_VERSION, 'all');
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
            __('VMH Product Options', 'sheetstowptable'),
            __('VMH Product Options', 'sheetstowptable'),
            'manage_options',
            'vmh-product-options',
            [$this, 'adminPage'],
            'dashicons-tickets',
            5
        );
        add_submenu_page(
            'vmh-product-options',
            __('Dashboard', 'sheetstowptable'),
            __('Dashboard', 'sheetstowptable'),
            'manage_options',
            'gswpts-dashboard',
            [$this, 'adminPage']
        );
    }

    // Load admin page in the backend of this website
    public static function adminPage() {
        load_template(VMH_PATH . 'Includes/Templates/admin-menu.php', true);
    }

    /**
     * Define all the product attibutes that will be used in options settings & in product attributes
     * @return mixed
     */
    public function vmhProductAttributes() {
        $settinsKey = [
            // Settings key with title
            'vmh_nicotine_amount' => 'Nicotine Amount',
            'vmh_nicotine_type'   => 'Nicotine Type',
            'vmh_pg_vg'           => 'PG:VG',
            'vmh_bottle_size'     => 'Bottle size'
        ];
        return $settinsKey;
    }

    // Register settings for product options
    public function addOptionSettings() {

        $settinsKey = $this->vmhProductAttributes();

        foreach ($settinsKey as $key => $settingKey) {
            register_setting(
                'vmh_options_key',
                $key
            );
        }

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
        load_template(VMH_PATH . 'Includes/Templates/options-settings.php');
    }

    // Set new attribute to woocommerce product attributes
    /**
     * @return null
     */
    public function setProductAttributes() {

        if (isset($_GET['page']) && $_GET['page'] == 'vmh-product-options') {

            $attributes = wc_get_attribute_taxonomies();

            $settingsKey = $this->vmhProductAttributes();

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
    }

    // Generate custom taxonomy for custom product attributes
    /**
     * @return null
     */
    public function generateCustomTaxonomy() {

        if (isset($_GET['page']) && $_GET['page'] == 'vmh-product-options') {

            $settingsKey = $this->vmhProductAttributes();

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
            wp_insert_term(
                $value, // the term
                'pa_' . $taxonomy . '', // the taxonomy
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

            if ($product->get_type() == 'simple') {
                $userID = get_post($productID)->post_author;
                $user = get_userdata($userID);
                // Get all the user roles as an array.
                $user_roles = $user->roles;
                // Check if the role you're interested in, is present in the array.
                if (in_array('subscriber', $user_roles)) {
                    $total = $item->get_total();
                    $percentageValue = $this->getTotalPercentage($total);
                    if ($order->get_status() === 'completed') {
                        $totalCommssion = get_user_meta($userID, 'user_commision', true);
                        $newCommission = $percentageValue + $totalCommssion;
                        update_user_meta($userID, 'user_commision', $newCommission);
                    }
                }
            }
        }
    }

    /**
     * Get the percentage of total
     * @param  $total
     * @return mixed
     */
    public function getTotalPercentage($total) {
        $productCommission = get_option('vmh_product_commission');

        if (!$productCommission) {
            return $total;
        }

        $percentageValue = ($productCommission / 100) * $total;
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
                        Hello ' . $displayName . ' your product ' . $post->post_title . ' is now approved my admin. <a href="' . get_permalink($postID) . '">Click Here</a> to view your product
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

        $metaValue = $metaValue ? $metaValue : "";

        echo '
            <div>
                <strong>
                    <label for="ingredients_stock">Stock Quantity :</label>
                    <br/>
                </strong>
                <br />
                <input type="number" name="ingredients_stock" min="0" id="ingredients_stock" value="' . $metaValue . '"/>
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
                <select name="product_ingredients[]" multiple="multiple" style="width: 300px" id="product_ingredients" class="product_ingredients">
                    ' . $this->getIngredients($metaValue) . '
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
            $options .= '<option ' . $this->echo_select($metaValues, esc_attr($ingredient->ID)) . ' value="' . esc_attr($ingredient->ID) . '" >
                            ' . esc_html($ingredient->post_title) . ' (' . get_post_meta($ingredient->ID, 'ingredients_stock', true) . ')
                        </option>';
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
}