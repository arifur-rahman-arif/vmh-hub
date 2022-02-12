<?php get_header('header.php')?>
<!-- Start Banner main area -->
<section class="banner_main_area expolore_banner">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="banner_content_area">
                    <h2>we mix</h2>
                    <h1>your tast</h1>
                    <p>
                        All our e-liquid recipes are created by users like you. You too can create your own recipe and
                        get paid doing so. Or sit back and relax, and let our recommendation engine find your favourite
                        recipes for you.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Banner main area -->

<!-- Start how it works area -->
<section class="comon_section_area explore_comon_section">
    <div class="container">
        <div class="alert alert-dark vmh_alert" role="alert" style="display: none;">
            This is a dark alert—check it out!
        </div>
        <div class="row">
            <?php if (is_user_logged_in()) {?>
            <div class="col-md-12">
                <!-- start recopies area -->
                <div class="main_recopies_area">
                    <div class="recopies_heading">
                        <h4>Recommended for you</h4>
                    </div>
                    <div class="recopies_content_area">
                        <?php getRecommendedProducts(20)?>
                    </div>
                    <!-- <div class="pagiation_area">
                        <ul class="paginations">
                            <li>
                                <a class="prev" href="#" aria-label="Previous">
                                    <span aria-hidden="true">
                                        <svg width="11" height="14" viewBox="0 0 11 14" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M9.61034e-05 6.97721L10.5198 0.949266L10.4803 13.0736L9.61034e-05 6.97721Z"
                                                fill="#324B4B" />
                                        </svg>
                                    </span>
                                </a>
                            </li>
                            <li><a href="#">1,</a></li>
                            <li><a href="#">2,</a></li>
                            <li><a href="#">3,</a></li>
                            <li><a href="#">4</a></li>
                            <li>
                                <a class="next" href="#" aria-label="Next">
                                    <span aria-hidden="true">
                                        <svg width="12" height="14" viewBox="0 0 12 14" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M11.0218 6.88219L0.625379 13.1203L0.421212 0.997682L11.0218 6.88219Z"
                                                fill="#324B4B" />
                                        </svg>
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div> -->
                </div>
                <!-- End recopies area -->
            </div>

            <?php }?>
            <div class="col-md-12">
                <!-- start recopies area -->
                <div class="main_recopies_area">
                    <div class="recopies_heading">
                        <h4>New Recipes</h4>
                    </div>
                    <div class="recopies_content_area">
                        <?php getRecentProducts(20)?>
                    </div>
                    <!-- <div class="pagiation_area">
                        <ul class="paginations">
                            <li>
                                <a class="prev" href="#" aria-label="Previous">
                                    <span aria-hidden="true">
                                        <svg width="11" height="14" viewBox="0 0 11 14" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M9.61034e-05 6.97721L10.5198 0.949266L10.4803 13.0736L9.61034e-05 6.97721Z"
                                                fill="#324B4B" />
                                        </svg>
                                    </span>
                                </a>
                            </li>
                            <li><a href="#">1,</a></li>
                            <li><a href="#">2,</a></li>
                            <li><a href="#">3,</a></li>
                            <li><a href="#">4</a></li>
                            <li>
                                <a class="next" href="#" aria-label="Next">
                                    <span aria-hidden="true">
                                        <svg width="12" height="14" viewBox="0 0 12 14" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M11.0218 6.88219L0.625379 13.1203L0.421212 0.997682L11.0218 6.88219Z"
                                                fill="#324B4B" />
                                        </svg>
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div> -->
                    <!-- End recopies area -->
                </div>

            </div>
        </div>
</section>
<!-- End how it works area -->

<?php get_footer('header.php')?>