<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- Start Map -->
<script src="https://maps.googleapis.com/maps/api/js?key=your-key-here"
    type="text/javascript"></script>
<section id="maps">
    <!-- sds: default location !-->
    <div id="map" data-position-latitude="14.987257" data-position-longitude="102.118503"></div>
</section>
<script>
<?php
    echo "var user_key = '".$_SESSION['user_key']."';";
    echo "var bike_key = '".$_SESSION['bike']."';";
    echo "var start_date = '".$_SESSION['start_date']."';";
    echo "var end_date = '".$_SESSION['end_date']."';";
?>
var nav_bar_active = 'nav-bar-maps';
</script>

<style>
html,
body {
    margin: 0;
    height: 100%;
    overflow: hidden
}
</style>

<script>
var map;

(function($) {
    $.fn.initMap = function(options) {

        var posLatitude = $('#map').data('position-latitude'),
            posLongitude = $('#map').data('position-longitude');

        var settings = $.extend({
            home: {
                latitude: posLatitude,
                longitude: posLongitude
            },
            text: '<div class="map-popup"><h4>Web Development | ZoOm-Arts</h4><p>A web development blog for all your HTML5 and WordPress needs.</p></div>',
            icon_url: $('#map').data('marker-img'),
            zoom: 13
        }, options);

        var coords = new google.maps.LatLng(settings.home.latitude, settings.home.longitude);

        return this.each(function() {

            var element = $(this);

            var options = {
                zoom: settings.zoom,
                center: coords,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                //mapTypeId: google.maps.MapTypeId.TERRAIN,
                //mapTypeId: google.maps.MapTypeId.HYBRID,
                mapTypeControl: true,
                scaleControl: true,
                streetViewControl: false,
                panControl: true,
                disableDefaultUI: true,
                zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.DEFAULT
                },
                scrollwheel: true,
                overviewMapControl: true,
            };

            map = new google.maps.Map(element[0], options);

            var icon = {
                //url: settings.icon_url,
                url: "https://labs.google.com/ridefinder/images/mm_20_green.png",
                origin: new google.maps.Point(0, 0)
            };

            /*
            var marker = new google.maps.Marker({
            	position: coords,
            	map: map,
            	icon: icon,
            	draggable: false
            });
            
            var info = new google.maps.InfoWindow({
            	content: settings.text
            });

            google.maps.event.addListener(marker, 'click', function() {
            	info.open(map, marker);
            });
            */

            var styles = [{
                "featureType": "landscape",
                "stylers": [{
                        "saturation": -100
                    },
                    {
                        "lightness": 65
                    },
                    {
                        "visibility": "on"
                    }
                ]
            }, {
                "featureType": "poi",
                "stylers": [{
                        "saturation": -100
                    },
                    {
                        "lightness": 51
                    },
                    {
                        "visibility": "simplified"
                    }
                ]
            }, {
                "featureType": "road.highway",
                "stylers": [{
                        "saturation": -100
                    },
                    {
                        "visibility": "simplified"
                    }
                ]
            }, {
                "featureType": "road.arterial",
                "stylers": [{
                        "saturation": -100
                    },
                    {
                        "lightness": 30
                    },
                    {
                        "visibility": "on"
                    }
                ]
            }, {
                "featureType": "road.local",
                "stylers": [{
                        "saturation": -100
                    },
                    {
                        "lightness": 40
                    },
                    {
                        "visibility": "on"
                    }
                ]
            }, {
                "featureType": "transit",
                "stylers": [{
                        "saturation": -100
                    },
                    {
                        "visibility": "simplified"
                    }
                ]
            }, {
                "featureType": "administrative.province",
                "stylers": [{
                    "visibility": "on"
                }]
            }, {
                "featureType": "water",
                "elementType": "labels",
                "stylers": [{
                        "visibility": "on"
                    },
                    {
                        "lightness": -25
                    },
                    {
                        "saturation": -100
                    }
                ]
            }, {
                "featureType": "water",
                "elementType": "geometry",
                "stylers": [{
                        "hue": "#ffff00"
                    },
                    {
                        "lightness": -25
                    },
                    {
                        "saturation": -97
                    }
                ]
            }];

            map.setOptions({
                styles: styles
            });
        });

    };
}(jQuery));

jQuery(document).ready(function() {
    jQuery('#map').initMap();
    getTrackData();
});

