var call_datatable;
var call_order;
var load_select_2;
var load_summernote;
var call_wizard;
var is_form_valid;

$(function () {

    if($(".load_admin_utility_file_to").length > 0){
        return false;
    }

    $("body").append("<input type='hidden' class='load_admin_utility_file_to' >");


    $("body").on("click",".button_disable_after_click",function(){
        var confirm_res=confirm("are you sure?");
        if(!confirm_res){
            return false;
        }

        $(this).hide();
    });

    $("body").on("dblclick",".button_disable_after_click",function(){
        console.log("button_disable_after_click");
        return false;
    });

    /**
     * Start general remove item
     */

    function resetAttributes(element) {
        element.each(function() {
            var attributes = this.attributes;
            var i = attributes.length;
            while( i-- ){
                this.removeAttributeNode(attributes[i]);
            }
        })
    }

    $('body').on("click", ".confirm_remove_item", function () {

        var this_element    = $(this);
        var submit_btn      = $('#confirmModal').find('.submit_btn');

        // initialize button to remove
        resetAttributes(submit_btn);
        submit_btn.attr('class','btn btn-primary submit_btn general_remove_item');
        $('#errorsModal').find('.display_errors_msg').html("");

        // remove old actions
        submit_btn.removeAttr("disabled");

        var item_id         = this_element.attr("data-itemid");
        var delete_url      = this_element.attr("data-deleteurl");
        var table_name      = this_element.attr("data-tablename");
        var tr_id           = this_element.attr("data-trid");
        var parent_id           = this_element.attr("data-parent_id");
        var reload          = this_element.attr("data-reload");

        // set new or update exist attributes
        submit_btn.attr('data-itemid',item_id);
        submit_btn.attr('data-deleteurl',delete_url);
        submit_btn.attr('data-tablename',table_name);
        submit_btn.attr('data-trid',tr_id);
        submit_btn.attr('data-parent_id',parent_id);
        submit_btn.attr('data-reload',reload);

    });


    $('body').on("click", ".general_remove_item", function (e) {

        var this_element    = $(this);
        var item_id         = this_element.attr("data-itemid");
        var delete_url      = this_element.attr("data-deleteurl");
        var table_name      = this_element.attr("data-tablename");
        var parent_id           = this_element.attr("data-parent_id");
        var tr_id           = this_element.attr("data-trid");
        var reload          = this_element.attr("data-reload");
        var elem_to_fade    = $('#'+tr_id);

        if (typeof(tr_id) == "undefined") {
            tr_id = "row";
            elem_to_fade    = $('#'+ tr_id + item_id);
        }

        if (parent_id != undefined) {
            elem_to_fade    = $('#'+ parent_id);
        }

        if(!(item_id>0)){
            elem_to_fade.fadeOut(400);
            $('#confirmModal').find('.submit_btn').removeClass('general_remove_item');
            $('#confirmModal').modal('hide');
            $('#thanksModal').modal('show');
            return false;
        }

        this_element.attr("disabled","disabled");

        $.ajax({
            url : delete_url,
            type: 'POST',
            data: {'_token': _token, 'item_id': item_id, 'table_name': table_name},
            success: function (data) {

                console.log(data);
                var returned_data = JSON.parse(data);

                $('#confirmModal').find('.submit_btn').removeClass('general_remove_item');
                $('#confirmModal').modal('hide');

                if (returned_data.deleted == "yes") {

                    if(typeof reload != undefined && reload == "1")
                    {
                        location.reload();
                    }
                    else{
                        elem_to_fade.fadeOut(400);
                        $('#thanksModal').modal('show');
                    }


                }else{
                    $('#errorsModal').modal('show');
                    $('#errorsModal').find('.display_errors_msg').html(returned_data.deleted);
                }

                if(typeof (returned_data.msg) != "undefined"){
                    $('#errorsModal').modal('show');
                    $('#errorsModal').find('.display_errors_msg').html(returned_data.msg);
                }

            }
        });

        return false;
    });

    /**
     * End general remove item
     */

    $('body').on("click", ".general_accept_item", function () {
        var confirm_res = confirm("Are you Sure?");
        if (confirm_res == true) {

            var this_element = $(this);
            var object = {};

            var item_id = this_element.data("itemid");
            var accept_url = this_element.data("accepturl");
            var table_name = this_element.data("tablename");
            var field_name = this_element.data("fieldname");
            var accept = this_element.attr("data-accept");

            object._token = _token;
            object.item_id = item_id;
            object.accept_url = accept_url;
            object.table_name = table_name;
            object.field_name = field_name;
            object.accept = accept;

            var email = this_element.data("send_email");
            var msg_target = this_element.data("msg_target");
            if (typeof(email) != "undefined") {
                object.email = email;
                object.msg_target = msg_target;
            }

            //show load img
            this_element.append("<img src='" + base_url + "images/ajax-loader.gif' class='ajax_loader_class' width='20'>");

            $.ajax({
                url: accept_url,
                type: 'POST',
                data: object,
                success: function (data) {
                    var returned_data = JSON.parse(data);

                    if (returned_data.success != "error") {
                        $('.ajax_loader_class').remove();
                        this_element.children(".inside_review_status").html(returned_data.status);
                        $(this_element).attr('data-accept',returned_data.new_accept);
                    }

                }
            });

        }//end confirmation if

        return false;
    });

    $("body").on("click",".show_general_data",function(){
        var data=$(this).data("alldata");

        console.log(data);

        var html_tags="";

        $.each(data,function(index,value){
            if(typeof (value)=="object"){
                var object_val="";
                $.each(value,function(i,v){
                    object_val=object_val+i+" : "+v+" <br>";
                });
                value=object_val;
            }

            html_tags+='<div class="col-md-12">';
                html_tags+='<div class="col-md-4">';
                    html_tags+='<p style="text-transform: capitalize;">'+index.replace("_"," ")+':</p>';
                html_tags+='</div>';
                html_tags+='<div class="col-md-8">';
                    html_tags+='<p>'+value+'</p>';
                html_tags+='</div>';
            html_tags+='</div><hr>';

        });

        $("#general_show_all_data_modal .modal-body").html(html_tags);
        $("#general_show_all_data_modal").modal("show");

        return false;
    });

    call_datatable=function(){
        //General functions
        if($('#cat_table').length > 0)
        {
            $('#cat_table').DataTable({
                "aaSorting": [],
                "paging": false

            });
        }


        if($('.datatable_with_pagination').length > 0){
            $('.datatable_with_pagination').DataTable({
                "paging": true
            });;
        }

        if($('.datatable_without_pagination').length > 0){
            $('.datatable_without_pagination').DataTable({
                "paging": false
            });;
        }
    };
    addToCallAtLoadArr("call_datatable");

    call_order=function(){
        if ($(".reorder_items").length) {
            $("#sortable").sortable();
            $("#sortable").disableSelection();

            $("body").on("click",".reorder_items",function () {
                var items = [];
                var table_name;
                var field_name;

                $.each($("#sortable").children(), function (index, value) {
                    var item_id = $(this).data("itemid");
                    table_name = $(this).data("tablename");
                    field_name = $(this).data("fieldname");

                    var item_order = index;

                    items.push([item_id, item_order]);
                });

                if (typeof(field_name) == "undefined") {
                    field_name = "order";
                }


                var this_element = $(this);
                this_element.append(ajax_loader_img_func("20px"));


                $.ajax({
                    url: base_url2 + '/reorder_items',
                    type: 'POST',
                    data: {'_token':_token, 'items': items, 'table_name': table_name , 'field_name':field_name},
                    success: function (data) {
                        $(".ajax_loader_class").hide();
                        var json_data = JSON.parse(data);


                        if (typeof (json_data) != "undefiend") {
                            if (typeof (json_data.success) != "undefined") {
                                this_element.html(" Re-Order " + json_data.success);
                                window.location.reload();
                            }

                            if (typeof (json_data.error) != "undefined") {
                                this_element.html(" Re-Order " + json_data.error);
                            }
                        }
                    }
                });


                return false;
            });
        }
    };
    addToCallAtLoadArr("call_order");

    $('.prevent_form_from_submit_on_keypress').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });

    $('.print_page').click(function () {
        window.print();
    });


    load_select_2=function () {

        if($('.select2').length)
        {
            $('.select2').select2({
                dropdownCssClass: 'hover-primary',
                minimumResultsForSearch: Infinity // disabling search
            });
        }

        if($('.select_2_class').length)
        {
            $('.select_2_class').select2({ minimumResultsForSearch: Infinity });
        }

        if($('.select_2_primary').length)
        {
            $('.select_2_primary').select2({
                containerCssClass: 'select2-full-color select2-primary',
                minimumResultsForSearch: Infinity // disabling search
            });
        }

        if($('.select2_search').length)
        {
            $('.select2_search').select2({
                containerCssClass: 'select2-full-color select2-primary',
                minimumResultsForSearch: ''
            });
        }

        if($('.select2_search_phones').length)
        {

            function formatSelect2Image(state) {

                if (!state.id) {
                    return state.text;
                }

                var originalOption = state.element;
                return $('<span> ' + state.text + ' &nbsp; <img src="' + $(originalOption).data('image_url') + '" class="img-flag" /> &nbsp;  (' + state.id + ') </span>');
            }

            function formatSelected2Image(state) {

                if (!state.id) {
                    return state.text;
                }

                var originalOption = state.element;
                $('.phone_placeholder').attr('placeholder',$(originalOption).data('placeholder'));
                return $('<span> ' + state.text + ' &nbsp; <img src="' + $(originalOption).data('image_url') + '" class="img-flag" /> &nbsp;  (' + state.id + ') </span>');
            }

            $('.select2_search_phones').select2({
                containerCssClass: 'select2-full-color select2-primary',
                minimumResultsForSearch: '',
                width: 'resolve',
                //language: "ar",
                templateResult: formatSelect2Image,
                templateSelection: formatSelected2Image,
            });
        }
    };
    addToCallAtLoadArr("load_select_2");

    load_summernote=function () {
        if($('.summernote').length)
        {
            $('.summernote').summernote({
                height  : 200,
                tooltip : false,
                focus   : false
            })
        }

        setTimeout(function(){
            if($('.my_ckeditor').length)
            {
                $('.my_ckeditor').ckeditor({
                    language : 'en'
                });
            }
        },500);

    };
    addToCallAtLoadArr("load_summernote");

    call_wizard=function(){
        /**
         * Start form wizard config
         */

        if ($('#wizard3').length)
        {

            $('#wizard3').steps({
                enableAllSteps          : true,
                enableFinishButton      : false,
                transitionEffect        : 1,
                stepsOrientation        : 1, // 0 => horizontal , 1 => vertical
                headerTag               : 'h3',
                bodyTag                 : 'section',
                autoFocus               : true,
                titleTemplate           : '<span class="number">#index#</span> <span class="title">#title#</span>',
                cssClass                : 'wizard wizard-style-2'
            });

        }

        if ($('.wizard6').length)
        {

            $.each($(".wizard6"),function(){

                if($(this).attr("id")!=undefined){
                    return;
                }

                $(this).steps({
                    enableAllSteps          : true,
                    enableFinishButton      : false,
                    transitionEffect        : 1,
                    stepsOrientation        : 0, // 0 => horizontal , 1 => vertical
                    headerTag               : 'h3',
                    bodyTag                 : 'section',
                    autoFocus               : true,
                    titleTemplate           : '<span class="number">#index#</span> <span class="title">#title#</span>',
                    cssClass                : 'wizard wizard-style-2'
                });
            });



        }

        /**
         * End form wizard config
         */
    }
    addToCallAtLoadArr("call_wizard");


    /**
     * start notification seen
     */

    $('body').on('click','.seen',function(e){

        console.log('is clicked');

        e.preventDefault();

        $.ajax({
            url:base_url2+"/admin/notifications_seen",
            type:'POST',
            data:{'_token':_token},
            success:function(){
                $('#hide').hide();
            }
        })

    });


    /**
     * End notifications seen
     */

    /**
     * Start print_invoice
     */

    $('body').on('click','.print_invoice',function () {

        var this_element = $(this);

        window.print();

        return false;
    });


    /**
     * End print_invoice
     */

    /**
     * Start cloning item
     */

    $('body').on('click','.clone_item_btn',function () {


        var this_element = $(this);

        if(this_element.parents('.clone_items_container').find('.select_2_primary').length)
        {
            this_element.parents('.clone_items_container').find('.select_2_primary').select2("destroy");
        }

        var cloned_item = this_element.parents('.clone_item_div').clone(true, true);

        if(cloned_item.find('.fire_timepicker').length)
        {
            cloned_item.find('.fire_timepicker').removeData().unbind().timepicker({
                dateFormat: "H:i"
            });
        }

        if(cloned_item.find('.fire_datetimepicker').length)
        {
            cloned_item.find('.fire_datetimepicker').removeData('datetimepicker').unbind().datetimepicker({
                autoclose: true,
                todayBtn: true
            });
        }

        if(cloned_item.find('.be_zero').length)
        {
            $.each(cloned_item.find('.be_zero'),function () {
                $(this).val(0);
            });
        }

        if(cloned_item.find('.be_empty').length)
        {
            $.each(cloned_item.find('.be_empty'),function () {
                $(this).val('');
            });
        }

        this_element.parents('.clone_items_container').append(cloned_item);

        if(this_element.parents('.clone_items_container').find('.select_2_primary').length)
        {
            this_element.parents('.clone_items_container').find('.select_2_primary').select2({
                containerCssClass: 'select2-full-color select2-primary',
                minimumResultsForSearch: Infinity // disabling search
            });
        }

        return false;

    });

    $('body').on('click','.remove_item_btn',function () {

        var this_element = $(this);

        var check_length = this_element.parents('.clone_items_container').find('.clone_item_div').length;
        if(check_length == 1)
        {
            alert('لا يمكن مسح اخر عنصر');
            return false;
        }

        var confirm_msg = confirm("هل أنت متأكد ؟");
        if(confirm_msg)
        {
            this_element.parents('.clone_item_div').fadeOut(500).remove();
        }

        return false;

    });

    /**
     * End cloning item
     */



    /**
     * start support_messages seen
     */

    $('body').on('show.bs.popover','.support-message-seen',function(e){
        var this_element = $(this);
        console.log('is clicked');

        var obj = {};
        obj._token       = _token;
        obj.id           = this_element.attr("data-id");
        obj.is_seen      = parseInt(this_element.attr("data-is-seen"));
        if(obj.is_seen == 0){
            $.ajax({
                url:base_url2+"/admin/support_messages_seen",
                type:'POST',
                data:obj,

                success:function(){

                    this_element.parents('tr').removeClass('support-message-seen');

                }
            })
        }


    });


    /**
     * End support_messages seen
     */





    $("body").on("click",".show_data",function(){


        var url = $(this).data("url");
        var item_id = $(this).data("itemid");




        $.ajax({
            url : url,
            type: 'POST',
            data: {'_token': _token, 'item_id': item_id},
            success: function (data) {

                var html_tags ="";
                $.each(data,function(index, value){

                    if (typeof (value) === "object"){

                        console.log(index, value);
                    }
                    html_tags+='<div class="row">';
                    html_tags+='<div class="col-md-6">';
                    html_tags+='<p style="text-transform: capitalize; color: black; font-weight: bold">'+ index.split('_').join(' ') +' : </p>';
                    html_tags+='</div>';
                    html_tags+='<div class="col-md-6">';
                    html_tags+='<p>'+value+'</p>';
                    html_tags+='</div>';
                    html_tags+='</div><hr style="margin-top:0;">';
                });

                $("#showDataModal .modal-body #data").html(html_tags);
                $("#showDataModal").modal("show");

                return false;


            }
        });







    });




});
