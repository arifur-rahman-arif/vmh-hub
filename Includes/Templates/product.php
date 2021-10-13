<!-- single recopies area -->
<div class="single_recopies_items">
    <h6><?php echo vmhEscapeTranslate($args['postTitle']) ?></h6>
    <p>by <?php echo get_the_author_meta('display_name', $args['postAuthorID']); ?></p>

    <?php echo getProductIngrediants($args['productID']) ?>

    <div class="single_recopies_items_overly">
        <div class="single_recopies_items_overly_item">
            <?php if ($args['productType'] !== 'variable') {?>
            <a href="#" class="vmh_add_to_cart_btn" data-id="<?php echo $args['productID'] ?>">Add To Cart</a>
            <?php }?>
            <a href="<?php echo esc_url(get_permalink($args['productID'])) ?>">View Recepie</a>
        </div>
    </div>
</div>
<!-- single recopies area -->