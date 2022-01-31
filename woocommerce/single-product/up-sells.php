<?php
/**
 * Single Product Up-Sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/up-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @version     3.0.0
 *
 * @package     WooCommerce\Templates
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 */

if (!defined('ABSPATH')) {
    exit;
}

if ($upsells): ?>

<?php woocommerce_product_loop_start();?>

<?php foreach ($upsells as $upsell): ?>

<?php
$post_object = get_post($upsell->get_id());

setup_postdata($GLOBALS['post'] = &$post_object); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
?>

<?php if (isset($related_product)) {?>
<div class="single_recopies_items recepes_single_left_item" onclick="location.href='<?php echo esc_url(get_permalink($post_object->ID)) ?>'">
    <h6><?php echo vmhEscapeTranslate($post_object->post_title) ?></h6>
    <p>By <?php echo get_the_author_meta('display_name', $post_object->post_author); ?></p>
    <?php echo getProductIngrediants($post_object->ID) ?>
    <!-- <div class="single_recopies_items_overly">
        <div class="single_recopies_items_overly_item">
            <?php if (wc_get_product($related_product)->get_type() !== 'variable') {?>
            <a href="#" class="vmh_add_to_cart_btn" data-id="<?php echo $post_object->ID ?>">Add To Cart</a>
            <?php }?>
            <a href="<?php echo esc_url(get_permalink($post_object->ID)) ?>">View Recepie</a>
        </div>
    </div> -->
</div>

<?php }?>

<?php endforeach;?>

<?php woocommerce_product_loop_end();?>

<?php
endif;

wp_reset_postdata();