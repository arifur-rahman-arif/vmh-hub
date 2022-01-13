jQuery(document).ready(function ($) {
    let darkModeSwitch = $(".dark-mode-swtich");
    let vmhLoginForm = $("#vmh-login-form");
    let userCreateForm = $("#vmh-signup-form");
    let privacyPolicyCheckbox = $("#privacy_policy");
    let addToCartBtn = $(".vmh_add_to_cart_btn");
    let cartRemoveBtn = $(".cart_remove1");
    let favoriteBtn = $(".vmh_favorite");
    let createRecipeBtn = $(".vmh_save_recipe_btn");
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

    // Controll the cart nicotine shot input

    let cartNicotineShotInput = $(".cart_nicotine_shot_value");

    let recipeCreateNextBtn = $(".recipie_create_next_btn");

    events();

    function events() {
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
        nicotineShotTrigger.change(calculateNicotineShot);

        // Controll the cart nicotine shot value input by restricting the increase feature
        cartNicotineShotInput.change(controllCartNicotineShotInput);

        nicotineshotCartUpdateBtn.click(updateNicotineshotValue);

        // Show the attributes options when clicked on next button
        recipeCreateNextBtn.click(showAttributeOptions);
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

        if ($(".login_input #email").val() == "" || $(".login_input #password").val() == "")
            return alert("One or more field is empty");

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
    }

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
                    setTimeout(() => {
                        window.location.href = vmhLocal.siteUrl + "login";
                    }, 2000);
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
        alertBox.slideDown(500).delay(4000).slideUp();
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
                    let cartTotal = $(".vmh_bottom_cart_total");

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
        let target = $(e.currentTarget);
        let productName = $("#vmh_recipe_name").val();
        let ingredientsValues = getIngredientsValues();
        let recipeNote = $(".vmh_recipe_create_note").val();
        let tagValues = getTagValues();
        let optionsValue = getOptionsValue();
        let ingredientsPercentageValues = getIngredientsPercentageValues();
        let recipeAction = target.attr("data-action");
        let proudctID = parseInt(getSlugParameter("edit_product"));

        if (recipeAction == "update-recepie") {
            if (!proudctID) {
                let recipePopup = $(".vmh_create_recipe_popup");
                recipePopup.find(".vmh_checkbox_image").css({
                    display: "none",
                });
                recipePopup.find(".vmh_checkbox_image_warning").css({
                    display: "block",
                });

                recipePopup.find(".vmh_alert_text").text("Product name is required");

                $(".save_recieved_hde").show();
                $("body").addClass("popup_overly");
                return false;
            }
        }

        let requiredFields = showAlertOnRequiredFields(productName, optionsValue);

        if (requiredFields === false) {
            return false;
        }

        let productPrice = parseFloat(
            $(".woocommerce-variation-price bdi")?.text()?.replace(vmhLocal.currencySymbol, "")
        );

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
                productPrice,
                action: "vmh_create_product",
                recipeAction,
                proudctID,
            },
            beforeSend: () => {
                $(e.currentTarget).attr("disabled", true);
                $(e.currentTarget).addClass("disabled");
                $(".vmh_discard_recipe").hide();
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
                        display: "block",
                    });

                    recipePopup.find(".vmh_checkbox_image_warning").css({
                        display: "none",
                    });

                    recipePopup
                        .find(".vmh_alert_text")
                        .text(
                            recipeAction == "save-recepie"
                                ? "Product is created. Please wait for admin to approve"
                                : "Product is updated successfully."
                        );

                    $(".save_recieved_hde").show();
                    $("body").addClass("popup_overly");

                    setTimeout(() => {
                        window.location.href = vmhLocal.siteUrl;
                    }, 2000);
                }
            },

            complete: () => {
                $(e.currentTarget).attr("disabled", false);
                $(e.currentTarget).removeClass("disabled");
            },

            error: (err) => {
                $(e.currentTarget).attr("disabled", false);
                $(e.currentTarget).removeClass("disabled");
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
                display: "none",
            });
            recipePopup.find(".vmh_checkbox_image_warning").css({
                display: "block",
            });

            recipePopup.find(".vmh_alert_text").text("Product name is required");

            $(".save_recieved_hde").show();
            $("body").addClass("popup_overly");
            return false;
        }

        if (showAlertOnEmptyIngredients() === false) {
            return false;
        }

        let totalPercentage = calculatePercentage();

        if (totalPercentage >= 30) {
            recipePopup.find(".vmh_checkbox_image").css({
                display: "none",
            });
            recipePopup.find(".vmh_checkbox_image_warning").css({
                display: "block",
            });

            recipePopup
                .find(".vmh_alert_text")
                .text("Ingredients amount is exceeded. Please keep it in 30%");

            $(".save_recieved_hde").show();
            $("body").addClass("popup_overly");
            return false;
        }

        if (optionsValue.length !== attributesLength) {
            recipePopup.find(".vmh_checkbox_image").css({
                display: "none",
            });
            recipePopup.find(".vmh_checkbox_image_warning").css({
                display: "block",
            });

            recipePopup.find(".vmh_alert_text").text("All options needs to filled.");

            $(".save_recieved_hde").show();
            $("body").addClass("popup_overly");
            return false;
        }

        let stockAvailablity = $(".woocommerce-variation-availability");

        if (stockAvailablity.find(".out-of-stock").length > 0) {
            recipePopup.find(".vmh_checkbox_image").css({
                display: "none",
            });
            recipePopup.find(".vmh_checkbox_image_warning").css({
                display: "block",
            });

            recipePopup.find(".vmh_alert_text").text("Product is out of stock");

            $(".save_recieved_hde").show();
            $("body").addClass("popup_overly");
            return false;
        }

        let productPrice = parseFloat(
            $(".woocommerce-variation-price bdi")?.text()?.replace(vmhLocal.currencySymbol, "")
        );

        if (!productPrice) {
            recipePopup.find(".vmh_checkbox_image").css({
                display: "none",
            });
            recipePopup.find(".vmh_checkbox_image_warning").css({
                display: "block",
            });

            recipePopup
                .find(".vmh_alert_text")
                .text("Sorry, this product is unavailable. Please choose a different combination.");

            $(".save_recieved_hde").show();
            $("body").addClass("popup_overly");
            return false;
        }
    }

    // Show a alert value if all ingredients percentage value is not filled
    function showAlertOnEmptyIngredients() {
        let ingredients = $("select.product_ingredients:not(#product_ingredients_0)");

        let recipePopup = $(".vmh_create_recipe_popup");

        if (!ingredients.val() || ingredients.val() == "") {
            recipePopup.find(".vmh_checkbox_image").css({
                display: "none",
            });

            recipePopup.find(".vmh_checkbox_image_warning").css({
                display: "block",
            });

            recipePopup.find(".vmh_alert_text").text("Please select 1 or more ingredients");

            $(".save_recieved_hde").show();
            $("body").addClass("popup_overly");
            return false;
        }

        if (ingredients.length) {
            let breakLoop = false;

            $.each(ingredients, function (i, element) {
                let value = $(element).val();
                if (value) {
                    let inputElement = $(element).parent().find(".ingredient_percentage");
                    if (!inputElement.val()) {
                        breakLoop = true;
                        return false; // breaks
                    }
                }
            });

            if (breakLoop) {
                recipePopup.find(".vmh_checkbox_image").css({
                    display: "none",
                });
                recipePopup.find(".vmh_checkbox_image_warning").css({
                    display: "block",
                });

                recipePopup
                    .find(".vmh_alert_text")
                    .text("All selected ingredients percentage value needs to filled");

                $(".save_recieved_hde").show();
                $("body").addClass("popup_overly");
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
            if (
                $(valueOfElement).parent().find(".product_ingredients").val() &&
                $(valueOfElement).val()
            ) {
                ingredientsPercentageValues.push(Number($(valueOfElement).val()));
            }
        });

        return ingredientsPercentageValues;
    }

    // Get options value from selectbox
    function getOptionsValue() {
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
            productAlert("Ingredients amount is limited to 30%");
            setTimeout(() => {
                $(".vmh_alert").slideUp(500);
            }, 3000);

            return;
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

        let total = ingredientsPercentage.reduce(
            (prevValue, currentValue) => prevValue + currentValue
        );

        return total;
    }

    // initate ingredients select box when existing create product is active
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
                console.log(res);

                if (!res) return;

                let response = JSON.parse(res);

                if (response.response == "success") {
                    $(".subscribe_mail_popup").css({
                        display: "block",
                    });
                } else {
                    alert(response.message);
                }
            },
            error: (err) => {
                console.error(err);
            },
        });
    }

    // Delete a user created recipe if the user is a subscriber
    function deleteRecipe(e) {
        e.preventDefault();
        let target = $(e.currentTarget);

        let productID = target.attr("data-id");

        if (!productID || productID == "") return productAlert("Product ID is missing");

        let item = target.parents(".single_recopies_items");
        let itemTitle = item.find("h6").text();

        if (
            !confirm(
                "Are you sure that you want to delete this product. ? If you do so you won't get any commision for this product from now on"
            )
        ) {
            return;
        }

        $.ajax({
            url: vmhLocal.ajaxUrl,
            data: {
                productID,
                action: "vmh_remove_product_action",
            },
            method: "post",
            success: (res) => {
                console.log(res);

                if (!res) return;

                let response = JSON.parse(res);

                if (response.response == "success") {
                    productAlert(`${itemTitle} is removed from your list`);
                    item.remove();
                } else {
                    alert(response.message);
                }
            },
            error: (err) => {
                console.error(err);
            },
        });
    }

    function calculateNicotineShot() {
        let nicotineAmount = parseFloat($("#pa_vmh_nicotine_amount").val());
        let bottleSize = parseFloat($("#pa_vmh_bottle_size").val());

        if (!nicotineAmount || !bottleSize) return;

        let nictineShotValue = 0;

        nictineShotValue = Math.round(nicotineAmount / ((1.8 / 100) * bottleSize));

        nictineShotValue = roundNumberTo10Times(nictineShotValue);

        if (nictineShotValue) {
            shotAmountElement.html(nictineShotValue);
            $("#nicotine_shot_value").val(nictineShotValue);
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

        let cartKey = target.parents(".cart_single_boxs").find(".cart_key").attr("data-key");
        let nicotineShot = parseInt(target.siblings(".cart_nicotine_shot_value").val());

        if (nicotineShot < 0) {
            productAlert("Nicotine shot can not be negative value");
            return;
        }

        if (!confirm("Are you sure that you want to modify your nicotine shot amount ?")) {
            return;
        }

        $.ajax({
            type: "POST",
            url: vmhLocal.ajaxUrl,
            data: {
                action: "vmh_update_nicotineshot",
                cartKey,
                nicotineShot,
            },
            success: function (res) {
                console.log(res);
                if (!res) return;

                let response = JSON.parse(res);

                if (response.response == "invalid") {
                    productAlert(response.message);
                }

                if (response.response == "success") {
                    // productAlert(response.message);
                    location.reload();
                    // let cartTotal = response.cartTotal.toFixed(2);
                    // $(".vmh_bottom_cart_total").html(` ${cartTotal}`);
                }
            },
            error: (err) => {
                console.error(err);
                alert("Something went wrong. Try again");
            },
        });
    }

    // Controll the shot value in by 10
    function controllCartNicotineShotInput(e) {
        let target = $(e.currentTarget);
        let value = target.val();

        let prevValue = target.parent().find(".cart_nicotine_shot_hidden_value").val();

        if (value > prevValue) {
            productAlert("You can't increase the shot value");
            target.val(prevValue);
            return;
        }

        value = roundNumberTo10Times(value);
        target.val(value);
    }

    // Show the attribute options when clicked on next button
    function showAttributeOptions(e) {
        e.preventDefault();

        let target = $(e.currentTarget);

        let isOk = showAlertOnEmptyIngredients();

        if (isOk === false) {
            return false;
        }

        $(".create_recipe_option").show();
        $(".recepes_btn").css({
            display: "flex",
        });

        restrictNicotineAmountValue();

        target.hide();
    }

    // Hide the nicotine amount attribute option if ingredients percentage is greater than 16.7
    function restrictNicotineAmountValue() {
        let ingredientsPercentageValues = getIngredientsPercentageValues();

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
});
