<?php

function getHeaderMenu() {
    wp_nav_menu([
        'theme_location' => 'headerMenu',
        'container'      => '',
        'fallback_cb'    => false
    ]);
}

function getFooterMenu() {
    wp_nav_menu([
        'theme_location' => 'footerMenu',
        'container'      => '',
        'fallback_cb'    => false
    ]);
}

// Get all the woocommerce prooduct categories
/**
 * @return mixed
 */
function getProductCategories() {
    // woocommerce product taxonooy name
    $taxonomy = 'product_cat';

    $terms = get_terms($taxonomy);

    return $terms;
}

// Get all the releated products that are associated with product taxonomy
/**
 * @param  array   $taxonomyArgs
 * @return mixed
 */
function getProductsByCategory(array $taxonomyArgs) {
    $args = [
        'post_type'      => 'product',
        'posts_per_page' => $taxonomyArgs['postPerPage'],
        'tax_query'      => array(
            array(
                'taxonomy' => $taxonomyArgs['taxonomy'],
                'field'    => 'term_id',
                'terms'    => $taxonomyArgs['termID']
            )
        )
    ];
    $products = new WP_Query($args);

    // wp_console_log($products->posts);

    return $products->posts;
}

// Get the product ingredients
/**
 * @param  $productID
 * @return mixed
 */
function getProductIngrediants($productID, $includePTage = false) {
    $ingredients = get_post_meta($productID, 'product_ingredients', true);

    if (!$ingredients) {
        return '';
    }

    if (!is_array($ingredients)) {
        return '';
    }

    $ingredientsHTML = $includePTage ? '<p>' : '<ul>';
    foreach ($ingredients as $key => $singleIngredient) {
        $ingredientsHTML .= '
                                    ' . productIngredientsHTML($singleIngredient, $includePTage) . '
                                ';
    }
    return $ingredientsHTML;
    $ingredientsHTML .= $includePTage ? '<p>' : '</ul>';

    return '';

}

// Should include p tag or li tag in cart products ingredients

/**
 * @param  $singleIngredient
 * @param  $includePTage
 * @return mixed
 */
function productIngredientsHTML($singleIngredient, $includePTage) {
    if ($includePTage) {
        return '<p>' . trim(get_the_title($singleIngredient)) . '</p>';
    } else {
        return '<li><span>' . trim(get_the_title($singleIngredient)) . '</span></li>';
    }
}

// Get total quantity of current cart
/**
 * @return mixed
 */
function getTotalCartQuantity() {

    global $woocommerce;
    $items = $woocommerce->cart->get_cart();

    $cartQuantity = 0;

    if ($items) {
        foreach ($items as $key => $item) {
            $cartQuantity += intval($item['quantity']);
        }
    }

    return $cartQuantity;
}

// Get the total cart price
/**
 * @return mixed
 */
function getTotalCartPrice() {
    global $woocommerce;
    $items = $woocommerce->cart->get_cart();

    $totalPrice = 0;

    if ($items) {
        foreach ($items as $key => $item) {
            $totalPrice += $item['line_total'];
        }
    }

    $totalPrice = number_format($totalPrice, 2);

    return $totalPrice;
}

// Get the all cart items
/**
 * @return mixed
 */
function getCartItems() {
    global $woocommerce;
    $items = $woocommerce->cart->get_cart();
    return $items;
}

/**
 * @param  int     $productID
 * @return mixed
 */
function singleProductIngredientsHTML(int $productID) {
    $ingredients = get_post_meta($productID, 'product_ingredients', true);

    if (!$ingredients) {
        return '';
    }

    if (!is_array($ingredients)) {
        return '';
    }

    $ingredientsHTML = '';

    foreach ($ingredients as $key => $singleIngredient) {
        // Start Single ingredient item
        $ingredientsHTML .= '
            <div class="recepes_single_ingridient_item">
                <span>' . trim(get_the_title($singleIngredient)) . '</span>
                ' . ingredientsPecentage($key) . '
            </div>
            ';
        // End Single ingredient item
    }

    return $ingredientsHTML;

}

/**
 * @param $key
 */
