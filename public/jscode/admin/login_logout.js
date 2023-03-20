$(function () {

    $('body').on("click",".add_login_logout",function () {

        var thisElement = $(this);
        var timeType = thisElement.attr("data-value");
        var parent = $(this).parents('.day_row');

        thisElement.parent().html('' +
            '<input ' +
            'type="time" '+
            'name="0" '+
            'class="edit_login_logout"' +
            'data-row_id="'+ parent.attr('id')+'" '+
            'data-time_type="'+ timeType +'" '+
            '/>');

    });

    var editLoginLogoutFunc = function(thisElement){

        var rowId = thisElement.attr("data-row_id");
        var timeValue = thisElement.val();
        var inputName = thisElement.attr("name");
        var timeType = thisElement.attr("data-time_type");
        var url = base_url2 + '/admin/employee-hr/employee-login-logout/edit-login-logout';


        thisElement.attr("disabled","disabled");

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                '_token': _token,
                'item_id': rowId,
                'time_type': timeType,
                'time_index' : inputName,
                'value': timeValue
            },
            success: function (data) {


                thisElement.removeAttr("disabled");

                if (data.length === 0){
                    http_get_data(location.href);
                    return;
                }
                else {
                    var returned_data = JSON.parse(data);

                    if (typeof (returned_data.error)!= "undefined") {
                        show_flash_message("error",returned_data.error);
                    }
                }

            }
        });

    };

    $('body').on("keyup",".edit_login_logout",function (event) {
        var thisElement = $(this);
        if (event.which==13) {
            editLoginLogoutFunc(thisElement);
        }
    });

    $('body').on("focusout",".edit_login_logout",function (event) {
        var thisElement = $(this);

        if(thisElement.data("old_value")==thisElement.val()){
            return false;
        }

        editLoginLogoutFunc(thisElement);
    });



});
