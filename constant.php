<?php

if (!defined('NICOTINE_SHOT_PRICE')) {
    define('NICOTINE_SHOT_PRICE', get_option('vmh_10ml_nicotineshot_price') ? floatval(get_option('vmh_10ml_nicotineshot_price')) : 0.3);
}

if (!defined('BOTTLE_SIZE_PRICE_10ml')) {
    define('BOTTLE_SIZE_PRICE_10ml', get_option('vmh_10ml_bottle_size_price') ? floatval(get_option('vmh_10ml_bottle_size_price')) : 1.3);
}

if (!defined('BOTTLE_SIZE_PRICE_50ml')) {
    define('BOTTLE_SIZE_PRICE_50ml', get_option('vmh_50ml_bottle_size_price') ? floatval(get_option('vmh_50ml_bottle_size_price')) : 6);
}