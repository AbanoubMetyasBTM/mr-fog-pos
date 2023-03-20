var load_dateTimePicker;
var check_inputs_inside_collapse;

var imgs_input_names = [];
var imgs_input = {};
var deleted_files_indices = {};

$(function(){

    if ($(".load_admin_form_file").length > 0) {
        return false;
    }

    $("body").append("<input type='hidden' class='load_admin_form_file' >");

    //generate_slider_tags slider

    $("body").on("click",".generate_slider_tags .add_img_btn_class",function(){
        var parent_element=$(this).parents(".generate_slider_tags");
        var new_item=$(".item",parent_element).first().clone();
        $("input",new_item).val("");

        $(".cke",new_item).remove();
        $(".slider_imgs_class",parent_element).append(new_item);

        $("textarea",new_item).each(function(){
            var text_area=$(this);

            if(text_area.attr("class").includes("body")){
                text_area.ckeditor();
            }
        });

        return false;
    });

    $("body").on("click",".generate_slider_tags .slider_img_remover",function(){

        var confirm_res = confirm("Are you Sure?");
        if(!confirm_res)return false;

        var parent_element=$(this).parents(".generate_slider_tags");

        $(this).parents(".old_item").remove();

        var removed_id=$(this).data("photoid");
        var ids=JSON.parse($(".json_values_of_slider_class",parent_element).val());

        if($(this).data("without_attachment")=="yes"){
            delete ids[removed_id-1];
        }
        else{
            $.each(ids,function(index,value){
                if (removed_id==value) {
                    ids.splice(index,1);
                }
            });
        }



        $(".json_values_of_slider_class",parent_element).val(JSON.stringify(ids));

        return false;
    });

    $("body").on("click",".generate_slider_tags .open_edit_modal",function(){
        var parent_element=$(this).parents(".generate_slider_tags");

        $(".edit_img_modal",parent_element).modal("show");
        $(".edit_img_id",parent_element).val($(this).attr("data-img_id"));
        $(".upload_new_item_msg",parent_element).html("");
    });

    $("body").on("click",".edit_img_at_slider_btn",function(){

        var this_element=$(this);
        var parent_element=this_element.parents(".generate_slider_tags");

        this_element.append(ajax_loader_img_func("10px"));

        var img_id=$(".edit_img_id",parent_element).val();

        var obj=new FormData();
        obj.append("_token",_token);
        obj.append("new_file",$(".edit_file_at_slider",parent_element)[0].files[0]);
        obj.append("img_id",img_id);

        $.ajax({
            url:base_url2+"/edit_slider_item",
            type:"POST",
            processData: false,
            contentType: false,
            data:obj,
            success:function(data){
                var ajax_data=JSON.parse(data);
                this_element.children("img").remove();

                $(".upload_new_item_msg",parent_element).html(ajax_data.msg);

                if(ajax_data.without_attachment=="yes"){
                    var changed_at="?"+new Date().getTime();

                    $("img[src='"+base_url2+"/"+ajax_data.file_path_with_time+"']").attr("src",base_url2+"/"+ajax_data.real_file_path+changed_at);
                    $(".open_edit_modal[data-img_id='"+ajax_data.file_path_with_time+"']").attr("data-img_id",ajax_data.real_file_path+changed_at);
                }
                else{

                    if($(".slider_item_when_edited_"+img_id).data("item_type")=="link"){
                        $(".slider_item_when_edited_"+img_id).attr("href",ajax_data.file_path);
                    }
                    else{
                        $(".slider_item_when_edited_"+img_id).attr("src",ajax_data.file_path);
                    }
                }

            }
        });


        return false;
    });


    $("body").on("change",".just_img_file_uploader",function(){

        var parent_element = $(this).parents(".slider_just_imgs");
        var this_id = $(this).attr("data-name");

        imgs_input[this_id] = $(this)[0];
        deleted_files_indices[this_id] = [];
        imgs_input_names.push(this_id);

        var append_where = $(".preview_imgs .new_img",parent_element);

        append_where.html("");

        var readers=[];

        $.each(imgs_input[this_id].files,function(k,val){
            readers[k] = new FileReader();

            readers[k].onload = function (e) {
                $(append_where).append(
                    "<div class='preview_post_img_div'>" +
                    "<img src='"+e.target.result+"' class='preview_post_img' />" +
                    "<div class='remove_img'>" +
                    "<a href='#' class='remove_img_from_arr remove_new_img' data-id='"+this_id+"'><i class='fa fa-times la la-times'></i></a>" +
                    "</div>"+
                    "</div>"
                );
            };

            readers[k].readAsDataURL(val);
        });

    });

    $("body").on("click",".remove_new_img",function(){
        var preview_post_img_div=$(this).parents(".preview_post_img_div");

        deleted_files_indices[$(this).attr("data-id")].push(preview_post_img_div.index());
        preview_post_img_div.remove();

        return false;
    });


    //end generate_slider_tags

    //generate_array_input
    $("body").on("click",".array_input_remove_item",function(){
        $(this).parent().remove();
        return false;
    });

    var text_area_counter=0;
    $("body").on("click",".array_input_add_item",function(){
        var confirm_res = confirm("Are you Sure?");
        if (!confirm_res) {
            return false;
        }

        $(this).parent().remove();
        return false;
    });

    //end generate_array_input


    // Start General Self Edit Item

    // Show input text field
    $('body').on("click",".general_self_edit",function () {

        var this_element = $(this);
        var old_value = this_element.attr("data-field_value");
        var table = this_element.attr("data-tablename");
        var field = this_element.attr("data-field_name");
        var row = this_element.attr("data-row_id");
        var url = this_element.attr("data-url");
        var input_type = this_element.attr("data-input_type");
        var row_primary_col = this_element.attr("data-row_primary_col");
        var func_after_edit = this_element.attr("data-func_after_edit");

        var additional_params = "";

        if (input_type == "number")
        {
            additional_params = ' min="0" ';
        }

        if (input_type == "textarea")
        {
            this_element.parent().html('' +
                '<textarea '+
                additional_params+' ' +
                'data-input_type="'+input_type+'" ' +
                'data-url="'+url+'" ' +
                'class="form-control self_edit_action" ' +
                'data-old_value="'+old_value+'" ' +
                'data-row_primary_col="'+row_primary_col+'" ' +
                'data-row_id="'+row+'" ' +
                'data-tablename="'+table+'" ' +
                'data-func_after_edit="'+func_after_edit+'" ' +
                'data-field_name="'+field+'" ' +
                'title="Press Enter To Save" ' +
                '>'+old_value+'</textarea>')
        }
        else{
            this_element.parent().html('' +
                '<input ' +
                'type="'+input_type+'" '+
                additional_params+'  ' +
                'data-input_type="'+input_type+'" ' +
                'value="'+old_value+'"  ' +
                'data-url="'+url+'" ' +
                'class="form-control self_edit_action" ' +
                'data-old_value="'+old_value+'" ' +
                'data-row_primary_col="'+row_primary_col+'" ' +
                'data-row_id="'+row+'" ' +
                'data-tablename="'+table+'" ' +
                'data-field_name="'+field+'" ' +
                'data-func_after_edit="'+func_after_edit+'" ' +
                'title="Press Enter To Save" ' +
                '/>');
        }



    });

    // apply action on enter
    // $(".self_edit_action").keyup(function(event){
    $('body').on("keyup",".self_edit_action",function (event) {

        var this_element = $(this);
        var old_value = this_element.attr("data-old_value");
        var new_value = this_element.val();
        var table = this_element.attr("data-tablename");
        var field = this_element.attr("data-field_name");
        var row = this_element.attr("data-row_id");
        var url = this_element.attr("data-url");
        var input_type = this_element.attr("data-input_type");
        var row_primary_col = this_element.attr("data-row_primary_col");
        var func_after_edit = this_element.attr("data-func_after_edit");

        var object = {};
        object._token = _token;
        object.item_id = row;
        object.table_name = table;
        object.field_name = field;
        object.value = new_value;
        object.input_type = input_type;
        object.row_primary_col = row_primary_col;
        object.func_after_edit = func_after_edit;
        object.url = url;

        if (event.which==13) {


            this_element.attr("disabled","disabled");

            $.ajax({
                url: url,
                type: 'POST',
                data: object,
                success: function (data) {
                    var returned_data = JSON.parse(data);
                    this_element.removeAttr("disabled");

                    if (typeof (returned_data.success)!= "undefined") {
                        show_flash_message("info",returned_data.success);
                    }

                    if (typeof (returned_data.error)!= "undefined") {
                        show_flash_message("error",returned_data.error);
                    }

                    if (typeof (returned_data.msg)!= "undefined") {
                        this_element.parent().html(returned_data.msg);
                    }

                    if(func_after_edit!=undefined&&typeof window[func_after_edit] === "function"){
                        window[func_after_edit]();
                    }

                }
            });




        }
    });

    // End General Self Edit Item

    $('body').on("click", ".new_general_accept_item", function () {
        var confirm_res = confirm("Are you Sure?");
        if (confirm_res == true) {

            var this_element = $(this);
            var object = {};

            var item_id = this_element.data("itemid");
            var accept_url = this_element.data("accepturl");
            var table_name = this_element.data("tablename");
            var field_name = this_element.data("fieldname");
            var accept = this_element.attr("data-accept");
            var acceptersdata = this_element.attr("data-acceptersdata");
            var item_primary_col = this_element.attr("data-item_primary_col");
            var display_block = this_element.attr("data-display_block");
            var func_after_edit = this_element.attr("data-func_after_edit");

            object._token = _token;
            object.item_id = item_id;
            object.accept_url = accept_url;
            object.table_name = table_name;
            object.field_name = field_name;
            object.accept = accept;
            object.acceptersdata = acceptersdata;
            object.item_primary_col = item_primary_col;
            object.accept_url = accept_url;
            object.display_block = display_block;
            object.func_after_edit = func_after_edit;

            //show load img
            this_element.append("<img src='" + base_url + "images/ajax-loader.gif' class='ajax_loader_class' width='20'>");

            $.ajax({
                url: accept_url,
                type: 'POST',
                data: object,
                success: function (data) {
                    var returned_data = JSON.parse(data);

                    $('.ajax_loader_class',this_element).remove();

                    if (typeof (returned_data.error)!= "undefined") {
                        show_flash_message('error', returned_data.error);
                    }

                    if (typeof (returned_data.success)!= "undefined") {
                        show_flash_message('info', returned_data.success);
                    }

                    if (typeof (returned_data.msg)!= "undefined") {
                        this_element.parents(".parent_accepters_div").html(returned_data.msg);
                    }

                    if(func_after_edit!=undefined&&typeof window[func_after_edit] === "function"){
                        window[func_after_edit](returned_data);
                    }

                }
            });

        }//end confirmation if

        return false;
    });

    load_dateTimePicker=function () {
        // fire datetimepicker for input type
        if ($('.datetimepicker_input_date_time').length){
            $('.datetimepicker_input_date_time').datetimepicker();
        }

        if ($('.datetimepicker_input_date').length){
            $('.datetimepicker_input_date').datetimepicker({format: 'DD-MM-YYYY'});
        }

        if ($('.datetimepicker_input_time').length){
            $('.datetimepicker_input_time').datetimepicker({format: 'LT'});
        }

        if ($('.datetimepicker_input_month_year').length){
            $('.datetimepicker_input_month_year').datetimepicker({ viewMode: 'years', format: 'MM/YYYY' });
        }
    };
    addToCallAtLoadArr("load_dateTimePicker");


    //generate_img_tags_for_form
    $('body').on("change", ".checkbox_field_image", function () {

        var check=$(this).is(":checked");
        var parent_element = $(this).parents(".parent_file_upload_input");
        if (check==true) {
            console.log("herer");
            $(".file_upload_input",parent_element).removeAttr("disabled")
        }
        else {
            $(".file_upload_input",parent_element).attr("disabled","disabled")
        }

    });

    $('body').on('click','.upload_new_file',function () {

        var this_element = $(this);
        this_element.parents('.parent_file_upload_input').find('.checkbox_field_image').click();
        this_element.parents('.parent_file_upload_input').find('.file_upload_input').removeAttr("disabled");
        this_element.parents('.parent_file_upload_input').find('.file_upload_input').click();

        return false;
    });

    $('body').on('change','.file_upload_input',function (e) {

        var parentElement = $(this).parents(".parent_file_upload_input");
        var this_element = $(this);
        var fileName = "";

        if (typeof e.target.files[0] == "undefined"){
            return false;
        }

        fileName = e.target.files[0].name;

        showImage(
            $(this)[0],
            $(".show_intial_img",parentElement)[0]
        );

        // $('.input_file_name',parentElement).text(fileName);
        $('.input_file_name',parentElement).text("");

        return false;
    });

    //generate_depended_selects
    $('body').on("change", ".parent_depended_selects", function () {
        var select_1_value=$(this).val();
        var parent_element = $(this).parents(".the_big_parent_for_depended_selects");

        $(".child_depended_selects option",parent_element).hide();
        $(".div_child_depended_selects",parent_element).show();

        var childs=$(".child_depended_selects option[data-targetid='"+select_1_value+"']",parent_element);

        $.each(childs,function(index,value){
            $(this).show();
            if (index==0) {
                $(this).attr("selected","selected")
            }
        });

    });
    $(".parent_depended_selects").change();

    //generate array input

    $("body").on("click",".remove_item_class",function(){

        var confirm_res = confirm("Are you Sure?");
        if (!confirm_res) {
            return false;
        }

        $(this).parents(".old_item").remove();
        return false;
    });

    $("body").on("click",".add_item_class",function(){

        var parent_element=$(this).parents(".contain_arr_input");
        var new_html=$(".first_item_class",parent_element).first().clone();
        $("input",new_html).val("");


        $(".contain_items_class",parent_element).append(new_html);

        $.each($("textarea",new_html),function(){
            if($(this).hasClass("ckeditor")){
                $(this).ckeditor();
            }
        });

        return false;
    });


    check_inputs_inside_collapse=function(){
        $.each($("input,textarea"),function(){
            $(this)[0].addEventListener('invalid',function(){
                $('[aria-controls="'+$(this).parents('[data-parentsection="yes"]').attr("id")+'"]').click();
            });
        });
    };
    addToCallAtLoadArr("check_inputs_inside_collapse");

});
