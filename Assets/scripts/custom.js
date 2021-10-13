jQuery(document).ready(function ($) {
    // Start Humber Main Menu Icon Js
    $(".all-p-humber").click(function () {
        $(this).toggleClass("open");
    });
    //==== End Humber Main Menu Icon Js
    // menu js
    $(".all-p-humber").click(function () {
        $(".menu ul").slideToggle();
    });

    // Personlisation Page 1
    var availableTags = [
        "Fruit",
        "Tobaco",
        "Dessert",
        "Confectionary",
        "Menthol and Herbs",
        "Apple",
        "Strawberry",
        "Banana",
        "ColdFusion",
        "Erlang",
        "Fortran",
        "Groovy",
        "Haskell",
        "Java",
        "JavaScript",
        "Lisp",
        "Perl",
        "PHP",
        "Python",
        "Ruby",
        "Scala",
        "Scheme",
    ];
    // $("#resautocom").autocomplete({
    //     source: availableTags,
    // });

    // Thank You Page
    // $(".thank_hide_btn").click(function () {
    //     $(".thank_you_mu").fadeOut(500);
    // });

    // User Dashboard
    $(".userdash_premove1").click(function () {
        $(".userdash_your_address_loca_text1").fadeOut();

        return false;
    });
    $(".userdash_premove2").click(function () {
        $(".userdash_your_address_loca_text2").fadeOut();

        return false;
    });
    $(".userdash_premove3").click(function () {
        $(".userdash_your_address_loca_text3").fadeOut();

        return false;
    });

    // Mixxer Earning Popup Page
    $(".mixxer_hide_icon").click(function () {
        $(".mixxer_earning_popup").fadeOut(500);

        return false;
    });

    // box hide js functions
    // $('.my_favorite_close a').click(function(){
    //   $('.my_favorite_close').hide();
    // })

    // Thank You2
    $(".thank_you2_hide_btn").click(function () {
        $(".thank_you2").fadeOut(500);

        return false;
    });

    $(".thank_you2_hide_btn").click(function () {
        $(".login_main").addClass("thank_you2_height");

        return false;
    });

    var x, i, j, l, ll, selElmnt, a, b, c;
    /*look for any elements with the class "custom-select":*/
    x = document.getElementsByClassName("custom-select");
    l = x.length;
    for (i = 0; i < l; i++) {
        selElmnt = x[i].getElementsByTagName("select")[0];
        ll = selElmnt.length;
        /*for each element, create a new DIV that will act as the selected item:*/
        a = document.createElement("DIV");
        a.setAttribute("class", "select-selected");
        a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
        x[i].appendChild(a);
        /*for each element, create a new DIV that will contain the option list:*/
        b = document.createElement("DIV");
        b.setAttribute("class", "select-items select-hide");
        for (j = 1; j < ll; j++) {
            /*for each option in the original select element,
        create a new DIV that will act as an option item:*/
            c = document.createElement("DIV");
            c.innerHTML = selElmnt.options[j].innerHTML;
            c.addEventListener("click", function (e) {
                /*when an item is clicked, update the original select box,
            and the selected item:*/
                var y, i, k, s, h, sl, yl;
                s = this.parentNode.parentNode.getElementsByTagName("select")[0];
                sl = s.length;
                h = this.parentNode.previousSibling;
                for (i = 0; i < sl; i++) {
                    if (s.options[i].innerHTML == this.innerHTML) {
                        s.selectedIndex = i;
                        h.innerHTML = this.innerHTML;
                        y = this.parentNode.getElementsByClassName("same-as-selected");
                        yl = y.length;
                        for (k = 0; k < yl; k++) {
                            y[k].removeAttribute("class");
                        }
                        this.setAttribute("class", "same-as-selected");
                        break;
                    }
                }
                h.click();
            });
            b.appendChild(c);
        }
        x[i].appendChild(b);
        a.addEventListener("click", function (e) {
            /*when the select box is clicked, close any other select boxes,
          and open/close the current select box:*/
            e.stopPropagation();
            closeAllSelect(this);
            this.nextSibling.classList.toggle("select-hide");
            this.classList.toggle("select-arrow-active");
        });
    }
    function closeAllSelect(elmnt) {
        /*a function that will close all select boxes in the document,
      except the current select box:*/
        var x,
            y,
            i,
            xl,
            yl,
            arrNo = [];
        x = document.getElementsByClassName("select-items");
        y = document.getElementsByClassName("select-selected");
        xl = x.length;
        yl = y.length;
        for (i = 0; i < yl; i++) {
            if (elmnt == y[i]) {
                arrNo.push(i);
            } else {
                y[i].classList.remove("select-arrow-active");
            }
        }
        for (i = 0; i < xl; i++) {
            if (arrNo.indexOf(i)) {
                x[i].classList.add("select-hide");
            }
        }
    }
    /*if the user clicks anywhere outside the select box,
    then close all select boxes:*/
    document.addEventListener("click", closeAllSelect);
});

