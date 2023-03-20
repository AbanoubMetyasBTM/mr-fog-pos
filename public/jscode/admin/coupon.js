var onLoadCouponPage;

$(function () {

    onLoadCouponPage = function(){
        var branchId = $('.branch_id').val();

        if(branchId==undefined){
            return false;
        }

        checkIfCouponForAllBranches(branchId);
    };
    addToCallAtLoadArr("onLoadCouponPage");


    $("body").on("change", ".branch_id", function (){
        var branchId = $(this).val();
        checkIfCouponForAllBranches(branchId);
    });


    function checkIfCouponForAllBranches(branchId)
    {

        if (branchId === '0') {
            $(".coupon_code_type option[value='value']").remove();
        }
        else {
            var couponCodeTypeValue = $(".coupon_code_type option[value='value']").val();

            console.log(couponCodeTypeValue);
            if (couponCodeTypeValue === undefined){
                $(".coupon_code_type").append('<option value="value">Value</option>')
            }
        }

    }


});
