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
 * @package    WooCommerce\Templates
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

?>
<?php if (get_the_ID() != get_option('vmh_create_product_option')) {?>

<div class="recepes_right_title_icon">
    <div class="recepes_right_title">
        <h2><?php echo get_the_title(get_the_ID()) ?></h2>
    </div>
    <div class="recepes_right_icon">
        <?php if (is_user_logged_in()) {?>
        <a href="#" data-action="<?php echo isProuductUserFavorite(get_the_ID()) ? 'unfavorite' : 'favorite' ?>"
            class="vmh_favorite" data-id="<?php echo get_the_ID() ?>"><i
                class="fas fa-heart vmh_heart <?php echo isProuductUserFavorite(get_the_ID()) ? null : 'vmh_heart_grey' ?>"></i></a>
        <a href="<?php echo esc_url(get_permalink(get_option('vmh_create_product_option'))) ?>"><img
                src="<?php echo esc_url(VMH_URL . 'Assets/images/recepes/setting.png') ?>" alt="images" /></a>
        <?php }?>
    </div>
</div>

<?php } else {?>

<div class="recepes_right_title">
    <h2><input type="text" name="vmh_recipe_name" id="vmh_recipe_name" required placeholder="Your recipe name"></h2>
</div>

<?php }?>

<?php if (get_the_ID() != get_option('vmh_create_product_option')) {?>
<div class="recepes_right_left_content">
    <p>By
        <?php echo get_the_author_meta('display_name', get_post(get_the_ID())->post_author); ?>
    </p>
</div>
<?php }?>