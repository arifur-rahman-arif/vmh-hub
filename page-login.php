<?php get_header('header.php')?>
<!--======================== Start Login Area ========================-->
<section class="login_main">
    <div class="container">
        <!-- Start Background Overly -->
        <div class="login_background">
            <img src="<?php echo VMH_URL . 'Assets/images/login/circle.png' ?>" alt="" />
        </div>
        <!-- End Background Overly -->

        <div class="sign_in_box">
            <div class="sing_in_header">
                <h3>Sign in</h3>
                <a href="<?php echo site_url('sign-up') ?>"><span>New User?!</span> &nbsp; Create a new account</a>
            </div>

            <form id="vmh-login-form">
                <div class="login_input">
                    <div class="login_input_left">
                        <label for="log_in_1">Email/Username</label>
                        <input type="text" id="email" name="email" />
                    </div>
                    <div class="login_input_left">
                        <label for="log_in_2">Password</label>
                        <input type="password" id="password" name="password" />
                    </div>
                </div>
                <div class="logon_input_btn">
                    <input type="submit" value="Login" id="vmh-login" class="vmh_button" />
                </div>
            </form>
            <!--
            <div class="login_social">
                <div class="login_down">
                    <h4>Or</h4>
                </div>

                <div class="login_social_icon">
                    <a href="#">
                        <span class="login_social_icon_mini"><img
                                src="<?php echo VMH_URL . 'Assets/Images/login/google_icon.png' ?>" alt="" /></span>
                        <span class="login_social_icon_mini_text">Continue with google</span>
                    </a>
                    <a href="#">
                        <span class="login_social_icon_mini"><i class="fab fa-facebook-f"></i></span>
                        <span class="login_social_icon_mini_text">Continue with Facebook</span>
                    </a>
                    <a href="#">
                        <span class="login_social_icon_mini"><img
                                src="<?php echo VMH_URL . 'Assets/Images/login/apple_logo.png' ?>" alt="" /></span>
                        <span class="login_social_icon_mini_text">Continue with Apple</span>
                    </a>
                </div>
                <div class="login_down_content">
                    <p><span class="login_down_content_col">Protected by captcha and subject to the VHM </span> <a
                            href="#">privacy policy </a><span class="login_down_content_col">and</span> <a
                            href="#">Terms of service</a></p>
                </div>
            </div> -->
        </div>
    </div>
</section>
<!--======================== End Login Area ========================-->


<?php get_footer('header.php')?>