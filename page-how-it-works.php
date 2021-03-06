<?php get_header('header.php')?>
<!-- Start how it works area -->
<section class="how_it_work_area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section_heading">
                    <h2>How It Works</h2>
                    <p>Lorem Ipsum is simply dummy text of the printing and</p>
                </div>
            </div>
            <div class="col-md-12">
                <div class="work_details_area">
                    <!-- single items of work -->
                    <div class="left_work_area">
                        <div class="left_work_txt">
                            <h3>How It Works</h3>
                            <p>
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum
                                has been the industry's standard dummy text ever since the 1500s, when an unknown
                                printer took a galley of type.
                            </p>
                        </div>
                    </div>
                    <!-- single items of work -->
                    <div class="middle_work_area">
                        <ul>
                            <li>
                                <a href="#"><span>Step1</span></a>
                            </li>
                            <li>
                                <a href="#"><span>Step1</span></a>
                            </li>
                            <li>
                                <a href="#"><span>Step3</span></a>
                            </li>
                            <li>
                                <a href="#"><span>Step4</span></a>
                            </li>
                        </ul>
                    </div>
                    <!-- single items of work -->
                    <div class="right_work_area">
                        <div class="right_work_img">
                            <img src="<?php echo esc_url(VMH_URL . 'Assets/images/circle_elipse.png') ?>" alt="" />
                        </div>
                    </div>
                    <!-- single items of work -->
                </div>
            </div>
            <div class="col-md-12">
                <!-- start recopies area -->
                <div class="main_recopies_area">
                    <div class="recopies_heading">
                        <h4>Recently added Recopies</h4>
                    </div>
                    <div class="recopies_content_area">
                        <?php getRecentProducts(15)?>
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
        </div>
    </div>
</section>
<!-- End how it works area -->

<?php get_footer('footer.php')?>