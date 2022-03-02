<?php
/**
 * Single Product title
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/title.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @version    1.6.4
 * @see        https://docs.woocommerce.com/document/template-structure/
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$recipeName = '';

if (isset($_GET['edit_product']) && $_GET['edit_product']) {
    if (isset($_GET['update_product']) && $_GET['update_product'] === '1') {
        $recipeName = get_the_title(sanitize_text_field($_GET['edit_product']));
    } else {
        // $recipeName = get_the_title(sanitize_text_field($_GET['edit_product'])) . ' modified';
        $recipeName = null;
    }
}

?>
<?php if (get_the_ID() != get_option('vmh_create_product_option')) {?>

<div class="recepes_right_title_icon">
    <div class="recepes_right_title">
        <h3><?php echo get_the_title(get_the_ID()) ?></h3>
    </div>
    <div class="recepes_right_icon">
        <?php if (is_user_logged_in()) {?>
        <a href="#" data-action="<?php echo isProuductUserFavorite(get_the_ID()) ? 'unfavorite' : 'favorite' ?>"
            class="vmh_favorite" data-id="<?php echo get_the_ID() ?>"><i
                class="fas fa-heart vmh_heart <?php echo isProuductUserFavorite(get_the_ID()) ? null : 'vmh_heart_grey' ?>"></i></a>
        <a style="width: 20px;" class="edit_product_icon"
            href="<?php echo esc_url(get_permalink(get_option('vmh_create_product_option'))) ?>?edit_product=<?php echo get_the_ID() ?><?php echo updateOrEditTag() ?>">
            <img src="<?php echo esc_url(VMH_URL . 'Assets/images/recepes/setting.png') ?>" alt="images" />
        </a>
        <?php }?>
    </div>
</div>

<?php } else {?>

<div class="recepes_right_title">
    <h3><input type="text" name="vmh_recipe_name" id="vmh_recipe_name" required placeholder="Your recipe name"
            value="<?php echo $recipeName ?>">
    </h3>
</div>

<?php }?>

<?php if (get_the_ID() != get_option('vmh_create_product_option')) {?>
<div class="recepes_right_left_content">
    <p>By

        <a href="<?php echo get_author_posts_url(get_post(get_the_ID())->post_author) ?>"
            style="font-size: 15px; font-weight: bold">
            <?php echo get_the_author_meta('user_login', get_post(get_the_ID())->post_author); ?>
        </a>

    </p>
</div>
<?php }?>