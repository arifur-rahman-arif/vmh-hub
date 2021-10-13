<div class="wrap">
    <form action="options.php" method="POST">
        <?php settings_fields('vmh_options_key')?>
        <?php do_settings_sections('vmh-product-options')?>
        <div class="vmh_options_btn">
            <?php submit_button('Save Settings');?>
        </div>
    </form>
</div>
<!--
<script>
jQuery(document).ready(function($) {
    $('.vmh_options_btn input').click((e) => {
        $.ajax({
            type: "post",
            data: {
                action: 'vmh_options_save'
            },
            url: "<?php echo admin_url('admin-ajax.php') ?>",
            success: function(response) {
                console.log(response)
            }
        });
    })
});
</script> -->