$(function () {

    $("body").on("click", ".btn-show", function () {
        $(".show-text-btn").toggleClass("d-block");
    });
    $("body").on("click", ".btn-show-sec-4", function () {
        $(".show-text-btn-sec-4").toggleClass("d-block");
    });
    $("body").on("click", ".btn-show-sec-5",function () {
        $(".show-text-btn-sec-5").toggleClass("d-block");
    });

});
