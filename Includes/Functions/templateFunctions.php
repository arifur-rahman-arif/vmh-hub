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
        'post__not_in'   => [get_option('vmh_create_product_option')],
        'tax_query'      => [
            [
                'taxonomy' => $taxonomyArgs['taxonomy'],
                'field'    => 'term_id',
                'terms'    => $taxonomyArgs['termID']
            ],
            [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => 'pending-product',
                'operator' => 'NOT IN'
            ],
            [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => 'duplicate-product',
                'operator' => 'NOT IN'
            ]
        ]
    ];
    $products = new WP_Query($args);

    return $products->posts;
}

// Get the product ingredients
/**
 * @param  $productID
 * @return mixed
 */
function getProductIngrediants($productID, $includePTage = false) {

    $productContents = organizePercentageAndIngredients($productID);

    if (!$productContents) {
        return '';
    }

    if (!is_array($productContents)) {
        return '';
    }

    $ingredientsHTML = $includePTage ? '<p>' : '<ul>';
    foreach ($productContents as $key => $singleIngredient) {
        $ingredientsHTML .= '
                                    '.productIngredientsHTML($singleIngredient, $includePTage).'
                                ';
    }
    return $ingredientsHTML;
    $ingredientsHTML .= $includePTage ? '<p>' : '</ul>';

}

/**
 * Organize the proudcut ingredients and its percentage value to show it in a organized way
 * @param $productID
 */
function organizePercentageAndIngredients($productID) {

    $ingredients = get_post_meta($productID, 'product_ingredients', true);

    $productPercentage = get_post_meta($productID, 'ingredients_percentage_values', true);

    if (!is_array($ingredients) || count($ingredients) < 1) {
        return [];
    }

    $organizedValues = [];

    // If there are no percentage value than return only the ingredeints
    if (!is_array($productPercentage) || count($productPercentage) < 1) {
        foreach ($ingredients as $key => $ingredient) {
            $organizedValues[] = esc_html(get_the_title($ingredient));
        }
        return $organizedValues;
    }

    foreach ($ingredients as $key => $ingredient) {

        if (isset($productPercentage[$key]) && $productPercentage[$key]) {

            if (isset($organizedValues[$productPercentage[$key]])) {
                $organizedValues[$productPercentage[$key] + 1] = ''.get_the_title($ingredient).' '.$productPercentage[$key].'%';
            } else {
                $organizedValues[$productPercentage[$key]] = ''.get_the_title($ingredient).' '.$productPercentage[$key].'%';
            }

        }
    }

    krsort($organizedValues);

    return $organizedValues;
}

// Should include p tag or li tag in cart products ingredients

/**
 * @param  $singleIngredient
 * @param  $includePTage
 * @return mixed
 */
