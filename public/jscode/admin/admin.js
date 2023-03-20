$(function(){


    $("body").on("click",'.download_all_files',function(){

        $(".contain_files a").each(function(){
            window.open($(this).attr("href"));
        });

    });


    if ($(".go_to_site_content_keyword").length) {

        const urlParams = new URLSearchParams(window.location.search);
        const myParam   = urlParams.get('go_to_keyword');

        var id               = myParam + "_id";
        var SCKeywordElement = $(`label[for='${id}']`);
        if (SCKeywordElement.length == 0) {
            id               = myParam + "id";
            SCKeywordElement = $(`label[for='${id}']`);
        }

        if (SCKeywordElement.length == 0) {
            id               = myParam + "id";
            SCKeywordElement = $(`#${id}`);
        }

        if (SCKeywordElement.length == 0) {
            id               = myParam + "_id";
            SCKeywordElement = $(`#${id}`);
        }


        if (SCKeywordElement.length > 0) {
            SCKeywordElement.parents(".form-group").css("background", "#EEE");


            $('html, body').animate({
                scrollTop: SCKeywordElement.offset().top - 40
            }, 500);

            $("body").append("<button class='btn btn-info go_to_SC_keyword'>Go To Keyword</button>");

        }

        $("body").on("click", ".go_to_SC_keyword", function () {

            $('html, body').animate({
                scrollTop: SCKeywordElement.offset().top - 40
            }, 500);
        });



    }




});