//===========================  Start Update JS ===========================
// Recepes Page Choose Btn(recepes page-footer)
jQuery(document).ready(function ($) {
    $(".subscribe_hide_icon a").click(function () {
        $(".subscribe_hide").hide();
        $("body").removeClass("popup_overly");

        return false;
    });
    $(".submit_btn").click(function () {
        $(".subscribe_hide").show();
        $("body").addClass("popup_overly");

        return false;
    });

    // Save Recieved Btn(recepes order page)
    $(".subscribe_hide_icon a").click(function () {
        $(".save_recieved_hde").hide();
        $("body").removeClass("popup_overly");

        return false;
    });

    // Save Recieved Btn(recepes order page)
    $(".recipes_order_single_tag_plus_img a, .person_search_right a").click(function () {
        $(".recepes_tag1").toggle();

        return false;
    });

    // Send Popup (Contact page)
    $(".subscribe_hide_icon a").click(function () {
        $(".subscribe_hide").hide();
        $("body").removeClass("popup_overly");

        return false;
    });
    // $('.single_btn_ct_area button').click(function(){
    //   $('.send_popup').show();
    //   $('body').addClass('popup_overly');

    //   return false;
    // });
    $(".submit_thanked_btn button").click(function () {
        $(".submit_thanked").show();
        $("body").addClass("popup_overly");

        return false;
    });

    // Edit Profile Popup (User Dashboard Page)
    $(".user_dashboard_header_btn").click(function () {
        $(".edit_profile_popup_hide").show();
        $("body").addClass("popup_overly");

        return false;
    });
    $(".edit_profile a").click(function () {
        $(".edit_profile_popup_hide").hide();
        $("body").removeClass("popup_overly");

        return false;
    });

    // Change Password Popup (User Dashboard Page)
    $(".userdash_change_password_btn").click(function () {
        $(".change_pass").show();
        $("body").addClass("popup_overly");

        return false;
    });
    $(".edit_profile a").click(function () {
        $(".change_pass").hide();
        $("body").removeClass("popup_overly");

        return false;
    });

    // Add Address Popup (User Dashboard Page)
    $(".add_address_popup_parent input").click(function () {
        $(".add_address_popup").show();
        $("body").addClass("popup_overly");

        return false;
    });
    $(".edit_profile a").click(function () {
        $(".add_address_popup").hide();
        $("body").removeClass("popup_overly");

        return false;
    });

    // Home Page Subscribe Popup
    $(".submit_btn").click(function () {
        $(".home_page_subscribe_popup_parent").show();
        $("body").addClass("popup_overly");

        return false;
    });
    $(".home_page_subscribe_popup").click(function () {
        $(".home_page_subscribe_popup_parent").hide();
        $("body").removeClass("popup_overly");

        return false;
    });

    $(".thank_hide_btn").click(function () {
        $(".create_acc_pages").hide();
        $("body").removeClass("popup_overly");

        return false;
    });

    $(".home_page_subscribe_popup a").click(function () {
        $(".contact_rating_popup").hide();
        $("body").removeClass("popup_overly");

        return false;
    });

    // Main All Page Rating Icon
    $(".main_icon_rating_link").click(function () {
        $(".main_icon_ratine").show();
        $("body").addClass("popup_overly");

        return false;
    });
    $(".home_page_subscribe_popup a").click(function () {
        $(".contact_rating_popup").hide();
        $("body").removeClass("popup_overly");

        return false;
    });

    // my favourite page close
    $(".my_orh1").click(function () {
        $(".my_favorite_close").hide();

        return false;
    });

    $(".my_orh3").click(function () {
        $(".my_favorite_close3").hide();

        return false;
    });
    $(".my_orh4").click(function () {
        $(".my_favorite_close4").hide();

        return false;
    });
    $(".my_orh5").click(function () {
        $(".my_favorite_close5").hide();

        return false;
    });
    $(".my_orh6").click(function () {
        $(".my_favorite_close6").hide();

        return false;
    });
    $(".my_orh7").click(function () {
        $(".my_favorite_close7").hide();

        return false;
    });
    $(".my_orh8").click(function () {
        $(".my_favorite_close8").hide();

        return false;
    });
    $(".my_orh9").click(function () {
        $(".my_favorite_close9").hide();

        return false;
    });
    $(".my_orh10").click(function () {
        $(".my_favorite_close10").hide();

        return false;
    });
    $(".my_orh11").click(function () {
        $(".my_favorite_close11").hide();

        return false;
    });
    $(".my_orh12").click(function () {
        $(".my_favorite_close12").hide();

        return false;
    });

    $(".my_orh13").click(function () {
        $(".my_favorite_close13").hide();

        return false;
    });
    $(".my_orh14").click(function () {
        $(".my_favorite_close14").hide();

        return false;
    });
    $(".my_orh15").click(function () {
        $(".my_favorite_close15").hide();

        return false;
    });
    $(".my_orh16").click(function () {
        $(".my_favorite_close16").hide();

        return false;
    });
    $(".my_orh17").click(function () {
        $(".my_favorite_close17").hide();

        return false;
    });
    $(".my_orh18").click(function () {
        $(".my_favorite_close18").hide();

        return false;
    });

    $(".cart_remove2").click(function () {
        $(".cart_items2").remove();

        return false;
    });
    $(".cart_remove3").click(function () {
        $(".cart_items3").remove();

        return false;
    });
    $(".cart_remove4").click(function () {
        $(".cart_items4").remove();

        return false;
    });
    $(".cart_remove5").click(function () {
        $(".cart_items5").remove();

        return false;
    });
    $(".cart_remove6").click(function () {
        $(".cart_items6").remove();

        return false;
    });
    $(".cart_remove7").click(function () {
        $(".cart_items7").remove();

        return false;
    });

    //===========================  End Update JS ===========================
});

// hover js function

jQuery(document).ready(function ($) {
    $(".what_popup a").hover(
        function () {
            $(".devgroup_1").addClass("active");
        },
        function () {
            $(".devgroup_1").removeClass("active");
        }
    );
    $(".what_popup2 a").hover(
        function () {
            $(".devgroup_2").addClass("active");
        },
        function () {
            $(".devgroup_2").removeClass("active");
        }
    );
    $(".what_popup3 a").hover(
        function () {
            $(".devgroup_3").addClass("active");
        },
        function () {
            $(".devgroup_3").removeClass("active");
        }
    );
    $(".what_popup4 a").hover(
        function () {
            $(".devgroup_4").addClass("active");
        },
        function () {
            $(".devgroup_4").removeClass("active");
        }
    );
});
