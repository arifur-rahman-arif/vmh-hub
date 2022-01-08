<?php
namespace VmhHub\Includes\Classes;

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

        $postID = null;

        $postArg = [
            'post_author'  => $userID,
            'post_title'   => $sanitizedData['productName'],
            'post_content' => $sanitizedData['recipeNote'],
            'post_status'  => 'pending',
            'post_type'    => "product"
        ];

        if ($sanitizedData['recipeAction'] === 'save-recepie') {
            $postID = wp_insert_post($postArg);
        }

        if ($sanitizedData['recipeAction'] === 'update-recepie') {
            $productID = isset($sanitizedData['proudctID']) ? intval($sanitizedData['proudctID']) : null;

            $postAuthor = get_post($productID)->post_author;

            if ($postAuthor != get_current_user_id()) {
                $output['response'] = 'invalid';
                $output['message'] = vmhEscapeTranslate('User don\'t have permission to edit product');
                echo json_encode($output);
                wp_die();
            }

            $postArg['ID'] = $productID;
            $postArg['post_status'] = 'publish';
            $postID = wp_update_post($postArg);
        }

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
                update_post_meta($postID, 'product_options', $productOptions);
            }

            // add product ingredients
            if ($productIngredients) {
                update_post_meta($postID, 'product_ingredients', $productIngredients);
            }

            // add product ingredients
            if ($ingredientsPercentageValues) {
                update_post_meta($postID, 'ingredients_percentage_values', $ingredientsPercentageValues);
            }

            // add product tags
            if ($productTags) {
                wp_set_object_terms($postID, $productTags, 'product_tag');
            }

            $output['response'] = 'success';

            if ($sanitizedData['recipeAction'] === 'save-recepie') {
                $output['message'] = vmhEscapeTranslate('Product created successfully');
            }

            if ($sanitizedData['recipeAction'] === 'update-recepie') {
                $output['message'] = vmhEscapeTranslate('Product updated successfully');
            }

            $this->sendEmailToAdmins($postID);

            echo json_encode($output);
            wp_die();
        }

        $output['response'] = 'invalid';
        $output['message'] = vmhEscapeTranslate('Product couldn\'t be created');
        echo json_encode($output);
        wp_die();

    }

    /**
     * Send notification email to all admins if user created a product successfully
     * @param  $productID
     * @return null
     */
    public function sendEmailToAdmins($productID) {

        $users = getAdministrators();

        if (!$users) {
            return;
        }

        $currentUserData = get_userdata(get_current_user_id());

        if (!$currentUserData) {
            return;
        }

        foreach ($users as $user) {

            $to = $user->user_email;

            $displayName = ($currentUserData->first_name . ' ' . $currentUserData->last_name);

            $adminName = $user->display_name;

            $subject = 'Needs approval for new product';

            $message = '
                Hello <i>' . $adminName . '</i>. <i>' . $displayName . '</i> has created new a reciepe and waiting for your approval.
                <br/>
                <b>Product ID:</b> ' . $productID . '
                <br/>
                <b>Product name:</b> ' . get_the_title($productID) . '
                <br/>
                <b>Creator email:</b> ' . $currentUserData->user_email . '
            ';

            $headers = ['Content-Type: text/html; charset=UTF-8'];

            try {
                if (!wp_mail($to, $subject, $message, $headers)) {
                    throw 'Error: Mail couldn\'t be sent.';
                }
            } catch (\Throwable $th) {
                throw $th;
            }

        }

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

    /**
     * @param  array   $ingredients
     * @return mixed
     */
    public function createSubscriber() {
        $output = [];

        if (sanitize_text_field($_POST['action']) !== 'vmh_subscriber_action') {
            $output['response'] = 'invalid';
            $output['message'] = vmhEscapeTranslate('Action is not valid');
            echo json_encode($output);
            wp_die();
        }

        parse_str($_POST['formData'], $parsedData);

        $sanitizedData = $this->sanitizeData($parsedData);

        if (!isset($sanitizedData['subscriber_mail']) || !$sanitizedData['subscriber_mail']) {
            $output['response'] = 'invalid';
            $output['message'] = vmhEscapeTranslate('Email is required to be a subscriber');
            echo json_encode($output);
            wp_die();
        }

        $subscribers = get_posts([
            "post_type" => 'subscriber',
            "s"         => $sanitizedData['subscriber_mail']
        ]);

        if ($subscribers) {
            $output['response'] = 'invalid';
            $output['message'] = vmhEscapeTranslate('Email already exists');
            echo json_encode($output);
            wp_die();
        }

        $args = [
            'post_title'  => esc_html($sanitizedData['subscriber_mail']),
            'post_type'   => 'subscriber',
            'post_status' => 'publish'
        ];

        $ingredientPost = wp_insert_post($args);

        if (!is_wp_error($ingredientPost)) {
            $output['response'] = 'success';
            $output['message'] = vmhEscapeTranslate('You have successfully subscribed.');
        } else {
            $output['response'] = 'invalid';
            $output['message'] = vmhEscapeTranslate('Can not add subscriber. Try again');
        }

        echo json_encode($output);
        wp_die();
    }

    // Remove product from subscriber product list and make that product to admin product list
    public function removeProduct() {
        $output = [];

        if (sanitize_text_field($_POST['action']) !== 'vmh_remove_product_action') {
            $output['response'] = 'invalid';
            $output['message'] = vmhEscapeTranslate('Action is not valid');
            echo json_encode($output);
            wp_die();
        }

        if (!isset($_POST['productID']) || !$_POST['productID']) {
            $output['response'] = 'invalid';
            $output['message'] = vmhEscapeTranslate('Product ID is missing.');
            echo json_encode($output);
            wp_die();
        }

        $productID = sanitize_text_field($_POST['productID']);

        // if (!get_option('vmh_main_admin')) {
        //     $output['response'] = 'invalid';
        //     $output['message'] = vmhEscapeTranslate('Product authority could not be moved to admin.');
        //     echo json_encode($output);
        //     wp_die();
        // }

        // $response = wp_update_post(
        //     [
        //         'ID'          => $productID,
        //         'post_author' => get_option('vmh_main_admin')
        //     ]
        // );

        $response = wp_delete_post($productID);

        if (!is_wp_error($response)) {
            $output['response'] = 'success';
            $output['message'] = vmhEscapeTranslate('Product is removed from your list');
        } else {
            $output['response'] = 'invalid';
            $output['message'] = vmhEscapeTranslate('Product is not removed from your list. Try again');
        }

        echo json_encode($output);
        wp_die();
    }

    /* Update nicotineshot value via ajax from cart page */
    public function updateNicotineshotValue() {
        $output = [];

        if (sanitize_text_field($_POST['action']) !== 'vmh_update_nicotineshot') {
            $output['response'] = 'invalid';
            $output['message'] = vmhEscapeTranslate('Action is not valid');
            echo json_encode($output);
            wp_die();
        }

        if (!isset($_POST['cartKey']) || !$_POST['cartKey']) {
            $output['response'] = 'invalid';
            $output['message'] = vmhEscapeTranslate('Cart key is missing');
            echo json_encode($output);
            wp_die();
        }

        if (!isset($_POST['nicotineShot'])) {
            $output['response'] = 'invalid';
            $output['message'] = vmhEscapeTranslate('Nicotine shot is requried to update');
            echo json_encode($output);
            wp_die();
        }

        $cartKey = sanitize_text_field($_POST['cartKey']);

        $nicotineShot = intval(sanitize_text_field($_POST['nicotineShot']));

        if ($nicotineShot < 0) {
            $output['response'] = 'invalid';
            $output['message'] = vmhEscapeTranslate('Nicotine shot can not be negative value');
            echo json_encode($output);
            wp_die();
        }

        try {
            $cart = WC()->cart->cart_contents;

            $cartItem = $cart[$cartKey];

            $cartItem['nicotine_shot_value'] = $nicotineShot;

            WC()->cart->cart_contents[$cartKey] = $cartItem;

            WC()->cart->set_session();

            // $cartTotal = WC()->cart->cart_contents_total;

            $output['response'] = 'success';
            $output['message'] = vmhEscapeTranslate('Nicotine shot updated');
            // $output['cartTotal'] = $cartTotal;
            echo json_encode($output);
            wp_die();

        } catch (\Throwable $th) {

            $output['response'] = 'invalid';
            $output['message'] = vmhEscapeTranslate($th->getMessage());
            echo json_encode($output);
            wp_die();
        }

        wp_die();
    }

}