function ingredientsPecentage($key) {
    $ingredientsPercentage = get_post_meta(get_the_ID(), 'ingredients_percentage_values', true);

    if (!$ingredientsPercentage) {
        return '';
    }

    if (!isset($ingredientsPercentage[$key]) || $ingredientsPercentage[$key] == '') {
        return '';
    }

    return '<b>' . $ingredientsPercentage[$key] . '%</b>';
}

// Get the use description from user database
/**
 * @return mixed
 */
function getUserDescription() {
    $userID = get_current_user_id();
    $userDescription = get_user_meta($userID, 'description', true);

    if ($userDescription && $userDescription !== '') {
        return $userDescription;
    }

    return 'User don\'t have bio yet';
}

// Check if a product is added to user favorite box
/**
 * @param $productID
 */
function isProuductUserFavorite($productID) {
    $userID = get_current_user_id();
    $favoriteMetaKey = '_user_favorite';
    $favoriteProductsID = get_user_meta($userID, $favoriteMetaKey, true);

    // Check if a product is found in user favorite meta data
    if (!$favoriteProductsID) {
        return false;
    }
    if (($key = array_search($productID, $favoriteProductsID)) !== false) {
        return true;
    } else {
        return false;
    }
}

// Get user favorite products
function getUserFavoriteProducts() {
    $userID = get_current_user_id();
    $favoriteMetaKey = '_user_favorite';
    $favoriteProductsID = get_user_meta($userID, $favoriteMetaKey, true);

    if (is_array($favoriteProductsID) && count($favoriteProductsID) > 0) {

        foreach ($favoriteProductsID as $key => $productID) {

            // If product exists and status is publish than load the favourite product template
            if (get_post($productID) && get_post_status($productID) == 'publish') {

                load_template(VMH_PATH . 'Includes/Templates/fav-product.php', false, [
                    'postTitle'   => get_the_title($productID),
                    'postAuthor'  => get_the_author_meta('display_name', get_post($productID)->post_author),
                    'productID'   => $productID,
                    'productType' => wc_get_product($productID)->get_type()
                ]);

            }
        }

    } else {
        echo '
        <div class="card">
            <div class="card-body">
              You have not added any products to your favorite collection.
            </div>
        </div>';
    }
}

/**
 * Get pg:vg options in recipe create form
 * @return mixed
 */
function displayPgVgOptions() {
    $pgVg = get_option('vmh_pg_vg') ? sanitize_text_field(get_option('vmh_pg_vg')) : null;

    if ($pgVg) {
        $pgVgArray = explode("|", $pgVg);

        $html = '<div class="custom-select">
                    <select name="vmh_pg_vg">';

        $html .= '<option value="">Choose</option>';
        foreach ($pgVgArray as $key => $value) {
            $html .= '<option value="' . esc_html(trim($value)) . '">' . esc_html(trim($value)) . '</option>';
        }

        $html .= '
            </select>
            </div>
                ';

        return $html;

    } else {
        return '';
    }
}

/**
 * Get nicotine type options in recipe create form
 * @return mixed
 */
function displayNicotineTypeOptions() {
    $pgVg = get_option('vmh_nicotine_type') ? sanitize_text_field(get_option('vmh_nicotine_type')) : null;

    if ($pgVg) {
        $pgVgArray = explode("|", $pgVg);

        $html = '<div class="custom-select">
                    <select name="vmh_nicotine_type">';

        $html .= '<option value="">Choose</option>';
        foreach ($pgVgArray as $key => $value) {
            $html .= '<option value="' . esc_html(trim($value)) . '">' . esc_html(trim($value)) . '</option>';
        }

        $html .= '
            </select>
            </div>
                ';

        return $html;

    } else {
        return '';
    }
}

/**
 * Get Nicotine Amount options in recipe create form
 * @return mixed
 */
