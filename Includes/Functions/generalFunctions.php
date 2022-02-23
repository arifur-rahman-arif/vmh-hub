<?php

/**
 * @param $text
 */
function vmhEscapeTranslate($text) {
    return esc_html__($text, 'vmh-hub');
}

/**
 * @param $userData
 */
function createUser($userData) {

    $userArgs = [
        'user_pass'            => $userData['password'],
        'user_login'           => $userData['username'], //(string) The user's login username.
        'user_email'           => $userData['email'], //(string) The user email address.
        'display_name'         => ($userData['fname'] . ' ' . $userData['lname']), //(string) The user's display name. Default is the user's username.
        'first_name'           => $userData['fname'], //(string) The user's first name. For new users, will be used to build the first part of the user's display name if $display_name is not specified.
        'last_name'            => $userData['lname'], //(string) The user's last name. For new users, will be used to build the second part of the user's display name if $display_name is not specified.
        'show_admin_bar_front' => 'false', //(string|bool) Whether to display the Admin Bar for the user on the site's front end. Default true.
        'role'                 => 'subscriber' //(string) User's role.
    ];

    $userCreateResponse = wp_insert_user($userArgs);

    if (is_int($userCreateResponse)) {
        update_user_meta($userCreateResponse, 'date_of_birth', $userData['date_of_birth']);
        update_user_meta($userCreateResponse, 'over_age', $userData['over_age'] == 'on' ? true : false);
        update_user_meta($userCreateResponse, 'user_country', $userData['country']);

        $to = $userData['email'];
        $displayName = ($userData['fname'] . ' ' . $userData['lname']);
        $subject = 'Account verified';
        $message = '
            Hello ' . $displayName . ' your account is verified. You can now log in into you account by clicking here <a href="' . site_url('/login') . '">Login to you account</a>
        ';
        $headers = ['Content-Type: text/html; charset=UTF-8'];

        wp_mail($to, $subject, $message, $headers);

        return [
            'response' => 'success',
            'message'  => 'Your account created successfully. <a href="' . site_url('/login') . '">Log in</a>'
        ];
    } else {
        if (is_wp_error($userCreateResponse)) {
            return [
                'response' => 'invalid',
                'message'  => vmhEscapeTranslate('User could not be created. Try again')
            ];
        }
    }

    return [
        'response' => 'invalid',
        'message'  => vmhEscapeTranslate('Something went wrong')
    ];
}

// This function will show the search bar if FiboSearch – Ajax Search for WooCommerce plugin is active https://wordpress.org/plugins/ajax-search-for-woocommerce/
function getSearchBar() {
    if (shortcode_exists('fibosearch')) {
        return do_shortcode('[fibosearch]');
    }
    return '';
}

// Get the all administrator info

/**
 * @return mixed
 */
function getAdministrators() {

    $args = [
        'role'    => 'administrator',
        'orderby' => 'user_nicename',
        'order'   => 'ASC'
    ];

    $users = get_users($args);

    return $users;
}

// Get the admins as a select box options
/**
 * @return mixed
 */
function getAdministratorsOptionHTML($saveID) {
    $admins = getAdministrators();

    if (!$admins || !is_array($admins)) {
        return '';
    }

    $optionsHTML = '<option disabled>Select a admin</option>';

    foreach ($admins as $key => $admin) {
        $selected = $saveID == $admin->ID ? "selected" : null;
        $optionsHTML .= '<option ' . $selected . ' value="' . esc_attr($admin->ID) . '" >' . esc_html($admin->display_name) . '</option>';
    }

    return $optionsHTML;
}

/**
 * Define all the product attibutes that will be used in options settings & in product attributes
 * @return mixed
 */
function vmhProductAttributes() {
    $settinsKey = [
        // Settings key with title
        'vmh_nicotine_amount' => 'Nicotine Amount',
        'vmh_nicotine_type'   => 'Nicotine Type',
        'vmh_pg_vg'           => 'PG:VG',
        'vmh_bottle_size'     => 'Bottle size'
    ];
    return $settinsKey;
}

/**
 * Calculate and return the combined ingredient price for specific bottle size variation
 * @param $productIngredients
 */
function getIngredientsTotalPrice($args) {
    $price = 0;

    if (!is_array($args['productIngredients']) ||
        count($args['productIngredients']) < 1 ||
        !is_array($args['ingredientsPercentageValues']) ||
        count($args['ingredientsPercentageValues']) < 1 ||
        !$args['bottleSize']) {
        return $price;
    }

    extract($args);

    $acceptedBottleSize = ['10ml', '50ml'];

    if (!in_array($bottleSize, $acceptedBottleSize)) {
        return $price;
    }

    $bottleSizeInteger = null;
    $bottleSizePrice = null;

    if ($bottleSize == '10ml') {
        $bottleSizePrice = BOTTLE_SIZE_PRICE_10ml;
        $bottleSizeInteger = 10;
    }

    if ($bottleSize == '50ml') {
        $bottleSizePrice = BOTTLE_SIZE_PRICE_50ml;
        $bottleSizeInteger = 50;
    }

    if ($bottleSizePrice == null) {
        return $price;
    }

    foreach ($productIngredients as $key => $ingredient) {
        $userSelectedPercentage = $ingredientsPercentageValues[$key];
        $ingredientPerMlPrice = floatval(get_post_meta($ingredient, 'ingredients_price', true));

        // If there are no price defined than skip the current ingredient
        if (!$ingredientPerMlPrice) {
            continue;
        }

        $ingredientPrice = $bottleSizeInteger * ($userSelectedPercentage / 100) * $ingredientPerMlPrice;

        $price += $ingredientPrice;
    }

    if ($price != 0) {
        $price += $bottleSizePrice;
    }

    return $price;
}

// Set user session for first time of opening the website
function setUserSession() {

    if (!isset($_SESSION["set_user_session"]) && !is_user_logged_in()) {
        echo '<input type="hidden" id="first_time_opening"/>';
        $_SESSION["set_user_session"] = true;
    }

    session_destroy();

}
