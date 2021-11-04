var $ = jQuery.noConflict();

(function () {
    let postWrap = $(".wrap");

    events();

    function events() {
        if (getSlugParameter("post_type") == "ingredient") {
            insertUploadButton();
            $(document).on("click", ".vmh_upload_ingredients", openMediaUploader);
        }
        initializeSelectBox();
    }

    // insert upload button after the add new post button
    function insertUploadButton() {
        postWrap
            .find(".page-title-action")
            .after(
                '<a href="" class="page-title-action vmh_upload_ingredients">Upload Ingredients</a>'
            );
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

    // Initialize wordpres media uploader to upload products
    function openMediaUploader(e) {
        e.preventDefault();

        let fileFrame = (wp.media.frames.fileFrame = wp.media({
            multiple: false,
            title: "Upload Ingredients CSV",
        }));

        fileFrame.open();

        fileFrame.on("select", () => {
            getMediaUploaderValue(fileFrame);
        });
    }

    function getMediaUploaderValue(fileFrame) {
        let attachment = fileFrame.state().get("selection").first();

        let payload = {
            attachmentID: attachment.id,
            uploadDate: attachment.attributes.dateFormatted,
            uploaderName: attachment.attributes.authorName,
            attachmentFilename: attachment.attributes.filename,
            attachmentURL: attachment.attributes.url,
            action: "vmh_upload_ingredients",
        };

        uploadProducts(payload);
    }

    function uploadProducts(payload) {
        $.ajax({
            type: "POST",
            url: vmhLocal.ajaxUrl,
            data: payload,
            success: (response) => {
                console.log(response);

                let res = JSON.parse(response);

                if (res.response == "success") {
                    alert(res.message);
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    alert(res.response);
                }
            },
        });
    }

    // Initialize the dropdown select in product post type
    function initializeSelectBox() {
        new SlimSelect({
            select: ".product_ingredients",
        });
        new SlimSelect({
            select: ".ingredients_percentage_values",
            showSearch: false,
            hideSelectedOption: true,
        });
    }
})();
