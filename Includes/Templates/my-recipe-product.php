<!-- single recopies area -->
<?php
$isSubscriberRecipe = isset($args['createdRecipe']) && $args['createdRecipe'] && in_array('subscriber', wp_get_current_user()->roles);
?>

<div class="single_recopies_items" style="cursor: auto;">
    <h6><?php echo vmhEscapeTranslate($args['postTitle']) ?></h6>
    <p>by <?php echo get_the_author_meta('display_name', $args['postAuthorID']); ?></p>

    <?php echo getProductIngrediants($args['productID']) ?>


    <?php if ($isSubscriberRecipe) {?>

    <a href="#" class="my_orh2 vmh_delete_recipe vmh_recipie_close_btn" data-id="<?php echo $args['productID'] ?>">
        <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M11.756 1.25236C11.6001 1.09614 11.3885 1.00834 11.1678 1.00834C10.9471 1.00834 10.7355 1.09614 10.5796 1.25236L6.5 5.32366L2.42036 1.24402C2.26449 1.08779 2.05288 1 1.8322 1C1.61151 1 1.3999 1.08779 1.24403 1.24402C0.918658 1.56939 0.918658 2.09498 1.24403 2.42035L5.32366 6.49999L1.24403 10.5796C0.918658 10.905 0.918658 11.4306 1.24403 11.756C1.5694 12.0813 2.09499 12.0813 2.42036 11.756L6.5 7.67633L10.5796 11.756C10.905 12.0813 11.4306 12.0813 11.756 11.756C12.0813 11.4306 12.0813 10.905 11.756 10.5796L7.67634 6.49999L11.756 2.42035C12.073 2.10333 12.073 1.56939 11.756 1.25236Z"
                fill="white" stroke="white" />
        </svg>
    </a>

    <?php }?>

    <!-- <div class="single_recopies_items_overly">


        <div class="single_recopies_items_overly_item">
            <?php if ($args['productType'] !== 'variable') {?>
                <a href="#" class="vmh_add_to_cart_btn" data-id="<?php echo $args['productID'] ?>">Add To Cart</a>
                <?php }?>
            <a href="<?php echo esc_url(get_permalink($args['productID'])) ?>">View Recepie</a>
        </div>
    </div> -->
</div>
<!-- single recopies area -->