function getTrackData() {
    // sds: user key
    var customerKey = user_key;
    var json = Object();
    // sds: bike key
    json.bikeKeys = bike_key;

    // sds: scope of times
    json.dateBegin = start_date;
    json.dateEnd = end_date;
    var trackData = JSON.stringify(json);
    // alert(trackData);
    $.ajax({
        type: "POST",
        url: base_url + 'Api/track_ajax/',
        data: {
            act: 'get',
            customerKey: customerKey,
            trackData: trackData
        },
        dataType: "json",
        success: function(r) {
            //alert(r.code);
            if (r.code == 'OK') {
                placeLocationOnMap(r.data);
                //alert("Add/Update '"+name+"' successful");
            } else {
                //alert("Can't add/update '"+name+"': "+r.message);
                alert("Error: " + r.message);
            }
        },
        error: function(jqXHR, status, error) {
            //alert("Can't add/update '"+name+"': "+error);
            alert("Error: " + error);
        }
    });

    function placeLocationOnMap(data) {
        var customerKey = data.customerKey;
        //alert(customerKey);
        var cars = data.cars;

        var lastCarMovingPath = undefined;
        var clickedCarMovingPath = undefined;

        var openedInfo = undefined;

        jQuery.each(cars, function(carKey, locs) {
            //alert(carKey);
            var date = undefined;
            var lat = undefined;
            var lng = undefined;

            var carMovingCoordinates = [];
            var lastPath = undefined;

            var lastMarker = undefined;

            var startTime = undefined;
            var finishTime = undefined;
            var lastTime = new Date("January 01, 1990 00:00:01");

            jQuery.each(locs, function() {
                date = this.date
                lat = parseFloat(this.lat);
                lng = parseFloat(this.lng);

                if (startTime == undefined)
                    startTime = this.date;

                if (this.event != '1' && this.event != '11') {
                    if (this.event == '302') {
                        icon_url = "https://maps.gstatic.com/mapfiles/ridefinder-images/mm_20_red.png";
                        info_content = "<div class='map-popup'><h4>หยุดการติดตามรถ " + carKey +
                            "</h4><p>วันและเวลา : " + this.date + "</p><p>ตำแหน่ง : " + this.lat + "," + this
                            .lng + "</p></div>";
                    }
                    if (this.event == '301') {
                        icon_url =
                            "https://maps.gstatic.com/mapfiles/ridefinder-images/mm_20_green.png";
                        info_content = "<div class='map-popup'><h4>เริ่มการติดตามรถ " + carKey +
                            "</h4><p>วันและเวลา : " + this.date + "</p><p>ตำแหน่ง : " + this.lat + "," + this
                            .lng + "</p></div>";
                    }
                    if (this.event == '201') {
                        icon_url =
                            "https://maps.google.com/intl/en_us/mapfiles/ms/micons/red-dot.png";
                        info_content = "<div class='map-popup'><h4>เกิดเหตุการณ์โจรกรรมรถ " + carKey +
                            "</h4><p>วันและเวลา : " + this.date + "</p><p>ตำแหน่ง : " + this.lat + "," + this
                            .lng + "</p></div>";
                    }
                    // if (this.event == '120') {
                    //     icon_url =
                    //         "https://maps.gstatic.com/mapfiles/ridefinder-images/mm_20_yellow.png";
                    //     info_content = "<div class='map-popup'><h4>Network avilable of: " + carKey +
                    //         "</h4><p>Date: " + this.date + "</p><p>Location: " + this.lat + "," + this
                    //         .lng + "</p></div>";
                    // }
                    // if (this.event == '121') {
                    //     icon_url = "https://maps.gstatic.com/mapfiles/ridefinder-images/mm_20_gray.png";
                    //     info_content = "<div class='map-popup'><h4>Network lost of: " + carKey +
                    //         "</h4><p>Date: " + this.date + "</p><p>Location: " + this.lat + "," + this
                    //         .lng + "</p></div>";
                    // }
                    var icon = {
                        url: icon_url,
                        origin: new google.maps.Point(0, 0)
                    };
                    var info = new google.maps.InfoWindow({
                        content: info_content
                    });
                    var curPosition = new google.maps.LatLng(lat, lng);
                    var marker = new google.maps.Marker({
                        position: curPosition,
                        map: map,
                        icon: icon,
                        draggable: false
                    });
                    google.maps.event.addListener(marker, 'click', function() {
                        info.open(map, marker);
                        openedInfo = info;
                        setTimeout(function() {
                            info.close();
                            openedInfo = undefined;
                        }, 5000);
                    });
                }

                var curTime = new Date(this.date);
                if ((curTime - lastTime) > 1800000) { //(1000*60*30)){ // 30 Minutes
                    if (carMovingCoordinates != undefined) {
                        drawMovingPath(carMovingCoordinates, startTime, finishTime);
                    }
                    startTime = undefined;
                    carMovingCoordinates = [];
                }
                lastTime = curTime;
                carMovingCoordinates.push({
                    lat: lat,
                    lng: lng
                });
                finishTime = this.date;
            });

            // Last Moving Path
            if (carMovingCoordinates != undefined) {
                lastPath = carMovingCoordinates;
                lastCarMovingPath = drawMovingPath(lastPath, startTime, finishTime);
            }

            // Last know Location
            if (date != undefined) {
                var icon = {
                    //url: "https://maps.gstatic.com/mapfiles/ridefinder-images/mm_20_green.png",
                    url: "https://maps.google.com/intl/en_us/mapfiles/ms/micons/green-dot.png",
                    //url: "lib/images/ic_directions_car_white_24dp_1x.png",
                    origin: new google.maps.Point(0, 0)
                };
                var info = new google.maps.InfoWindow({
                    content: "<div class='map-popup'><h4>ตำแหน่งล่าสุดของรถ " + carKey +
                        "</h4><p>วันและเวลา : " + date + "</p><p>ตำแหน่ง : " + lat + "," + lng + "</p></div>"
                });
                var last = new google.maps.LatLng(lat, lng);
                lastMarker = new google.maps.Marker({
                    position: last,
                    map: map,
                    icon: icon,
                    draggable: false
                });
                google.maps.event.addListener(lastMarker, 'click', function() {
                    info.open(map, lastMarker);
                    openedInfo = info;
                    setTimeout(function() {
                        info.close();
                        openedInfo = undefined;
                    }, 5000);
                });
                map.setCenter(lastMarker.getPosition());
            }

            // Draw Moving Path
            function drawMovingPath(carMovingCoordinates, startTime, finishTime) {
                if (carMovingCoordinates == lastPath) {
                    var carMovingPath = new google.maps.Polyline({
                        path: carMovingCoordinates,
                        geodesic: true,
                        strokeColor: '#FF0000',
                        strokeOpacity: 1.0,
                        strokeWeight: 3
                    });
                } else {
                    var carMovingPath = new google.maps.Polyline({
                        path: carMovingCoordinates,
                        geodesic: true,
                        strokeColor: '#FFAA00',
                        strokeOpacity: 1.0,
                        strokeWeight: 2
                    });
                }
                carMovingPath.setMap(map);
                google.maps.event.addListener(carMovingPath, 'click', function(e) {
                    highlightMovingPath(carMovingPath);
                });
                return carMovingPath;
            }
        });

        // Highlight clicked moving path
        function highlightMovingPath(movingPath) {
            // Clear highlighted moving path
            if (clickedCarMovingPath != undefined) {
                if (clickedCarMovingPath == lastCarMovingPath) {
                    clickedCarMovingPath.setOptions({
                        strokeWeight: 3.0,
                        strokeColor: '#FF0000'
                    });
                } else {
                    clickedCarMovingPath.setOptions({
                        strokeWeight: 2.0,
                        strokeColor: '#FFAA00'
                    });
                }
            }
            // Highlight clicked moving path
            movingPath.setOptions({
                strokeWeight: 3.0,
                strokeColor: '#20FF55'
            });
            clickedCarMovingPath = movingPath;
        }

        map.addListener('click', function() {
            // Clear highlighted moving path
            if (clickedCarMovingPath != undefined) {
                if (clickedCarMovingPath == lastCarMovingPath) {
                    clickedCarMovingPath.setOptions({
                        strokeWeight: 3.0,
                        strokeColor: '#FF0000'
                    });
                } else {
                    clickedCarMovingPath.setOptions({
                        strokeWeight: 2.0,
                        strokeColor: '#FFAA00'
                    });
                }
            }
            clickedCarMovingPath = undefined;
            // Close Info Pop-up
            if (openedInfo != undefined) {
                openedInfo.close();
                openedInfo = undefined;
            }
        });
    }
}
</script>
<!-- End Map -->

<script>
var newTitle = "แผนที่ | Track My Bikes";
if (document.title != newTitle) {
    document.title = newTitle;
}
</script>