function displayNicotineAmountOptions() {
    $pgVg = get_option('vmh_nicotine_amount') ? sanitize_text_field(get_option('vmh_nicotine_amount')) : null;

    if ($pgVg) {
        $pgVgArray = explode("|", $pgVg);

        $html = '<div class="custom-select">
                    <select name="vmh_nicotine_amount">';

        $html .= '<option value="">Choose</option>';
        foreach ($pgVgArray as $key => $value) {
            $html .= '<option value="' . esc_html(trim($value)) . '">' . esc_html(trim($value)) . '</option>';
        }

        $html .= '
            </select>
            </div>
                ';

        return $html;

    } else {
        return '';
    }
}

/**
 * Get bottle size options in recipe create form
 * @return mixed
 */
function displayBottleSizeOptions() {
    $pgVg = get_option('vmh_bottle_size') ? sanitize_text_field(get_option('vmh_bottle_size')) : null;

    if ($pgVg) {
        $pgVgArray = explode("|", $pgVg);

        $html = '<div class="custom-select">
                    <select name="vmh_bottle_size">';

        $html .= '<option value="">Choose</option>';
        foreach ($pgVgArray as $key => $value) {
            $html .= '<option value="' . esc_html(trim($value)) . '">' . esc_html(trim($value)) . '</option>';
        }

        $html .= '
            </select>
            </div>
                ';

        return $html;

    } else {
        return '';
    }
}

/**
 * Get simple product options created by user
 * @return mixed
 */
function simpleProductOptions() {
    $productID = get_the_ID();
    $optionsHTML = '';
    $productOptions = get_post_meta($productID, 'product_options', true);

    $callbackClass = new \VmhHub\Includes\Classes\HookCallbacks();

    $productAttributes = $callbackClass->vmhProductAttributes();

    if (is_array($productOptions)) {
        foreach ($productOptions as $key => $option) {
            $optionKeys = array_keys($option)[0];
            $optionsHTML .= '
            <div class="recepes_single_choose_option">
                <h4>' . esc_html($productAttributes[$optionKeys]) . ' :</h4>
                <span class="vmh_simple_option_value">' . esc_html($option[$optionKeys]) . '</span>
            </div>
           ';
        }
        return $optionsHTML;
    } else {
        return '';
    }
}

// Show earning section html
function showEarningSectionHtml() {
    $userID = get_current_user_id();
    $user = get_userdata($userID);
    // Get all the user roles as an array.
    $user_roles = $user->roles;

    if (in_array('subscriber', $user_roles)) {
        return '
            <h4>Earned: ' . totalEarningOfUser() . '</h4>
            <p>You Will be paid by end of each calendar month</p>
        ';
    } else {
        return '
            <p>The section is only for subscribers</p>
        ';
    }
}

/**
 * @return mixed
 */
function totalEarningOfUser() {

    $userID = get_current_user_id();
    $user = get_userdata($userID);
    // Get all the user roles as an array.
    $user_roles = $user->roles;

    $earnings = '';

    if (in_array('subscriber', $user_roles)) {
        if (get_user_meta($userID, 'user_commision', true)) {
            $earnings = get_woocommerce_currency_symbol() . ' ' . number_format(get_user_meta($userID, 'user_commision', true), 2);
        } else {
            $earnings = get_woocommerce_currency_symbol() . " " . '0.00';
        }
    } else {
        $earnings = get_woocommerce_currency_symbol() . " " . '0.00';
    }

    return $earnings;
}

// Get user created post
function getUserCreatedRecipe() {
    $args = array(
        'author'         => get_current_user_id(),
        'posts_per_page' => -1,
        'post_type'      => 'product'
    );

    $recipes = get_posts($args);

    if ($recipes) {
        foreach ($recipes as $key => $recipe) {
            if (wc_get_product($recipe)->get_id() != get_option('vmh_create_product_option')) {
                load_template(VMH_PATH . 'Includes/Templates/product.php', false, [
                    'postTitle'    => $recipe->post_title,
                    'postAuthorID' => $recipe->post_author,
                    'productID'    => $recipe->ID,
                    'productType'  => wc_get_product($recipe)->get_type()
                ]);
            }
        }
    } else {
        echo '
        <div class="card">
            <div class="card-body">
              Oops. There are no products found for you.
            </div>
        </div>';
    }
}

// Recently added products
/**
 * @param $postPerPage
 */
