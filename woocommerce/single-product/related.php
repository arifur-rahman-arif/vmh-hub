<?php
/* *
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 * @version     3.9.0
 * @package     WooCommerce\Templates
 * @see         https://docs.woocommerce.com/document/template-structure/
 */

if (!defined('ABSPATH')) {
    exit;
}

if ($related_products) {?>

<?php $count = 0?>
<?php woocommerce_product_loop_start();?>

<?php foreach ($related_products as $related_product): ?>

<?php
$post_object = get_post($related_product->get_id());

    if ($count == 4) {
        break;
    }

    setup_postdata($GLOBALS['post'] = &$post_object); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
    ?>

<?php if ($related_product->get_id() != get_option('vmh_create_product_option')) {?>
<div class="single_recopies_items recepes_single_left_item"
    onclick="location.href='<?php echo esc_url(get_permalink($post_object->ID)) ?>'">

    <h6><?php echo vmhEscapeTranslate($post_object->post_title) ?></h6>

    <p>By <?php echo get_the_author_meta('display_name', $post_object->post_author); ?></p>

    <?php echo getProductIngrediants($post_object->ID) ?>

    <!-- <div class="single_recopies_items_overly">

        <div class="single_recopies_items_overly_item">
            HIding the add to cart button
            <?php if (wc_get_product($related_product)->get_type() !== 'variable') {?>
            <a href="#" class="vmh_add_to_cart_btn" data-id="<?php echo $post_object->ID ?>">Add To Cart</a>
            <?php }?>
            <a href="<?php echo esc_url(get_permalink($post_object->ID)) ?>">View Recepie</a>
        </div>

    </div> -->

</div>

<?php }?>

<?php
$count++
// wc_get_template_part('content', 'product');
    ?>

<?php endforeach;?>

<?php woocommerce_product_loop_end();?>

<?php
wp_reset_postdata();
} else {
    ?>

<style>
.recepes_title {
    display: none;
}

.empty_column {
    width: 200px;
}
</style>

<ul class="products empty_column columns-<?php echo esc_attr(wc_get_loop_prop('columns')); ?>">
</ul>

<?php
}