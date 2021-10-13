<?php
/**
 * Cross-sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cross-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @version 4.4.0
 * @package WooCommerce\Templates
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 */

defined('ABSPATH') || exit;

if ($cross_sells): ?>

<div class="cross-sells">
    <?php
$heading = apply_filters('woocommerce_product_cross_sells_products_heading', __('You may be interested in&hellip;', 'woocommerce'));

if ($heading):
?>
    <h2><?php echo esc_html($heading); ?></h2>
    <?php endif;?>

    <?php woocommerce_product_loop_start();?>

    <?php foreach ($cross_sells as $cross_sell): ?>

    <?php
$post_object = get_post($cross_sell->get_id());

setup_postdata($GLOBALS['post'] = &$post_object); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
?>

    <div class="single_recopies_items recepes_single_left_item">
        <h6><?php echo vmhEscapeTranslate($post_object->post_title) ?></h6>
        <p>By <?php echo get_the_author_meta('display_name', $post_object->post_author); ?></p>
        <?php echo getProductIngrediants($post_object->ID) ?>
    </div>


    <?php endforeach;?>

    <?php woocommerce_product_loop_end();?>

</div>
<?php
endif;

wp_reset_postdata();