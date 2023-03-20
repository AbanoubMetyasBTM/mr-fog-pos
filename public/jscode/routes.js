var http_get_data;
var show_progress_bar;
var checkRefreshType;
var checkResetCaptcha;
var handleResponse;
var getRequestHeaders;

$(function () {

    // if user click at previous or next button at browser
    if (window.history && window.history.pushState) {
        $(window).on('popstate', function () {

            http_get_data(location.href);

            // location.reload();
        });
    }

    $(document).on('click', 'a[href^="mailto:"], a[href^="tel:"]', function (e) {
        e.preventDefault();
        var emailWindow = window.open($(e.currentTarget).attr('href'));

        // setTimeout(function () {
        //     emailWindow.close();
        // }, 500); // Is half a second long enough?
        //          // I don't know.
        //          // I'd set it as long as you can stand.

        return false;
    });

    checkRefreshType = function (data) {

        if (data.refresh != undefined && data.refresh === true) {

            console.log(data.refresh_after);
            setTimeout(function() {
                location.href = data.redirect;
            }, data.refresh_after*1000);

            return true;
        }

        localStorage.removeItem('flashMessage');

        http_get_data(data.redirect);

    };

    checkResetCaptcha = function (data) {

        if (!(data.reload_captcha != undefined && data.reload_captcha === true)) {
            return false;
        }

        resetGoogleReCaptcha();

    };

    show_progress_bar = function (percent) {

        $(".show_progress_bar").css('visibility', 'inherit');
        $(".show_progress_bar").css('width', percent + '%');

        if (percent >= 99) {
            setTimeout(function () {
                $(".show_progress_bar").css('visibility', 'hidden');
            }, 200);
            clearTimeout();
        }

    };

    getRequestHeaders = function () {

        var req = new XMLHttpRequest();
        req.open('GET', document.location, false);
        req.send(null);
        var headers = req.getAllResponseHeaders().toLowerCase();
        alert(headers);

    }

    handleResponse = function (href, data) {

        if (isJson(data)) {

            if (data.msg !== undefined) {
                show_flash_message("info", data.msg);
            }

            if (data.error !== undefined) {
                show_flash_message("error", data.error);
                $(".ajax_page_loader").hide();
                return false;
            }

            checkRefreshType(data);
            return false;
        }

        $(".load_ajax_content").html(data);

        if ($(".do_not_ajax_class").length) {
            location.href = location.href;
            return;
        }

        window.history.pushState(data, 'Title', href);

        call_at_load();
        $(".ajax_page_loader").hide();
        $(".modal-backdrop").remove();
        $("body").removeClass("modal-open");

        $("title").text($(".get_ajax_title").val());

        $("html, body").animate({scrollTop: 0}, "slow");

    };


    http_get_data = function (href) {

        if(!href.includes("admin")){
            location.href= href;
            return;
        }

        if(href === undefined){
            return false;
        }

        if (
            href.includes("do_not_ajax")
        ) {
            location.href = href;
            return true;
        }

        $(".ajax_page_loader").show();
        var object = {
            "load_inner": true
        };

        if (socket != undefined && $(".disable_socket_as_route").length == 0) {
            socket.emit("html_page_is_wanted", href, null)
        }
        else {
            $.ajax({
                url: href,
                type: 'GET',
                data: object,
                error: function (request, status, error) {

                    var msg = "page not found";
                    if (request.status != 404) {
                        msg = "error";
                    }

                    show_flash_message("error", msg);
                    $(".ajax_page_loader").hide();

                },
                success: function (data) {
                    handleResponse(href, data);
                }
            });
        }

    };

    if (typeof socket !== 'undefined'){
        socket.on("html_page_is_done", function (data) {
            handleResponse(data.href, data);
        });
    }


    $("body").on("click", ".ask_before_go", function () {

        var this_element = $(this);

        Swal.fire({
            title: $(".sweet_alert_confirmation_msg").val(),
            showDenyButton: true,
            confirmButtonText: $(".sweet_alert_confirmation_yes").val(),
            denyButtonText: $(".sweet_alert_confirmation_no").val(),
        }).then((result) => {
            if (result.isConfirmed) {

                if (this_element[0].nodeName == "BUTTON") {
                    this_element.parents("form").submit();
                }
                else if (this_element[0].nodeName == "A") {
                    http_get_data(this_element.attr("href"));
                }

            }
        });

        return false;

    });

    var aTagReturnFalseChecks = function (thisElement) {

        return (
            (
                thisElement.attr("class") !== undefined &&
                thisElement.attr("class").includes("ask_before_go")
            ) ||
            thisElement.attr("href") == "#" ||
            thisElement.attr("href") == undefined ||
            thisElement.attr("href").length == 0
        );

    };

    var aTagReturnTrueChecks = function (thisElement) {
        return (
            thisElement.attr("href").charAt(0) == "#" ||
            (
                thisElement.attr("class") != undefined &&
                (
                    thisElement.attr("class").includes("cke_button") ||
                    thisElement.attr("class").includes("cke_combo_button") ||
                    thisElement.attr("class").includes("do_not_ajax")
                )
            ) ||
            (
                thisElement.attr("target") != undefined &&
                thisElement.attr("target").includes("_blank")
            ) ||

            thisElement.attr("href").includes("do_not_ajax")
        );
    };


    $("body").on("click", "a", function (event) {

        var thisElement = $(this);

        if (
            thisElement.attr("class") != undefined &&
            thisElement.attr("class").includes("do_not_ajax")
        ) {
            return true;
        }


        if (aTagReturnFalseChecks(thisElement)) {
            return false;
        }

        if (aTagReturnTrueChecks(thisElement)) {
            return true;
        }


        if (event.ctrlKey || event.shiftKey) {
            return true;
        }

        http_get_data(thisElement.attr("href"));

        return false;
    });

    $("body").on("click", "a.accumulate_to_current_link", function (event) {

        var thisElement        = $(this);
        var currentUrl         = window.location.href.split('?')[0];
        var currentQueryString = window.location.search;

        if (currentQueryString == "") {
            currentQueryString = "?" + thisElement.data("href");
        }
        else {
            currentQueryString += "&" + thisElement.data("href");
        }

        location.href = currentUrl + currentQueryString;

        return false;

    });

    $("body").on("keyup keydown", "form.ajax_form.disable_enter_submit input", function (event) {

        if(event.which == 13){
            return false;
        }

    });


    $("body").on("submit", "form.ajax_form", function (event) {

        var formElement = $(this);

        if (formElement.attr("method") == "GET" || formElement.attr("method") == "" || formElement.attr("method") == undefined) {
            http_get_data(formElement.attr("action") + "?" + formElement.serialize());
            return false;
        }

        var formData     = new FormData($(this)[0]);
        var submitButton = formElement.find(":submit");

        if (event.originalEvent !== undefined) {
            formData.append($(event.originalEvent.submitter).attr("name"), $(event.originalEvent.submitter).attr("value"));
            submitButton = $(event.originalEvent.submitter);
        }

        if (typeof (imgs_input) != "undefined") {
            $.each(imgs_input_names, function (input_index, input_name) {

                if (imgs_input[input_name] == undefined) {
                    return true;
                }

                $.each(imgs_input[input_name].files, function (file_index, val) {
                    if (deleted_files_indices[input_name].indexOf(file_index) >= 0) {
                        return true;
                    }

                    formData.append(input_name + "[]", imgs_input[input_name].files[file_index]);
                });

            });
        }


        if ($('[data-is_default="true"]').length > 0) {
            submitButton = $('[data-is_default="true"]');

            if ($('[data-is_default="false"]').length > 0) {
                $('[data-is_default="false"]').attr('disabled', 'disabled');
            }

        }

        if (submitButton.length) {
            submitButton.attr('disabled', 'disabled');
            submitButton.append("  " + ajax_loader_img_func("10px"));
        }

        $.ajax({
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        show_progress_bar(Math.round((evt.loaded / evt.total) * 100));
                    }
                }, false);

                xhr.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        show_progress_bar(Math.round((evt.loaded / evt.total) * 100));
                    }
                }, false);
                return xhr;
            },
            url: formElement.attr("action"),
            type: 'POST',
            cache: false,
            processData: false,
            contentType: false,
            data: formData,
            error: function (request, status, error) {

                if (submitButton.length) {
                    submitButton.removeAttr('disabled');
                    submitButton.find('img').remove();
                }

                if ($('[data-is_default="false"]').length > 0) {
                    $('[data-is_default="false"]').removeAttr('disabled');
                }

                var msg = "page not found";
                if (request.status != 404) {
                    msg = "error";
                }

                show_flash_message("error", msg);

            },
            success: function (data) {

                if (submitButton.length) {
                    submitButton.removeAttr('disabled');
                    submitButton.find('img').remove();
                }

                checkResetCaptcha(data);


                if ($('[data-is_default="false"]').length > 0) {
                    $('[data-is_default="false"]').removeAttr('disabled');
                }

                if (data.error != undefined) {
                    show_flash_message("error", data.error);
                }
                else {
                    console.log(data);
                    if (data.msg.length > 0) {
                        show_flash_message("info", data.msg);
                        localStorage.setItem('flashMessage', data.msg);
                    }

                    checkRefreshType(data);

                    imgs_input_names      = [];
                    imgs_input            = {};
                    deleted_files_indices = {};
                }
            }
        });

        return false;
    });


});
