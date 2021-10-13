<?php get_header('header.php')?>

<!--======================== Start Recipes Order Page ========================-->
<section class="login_main">
    <div class="container">
        <!-- Start Background Overly -->
        <div class="recipes_order_background_img">
            <img src="<?php echo esc_url(VMH_URL . 'Assets/images/thank_you2/circle.png') ?>" alt="images" />
        </div>
        <!-- End Background Overly -->

        <div class="userdash_profile mixxer_earning_popup_profile recepes_profile">
            <div class="thank_profile_img">
                <a href="#"><img src="<?php echo esc_url(get_avatar_url(get_current_user_id())) ?>" alt="images" /></a>
            </div>
            <div class="userdash_profile_title">
                <a href="#"><?php echo wp_get_current_user()->display_name ?></a>
                <p><?php echo vmhEscapeTranslate(getUserDescription()) ?></p>
            </div>
        </div>

        <div class="recepes_page">

            <div class="recepes_main_content">

                <form class="recepes_right recipes_order">
                    <div class="recepes_right_mini">
                        <!-- Start recepes right left -->
                        <div class="recepes_right_site_left">
                            <div class="recepes_right_title_icon">
                                <div class="recepes_right_title">
                                    <h2><input type="text" name="vmh_recipe_name" id="vmh_recipe_name" required
                                            placeholder="Your recipe name"></h2>
                                </div>
                            </div>
                            <div class="recepes_right_left_content">
                                <p>By <?php echo wp_get_current_user()->display_name ?></p>
                            </div>

                            <div class="recepes_right_left_input_code">
                                <div class="recepes_right_left_input_code_header">
                                    <h5>Ingredients</h5>
                                </div>
                                <!-- Start Single ingredient item -->
                                <div class="recepes_single_ingridient_item">
                                    <h5>Ingredient</h5>
                                    <input type="text" placeholder="Enter Your Ingredient" />
                                </div>
                                <!-- End Single ingredient item -->
                                <!-- Start Single ingredient item -->
                                <div class="recepes_single_ingridient_item">
                                    <h5>Ingredient</h5>
                                    <input type="text" placeholder="Enter Your Ingredient" />
                                </div>
                                <!-- End Single ingredient item -->
                                <!-- Start Single ingredient item -->
                                <div class="recepes_single_ingridient_item">
                                    <h5>Ingredient</h5>
                                    <input type="text" placeholder="Enter Your Ingredient" />
                                </div>
                                <!-- End Single ingredient item -->
                                <!-- Start Single ingredient item -->
                                <div class="recepes_single_ingridient_item">
                                    <h5>Ingredient</h5>
                                    <input type="text" placeholder="Enter Your Ingredient" />
                                </div>
                                <!-- End Single ingredient item -->
                                <!-- Start Single ingredient item -->
                                <div class="recepes_single_ingridient_item">
                                    <h5>Ingredient</h5>
                                    <input type="text" placeholder="Enter Your Ingredient" />
                                </div>
                                <!-- End Single ingredient item -->

                                <!-- Start Choose Option -->
                                <div class="recepes_choose_option">
                                    <!-- Start Choose Single Item -->
                                    <div class="recepes_single_choose_option">
                                        <div class="recepes_single_choose_option_left what_popup">
                                            <h4>pg. vg &nbsp;</h4>
                                            <a href="#"><i class="fas fa-question"></i>
                                                <div class="another_popupdev devgroup_1">
                                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting
                                                        industry. Lorem Ipsum has been the industry's standard dummy
                                                        text ever since the 1500s, when an unknown printer took a galley
                                                        of type and scrambled it to make a type specimen book. </p>
                                                </div>
                                            </a>
                                        </div>
                                        <?php echo displayPgVgOptions() ?>
                                    </div>
                                    <!-- End Choose Single Item -->
                                    <!-- Start Choose Single Item -->
                                    <div class="recepes_single_choose_option">
                                        <div class="recepes_single_choose_option_left what_popup2">
                                            <h4>Nicotine Type</h4>
                                            <a href="#"><i class="fas fa-question"></i>
                                                <div class="another_popupdev devgroup_2">
                                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting
                                                        industry. Lorem Ipsum has been the industry's standard dummy
                                                        text ever since the 1500s, when an unknown printer took a galley
                                                        of type and scrambled it to make a type specimen book. </p>
                                                </div>
                                            </a>
                                        </div>
                                        <?php echo displayNicotineTypeOptions() ?>
                                    </div>
                                    <!-- End Choose Single Item -->
                                    <!-- Start Choose Single Item -->
                                    <div class="recepes_single_choose_option">
                                        <div class="recepes_single_choose_option_left">
                                            <h4>Nicotine Amount</h4>
                                        </div>
                                        <?php echo displayNicotineAmountOptions() ?>
                                    </div>
                                    <!-- End Choose Single Item -->
                                    <!-- Start Choose Single Item -->
                                    <div class="recepes_single_choose_option">
                                        <div class="recepes_single_choose_option_left">
                                            <h4>Bottle Size</h4>
                                        </div>
                                        <?php echo displayBottleSizeOptions() ?>
                                    </div>
                                    <!-- End Choose Single Item -->
                                </div>
                                <!-- End Choose Option -->

                                <!-- Start Button -->
                                <div class="logon_input_btn logon_input_btn2 shipping_address_btn recepes_btn">
                                    <div class="recepes_btn_content recipes_order_btn_content">
                                        <button class="vmh_button vmh_save_recipe_btn" type="submit">Save
                                            Recepie</button>
                                        <!-- Start Save Recieved Popup -->
                                        <div class="subscribe_mail_popup save_recieved_hde">
                                            <div class="subscribe_mail_popup_header">
                                                <img src="<?php echo esc_url(VMH_URL . 'Assets/images/check.png') ?>"
                                                    alt="images">
                                                <h3>Recepie saved</h3>
                                            </div>
                                            <div class="subscribe_hide_icon">
                                                <a href="#"><img
                                                        src="<?php echo esc_url(VMH_URL . 'Assets/images/subscribe_hide.png') ?>"
                                                        alt="images"></a>
                                            </div>
                                        </div>
                                        <!-- End Save Recieved Popup -->
                                    </div>
                                </div>
                                <!-- End Button -->
                            </div>
                        </div>
                        <!-- End recepes right left -->

                        <!-- Start recepes right right -->
                        <div class="recepes_right_site_right">
                            <!-- Start Button -->
                            <div class="logon_input_btn logon_input_btn2 shipping_address_btn recipes_order_btn">
                                <div class="recepes_btn_content">

                                </div>
                            </div>
                            <!-- End Button -->
                            <!-- Start Content -->
                            <div class="recepes_right_site_right_content">
                                <h6>Note By Creator</h6>
                                <p>
                                    <textarea name="" id="" cols="30" rows="10"
                                        class="focus-visible vmh_recipe_create_note"></textarea>
                                </p>
                            </div>
                            <!-- End Content -->
                            <!-- Start tags -->
                            <div class="recipes_order_tags">
                                <h6>Recipe's tags</h6>

                                <div class="recipes_order_single_tag_plus">
                                    <div class="recipes_order_single_tag">
                                        <span>Tag Name</span>
                                        <input type="hidden" name="vmh_first_tag" id="vmh_first_tag">
                                        <span>Tag Name</span>
                                        <input type="hidden" name="vmh_second_tag" id="vmh_second_tag">
                                        <!-- Start Tag name type -->
                                        <div class="recepes_tag_type_input recepes_tag1">
                                            <input type="text" placeholder="Type Tag Name" data-target="vmh_first_tag">
                                            <input type="text" placeholder="Type Tag Name" data-target="vmh_second_tag">
                                        </div>
                                        <!-- End Tag name type -->
                                    </div>
                                    <div class="recipes_order_single_tag_plus_img">
                                        <a href="#"><img
                                                src="<?php echo esc_url(VMH_URL . 'Assets/images/recipes_order/plus.png') ?>"
                                                alt="images" /></a>
                                    </div>
                                </div>
                            </div>
                            <!-- End Tags -->
                        </div>
                        <!-- End recepes right right -->
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php get_footer('footer.php')?>