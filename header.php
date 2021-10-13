<!DOCTYPE html>
<html lang="en">

<head>
    <!--Required meta tag-->
    <meta charset="UTF-8" />
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php bloginfo('name');?><?php wp_title('|')?></title>
    <?php wp_head()?>
</head>

<body <?php is_home() ? '' : body_class()?>>
    <!--Start Header Area-->
    <header class="header_main_area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="header_content_area">
                        <div class="logo_area">
                            <a href="<?php echo site_url('/') ?>"><img
                                    src="<?php echo VMH_URL . 'Assets/images/logo.png' ?>" alt="" /></a>
                        </div>
                        <div class="menu_area">
                            <div class="menu">
                                <?php getHeaderMenu()?>
                            </div>

                            <!-- start mobile menu icon -->
                            <div class="mobile-menu-icon">
                                <div class="all-p-humber">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </div>
                            <!-- end mobile menu icon -->
                        </div>
                        <div class="shiping_cart_area">
                            <?php if (is_user_logged_in()) {?>
                            <a class="top_shiping_box" href="<?php echo site_url('/earnings') ?>">
                                <img src="<?php echo VMH_URL . 'Assets/images/sack-doller.png' ?>" alt="earnings"
                                    class="vmh_user_earning" />
                                <!-- <span class="vmh_cart_quantity"><?php echo getTotalCartQuantity() ?></span> -->
                                <!-- <h5><?php echo get_woocommerce_currency_symbol() ?>
                                    <span class="vmh_total_price"><?php echo getTotalCartPrice(); ?></span>
                                </h5> -->
                                <h5>
                                    <span><?php echo totalEarningOfUser(); ?></span>
                                </h5>
                            </a>
                            <?php }?>
                            <div class="shiping_icon_area">
                                <ul>
                                    <li>
                                        <a href="#">


                                            <a href="#" class="dark-mode-swtich">

                                                <div class="wp-dark-mode-switcher wp-dark-mode-ignore">

                                                    <label for="wp-dark-mode-switch"
                                                        class="wp-dark-mode-ignore wp-dark-mode-none">
                                                        <div class="modes wp-dark-mode-ignore">
                                                        </div>
                                                    </label>

                                                </div>

                                                <i class="fas fa-sun"></i>
                                            </a>

                                            <!-- <a href="#" class="main_icon_rating_link">
                                                <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M11 0C4.93461 0 0 4.93461 0 11C0 17.0654 4.93461 22 11 22C17.0654 22 22 17.0654 22 11C22 4.93461 17.0655 0 11 0ZM11 20.8108C8.30526 20.8108 5.86092 19.7185 4.08611 17.9537C3.37734 17.249 2.77568 16.4368 2.30721 15.5441C1.59368 14.1848 1.18921 12.6388 1.18921 11C1.18921 5.59031 5.59031 1.18921 11 1.18921C13.5659 1.18921 15.9045 2.17981 17.6546 3.79817C18.5619 4.63716 19.3112 5.64471 19.8515 6.77089C20.4661 8.05218 20.8108 9.48659 20.8108 11C20.8108 16.4097 16.4097 20.8108 11 20.8108Z"
                                                        fill="#FFF7EF" />
                                                    <path
                                                        d="M7.40771 9.73644C8.06449 9.73644 8.59692 9.20402 8.59692 8.54724C8.59692 7.89046 8.06449 7.35803 7.40771 7.35803C6.75093 7.35803 6.21851 7.89046 6.21851 8.54724C6.21851 9.20402 6.75093 9.73644 7.40771 9.73644Z"
                                                        fill="#FFF7EF" />
                                                    <path
                                                        d="M14.7659 9.73644C15.4227 9.73644 15.9551 9.20402 15.9551 8.54724C15.9551 7.89046 15.4227 7.35803 14.7659 7.35803C14.1091 7.35803 13.5767 7.89046 13.5767 8.54724C13.5767 9.20402 14.1091 9.73644 14.7659 9.73644Z"
                                                        fill="#FFF7EF" />
                                                    <path
                                                        d="M10.979 17.0945C13.2796 17.0945 15.4845 15.9179 16.7469 13.9644L15.748 13.319C14.5888 15.1128 12.4684 16.1139 10.3451 15.8689C8.69048 15.6781 7.16035 14.7248 6.25187 13.319L5.25308 13.9644C6.35235 15.6656 8.20499 16.8192 10.2088 17.0503C10.466 17.08 10.7229 17.0945 10.979 17.0945Z"
                                                        fill="#FFF7EF" />
                                                </svg>
                                            </a> -->

                                            <?php if (is_user_logged_in()) {?>
                                            <a
                                                href="<?php echo esc_url(get_permalink(get_option('vmh_create_product_option'))) ?>">
                                                <i class="fas fa-plus-circle"></i>
                                                <!-- <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="11" cy="11" r="10.5" stroke="white" />
                                                    <path
                                                        d="M11.8386 10.3711H14.1301V12.0278H11.8386V14.6177H10.093V12.0278H7.79517V10.3711H10.093V7.88916H11.8386V10.3711Z"
                                                        fill="white" />
                                                </svg> -->
                                            </a>

                                            <?php }?>

                                            <a href="<?php echo wp_logout_url() ?>">
                                                <i class="fas fa-user-circle"></i>
                                                <!-- <svg width="22" height="23" viewBox="0 0 22 23" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M18.7782 3.44838C16.7006 1.37077 13.9382 0.226562 11 0.226562C8.06168 0.226562 5.29942 1.37077 3.22182 3.44838C1.14421 5.52599 0 8.28824 0 11.2266C0 14.1647 1.14421 16.9271 3.22182 19.0047C5.29942 21.0824 8.06168 22.2266 11 22.2266C13.9382 22.2266 16.7006 21.0824 18.7782 19.0047C20.8558 16.9271 22 14.1647 22 11.2266C22 8.28824 20.8558 5.52599 18.7782 3.44838ZM5.5146 19.2354C5.97467 16.5864 8.27014 14.6325 11 14.6325C13.73 14.6325 16.0253 16.5864 16.4854 19.2354C14.9236 20.3084 13.034 20.9375 11 20.9375C8.96603 20.9375 7.07642 20.3084 5.5146 19.2354ZM7.50241 9.84586C7.50241 7.91713 9.07144 6.34827 11 6.34827C12.9286 6.34827 14.4976 7.9173 14.4976 9.84586C14.4976 11.7744 12.9286 13.3434 11 13.3434C9.07144 13.3434 7.50241 11.7744 7.50241 9.84586ZM17.6019 18.3412C17.255 17.1081 16.566 15.9892 15.6002 15.1152C15.0077 14.579 14.3333 14.1558 13.6082 13.8576C14.9187 13.0027 15.7868 11.5238 15.7868 9.84586C15.7868 7.20647 13.6394 5.0592 11 5.0592C8.36061 5.0592 6.21335 7.20647 6.21335 9.84586C6.21335 11.5238 7.08145 13.0027 8.39183 13.8576C7.6669 14.1558 6.99232 14.5788 6.39983 15.1151C5.4342 15.989 4.74503 17.1079 4.39809 18.3411C2.48715 16.5664 1.28906 14.034 1.28906 11.2266C1.28906 5.87192 5.64536 1.51562 11 1.51562C16.3546 1.51562 20.7109 5.87192 20.7109 11.2266C20.7109 14.0341 19.5128 16.5666 17.6019 18.3412Z"
                                                        fill="#FFF7EF" />
                                                </svg> -->
                                            </a>


                                            <a href="<?php echo site_url('/cart') ?>" class="vmh_cart_total_wrapper">
                                                <svg width="22" height="22" viewBox="0 0 20 20" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M6 16C4.9 16 4 16.9 4 18C4 19.1 4.9 20 6 20C7.1 20 8 19.1 8 18C8 16.9 7.1 16 6 16ZM0 0V2H2L5.6 9.6L4.2 12C4.1 12.3 4 12.7 4 13C4 14.1 4.9 15 6 15H18V13H6.4C6.3 13 6.2 12.9 6.2 12.8V12.7L7.1 11H14.5C15.3 11 15.9 10.6 16.2 9.99996L19.8 3.5C20 3.3 20 3.2 20 3C20 2.4 19.6 2 19 2H4.2L3.3 0H0ZM16 16C14.9 16 14 16.9 14 18C14 19.1 14.9 20 16 20C17.1 20 18 19.1 18 18C18 16.9 17.1 16 16 16Z"
                                                        fill="#ffffff" />
                                                </svg>
                                                <span
                                                    class="vmh_cart_quantity"><?php echo getTotalCartQuantity() ?></span>
                                            </a>

                                        </a>
                                    </li>
                                </ul>

                                <!-- Start Rating Popup -->
                                <div class="subscribe_mail_popup contact_rating_popup main_icon_ratine">
                                    <div class="subscribe_mail_popup_header contact_rating_popup_heade">
                                        <h3>Rate our service</h3>
                                    </div>
                                    <div class="star-rating">
                                        <input type="radio" id="5-stars" name="rating" value="5" />
                                        <label for="5-stars" class="star">&#9733;</label>
                                        <input type="radio" id="4-stars" name="rating" value="4" />
                                        <label for="4-stars" class="star">&#9733;</label>
                                        <input type="radio" id="3-stars" name="rating" value="3" />
                                        <label for="3-stars" class="star">&#9733;</label>
                                        <input type="radio" id="2-stars" name="rating" value="2" />
                                        <label for="2-stars" class="star">&#9733;</label>
                                        <input type="radio" id="1-star" name="rating" value="1" />
                                        <label for="1-star" class="star">&#9733;</label>
                                    </div>
                                    <div class="contact_popup_textarea">
                                        <textarea placeholder="Type your message (Optional)"></textarea>
                                    </div>
                                    <div
                                        class="logon_input_btn shipping_address_btn shipping_method_btn payment_btn rating_btn">
                                        <a href="#"><input type="submit" value="Submit" /></a>
                                    </div>
                                    <div class="subscribe_hide_icon home_page_subscribe_popup ">
                                        <a href="#"><img
                                                src="<?php echo VMH_URL . 'Assets/images/subscribe_hide.png' ?>"
                                                alt="images"></a>
                                    </div>
                                </div>
                                <!-- End Rating Popup -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!--End Header Area-->