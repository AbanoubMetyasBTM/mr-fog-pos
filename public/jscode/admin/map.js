var cityCircle;
var initMap;

$(function () {


    var pos = {
        lat: 29.9809061,
        lng: 31.318960899999997
    };

    var map;

    // Try HTML5 geolocation.
    initMap = function () {

        // not load map if not on page edit shop
        if (!($("#shop_map").length > 0)) {
            return;
        }

        // get current location of user by GPS
        pos = {
            lat: pos.lat,
            lng: pos.lng
        };


        // get old shop directions
        var shop_lat;
        var shop_lng;

        shop_lat = $('.lat').val();
        shop_lng = $('.lng').val();

        // check if shop directions is found or not
        if (shop_lat > 0 && shop_lng > 0) {

            pos.lat = shop_lat;
            pos.lng = shop_lng;
            // console.log('shop_lat' + shop_lat);
            // console.log('shop_lng' + shop_lng);
        }

        // load the map and set your coordinates
        map = new google.maps.Map(document.getElementById('shop_map'), {
            center: new google.maps.LatLng(pos.lat, pos.lng),
            zoom: 10
        });

        // Start Add Search box
        // Create the search box and link it to the UI element.

        var input;
        var searchBox;

        if($(".hide_search_box").length==0)
        {
            input = document.getElementById('pac-input');
            searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

            searchBox.addListener('places_changed', function () {
                var places = searchBox.getPlaces();

                var markers = [];

                if (places.length == 0) {
                    return;
                }

                // Clear out the old markers.
                markers.forEach(function (marker) {
                    marker.setMap(null);
                });

                // For each place, get the icon, name and location.
                var bounds = new google.maps.LatLngBounds();
                places.forEach(function (place) {

                    change_position(place.geometry.location);

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });

            // Bias the SearchBox results towards current map's viewport.
            map.addListener('bounds_changed', function () {
                searchBox.setBounds(map.getBounds());
            });
            // End Add Search box
        }

        // set marker to your previous location
        placeMarker(new google.maps.LatLng(pos.lat, pos.lng));

        // set your text input values
        // $(".lat").val(pos.lat);
        // $(".lng").val(pos.lng);

        // set your new pos by marker and
        google.maps.event.addListener(map, 'click', function (event) {
            change_position(event.latLng);
        });



    };
    addToCallAtLoadArr("initMap");


    // definition function of marker
    function placeMarker(location) {
        marker = new google.maps.Marker({
            position: location,
            map: map
        });

        map.setCenter(location);

        if($("#deliver_in_range_id").length){
            cityCircle = new google.maps.Circle({
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#FF0000',
                fillOpacity: 0.35,
                map: map,
                center: location,
                radius: $("#deliver_in_range_id").val()*1000
            });

            google.maps.event.addListener(cityCircle, 'click', function(ev) {

                change_position(ev.latLng);

            });
        }
    }

    $("body").on("change keyup","#deliver_in_range_id",function(){

        cityCircle.setRadius($(this).val()*1000);

    });

    google.maps.event.addDomListener(window, "load", initMap);

    var change_position = function(latlng){

        marker.setPosition(latlng);
        if(cityCircle != undefined)
        {
            cityCircle.setCenter(latlng);
        }

        $(".lat").val(latlng.lat);
        $(".lng").val(latlng.lng);
    };

    $('form').keypress(function(e){
        if(e.which === 13){
            return false;
        }
    });

});

