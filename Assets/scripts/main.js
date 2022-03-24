jQuery(document).ready(function ($) {
    let darkModeSwitch = $(".dark-mode-swtich");
    let vmhLoginForm = $("#vmh-login-form");
    let userCreateForm = $("#vmh-signup-form");
    let privacyPolicyCheckbox = $("#privacy_policy");
    let cartRemoveBtn = $(".cart_remove1");
    let favoriteBtn = $(".vmh_favorite");

    let addTagName = $(".add_tag_name");
    let tagInput = $(".predefied_tag_input");
    let saveTagBtn = $(".save_tag_btn");
    let subsciberForm = $("#vmh_subscriber_form");
    let deleteRecipeBtn = $(".vmh_delete_recipe");

    // Calculation of nicotine shot amount
    // These selection is required to to calculation the Nicotine shot
    let nicotineShotTrigger = $("#pa_vmh_bottle_size, #pa_vmh_nicotine_amount");
    let shotAmountElement = $(".shot_amount");
    let nicotineshotCartUpdateBtn = $(".nicotineshot_save_btn");

    // Control the cart nicotine shot input
    let cartNicotineShotInput = $(".cart_nicotine_shot_value");

    let bottleSizeSelect = $("#pa_vmh_bottle_size");

    let addToCartSaveUpdateBtn = $(".save_update_add_to_cart_btn");

    let discardButton = $(".vmh_discard_recipe");

    let emptyCartPage = $("#return-to-shop");
    let wcSuccesAlert = $(".vmh_wc_notice_text");

    let favoriteCloseButton = $(".vmh_favorite_list_close");

    events();

    function events() {
        darkModeSwitch.on("click", toogleDarkMode);
        vmhLoginForm.on("submit", loginHandler);
        userCreateForm.on("submit", handleUserCreate);

        cartRemoveBtn.on("click", removeProductFromCart);

        $(document).on("click", ".vmh_previous_btn, .vmh_checkout_next_btn", moveAroundCheckoutForm);
        clearParameter();
        favoriteBtn.on("click", toggleFavorite);

        $(document).on("click", ".js-dgwt-wcas-enable-mobile-form", hideModal);
        initiateSelectBox();
        $(document).on("click", ".add_ingredients_icon", duplicateSelectBox);
        $(document).on("click", ".cut_selectbox", deleteSelectBox);
        $(addTagName).on("click", duplicateTagInput);

        $(document).on("click", ".cut_tag", deleteInputTag);
        $(".recipes_order_single_tag_plus_img a").click(toggleTagIcon);
        initializeSelectCreateSelectBox();

        // setSelectedValuesForOptions();

        tagInput.on("input", changeTagName);
        saveTagBtn.on("click", saveTagNames);
        subsciberForm.on("submit", createSubscriber);
        deleteRecipeBtn.click(deleteRecipe);

        // Modify the nicotine shot value either on change of select or initial loading
        calculateNicotineShot();

        // calculate the ingredients price value either on change of select or initial loading
        // calculateIngredientsPrice();

        nicotineShotTrigger.on("change", calculateNicotineShot);

        // Controll the cart nicotine shot value input by restricting the increase feature
        cartNicotineShotInput.change(controllCartNicotineShotInput);

        nicotineshotCartUpdateBtn.click(updateNicotineshotValue);

        // Show the attributes options when clicked on this button and create recipe
        $(document).on("click", ".vmh_save_recipe_btn", createRecipe);

        // Intitalize jquery custom date picker
        customDatePicker();

        bottleSizeSelect.on("change", showIngredientPrice);

        addToCartSaveUpdateBtn.on("click", addProductToCart);

        discardButton.on("click", discardRecipe);

        // Redirect to shop page if cart is empty
        redirectToshopPage();

        // Show success notice alert
        showWcSuccessAlert();

        favoriteCloseButton.on("click", removeFavoriteProduct);

        restrictNicotineAmountValue();

        $("#pa_vmh_nicotine_type").on("change", hideNicotineAmountSelect);
        hideNicotineAmountSelect();

        $(".ingredient_percentage, .product_ingredients").on("change", reChecktheRecipe);
        $(document).on("click", ".add_ingredients_icon, .cut_selectbox", reChecktheRecipe);

        hideEmptyCategory();

        $(document).on("click", ".reset_variations", clearPriceOnVariationClear);

        $(".nicotineshot_save_btn").tooltip();

        // Add tooltip on add to cart button
        $(".save_update_add_to_cart_btn, .vmh_single_add_to_cart").tooltip({
            trigger: "manual",
        });

        // $(document).on("click", ".shipping_method_first", (e) => {
        //     $.each($(".vmh_shipping_method_input"), function (i, element) {
        //         console.log($(element));
        //         $(element).attr("checked", "false");
        //     });
        //     let target = $(e.currentTarget);
        //     target.find("input:radio").attr("checked", "checked");
        // });

        navigateUserOnSiteOpening();
    }

    // Navigate the user for first time opening the site
    function navigateUserOnSiteOpening() {
        if (localStorage.getItem("overAge") == null && $("#first_time_opening").length) {
            swal("Are you over 18", "", {
                buttons: {
                    no: {
                        text: "No",
                        value: "no",
                    },
                    yes: {
                        text: "Yes",
                        value: "yes",
                    },
                },
            }).then((value) => {
                switch (value) {
                    case "no":
                        window.location.href = "https://www.google.com/";
                        break;
                    case "yes":
                        localStorage.setItem("overAge", Date.now());
                        break;
                    default:
                        window.location.href = "https://www.google.com/";
                }
            });
        }
    }

    // Clear price box on clicking of clear button
    function clearPriceOnVariationClear() {
        $(".price_box").removeClass("active");
        $(".save_update_add_to_cart_btn, .vmh_single_add_to_cart").tooltip("dispose");
        $(".save_update_add_to_cart_btn, .vmh_single_add_to_cart").tooltip("hide");
    }

    // Hide category container if there are no recipe inside of that element
    function hideEmptyCategory() {
        $(".vmh_hidden_no_product_cat").parents(".category_container").css({
            display: "none",
        });
    }

    // After clicking save recipe if the recipe is duplicate and user change ingredients bring back the previous button
    function reChecktheRecipe() {
        if ($(".create_anyway").length && $(".reload_button").length) {
            $(".recipes_create_buttons").html(`
                <button class="vmh_button vmh_save_recipe_btn recipie_create_next_btn" data-action="check-recepie">
                    Save Recipe
                </button>
            `);
        }
    }

    // Hide the nicotine amount select field when user choose no nicotine type value
    function hideNicotineAmountSelect(e) {
        let target = $("#pa_vmh_nicotine_type");

        let value = target.val();

        if (value == "no-nicotine") {
            setTimeout(() => {
                $("#pa_vmh_nicotine_amount .enabled").removeClass("enabled");
                $("#pa_vmh_nicotine_amount option[value='no-nicotine']").prop("selected", true);
                $("#pa_vmh_nicotine_amount").parents("tr").css({
                    display: "none",
                });
            }, 200);
        } else {
            setTimeout(() => {
                $("#pa_vmh_nicotine_amount option:contains('Choose')").prop("selected", true);

                $("#pa_vmh_nicotine_amount")
                    .parents("tr")
                    .attr("style", function (i, style) {
                        return style && style.replace(/display[^;]+;?/g, "");
                    });
            }, 200);
        }
    }

    // Remove the product from user favorite list
    function removeFavoriteProduct(e) {
        e.preventDefault();
        e.stopPropagation();
        let target = $(e.currentTarget);

        let productID = target.attr("data-id");

        if (!productID) {
            swal({
                title: "Invalid Request",
                text: "Product ID is missing",
                button: "OK",
            });

            return;
        }

        swal({
            title: "Confirmation",
            text: "Are you sure that you want to remove this recipe from your favorite list?",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: vmhLocal.ajaxUrl,
                    data: {
                        productID,
                        btnAction: "unfavorite",
                        action: "vmh_toggle_favorite_product",
                    },
                    method: "post",
                    beforeSend: () => {
                        target.css({
                            "pointer-events": "none",
                        });
                    },
                    success: (res) => {
                        if (!res) return;

                        let response = JSON.parse(res);

                        if (response.response == "invalid") {
                            swal({
                                title: "Error",
                                text: response.message,
                                button: "OK",
                            });
                        }
                        if (response.response == "success") {
                            if (response.status == "removed") {
                                swal({
                                    title: "Removed",
                                    text: "Product removed from favorite list",
                                    button: "OK",
                                });

                                target.parents(".single_recopies_items").remove();

                                if (!$(".recopies_content_area .single_recopies_items").length) {
                                    $(".recopies_content_area").html(`
                                        <div class="card">
                                            <div class="card-body">
                                            You have not added any products to your favorite collection.
                                            </div>
                                        </div>
                                    `);
                                }
                            }
                        }
                    },

                    complete: () => {
                        target.css({
                            "pointer-events": "auto",
                        });
                    },
                    error: (err) => {
                        target.css({
                            "pointer-events": "auto",
                        });
                        swal({
                            title: "Server Error",
                            text: "Someting went wrong try again or contact admin",
                            button: "OK",
                        });
                    },
                });
            }
        });
    }

    // Redirect to shop page if cart is empty
    function redirectToshopPage() {
        if (!emptyCartPage.length) return;

        swal("Empty Cart", "You cart is empty. Return to shop to add recipe in your cart", {
            buttons: {
                cart: {
                    text: "Return to shop",
                    value: "return_to_shop",
                },
            },
        }).then((value) => {
            switch (value) {
                case "return_to_shop":
                    window.location.href = vmhLocal.siteUrl;
                    break;
                default:
                    window.location.href = vmhLocal.siteUrl;
            }
        });
    }

    // Show success notice alert
    function showWcSuccessAlert() {
        if (!wcSuccesAlert.length) return;

        if (!$(".vmh_single_add_to_cart").length) return;

        let title = $(".recepes_right_title h3").text();

        swal(title, `${title} has been added to your cart`, {
            buttons: {
                cart: {
                    text: "Go to cart",
                    value: "cart",
                },
                shopping: {
                    text: "Continue shopping",
                    value: "shopping",
                },
            },
        }).then((value) => {
            switch (value) {
                case "cart":
                    window.location.href = vmhLocal.siteUrl + "cart";
                    break;

                case "shopping":
                    window.location.href = vmhLocal.siteUrl;
                    break;

                default:
                    return;
            }
        });

        $(".swal-title").css({
            "text-transform": "none",
        });
    }

    function discardRecipe(e) {
        e.preventDefault();
        let target = $(e.currentTarget);

        let productID = $("#created_recipe_id").val();

        if (!productID || productID == "") {
            swal({
                title: "Invalid process",
                text: "Product ID is missing",
                button: "OK",
            });
            return;
        }

        swal({
            title: "Confirmation",
            text: "Are you sure that you want to discard this recipe?",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: vmhLocal.ajaxUrl,
                    data: {
                        productID,
                        action: "vmh_remove_product_action",
                    },
                    method: "post",
                    beforeSend: () => {
                        target.css({
                            "pointer-events": "none",
                            opacity: 0.5,
                        });
                        $(".save_update_add_to_cart_btn").css({
                            "pointer-events": "none",
                            opacity: 0.5,
                        });
                    },
                    success: (res) => {
                        if (!res) return;

                        let response = JSON.parse(res);

                        if (response.response == "success") {
                            location.reload();
                        } else {
                            swal({
                                title: "Error",
                                text: response.message,
                                button: "OK",
                            });
                            return;
                        }
                    },
                    error: (err) => {
                        target.css({
                            "pointer-events": "auto",
                            opacity: 1,
                        });
                        console.error(err);
                    },
                });
            }
        });
    }

    function customDatePicker() {
        if ($("#date_of_birth").length) {
            $("#date_of_birth").datetimepicker({
                timepicker: false,
                format: "m/d/Y",
            });
        }
    }

    function toggleTagIcon(e) {
        e.preventDefault();

        let target = $(e.currentTarget);

        let popup = $(".recepes_tag_type_input.recepes_tag1");

        if (popup.hasClass("active")) {
            target.find("i").removeClass("fa-times");
            target.find("i").addClass("fa-edit");

            let tags = $(
                ".input_container .tag_input:not(.duplicate_tag) .vmh_tag_input:not(.predefied_tag_input)"
            );

            if (!tags.length) {
                $(".dynamic_tags").html("");
            }
        } else {
            target.find("i").removeClass("fa-edit");
            target.find("i").addClass("fa-times");
        }

        $(".recepes_tag1").toggle();
        $(".recepes_tag1").toggleClass("active");
    }

    // Toggle the dark mode in webstie frontend
    function toogleDarkMode(e) {
        e.preventDefault();
    }

    // Handle login process
    function loginHandler(e) {
        e.preventDefault();

        let formData = $(e.currentTarget).serialize();

        if ($(".login_input #email").val() == "" || $(".login_input #password").val() == "") {
            swal({
                title: "Empty fields",
                text: "One or more field is empty. Please fill up all fields",
                button: "OK",
            });
            return;
        }

        $.ajax({
            url: vmhLocal.ajaxUrl,
            data: {
                formData,
                action: "vmh_login_action",
            },
            method: "post",
            beforeSend: () => {
                $("#vmh-login").addClass("disabled");
            },
            success: (res) => {
                if (!res) return;

                let response = JSON.parse(res);
                if (response.response == "success") {
                    window.location.href = vmhLocal.siteUrl;
                } else {
                    swal("Are you sure you want to do this?", {
                        buttons: ["Oh noez!", "Aww yiss!"],
                    });
                    swal({
                        title: "Sorry",
                        text: response.message,
                        button: "OK",
                    });
                    return;
                }
            },
            complete: () => {
                $("#vmh-login").removeClass("disabled");
            },
            error: (err) => {
                swal({
                    title: "Server Error",
                    text: "Something went wrong. Try again or contact admin",
                    button: "OK",
                });
                return;
            },
        });
    }

    // Handle the user creation form
    function handleUserCreate(e) {
        e.preventDefault();
        let responseMsgContainer = $(".vmh-response-msg");
        let currentTarget = $(e.currentTarget);
        let formData = currentTarget.serialize();

        // check if user is over 18 years old
        if (isAgeOverEighteen($("#date_of_birth").val()) === "no") {
            swal({
                title: "Age Restriction",
                text: "You must be over 18 to create an account",
                button: "OK",
            });
            return;
        }

        if (privacyPolicyCheckbox.prop("checked") == false) {
            swal({
                title: "Terms & Conditions",
                text: "Please agree to our terms & conditions",
                button: "OK",
            });
            return;
        }

        $.ajax({
            url: vmhLocal.ajaxUrl,
            data: {
                formData,
                action: "vmh_create_user",
            },
            method: "post",
            beforeSend: () => {
                $("#vmh-user-create-submit").attr("disabled", true);
            },
            success: (res) => {
                if (!res) return;

                let response = JSON.parse(res);

                if (response.response == "invalid") {
                    swal({
                        title: "Error Occurred",
                        text: response.message,
                        button: "OK",
                    });
                    $(".vmh-login-btn").hide();
                }
                if (response.response == "success") {
                    swal({
                        title: "Thank you",
                        text: response.message,
                        button: "OK",
                    });
                    $(".vmh-login-btn").hide();
                    setTimeout(() => {
                        window.location.href = vmhLocal.siteUrl + "login";
                    }, 2000);
                }

                $("#vmh-user-create-submit").attr("disabled", false);
            },
            error: (err) => {
                swal({
                    title: "Server Error",
                    text: "Someting went wrong try again or contact admin",
                    button: "OK",
                });
            },
        });
    }

    // check if user age is over 18 or not
    function isAgeOverEighteen(date) {
        let currentDate = new Date();
        let userDate = new Date(date);

        let currentDateYear = currentDate.getFullYear();
        let userDateYear = userDate.getFullYear();

        if (parseInt(currentDateYear) - parseInt(userDateYear) > 18) {
            return "yes";
        } else {
            return "no";
        }
    }

    // Add the product to cart
    function addProductToCart(e) {
        e.preventDefault();

        let target = $(e.currentTarget);
        let productID = target.attr("data-id");
        let productName = $("#vmh_recipe_name").val();
        let variationsValue = getAttributeOptionsValue();
        let recipeAction = "add-to-cart";
        let nicotineShotValue = $("#nicotine_shot_value").val();

        let requiredFields = showAlertOnRequiredFields(productName, variationsValue, recipeAction);

        // If there are nay missing required field than show the alert popup and stop executing
        if (requiredFields === false) {
            return false;
        }

        if (!productID) {
            swal({
                title: "Invalid process",
                text: "Product ID is missing",
                button: "OK",
            });
            return;
        }

        if (!variationsValue) {
            swal({
                title: "Invalid process",
                text: "Variation are missing",
                button: "OK",
            });
            return;
        }

        // if (!nicotineShotValue) {
        //     swal({
        //         title: "Invalid process",
        //         text: "Nicotine shot value is missing",
        //         button: "OK",
        //     });
        //     return;
        // }

        $.ajax({
            url: vmhLocal.ajaxUrl,
            method: "POST",
            data: {
                productID,
                variationsValue,
                nicotineShotValue,
                action: "vmh_add_product_to_cart",
            },
            beforeSend: () => {
                $(".vmh_alert").hide();
                $(e.currentTarget).attr("disabled", true);
                $(e.currentTarget).addClass("disabled");
            },
            success: (response) => {
                if (response.data.response == "invalid") {
                    swal({
                        title: "Error or invalid process",
                        text: response.data.message,
                        button: "OK",
                    });
                }

                if (response.data.response == "success") {
                    let cartQuantity = $(".vmh_cart_quantity");

                    let updatedQuatity = parseInt(cartQuantity.text()) + 1;

                    cartQuantity.html(updatedQuatity);

                    swal("Your recipe added to cart. Go to your cart of continue shopping", {
                        buttons: {
                            cart: {
                                text: "Go to cart",
                                value: "cart",
                            },
                            shopping: {
                                text: "Continue shopping",
                                value: "shopping",
                            },
                        },
                    }).then((value) => {
                        switch (value) {
                            case "cart":
                                window.location.href = vmhLocal.siteUrl + "cart";
                                break;

                            case "shopping":
                                window.location.href = vmhLocal.siteUrl;
                                break;

                            default:
                                return;
                        }
                    });
                }
            },

            complete: () => {
                $(e.currentTarget).attr("disabled", false);
                $(e.currentTarget).removeClass("disabled");
            },

            error: (err) => {
                swal({
                    title: "Server Error",
                    text: "Someting went wrong try again or contact admin",
                    button: "OK",
                });
            },
        });
    }

    // Remove the product from cart
    function removeProductFromCart(e) {
        e.preventDefault();

        let target = $(e.currentTarget);
        let productID = target.attr("data-id");
        let cartKey = target.attr("data-key");

        if (!cartKey) {
            swal({
                title: "Invalid process",
                text: "Cart key is required",
                button: "OK",
            });
            return;
        }

        if (!productID) return;

        swal({
            title: "Confirmation",
            text: "Are you sure you want to remove this recipe from cart",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: vmhLocal.ajaxUrl,
                    data: {
                        productID,
                        action: "vmh_remove_product_from_cart",
                        cartKey,
                    },
                    method: "post",

                    beforeSend: () => {
                        target.css({
                            "pointer-events": "none",
                        });
                    },

                    success: (res) => {
                        if (!res) return;

                        let response = JSON.parse(res);

                        if (response.response == "invalid") {
                            swal({
                                title: "Error",
                                text: response.message,
                                button: "OK",
                            });
                        }

                        if (response.response == "success") {
                            location.reload();
                        }
                    },
                    complete: () => {
                        target.css({
                            "pointer-events": "auto",
                        });
                    },
                    error: (err) => {
                        target.css({
                            "pointer-events": "auto",
                        });
                        swal({
                            title: "Error",
                            text: "Someting went wrong try again or contact again",
                            button: "OK",
                        });
                    },
                });
            }
        });
    }

    // Get the URL parameter
    function getUrlParameter(sParam) {
        let sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split("&"),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split("=");

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
            }
        }
        return false;
    }

    function moveAroundCheckoutForm(e) {
        e.preventDefault();
        let target = $(e.currentTarget);

        let requiredFields = target.parents(".sign_in_box").find(".form-row .required");

        let allFieldOk = true;

        $.each(requiredFields, function (i, requiredField) {
            if (
                (!$(requiredField).parents(".form-row").find("input, select").val() ||
                    $(requiredField).parents(".form-row").find("input[type='checkbox']").prop("checked") ==
                        false) &&
                target.hasClass("vmh_checkout_next_btn")
            ) {
                swal({
                    text: `${$(requiredField).parent().text().replace("*", " ").trim()} is a required field`,
                    button: "OK",
                });
                allFieldOk = false;
                return false;
            }
        });

        if (!allFieldOk) return;

        let parentContainer = target.parents(".vmh_checkout_form");
        let nextCheckoutTarget = target.attr("data-target");
        parentContainer.removeClass("checkout_form_active");
        $(`.${nextCheckoutTarget}`).addClass("checkout_form_active");
        pushParameter(nextCheckoutTarget);
    }

    // Push parameter to the url
    function pushParameter(parmeter) {
        let url = new URL(window.location);
        url.searchParams.set("form-step", parmeter);
        window.history.pushState({}, "", url);
    }

    // Clear parameter from the url
    function clearParameter() {
        let url = new URL(window.location);
        let formParameter = url.searchParams.get("form-step");
        if (formParameter) {
            url.searchParams.set("form-step", "");
            window.history.pushState({}, "", url);
        }
    }

    // Toggle a product to favorite or not
    function toggleFavorite(e) {
        e.preventDefault();
        let target = $(e.currentTarget);
        let productID = parseInt(target.attr("data-id"));
        let heartBtn = target.find(".vmh_heart");
        let btnAction = target.attr("data-action");
        if (!productID) return;

        $.ajax({
            url: vmhLocal.ajaxUrl,
            data: {
                productID,
                btnAction,
                action: "vmh_toggle_favorite_product",
            },
            method: "post",
            beforeSend: () => {
                target.css({
                    "pointer-events": "none",
                });
            },
            success: (res) => {
                if (!res) return;

                let response = JSON.parse(res);

                if (response.response == "invalid") {
                    swal({
                        title: "Error",
                        text: response.message,
                        button: "OK",
                    });
                }
                if (response.response == "success") {
                    // if the page is not from my favorite page
                    if (!target.hasClass("vmh_fav_close")) {
                        if (response.status == "added") {
                            heartBtn.removeClass("vmh_heart_grey");
                            target.attr("data-action", "unfavorite");
                            swal({
                                title: "Done",
                                text: "Product added to your favorite list",
                                button: "OK",
                            });
                        }
                        if (response.status == "removed") {
                            heartBtn.addClass("vmh_heart_grey");
                            target.attr("data-action", "favorite");
                            swal({
                                title: "Done",
                                text: "Product removed from favorite list",
                                button: "OK",
                            });
                        }
                    }
                }
            },

            complete: () => {
                target.css({
                    "pointer-events": "auto",
                });
            },
            error: (err) => {
                target.css({
                    "pointer-events": "auto",
                });
                swal({
                    title: "Server Error",
                    text: "Someting went wrong try again or contact admin",
                    button: "OK",
                });
            },
        });
    }

    // Create a simple product as new recipe
    function createRecipe(e) {
        e.preventDefault();

        let target = $(e.currentTarget);
        let productName = $("#vmh_recipe_name").val();
        let ingredientsValues = getIngredientsValues();
        let recipeNote = $(".vmh_recipe_create_note").val();
        let tagValues = getTagValues();
        let optionsValue = getAttributeOptionsValue();
        let ingredientsPercentageValues = getIngredientsPercentageValues();
        let recipeAction = target.attr("data-action");
        let proudctID = parseInt(getSlugParameter("edit_product"));

        let isDuplicate = target.attr("data-duplicate");
        let originalProductID = target.attr("data-original-product");

        let comparisionData = {
            isDuplicate,
            originalProductID,
        };

        if (recipeAction == "update-recepie") {
            if (!proudctID) {
                swal({
                    title: "Invalid process",
                    text: "Product name is required",
                    button: "OK",
                });
                return;
            }
        }

        let requiredFields = showAlertOnRequiredFields(productName, optionsValue, recipeAction);

        // If there are nay missing required field than show the alert popup and stop executing
        if (requiredFields === false) {
            return false;
        }

        let isIngredientsOk = showAlertOnEmptyIngredients();

        // If all ingredients are not ok than stop executing and show a popup
        if (isIngredientsOk === false) {
            return false;
        }

        let totalIngredientsAmount = calculatePercentage();

        $.ajax({
            type: "post",
            url: vmhLocal.ajaxUrl,
            data: {
                productName,
                ingredientsValues,
                ingredientsPercentageValues,
                recipeNote,
                tagValues,
                optionsValue,
                action: "vmh_create_product",
                recipeAction,
                proudctID,
                totalIngredientsAmount,
                comparisionData,
            },
            beforeSend: () => {
                $(e.currentTarget).addClass("disabled").attr("disabled", true);
                target
                    .parents(".recipes_create_buttons")
                    .children()
                    .addClass("disabled")
                    .attr("disabled", true);

                // Disable the product ingredients section
                $(
                    ".ingredients_wrapper, .vmh_product_content textarea, .recipes_order_tags, .vmh_tag_list, #vmh_recipe_name"
                ).css({
                    "pointer-events": "none",
                    opacity: 0.5,
                });
            },

            success: (res) => {
                if (!res) return;

                if (res.data.response == "duplicate") {
                    swal({
                        // title: "Information",
                        text: res.data.message,
                        button: "OK",
                    });

                    $(".swal-modal .swal-text").html(`
                        Sorry but there is another recipe: <a href="${res.data.comparision.productUrl}" target="_blank"><b>${res.data.comparision.productName}</b></a> 
                        which is more than 95% similar to yours and so the creator of the original recipe will get the royalties for this recipe & this recipe will be only visible to you
                    `);

                    target.parents(".recipes_create_buttons").html(`
                        <a class="vmh_button recipie_create_next_btn reload_button" href="${vmhLocal.templateProductUrl}">Cancel</a>
                        <button class="vmh_button vmh_save_recipe_btn recipie_create_next_btn create_anyway" 
                                data-action="save-recepie" 
                                data-duplicate='1'
                                data-original-product="${res.data.comparision.originalProductID}"
                                style="width: 120px !important">
                            Create Anyway
                        </button>
                    `);

                    return;
                }

                if (res.data.response == "success") {
                    swal({
                        title: "Created",
                        text: res.data.message,
                        button: "OK",
                    });

                    /* Show the next part of recipe creation */
                    $(".create_recipe_option").show();
                    $(".recepes_btn").css({
                        display: "flex",
                    });
                    restrictNicotineAmountValue();
                    target.parent().hide();
                    /* End of the next part of recipe creation */

                    // Disable the product ingredients section
                    $(
                        ".ingredients_wrapper, .vmh_product_content textarea, .recipes_order_tags, .vmh_tag_list, #vmh_recipe_name"
                    ).css({
                        "pointer-events": "none",
                        opacity: 0.5,
                    });

                    $(".cut_selectbox, .add_ingredients_icon").remove();

                    $("#created_recipe_id").val(res.data.id);
                    $(".save_update_add_to_cart_btn").attr("data-id", res.data.id);
                }
            },

            complete: () => {
                $(e.currentTarget).attr("disabled", false);
                $(e.currentTarget).removeClass("disabled");
            },

            error: (err) => {
                $(e.currentTarget).attr("disabled", false);
                $(e.currentTarget).removeClass("disabled");

                try {
                    swal({
                        title: "Server Error",
                        text: err.responseJSON.data.message,
                        button: "OK",
                    });
                    return;
                } catch (error) {
                    swal({
                        title: "Server Error",
                        text: "Something went wrong. Try again or contact admin",
                        button: "OK",
                    });
                    return;
                }
            },
        });
    }

    function showAlertOnRequiredFields(productName, optionsValue, recipeAction) {
        let productAttributes = vmhLocal.vmhProductAttributes;
        let attributesLength = Object.keys(productAttributes).length;

        // Check if the prodcut name exits
        if (!productName) {
            swal({
                title: "Invalid process",
                text: "Product name is required",
                button: "OK",
            });
            return false;
        }

        if (showAlertOnEmptyIngredients() === false) {
            return false;
        }

        let ingredientsValues = getIngredientsValues();

        if (new Set(ingredientsValues).size !== ingredientsValues.length) {
            swal({
                title: "Invalid process",
                text: "Please remove duplicate ingredients",
                button: "OK",
            });
            return false;
        }

        let totalPercentage = calculatePercentage();

        if (totalPercentage >= 30) {
            swal({
                title: "Invalid process",
                text: "Ingredients amount is exceeded. Please keep it in 30%",
                button: "OK",
            });
            return false;
        }

        if (
            optionsValue.length !== attributesLength &&
            recipeAction != "save-recepie" &&
            recipeAction != "check-recepie"
        ) {
            swal({
                title: "Invalid process",
                text: "All options needs to filled",
                button: "OK",
            });
            return false;
        }

        let stockAvailablity = $(".woocommerce-variation-availability");

        if (stockAvailablity.find(".out-of-stock").length > 0) {
            swal({
                title: "Invalid process",
                text: "Product variation is out of stock",
                button: "OK",
            });
            return false;
        }

        if ($(".wc-no-matching-variations.woocommerce-info").length) {
            swal({
                title: "Invalid process",
                text: "Sorry your selected combination is not available. Please choose a different combination",
                button: "OK",
            });
            return false;
        }
    }

    // Show a alert value if all ingredients percentage value is not filled
    function showAlertOnEmptyIngredients() {
        let ingredients = $("select.product_ingredients:not(#product_ingredients_0)");

        let recipePopup = $(".vmh_create_recipe_popup");

        if (!ingredients.val() || ingredients.val() == "") {
            swal({
                title: "Invalid process",
                text: "Please select 1 or more ingredients",
                button: "OK",
            });
            return false;
        }

        if (ingredients.length) {
            let breakLoop = false;

            $.each(ingredients, function (i, element) {
                let value = $(element).val();

                if (value) {
                    let inputElement = $(element).parent().find(".ingredient_percentage");
                    let percentageValue = parseFloat(inputElement.val());
                    if (!percentageValue || percentageValue == 0) {
                        breakLoop = true;
                        return false; // breaks
                    }
                } else {
                    $(element).parent().remove();
                }
            });

            if (breakLoop) {
                recipePopup.find(".vmh_checkbox_image").css({
                    display: "none",
                });
                recipePopup.find(".vmh_checkbox_image_warning").css({
                    display: "block",
                });

                swal({
                    title: "Invalid process",
                    text: "All selected ingredients percentage value needs to be filled",
                    button: "OK",
                });
                return false;
            }
        }
    }

    // Get the ingredients value from create recipe
    function getIngredientsValues() {
        let ingredientsValues = [];

        $.each($(".product_ingredients"), function (indexInArray, valueOfElement) {
            if ($(valueOfElement).val()) {
                ingredientsValues.push($(valueOfElement).val());
            }
        });

        return ingredientsValues;
    }

    // Get all the tag values
    function getTagValues() {
        let tags = $(".input_container .vmh_tag_input");

        if (tags.length < 1) return [];

        let tagValues = [];

        $.each(tags, function (indexInArray, tag) {
            let tagValue = $(tag).val();
            tagValues.push(tagValue);
        });

        return tagValues;
    }

    // Get the ingredients percentage value from create recipe
    function getIngredientsPercentageValues() {
        let ingredientsPercentageValues = [];

        $.each($(".ingredient_percentage"), function (indexInArray, valueOfElement) {
            // if both select box value and
            if ($(valueOfElement).parent().find(".product_ingredients").val() && $(valueOfElement).val()) {
                ingredientsPercentageValues.push(Number($(valueOfElement).val()));
            }
        });

        // Push ingredients on normal single recipe page
        if (!ingredientsPercentageValues.length) {
            $.each($(".ingredient_percentage_normal"), function (indexInArray, valueOfElement) {
                if ($(valueOfElement).val()) {
                    ingredientsPercentageValues.push(Number($(valueOfElement).val()));
                }
            });
        }

        return ingredientsPercentageValues;
    }

    // Get options value from selectbox
    function getAttributeOptionsValue() {
        let optionsValue = [];

        let productAttributes = vmhLocal.vmhProductAttributes;

        if (!productAttributes) return [];

        for (const key in productAttributes) {
            if (Object.hasOwnProperty.call(productAttributes, key)) {
                let selectedText = $(`#pa_${key} option:selected`).text();
                let selectedValue = $(`#pa_${key} option:selected`).val();
                if (selectedText != "Choose") {
                    let valueObject = {};

                    valueObject[selectedValue] = [`${key} | ${selectedText}`];

                    optionsValue.push(valueObject);
                }
            }
        }

        return optionsValue;
    }

    function hideModal() {
        $("#vmh_search_modal").modal("hide");
    }

    // Initiate the select box in create product option
    function initiateSelectBox() {
        if (!$("#product_ingredients_1").length) return;

        let selector = new SlimSelect({
            select: "#product_ingredients_1",
            placeholder: "Select Ingredients",
            allowDeselectOption: true,
            valuesUseText: false,
            hideSelectedOption: false,
            searchText: "Sorry ingredients not found.",
            // addable: function (value) {
            //     // Optional - Return a valid data object. See methods/setData for list of valid options
            //     return {
            //         text: value,
            //         value: value.toLowerCase(),
            //     };
            // },
        });
    }

    // Duplicate the ingredients select box
    function duplicateSelectBox(e) {
        let totalPercentage = calculatePercentage();

        if (totalPercentage >= 30) {
            swal({
                title: "Invalid process",
                text: "Ingredients amount is limited to 30%",
                button: "OK",
            });
            return false;
        }

        if ($(".ingredients_wrapper select").length >= 11) {
            swal({
                title: "Sorry",
                text: "You can have only maximum of 10 ingredients",
                button: "OK",
            });
            return false;
        }

        $(".vmh_alert").slideUp(500);

        let selectorElement = $("#ingredients_wrapper_0");

        let uniqueId = Date.now();

        let copy = selectorElement.clone(true);

        copy.find(".add_ingredients_icon").remove();

        let parantID = "ingredients_wrapper_" + uniqueId;

        let selectorID = "product_ingredients_" + uniqueId;

        copy.attr("id", parantID);

        copy.css({
            display: "flex",
        });

        copy.find(".product_ingredients").attr("id", selectorID);

        $(".ingredients_container").append(copy);

        new SlimSelect({
            select: $(`#${selectorID}`)[0],
            placeholder: "Select Ingredients",
            allowDeselectOption: true,
            valuesUseText: false,
            hideSelectedOption: false,
            searchText: "Sorry ingredients not found.",
        });
    }

    // Duplicate the tag input box
    function duplicateTagInput() {
        let selectorElement = $(".input_container .duplicate_tag");

        let copy = selectorElement.clone(true);

        copy.removeClass("duplicate_tag");

        copy.css({
            display: "flex",
        });

        $(".tags_popup_container .input_container").append(copy);
    }

    function deleteSelectBox(e) {
        let target = $(e.currentTarget);

        let wrapperElements = target.parents(".ingredients_wrapper");

        wrapperElements.remove();
    }

    // Delete the input tag on clicking of delete button
    function deleteInputTag(e) {
        let target = $(e.currentTarget);

        let wrapperElements = target.parents(".tag_input");
        wrapperElements.remove();
    }

    // Calculate the percentage value of ingredients
    function calculatePercentage() {
        let targets = $(".ingredient_percentage");

        if (!targets.length) return;

        let ingredientsPercentage = [];

        $.each(targets, function (indexInArray, valueOfElement) {
            let value = Number($(valueOfElement).val());

            ingredientsPercentage.push(value);
        });

        let total = ingredientsPercentage.reduce((prevValue, currentValue) => prevValue + currentValue);

        return total;
    }

    // Initiate ingredients select box when existing create product is active
    function initializeSelectCreateSelectBox() {
        if (!getSlugParameter("edit_product")) return;

        let selectBoxWrappers = $(".create_ingredients_wrapper");

        $.each(selectBoxWrappers, function (indexInArray, wrapper) {
            let selectBox = $(wrapper).find(".product_ingredients");

            let select = new SlimSelect({
                select: selectBox[0],
                placeholder: "Select Ingredients",
                allowDeselectOption: true,
                valuesUseText: false,
                hideSelectedOption: false,
                searchText: "Sorry ingredients not found.",
            });

            select.set(selectBox.attr("data-seleted_val"));
        });
    }

    function getSlugParameter(slug) {
        let url = new URL(window.location);
        let params = new URLSearchParams(url.search);
        let retrieve_param = params.get(slug);
        if (retrieve_param) {
            return retrieve_param;
        } else {
            return false;
        }
    }

    // // Set the product options dropdown values if user want to edit another user's created product
    // function setSelectedValuesForOptions() {
    //     if (!getSlugParameter("edit_product")) return;

    //     let params = {};

    //     window.location.search
    //         .slice(1)
    //         .split("&")
    //         .forEach((elm, i) => {
    //             if (elm === "") return;

    //             if (i != 0) {
    //                 let spl = elm.split("=");
    //                 const d = decodeURIComponent;
    //                 params[d(spl[0])] = spl.length >= 2 ? d(spl[1]) : true;
    //             }
    //         });

    //     for (const key in params) {
    //         if (Object.hasOwnProperty.call(params, key)) {
    //             const element = params[key];
    //             let selectBox = $(`#pa_${element}`);
    //             selectBox.val(key);
    //         }
    //     }
    // }

    // Change the predefined tag name on typing of tag name
    function changeTagName(e) {
        let target = $(e.currentTarget);
        let spanTag = $(".vmh_tag_list").find(`[data-target=tag_name_${target.attr("data-id")}]`);
        if (target.val()) {
            spanTag.text(target.val());
        } else {
            spanTag.text("No Tag");
        }
    }

    // Save all the tags upon click on save tag button
    function saveTagNames(e) {
        let tags = $(
            ".input_container .tag_input:not(.duplicate_tag) .vmh_tag_input:not(.predefied_tag_input)"
        );

        if (tags.length) {
            let innerHTML = "";

            $.each(tags, function (i, tag) {
                let tagValue = $(tag).val();
                if (tagValue) {
                    innerHTML += `<a href="#" class="tag_name" data-target="tag_name_${
                        i + 3
                    }">${tagValue}</a>`;
                }
            });
            $(".dynamic_tags").html(innerHTML);
        } else {
            $(".dynamic_tags").html("");
        }

        $(".recepes_tag_type_input.recepes_tag1").removeClass("active");
        $(".recepes_tag_type_input.recepes_tag1").css({
            display: "none",
        });

        $(".tag_edit_icon").removeClass("fa-times");
        $(".tag_edit_icon").addClass("fa-edit");
    }

    // create a subscriber on submit of a subscriber form
    function createSubscriber(e) {
        e.preventDefault();

        let currentTarget = $(e.currentTarget);

        if (currentTarget.find("input[type=email]").val() == "")
            return alert("Email is required to subscribe");

        let formData = currentTarget.serialize();

        $.ajax({
            url: vmhLocal.ajaxUrl,
            data: {
                formData,
                action: "vmh_subscriber_action",
            },
            method: "post",
            success: (res) => {
                if (!res) return;

                let response = JSON.parse(res);

                if (response.response == "success") {
                    swal({
                        title: "Thank you",
                        text: "You have successfully subscribed",
                        button: "OK",
                    });
                    return false;
                } else {
                    swal({
                        title: "Sorry",
                        text: response.message,
                        button: "OK",
                    });
                    return false;
                }
            },
            error: (err) => {
                swal({
                    title: "Error",
                    text: "Something went wrong. Please try again",
                    button: "OK",
                });
                console.error(err);
            },
        });
    }

    // Delete a user created recipe if the user is a subscriber
    function deleteRecipe(e) {
        e.preventDefault();
        e.stopPropagation();
        let target = $(e.currentTarget);

        let productID = target.attr("data-id");

        if (!productID || productID == "") {
            swal({
                title: "Invalid process",
                text: "Product ID is missing",
                button: "OK",
            });
            return false;
        }

        let item = target.parents(".single_recopies_items");
        let itemTitle = item.find("h6").text();

        swal({
            title: "Confirmation",
            text: "Are you sure that you want to delete this recipe? If you do so you won't get any royalty for this recipe from now on",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: vmhLocal.ajaxUrl,
                    data: {
                        productID,
                        action: "vmh_remove_product_action",
                    },
                    method: "post",
                    success: (res) => {
                        if (!res) return;

                        let response = JSON.parse(res);

                        if (response.response == "success") {
                            swal({
                                title: "Success",
                                text: `${itemTitle} is removed from your list`,
                                button: "OK",
                            });
                            item.remove();
                        } else {
                            swal({
                                title: "Error",
                                text: response.message,
                                button: "OK",
                            });
                        }
                    },
                    error: (err) => {
                        swal({
                            title: "Server Error",
                            text: "Something went wrong. Try again or contact admin",
                            button: "OK",
                        });
                    },
                });
            }
        });
    }

    function calculateNicotineShot() {
        let nicotineAmount = parseFloat($("#pa_vmh_nicotine_amount option:selected").text());
        let bottleSize = parseFloat($("#pa_vmh_bottle_size").val());

        let nictineShotValue = 0;

        nictineShotValue = (nicotineAmount / 100 / (1.8 / 100)) * bottleSize;

        // nictineShotValue = roundNumberTo10Times(nictineShotValue);

        if (nictineShotValue || nictineShotValue == 0) {
            nictineShotValue = nictineShotValue.toFixed(2);
            shotAmountElement.html(nictineShotValue);
            $("#nicotine_shot_value").val(nictineShotValue);
        } else {
            $("#nicotine_shot_value").val("0");
        }
    }

    // Round the number to nearest 10 times
    // For exmplate 2 would be 10 11 would be 20 like this
    function roundNumberTo10Times(n) {
        let roundedNumber = Math.ceil(n / 10) * 10;

        return roundedNumber;
    }

    // Update the nicotine shot value from cart page
    function updateNicotineshotValue(e) {
        let target = $(e.currentTarget);

        let cartKey = target.attr("data-key");
        let nicotineShot = Number(target.siblings(".cart_nicotine_shot_value").val());
        let saltType = target.attr("data-type");
        let typeCount = target.attr("data-type-count");

        if (nicotineShot < 0) {
            swal({
                title: "Warning",
                text: "Nicotine shot value can not be a negative number",
                button: "OK",
            });
            return false;
        }
        if (!saltType) {
            swal({
                title: "Error",
                text: "Nicotine salt type is missing",
                button: "OK",
            });
            return false;
        }
        if (typeCount == undefined || typeCount == null) {
            swal({
                title: "Error",
                text: "Salt type count is missing",
                button: "OK",
            });
            return false;
        }

        // if (!cartKey) {
        //     swal({
        //         title: "Error",
        //         text: "Invalid cart key",
        //         button: "OK",
        //     });
        //     return false;
        // }

        swal({
            title: "Confirmation",
            text: "Are you you want to modify your nicotine shot amount?",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: "POST",
                    url: vmhLocal.ajaxUrl,
                    data: {
                        action: "vmh_update_nicotineshot",
                        nicotineShot,
                        saltType,
                        typeCount,
                    },
                    beforeSend: () => {
                        target.css({
                            "pointer-events": "none",
                        });
                    },
                    success: function (response) {
                        if (!response) return;

                        if (response.data.response == "invalid") {
                            swal({
                                title: "Error",
                                text: response.data.message,
                                button: "OK",
                            });
                            return;
                        }

                        if (response.data.response == "success") {
                            location.reload();
                        }
                    },
                    complete: () => {
                        target.css({
                            "pointer-events": "auto",
                        });
                    },
                    error: (err) => {
                        target.css({
                            "pointer-events": "auto",
                        });
                        swal({
                            title: "Error",
                            text: "Something went wrong. Try again or contact admin",
                            button: "OK",
                        });
                        return false;
                    },
                });
            }
        });
    }

    // Controll the shot value in by 10
    function controllCartNicotineShotInput(e) {
        let target = $(e.currentTarget);
        let value = Number(target.val());

        let prevValue = Number(target.parent().find(".cart_nicotine_shot_hidden_value").val());

        if (value > prevValue) {
            target.val(prevValue);
            swal({
                title: "Sorry",
                text: "You can't increase the shot value",
                button: "OK",
            });
            return false;
        }

        target.parent().find(".nicotineshot_save_btn").tooltip("show");

        // Disable the checkout button and show alert on click
        $(".vmh_checkout_btn").addClass("vmh_button disabled");

        $(".vmh_checkout_btn.vmh_button.disabled").click((e) => {
            let saltType = target.attr("data-type");
            e.preventDefault();
            swal({
                title: "Wait",
                text: `You have changed "${saltType}" shot value. 
                Please click save to update`,
                button: "OK",
            });

            $(".swal-text").html(`
                You have changed "${saltType}" shot value.
                Please click save <i class="far fa-save" style="
                font-size: 1.3rem;
                transform: translateY(2px);"></i> to update
            `);
            return false;
        });

        value = roundNumberTo10Times(value);

        target.val(value);

        let shotPrice = ((value / 10) * Number(vmhLocal.nicotineShotPer10mlPrice)).toFixed(2);
        target.parents(".vmh_nicotine_shot_card").find(".calculatedPrice").html(shotPrice);
    }

    // Hide the nicotine amount attribute option if ingredients percentage is greater than 16.7
    function restrictNicotineAmountValue() {
        let ingredientsPercentageValues = getIngredientsPercentageValues();

        if (ingredientsPercentageValues.length < 1) return;

        let totalPercentage = ingredientsPercentageValues.reduce(
            (prevValue, currentValue) => prevValue + currentValue
        );

        let hiddenValue = vmhLocal.hideNicotineValue;

        if (totalPercentage > 16.7) {
            $("#pa_vmh_nicotine_amount")
                .find(`option:contains("${hiddenValue}")`)
                .attr("disabled", true)
                .hide();
        }

        let attributes = vmhLocal.vmhProductAttributes;

        if (!attributes) return;

        for (const key in attributes) {
            if (Object.hasOwnProperty.call(attributes, key)) {
                $(document).on("change", `#pa_${key}`, (e) => {
                    ingredientsPercentageValues = getIngredientsPercentageValues();

                    totalPercentage = ingredientsPercentageValues.reduce(
                        (prevValue, currentValue) => prevValue + currentValue
                    );

                    if (totalPercentage > 16.7) {
                        $("#pa_vmh_nicotine_amount")
                            .find(`option:contains("${hiddenValue}")`)
                            .attr("disabled", true)
                            .hide();
                    } else {
                        $("#pa_vmh_nicotine_amount")
                            .find(`option:contains("${hiddenValue}")`)
                            .attr("disabled", false)
                            .show();
                    }
                });
            }
        }
    }

    // Show the ingredients total price
    function showIngredientPrice(e) {
        let target = $(e.currentTarget);
        let bottleSize = target.val();
        let productID = $("#created_recipe_id").val();

        if (!bottleSize || !productID) {
            return;
        }

        try {
            $.ajax({
                type: "POST",
                url: vmhLocal.ajaxUrl,
                data: {
                    action: "vmh_get_ingredients_price",
                    bottleSize,
                    productID,
                },
                beforeSend: () => {
                    $(".vmh_single_add_to_cart, .save_update_add_to_cart_btn").attr("disabled", true);
                    $(".save_update_add_to_cart_btn").addClass("disabled");
                    $(".save_update_add_to_cart_btn, .vmh_single_add_to_cart").tooltip("dispose");
                    $(".save_update_add_to_cart_btn, .vmh_single_add_to_cart").tooltip("hide");
                },
                success: function (response) {
                    if (!response) return;

                    if (response.data.response == "success") {
                        $(".price_box .price").html(response.data.price);
                        $(".price_box").addClass("active");

                        if (!response.data.ingredientsAvailability || !response.data.price) {
                            $(".vmh_single_add_to_cart, .save_update_add_to_cart_btn").attr("disabled", true);
                            $(".save_update_add_to_cart_btn").addClass("disabled");

                            $(".save_update_add_to_cart_btn, .vmh_single_add_to_cart").tooltip("dispose");
                            $(".save_update_add_to_cart_btn, .vmh_single_add_to_cart").tooltip("show");
                        } else {
                            $(".save_update_add_to_cart_btn, .vmh_single_add_to_cart").tooltip("dispose");
                            $(".save_update_add_to_cart_btn, .vmh_single_add_to_cart").tooltip("hide");
                        }
                    } else {
                        $(".vmh_single_add_to_cart, .save_update_add_to_cart_btn").attr("disabled", true);
                        $(".save_update_add_to_cart_btn").addClass("disabled");

                        $(".save_update_add_to_cart_btn, .vmh_single_add_to_cart").tooltip("dispose");
                        $(".save_update_add_to_cart_btn, .vmh_single_add_to_cart").tooltip("show");

                        swal({
                            title: "Error",
                            text: response.data.message,
                            button: "OK",
                        });
                        return;
                    }
                },
                complete: (response) => {
                    if (
                        !response.responseJSON.data.ingredientsAvailability ||
                        !response.responseJSON.data.price
                    ) {
                        $(".vmh_single_add_to_cart, .save_update_add_to_cart_btn").attr("disabled", true);
                        $(".save_update_add_to_cart_btn").addClass("disabled");
                    } else {
                        $(".vmh_single_add_to_cart, .save_update_add_to_cart_btn").attr("disabled", false);
                        $(".save_update_add_to_cart_btn").removeClass("disabled");
                    }
                },
                error: (err) => {
                    $(".vmh_single_add_to_cart, .save_update_add_to_cart_btn").attr("disabled", false);
                    $(".save_update_add_to_cart_btn").removeClass("disabled");

                    swal({
                        title: "Server Error",
                        text: JSON.parse(err.responseText).data.message,
                        button: "OK",
                    });
                    return;
                },
            });
        } catch (error) {
            console.error(error);
            swal({
                title: "Server Error",
                text: "Something went wrong. Try again or contact admin",
                button: "OK",
            });
            return false;
        }
    }

    // calculate the ingredients price value either on change of select or initial loading
    function calculateIngredientsPrice() {
        let bottleSize = $("#pa_vmh_bottle_size").val();
        let productID = $("#created_recipe_id").val();

        if (!bottleSize || !productID) {
            return;
        }

        try {
            $.ajax({
                type: "POST",
                url: vmhLocal.ajaxUrl,
                data: {
                    action: "vmh_get_ingredients_price",
                    bottleSize,
                    productID,
                },
                success: function (response) {
                    if (!response) return;

                    if (response.data.response == "success") {
                        $(".price_box .price").html(response.data.price);
                        $(".price_box").addClass("active");
                    } else {
                        swal({
                            title: "Error",
                            text: response.data.message,
                            button: "OK",
                        });
                        return;
                    }
                },
                error: (err) => {
                    console.log(err);
                    swal({
                        title: "Server Error",
                        text: JSON.parse(err.responseText).data.message,
                        button: "OK",
                    });
                    return;
                },
            });
        } catch (error) {
            console.error(error);
        }
    }
});
