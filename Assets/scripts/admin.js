var $ = jQuery.noConflict();

(function () {
    let postWrap = $(".wrap");

    events();

    function events() {
        insertUploadButton();
        $(document).on("click", ".vmh_upload_ingredients", openMediaUploader);
    }

    // insert upload button after the add new post button
    function insertUploadButton() {
        postWrap
            .find(".page-title-action")
            .after(
                '<a href="" class="page-title-action vmh_upload_ingredients">Upload Ingredients</a>'
            );
    }

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
})();
