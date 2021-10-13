<?php

namespace VmhHub\Includes\Classes;

class CallbackFunctions {

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
        wp_enqueue_style('vmh-style', VMH_URL . 'Assets/css/style.css', [], VMH_VERSION, 'all');
        wp_enqueue_style('vmh-responsive', VMH_URL . 'Assets/css/responsive.css', [], VMH_VERSION, 'all');
        wp_enqueue_style('vmh-custom', VMH_URL . 'Assets/css/custom.css', [], VMH_VERSION, 'all');
        wp_enqueue_style('vmh-themeCss', get_stylesheet_uri());
    }

    // Load all the javascript files here
    public function loadScripts() {
        wp_enqueue_script('jquery');
        wp_enqueue_script('vmh-modernizr', VMH_URL . 'Assets/scripts/modernizr-3.11.2.min.js', [], VMH_VERSION, true);
        wp_enqueue_script('vmh-popper', VMH_URL . 'Assets/scripts/popper.min.js', [], VMH_VERSION, true);
        wp_enqueue_script('vmh-bootstrap', VMH_URL . 'Assets/scripts/bootstrap.min.js', [], VMH_VERSION, true);
        wp_enqueue_script('vmh-custom', VMH_URL . 'Assets/scripts/custom.js', ['jquery'], VMH_VERSION, true);
        wp_enqueue_script('vmh-main', VMH_URL . 'Assets/scripts/main.js', ['jquery'], VMH_VERSION, true);
    }

    // Localize javascript files
    public function localizeFile() {
        wp_localize_script('vmh-main', 'vmhLocal', [
            'ajaxUrl'              => admin_url('admin-ajax.php'),
            'siteUrl'              => site_url('/'),
            'vmhProductAttributes' => $this->vmhProductAttributes(),
            'currencySymbol'       => get_woocommerce_currency_symbol(),
            'siteUrl'              => site_url('/')
        ]);
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
            'headerMenu' => __('Header Menu')
            // 'footerMenu' => __('Footer Menu')
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

    /**
     * Customize the woocommerce billing fields according to custom vmhhub theme
     * @param $billingFields
     */
    public function customizeBillingFields($billingFields) {
        $billingFields['billing_first_name']['class'][] = 'login_input_left shipping_address_left';
        $billingFields['billing_last_name']['class'][] = 'login_input_left shipping_address_left';
        $billingFields['billing_company']['class'][] = 'login_input_left';
        $billingFields['billing_country']['class'][] = 'login_input_left';
        $billingFields['billing_address_1']['class'][] = 'login_input_left';
        $billingFields['billing_address_2']['class'][] = 'login_input_left';
        $billingFields['billing_city']['class'][] = 'login_input_left';
        $billingFields['billing_state']['class'][] = 'login_input_left';
        $billingFields['billing_postcode']['class'][] = 'login_input_left';
        $billingFields['billing_phone']['class'][] = 'login_input_left';
        $billingFields['billing_email']['class'][] = 'login_input_left';
        return $billingFields;
    }

    /**
     * Customize the woocommerce billing fields according to custom vmhhub theme
     * @param $billingFields
     */
    public function customizeCheckoutFields($checkoutFields) {
        $checkoutFields['shipping']['shipping_first_name']['class'][] = 'login_input_left shipping_address_left';
        $checkoutFields['shipping']['shipping_last_name']['class'][] = 'login_input_left shipping_address_left';
        $checkoutFields['shipping']['shipping_company']['class'][] = 'login_input_left';
        $checkoutFields['shipping']['shipping_country']['class'][] = 'login_input_left';
        $checkoutFields['shipping']['shipping_address_1']['class'][] = 'login_input_left';
        $checkoutFields['shipping']['shipping_address_2']['class'][] = 'login_input_left';
        $checkoutFields['shipping']['shipping_city']['class'][] = 'login_input_left';
        $checkoutFields['shipping']['shipping_state']['class'][] = 'login_input_left';
        $checkoutFields['shipping']['shipping_postcode']['class'][] = 'login_input_left';
        return $checkoutFields;
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

    // Generate custom taxonomy for custom product attributes
    /**
     * @return null
     */
    public function generateCustomTaxonomy() {

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
     * Chagne default text of woocommerce variable product choose options default text
     * @return mixed
     */
    public function changeVariableProductChooseOption($array) {

        // Find the name of the attribute for the slug we passed in to the function
        $attribute_name = wc_attribute_label($array['attribute']);

        // Create a string for our select
        $array['show_option_none'] = __('Choose', 'woocommerce');
        return $array;
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
}