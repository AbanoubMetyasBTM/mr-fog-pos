$(function () {

    $("body").on("change", "#available_amount_in_wallet", function (){

        console.log(parseFloat($(this).val()));

        if (parseFloat($(this).val()) === 0 || parseFloat($(this).val()) < 0) {
            $('#client_wallet').prop('disabled', true);
        }
    });


});
