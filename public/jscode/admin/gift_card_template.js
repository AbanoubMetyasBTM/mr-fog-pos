var onLoadSaveGiftCardTemplate;
var populateGiftCardTemplateItems;
var selectedGiftCardElement;

$(function () {

    onLoadSaveGiftCardTemplate = function () {
        setTimeout(function () {
            if ($(".gift_card_template_display .bg_img").css("width") == undefined) {
                return false;
            }

            $(".gift_card_template_position_item").draggable({
                containment: "parent",
                drag: function (event, ui) {
                    saveItemsPositions();
                }
            });

            $(".gift_card_template_display").css(
                "width",
                $(".gift_card_template_display .bg_img").css("width")
            );
        }, 500);
    };
    addToCallAtLoadArr("onLoadSaveGiftCardTemplate");

    populateGiftCardTemplateItems = function () {

        var positions = $(".template_text_positions_class").val();
        if (!isJson(positions)) {
            return false;
        }
        positions = JSON.parse(positions);

        $.each(positions, function (name, style) {
            $(".gift_card_template_position_item[data-name='" + name + "']").attr("style", style);
        })

    };
    addToCallAtLoadArr("populateGiftCardTemplateItems");


    $('body').on('change', '#template_bg_img_obj_fileid', function (e) {


        if (typeof e.target.files[0] == "undefined") {
            return false;
        }

        fileName = e.target.files[0].name;

        showImage(
            $(this)[0],
            $(".gift_card_template_display .bg_img")[0],
            function () {
                console.log($(".gift_card_template_display .bg_img").css("width"));
                $(".gift_card_template_display").css(
                    "width",
                    $(".gift_card_template_display .bg_img").css("width")
                );
            }
        );


        return false;
    });


    var saveItemsPositions = function () {

        var positions = {};

        $.each($(".gift_card_template_position_item"), function () {
            positions[$(this).data("name")] = $(this).attr("style")
        })

        $(".template_text_positions_class").val(JSON.stringify(positions));

    };

    $("body").on("click", ".gift_card_template_position_item", function () {
        selectedGiftCardElement = $(this);
        $(".gift_card_template_position_item").removeClass("selected_item");
        selectedGiftCardElement.addClass("selected_item");
    });

    $("body").on("click", ".gift_card_template_increase_font", function () {

        var fontSize = parseInt(selectedGiftCardElement.css("font-size"));
        fontSize     = fontSize + 1 + "px";
        selectedGiftCardElement.css({'font-size': fontSize});

        saveItemsPositions();

    });

    $("body").on("click", ".gift_card_template_decrease_font", function () {

        var fontSize = parseInt(selectedGiftCardElement.css("font-size"));
        fontSize     = fontSize - 1 + "px";
        selectedGiftCardElement.css({'font-size': fontSize});

        saveItemsPositions();

    });

    $("body").on("click", ".gift_card_template_increase_width", function () {

        if (selectedGiftCardElement == undefined) {
            return;
        }

        var width = parseInt(selectedGiftCardElement.css("width"));
        width     = width + 10 + "px";
        selectedGiftCardElement.css({'width': width});

        saveItemsPositions();

    });

    $("body").on("click", ".gift_card_template_decrease_width", function () {

        if (selectedGiftCardElement == undefined) {
            return;
        }

        var width = parseInt(selectedGiftCardElement.css("width"));
        width     = width - 10 + "px";
        selectedGiftCardElement.css({'width': width});

        saveItemsPositions();

    });

    $("body").on("click", ".gift_card_template_change_barcode_color", function () {

        if (selectedGiftCardElement == undefined) {
            return;
        }

        var filter = selectedGiftCardElement.css("filter");
        if (filter == "none" || filter == undefined) {
            filter = "invert(100%)"
        }
        else {
            filter = "none";
        }
        selectedGiftCardElement.css({'filter': filter});

        saveItemsPositions();

    });


    $("body").on("change", ".gift_card_template_change_color", function () {

        var color = $(this).val();
        selectedGiftCardElement.css({'color': color});

        saveItemsPositions();

    });


});
