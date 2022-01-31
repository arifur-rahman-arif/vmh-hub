<?php settings_errors();?>

<?php

$nicotineAmount = get_option('vmh_nicotine_amount') ? sanitize_text_field(get_option('vmh_nicotine_amount')) : null;
$nicotineType = get_option('vmh_nicotine_type') ? sanitize_text_field(get_option('vmh_nicotine_type')) : null;
$pgVg = get_option('vmh_pg_vg') ? sanitize_text_field(get_option('vmh_pg_vg')) : null;
$bottleSize = get_option('vmh_bottle_size') ? sanitize_text_field(get_option('vmh_bottle_size')) : null;
$createProductOption = get_option('vmh_create_product_option') ? sanitize_text_field(get_option('vmh_create_product_option')) : null;
$productCommission = get_option('vmh_product_commission') ? sanitize_text_field(get_option('vmh_product_commission')) : null;
$mainAdmin = get_option('vmh_main_admin') ? sanitize_text_field(get_option('vmh_main_admin')) : null;
$hideNicotineValue = get_option('vmh_hide_nicotine') ? sanitize_text_field(get_option('vmh_hide_nicotine')) : null;
$priceOf10mlshot = get_option('vmh_10ml_nicotineshot_price') ? sanitize_text_field(get_option('vmh_10ml_nicotineshot_price')) : null;
$constantPriceOF10mlBottle = get_option('vmh_10ml_bottle_size_price') ? sanitize_text_field(get_option('vmh_10ml_bottle_size_price')) : null;
$constantPriceOF50mlBottle = get_option('vmh_50ml_bottle_size_price') ? sanitize_text_field(get_option('vmh_50ml_bottle_size_price')) : null;

?>

<tr>
    <td>
        <strong style="font-size: 15px;">
            <label for="vmh_nicotine_amount">Nicotine Amount :</label>
        </strong>
    </td>
    <td>
        <input style='width: 500px;' type="text" name="vmh_nicotine_amount"
            placeholder="Separate values with | Like value1,value2,..."
            value="<?php echo esc_attr($nicotineAmount) ?>" />
    </td>
</tr>
<tr>
    <td>
        <strong style="font-size: 15px;">
            <label for="vmh_nicotine_type">Nicotine Type :</label>
        </strong>
    </td>
    <td>
        <input style='width: 500px;' type="text" name="vmh_nicotine_type"
            placeholder="Separate values with | Like value1,value2,..." value="<?php echo esc_attr($nicotineType) ?>" />
    </td>
</tr>
<tr>
    <td>
        <strong style="font-size: 15px;">
            <label for="vmh_pg_vg">PG:VG :</label>
        </strong>
    </td>
    <td>
        <input style='width: 500px;' type="text" name="vmh_pg_vg"
            placeholder="Separate values with | Like value1,value2,..." value="<?php echo esc_attr($pgVg) ?>" />
    </td>
</tr>
<tr>
    <td>
        <strong style="font-size: 15px;">
            <label for="vmh_bottle_size">Bottle size :</label>
        </strong>
    </td>
    <td>
        <input style='width: 500px;' type="text" name="vmh_bottle_size"
            placeholder="Separate values with | Like value1,value2,..." value="<?php echo esc_attr($bottleSize) ?>" />
    </td>
</tr>


<tr>
    <td>
        <strong style="font-size: 15px;">
            <label for="vmh_create_product_option">Product Option ID :</label>
        </strong>
    </td>
    <td>
        <input style='width: 200px;' type="number" name="vmh_create_product_option" placeholder="Reference Product ID"
            value="<?php echo esc_attr($createProductOption) ?>" />
    </td>
</tr>

<tr>
    <td>
        <strong style="font-size: 15px;">
            <label for="vmh_product_commission">Product Commission (%) :</label>
        </strong>
    </td>
    <td>
        <input style='width: 200px;' type="number" name="vmh_product_commission" placeholder="Product commission"
            value="<?php echo esc_attr($productCommission) ?>" />
    </td>
</tr>

<tr>
    <td>
        <strong style="font-size: 15px;">
            <label for="vmh_hide_nicotine">Hide nicotine over 16.7% :</label>
        </strong>
    </td>
    <td>
        <input style='width: 200px;' type="text" name="vmh_hide_nicotine" placeholder="Nicotine value to hide"
            value="<?php echo esc_attr($hideNicotineValue) ?>" />
    </td>
</tr>

<tr>
    <td>
        <strong style="font-size: 15px;">
            <label for="vmh_10ml_nicotineshot_price">10ml Nicotine shot price</label>
        </strong>
    </td>
    <td>
        <input style='width: 300px;' type="text" name="vmh_10ml_nicotineshot_price"
            placeholder="Enter 10ml nicotine shot price" value="<?php echo esc_attr($priceOf10mlshot) ?>" />
    </td>
</tr>

<tr>
    <td>
        <strong style="font-size: 15px;">
            <label for="vmh_10ml_bottle_size_price">10ml Bottle size price</label>
        </strong>
    </td>
    <td>
        <input style='width: 300px;' type="text" name="vmh_10ml_bottle_size_price" placeholder="10ml bottle size price"
            value="<?php echo esc_attr($constantPriceOF10mlBottle) ?>" />
    </td>
</tr>

<tr>
    <td>
        <strong style="font-size: 15px;">
            <label for="vmh_50ml_bottle_size_price">50ml Bottle size price</label>
        </strong>
    </td>
    <td>
        <input style='width: 300px;' type="text" name="vmh_50ml_bottle_size_price" placeholder="50ml bottle size price"
            value="<?php echo esc_attr($constantPriceOF50mlBottle) ?>" />
    </td>
</tr>

<tr>
    <td>
        <strong style="font-size: 15px;">
            <label for="vmh_main_admin">Main Admin :</label>
        </strong>
    </td>
    <td>
        <select style="width: 200px;" name="vmh_main_admin" id="vmh_main_admin">
            <?php echo getAdministratorsOptionHTML($mainAdmin) ?>
        </select>
    </td>
</tr>