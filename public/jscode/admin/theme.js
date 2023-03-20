var nav_item_to_active;
var load_datatables;
var load_popover;

$(function () {


    nav_item_to_active=function () {

        var link_active = false;
        $(".nav-item.active").removeClass("active");
        $(".sidebar-nav-link.active").removeClass("active");

        $.each($(".sidebar-nav-item a"),function(i,v){

            if($(this).attr("href") == location.href){

                $(this).parents(".sidebar-nav-item").find('.sidebar-nav-link').addClass("active");
                $(this).addClass("active");

                link_active = true;
                return false;
            }
            else{
                $(this).parents(".sidebar-nav-item").find('.sidebar-nav-link').removeClass("active");
                $(this).removeClass("active");
            }

        });

        if(link_active == false){
            $.each($(".sidebar-nav-item a"),function(i,v){

                var current_location    = location.href;
                current_location        = current_location.split("/");

                if(current_location.length == 0)return;
                delete current_location[current_location.length-1];
                current_location = current_location.join("/");
                current_location = current_location.substring(0, current_location.length-1);

                if(current_location == $(this).attr("href")){

                    $(this).parents(".sidebar-nav-item").find('.sidebar-nav-link').addClass("active");
                    $(this).addClass("active");

                    link_active = true;
                    return false;
                }
                else{
                    $(this).parents(".sidebar-nav-item").find('.sidebar-nav-link').removeClass("active");
                    $(this).removeClass("active");
                }
            });
        }

        if(link_active == false){
            var current_location = location.protocol + '//' + location.host + location.pathname;

            $.each($(".sidebar-nav-item a"),function(i,v){

                if($(this).attr("href") == current_location){

                    $(this).parents(".sidebar-nav-item").find('.sidebar-nav-link').addClass("active");
                    $(this).addClass("active");

                    link_active = true;
                    return false;
                }
                else{
                    $(this).parents(".sidebar-nav-item").find('.sidebar-nav-link').removeClass("active");
                    $(this).removeClass("active");
                }

            });
        }


        /**
         * End set nav item to active
         */


        /**
         * Start set nav item to active (wide)
         */

        var wide_link_active = false;

        $.each($(".nav-item a"),function(i,v){

            if($(this).attr("href") == location.href){

                $(this).parents(".nav-item").addClass("active");

                wide_link_active = true;
                return false;
            }
            else{
                $(this).parents(".nav-item").removeClass("active");
            }

        });

        if(wide_link_active == false){
            $.each($(".nav-item a"),function(i,v){

                var current_location    = location.href;
                current_location        = current_location.split("/");

                if(current_location.length == 0)return;
                delete current_location[current_location.length-1];
                current_location = current_location.join("/");
                current_location = current_location.substring(0, current_location.length-1);

                if(current_location == $(this).attr("href")){

                    $(this).parents(".nav-item").addClass("active");

                    wide_link_active = true;
                    return false;
                }
                else{
                    $(this).parents(".nav-item").removeClass("active");
                }
            });
        }

        if(wide_link_active == false){
            var current_location = location.protocol + '//' + location.host + location.pathname;

            $.each($(".nav-item a"),function(i,v){

                if($(this).attr("href") == current_location){

                    $(this).parents(".nav-item").addClass("active");

                    wide_link_active = true;
                    return false;
                }
                else{
                    $(this).parents(".nav-item").removeClass("active");
                }

            });
        }



    };

    addToCallAtLoadArr("nav_item_to_active");

    // customize sub menu width
    $.each($(".sub-item"),function(){
        if($(this).parents(".nav-item").width() < 200) return;
        $(this).css("width",$(this).parents(".nav-item").width());
    });

    /**
     * Start NavIcon toggle
     */

    $('#managerNavicon').on('click', function(e) {
        e.preventDefault();

        $('.manager-left').toggleClass('d-block');
        $('.manager-right').toggleClass('d-none');
    });

    /**
     * End NavIcon toggle
     */


    load_datatables=function () {
        if($('#datatable1').length)
        {
            $('#datatable1').DataTable({
                responsive: true,
                "aaSorting": [],
                language: {
                    searchPlaceholder: 'Search',
                    sSearch: '',
                    lengthMenu: '_MENU_ Row/Page',
                }
            });
        }

        if($('#datatable2').length)
        {
            $('#datatable2').DataTable({
                bLengthChange: false,
                searching: false,
                bPaginate: false,
                bInfo: false,
                responsive: true,
                "aaSorting": []
            });
        }
    };
    addToCallAtLoadArr("load_datatables");


    /**
     * Start contactNavicon settings
     */

    $('#contactNavicon').on('click', function(e) {
        e.preventDefault();

        $('.contact-left').toggleClass('d-block');
        $('.contact-right').toggleClass('d-none');
    });

    /**
     * End contactNavicon settings
     */



    /**
     * Start remove_slider_file
     */

    $('body').on('click', '.remove_slider_file', function () {

        var this_element = $(this);
        var check_count  = $('.remove_slider_file').length;

        if(check_count > 1)
        {
            this_element.parents('.item').remove();
        }
        else{
            $('#errorsModal').modal('show');
            $('#errorsModal').find('.display_errors_msg').html("");
        }

        return false;
    });

    /**
     * End remove_slider_file
     */


    /**
     * Start popover settings
     */
    load_popover = function(){

        if($('[data-toggle="popover"]').length)
        {

            // $('[data-toggle="popover"]').popover();

            $('[data-popover-color="primary"]').popover({
                template: '<div class="popover popover-primary" role="tooltip">' +
                    '<div class="arrow"></div>' +
                    '<h3 class="popover-header"></h3>' +
                    '<div class="popover-body"></div>' +
                    '</div>'
            });

            $(document).on('click', function (e) {
                $('[data-toggle="popover"],[data-original-title]').each(function () {
                    //the 'is' for buttons that trigger popups
                    //the 'has' for icons within a button that triggers a popup
                    if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                        (($(this).popover('hide').data('bs.popover')||{}).inState||{}).click = false  // fix for BS 3.3.6
                    }

                });
            });

        }

    };
    addToCallAtLoadArr("load_popover");

    /**
     * End popover settings
     */

    /**
     * Start switch toggle settings
     */

    if($('.switch_toggle').length)
    {

        $('body').on("click",".switch_toggle",function () {

            var dark_mode = $(this).attr('data-dark_mode');

            if(typeof dark_mode == "undefined")
            {
                dark_mode = "off";
            }

            var obj = {};
            obj._token      = _token;
            obj.dark_mode   = dark_mode;

            $.ajax({
                url:base_url2+"/admin/theme/dark_mode",
                type:"POST",
                data:obj,
                success:function(data){
                    location.reload(true);
                }
            });

            return false;
        });

    }


    /**
     * End switch toggle settings
     */

});
