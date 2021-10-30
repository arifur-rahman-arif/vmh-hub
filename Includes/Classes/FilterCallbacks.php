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
    public function changeVariableProductChooseOption($array) {

        // Find the name of the attribute for the slug we passed in to the function
        $attribute_name = wc_attribute_label($array['attribute']);

        // Create a string for our select
        $array['show_option_none'] = __('Choose', 'woocommerce');
        return $array;
    }

}