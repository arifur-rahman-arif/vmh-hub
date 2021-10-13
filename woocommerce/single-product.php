<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @version     1.6.4
 * @package     WooCommerce\Templates
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (get_the_ID() == get_option('vmh_create_product_option')) {
    if (!is_user_logged_in()) {
        ob_clean();
        ob_start();
        wp_redirect(site_url('/'));
    }
}

get_header('shop');?>

<?php
/*
 * woocommerce_before_main_content hook.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 */
do_action('woocommerce_before_main_content');
?>

<?php while (have_posts()): ?>
<?php the_post();?>

<?php wc_get_template_part('content', 'single-product');?>

<?php endwhile; // end of the loop. ?>

<?php
/*
 * woocommerce_after_main_content hook.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action('woocommerce_after_main_content');
?>


<?php
get_footer('shop');

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */