<div class="wrap">
    <form action="options.php" method="POST">
        <?php settings_fields('vmh_options_key')?>
        <?php do_settings_sections('vmh-product-options')?>
        <div class="vmh_options_btn">
            <?php submit_button('Save Settings', 'primary', 'vmh_attribute_create');?>
        </div>
    </form>
</div>

<!-- <script>
jQuery(document).ready(function($) {
    $('#vmh_attribute_create').parents('form').on('submit', (e) => {
        setTimeout(() => {

            $.ajax({
                type: "post",
                data: {
                    action: 'vmh_create_product_attribute'
                },
                url: "<?php echo admin_url('admin-ajax.php') ?>",
                success: function(response) {
                    console.log(response)
                }
            });
        }, 500)

    })
});
</script> -->