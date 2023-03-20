var callAfterAcceptHoliday;

$(function () {

    $("body").on("keyup", ".add_new_variant_value", function (e) {

        if (e.which != "13") {
            return false;
        }

        var parentDiv = $(this).parents(".variation_type_parent_div");
        var clonedDiv = $(".original_variant_value_div", parentDiv).first().clone();

        clonedDiv.removeClass("hide");
        clonedDiv.addClass("show");
        $(".variant_title", clonedDiv).text($(this).val());
        $(".new_variant_values", clonedDiv).attr("value", $(this).val());

        $(".variant_values_parent_div", parentDiv).append(clonedDiv);


        $(this).val("");

    });

    $("body").on("click", ".remove_variant_value", function () {

        var thisElement = $(this);

        Swal.fire({
            html: "<b>Are You Sure?</b><br><br>",
            showDenyButton: true,
            confirmButtonText: "Yes",
            denyButtonText: `No`,
            icon: "info",
        }).then((result) => {
            if (result.isConfirmed) {
                thisElement.parents(".original_variant_value_div").remove();
            }
        });

    });

    $("body").on("click", ".add_new_variation_type", function () {

        var clonedDiv = $(".variation_type_parent_div.new_one").first().clone();

        $(".original_variant_value_div.show", clonedDiv).remove();
        $(".new_variant_name", clonedDiv).val("");
        $(".add_new_variant_value", clonedDiv).val("");

        var newId = $(".variation_type_parent_div.new_one").length;
        $(".new_variant_values", clonedDiv).attr("name",`new_variant_values_${newId}[]`);

        $(".variation_types_section").append(clonedDiv);

    });



    $("body").on("click", ".generate_barcode", function () {

        var fieldName        = $(this).data("field_name");
        var parentElement    = $(this).parents(".form-group").first();
        var selfEditElement  = $(`span.general_self_edit[data-field_name='${fieldName}']`,parentElement).first();

        selfEditElement.click();

        var barcode = Math.floor(11111111 + Math.random() * 99999999);
        $(`input[data-field_name='${fieldName}']`, parentElement).val(barcode);
        var e = jQuery.Event("keyup");
        e.which = 13; // # Some key code value for Enter

        $(`input[data-field_name='${fieldName}']`, parentElement).trigger(e);

    });



});
