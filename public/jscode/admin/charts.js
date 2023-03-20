google.charts.load('current', {'packages':['corechart']});

var googleChartsIsLoadedRefreshPage;

$(function () {

    var drawAreaChart = function (json_data,title,ElementId) {

        var arr_data  = [
            ['', 'اﻻرباح']
        ];

        console.log(json_data);

        $.each(json_data,function(i,v){
            arr_data.push([i.toString(),parseFloat(v)]);
        });

        console.log('arr_data',arr_data);

        var data = google.visualization.arrayToDataTable(arr_data);

        var options = {
            title: title,
            vAxis: {minValue: 0},
            pointSize: 10,
        };

        var chart = new google.visualization.AreaChart(document.getElementById(ElementId));
        chart.draw(data, options);
    };

    var drawBarChart = function(json_data,title,ElementId){

        var arr_data  = [
            ["", "العدد"]
        ];

        $.each(json_data,function(i,v){
            arr_data.push([i,parseFloat(v)]);
        });

        console.log('arr_data', arr_data);

        var data = google.visualization.arrayToDataTable(arr_data);

        var view = new google.visualization.DataView(data);
        view.setColumns([
            0,
            1,
            {
                calc: "stringify",
                sourceColumn: 1,
                type: "string",
                role: "annotation"
            }
        ]);

        var options = {
            title: title,
            bar: {groupWidth: "25%"},
            legend: { position: "none" },
        };
        var chart = new google.visualization.ColumnChart(document.getElementById(ElementId));
        chart.draw(view, options);

    };

    var drawBirChart = function (json_data,title,ElementId) {

        var arr_data  = [
            ["", ""]
        ];

        $.each(json_data,function(i,v){
            arr_data.push([i,parseFloat(v)]);
        });

        var data = google.visualization.arrayToDataTable(arr_data);

        var options = {
            title: title,
            is3D: true
        };

        var chart = new google.visualization.PieChart(document.getElementById(ElementId));

        chart.draw(data, options);

    };

    googleChartsIsLoaded = function (){

        //drawAreaChart($("#daily_orders_revenue_chart").data("value"),'اﻻرباح اليومية',"daily_orders_revenue_chart");
        if($(".draw_area_chart").length){
            $.each($(".draw_area_chart"),function(){
                drawAreaChart($(this).data("value"),$(this).data("title"),$(this).attr("id"));
            });
        }

        if($(".draw_bar_chart").length){
            $.each($(".draw_bar_chart"),function(){
                drawBarChart($(this).data("value"),$(this).data("title"),$(this).attr("id"));
            });
        }


        if($(".draw_bie_chart").length){
            $.each($(".draw_bie_chart"),function(){
                drawBirChart($(this).data("value"),$(this).data("title"),$(this).attr("id"));
            });
        }

    };

    googleChartsIsLoadedRefreshPage = function(){
        google.charts.setOnLoadCallback(googleChartsIsLoaded);
    };
    addToCallAtLoadArr("googleChartsIsLoadedRefreshPage");

});
