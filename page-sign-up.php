<?php get_header('header.php')?>
<!--======================== Start Create Area ========================-->
<section class="login_main create_account comon_section_area">
    <div class="container">
        <!-- Start Background Overly -->
        <!-- <div class="create_background">
            <img src="<?php echo VMH_URL.'Assets/images/create-account/circle.png' ?>" alt="" />
        </div> -->
        <!-- End Background Overly -->

        <div class="sign_in_box" style="position: relative;">
            <div class="sing_in_header">
                <h3>Create an account</h3>
                <p><span>Already have an account?</span> &nbsp; <a style="margin-bottom: 20px;"
                        href="<?php echo site_url('login') ?>">Sign in</a>
                </p>
            </div>

            <form id="vmh-signup-form">

                <div class="login_input">
                    <div class="login_input_left_single">
                        <div class="login_input_left">
                            <label for="email">Email Address</label>
                            <input required type="email" id="email" name="email" />
                        </div>
                        <div class="login_input_left">
                            <label for="username">Username</label>
                            <input required type="text" id="username" name="username" />
                        </div>
                        <div class="login_input_left">
                            <label for="password">Password</label>
                            <input required type="password" id="password" name="password" />
                        </div>
                        <div class="login_input_left">
                            <label for="confirm_password">Confirm Password</label>
                            <input required type="password" id="confirm_password" name="confirm_password" />
                        </div>
                    </div>

                    <div class="login_input_left_single">
                        <div class="login_input_left">
                            <label for="fname">First Name</label>
                            <input required type="text" id="fname" name="fname" />
                        </div>
                        <div class="login_input_left">
                            <label for="lname">Last Name</label>
                            <input required type="text" id="lname" name="lname" />
                        </div>
                        <div class="login_input_left create_input_reletive">
                            <label for="date_of_birth">Date of Birth</label>
                            <input required type="text" id="date_of_birth" name="date_of_birth" autocomplete="off" />
                            <div class="create_input_icon_absolute" style="pointer-events: none;">
                                <i class="fas fa-sort-down"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="login_input_left login_input_left_country">
                    <label for="country">Country</label>
                    <input required type="text" id="country" name="country" />
                </div>

                <div class="create_chekbox">
                    <div class="create_chekbox_item">
                        <div class="mu-form-group">
                            <input type="checkbox" id="privacy_policy" />
                            <label for="privacy_policy"></label>
                        </div>
                        <div class="create_chekbox_content mt-1">
                            <p>I accept <?php echo get_bloginfo() ?> terms and conditions <a
                                    href="<?php echo get_privacy_policy_url() ?>">terms & conditions</a> </p>
                        </div>
                    </div>
                    <!-- <div class="create_chekbox_item">
                        <div class="mu-form-group">
                            <input type="checkbox" id="over_age" name="over_age" />
                            <label for="over_age"></label>
                        </div>
                        <div class="create_chekbox_content">
                            <p>I am over 18</p>
                        </div>
                    </div> -->
                </div>
            </form>


            <div class="logon_input_btn logon_input_btn2 login_create_btn create_btn_dots">

                <input type="submit" value="Create Account" form="vmh-signup-form" id="vmh-user-create-submit"
                    class="vmh_button" />
            </div>

            <!-- <div class="login_down_content login_down_content2">
                <p><span class="login_down_content_col">Protected by captcha and subject to the VHM </span> <a
                        href="#">privacy policy </a><span class="login_down_content_col">and</span> <a href="#">Terms of
                        service</a></p>
            </div> -->
        </div>
    </div>
</section>
<!--======================== End Create Area ========================-->

<?php get_footer('header.php')?>