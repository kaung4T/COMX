var isAdminBar = false,
        isEditMode = false;
(function ($) {

    var DyncontEl_GoogleMapsHandler = function ($scope, $) {
        //console.log( $scope );
        var map;
        var bounds;

        var elementSettingsMap = get_Dyncontel_ElementSettings($scope);
        
        // elementor bug
        /*if (typeof elementSettingsMap == 'string') {
            elementSettingsMap.split(',"sizes":}').join('');
            console.log(elementSettingsMap);
            elementSettingsMap = JSON.parse(elementSettingsMap);
        }*/
        
        var id_scope = $scope.attr('data-id');
        var map = $scope.find('#el-wgt-map-' + id_scope)[0];
        var indirizzo = jQuery(map).data('address');
        var lati = jQuery(map).data('lat') || 0;
        var long = jQuery(map).data('lng') || 0;
        var infoWindow = jQuery(map).data('infowindow') || 'Empty Info Window';
        var imageMarker = jQuery(map).data('imgmarker') || '';
        var zoom = jQuery(map).data('zoom');
        if (elementSettingsMap.zoom && elementSettingsMap.zoom.size) {
            zoom = elementSettingsMap.zoom.size;
        }

        
        //alert('dce js '+$($scope.context).find('#map').attr('data-height'));
        //alert(indirizzo);
        //$($scope.context).find('#map').height($($scope.context).find('#map').attr('data-height'));
        debugHolder = $scope.find('#debug')[0]; //document.getElementById("debug");
        var centroMappa = {lat: lati, lng: long};
        //console.log(centroMappa);
        //setTimeout(function(){ alert('Testtt: ' + $scope.find('#el-wgt-map-'+id_scope).length) }, 500 );;
        //

        //
        var mapParams = {
            zoom: zoom,
            scrollwheel: Boolean( elementSettingsMap.prevent_scroll ),
            mapTypeControl: Boolean( elementSettingsMap.maptypecontrol ),
            panControl: Boolean( elementSettingsMap.pancontrol ),
            rotateControl: Boolean( elementSettingsMap.rotaterontrol ),
            scaleControl: Boolean( elementSettingsMap.scalecontrol ),
            streetViewControl: Boolean( elementSettingsMap.streetviewcontrol ),
            zoomControl: Boolean( elementSettingsMap.zoomcontrol ),
            fullscreenControl: Boolean( elementSettingsMap.fullscreenControl ),
            center: centroMappa
        };

        if (elementSettingsMap.map_type && elementSettingsMap.map_type != "acfmap") {
            mapParams['mapTypeId'] = elementSettingsMap.map_type;
        }
        
        if (elementSettingsMap.style_select == 'custom') {
            mapParams['styles'] = eval(elementSettingsMap.style_map);
            fireMap(map, mapParams);
        } else if (elementSettingsMap.style_select == 'prestyle') {
            var fileStyle = elementSettingsMap.snazzy_select;
            //alert(fileStyle+".json");
            $.getJSON(fileStyle + ".json", function (json) {
                //console.log(json); // this will show the info it in firebug console
                mapParams['styles'] = json;
                fireMap(map, mapParams);
            });
        } else {
            fireMap(map, mapParams);
        }

        function fireMap(elements_map, mapParams_map) {
            map = new google.maps.Map(elements_map, mapParams_map);
            
            // Create an array of alphabetical characters used to label the markers.
            var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            var markers = [];
            
            var mapDataType = elementSettingsMap.map_data_type;
            //alert(mapDataType);
            
            // Forzo l'uso della latitudine-longitudine se sto usando acf
            if(elementSettingsMap.acf_mapfield ) mapDataType = 'latlng';
            
            // ---- se è una query su posts che usano acf ----------- 
            if (elementSettingsMap.use_query) {
                bounds = new google.maps.LatLngBounds();

                for (i = 0; i < address_list.length; i++) {

                    if (mapDataType == 'address') {
                        addressToLocation(
                                address_list[i]['address'],
                                address_list[i]['marker'],
                                address_list[i]['infoWindow'],
                                address_list[i]['postLink'],
                                changeMapLocation);

                    } else if (mapDataType == 'latlng') {

                        //alert('mapLatLong: '+address_list[i]['lat']+' '+address_list[i]['lng']);
                        var latLng = new google.maps.LatLng(address_list[i]['lat'], address_list[i]['lng']); //Makes a latlng
                        map.panTo(latLng); //Make map global
                        //alert(address_list[i]['postlink']);

                        //var markers = locations.map(function(location, i) {
                            //return new google.maps.Marker({
                            var marker = new google.maps.Marker({
                                position: latLng,
                                map: map,
                                icon: address_list[i]['marker'],
                                animation: google.maps.Animation.DROP, //.DROP.BOUNCE,
                                //label: labels[i % labels.length],
                            });
                        //});
                        //console.log(marker);
                        markers.push(marker);
                        
                        //
                        bounds.extend(marker.position);
                        //

                        if (elementSettingsMap.enable_infoWindow) {

                            google.maps.event.addListener(marker, 'click', (function (marker, k) {
                                //
                                return function () {

                                    if (elementSettingsMap.infoWindow_click_to_post) {
                                        if (isEditMode) {
                                            alert('You have clicked: ' + address_list[k]['postLink']);
                                            return false;
                                        } else {
                                            window.location = address_list[k]['postLink'];
                                        }
                                    } else {
                                        var infoWindowMap = new google.maps.InfoWindow({
                                            content: address_list[k]['infoWindow']
                                        });
                                        infoWindowMap.open(map, marker);
                                    }

                                }
                                //
                            })(marker, i));

                        }
                    }
                    if (address_list.length > 1) {
                        map.fitBounds(bounds);
                    }
                    //alert(address_list[i]['marker']);
                    //alert('query');
                }
                if( elementSettingsMap.markerclustererControl ){
                // Add a marker clusterer to manage the markers.
                var markerCluster = new MarkerClusterer(map, markers,
                    {imagePath: '/wp-content/plugins/dynamic-content-for-elementor/assets/lib/gmap/markerclusterer/img/m'});
                }

            } else {
                // ---- se non è una query... pesco dal singolo -----------
                
                if (mapDataType == 'address') {

                    addressToLocation(indirizzo, imageMarker, infoWindow, null, changeMapLocation);
                } else if (mapDataType == 'latlng') {

                    //alert('mapLatLong: '+lati+' '+long);

                    var latLng = new google.maps.LatLng(lati, long); //Makes a latlng
                    map.panTo(latLng); //Make map global


                    var infoWindowMap = new google.maps.InfoWindow({
                        content: infoWindow
                    });


                    var marker = new google.maps.Marker({
                        position: latLng, //centroMappa,
                        map: map,
                        icon: imageMarker,
                        animation: google.maps.Animation.DROP,
                        //title:"Hello World!",
                        //label: 'LABEL'
                        //infowindow: 'HI!'
                    });

                    if (elementSettingsMap.enable_infoWindow) {
                        marker.addListener('click', function () {
                            infoWindowMap.open(map, this);
                        });
                    }

                }
                //alert(elementSettingsMap.map_data_type);
            }
        }
        
        function changeMapLocation(locations) {
            if (locations && locations.length >= 1) {

                /*var numOfLocations = locations.length;
                 for(var i=0; i<numOfLocations; i++) {  
                 //log("- " + locations[i].text + " / <strong>" + locations[i].location.toString() + "</strong>");
                 var marker = new google.maps.Marker({
                 map: map,
                 position: locations[i].location
                 });
                 }
                 */
                //alert(locations[0].marker);
                console.log(locations[0]);

                var image = {
                    url: locations[0].marker, //elementSettingsMap.imageMarker['url'],
                    // This marker is 20 pixels wide by 32 pixels high.
                    //size: new google.maps.Size(190, 190),
                    // The origin for this image is (0, 0).
                    //origin: new google.maps.Point(0, 0),
                    // The anchor for this image is the base of the flagpole at (0, 32).
                    //anchor: new google.maps.Point(0, 32)
                };
                var shape = {
                    coords: [1, 1, 1, 20, 18, 20, 18, 1],
                    type: 'poly'
                };
                var objMarker = {
                    map: map,
                    position: locations[0].location,
                    //icon: image,
                    //shape: shape,
                };
                //alert(locations[0].location);
                if (locations[0].marker != "") {
                    objMarker['icon'] = image;
                }
                var marker = new google.maps.Marker(objMarker);

                var infoWindowMap = new google.maps.InfoWindow({
                    content: locations[0].infoWindow,
                });
                map.panTo(locations[0].location);

                if (elementSettingsMap.enable_infoWindow) {
                    marker.addListener('click', function () {
                        //alert(locations[0].infoWindow);
                        //alert(locations[0].postLink);


                        if (elementSettingsMap.infoWindow_click_to_post) {
                            if (isEditMode) {
                                alert('You have clicked: ' + locations[0].postLink);
                                return false;
                            } else {
                                window.location = locations[0].postLink;
                            }
                        } else {
                            infoWindowMap.open(map, marker);
                        }
                    });
                }
                if (elementSettingsMap.use_query) {
                    bounds.extend(marker.position);
                    map.fitBounds(bounds);
                }
                //
            } else {
                log("Num of results: 0");
            }
        }
    };

    

    function addressToLocation(address, markerimg, iw, pl, callback) {
        //alert('addressToLocation '+address+' '+markerimg);

        // ********* geocoder converte l'indirizzo in posizioni lat-lon **********************************
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode(
                {
                    address: address
                },
                function (results, status) {

                    var resultLocations = [];

                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results) {
                            var numOfResults = results.length;
                            //alert(results[0].formatted_address);
                            for (var i = 0; i < numOfResults; i++) {
                                var result = results[i];
                                resultLocations.push(
                                        {
                                            text: result.formatted_address,
                                            addressStr: result.formatted_address,
                                            location: result.geometry.location,
                                            marker: markerimg,
                                            postLink: pl,
                                            infoWindow: iw
                                        }
                                );
                            }
                            ;
                        }
                    } else if (status == google.maps.GeocoderStatus.ZERO_RESULTS) {
                        // address not found
                    }

                    if (resultLocations.length > 0) {
                        callback(resultLocations);
                        //console.log(resultLocations);
                    } else {
                        callback(null);
                    }
                }
        );
        // ***********************************************************************************************

    }
    // debugging
    function log(str, clear) {

        if (clear) {
            debugHolder.innerHTML = "";
        }
        debugHolder.innerHTML = debugHolder.innerHTML + "<br />" + str;
    }
    // Make sure you run this code under Elementor..
    $(window).on('elementor/frontend/init', function () {
        if (elementorFrontend.isEditMode()) {
            isEditMode = true;
        }

        if ($('body').is('.admin-bar')) {
            isAdminBar = true;
        }
        //alert('inittt');
        elementorFrontend.hooks.addAction('frontend/element_ready/dyncontel-acf-google-maps.default', DyncontEl_GoogleMapsHandler);
    });

})(jQuery);