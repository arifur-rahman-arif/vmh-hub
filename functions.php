<?php

if (!defined('ABSPATH')) {
    return;
}

if (!defined('VMH_VERSION')) {
    // define('VMH_VERSION', '1.12.0');
    define('VMH_VERSION', time());
}

if (!defined('VMH_PATH')) {
    define('VMH_PATH', trailingslashit(get_theme_file_path()));
}

if (!defined('VMH_URL')) {
    define('VMH_URL', trailingslashit(get_template_directory_uri()));
}

if (!class_exists('WooCommerce')) {
    return;
}

require_once VMH_PATH . 'vendor/autoload.php';
require_once VMH_PATH . 'constant.php';
require_once VMH_PATH . 'Includes/Functions/templateFunctions.php';
require_once VMH_PATH . 'Includes/Functions/generalFunctions.php';

new \VmhHub\Includes\Classes\Hooks();
new \VmhHub\Includes\Classes\Filters();
new \VmhHub\Includes\Classes\ShortCode();