function getRecentProducts($postPerPage) {
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => $postPerPage
    );

    $recipes = get_posts($args);

    if ($recipes) {
        foreach ($recipes as $key => $recipe) {
            $timeDiff = (time() - strtotime($recipe->post_date)) / (60 * 60 * 24);

            if ($timeDiff <= 10) {
                if (wc_get_product($recipe)->get_id() != get_option('vmh_create_product_option')) {
                    load_template(VMH_PATH . 'Includes/Templates/product.php', false, [
                        'postTitle'    => $recipe->post_title,
                        'postAuthorID' => $recipe->post_author,
                        'productID'    => $recipe->ID,
                        'productType'  => wc_get_product($recipe)->get_type()
                    ]);
                }
            }
        }
    } else {
        echo '
        <div class="card">
            <div class="card-body">
              No recently created product is found
            </div>
        </div>';
    }
}

/**
 * Get recommeended products
 * @param $postPerPage
 */
function getRecommendedProducts($postPerPage) {

    $userID = get_current_user_id();
    $favoriteMetaKey = '_user_favorite';
    $favoriteProductsID = get_user_meta($userID, $favoriteMetaKey, true);

    $uniqueCategories = [];
    $products = [];

    if ($favoriteProductsID) {
        foreach ($favoriteProductsID as $key => $productID) {
            $categories = get_the_terms($productID, 'product_cat');
            if ($categories) {
                foreach ($categories as $key => $cat) {
                    // insert unquie categories to array
                    if (!array_key_exists($cat->slug, $uniqueCategories)) {
                        $uniqueCategories[$cat->term_id] = $cat->name;
                    }
                }
            }
        }
    }

    if ($uniqueCategories) {
        foreach ($uniqueCategories as $key => $uniqueCategorie) {
            $productsByCategorie = getProductsByCategory([
                'taxonomy'    => 'product_cat',
                'termID'      => $key,
                'postPerPage' => $postPerPage
            ]);

            if ($productsByCategorie) {
                foreach ($productsByCategorie as $key => $value) {
                    array_push($products, $value);
                }
            }
        }
    }

    // if we have recommeded product show them
    if ($products) {
        foreach ($products as $key => $product) {
            if (wc_get_product($product)->get_type() === 'simple' || wc_get_product($product)->get_type() === 'variable') {
                if (wc_get_product($product)->get_id() != get_option('vmh_create_product_option')) {
                    load_template(VMH_PATH . 'Includes/Templates/product.php', false, [
                        'postTitle'    => $product->post_title,
                        'postAuthorID' => $product->post_author,
                        'productID'    => $product->ID,
                        'productType'  => wc_get_product($product)->get_type()
                    ]);
                }
            }
        }
    } else {
        echo '
        <div class="card">
            <div class="card-body">
              No recommendation found yet. Try to bookmark a product.
            </div>
        </div>';
    }

}

/**
 * Get all the ingredients post type for showing into select box
 * @return mixed
 */
function getAllIngredients() {
    $args = [
        'post_type'      => 'ingredient',
        'posts_per_page' => -1,
        'post_status'    => 'publish'
    ];

    $ingredients = get_posts($args);

    if (!$ingredients) {
        return '';
    }

    $options = '<option data-placeholder="true"></option>';

    foreach ($ingredients as $key => $ingredient) {
        $options .= '<option class="' . addDisableAttr($ingredient->ID) . '" ' . addDisableAttr($ingredient->ID) . ' value="' . esc_attr($ingredient->ID) . '" >
                        ' . esc_html($ingredient->post_title) . ' (' . ingredientInStock($ingredient->ID) . ')
                    </option>';
    }

    return $options;
}

/**
 * @param $postID
 */
function ingredientInStock($postID) {
    $ingredientStock = get_post_meta($postID, 'ingredients_stock', true);
    if ($ingredientStock > 0) {
        return 'In Stock';
    } else {
        return 'Out of stock';
    }
}

/**
 * @param $postID
 */
function addDisableAttr($postID) {
    $ingredientStock = get_post_meta($postID, 'ingredients_stock', true);
    if ($ingredientStock > 0) {
        return '';
    } else {
        return 'disabled';
    }
}