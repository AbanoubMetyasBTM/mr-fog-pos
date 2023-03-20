var load_bulk_save;
var get_bulk_form;

$(function(){

    get_bulk_form=function(appendAt,appendOrReplace,href){
        var obj={};

        $.ajax({
            url:href,
            type: "GET",
            data: obj,
            success: function (data) {

                if(appendOrReplace=="append"){
                    appendAt.append(data);
                }
                else{
                    appendAt.replaceWith(data);
                }

                $("button[type='submit']",$(".save_bulk_parent_div")).remove();
                $("input[type='submit']",$(".save_bulk_parent_div")).remove();

                call_at_load();
            }
        });
    };

    load_bulk_save=function(){
        $.each($(".bulk_save_get_inner_form"),function(){
            get_bulk_form($(this),"replace",$(this).data("url"));
        });
    };
    addToCallAtLoadArr("load_bulk_save");


    $("body").on("click",".add_more_bulk",function () {

        var this_element = $(this);

        get_bulk_form($(".add_bulk_more_rows"),"append",this_element.data("url"));

    });

    $("body").on("click",".submit_bulk",function () {

        var processed_to_submit=true;
        $.each($(".save_bulk_parent_div form"),function(){

            if(!general_check_valid($(this))){
                processed_to_submit=false;
            }

        });

        if(processed_to_submit){
            $.each($(".save_bulk_parent_div form"),function(){
                $(this).submit();
            });
        }
    });





});
