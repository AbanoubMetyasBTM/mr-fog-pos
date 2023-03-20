var showContentFieldsForAdmin;

$(function () {


    $("body").on("contextmenu", "img[src*='show_admin_content=yes']", function (event) {


        const urlParams = new URLSearchParams($(this).attr("src"));

        if (urlParams.get('site_content_url') == null) {
            return false;
        }

        if (event.ctrlKey) {

            copyToClipboard(urlParams.get('site_content_url'));
            show_flash_message("info", "Link Copied To Clipboard");

            return false;
        }

        if (event.altKey) {
            return false;
        }

        window.open(urlParams.get('site_content_url'), '_blank');

        return false;

    });

    $("body").on("contextmenu", "[style*='show_admin_content=yes']", function (event) {

        var style    = $(this).attr("style");
        var url      = style.match(/background-btm: url\(["']?([^"']*)["']?\)/)[0];
        var imageUrl = url.replace("background-btm: url('", "");

        var urlParams = new URLSearchParams(url);

        if (urlParams.get('site_content_url') == null) {
            return false;
        }

        urlParams = urlParams.get('site_content_url');
        urlParams = urlParams.replace("')", "");


        if (event.ctrlKey) {

            copyToClipboard(urlParams);
            show_flash_message("info", "Link Copied To Clipboard");

            return false;
        }

        if (event.altKey) {

            getImageWidthAndHeight(imageUrl);

            return false;
        }

        window.open(urlParams, '_blank');


        return false;

    });

    $("body").on("contextmenu", "img", function (event) {

        if ($(".show_admin_content").val() == "yes" && event.altKey) {
            getImageWidthAndHeight($(this).attr("src"),$(this));
            return false;
        }

    });

    $("body").on("contextmenu", ".admin_edit_content", function (event) {

        if (event.ctrlKey) {

            copyToClipboard($(this).attr("href"));
            show_flash_message("info", "Link Copied To Clipboard");

            return false;
        }

        window.open($(this).attr("href"), '_blank');

        return false;

    });

    showContentFieldsForAdmin = function () {

        if ($("img[src*='show_admin_content=yes']").length > 0) {

            $("img[src*='show_admin_content=yes']").each(function () {

                $(this).css('border', '2px solid #487dfa');

            });

        }

        if ($("[style*='show_admin_content=yes']").length > 0) {

            $("[style*='show_admin_content=yes']").each(function () {

                $(this).addClass("show_admin_content_bg");

            });

        }

    };
    addToCallAtLoadArr("showContentFieldsForAdmin");


});
