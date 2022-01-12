<?php

namespace VmhHub\Includes\Classes;

class FilterCallbacks {
    /**
     * Customize the woocommerce billing fields according to custom vmhhub theme
     * @param $billingFields
     */
    public function customizeBillingFields($billingFields) {
        $billingFields['billing_first_name']['class'][] = 'login_input_left shipping_address_left';
        $billingFields['billing_last_name']['class'][] = 'login_input_left shipping_address_left';
        $billingFields['billing_company']['class'][] = 'login_input_left';
        $billingFields['billing_country']['class'][] = 'login_input_left';
        $billingFields['billing_address_1']['class'][] = 'login_input_left';
        $billingFields['billing_address_2']['class'][] = 'login_input_left';
        $billingFields['billing_city']['class'][] = 'login_input_left';
        $billingFields['billing_state']['class'][] = 'login_input_left';
        $billingFields['billing_postcode']['class'][] = 'login_input_left';
        $billingFields['billing_phone']['class'][] = 'login_input_left';
        $billingFields['billing_email']['class'][] = 'login_input_left';
        return $billingFields;
    }

    /**
     * Customize the woocommerce billing fields according to custom vmhhub theme
     * @param $billingFields
     */
    public function customizeCheckoutFields($checkoutFields) {
        $checkoutFields['shipping']['shipping_first_name']['class'][] = 'login_input_left shipping_address_left';
        $checkoutFields['shipping']['shipping_last_name']['class'][] = 'login_input_left shipping_address_left';
        $checkoutFields['shipping']['shipping_company']['class'][] = 'login_input_left';
        $checkoutFields['shipping']['shipping_country']['class'][] = 'login_input_left';
        $checkoutFields['shipping']['shipping_address_1']['class'][] = 'login_input_left';
        $checkoutFields['shipping']['shipping_address_2']['class'][] = 'login_input_left';
        $checkoutFields['shipping']['shipping_city']['class'][] = 'login_input_left';
        $checkoutFields['shipping']['shipping_state']['class'][] = 'login_input_left';
        $checkoutFields['shipping']['shipping_postcode']['class'][] = 'login_input_left';
        return $checkoutFields;
    }

    /**
     * Chagne default text of woocommerce variable product choose options default text
     * @return mixed
     */
    public function changeVariableProductChooseOption($args) {

        // Find the name of the attribute for the slug we passed in to the function
        $attribute_name = wc_attribute_label($args['attribute']);

        // Create a string for our select
        $args['show_option_none'] = __('Choose', 'woocommerce');
        return $args;
    }

    /**
     * Pre select the variation on a varibale product
     * @param  $args
     * @return mixed
     */
    public function preSelectVariations($args) {

        $productID = null;

        if (isset($_GET['edit_product'])) {

            $productID = sanitize_text_field($_GET['edit_product']);
            return $this->addPreSelectValues($args, $productID);

        } else {

            if (get_the_ID() != get_option('vmh_create_product_option')) {
                $productID = get_the_ID();
                return $this->addPreSelectValues($args, $productID);
            }

        }

        return $args;
    }

    /**
     * Change the select option value based on saved product_options meta value
     * @param $args
     */
    public function addPreSelectValues($args, $productID) {
        $productOptions = get_post_meta($productID, 'product_options', true);

        if (!$productOptions) {
            return $args;
        }

        $productOptions = array_merge(...$productOptions);

        if (count($args['options']) > 0 && is_array($productOptions)) {
            $attribute = $args['attribute'];

            foreach ($productOptions as $key => $value) {

                $attributeKey = 'pa_' . trim(explode("|", $value[0])[0]);

                // Check if the saved options attribute key is == variable attibute name
                if ($attribute == $attributeKey) {
                    $indexKey = array_search($key, $args['options']);
                    $args['selected'] = $args['options'][$indexKey];
                }
            }

        }

        return $args;
    }

}