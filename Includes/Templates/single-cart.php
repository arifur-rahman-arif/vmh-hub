  <!-- Start single Item -->
  <div class="single_recopies_items cart_single_item cart_items1">
      <div class="cart_single_item_flex">
          <div class="cart_single_item_flex_left">
              <h6><?php echo get_the_title($args['product_id']) ?></h6>
              <p>By <?php echo get_the_author_meta('display_name', get_post($args['product_id'])->post_author) ?></p>
          </div>
          <div class="cart_single_item_flex_right cart_remove1" data-id="<?php echo $args['product_id'] ?>">
              <a href="#"><img src="<?php echo VMH_URL.'Assets/images/cart/remove_icon.png' ?>" alt="images" /></a>
          </div>
      </div>
      <div class="cart_single_item_content">
          <?php echo getProductIngrediants($args['product_id'], true) ?>
          <p>quantity : x<?php echo $args['quantity'] ?></p>
          <p>Price: <?php echo get_woocommerce_currency_symbol() ?><?php echo $args['line_total'] ?></p>
      </div>
  </div>
  <!-- End Single Item -->