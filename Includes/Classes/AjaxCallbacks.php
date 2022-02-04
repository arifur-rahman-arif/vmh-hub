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
                        'message'  => esc_html('Username or password is incorrect. Try again')
                    ];
                }
            }
            if (array_key_exists("invalid_username", $res->errors)) {
                if ($res->errors['invalid_username'][0]) {
                    return [
                        'response' => 'error',
                        'message'  => esc_html('Username or password is incorrect. Try again')
                    ];
                }
            }
            if (array_key_exists("invalid_email", $res->errors)) {
                if ($res->errors['invalid_email'][0]) {
                    return [
                        'response' => 'error',
                        'message'  => esc_html('Username or password is incorrect. Try again')
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
            wp_send_json_error($output, 400);
            wp_die();
        }

        $productID = sanitize_text_field($_POST['productID']);
        $nicotineShotValue = sanitize_text_field($_POST['nicotineShotValue']);

        if (!$productID) {
            $output['response'] = 'invalid';
            $output['message'] = vmhEscapeTranslate('Product ID is missing');
            wp_send_json_error($output, 400);
            wp_die();
        }

        if (!$nicotineShotValue) {
            $output['response'] = 'invalid';
            $output['message'] = vmhEscapeTranslate('Nicotine shot is missing');
            wp_send_json_error($output, 400);
            wp_die();
        }

        $variationsValue = $this->sanitizeData($_POST['variationsValue']);

        if (!$variationsValue || count($variationsValue) < count(vmhProductAttributes())) {
            $output['response'] = 'invalid';
            $output['message'] = vmhEscapeTranslate('1 or more varitions are missing');
            wp_send_json_error($output, 400);
            wp_die();
        }

        $variationData = $this->getMatchingVariationID($productID, $variationsValue);

        if ($variationData['response'] == 'invalid') {
            wp_send_json_error($variationData, 404);
            wp_die();
        }

        if (get_post_meta($productID, "_stock_status", true) !== 'instock') {
            $output['response'] = 'invalid';
            $output['message'] = vmhEscapeTranslate('Product is out of stock');
            wp_send_json_error($output, 404);
            wp_die();
        }

        $cartItemData = [];

        $cartItemData['nicotine_shot_value'] = $nicotineShotValue;
        $cartItemData['nicotine_shot_calculated_value'] = $nicotineShotValue;

        // Add the ingredients total price to cart
        $attributes = wc_get_product_variation_attributes($variationData['variationID']);
        $productIngredients = get_post_meta($productID, 'product_ingredients', true);
        $ingredientsPercentageValues = get_post_meta($productID, 'ingredients_percentage_values', true);
        $bottleSize = $attributes['attribute_pa_vmh_bottle_size'];
        $ingredientsTotalPrice = getIngredientsTotalPrice([
            'productIngredients'          => $productIngredients,
            'ingredientsPercentageValues' => $ingredientsPercentageValues,
            'bottleSize'                  => $bottleSize
        ]);
        $cartItemData['ingredientsTotalPrice'] = $ingredientsTotalPrice;

        $isAdded = WC()->cart->add_to_cart($productID, 1, $variationData['variationID'], [], $cartItemData);

        if ($isAdded) {
            $output['response'] = 'success';
            $output['message'] = vmhEscapeTranslate('Your product is added to your cart');
            $output['productPrice'] = wc_get_product($productID)->get_price();
            wp_send_json_success($output, 200);
            wp_die();
        }

        $output['response'] = 'invalid';
        $output['message'] = vmhEscapeTranslate('Product can not be added to cart');
        $output['productPrice'] = wc_get_product($productID)->get_price();
        wp_send_json_error($output, 400);
        wp_die();

    }

    /**
     * Get a variable product matching variations varition ID
     * @param  $productID
     * @param  $attributes
     * @return mixed
     */
    public function getMatchingVariationID($productID, $variationsValue) {
        $returnValue = [];
        $product = wc_get_product($productID);

        if (!$product) {
            $returnValue['response'] = 'invalid';
            $returnValue['message'] = vmhEscapeTranslate('Product not found');
            $returnValue['code'] = 404;
            return $returnValue;
        }

        $variations = $product->get_available_variations();

        if (!$variations || count($variations) < 1) {
            $returnValue['response'] = 'invalid';
            $returnValue['message'] = vmhEscapeTranslate('No variations found');
            $returnValue['code'] = 404;
            return $returnValue;
        }

        $attributes = $this->getOrganizedAttributes($variationsValue);

        if (!$attributes || count($attributes) < 1) {
            $returnValue['response'] = 'invalid';
            $returnValue['message'] = vmhEscapeTranslate('Unable get attributes');
            $returnValue['code'] = 404;
            return $returnValue;
        }

        $variationID = null;

        foreach ($variations as $key => $variation) {
            $currentAttributes = $variation['attributes'];

            if (!is_array($currentAttributes) || count($currentAttributes) < 1) {
                continue;
            }

            $allMatch = [];

            foreach ($attributes as $attrKey => $attributeValue) {
                $dataAttributeValue = $currentAttributes['attribute_pa_' . $attrKey];
                if ($attributeValue === $dataAttributeValue) {
                    $allMatch[] = true;
                } else {
                    $allMatch[] = false;
                }
            }

            // Check if all match value is true
            // if true exit the loop and return the variation ID
            if (array_unique($allMatch) === [true]) {
                $variationID = $variation['variation_id'];
                break;
            }

        }

        if ($variationID) {
            $returnValue['response'] = 'success';
            $returnValue['message'] = vmhEscapeTranslate('Varition ID is #' . $variationID . '');
            $returnValue['variationID'] = $variationID;
            $returnValue['code'] = 200;
            return $returnValue;
        }

        $returnValue['response'] = 'invalid';
        $returnValue['message'] = vmhEscapeTranslate('No variations ID found');
        $returnValue['code'] = 404;
        return $returnValue;
    }

    /**
     * Organize the attibutes key and its value
     * @param  $variationsValue
     * @return mixed
     */
    public function getOrganizedAttributes($variationsValue) {
        $attributes = [];

        if (!$variationsValue || count($variationsValue) < 1) {
            return $attributes;
        }

        foreach ($variationsValue as $i => $variation) {

            $attributeKey = trim(explode("|", $variation[array_keys($variation)[0]][0])[0]);
            $optionValue = trim(array_keys($variation)[0]);

            $attributes[$attributeKey] = $optionValue;
        }

        return $attributes;
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
                    $output['message'] = vmhEscapeTranslate('Product do not exists to user favorite');
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

        $categoryName = 'Pending Product';

        wp_insert_term(
            'Pending Product',
            'product_cat',
            [
                'description' => 'Description for category', // optional
                'parent'      => 0, // optional
                'slug'        => 'pending-product' // optional
            ]
        );

        //Check if category already exists
        $term = get_term_by('slug', 'pending-product', 'product_cat');

        $categoryID = null;

        if ($term) {
            $categoryID = $term->term_id;
        }

        //If it doesn't exist create new category
        if (!$categoryID) {
            wp_insert_term(
                'Pending Product',
                'product_cat',
                [
                    'description' => 'Description for category', // optional
                    'parent'      => 0, // optional
                    'slug'        => 'pending-product' // optional
                ]
            );
        }

        //Get ID of category again incase a new one has been created
        $categoryID = get_term_by('slug', 'pending-product', 'product_cat')->term_id;

        $postArg = [
            'post_author'   => $userID,
            'post_title'    => $sanitizedData['productName'],
            'post_content'  => $sanitizedData['recipeNote'],
            'post_status'   => 'publish',
            'post_type'     => "product",
            'post_category' => [$categoryID]
        ];

        if ($sanitizedData['recipeAction'] === 'save-recepie') {

            $templateProductID = get_option('vmh_create_product_option');

            if ($templateProductID) {
                $productObject = wc_get_product($templateProductID);

                if (!$productObject->is_type('variable')) {
                    $output['response'] = 'invalid';
                    $output['message'] = vmhEscapeTranslate('Template product is not a variable product');
                    echo json_encode($output);
                    wp_die();
                }

                // Duplicate the template product for creating a new product
                $duplicateProduct = new \WC_Admin_Duplicate_Product;
                $newProduct = $duplicateProduct->product_duplicate($productObject);
                $postID = $newProduct->get_id();

                // Update the post tile after creating the variable product
                $postArg['ID'] = $postID;

                wp_set_post_terms($postID, [$categoryID], 'product_cat');

                wp_update_post($postArg);

            }

        }

        if ($sanitizedData['recipeAction'] === 'update-recepie') {
            $productID = isset($sanitizedData['proudctID']) ? intval($sanitizedData['proudctID']) : null;

            $postAuthor = get_post($productID)->post_author;

            if ($postAuthor != get_current_user_id()) {
                $output['response'] = 'invalid';
                $output['message'] = vmhEscapeTranslate('User do not have permission to edit product');
                echo json_encode($output);
                wp_die();
            }

            $postArg['ID'] = $productID;
            $postArg['post_status'] = 'publish';
            $postID = wp_update_post($postArg);
        }

        if (!is_wp_error($postID)) {

            $productOptions = isset($sanitizedData['optionsValue']) ? $sanitizedData['optionsValue'] : null;
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
            $output['id'] = $postID;

            if ($sanitizedData['recipeAction'] === 'save-recepie') {
                $output['message'] = vmhEscapeTranslate('Your recipe has been created successfully. You can purchase this recipe by selecting other required options.');
            }

            if ($sanitizedData['recipeAction'] === 'update-recepie') {
                $output['message'] = vmhEscapeTranslate('Recipe updated successfully');
            }

            $this->sendEmailToAdmins($postID);

            echo json_encode($output);
            wp_die();
        }

        $output['response'] = 'invalid';
        $output['message'] = vmhEscapeTranslate('Recipe could not be created');
        echo json_encode($output);
        wp_die();

    }

    public function calculatedIngredientPrice() {
        $output = [];

        if (sanitize_text_field($_POST['action']) !== 'vmh_get_ingredients_price') {
            $output['response'] = 'invalid';
            $output['message'] = vmhEscapeTranslate('Action is not valid');
            wp_send_json_error($output, 400);
            wp_die();
        }

        $sanitizedData = $this->sanitizeData($_POST);

        $productID = $sanitizedData['productID'];
        $bottleSize = $sanitizedData['bottleSize'];

        if (!$productID) {
            $output['response'] = 'invalid';
            $output['message'] = vmhEscapeTranslate('Product ID is missing or invalid product ID');
            wp_send_json_error($output, 400);
            wp_die();
        }

        if (!$bottleSize) {
            $output['response'] = 'invalid';
            $output['message'] = vmhEscapeTranslate('Bottle size is a required field');
            wp_send_json_error($output, 400);
            wp_die();
        }

        $productIngredients = get_post_meta($productID, 'product_ingredients', true);
        $ingredientsPercentageValues = get_post_meta($productID, 'ingredients_percentage_values', true);

        $ingredientsTotalPrice = getIngredientsTotalPrice([
            'productIngredients'          => $productIngredients,
            'ingredientsPercentageValues' => $ingredientsPercentageValues,
            'bottleSize'                  => $bottleSize
        ]);

        if ($ingredientsTotalPrice) {
            $ingredientsTotalPrice = number_format($ingredientsTotalPrice, 2);

            $output['response'] = 'success';
            $output['message'] = vmhEscapeTranslate('Total price of ingredients: ' . get_woocommerce_currency_symbol() . $ingredientsTotalPrice);
            $output['price'] = $ingredientsTotalPrice;
            wp_send_json_success($output, 200);
            wp_die();
        }

        $output['response'] = 'invalid';
        $output['message'] = vmhEscapeTranslate('Unable to get ingredients price');
        wp_send_json_error($output, 400);
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
                $ingredientPrice = $ingredient[2];

                if (update_post_meta($ingredientPost, 'ingredients_stock', $stockQuantity) &&
                    update_post_meta($ingredientPost, 'ingredients_price', $ingredientPrice)
                ) {
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
            $cart = WC()->cart->get_cart();

            $cartItem = $cart[$cartKey];

            $cartItem['nicotine_shot_value'] = $nicotineShot;

            WC()->cart->cart_contents[$cartKey] = $cartItem;

            WC()->cart->calculate_totals();

            $output['response'] = 'success';
            $output['message'] = vmhEscapeTranslate('Nicotine shot updated');
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

    // Trigger vmh_create_product_attribute_action hook to create the woocommerce product attributes
    public function createProductAttributes() {
        $output = [];

        if (sanitize_text_field($_POST['action']) !== 'vmh_create_product_attribute') {
            $output['response'] = 'invalid';
            $output['message'] = vmhEscapeTranslate('Action is not valid');
            echo json_encode($output);
            wp_die();
        }

        do_action('vmh_create_product_attribute_action');

        $output['response'] = 'success';
        $output['message'] = vmhEscapeTranslate('Product attributes are created');
        echo json_encode($output);
        wp_die();

    }

    // Trigger vmh_create_product_taxonomy_action hook to create the taxonomies terms
    // This hoook comes after this vmh_create_product_attribute_action hook
    public function createProductTaxonomies() {
        $output = [];

        if (sanitize_text_field($_POST['action']) !== 'vmh_create_product_taxonomy') {
            $output['response'] = 'invalid';
            $output['message'] = vmhEscapeTranslate('Action is not valid');
            echo json_encode($output);
            wp_die();
        }

        do_action('vmh_create_product_taxonomy_action');

        $output['response'] = 'success';
        $output['message'] = vmhEscapeTranslate('Product attributes are created');
        echo json_encode($output);
        wp_die();

    }

}