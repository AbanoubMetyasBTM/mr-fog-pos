var convertSelectToBTMSelect2;
var populateBTMSelect2FromCache;
var btmSelect2Socket;

$(function () {

    if (typeof io !== 'undefined' && $(".btm_select2_socket_link").val().length > 0) {
        btmSelect2Socket = io.connect($(".btm_select2_socket_link").val());
    }


    $(document).mouseup(function (e) {
        var container = $(".btm_select_2_parent_div.active");

        // if the target of the click isn't the container nor a descendant of the container
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.removeClass("active");
        }
    });

    //select option from list
    $("body").on("click", ".btm_select_2_parent_div .result_row", function () {

        var thisElement   = $(this);
        var parentElement = thisElement.parents(".btm_select_2_parent_div");
        var allowCache    = $(".btm_select_2", parentElement).attr("data-allow_cache");

        $(".btm_select_2_display_selected_text", parentElement).val(thisElement.text().trim());
        $(".btm_select_2_selected_value", parentElement).val(thisElement.data("value")).trigger('change');
        $(".btm_select_2_selected_obj", parentElement).val(JSON.stringify(thisElement.data("obj")));

        if (allowCache == "true") {

            localStorage.setItem(
                $(".btm_select_2_selected_value", parentElement).attr("name") + "_selected_text",
                $(this).text().trim()
            );

            localStorage.setItem(
                $(".btm_select_2_selected_value", parentElement).attr("name") + "_selected_value",
                $(this).data("value")
            );

            localStorage.setItem(
                $(".btm_select_2_selected_value", parentElement).attr("name") + "_selected_obj",
                JSON.stringify(thisElement.data("obj"))
            );

        }

        parentElement.removeClass("active");

        if (
            $(".btm_select_2", parentElement).data("run_after_select") !== undefined &&
            $(".btm_select_2", parentElement).data("run_after_select").length > 0
        ) {
            window[$(".btm_select_2", parentElement).data("run_after_select")](parentElement);
        }

    });

    $("body").on("click", ".btm_select_2_parent_div .btm_select_2_display_selected_text", function () {

        var parentElement = $(this).parents(".btm_select_2_parent_div");
        parentElement.addClass("active");

        $(".btm_select_2", parentElement).focus();
        $(".btm_select_2", parentElement).select();

    });

    var filterStaticList = function (thisElement) {

        var parentElement = thisElement.parents(".btm_select_2_parent_div");
        var searchKeyword = thisElement.val().toLowerCase();

        $(".result_row", parentElement).each(function () {

            $(this).hide();
            if ($(this).text().toLowerCase().includes(searchKeyword)) {
                $(this).show();
            }

        });


    };

    var filterDynamicListBySocket = function (thisElement) {

        var uniqueId = thisElement.attr("data-unique_id");

        if (uniqueId == undefined) {
            var rand     = Math.random().toString(36).substring(7);
            var day      = new Date();
            var time     = day.getTime();
            var uniqueId = rand + time + "_class";

            thisElement.attr("data-unique_id", uniqueId);
        }

        var requestData                                      = {};
        requestData[thisElement.attr("data-search_keyword")] = thisElement.val();
        requestData["element_class"]                         = uniqueId;

        var params = new URLSearchParams(requestData);
        var url    = thisElement.attr("data-url");
        if (thisElement.attr("data-action_type") == "GET") {
            url = url + "?" + params;
        }

        btmSelect2Socket.emit("btm_select_2_search", url, requestData)

    };

    if (typeof btmSelect2Socket !== 'undefined') {
        btmSelect2Socket.on("btm_select_2_search_is_done", function (element_class, data) {

            var thisElement   = $('[data-unique_id="' + element_class + '"]');
            var parentElement = thisElement.parents(".btm_select_2_parent_div");

            handleRequestData(data, thisElement, parentElement);

        });
    }

    var select2xhr;
    var filterDynamicListByAjax = function (thisElement) {

        var parentElement = thisElement.parents(".btm_select_2_parent_div");

        if (select2xhr) {
            select2xhr.abort();
        }

        var requestData                                      = {};
        requestData[thisElement.attr("data-search_keyword")] = thisElement.val();

        if(thisElement.attr("data-append_data_from_dom_by_classes") && thisElement.attr("data-append_data_from_dom_by_classes").length > 0){
            var appendCLassesData = JSON.parse(thisElement.attr("data-append_data_from_dom_by_classes"));

            for(var key in appendCLassesData){
                requestData[key] = $(appendCLassesData[key]).val()
            }

        }

        var url    = new URL(thisElement.attr("data-url"));

        if (thisElement.attr("data-action_type") == "GET") {
            $.each(requestData, function(i, v){
                url.searchParams.append(i, v);
            });
        }


        select2xhr = $.ajax({
            url: url.href,
            type: thisElement.attr("data-action_type"),
            cache: false,
            processData: false,
            contentType: false,
            data: requestData,
            success: function (data) {

                handleRequestData(data, thisElement, parentElement);

            }
        });

    };

    var handleRequestData = function (data, thisElement, parentElement) {

        if(data.Data !== undefined){
            data = data.Data;
        }
        else if(data.original !== undefined){
            data = data.original.Data;
        }
        else{
            data = JSON.parse(data);
        }

        if(data === null){
            return false;
        }

        $(".filter_results", parentElement).removeClass("add_height_limitation");
        if (data.totalRecords > 3) {
            $(".filter_results", parentElement).addClass("add_height_limitation");
        }

        $(".result_rows", parentElement).html("");

        $.each(data.results, function (i, item) {

            var value_field_name = thisElement.attr("data-value_field_name").split(".");
            var optionValue      = item[value_field_name[0]];
            if (value_field_name.length > 1) {
                for (var i = 1; i < value_field_name.length; i++) {
                    optionValue = optionValue[value_field_name[i]];
                }
            }

            var itemJson = JSON.stringify(item).replace(/[']/g, "");

            $(".result_rows", parentElement).append(
                '<div class="result_row" ' + "data-obj='" + itemJson + "'" + ' data-value="' + optionValue + '">' +
                item[thisElement.attr("data-text_field_name")] +
                '</div>'
            );
        });

        $(".result_rows .result_row", parentElement).first().addClass("active");

    }

    var typingTimer;                //timer identifier
    var doneTypingInterval = 300;  //time in ms (5 seconds)

    $("body").on("keydown keyup", ".btm_select_2_parent_div .btm_select_2", function (event) {

        var parentElement = $(this).parents(".btm_select_2_parent_div");

        if (event.type == "keydown") {

            //user pressed enter
            if (event.which === 13) {
                $(".result_rows .result_row.active", parentElement).click();

                return false;
            }

            //move down
            if (event.which === 40) {

                var currentIndex = $(".result_rows .result_row.active", parentElement).index();
                if (currentIndex + 1 < $(".result_rows .result_row", parentElement).length) {
                    $(".result_rows .result_row", parentElement).eq(currentIndex + 1).addClass("active");
                    $(".result_rows .result_row.active", parentElement).first().removeClass("active");


                    var $container = $('.filter_results', parentElement),
                        $scrollTo  = $(".result_rows .result_row.active", parentElement);

                    $container.scrollTop(
                        $scrollTo.offset().top - $container.offset().top + $container.scrollTop() - 100
                    );

                }

                return false;
            }

            //move up
            if (event.which === 38) {

                var currentIndex = $(".result_rows .result_row.active", parentElement).index();
                if (currentIndex - 1 >= 0) {
                    $(".result_rows .result_row", parentElement).eq($(".result_rows .result_row.active", parentElement).index() - 1).addClass("active");
                    $(".result_rows .result_row.active", parentElement).last().removeClass("active");

                    var $container = $('.filter_results', parentElement),
                        $scrollTo  = $(".result_rows .result_row.active", parentElement);

                    $container.scrollTop(
                        $scrollTo.offset().top - $container.offset().top + $container.scrollTop() - 100
                    );
                }

                return false;
            }

        }

        var thisElement = $(this);

        if ($(this).val().length == 0) {
            return true;
        }

        if (
            thisElement.data("min_search_chars") !== undefined &&
            thisElement.data("min_search_chars") > $(this).val().length
        ) {
            return true;
        }

        clearTimeout(typingTimer);
        if ($(this).val()) {
            typingTimer = setTimeout(function () {

                if (
                    event.which === 13 ||
                    event.which === 40 ||
                    event.which === 38
                ){
                    return true;
                }

                if (thisElement.data("static_list") === true) {
                    return filterStaticList(thisElement);
                }

                if (btmSelect2Socket !== undefined) {
                    return filterDynamicListBySocket(thisElement);
                }

                return filterDynamicListByAjax(thisElement);

            }, doneTypingInterval);
        }

    });

    var getElementDataField = function (thisElement, key) {

        if (thisElement.attr(key) == undefined) {
            return undefined;
        }

        return thisElement.attr(key);

    }

    var _convertSelectToBTMSelect2AfterLoadHtml = function (basicHtml, thisElement) {

        var select2Html = basicHtml;
        var select2Html = $('<div />', {html: select2Html});
        var options     = $("option", thisElement);

        if (getElementDataField(thisElement, "data-allow_cache") !== undefined) {
            $(".btm_select_2", select2Html).attr("data-allow_cache", getElementDataField(thisElement, "data-allow_cache"));
        }

        if (getElementDataField(thisElement, "data-placeholder") !== undefined) {
            $(".btm_select_2_display_selected_text", select2Html).attr("placeholder", getElementDataField(thisElement, "data-placeholder"));
            $(".btm_select_2", select2Html).attr("placeholder", getElementDataField(thisElement, "data-placeholder"));
        }

        if (
            getElementDataField(thisElement, "data-class") !== undefined &&
            getElementDataField(thisElement, "data-class").length > 0
        ) {
            $(".btm_select_2_selected_value", select2Html).addClass(getElementDataField(thisElement, "data-class"));
        }


        $(".btm_select_2_selected_value", select2Html).attr("name", thisElement.attr("name"));
        $(".btm_select_2_selected_value", select2Html).val(thisElement.val());
        $(".btm_select_2_display_selected_text", select2Html).val($("option:selected", thisElement).text().trim());

        if (options.length > 0) {
            $(".btm_select_2", select2Html).attr("data-static_list", "true");
        }
        else {

            $(".btm_select_2", select2Html).attr("data-static_list", "false");

            var checkList = [
                "url", "min_search_chars", "min_search_chars_msg", "search_keyword", "action_type",
                "value_field_name", "text_field_name",
                "pre_selected_value", "pre_selected_text",
                "run_after_select", "hide_label",
                "append_data_from_dom_by_classes"
            ];

            $.each(checkList, function (i, v) {
                if (getElementDataField(thisElement, "data-" + v) !== undefined) {
                    $(".btm_select_2", select2Html).attr("data-" + v, getElementDataField(thisElement, "data-" + v));
                }
            });

        }

        //show min_search_chars_msg at result_rows section
        if (
            getElementDataField($(".btm_select_2", select2Html), "data-min_search_chars_msg") !== undefined &&
            getElementDataField($(".btm_select_2", select2Html), "data-static_list") !== "true"
        ) {
            var minSearchCharsMsg = getElementDataField($(".btm_select_2", select2Html), "data-min_search_chars_msg");
            minSearchCharsMsg     = minSearchCharsMsg.replace("CHAR_NUM", getElementDataField($(".btm_select_2", select2Html), "data-min_search_chars"));
            $(".result_rows", select2Html).html("<p>" + minSearchCharsMsg + "</p>");
        }

        if (options.length > 3) {
            $(".filter_results", select2Html).addClass("add_height_limitation");
        }

        //get selected value and selected text from localstorage
        if ($(".btm_select_2", select2Html).attr("data-allow_cache") == "true") {

            populateBTMSelect2FromCache(
                select2Html,
                thisElement.attr("name")
            );

        }

        //check if btm_select_2_selected_value is empty and there is a value at data-pre_selected_value
        if (
            $(".btm_select_2_selected_value", select2Html).val() == "" &&
            $(".btm_select_2", select2Html).attr("data-pre_selected_value") !== undefined &&
            $(".btm_select_2", select2Html).attr("data-pre_selected_value").length > 0
        ) {
            $(".btm_select_2_selected_value", select2Html).val($(".btm_select_2", select2Html).attr("data-pre_selected_value"));
            $(".btm_select_2_display_selected_text", select2Html).val($(".btm_select_2", select2Html).attr("data-pre_selected_text"));
        }

        var fieldName = $(".btm_select_2_selected_value", select2Html).attr("name");

        $(".btm_select_2_selected_value", select2Html).addClass("btm_select_2_selected_value_" + fieldName);
        $(".btm_select_2_display_selected_text", select2Html).addClass("btm_select_2_display_selected_text_" + fieldName);
        $(".btm_select_2", select2Html).addClass("btm_select_2_" + fieldName);

        options.each(function () {
            $(".result_rows", select2Html).append('<div class="result_row" data-value="' + $(this).attr("value") + '">' + $(this).text() + '</div>');
        });

        if(thisElement.attr("data-hide_label") === "0"){
            thisElement.replaceWith(select2Html);
        }
        else{
            thisElement.parents(".form-group").replaceWith(select2Html);
        }

        if (
            $(".btm_select_2", select2Html).data("run_after_select") !== undefined &&
            $(".btm_select_2", select2Html).data("run_after_select").length > 0
        ) {
            setTimeout(function () {
                window[$(".btm_select_2", select2Html).data("run_after_select")](select2Html);
            }, 500);
        }

    };

    convertSelectToBTMSelect2 = function () {

        setTimeout(function(){
            $(".convert_select_to_btm_select2").each(function () {

                var thisElement = $(this);
                _convertSelectToBTMSelect2AfterLoadHtml($(".basic_btm_select_2_html").children().html(), thisElement);


            });
        },200);

    };
    addToCallAtLoadArr("convertSelectToBTMSelect2");

    populateBTMSelect2FromCache = function (parentElement, fieldname) {

        if (localStorage.getItem(fieldname + "_selected_value") != null) {
            $(".btm_select_2_selected_value", parentElement).val(localStorage.getItem(fieldname + "_selected_value"));
        }

        if (localStorage.getItem(fieldname + "_selected_value") != null) {
            $(".btm_select_2_selected_obj", parentElement).val(localStorage.getItem(fieldname + "_selected_obj"));
        }

        if (localStorage.getItem(fieldname + "_selected_text") != null) {
            $(".btm_select_2_display_selected_text", parentElement).val(localStorage.getItem(fieldname + "_selected_text"));
        }

    };


});
