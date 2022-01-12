<div class="wrap">
    <form action="options.php" method="POST">
        <?php settings_fields('vmh_options_key')?>
        <?php do_settings_sections('vmh-product-options')?>
        <?php submit_button('Save Settings');?>
        <div class="vmh_options_btn">
            <button class="vmh_attribute_create button button-primary">Create Attributes</button>
        </div>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    $('.vmh_attribute_create').on('click', (e) => {
        e.preventDefault();

        $.ajax({
            type: "post",
            data: {
                action: 'vmh_create_product_attribute'
            },
            url: "<?php echo admin_url('admin-ajax.php') ?>",
            success: function(response) {

                let res = JSON.parse(response);

                if (res.response != 'success') {
                    return alert(res.message)
                }

                $.ajax({
                    type: "post",
                    data: {
                        action: 'vmh_create_product_taxonomy'
                    },
                    url: "<?php echo admin_url('admin-ajax.php') ?>",
                    success: function(response) {

                        console.log(response)

                        let res = JSON.parse(response);

                        if (res.response != 'success') {
                            return alert(res.message)
                        }

                        return alert(res.message)

                    }
                })

            }
        })

    })
});
</script>