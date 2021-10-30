jQuery(document).ready(function ($) {
    let darkModeSwitch = $(".dark-mode-swtich");
    let vmhLoginForm = $("#vmh-login-form");
    let userCreateForm = $("#vmh-signup-form");
    let privacyPolicyCheckbox = $("#privacy_policy");
    let addToCartBtn = $(".vmh_add_to_cart_btn");
    let cartRemoveBtn = $(".cart_remove1");
    let favoriteBtn = $(".vmh_favorite");
    let createRecipeBtn = $(".vmh_save_recipe_btn");
    let tagInput = $(".vmh_tag_input");

    // Toggle the dark mode in webstie frontend
    const toogleDarkMode = (e) => {
        e.preventDefault();
    };

    // Handle login process
    const loginHandler = (e) => {
        e.preventDefault();
        let formData = $(e.currentTarget).serialize();
        $.ajax({
            url: vmhLocal.ajaxUrl,
            data: {
                formData,
                action: "vmh_login_action",
            },
            method: "post",
            success: (res) => {
                console.log(res);

                if (!res) return;

                let response = JSON.parse(res);
                if (response.response == "success") {
                    window.location.href = vmhLocal.siteUrl;
                } else {
                    alert(response.message);
                }
            },
            error: (err) => {
                $(".oe-warning").removeClass("oe-success");
                $(".oe-warning").hide().slideDown().html("Something went wrong");
                $(".reg-btn").attr("disabled", false);
            },
        });
    };

    // Handle the user creation form
    function handleUserCreate(e) {
        e.preventDefault();
        let responseMsgContainer = $(".vmh-response-msg");
        let currentTarget = $(e.currentTarget);
        let formData = currentTarget.serialize();

        // check if user is over 18 years old
        if (isAgeOverEighteen($("#date_of_birth").val()) === "no") {
            makeAlert("Age Restriction", "You age have to over 18");
            return false;
        }

        if (privacyPolicyCheckbox.prop("checked") == false) {
            makeAlert("Terms & Conditions", "Please agree to our term & conditions");
            return false;
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
                console.log(res);

                if (!res) return;

                let response = JSON.parse(res);

                if (response.response == "invalid") {
                    makeAlert("Error Occurred", response.message);
                    $(".vmh-login-btn").hide();
                }
                if (response.response == "success") {
                    makeAlert("Thank you", response.message);
                    $(".vmh-login-btn").hide();
                }

                $("#vmh-user-create-submit").attr("disabled", false);
            },
            error: (err) => {
                alert("Someting went wrong try again.");
            },
        });
    }

    function makeAlert(alertTitle, alertMessage) {
        let responseMsgContainer = $(".vmh-response-msg");

        responseMsgContainer.html(`
            <h3>${alertTitle}</h3>
            <h4>${alertMessage}</h4>
        `);

        $(".vmh-login-btn").hide();
        $(".create_acc_pages").show();
        $("body").addClass("popup_overly");
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

        if (!productID) return;

        $.ajax({
            url: vmhLocal.ajaxUrl,
            data: {
                productID,
                action: "vmh_add_product_to_cart",
            },
            method: "post",
            beforeSend: () => {
                $(".vmh_alert").hide();
            },
            success: (res) => {
                console.log(res);
                if (!res) return;

                let response = JSON.parse(res);

                if (response.response == "invalid") {
                    productAlert(response.message);
                }
                if (response.response == "success") {
                    productAlert(response.message);
                    let cartQuantity = $(".vmh_cart_quantity");
                    let cartTotal = $(".vmh_total_price");

                    let updatedQuatity = parseInt(cartQuantity.text()) + 1;
                    let updatedPrice = (
                        Number(cartTotal.text()) + Number(response.productPrice)
                    ).toFixed(2);

                    cartQuantity.html(updatedQuatity);
                    cartTotal.html(updatedPrice);
                }
            },
            error: (err) => {
                alert("Someting went wrong try again.");
            },
        });
    }

    function productAlert(message) {
        let alertBox = $(".vmh_alert");
        alertBox.html(`<b>${message}</b>`);
        alertBox.slideDown(500);
    }

    // Remove the product from cart
    function removeProductFromCart(e) {
        e.preventDefault();

        let target = $(e.currentTarget);
        let productID = target.attr("data-id");

        if (!productID) return;

        $.ajax({
            url: vmhLocal.ajaxUrl,
            data: {
                productID,
                action: "vmh_remove_product_from_cart",
            },
            method: "post",
            beforeSend: () => {
                $(".vmh_alert").hide();
            },
            success: (res) => {
                console.log(res);
                if (!res) return;

                let response = JSON.parse(res);

                if (response.response == "invalid") {
                    productAlert(response.message);
                }
                if (response.response == "success") {
                    productAlert(response.message);
                    let cartQuantity = $(".vmh_cart_quantity");
                    let cartTotal = $(".vmh_total_price");

                    let updatedQuatity =
                        parseInt(cartQuantity.text()) - Number(parseInt(response.productQuantity));
                    let updatedPrice = (
                        Number(cartTotal.text()) - Number(response.productPrice)
                    ).toFixed(2);

                    cartQuantity.html(updatedQuatity);
                    cartTotal.html(updatedPrice);
                    $(".vmh_bottom_cart_total").html(updatedPrice);

                    target
                        .parents(".single_recopies_items.cart_single_item")
                        .fadeOut(500)
                        .delay(400)
                        .remove();
                }
            },
            error: (err) => {
                alert("Someting went wrong try again.");
            },
        });
    }

    function moveAroundCheckoutForm(e) {
        e.preventDefault();
        let target = $(e.currentTarget);
        let parantContainer = target.parents(".vmh_checkout_form");
        let nextCheckoutTarget = target.attr("data-target");
        parantContainer.removeClass("checkout_form_active");
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
            success: (res) => {
                console.log(res);
                if (!res) return;

                let response = JSON.parse(res);

                if (response.response == "invalid") {
                    productAlert(response.message);
                }
                if (response.response == "success") {
                    // if the page is not from my favorite page
                    if (!target.hasClass("vmh_fav_close")) {
                        if (response.status == "added") {
                            heartBtn.removeClass("vmh_heart_grey");
                            target.attr("data-action", "unfavorite");
                            productAlert("Product added to favorite list");
                        }
                        if (response.status == "removed") {
                            heartBtn.addClass("vmh_heart_grey");
                            target.attr("data-action", "favorite");
                            productAlert("Product removed to favorite list");
                        }
                    } else {
                        target.parents(".single_recopies_items").fadeOut(500).delay(400).remove();
                    }
                }
            },
            error: (err) => {
                alert("Someting went wrong try again.");
            },
        });
    }

    // Create a simple product as new recipe
    function createRecipe(e) {
        e.preventDefault();
        let productName = $("#vmh_recipe_name").val();
        let ingredientsValues = getIngredientsValues();
        let recipeNote = $(".vmh_recipe_create_note").val();
        let firstTagValue = $("input[name=vmh_first_tag]").val();
        let secondTagValue = $("input[name=vmh_second_tag]").val();
        let tagValues = [firstTagValue, secondTagValue];
        let optionsValue = getOptionsValue();

        let requiredFields = showAlertOnRequiredFields(productName, optionsValue);
        if (requiredFields === false) {
            return false;
        }
        let productPrice = parseInt(
            $(".woocommerce-variation-price bdi")?.text()?.replace(vmhLocal.currencySymbol, "")
        );

        $.ajax({
            type: "post",
            url: vmhLocal.ajaxUrl,
            data: {
                productName,
                ingredientsValues,
                recipeNote,
                tagValues,
                optionsValue,
                productPrice,
                action: "vmh_create_product",
            },
            success: (res) => {
                console.log(res);
                if (!res) return;

                let response = JSON.parse(res);

                if (response.response == "invalid") {
                    productAlert(response.message);
                }

                if (response.response == "success") {
                    // if the page is not from my favorite page
                    let recipePopup = $(".vmh_create_recipe_popup");

                    recipePopup.find(".vmh_checkbox_image").css({
                        visibility: "visible",
                    });

                    recipePopup
                        .find(".vmh_alert_text")
                        .text("Product is created. Please wait for admin to approve");

                    $(".save_recieved_hde").show();
                    $("body").addClass("popup_overly");

                    setTimeout(() => {
                        window.location.href = vmhLocal.siteUrl;
                    }, 3000);
                }
            },
            error: (err) => {
                alert("Someting went wrong try again.");
                console.error(err);
            },
        });
    }

    function showAlertOnRequiredFields(productName, optionsValue) {
        let productAttributes = vmhLocal.vmhProductAttributes;
        let attributesLength = Object.keys(productAttributes).length;

        let recipePopup = $(".vmh_create_recipe_popup");
        if (!productName) {
            recipePopup.find(".vmh_checkbox_image").css({
                visibility: "hidden",
            });

            recipePopup.find(".vmh_alert_text").text("Product name is required");

            $(".save_recieved_hde").show();
            $("body").addClass("popup_overly");
            return false;
        }
        if (optionsValue.length !== attributesLength) {
            recipePopup.find(".vmh_checkbox_image").css({
                visibility: "hidden",
            });

            recipePopup.find(".vmh_alert_text").text("All options needs to filled.");

            $(".save_recieved_hde").show();
            $("body").addClass("popup_overly");
            return false;
        }
    }

    // Get the ingredients value from create recipe
    function getIngredientsValues() {
        let ingredientsValues = [];

        $.each($(".vmh_create_recipe_ingredients"), function (indexInArray, valueOfElement) {
            if ($(valueOfElement).val()) {
                ingredientsValues.push($(valueOfElement).val());
            }
        });

        return ingredientsValues;
    }

    // Change the tag name on typing of tag name
    function changeTagName(e) {
        let target = $(e.currentTarget);
        let input = $(`input[name=${target.attr("data-target")}]`);
        let spanTag = $(`#${target.attr("data-target")}`);
        input.val(target.val());
        spanTag.text(target.val());
    }

    // Get options value from selectbox
    function getOptionsValue() {
        let optionsValue = [];

        let productAttributes = vmhLocal.vmhProductAttributes;

        if (!productAttributes) return [];

        for (const key in productAttributes) {
            if (Object.hasOwnProperty.call(productAttributes, key)) {
                let selectedText = $(`#pa_${key} option:selected`).text();
                if (selectedText != "Choose") {
                    optionsValue.push([`${key} | ${selectedText}`]);
                }
            }
        }

        return optionsValue;
    }

    function hideModal() {
        $("#vmh_search_modal").modal("hide");
    }

    const events = () => {
        darkModeSwitch.on("click", toogleDarkMode);
        vmhLoginForm.on("submit", loginHandler);
        userCreateForm.on("submit", handleUserCreate);
        addToCartBtn.on("click", addProductToCart);
        cartRemoveBtn.on("click", removeProductFromCart);
        $(document).on(
            "click",
            ".vmh_previous_btn, .vmh_checkout_next_btn",
            moveAroundCheckoutForm
        );
        clearParameter();
        favoriteBtn.on("click", toggleFavorite);
        createRecipeBtn.on("click", createRecipe);
        tagInput.on("input", changeTagName);
        $(document).on("click", ".js-dgwt-wcas-enable-mobile-form", hideModal);
    };

    events();
});
