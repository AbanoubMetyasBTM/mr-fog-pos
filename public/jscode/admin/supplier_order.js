var supplierOrderAfterSelectProduct;

$(function () {

    if($(".add_supplier_order_class .add_new_order_item").length > 0){
        $(document).keyup(function(e){
            if(e.which==187 && e.shiftKey==true){
                $(".add_new_order_item").click();
            }
        });
    }


    $("body").on("click", ".add_supplier_order_class .add_new_order_item", function () {

        var clonedDiv = $(".table .item_row").first().clone();
        var defaultInventory = $("#basic_inv_id_id").val();

        $(".btm_select_2_display_selected_text", clonedDiv).val("");
        $(".inv_id", clonedDiv).val(defaultInventory);
        $(".item_type", clonedDiv).val("item");
        $(".order_quantity", clonedDiv).val("");
        $(".item_total_cost", clonedDiv).val("");
        $(".items_cost", clonedDiv).val("");

        $(".table").append(clonedDiv);

    });


    supplierOrderAfterSelectProduct = function(selectedObj){

        var data = $(".btm_select_2_selected_obj",selectedObj).val();

        var parent = selectedObj.parents(".item_row");

        if (data !== ""){
            var obj = JSON.parse(data.toString());
            if (obj.search_q_is_box === true){
                parent.find(".item_type").val("box");
            }
        }

    };


    $("body").on("change", ".add_supplier_order_class .item_row", function () {


        var itemQty  = $(this).find(".order_quantity").val();
        var itemCost = $(this).find(".item_total_cost").val();


        if (itemQty.length !== 0 && itemCost.length !== 0){

            var totalItemsCost = (parseFloat(itemCost) * parseFloat(itemQty)).toFixed(2);

            $(this).find(".items_cost").val(totalItemsCost);

        }
        calculateOrderCostWithAdditionalFees();
    });


    $("body").on("change", ".add_supplier_order_class #additional_fees", function () {
        var orderTotalCost = 0;
        var orderCost      = 0;

        calculateOrderCostWithAdditionalFees(orderTotalCost,orderCost);
    });


    function calculateOrderCostWithAdditionalFees(orderTotalCost,orderCost) {

        var additionalFees = $("#additional_fees").val();


        $(".items_cost").each(function () {
           if ($(this).val() !== 0 && $(this).val() !== ""){
               orderCost = orderCost + parseFloat($(this).val())
           }
        });

        if (typeof orderTotalCost === 'number' && !isNaN(orderTotalCost) && orderCost !== 0){
            $(".total_items_cost").val(orderCost);
        }


        if (additionalFees !== "" && additionalFees !== 0){
           orderTotalCost = parseFloat(orderCost) + parseFloat(additionalFees);
        }
        else {
           orderTotalCost = parseFloat(orderCost);
        }

        if (typeof orderTotalCost === 'number' && !isNaN(orderTotalCost)){
           $(".total_cost").val(orderTotalCost.toFixed(2));

           $("#paid_amount").attr("max", orderTotalCost.toFixed(2))
        }

    }


    $("body").on("change", ".add_supplier_order_class #basic_inv_id_id", function () {

        $(".table").find(".inv_id").val($(this).val()).change();

    });


    $("body").on("click", ".add_supplier_order_class .return_all_item_qty", function () {

        var parent = $(this).parents(".item_will_return");

        var orderQty      = parent.find(".order_quantity").val();
        var returnedQty   = parent.find(".returned_qty").val();
        var qtyWillReturn = orderQty - returnedQty;
        console.log(orderQty, returnedQty);

        parent.find(".want_return_qty").val(qtyWillReturn).change();
    });

    $("body").on("change", ".add_supplier_order_class .want_return_qty", function () {
        returnOrder();
    });

    $("body").on("click", ".add_supplier_order_class .remove_order_item_row", function () {

        var parent = $(this).parents(".item_row");

        Swal.fire({
            html: "<b>Are You Sure?</b><br><br>",
            showDenyButton: true,
            confirmButtonText: "Yes",
            denyButtonText: `No`,
            icon: "info",
        }).then((result) => {
            if (result.isConfirmed) {

                if($(".item_row").length > 1){
                    parent.remove();
                    calculateOrderCostWithAdditionalFees()
                }
                else {
                    Swal.fire(
                        'Info',
                        `The order should contain at least one item`,
                        'info'
                    );
                }
            }
        });

    });


    $("body").on("change", ".add_supplier_order_class .item_total_cost", function () {


        var itemCost = $(this).val();
        var parentElement = $(this).parents(".item_row");
        var proSkuId = $(".btm_select_2_selected_value", parentElement).val();
        var itemType = $(".item_type", parentElement).val();

        if (itemCost !== undefined && proSkuId !== undefined && itemType !== undefined) {
            $.ajax({
                url: base_url2+"/admin/suppliers-orders/check-product-price",
                type: 'POST',
                data: {'_token': _token, 'pro_sku_id': proSkuId, "item_cost": itemCost, "item_type": itemType},
                success: function (data) {
                    if (data.length > 0) {
                        Swal.fire(
                            'Info',
                            `${data}`,
                            'info'
                        );
                    }
                }
            });
        }

    });

    $("body").on("click", ".add_supplier_order_class #return_all_items", function () {

        var parent = $(".item_will_return");


        parent.each(function () {

            var operationType = $(this).find(".item_operation_type").val();

            if (operationType === "buy"){
                var orderQty = $(this).find(".order_quantity").val();
                var returnedQty = $(this).find(".returned_qty").val();
                var qtyWillReturn = orderQty - returnedQty;

                $(this).find(".want_return_qty").val(qtyWillReturn).change();
            }


        });


    });

    $("body").on("change", ".add_supplier_order_class #order_status_id", function () {

        $('.hidden_if_order_status_pending').show();
        if ($(this).val()=='pending'){
            $('.hidden_if_order_status_pending').hide();
        }

    });

    $("body").on("change", ".supplier_make_order_done_class #additional_fees", function () {

        var orderTotalCost =0 ;
        var orderCost      = $("#total_items_cost_value").val();

        calculateOrderCostWithAdditionalFees(orderTotalCost,orderCost);

    });

    $("body").on("change", ".supplier_return_order_class .want_return_qty", function () {

        returnOrder();

    });

    function returnOrder(){

        var newValue = 0;
        $(".want_return_qty").each(function (index, item) {
            var parent        = $(item).parents(".item_will_return");
            var itemCost      = $(".item_total_cost", parent).val();
            var orderQty      = $(".order_quantity", parent).val();
            var returnedQty   = $(".returned_qty", parent).val();
            var availableQty  = parseInt(orderQty) - parseInt(returnedQty);
            var qtyWillReturn = $(item).val();
            var itemId        = parent.attr("id");

            if (qtyWillReturn > availableQty){

                $(item).val("");
                Swal.fire(
                    'Warning',
                    `wanted return quantity it should be less than or equal ${availableQty}`,
                    'warning'
                );
            }
            else {

                if (qtyWillReturn > 0){
                    newValue = newValue + (parseFloat(itemCost) * parseFloat(qtyWillReturn));
                }

            }

        });

        var basicReturnedAmount = $("#basic_returned_amount");
        var oldValue            = basicReturnedAmount.val();
        newValue                = (parseFloat(oldValue) + parseFloat(newValue)).toFixed(2);

        $("#returned_amount_id").val(newValue).change();
        $("#received_amount_id").attr("max", newValue);

    }

});
