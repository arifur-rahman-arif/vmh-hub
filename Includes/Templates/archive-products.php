<?php
$products = getProductsByCategory([
    'taxonomy'     => $args->taxonomy,
    'termID'       => $args->term_id,
    'postPerPage'  => -1,
    'post__not_in' => [get_option('vmh_create_product_option')],
    'tax_query'    => [
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
]);
?>

<?php if (!$products) {?>

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

<input type="hidden" class="vmh_hidden_no_product_cat" />
<!-- <div class="card">
    <div class="card-body">
        No products are available the this category.
    </div>
</div> -->
<?php }?>