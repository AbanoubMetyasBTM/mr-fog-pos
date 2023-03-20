var change_mail_type;

$(function () {

    /**
     * Start verification settings
     */

    $('body').on('change', '.change_mail_type', function () {

        var this_element = $(this);
        var current_val = this_element.val();

        if (typeof current_val != "undefined") {

            if (current_val == "mail") {

                $('.smtp_settings_div').hide();

            }
            else if (current_val == "smtp") {
                $('.smtp_settings_div').show();
            }

        }


        return false;
    });

    change_mail_type = function () {
        if ($('.change_mail_type').length) {
            $('.change_mail_type').change();
        }
    };
    addToCallAtLoadArr("change_mail_type");


    /**
     * End verification settings
     */


});
