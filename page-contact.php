<?php get_header('header.php')?>

<!-- Start Banner main area -->
<section class="banner_main_area contact_us_banner">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="banner_headign">
                    <div class="section_heading">
                        <h2>CONTACT</h2>
                        <!-- <p>Lorem Ipsum is simply dummy text of the printing and</p> -->
                    </div>
                    <div class="about_banner_text">
                        <div class="left_work_txt">
                            <h3>How can we help</h3>
                            <!-- <p>
                                Lorem Ipsum is simply dummy text of the printing and done.
                            </p> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Banner main area -->

<!-- Start contact us pages body area -->
<div class="ct_contact_main_area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="contact_pages_form_data">
                    <div class="ct_left_pages_content">

                        <!-- <form action="#" method="POST">
                            <div class="single_form_ct_area">
                                <input class="form-control" type="text" placeholder="Name" id="fanme" />
                            </div>
                            <div class="single_form_ct_area">
                                <input class="form-control" type="email" placeholder="Email" id="email" />
                            </div>
                            <div class="single_form_ct_area">
                                <textarea name="Message" id="Message" placeholder="Your Message"></textarea>
                            </div>
                            <div class="single_btn_ct_area submit_thanked_btn">
                                <button type="submit">Send</button>

                                <div class="subscribe_mail_popup subscribe_hide submit_thanked">
                                    <div class="subscribe_mail_popup_header">
                                        <img src="assets/images/check.png" alt="images">
                                        <h3>Thnak you</h3>
                                    </div>
                                    <span>Your Message Successfully Send</span>
                                    <div class="subscribe_hide_icon">
                                        <a href="#"><img src="assets/images/subscribe_hide.png" alt="images"></a>
                                    </div>
                                </div>
                            </div>
                        </form> -->

                        <?php echo do_shortcode('[contact-form-7 id="119" title="Contact Form"]') ?>

                    </div>
                    <div class="ct_right_pages_content">
                        <div class="ct_main_location">
                            <a href="#">
                                <svg width="22" height="33" viewBox="0 0 22 33" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M10.9999 7.14935C8.44862 7.14935 6.37305 9.257 6.37305 11.8476C6.37305 14.4382 8.44868 16.5458 10.9999 16.5458C13.5513 16.5458 15.6268 14.4382 15.6268 11.8476C15.6268 9.257 13.5513 7.14935 10.9999 7.14935Z"
                                        fill="#141D1D" />
                                    <path
                                        d="M10.9999 0.92572C4.93456 0.92572 0 5.93634 0 12.0952C0 14.624 1.74781 18.6362 5.19499 24.0204C7.71354 27.9542 10.268 31.2361 10.2935 31.2688L11 32.1741L11.7065 31.2688C11.732 31.2361 14.2865 27.9542 16.805 24.0204C20.2521 18.6362 22 14.624 22 12.0952C21.9999 5.93634 17.0653 0.92572 10.9999 0.92572ZM10.9999 18.3762C7.45467 18.3762 4.57038 15.4475 4.57038 11.8476C4.57038 8.24763 7.45467 5.31888 10.9999 5.31888C14.5452 5.31888 17.4295 8.24763 17.4295 11.8476C17.4296 15.4475 14.5453 18.3762 10.9999 18.3762Z"
                                        fill="#141D1D" />
                                </svg>

                                <span><?php echo get_post_meta(get_the_ID(), 'vmh_address', true) ?></span>
                            </a>
                            <a href="<?php echo get_post_meta(get_the_ID(), 'vmh_email', true) ?>">
                                <svg width="32" height="18" viewBox="0 0 32 18" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7.125 2.44172V15.8467L15.7225 9.49984L7.125 2.44172Z" fill="#141D1D" />
                                    <path
                                        d="M21.9078 10.7267L19.5622 12.6523L17.2166 10.7267L8.14844 17.4217H30.9759L21.9078 10.7267Z"
                                        fill="#141D1D" />
                                    <path d="M23.4028 9.49984L32.0003 15.8467V2.44172L23.4028 9.49984Z"
                                        fill="#141D1D" />
                                    <path d="M5.35437 11.8502H0V13.7252H5.35437V11.8502Z" fill="#141D1D" />
                                    <path d="M5.35437 8.12265H1.25V9.99765H5.35437V8.12265Z" fill="#141D1D" />
                                    <path d="M5.35413 4.39453H2.5V6.26953H5.35413V4.39453Z" fill="#141D1D" />
                                    <path d="M7.81104 0.578589L19.5629 10.2267L31.3148 0.578589H7.81104Z"
                                        fill="#141D1D" />
                                </svg>

                                <span><?php echo get_post_meta(get_the_ID(), 'vmh_email', true) ?></span>
                            </a>
                        </div>
                        <div class="ct_follow_us">
                            <h3>Follow Us</h3>
                            <ul>
                                <li>
                                    <a href="<?php echo get_post_meta(get_the_ID(), 'facebook_url', true) ?>"><i
                                            class="fab fa-facebook-f"></i></a>
                                </li>
                                <li>
                                    <a href="<?php echo get_post_meta(get_the_ID(), 'instagram_url', true) ?>"><i
                                            class="fab fa-instagram"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End contact us pages body area -->

<!-- Start maps main area -->
<!-- <div class="contact_maps_main_area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="contact_maps">
                    <div class="mapouter">
                        <div class="gmap_canvas"><iframe style="width: 100% " height="500" id="gmap_canvas"
                                src="https://maps.google.com/maps?q=<?php echo get_post_meta(get_the_ID(), 'google_map_location', true) ?>B&t=&z=15&ie=UTF8&iwloc=&output=embed"
                                frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe><a
                                href="https://www.embedgooglemap.net/blog/divi-discount-code-elegant-themes-coupon/"></a><br>
                            <style>
                            .mapouter {
                                position: relative;
                                text-align: right;
                                height: 500px;
                                width: 100%;
                            }
                            </style>
                            <style>
                            .gmap_canvas {
                                overflow: hidden;
                                background: none !important;
                                height: 500px;
                                width: 100%;
                            }
                            </style>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->
<!-- Start maps main area -->

<?php get_footer('header.php')?>