<?php
namespace VmhHub\Includes\Classes;

use WC_Product;

trait AjaxCallbacks {

    // Handle the login process
    public function handleLoginProcess() {
        $output = [];

        if (sanitize_text_field($_POST['action']) !== 'vmh_login_action') {
            $output['response'] = 'invalid';
            $output['message'] = 'Action is not valid';
            echo json_encode($output);
            wp_die();
        }

        parse_str($_POST['formData'], $parsedData);

        $sanitizedData = $this->sanitizeData($parsedData);

        $output = $this->logTheUser($sanitizedData);

        echo json_encode($output);

        wp_die();
    }

    // Login the user into website
    /**
     * @param $loginCred
     */
    public function logTheUser($loginCred) {

        $cred = [
            'user_login'    => $loginCred['email'],
            'user_password' => $loginCred['password'],
            'remember'      => true
        ];

        $res = wp_signon($cred, true);

        if (!is_wp_error($res)) {
            $user_id = $res->data->ID;
            wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id, true);
            return [
                'response' => 'success',
                'message'  => esc_html('Login Successfull')
            ];
        } else {
            if (array_key_exists("incorrect_password", $res->errors)) {
                if ($res->errors['incorrect_password'][0]) {
                    return [
                        'response' => 'error',
                        'message'  => esc_html('Incorrect credentials')
                    ];
                }
            }
            if (array_key_exists("invalid_username", $res->errors)) {
                if ($res->errors['invalid_username'][0]) {
                    return [
                        'response' => 'error',
                        'message'  => esc_html('Invalid credentials')
                    ];
                }
            }
            if (array_key_exists("invalid_email", $res->errors)) {
                if ($res->errors['invalid_email'][0]) {
                    return [
                        'response' => 'error',
                        'message'  => esc_html('Invalid credentials')
                    ];
                }
            }
        }
    }

    /**
     *  Sanitize an array of data
     * @param  array   $NonSanitzedData
     * @return mixed
     */
    public function sanitizeData(array $NonSanitzedData) {
        $sanitizedData = null;

        $sanitizedData = array_map(function ($data) {
            if (gettype($data) == 'array') {
                return $this->sanitizeData($data);
            } else {
                return sanitize_text_field($data);
            }
        }, $NonSanitzedData);

        return $sanitizedData;
    }

    // Handle the process of user creating throuhg ajax
    public function handleCreateUser() {
        $output = [];

        if (sanitize_text_field($_POST['action']) !== 'vmh_create_user') {
            $output['response'] = 'invalid';
            $output['message'] = 'Action is not valid';
            echo json_encode($output);
            wp_die();
        }

        parse_str($_POST['formData'], $parsedData);

        $sanitizedData = $this->sanitizeData($parsedData);

        $checkUserData = $this->checkUserData($sanitizedData);

        if ($checkUserData['response'] !== 'data_valid') {
            $output['response'] = 'invalid';
            $output['message'] = $checkUserData['message'];
            echo json_encode($output);
            wp_die();
        }

        $output = $this->createUser($sanitizedData);
        echo json_encode($output);

        wp_die();

    }

    /**
     * @param  $userData
     * @return null
     */
    public function checkUserData($userData) {
        if (!$userData['email'] ||
            !$userData['username'] ||
            !$userData['password'] ||
            !$userData['confirm_password'] ||
            !$userData['fname'] ||
            !$userData['date_of_birth'] ||
            !$userData['lname'] ||
            !$userData['country']) {
            return [
                'response' => 'invalid',
                'message'  => vmhEscapeTranslate('One or more field is empty')
            ];
        }
        if ($userData['password'] !== $userData['confirm_password']) {
            return [
                'response' => 'invalid',
                'message'  => vmhEscapeTranslate('Password didn\'t matched')
            ];
        }

        if (email_exists($userData['email'])) {
            return [
                'response' => 'invalid',
                'message'  => vmhEscapeTranslate('Email already exists')
            ];
        }

        if (username_exists($userData['username'])) {
            return [
                'response' => 'invalid',
                'message'  => vmhEscapeTranslate('Username already exists')
            ];
        }

        return [
            'response' => 'data_valid'
        ];
    }

    // Create the user into database

    /**
     * @param $userData
     */
    public function createUser($userData) {

        $hashString = md5(time());

        if (set_transient($hashString, $userData, (60 * 60 * 24))) {
            $to = $userData['email'];
            $displayName = ($userData['fname'] . ' ' . $userData['lname']);
            $subject = 'Verify your account';
            $message = '
                Hello ' . $displayName . ' please verify your account by clicking here <a href="' . site_url('/verify?key=' . $hashString . '') . '">Verify Account</a>
            ';
            $headers = ['Content-Type: text/html; charset=UTF-8'];

            if (wp_mail($to, $subject, $message, $headers)) {
                return [
                    'response' => 'success',
                    'message'  => vmhEscapeTranslate('Verify your email to confirm your account. Check inbox')
                ];
            } else {
                return [
                    'response' => 'invalid',
                    'message'  => vmhEscapeTranslate('Mail can not be sent to user email')
                ];
            }
        }

    }

    // Add the prooduct to cart
    public function addProductToCart() {
        $output = [];

        if (sanitize_text_field($_POST['action']) !== 'vmh_add_product_to_cart') {
            $output['response'] = 'invalid';
            $output['message'] = vmhEscapeTranslate('Action is not valid');
            echo json_encode($output);
            wp_die();
        }

        $productID = sanitize_text_field($_POST['productID']);

        global $woocommerce;

        if (get_post_meta($productID, "_stock_status", true) !== 'instock') {
            $output['response'] = 'invalid';
            $output['message'] = vmhEscapeTranslate('This product is out of stock');
            echo json_encode($output);
            wp_die();
        }

        if ($woocommerce->cart->add_to_cart($productID)) {
            $output['response'] = 'success';
            $output['message'] = vmhEscapeTranslate('Your product is added to your cart');
            $output['productPrice'] = wc_get_product($productID)->get_price();
            echo json_encode($output);
            wp_die();
        }

        $output['response'] = 'invalid';
        $output['message'] = vmhEscapeTranslate('Product couldn\'t be added. Please try again');
        echo json_encode($output);

        wp_die();
    }

    // Remove product from cart via ajax request
    public function removeProductFromCart() {
        $output = [];

        if (sanitize_text_field($_POST['action']) !== 'vmh_remove_product_from_cart') {
            $output['response'] = 'invalid';
            $output['message'] = vmhEscapeTranslate('Action is not valid');
            echo json_encode($output);
            wp_die();
        }

        $productID = sanitize_text_field($_POST['productID']);

        if (!function_exists('WC')) {
            $output['response'] = 'invalid';
            $output['message'] = vmhEscapeTranslate('Woocommerce is not installed');
            echo json_encode($output);
            wp_die();
        }

        global $woocommerce;
        $items = $woocommerce->cart->get_cart();

        // wp_console_log($items);

        $product = wc_get_product($productID);

        if ($product->get_type() == 'variable') {
            if ($items) {
                foreach ($items as $key => $cartItem) {
                    // if product id matches with cart item product id than retun the cart tiem key
                    if ($cartItem['product_id'] == $productID) {
                        $output = $this->removeCartItem([
                            'cartItemKey' => $key,
                            'price'       => $items[$key]['line_total'],
                            'items'       => $items
                        ]);
                        echo json_encode($output);
                        wp_die();
                    }
                }
            }
        }

        if ($product->get_type() == 'simple') {
            $productCartID = WC()->cart->generate_cart_id($productID);
            $cartItemKey = WC()->cart->find_product_in_cart($productCartID);
            $output = $this->removeCartItem([
                'cartItemKey' => $cartItemKey,
                'price'       => $items[$cartItemKey]['line_total'],
                'items'       => $items
            ]);
            echo json_encode($output);
            wp_die();
        }

        $output['response'] = 'invalid';
        $output['message'] = vmhEscapeTranslate('Product couldn\'t be removed. Please try again');
        echo json_encode($output);

        wp_die();

    }

    /**
     * Remove cart item from cart
     * @param  array   $args
     * @return mixed
     */
    public function removeCartItem(array $args) {
        $output = [];
        extract($args);
        if ($cartItemKey) {
            if (WC()->cart->remove_cart_item($cartItemKey)) {
                $output['response'] = 'success';
                $output['message'] = vmhEscapeTranslate('Product removed from your cart sucessfully');
                $output['productPrice'] = ($price * $items[$cartItemKey]['quantity']);
                $output['productQuantity'] = $items[$cartItemKey]['quantity'];
                return $output;
            };
        }

        return $output;
    }

    // Toggle a product favorite state to favorite or un favorite
    public function toggleProductFavorite() {
        $output = [];

        if (sanitize_text_field($_POST['action']) !== 'vmh_toggle_favorite_product') {
            $output['response'] = 'invalid';
            $output['message'] = vmhEscapeTranslate('Action is not valid');
            echo json_encode($output);
            wp_die();
        }

        $userID = get_current_user_id();
        $sanitizedData = $this->sanitizeData($_POST);

        if (!isset($sanitizedData['productID']) || !isset($sanitizedData['btnAction'])) {
            $output['response'] = 'invalid';
            $output['message'] = vmhEscapeTranslate('Product ID or action is missing');
            echo json_encode($output);
            wp_die();
        }

        $productID = intval($sanitizedData['productID']);
        $btnAction = $sanitizedData['btnAction'];

        $favoriteMetaKey = '_user_favorite';
        $favoriteProductsID = get_user_meta($userID, $favoriteMetaKey, true);

        if ($favoriteProductsID == "") {

            // if no favortie products exists than add meta to user database
            if (add_user_meta($userID, $favoriteMetaKey, [$productID])) {
                $output['response'] = 'success';
                $output['status'] = 'added';
                $output['message'] = vmhEscapeTranslate('Product added to user favorite');
                echo json_encode($output);
                wp_die();
            } else {
                $output['response'] = 'invalid';
                $output['message'] = vmhEscapeTranslate('Product could\'t be added to favorite');
                echo json_encode($output);
                wp_die();
            }

        } else {
            // IF action is favorite than add that product to user's favorite or else remove that product from user's favorite products
            if ($btnAction === 'favorite') {
                array_push($favoriteProductsID, $productID);
                update_user_meta($userID, $favoriteMetaKey, $favoriteProductsID);
                $output['response'] = 'success';
                $output['status'] = 'added';
                $output['message'] = vmhEscapeTranslate('Product updated to user favorite');
                echo json_encode($output);
                wp_die();

            } elseif ($btnAction === 'unfavorite') {
                // if product id exists than remove that product id from favorite
                if (($key = array_search($productID, $favoriteProductsID)) !== false) {
                    unset($favoriteProductsID[$key]);
                    update_user_meta($userID, $favoriteMetaKey, $favoriteProductsID);
                    $output['response'] = 'success';
                    $output['status'] = 'removed';
                    $output['message'] = vmhEscapeTranslate('Product removed from user favorite');
                    echo json_encode($output);
                    wp_die();
                } else {
                    $output['response'] = 'invalid';
                    $output['message'] = vmhEscapeTranslate('Product don\'t exists to user favorite');
                    echo json_encode($output);
                    wp_die();
                }

            } else {
                $output['response'] = 'invalid';
                $output['message'] = vmhEscapeTranslate('Product action is missing');
                echo json_encode($output);
                wp_die();
            }

        }

        $output['response'] = 'invalid';
        $output['message'] = vmhEscapeTranslate('Product couldn\'t be added to favorite.');
        echo json_encode($output);
        wp_die();
    }

    // Create product for referral purpose
    /**
     * @return null
     */
    public function createProduct() {
        $output = [];

        if (sanitize_text_field($_POST['action']) !== 'vmh_create_product') {
            $output['response'] = 'invalid';
            $output['message'] = vmhEscapeTranslate('Action is not valid');
            echo json_encode($output);
            wp_die();
        }

        $userID = get_current_user_id();
        $sanitizedData = $this->sanitizeData($_POST);

        $postID = wp_insert_post(array(
            'post_author'  => $userID,
            'post_title'   => $sanitizedData['productName'],
            'post_content' => $sanitizedData['recipeNote'],
            'post_status'  => 'pending',
            'post_type'    => "product"
        ));

        if (!is_wp_error($postID)) {
            wp_set_object_terms($postID, 'simple', 'product_type');
            update_post_meta($postID, '_visibility', 'visible');
            update_post_meta($postID, '_stock_status', 'instock');
            update_post_meta($postID, 'total_sales', '0');
            update_post_meta($postID, '_downloadable', 'no');
            update_post_meta($postID, '_virtual', 'yes');
            update_post_meta($postID, '_regular_price', '');
            update_post_meta($postID, '_sale_price', '');
            update_post_meta($postID, '_purchase_note', '');
            update_post_meta($postID, '_featured', 'no');
            update_post_meta($postID, '_weight', '');
            update_post_meta($postID, '_length', '');
            update_post_meta($postID, '_width', '');
            update_post_meta($postID, '_height', '');
            update_post_meta($postID, '_product_attributes', array());
            update_post_meta($postID, '_price', $sanitizedData['productPrice']);
            update_post_meta($postID, '_regular_price', $sanitizedData['productPrice']);
            update_post_meta($postID, '_virtual', 'no');

            $productOptions = $sanitizedData['optionsValue'];
            $productIngredients = $sanitizedData['ingredientsValues'];
            $ingredientsPercentageValues = $sanitizedData['ingredientsPercentageValues'];
            $productTags = $sanitizedData['tagValues'];

            // add the product options to meta value
            if ($productOptions) {
                // $organizedOptions = [];
                // foreach ($productOptions as $key => $value) {
                //     $option = explode("|", $value[0]);
                //     array_push($organizedOptions, [
                //         trim($option[0]) => trim($option[1])
                //     ]);
                // }
                add_post_meta($postID, 'product_options', $productOptions);
            }

            // add product ingredients
            if ($productIngredients) {
                update_post_meta($postID, 'product_ingredients', $productIngredients);
            }

            // add product ingredients
            if ($ingredientsPercentageValues) {
                add_post_meta($postID, 'ingredients_percentage_values', $ingredientsPercentageValues);
            }

            // add product tags
            if ($productTags) {
                wp_set_object_terms($postID, $productTags, 'product_tag');
            }

            $output['response'] = 'success';
            $output['message'] = vmhEscapeTranslate('Product created successfully');
            echo json_encode($output);
            wp_die();
        }

        $output['response'] = 'invalid';
        $output['message'] = vmhEscapeTranslate('Product couldn\'t be created');
        echo json_encode($output);
        wp_die();

    }

    // Create new post for ingredient post type
    public function uploadIngredients() {
        $output = [];

        if (sanitize_text_field($_POST['action']) !== 'vmh_upload_ingredients') {
            $output['response'] = 'invalid';
            $output['message'] = vmhEscapeTranslate('Action is not valid');
            echo json_encode($output);
            wp_die();
        }

        if (!isset($_POST['attachmentURL']) || !esc_url($_POST['attachmentURL'])) {
            $output['response'] = 'invalid';
            $output['message'] = vmhEscapeTranslate('Attachment URL is not found');
            echo json_encode($output);
            wp_die();
        }

        $extension = pathinfo(esc_url($_POST['attachmentURL']), PATHINFO_EXTENSION);

        if (!$extension || $extension != 'csv') {
            $output['response'] = 'invalid';
            $output['message'] = vmhEscapeTranslate('Attachment is not a csv type');
            echo json_encode($output);
            wp_die();
        }

        $args = [
            'attachmentURL' => esc_url($_POST['attachmentURL'])
        ];

        $csvResponse = $this->getCsvData($args);
        $csvArray = $this->convertCsvToArray($csvResponse);

        if (!$csvArray) {
            $output['response'] = 'invalid';
            $output['message'] = vmhEscapeTranslate('Ingredients is empty');
            echo json_encode($output);
            wp_die();
        }

        $response = $this->createIngredientsPost($csvArray);

        echo json_encode($response);
        wp_die();
    }

    /**
     * @param  $args
     * @return mixed
     */
    public function getCsvData($args) {
        if (!isset($args['attachmentURL']) || !$args['attachmentURL']) {
            trigger_error('Csv file is not found', E_USER_ERROR);
        }

        try {
            $csvResponse = wp_remote_get($args['attachmentURL']);

            if ($csvResponse['response']['code'] == 200) {
                return $csvResponse['body'];
            } else {
                return [];
            }

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * @param $csvResponse
     */
    public function convertCsvToArray($csvResponse) {
        $stream = fopen('php://memory', 'r+');

        $csvArray = [];

        fwrite($stream, $csvResponse);
        rewind($stream);

        while (($line = fgetcsv($stream)) !== FALSE) {
            array_push($csvArray, $line);
        }

        fclose($stream);

        return $csvArray;
    }

    /**
     * @param  array   $ingredients
     * @return mixed
     */
    public function createIngredientsPost(array $ingredients) {
        $output = [];

        if (!$ingredients) {
            $output['response'] = 'invalid';
            $output['message'] = vmhEscapeTranslate('Ingredients is empty');
        }

        for ($i = 1; $i < count($ingredients); $i++) {

            $ingredient = $ingredients[$i];

            $args = [
                'post_title'  => esc_html($ingredient[0]),
                'post_type'   => 'ingredient',
                'post_status' => 'publish'
            ];

            $ingredientPost = wp_insert_post($args);

            if (!is_wp_error($ingredientPost)) {
                $stockQuantity = $ingredient[1];

                if (update_post_meta($ingredientPost, 'ingredients_stock', $stockQuantity)) {
                    $output['response'] = 'success';
                    $output['message'] = vmhEscapeTranslate('All ingredients created successfully');
                }

            } else {
                $output['response'] = 'invalid';
                $output['message'] = vmhEscapeTranslate('' . $ingredient[0] . ' ingredient is failed to create');
            }
        }

        return $output;
    }
}