<?php

namespace VmhHub\Includes\Classes;

use VmhHub\Includes\Classes\FilterCallbacks;

class Filters extends FilterCallbacks {

    public function __construct() {
        add_filter('woocommerce_billing_fields', [$this, 'customizeBillingFields']);
        add_filter('woocommerce_checkout_fields', [$this, 'customizeCheckoutFields']);

        // change the deafult choose option text
        add_filter('woocommerce_dropdown_variation_attribute_options_args', [$this, 'changeVariableProductChooseOption'], 10, 1);

        // Pre select the product options if the product is created by a user
        // add_filter('woocommerce_dropdown_variation_attribute_options_args', [$this, 'preSelectVariations']);

        // Increase the combination validation threshold of varitions in a variable product
        add_filter('woocommerce_ajax_variation_threshold', [$this, 'increaseCombinationThreshold'], 10, 2);

        // Modify the Ultimate member plugin dropdown menu
        add_filter('um_myprofile_edit_menu_items', [$this, 'modifyUltimateMemberPluginDropdown'], 10, 2);

    }
}