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

    // Controll the cart nicotine shot input
    let cartNicotineShotInput = $(".cart_nicotine_shot_value");

    let recipeSaveBtn = $(".vmh_save_recipe_btn");

    let bottleSizeSelect = $("#pa_vmh_bottle_size");

    let addToCartSaveUpdateBtn = $(".save_update_add_to_cart_btn");

    let discardButton = $(".vmh_discard_recipe");

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
        calculateIngredientsPrice();

        nicotineShotTrigger.change(calculateNicotineShot);

        // Controll the cart nicotine shot value input by restricting the increase feature
        cartNicotineShotInput.change(controllCartNicotineShotInput);

        nicotineshotCartUpdateBtn.click(updateNicotineshotValue);

        // Show the attributes options when clicked on next button
        recipeSaveBtn.click(createRecipe);

        // Intitalize jquery custom date picker
        customDatePicker();

        bottleSizeSelect.on("change", showIngredientPrice);

        addToCartSaveUpdateBtn.on("click", addProductToCart);

        discardButton.on("click", discardRecipe);
    }

    function discardRecipe(e) {
        e.preventDefault();
        let target = $(e.currentTarget);

        let productID = $("#created_recipe_id").val();

        if (!productID || productID == "") return showRecipePopup(`Product ID is missing`, "invalid");

        if (!confirm("Are you sure that you want to discard this recipe?")) {
            return;
        }

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
            },
            success: (res) => {
                if (!res) return;

                let response = JSON.parse(res);

                if (response.response == "success") {
                    location.reload();
                } else {
                    showRecipePopup(response.message, "invalid");
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

            let tags = $(".input_container .tag_input:not(.duplicate_tag) .vmh_tag_input:not(.predefied_tag_input)");

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

        if ($(".login_input #email").val() == "" || $(".login_input #password").val() == "") return alert("One or more field is empty");

        $.ajax({
            url: vmhLocal.ajaxUrl,
            data: {
                formData,
                action: "vmh_login_action",
            },
            method: "post",
            success: (res) => {
                if (!res) return;

                let response = JSON.parse(res);
                if (response.response == "success") {
                    window.location.href = vmhLocal.siteUrl;
                } else {
                    makeAlert("Error", response.message);
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
            makeAlert("Age Restriction", "You must be over 18 to create an account");
            return false;
        }

        if (privacyPolicyCheckbox.prop("checked") == false) {
            makeAlert("Terms & Conditions", "Please agree to our terms & conditions");
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
        let productName = $("#vmh_recipe_name").val();
        let variationsValue = getAttributeOptionsValue();
        let recipeAction = "add-to-cart";
        let nicotineShotValue = $("#nicotine_shot_value").val();

        let requiredFields = showAlertOnRequiredFields(productName, variationsValue, recipeAction);

        // If there are nay missing required field than show the alert popup and stop executing
        if (requiredFields === false) {
            return false;
        }

        if (!productID) return showRecipePopup("Product ID is missing", "invalid");

        if (!variationsValue) return showRecipePopup("Variation are missing", "invalid");

        if (!nicotineShotValue) return showRecipePopup("Nicotine shot value is missing", "invalid");

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
                    showRecipePopup(response.data.message, response.data.response);
                }

                if (response.data.response == "success") {
                    let cartQuantity = $(".vmh_cart_quantity");

                    let updatedQuatity = parseInt(cartQuantity.text()) + 1;

                    cartQuantity.html(updatedQuatity);

                    let responseMsgContainer = $(".vmh-response-msg");

                    responseMsgContainer.html(`
                        <h3>Product Added</h3>
                        <h4>Your product added to cart. Go to your cart of continue shopping</h4>
                        <div class="create_recipe_add_to_cart_popup">
                            <a class="vmh_checkout_btn" href="${vmhLocal.siteUrl}/cart">Go to cart</a>
                            <a class="vmh_checkout_btn" href="${vmhLocal.siteUrl}">Continue shopping</a>
                        </div>
                    `);

                    $(".vmh-login-btn").hide();
                    $(".create_acc_pages").show();
                    $("body").addClass("popup_overly");
                }
            },

            complete: () => {
                $(e.currentTarget).attr("disabled", false);
                $(e.currentTarget).removeClass("disabled");
            },

            error: (err) => {
                showRecipePopup("Someting went wrong try again", "invalid");
            },
        });
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
                target.css({
                    "pointer-events": "none",
                });
            },

            success: (res) => {
                if (!res) return;

                let response = JSON.parse(res);

                if (response.response == "invalid") {
                    makeAlert("Error", response.message);
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
                makeAlert("Error", "Someting went wrong try again");
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
                if (!res) return;

                let response = JSON.parse(res);

                if (response.response == "invalid") {
                    makeAlert("Error", response.message);
                }
                if (response.response == "success") {
                    // if the page is not from my favorite page
                    if (!target.hasClass("vmh_fav_close")) {
                        if (response.status == "added") {
                            heartBtn.removeClass("vmh_heart_grey");
                            target.attr("data-action", "unfavorite");
                            makeAlert("Made favorite", "Product added to favorite list");
                        }
                        if (response.status == "removed") {
                            heartBtn.addClass("vmh_heart_grey");
                            target.attr("data-action", "favorite");
                            makeAlert("Removed", "Product removed to favorite list");
                        }
                    }
                }
            },
            error: (err) => {
                makeAlert("Error", "Someting went wrong try again");
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

        if (recipeAction == "update-recepie") {
            if (!proudctID) {
                return showRecipePopup("Product name is required", "invalid");
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
            },
            beforeSend: () => {
                $(e.currentTarget).attr("disabled", true);
                $(e.currentTarget).addClass("disabled");
            },

            success: (res) => {
                if (!res) return;

                let response = JSON.parse(res);

                if (response.response == "invalid") {
                    showRecipePopup(response.message, "invalid");
                }

                if (response.response == "success") {
                    showRecipePopup(response.message, response.response);

                    /* Show the next part of recipe creation */
                    $(".create_recipe_option").show();
                    $(".recepes_btn").css({
                        display: "flex",
                    });
                    restrictNicotineAmountValue();
                    target.hide();
                    /* End of the next part of recipe creation */

                    // Disable the product ingredients section
                    $(".ingredients_wrapper").css({
                        "pointer-events": "none",
                        opacity: 0.5,
                    });
                    $(".cut_selectbox, .add_ingredients_icon").remove();

                    $("#created_recipe_id").val(response.id);
                    $(".save_update_add_to_cart_btn").attr("data-id", response.id);
                }
            },

            complete: () => {
                $(e.currentTarget).attr("disabled", false);
                $(e.currentTarget).removeClass("disabled");
            },

            error: (err) => {
                $(e.currentTarget).attr("disabled", false);
                $(e.currentTarget).removeClass("disabled");
                showRecipePopup("Product name is required", "invalid");
                console.error(err);
            },
        });
    }

    function showAlertOnRequiredFields(productName, optionsValue, recipeAction) {
        let productAttributes = vmhLocal.vmhProductAttributes;
        let attributesLength = Object.keys(productAttributes).length;

        // Check if the prodcut name exits
        if (!productName) {
            return showRecipePopup("Product name is required", "invalid");
        }

        if (showAlertOnEmptyIngredients() === false) {
            return false;
        }

        let totalPercentage = calculatePercentage();

        if (totalPercentage >= 30) {
            return showRecipePopup("Ingredients amount is exceeded. Please keep it in 30%", "invalid");
        }

        if (optionsValue.length !== attributesLength && recipeAction != "save-recepie") {
            return showRecipePopup("All options needs to filled", "invalid");
        }

        let stockAvailablity = $(".woocommerce-variation-availability");

        if (stockAvailablity.find(".out-of-stock").length > 0) {
            return showRecipePopup("Product variation is out of stock", "invalid");
        }
    }

    // Show a alert value if all ingredients percentage value is not filled
    function showAlertOnEmptyIngredients() {
        let ingredients = $("select.product_ingredients:not(#product_ingredients_0)");

        let recipePopup = $(".vmh_create_recipe_popup");

        if (!ingredients.val() || ingredients.val() == "") {
            return showRecipePopup("Please select 1 or more ingredients", "invalid");
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
                }
            });

            if (breakLoop) {
                recipePopup.find(".vmh_checkbox_image").css({
                    display: "none",
                });
                recipePopup.find(".vmh_checkbox_image_warning").css({
                    display: "block",
                });

                return showRecipePopup("All selected ingredients percentage value needs to be filled", "invalid");
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
            showRecipePopup(`Ingredients amount is limited to 30%`, "invalid");
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

        let total = ingredientsPercentage.reduce((prevValue, currentValue) => prevValue + currentValue);

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
        let tags = $(".input_container .tag_input:not(.duplicate_tag) .vmh_tag_input:not(.predefied_tag_input)");

        if (tags.length) {
            let innerHTML = "";

            $.each(tags, function (i, tag) {
                let tagValue = $(tag).val();
                if (tagValue) {
                    innerHTML += `<a href="#" class="tag_name" data-target="tag_name_${i + 3}">${tagValue}</a>`;
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

        if (currentTarget.find("input[type=email]").val() == "") return alert("Email is required to subscribe");

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

        if (!productID || productID == "") return makeAlert("Error", `Product ID is missing`);

        let item = target.parents(".single_recopies_items");
        let itemTitle = item.find("h6").text();

        if (!confirm("Are you sure that you want to delete this product. ? If you do so you won't get any commision for this product from now on")) {
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
                    makeAlert("Success", `${itemTitle} is removed from your list`);
                    item.remove();
                } else {
                    makeAlert("Error", response.message);
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

        let cartKey = target.attr("data-key");
        let nicotineShot = parseInt(target.siblings(".cart_nicotine_shot_value").val());

        if (nicotineShot < 0) {
            makeAlert("Warning", "Nicotine shot value can not be a negative number");
            return;
        }

        if (!cartKey) {
            makeAlert("Error", "Invalid cart key");
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
            beforeSend: () => {
                target.css({
                    "pointer-events": "none",
                });
            },
            success: function (res) {
                if (!res) return;

                let response = JSON.parse(res);

                if (response.response == "invalid") {
                    makeAlert("Error", response.message);
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
                console.error(err);
                target.css({
                    "pointer-events": "auto",
                });
                makeAlert("Error", "Something went wrong. Try again");
            },
        });
    }

    // Controll the shot value in by 10
    function controllCartNicotineShotInput(e) {
        let target = $(e.currentTarget);
        let value = target.val();

        let prevValue = target.parent().find(".cart_nicotine_shot_hidden_value").val();

        if (value > prevValue) {
            makeAlert("Warning", "You can't increase the shot value");
            target.val(prevValue);
            return;
        }

        // Disable the checkout button and show alert on click
        $(".vmh_checkout_btn").addClass("vmh_button disabled");

        $(".vmh_checkout_btn.vmh_button.disabled").click((e) => {
            e.preventDefault();
            makeAlert("Warning", "You have changed nicotine shot value. Please click save to update");
        });

        value = roundNumberTo10Times(value);
        target.val(value);
    }

    // Hide the nicotine amount attribute option if ingredients percentage is greater than 16.7
    function restrictNicotineAmountValue() {
        let ingredientsPercentageValues = getIngredientsPercentageValues();

        let totalPercentage = ingredientsPercentageValues.reduce((prevValue, currentValue) => prevValue + currentValue);

        let hiddenValue = vmhLocal.hideNicotineValue;

        if (totalPercentage > 16.7) {
            $("#pa_vmh_nicotine_amount").find(`option:contains("${hiddenValue}")`).attr("disabled", true).hide();
        }

        let attributes = vmhLocal.vmhProductAttributes;

        if (!attributes) return;

        for (const key in attributes) {
            if (Object.hasOwnProperty.call(attributes, key)) {
                $(document).on("change", `#pa_${key}`, (e) => {
                    ingredientsPercentageValues = getIngredientsPercentageValues();

                    totalPercentage = ingredientsPercentageValues.reduce((prevValue, currentValue) => prevValue + currentValue);

                    if (totalPercentage > 16.7) {
                        $("#pa_vmh_nicotine_amount").find(`option:contains("${hiddenValue}")`).attr("disabled", true).hide();
                    } else {
                        $("#pa_vmh_nicotine_amount").find(`option:contains("${hiddenValue}")`).attr("disabled", false).show();
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
                success: function (response) {
                    if (!response) return;

                    if (response.data.response == "success") {
                        $(".price_box .price").html(response.data.price);
                        $(".price_box").addClass("active");
                    } else {
                        showRecipePopup(response.data.message, "invalid");
                    }
                },
                error: (err) => {
                    console.log(err);
                    showRecipePopup(JSON.parse(err.responseText).data.message, "invalid");
                },
            });
        } catch (error) {
            console.error(error);
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
                        makeAlert("Error", response.data.message);
                    }
                },
                error: (err) => {
                    console.log(err);
                    makeAlert("Error", JSON.parse(err.responseText).data.message);
                },
            });
        } catch (error) {
            console.error(error);
        }
    }

    // Show recipe popup in recipe create/update page
    function showRecipePopup(message, type) {
        let recipePopup = $(".vmh_create_recipe_popup");

        if (type == "success") {
            recipePopup.find(".vmh_checkbox_image_warning").css({
                display: "none",
            });

            recipePopup.find(".vmh_checkbox_image").css({
                display: "block",
            });
        } else {
            recipePopup.find(".vmh_checkbox_image").css({
                display: "none",
            });

            recipePopup.find(".vmh_checkbox_image_warning").css({
                display: "block",
            });
        }

        recipePopup.find(".vmh_alert_text").text(message);

        $(".save_recieved_hde").show();
        $("body").addClass("popup_overly");
        return false;
    }
});
