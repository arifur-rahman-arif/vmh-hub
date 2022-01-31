  <!-- Start single Item -->
  <?php
$nicotineType = isset($args['variation']['attribute_pa_vmh_nicotine_type']) ? $args['variation']['attribute_pa_vmh_nicotine_type'] : null;
?>

  <div class="single_recopies_items cart_single_item cart_items1">
      <div class="cart_single_item_flex">
          <div class="cart_single_item_flex_left">
              <h6><?php echo get_the_title($args['product_id']) ?></h6>
              <p>By <?php echo get_the_author_meta('display_name', get_post($args['product_id'])->post_author) ?></p>
          </div>
          <div class="cart_single_item_flex_right cart_remove1" data-id="<?php echo $args['product_id'] ?>">
              <a href="#"><img src="<?php echo VMH_URL . 'Assets/images/cart/remove_icon.png' ?>" alt="images" /></a>
          </div>
      </div>
      <div class="cart_single_item_content">
          <?php echo getProductIngrediants($args['product_id'], true) ?>
          <br>

          <?php if (isset($args['nicotine_shot_value'])) {?>

          <div class="nicotineshot_container">
              <p>Nicotine Shot: <?php echo esc_attr($args['nicotine_shot_value']) ?>ml</p>
              <div class="input_container">
                  <input type="hidden" class="cart_nicotine_shot_hidden_value" min="0"
                      max="<?php echo esc_attr($args['nicotine_shot_calculated_value']) ?>"
                      value="<?php echo esc_attr($args['nicotine_shot_calculated_value']) ?>">
                  <input type="number" class="cart_nicotine_shot_value" min="0"
                      max="<?php echo esc_attr($args['nicotine_shot_calculated_value']) ?>" step="10"
                      value="<?php echo esc_attr($args['nicotine_shot_value']) ?>">
                  <i class="far fa-save nicotineshot_save_btn" data-key="<?php echo esc_attr($args['cart_key']) ?>"></i>
              </div>
          </div>
          <?php }?>
          <p>Quantity : x<?php echo $args['quantity'] ?></p>
          <p style="margin-top: 15px;">Ingredients Price: <?php echo get_woocommerce_currency_symbol() ?>
              <?php echo $args['line_total'] ?></p>

          <?php if ($nicotineType) {?>
          <p style="margin-top: 15px;">Nicotine Type: <?php echo esc_html(getFormattedNicotineType($nicotineType)) ?>
          </p>
          <?php }?>
      </div>
  </div>
  <!-- End Single Item -->