<?php get_header('header.php')?>
<!-- Start how it works area -->
<section class="comon_section_area my_favorite_area">
    <div class="container">
        <div class="alert alert-dark vmh_alert" role="alert" style="display: none;">
            This is a dark alert—check it out!
        </div>
        <div class="row">
            <div class="col-md-12">
                <!-- start recopies area -->
                <div class="main_recopies_area">
                    <div class="recopies_heading">
                        <h4>My Favorites</h4>
                    </div>
                    <div class="recopies_content_area">
                        <?php getUserFavoriteProducts();?>
                    </div>
                </div>
                <!-- End recopies area -->
            </div>
        </div>
    </div>
</section>
<!-- End how it works area -->

<?php get_footer('header.php')?>