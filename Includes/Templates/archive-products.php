<?php
$products = getProductsByCategory([
    'taxonomy'    => $args->taxonomy,
    'termID'      => $args->term_id,
    'postPerPage' => -1
]);
?>

<?php if ($products) {?>

<?php foreach ($products as $key => $product) {?>
<?php if (wc_get_product($product)->get_type() === 'simple' || wc_get_product($product)->get_type() === 'variable') {?>

<?php if (wc_get_product($product)->get_id() != get_option('vmh_create_product_option')) {?>
<?php load_template(VMH_PATH . 'Includes/Templates/product.php', false, [
    'postTitle'    => $product->post_title,
    'postAuthorID' => $product->post_author,
    'productID'    => $product->ID,
    'productType'  => wc_get_product($product)->get_type()
])?>

<?php }?>

<?php }?>
<?php }?>
<?php } else {?>
<div class="card">
    <div class="card-body">
        No products are available the this category.
    </div>
</div>
<?php }?>