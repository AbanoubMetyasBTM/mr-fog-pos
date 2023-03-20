var clientOrderAfterSelectProduct;
var runAfterSelectClientId;
var makeOrderDonePage;
var afterSelectClient;

$(function () {


    if($(".add_client_order_class .add_new_client_order_item").length > 0){
        $(document).keyup(function(e){
            if(e.which==187 && e.shiftKey==true){
                $(".add_new_client_order_item").click();
            }
        });
    }

    $("body").on("click", ".add_client_order_class .add_new_client_order_item", function () {

        var clonedDiv = $(".table .client_order_items").first().clone();

        var defaultInventory = $("#basic_inv_id_id").val();

        $(".btm_select_2_display_selected_text", clonedDiv).val("").change();
        $(".btm_select_2_selected_obj", clonedDiv).val("").change();
        $(".inv_id", clonedDiv).val(defaultInventory);
        $(".item_type", clonedDiv).val("item");
        $(".order_quantity", clonedDiv).val(1);
        $(".item_total_cost", clonedDiv).val("");
        $(".items_cost", clonedDiv).val("");

        $(".promo_note", clonedDiv).remove();


        $("#client_order_items").append(clonedDiv);
    });

    clientOrderAfterSelectProduct = function(selectedObj){

        var data = $(".btm_select_2_selected_obj",selectedObj).val();
        var parent = selectedObj.parents(".client_order_items");
        if (data !== ""){
            var obj  = JSON.parse(data.toString());
            if (obj.search_q_is_box === true){
                parent.find(".item_type").val("box").change();
            }
            else {
                parent.find(".item_type").val("item").change();
            }
        }

    };

    $("body").on("change", ".add_client_order_class .client_order_items", function () {

        var parent = $(this);
        var itemQty  = $(".order_quantity", parent).val();
        var itemCost = $(".item_total_cost", parent).val();

        if (itemQty.length !== 0 && itemCost.length !== 0){
            var totalItemsCost = (parseFloat(itemCost) * parseFloat(itemQty)).toFixed(2);
            $(this).find(".items_cost").val(totalItemsCost);
        }
        calculateOrderCost();
    });

    $("body").on("change", ".add_client_order_class .product_sku", function () {
        var parent = $(this).parents('.client_order_items');
        calculateItemPrice(parent)


        var thisEle = $(this);
        thisEle.parents(".parent_div").find("label").append(ajax_loader_img_func("10px"));
        thisEle.attr("readonly","readonly");


    });

    $("body").on("change", ".add_client_order_class .order_quantity", function () {
        var parent = $(this).parents('.client_order_items');
        calculateItemPrice(parent)

        checkIfMainInvOfBranchHasSpecificProductQty(parent);
    });

    $("body").on("change", ".add_client_order_class .item_type", function () {
        var parent = $(this).parents('.client_order_items');
        calculateItemPrice(parent)

        checkIfMainInvOfBranchHasSpecificProductQty(parent);
    });


    function calculateItemPrice(clientOrderItemRow) {
        var parent    = clientOrderItemRow;
        var proSkuObj = $('.btm_select_2_selected_obj', parent).val();
        var itemType  = $(".item_type", parent).val();

        var clientParentDiv  = $(".btm_select_2_selected_value_client_id").parents(".btm_select_2_parent_div");
        if ($('.btm_select_2_selected_obj', clientParentDiv).val()==""){
            show_flash_message("info", "please select client first");
            return;
        }

        var clientObj = JSON.parse($('.btm_select_2_selected_obj', clientParentDiv).val());

        if (
            clientObj !== "" &&
            proSkuObj !== undefined &&
            proSkuObj !== '' &&
            itemType !== undefined &&
            itemType !== ''
        ){

            proSkuObj = JSON.parse(proSkuObj.toString());

            var price = `${itemType}` + '_' + `${clientObj.client_type}` + "_price";

            $(".item_total_cost", clientOrderItemRow).val(proSkuObj[`${price}`]).change();

            var promoNote = $(".promo_note", parent);
            promoNote.remove();

            if (proSkuObj.promotion_text !== ""){

                if (promoNote.val() === undefined || promoNote.val() === ''){
                    var promo = "<div class='alert alert-info promo_note'>" + proSkuObj.promotion_text +
                        "<input hidden name='promo_id["+ proSkuObj.id +"]' value='"+ proSkuObj.promotion.promo_id +"'></div>";
                    $(".item_total_cost", parent).parents(".form-group").append(promo)
                }
            }




        }
    }

    function _calculateDiscountValue(orderTotalCost)
    {

        var couponValue = $('#coupon_value');
        var couponType = $('#coupon_type');
        var totalDiscount = 0;
        $('#coupon_notes_div').val("");

        //discount from coupon
        if (couponValue.val() !== undefined && couponType.val() !== undefined) {
            if (couponType.val() === 'value'){
                totalDiscount = parseFloat(couponValue.val());
            }
            else {
                totalDiscount  = ((parseFloat(couponValue.val()) /100) * orderTotalCost);
            }

            orderTotalCost = orderTotalCost - totalDiscount;
        }

        //discount from redeem
        var rewardMoney = $(".selected_redeem_class:checked").data("reward_money");
        if(rewardMoney === undefined){
            rewardMoney = 0;
        }

        rewardMoney     = parseFloat(rewardMoney);
        orderTotalCost  = orderTotalCost-rewardMoney;

        if(orderTotalCost < 0){
            orderTotalCost = 0;
        }

        $('#coupon_notes_div').val(parseFloat(totalDiscount)+parseFloat(rewardMoney));

        return orderTotalCost;

    }

    function calculateOrderCost() {
        var orderTotalCost = 0;
        var orderCost      = 0;

        $(".total_cost").val(orderTotalCost);


        $(".items_cost").each(function () {
           if ($(this).val() !== ""){
               orderCost = orderCost + parseFloat($(this).val());
           }
        });
        if (typeof orderCost === 'number' && !isNaN(orderCost)){
            $(".total_items_cost").val(orderCost.toFixed(2));
        }

        orderTotalCost = parseFloat(orderCost);


        if (typeof orderTotalCost === 'number' && !isNaN(orderTotalCost) && orderTotalCost !== 0){
            orderTotalCost = _calculateDiscountValue(orderTotalCost);

            $(".total_cost").val(orderTotalCost.toFixed(2));
            $("#paid_amount").attr("max", orderTotalCost.toFixed(2));

            calculatePaidAmount();
        }

    }


    $("body").on("change", ".add_client_order_class #basic_inv_id_id", function () {
        $(".table").find(".inv_id").val($(this).val()).change();
    });

    $("body").on("change", ".add_client_order_class #coupon_code", function () {
        checkIfCouponIsUsable($(this));
    });


    $("body").on("change", ".add_client_order_class .selected_redeem_class", function () {
        calculateOrderCost();
    });



    function checkIfCouponIsUsable(couponCodeElement) {


        var clientId  = $(".btm_select_2_selected_value_client_id").val();
        if (clientId == ""){
            show_flash_message("info", "please select client first");
            return;
        }

        addImageToFormGroupLabel(couponCodeElement);

        $("#coupon_notes_div_value").remove().change();
        $("#coupon_value").remove().change();
        $("#coupon_type").remove().change();

        if (couponCodeElement.val() !== '') {
            var url  = base_url2 + '/admin/clients-orders/check-if-coupon-is-usable';
            return $.ajax({
                url : url,
                type: 'POST',
                data: {'_token': _token, 'client_id': clientId, 'coupon_code': couponCodeElement.val()},
                success: function (data) {
                    removeImageFromFormGroupLabel(couponCodeElement);

                    if (data.error !== undefined){
                        Swal.fire(
                            'Info',
                            `${data.error}`,
                            'info'
                        );
                        couponCodeElement.val("");
                    }

                    if (data.data !== undefined){

                        var couponValueInput = `<input hidden id='coupon_value' value='${data.data.coupon_value}'>`;
                        var couponTypeInput  = `<input hidden id='coupon_type' value='${data.data.coupon_type}'>`
                        couponCodeElement.parent().append(couponValueInput);
                        couponCodeElement.parent().append(couponTypeInput);
                    }

                    calculateOrderCost();
                }
            });
        }
        else {
            calculateOrderCost();
            removeImageFromFormGroupLabel(couponCodeElement);
        }

    }

    $("body").on("change", ".add_client_order_class #gift_card", function () {

        var giftCardElement = $(this);
        var clientId = $("#client_id_id").val();

        $("#gift_card_note").remove();
        $("#card_value").remove();
        calculatePaidAmount();

        checkIfGiftCardUsable(giftCardElement)
    });

    function checkIfGiftCardUsable(giftCardElement) {
        addImageToFormGroupLabel(giftCardElement);

        if (giftCardElement.val() !== undefined && giftCardElement.val() !== '') {
            var url  = base_url2 + '/admin/clients-orders/check-if-gift-card-is-usable';
            $.ajax({
                url : url,
                type: 'POST',
                data: {'_token': _token, 'gift_card': giftCardElement.val()},
                success: function (data) {

                    removeImageFromFormGroupLabel(giftCardElement);

                    if (data.error !== undefined){
                        Swal.fire(
                            'Info',
                            `${data.error}`,
                            'info'
                        );
                        giftCardElement.val("");
                    }

                    if (data.data !== undefined){
                        var cardValueInput = `<input hidden id='card_value' class="payment_method">`
                        giftCardElement.parent().append(cardValueInput).change();
                        $('#card_value').val(data.data).change();
                        var msg = `<div class="col-md-12"><div id="gift_card_note" class="alert alert-info">${data.data} gift card used</div></div>`
                        $("#payment_methods_section").append(msg);
                    }

                }
            });


        }
        else{
            removeImageFromFormGroupLabel(giftCardElement);
        }

    }

    function checkIfMainInvOfBranchHasSpecificProductQty(clientOrderItemRow) {
        var itemQty = $('.order_quantity', clientOrderItemRow).val();
        var proSkuObj = $('.btm_select_2_selected_obj', clientOrderItemRow).val();
        var itemType =  $(".item_type", clientOrderItemRow).val();


        if(proSkuObj.length > 0 && itemType.length > 0 && itemQty.length > 0){

            proSkuObj = JSON.parse(proSkuObj.toString());

            var proQtyInInv = '';

            if (itemType === 'item'){
                proQtyInInv = proSkuObj.total_items_quantity;
            }
            else{
                proQtyInInv = proSkuObj.ip_box_quantity;
            }

            if (proQtyInInv  <  itemQty){
                var msg = 'The quantity of the required product is greater than the number available in the inventory ' +
                          `<br> The quantity in inventory is ${proQtyInInv}`;

                Swal.fire(
                    'Info',
                    `${msg}`,
                    'info'
                );
                $('.order_quantity', clientOrderItemRow).val("").change();
                $('.items_cost', clientOrderItemRow).val("").change();
            }


        }
    }

    $("body").on("change", ".add_client_order_class .payment_method", function () {


        calculatePaidAmount();

        if (
            parseFloat($('#client_wallet').val()) < parseFloat($('.total_cost').val()) &&
            parseFloat($('#paid_amount').val()) > parseFloat($('.total_cost').val()) &&
            $(this).val() != ""
        ){
            Swal.fire(
                'Info',
                `The amount paid is greater than the cost of the order`,
                'info'
            );
            $(this).val("").change();
        }

    });

    function calculatePaidAmount(){
        var paidAmount = 0;

        var orderCost = $('.total_cost').val();

        $('#paid_amount').val("0");
        $('#card_value_will_paid').remove();

        if ( $('#card_value').val() != undefined &&  parseFloat($('#card_value').val()) >= parseFloat(orderCost)){

            var amountWillPaidFromCard = parseFloat(orderCost) ;
            var cardValueWillPaidInput = `<input hidden id='card_value_will_paid' value="${amountWillPaidFromCard}">`
            $('#card_value').parent().append(cardValueWillPaidInput);
            paidAmount = amountWillPaidFromCard;
        }
        else {
            $(".payment_method").each(function () {
                if ($(this).val() !== ""){
                    paidAmount = paidAmount + parseFloat($(this).val());
                }
            });
        }
        $("#paid_amount").val(paidAmount);

        calculateRemainAmountOfClientOrder()

    }

    function calculateRemainAmountOfClientOrder() {
        var paidAmount = $("#paid_amount").val();
        var orderCost  = $('.total_cost').val();

        if(paidAmount !== '' && orderCost !== ''){
            var remainAmount = orderCost - paidAmount;
            $('#remain_amount').val(roundNumber(remainAmount));
        }

    }


    runAfterSelectClientId = function(selectedObj){

        var data = $(".btm_select_2_selected_obj",selectedObj).val();

        if(isJson(data)===false){
            return;
        }

        data = JSON.parse(data);

        afterSelectClient(data);

    };

    afterSelectClient = function(data){

        populateClientWalletField(data.wallet_amount);
        populateClientLoyaltyPoints(data.points_wallet_value);

        $(".branch_taxes_div").show();
        $(".client_taxes_div").hide();

        if (data.group_taxes != undefined && data.group_taxes.length) {
            $(".client_taxes_div").html("");

            $.each(data.group_taxes, function (i, item) {
                $(".client_taxes_div").append(`<label>Taxes Applied: ${item.tax_label} : ${item.tax_percent}%</label><br>`);
            });

            $(".branch_taxes_div").hide();
            $(".client_taxes_div").show();
        }


        $(".client_order_items").each(function (index, item){
            calculateItemPrice(item);
        });

        if($('#coupon_code').val() !== undefined){
            checkIfCouponIsUsable($('#coupon_code'));
        }

        $(".hide_until_select_client").removeClass("hide_until_select_client");

        $(".select_client_parent_div").hide();
        $(".show_client_parent_div .show_client_name").html(data.display_text);
        $(".show_client_parent_div").removeClass("hide_div");



    };


    function populateClientWalletField(wallet_amount) {
        $("#client_wallet").prop('disabled', false).val('');

        $("#available_amount_in_wallet").val(wallet_amount).change();
        $("#client_wallet").attr("max", wallet_amount)

        if (parseFloat(wallet_amount) < 0){
            $("#client_wallet").prop('disabled', true);
        }
    }

    function populateClientLoyaltyPoints(loyaltyPoints){

        $(".selected_redeem_class").removeProp("checked");
        $(".selected_redeem_class").first().prop("checked", true);

        $("#available_points_in_wallet").val(loyaltyPoints);
        $(".available_redeems").hide();

        $(".available_redeems").each(function () {
            if (parseInt(loyaltyPoints) >= parseInt($(this).data("points_value"))) {
                $(this).show();
            }
        });

    }




    $("body").on("change", ".add_client_order_class .order_status", function (){

        if ($(this).val() === 'pick_up'){
            $('#pick_up_date').show();
            $('#pick_up_date input').attr("required", "required");

            $('.payment_method').val('');
            $('#gift_card').val('');
            $('#coupon_code').val('');
            $('#coupon_notes_div').empty();

            $('#payment_method_row').hide();
            $('#discount_row').hide();
            $("#loyalty_points_row").hide();
        }
        else {
            $('#pick_up_date').hide();
            $('#pick_up_date input').removeAttr("required");

            $('#payment_method_row').show();
            $('#discount_row').show();
            $("#loyalty_points_row").show();
        }

    });



    /********************** return order ************************/
    $("body").on("click", ".add_client_order_class .return_all_item_qty", function () {

        var parent = $(this).parents(".item_will_return");

        var orderQty      = parent.find(".order_quantity").val();
        var returnedQty   = parent.find(".returned_qty").val();
        var qtyWillReturn = orderQty - returnedQty;

        parent.find(".want_return_qty").val(qtyWillReturn).change();
    });


    $("body").on("change", ".add_client_order_class .want_return_qty", function () {


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

        $("#amount_will_return_id").val(newValue).change();
        $("#received_amount_id").attr("max", newValue);


    });

    $("body").on("click", ".add_client_order_class #return_all_items", function () {

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



    /********************** End return order ************************/

    $("body").on("click", ".add_client_order_class .remove_order_item_row", function () {

        var parent = $(this).parents(".client_order_items");

        Swal.fire({
            html: "<b>Are You Sure?</b><br><br>",
            showDenyButton: true,
            confirmButtonText: "Yes",
            denyButtonText: `No`,
            icon: "info",
        }).then((result) => {
            if (result.isConfirmed) {

                if($(".client_order_items").length > 1){
                    parent.remove();
                    calculateOrderCost()
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



    $("body").on("change", ".add_client_order_class .item_total_cost", function () {

        var itemCost = $(this).val();
        var parentElement = $(this).parents(".item_row");
        var proSkuId = $(".btm_select_2_selected_value", parentElement).val();
        var itemType = $(".item_type", parentElement).val();

        if (itemCost !== undefined && proSkuId !== undefined && itemType !== undefined) {

            var url = base_url2 + "/admin/clients-orders/check-product-price";
            $.ajax({
                url : url,
                type: 'POST',
                data: {'_token': _token, 'pro_sku_id': proSkuId, "item_cost": itemCost, "item_type": itemType},
                success: function (data) {
                    if (data.length > 0){
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

    $("body").on("change", ".add_client_order_class .amount_will_return", function () {
        calculateRemainderOfAmountWillReturn();
    });

    $("body").on("change", ".add_client_order_class #amount_will_return_id", function () {
        calculateRemainderOfAmountWillReturn();
    });



    function calculateRemainderOfAmountWillReturn(){

        var amountWillReturn = $('#amount_will_return_id').val();
        var receivedAmount   = 0;

        $(".amount_will_return").each(function (){
            if ($(this).val() !== ''){
                receivedAmount = receivedAmount + parseFloat($(this).val());
            }
        });

        $('#received_amount_id').val(receivedAmount);



        if (amountWillReturn !== ''){
            var remainderOfAmountWillReturn = parseFloat(amountWillReturn) - parseFloat(receivedAmount);

            $('#received_amount_id').attr('max', amountWillReturn);
            $('#remainder_of_amount_will_return').val(remainderOfAmountWillReturn);
        }

    }


    makeOrderDonePage = function(){

        if($(".this_is_make_order_done_page").length === 0){
            return false;
        }

        $('#payment_method_row').show();
        $('#discount_row').show();
        $("#loyalty_points_row").show();

        afterSelectClient(JSON.parse($(".client_obj").val()));

    };
    addToCallAtLoadArr("makeOrderDonePage");


});
