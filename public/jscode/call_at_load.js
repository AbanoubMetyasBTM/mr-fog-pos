var call_at_load;
var callAtLoadDynamicArr = [];
var addToCallAtLoadArr;

$(function () {

    addToCallAtLoadArr = function (methodName) {
        callAtLoadDynamicArr.push(methodName);

        if (window[methodName] != undefined) {
            window[methodName]();
        }
    }

    call_at_load = function () {

        if (callAtLoadDynamicArr.length) {

            $.each(callAtLoadDynamicArr, function (i, methodName) {
                if (window[methodName] != undefined) {
                    window[methodName]();
                }
            });

        }

    };

    call_at_load();

});
