<?php get_header('header.php')?>

<!-- Start Banner main area -->

<?php
$productCategories = getProductCategories();
$bannerHeaderText = get_post_meta(get_page_by_title('Shop')->ID, 'banner_header', true) ? get_post_meta(get_page_by_title('Shop')->ID, 'banner_header', true) : 'WE MIX
YOUR TASTE';

$bannerDescription = get_post_meta(get_page_by_title('Shop')->ID, 'banner_description', true);

?>

<!-- Banner section -->
<section class="banner_main_area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="banner_content_area">
                    <h3><?php echo $bannerHeaderText ?></h3>
                    <p>
                        <?php if ($bannerDescription) {?>
                        <?php echo $bannerDescription ?>
                        <?php } else {?>
                        All our e-liquid recipes are created by users like you. You too can create your own recipe and
                        get paid doing so. Or sit back and relax, and let our recommendation engine find your favourite
                        recipes for you.
                        <?php }?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Banner main area -->

<!-- Start how it works area -->
<section class="comon_section_area vmh_shop_page">

    <div class="container">

        <div class="alert alert-dark vmh_alert" role="alert" style="display: none;">
            This is a dark alert—check it out!
        </div>

        <div class="row">


            <!-- If there is a search parameter in url than show the search result page -->
            <?php if (isset($_GET['s']) && isset($_GET['post_type'])) {?>

            <!-- Recommendation section -->
            <div class="col-md-12">
                <!-- start recopies area -->
                <div class="main_recopies_area">
                    <div class="recopies_heading">
                        <h3>Search Results:</h3>
                    </div>
                    <div class="recopies_content_area">
                        <?php getSearchResults(15)?>
                    </div>
                </div>
                <!-- End recopies area -->
            </div>
            <!-- End of recommendation section -->


            <?php } else {?>


            <!-- Recommendation section -->
            <div class="col-md-12">
                <!-- start recopies area -->
                <div class="main_recopies_area">
                    <div class="recopies_heading">
                        <h4>Recommended for you</h4>
                    </div>
                    <div class="recopies_content_area">
                        <?php getRecommendedProducts(20)?>
                    </div>
                </div>
                <!-- End recopies area -->
            </div>
            <!-- End of recommendation section -->



            <?php if ($productCategories) {?>

            <?php foreach ($productCategories as $key => $category) {?>

            <?php if ($category->slug == 'pending-product' || $category->slug == 'duplicate-product') {?>
            <?php continue;?>
            <?php }?>

            <!-- Individual category products  -->
            <div class="col-md-12 category_container">
                <!-- start recopies area -->
                <div class="main_recopies_area">
                    <div class="recopies_heading">
                        <h4><?php echo vmhEscapeTranslate($category->name) ?></h4>
                    </div>
                    <div class="recopies_content_area">
                        <?php load_template(VMH_PATH . 'Includes/Templates/archive-products.php', false, $category)?>
                    </div>
                </div>
                <!-- End recopies area -->
            </div>
            <!-- End of individual category products  -->

            <?php }?>
            <?php }?>


            <!-- New recipe section -->
            <div class="col-md-12">
                <!-- start recopies area -->
                <div class="main_recopies_area">
                    <div class="recopies_heading">
                        <h4>New Recipes</h4>
                    </div>
                    <div class="recopies_content_area">
                        <?php getRecentProducts(20)?>
                    </div>

                </div>
            </div>
            <!-- End of new recipe section -->

            <?php }?>
        </div>
    </div>
</section>
<!-- End how it works area -->


<?php get_footer('footer.php')?>