function productIngredientsHTML($singleIngredient, $includePTage) {
    if ($includePTage) {
        return '<p>'.trim($singleIngredient).'</p>';
    } else {
        return '<li><span>'.trim($singleIngredient).'</span></li>';
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
    $total = ((float) WC()->cart->total - (float) WC()->cart->shipping_total);
    return number_format($total, 2);
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
                <span>'.trim(get_the_title($singleIngredient)).'</span>
                '.ingredientsPecentage($key).'
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

    return '<b>'.$ingredientsPercentage[$key].'%</b> <input type="hidden" class="ingredient_percentage_normal" value="'.esc_attr($ingredientsPercentage[$key]).'" />';
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

        $hasFavorite = false;

        foreach ($favoriteProductsID as $key => $productID) {

            // If product exists and status is publish than load the favourite product template
            if (get_post($productID) && get_post_status($productID) == 'publish') {

                $hasFavorite = true;

                load_template(VMH_PATH.'Includes/Templates/fav-product.php', false, [
                    'postTitle'   => get_the_title($productID),
                    'postAuthor'  => get_the_author_meta('user_login', get_post($productID)->post_author),
                    'productID'   => $productID,
                    'productType' => wc_get_product($productID)->get_type()
                ]);

            } else {
                $hasFavorite = false;
            }
        }

        if (!$hasFavorite) {
            echo '
            <div class="card">
                <div class="card-body">
                  You have not added any recipe to your favorite collection.
                </div>
            </div>';
        }

    } else {
        echo '
        <div class="card">
            <div class="card-body">
              You have not added any recipe to your favorite collection.
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
            $html .= '<option value="'.esc_html(trim($value)).'">'.esc_html(trim($value)).'</option>';
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
            $html .= '<option value="'.esc_html(trim($value)).'">'.esc_html(trim($value)).'</option>';
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
            $html .= '<option value="'.esc_html(trim($value)).'">'.esc_html(trim($value)).'</option>';
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
            $html .= '<option value="'.esc_html(trim($value)).'">'.esc_html(trim($value)).'</option>';
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
 * Get simple product options created by user from a variable product
 * @return mixed
 */
function simpleProductOptions() {
    $productID = get_the_ID();
    $optionsHTML = '';
    $productOptions = get_post_meta($productID, 'product_options', true);

    $productAttributes = vmhProductAttributes();

    if (is_array($productOptions) && is_array($productAttributes)) {

        $i = 0;

        foreach ($productAttributes as $key => $option) {

            $optionsHTML .= '
                <div class="recepes_single_choose_option">
                    <h4>'.esc_html($option).' :</h4>
                    <span class="vmh_simple_option_value">'.getOrganizedAttributes($productOptions, $key, $i)[1].'</span>
                </div>
               ';

            $i += 1;
        }
        return $optionsHTML;
    } else {
        return '';
    }
}

/**
 * Get the attributes and its values in an organized array
 * @param $productOptions
 * @param $key
 * @param $i
 */
function getOrganizedAttributes($productOptions, $key, $i) {
    if (!isset($productOptions[$i]) || !$productOptions[$i]) {
        return '';
    }

    $optionsArray = $productOptions[$i];

    $attributeKey = explode("|", $optionsArray[array_keys($optionsArray)[0]][0])[0];
    $optionValue = trim(explode("|", $optionsArray[array_keys($optionsArray)[0]][0])[1]);

    return [
        $attributeKey,
        $optionValue
    ];
}

// Show earning section html
function showEarningSectionHtml() {
    if (userRole('subscriber')) {
        return '
            <h4>Earned: '.totalEarningOfUser().'</h4>
            <p>You will be paid by end of each calendar month</p>
        ';
    } else {
        return '
            <p>The section is only for subscribers. Create a <a style="color: white;text-decoration: underline;" href="'.wp_login_url('/login').'">subsriber account or login to a subscriber account</a></p>
        ';
    }
}

/**
 * check if the current user is admin
 * @param string $role
 */
function userRole(string $role) {
    $userID = get_current_user_id();
    $user = get_userdata($userID);
    // Get all the user roles as an array.
    $user_roles = $user->roles;

    if (in_array($role, $user_roles)) {
        return true;
    } else {
        return false;
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
        if (get_user_meta($userID, 'user_commission', true)) {
            $earnings = get_woocommerce_currency_symbol().' '.number_format(get_user_meta($userID, 'user_commission', true), 2);
        } else {
            $earnings = get_woocommerce_currency_symbol()." ".'0.00';
        }
    } else {
        $earnings = get_woocommerce_currency_symbol()." ".'0.00';
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
                load_template(VMH_PATH.'Includes/Templates/my-recipe-product.php', false, [
                    'postTitle'     => $recipe->post_title,
                    'postAuthorID'  => $recipe->post_author,
                    'productID'     => $recipe->ID,
                    'productType'   => wc_get_product($recipe)->get_type(),
                    'createdRecipe' => true
                ]);
            }
        }
    } else {
        echo '
        <div class="card">
            <div class="card-body">
              Oops. You have not created any recipe. <a href="'.esc_url(get_permalink(get_option('vmh_create_product_option'))).'">Create a recipe</a>
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
        'posts_per_page' => $postPerPage,
        'tax_query'      => [
            [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => 'pending-product',
                'operator' => 'NOT IN'
            ],
            [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => 'duplicate-product',
                'operator' => 'NOT IN'
            ]
        ]
    );

    $recipes = get_posts($args);

    if ($recipes) {
        foreach ($recipes as $key => $recipe) {
            $timeDiff = (time() - strtotime($recipe->post_date)) / (60 * 60 * 24);

            if ($timeDiff <= 10) {
                if (wc_get_product($recipe)->get_id() != get_option('vmh_create_product_option')) {

                    load_template(VMH_PATH.'Includes/Templates/product.php', false, [
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

    // $userID = get_current_user_id();
    // $favoriteMetaKey = '_user_favorite';
    // $favoriteProductsID = get_user_meta($userID, $favoriteMetaKey, true);

    // $uniqueCategories = [];
    // $products = [];

    // if ($favoriteProductsID) {
    //     foreach ($favoriteProductsID as $key => $productID) {
    //         $categories = get_the_terms($productID, 'product_cat');
    //         if ($categories) {
    //             foreach ($categories as $key => $cat) {
    //                 // insert unquie categories to array
    //                 if (!array_key_exists($cat->slug, $uniqueCategories)) {
    //                     $uniqueCategories[$cat->term_id] = $cat->name;
    //                 }
    //             }
    //         }
    //     }
    // }

    // if ($uniqueCategories) {
    //     foreach ($uniqueCategories as $key => $uniqueCategorie) {
    //         $productsByCategorie = getProductsByCategory([
    //             'taxonomy'    => 'product_cat',
    //             'termID'      => $key,
    //             'postPerPage' => $postPerPage
    //         ]);

    //         if ($productsByCategorie) {
    //             foreach ($productsByCategorie as $key => $value) {
    //                 array_push($products, $value);
    //             }
    //         }
    //     }
    // }

    // // if we have recommeded product show them
    // if ($products) {
    //     foreach ($products as $key => $product) {
    //         if (wc_get_product($product)->get_type() === 'simple' || wc_get_product($product)->get_type() === 'variable') {
    //             if (wc_get_product($product)->get_id() != get_option('vmh_create_product_option')) {
    //                 load_template(VMH_PATH . 'Includes/Templates/product.php', false, [
    //                     'postTitle'    => $product->post_title,
    //                     'postAuthorID' => $product->post_author,
    //                     'productID'    => $product->ID,
    //                     'productType'  => wc_get_product($product)->get_type()
    //                 ]);
    //             }
    //         }
    //     }
    // } else {
    //     echo '
    //     <div class="card">
    //         <div class="card-body">
    //           No recommendation found yet. Try to bookmark a recipe.
    //         </div>
    //     </div>';

    // }

    echo do_shortcode('[DCAS_shortcode style="1" rating="0" postsperpage="'.$postPerPage.'" columns="1"]');

}

/**
 * Get all the ingredients post type for showing into select box
 * @return mixed
 */
function getAllIngredients($args = []) {
    $postArgs = [
        'post_type'      => 'ingredient',
        'posts_per_page' => -1,
        'post_status'    => 'publish'
    ];

    $ingredients = get_posts($postArgs);

    if (!$ingredients) {
        return '';
    }

    $options = '<option data-placeholder="true"></option>';

    foreach ($ingredients as $key => $ingredient) {
        // $options .= '<option class="' . addDisableAttr($ingredient->ID) . '" ' . addDisableAttr($ingredient->ID) . ' value="' . esc_attr($ingredient->ID) . '" >
        //                 ' . esc_html($ingredient->post_title) . ' (' . ingredientInStock($ingredient->ID) . ')
        //             </option>';
        $options .= '<option value="'.esc_attr($ingredient->ID).'" >
                        '.esc_html($ingredient->post_title).'
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

// Get the prodcut ingredients for edit product feature if edit_product found parameter is found
/**
 * @return mixed
 */
function getIngredientsOnProductEdit() {
    if (!isset($_GET['edit_product']) || !$_GET['edit_product']) {
        return null;
    }

    $productIngredients = get_post_meta($_GET['edit_product'], 'product_ingredients', true);

    $ingredientsPercentage = get_post_meta($_GET['edit_product'], 'ingredients_percentage_values', true);

    if (!$productIngredients) {
        return '
        <div class="ingredients_wrapper" id="ingredients_wrapper_1">
            <select name="product_ingredients" style="width: 300px" class="product_ingredients"
                id="product_ingredients_1">
                '.getAllIngredients().'
            </select>

            <input type="number" min="0" max="30" name="ingredient_percentage" class="ingredient_percentage"
                placeholder="%">

            <i class="fa fa-plus-circle add_ingredients_icon" aria-hidden="true"></i>
        </div>
        ';
    }

    $ingredientsHTML = '';

    $ingredientOptions = getAllIngredients();

    foreach ($productIngredients as $key => $ingredient) {

        $ingredientsHTML .= '
                <div class="ingredients_wrapper create_ingredients_wrapper" id="create_ingredients_select'.$key.'">


                    <select data-seleted_val="'.esc_attr($ingredient).'" name="product_ingredients" style="width: 300px" class="product_ingredients"
                        <option data-placeholder="true"></option>

                        '.$ingredientOptions.'
                    </select>

                    <input type="number" min="0" max="30" name="ingredient_percentage" class="ingredient_percentage" value="'.showIngredientsPercentageValues($key, $ingredientsPercentage).'" >

                   '.showCreateIcon($key).'
                   '.showDeleteIcon($key).'

                </div>
        ';

    }

    return $ingredientsHTML;

}

// Show the plus create icon for only the first element
/**
 * @param $i
 */
function showCreateIcon($i) {
    if ($i == 0) {
        return '<i class="fa fa-plus-circle add_ingredients_icon" aria-hidden="true"></i>';
    } else {
        return null;
    }
}

/**
 * @param $i
 */
function showDeleteIcon($i) {
    if ($i != 0) {
        return '<i class="fas fa-times-circle cut_selectbox"></i>';
    } else {
        return null;
    }
}

/**
 * Show the percentage values if this product is created by user from frontend
 * @param  $key
 * @param  $ingredientsPercentage
 * @return mixed
 */
function showIngredientsPercentageValues($key, $ingredientsPercentage) {
    if (isset($ingredientsPercentage[$key]) && $ingredientsPercentage[$key]) {
        return esc_attr($ingredientsPercentage[$key]);
    } else {
        return null;
    }
}

// Determiner if the product should be move to update mode or create new mode
// If the the product owner clicks the range icon than it should be update mode
// Or if user click on the range icon of other user product it should be create new mode
function updateOrEditTag() {
    // $productOptions = get_post_meta(get_the_ID(), 'product_options', true);

    // $postAuthor = get_post(get_the_ID())->post_author;

    $updateProduct = false;

    // if ($postAuthor == get_current_user_id()) {
    //     $updateProduct = true;
    // }

    // if (is_array($productOptions)) {

    //     $productOptions = call_user_func_array('array_merge', $productOptions);

    //     $organizedOptions = [];

    //     foreach ($productOptions as $key => $value) {

    //         $option = explode("|", $value[0]);

    //         $organizedOptions[$key] = trim($option[0]);
    //     }

    //     return '&' . http_build_query($organizedOptions) . '&update_product=' . $updateProduct . '';
    // } else {
    // }
    return '&update_product='.$updateProduct.'';
}

/**
 * Get product tags HTML
 * @return mixed
 */
function getProductTags($productID = null) {

    $postID = get_the_ID();

    if ($productID) {
        $postID = $productID;
    }

    $tags = wp_get_post_terms($postID, 'product_tag', array("fields" => "all"));

    if (!$tags) {
        return '';
    }

    $tagsHTML = '';

    foreach ($tags as $key => $tag) {
        $tagsHTML .= '<span class="tag_name" data-target="tag_name_'.($key + 1).'">'.esc_html($tag->name).'</span>';
    }

    return $tagsHTML;
}

/**
 * @return null
 */
function editProductTagsHTML() {
    if (isset($_GET['edit_product']) && $_GET['edit_product']) {
        $productID = sanitize_text_field($_GET['edit_product']);

        if (isset($_GET['update_product']) && $_GET['update_product'] === '1') {

            $tags = wp_get_post_terms($productID, 'product_tag', array("fields" => "all"));

            if (!$tags) {
                echo '<div class="dynamic_tags"></div>';
                return;
            }

            $deafultTags = '<div class="deafult_tags">';
            $dynamicTags = '<div class="dynamic_tags">';

            foreach ($tags as $key => $tag) {

                if ($key < 2) {
                    $deafultTags .= '<span class="tag_name" data-target="tag_name_'.($key + 1).'">'.esc_html($tag->name).'</span>';
                } else {
                    $dynamicTags .= '<span class="tag_name" data-target="tag_name_'.($key + 1).'">'.esc_html($tag->name).'</span>';
                }
            }

            $deafultTags .= '</div>';
            $dynamicTags .= '</div>';

            echo $deafultTags.$dynamicTags;

        } else {
            echo '
            <div class="deafult_tags">
                <a href="#" class="tag_name" data-target="tag_name_1">Tag Name</a>
                <a href="#" class="tag_name" data-target="tag_name_2">Tag Name</a>
            </div>
            <div class="dynamic_tags">
            </div>
            ';
        }
    } else {
        echo '
        <div class="deafult_tags">
            <a href="#" class="tag_name" data-target="tag_name_1">Tag Name</a>
            <a href="#" class="tag_name" data-target="tag_name_2">Tag Name</a>
        </div>
        <div class="dynamic_tags">
        </div>
        ';
    }
}

// Display the tag input fields based on product edit conditions
function editProductTagsInput() {
    if (isset($_GET['edit_product']) && $_GET['edit_product']) {
        $productID = sanitize_text_field($_GET['edit_product']);

        if (isset($_GET['update_product']) && $_GET['update_product'] === '1') {

            $tags = wp_get_post_terms($productID, 'product_tag', array("fields" => "all"));

            if (!$tags) {
                return '';
            }

            $tagsInputHTML = '';

            foreach ($tags as $key => $tag) {

                if ($key < 2) {
                    $tagsInputHTML .= '
                    <div class="tag_input">
                        <input type="text" placeholder="Type Tag Name" data-id="'.($key + 1).'"
                        class="vmh_tag_input predefied_tag_input"
                        value="'.esc_html($tag->name).'">
                    </div>';
                } else {
                    $tagsInputHTML .= '
                    <div class="tag_input" style="display: flex;">
                        <i class="fas fa-times cut_tag"></i>
                        <input type="text" placeholder="Type Tag Name" data-id="'.($key + 1).'"
                        class="vmh_tag_input"
                        value="'.esc_html($tag->name).'">
                    </div>';
                }
            }

            echo $tagsInputHTML;

        } else {
            echo '
            <div class="tag_input">
                <input type="text" placeholder="Type Tag Name" data-id="1"
                    class="vmh_tag_input predefied_tag_input">
            </div>

            <div class="tag_input">
                <input type="text" placeholder="Type Tag Name" data-id="2"
                    class="vmh_tag_input predefied_tag_input">
            </div>
            ';
        }
    } else {
        echo '
        <div class="tag_input">
            <input type="text" placeholder="Type Tag Name" data-id="1"
                class="vmh_tag_input predefied_tag_input">
        </div>
        <div class="tag_input">
            <input type="text" placeholder="Type Tag Name" data-id="2"
                class="vmh_tag_input predefied_tag_input">
        </div>
        ';
    }
}

/**
 * @param  array   $cartItems
 * @return mixed
 */
function getCalculatedNicotineShots(array $cartItems) {

    if (!$cartItems || count($cartItems) < 1) {
        return [];
    }

    $shotCalculationData = [
        'freebase-nicotine' => [
            'name'            => 'Freebase Nicotine',
            'calculatedValue' => 0,
            'shotValue'       => 0,
            'typeCount'       => 0
        ],
        'nicotine-salt'     => [
            'name'            => 'Nicotine Salt',
            'calculatedValue' => 0,
            'shotValue'       => 0,
            'typeCount'       => 0
        ]
    ];

    foreach ($cartItems as $key => $item) {
        $nicotineType = isset($item["variation"]["attribute_pa_vmh_nicotine_type"]) ? $item["variation"]["attribute_pa_vmh_nicotine_type"] : null;
        $nicotineShotValue = isset($item["nicotine_shot_value"]) ? $item["nicotine_shot_value"] : null;
        $nicotineShotCalculatedValue = isset($item["nicotine_shot_calculated_value"]) ? $item["nicotine_shot_calculated_value"] : null;

        if ($nicotineType && $nicotineShotValue) {
            // Add up the nicotine shot value
            $shotCalculationData[$nicotineType]['shotValue'] += $nicotineShotValue * $item["quantity"];
            $shotCalculationData[$nicotineType]['calculatedValue'] += $nicotineShotCalculatedValue * $item["quantity"];
            $shotCalculationData[$nicotineType]['typeCount'] += 1 * $item["quantity"];
        }
    }

    // Rounding the number to nearest integer & than rounding the number to 10 times of its value
    $shotCalculationData['freebase-nicotine']['shotValue'] = ceil($shotCalculationData['freebase-nicotine']['shotValue'] / 10) * 10;
    $shotCalculationData['nicotine-salt']['shotValue'] = ceil($shotCalculationData['nicotine-salt']['shotValue'] / 10) * 10;

    // Rounding the calculated number to nearest integer & than rounding the number to 10 times of its value
    $shotCalculationData['freebase-nicotine']['calculatedValue'] = ceil($shotCalculationData['freebase-nicotine']['calculatedValue'] / 10) * 10;
    $shotCalculationData['nicotine-salt']['calculatedValue'] = ceil($shotCalculationData['nicotine-salt']['calculatedValue'] / 10) * 10;

    return $shotCalculationData;
}

/**
 * @param  $shotValue
 * @return mixed
 */
function getIndividualShotPrice($shotValue) {

    $shotPrice = 0;

    if (!$shotValue) {
        return number_format($shotPrice, 2);
    }

    $shotPrice = ($shotValue / 10) * NICOTINE_SHOT_PRICE;

    return number_format($shotPrice, 2);
}

/**
 * Get the formatted nicotine type based on value
 * @param $type
 */
function getFormattedNicotineType($type) {

    $formattedType = [
        'freebase-nicotine' => [
            'name' => 'Freebase Nicotine'
        ],
        'nicotine-salt'     => [
            'name' => 'Nicotine Salt'
        ]
    ];

    return isset($formattedType[$type]) ? $formattedType[$type]['name'] : 'No nicotine';
}

/**
 * Get user created post
 * @return null
 */
function getUserRecipes() {

    $userID = getUserIDFromUrl(sanitize_url($_SERVER['REQUEST_URI']));

    if (!$userID) {
        echo '
        <div class="card">
            <div class="card-body">
              User don\'t have any recipe yet.
            </div>
        </div>';
        return;
    }

    $userInfo = get_userdata($userID);

    $args = [
        'author'         => $userID,
        'posts_per_page' => -1,
        'post_type'      => 'product',
        'post__not_in'   => [get_option('vmh_create_product_option')],
        'tax_query'      => [
            [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => 'pending-product',
                'operator' => 'NOT IN'
            ],
            [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => 'duplicate-product',
                'operator' => 'NOT IN'
            ]
        ]
    ];

    if (is_user_logged_in() && $userID == get_current_user_id()) {
        unset($args['tax_query']);
    }

    $recipes = get_posts($args);

    if ($recipes) {
        foreach ($recipes as $key => $recipe) {
            if (wc_get_product($recipe)->get_id() != get_option('vmh_create_product_option')) {
                load_template(VMH_PATH.'Includes/Templates/my-recipe-product.php', false, [
                    'postTitle'     => $recipe->post_title,
                    'postAuthorID'  => $recipe->post_author,
                    'productID'     => $recipe->ID,
                    'productType'   => wc_get_product($recipe)->get_type(),
                    'createdRecipe' => true
                ]);
            }
        }
    } else {

        if (is_user_logged_in() && $userID == get_current_user_id()) {
            echo '
            <div class="card">
                <div class="card-body">
                  Oops. You have not created any recipe. <a href="'.esc_url(get_permalink(get_option('vmh_create_product_option'))).'">Create a recipe</a>
                </div>
            </div>';
            return;
        } else {
            echo '
            <div class="card">
                <div class="card-body">
                  '.$userInfo->display_name.' don\'t have any recipe yet.
                </div>
            </div>';
            return;
        }
    }
}

/**
 * @param  $url
 * @return mixed
 */
function getUserIDFromUrl($url) {
    $pattern = "/profile\/\d+/i";
    $isMatched = preg_match($pattern, $url, $matches);

    if ($isMatched) {
        $userID = (int) explode("/", $matches[0])[1];

        return $userID;
    }

    return false;
    print_r($matches);
}

/**
 * Get product on user serach
 * @param $postPerPage
 */
function getSearchResults($postPerPage) {

    $searchText = sanitize_text_field($_GET['s']);

    $args = [
        'posts_per_page' => $postPerPage,
        'post_type'      => 'product',
        'post__not_in'   => [get_option('vmh_create_product_option')],
        's'              => $searchText,
        'tax_query'      => [
            [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => 'pending-product',
                'operator' => 'NOT IN'
            ],
            [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => 'duplicate-product',
                'operator' => 'NOT IN'
            ]
        ]
    ];

    $recipes = get_posts($args);

    if (!$recipes) {

        echo "
        <div class='card'>
            <div class='card-body'>
              Sorry, could not find any results for your <b>".$searchText."</b> search.
            </div>
        </div>";
    }

    foreach ($recipes as $key => $recipe) {
        load_template(VMH_PATH.'Includes/Templates/product.php', false, [
            'postTitle'    => $recipe->post_title,
            'postAuthorID' => $recipe->post_author,
            'productID'    => $recipe->ID,
            'productType'  => wc_get_product($recipe)->get_type()
        ]);
    }

}