<!-- Start Footer main area -->
<footer class="footer_main_area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="footer_content_area">
                    <div class="footer_left_area">
                        <!-- <a href="#"><img src="<?php echo VMH_URL . 'Assets/images/footer-logo.png' ?>" alt="" /></a>
                        <p>
                            All our e-liquid recipes are created by users like you. You too can create your own
                            recipe and get paid doing so. Or sit back and relax, and let our recommendation engine
                            find your favourite recipes for
                            you.
                        </p> -->
                    </div>
                    <div class="footer_middle_area">
                        <div class="usefull_link">
                            <h2>Usefull Link</h2>
                            <?php getFooterMenu()?>
                        </div>
                    </div>
                    <div class="footer_right_area">
                        <h2>Subscribe</h2>
                        <p>Get subscribed for our latest news & products</p>
                        <div class="subscribe_mail home_page_subscribe_popup_main">
                            <form action="#" method="POST" id="vmh_subscriber_form">
                                <div class="single_form">
                                    <input type="email" name="subscriber_mail" class="form-control"
                                        placeholder="Your Email" required />
                                </div>
                                <div class="submit_btn">
                                    <button type="submit">Submit</button>
                                </div>
                            </form>
                            <!-- Start Subscribe Popup -->
                            <div class="subscribe_mail_popup subscribe_hide">
                                <div class="subscribe_mail_popup_header">
                                    <img src="<?php echo VMH_URL . 'Assets/images/check.png' ?>" alt="images">
                                    <h3>Thnak you</h3>
                                </div>
                                <span>You have Successfully Subscribed</span>
                                <div class="subscribe_hide_icon">
                                    <a href="#"><img src="<?php echo VMH_URL . 'Assets/images/subscribe_hide.png' ?>"
                                            alt="images"></a>
                                </div>
                            </div>
                            <!-- End Subscribe Popup -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="footer_btm_area">
                    <div class="copyright_txt">
                        <p>
                            &copy; Copyright <script>
                            document.write(new Date().getFullYear())
                            </script>. All rights reserved.
                        </p>
                    </div>

                    <!-- <div class="privacy_area">
                        <ul>
                            <li><a href="#">Privacy</a></li>
                            <li><a href="#">|</a></li>
                            <li><a href="#">Terms</a></li>
                            <li><a href="#">|</a></li>
                            <li><a href="#">Cookies</a></li>
                        </ul>
                    </div> -->

                </div>
            </div>
        </div>
    </div>


    <!-- The popup -->
    <div class="sign_in_box thank_you_mu create_acc_pages">
        <!-- Hide Btn Overly -->
        <div class="thank_hide_btn">
            <a href="#"><img src="<?php echo VMH_URL . 'Assets/images/thank-you/hide.png' ?>" alt="" /></a>
        </div>

        <div class="sing_in_header sing_in_header2 vmh-response-msg">
            <h3>Thank you</h3>
            <p>Your account successfully created</p>
        </div>

        <div class="logon_input_btn logon_input_btn2 logon_input_btn3 logon_input_btn4 vmh-login-btn">
            <a href="<?php echo site_url('/login') ?>">Login to your account</a>
        </div>
    </div>


</footer>



<!-- End Footer main area -->

<!-- JS File -->
<?php wp_footer();?>

</body>

</html>