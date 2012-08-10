// A helper class to create styled maps

function octoMap() {}

octoMap.prototype.create = function(jQueryElement, center, zoom, options) {

    // Init variables
    var domElement = jQueryElement[0];
    var mapCenter = '';
    var defaultZoom = 10;
    var myOptions = {
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    this.map = new google.maps.Map(domElement, myOptions);
    var map = this.map;
    
    if (options) {
        map.setOptions(options);
    }
    
    this.getCenterLtLng(center, function(mapCenter) {
        map.setCenter(mapCenter.latLng);
        
        // Set the zoom
        // - If it is true, we setZoom with it
        // - If it is false, we check if there is a viewport in mapCenter
        // - If not we set the defaultzoom
        if (zoom) {
            map.setZoom(zoom);
        } else {
            if (mapCenter.viewport) {
                map.fitBounds(mapCenter.viewport);
            } else {
                map.setZoom(defaultZoom);    
            }
        }
    });
    this.map = map;
    return map;
}

    
// Just add custom style to a map object
octoMap.prototype.setStyle = function(style) {
    if (style == 'classic' || style == 'satellite') {
        if (style == 'classic') {
            this.map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
        }
        if (style == 'satellite') {
            this.map.setMapTypeId(google.maps.MapTypeId.SATELLITE);
        }
    } else {
        var styledMap = new google.maps.StyledMapType(style, {
            name: "StyledMap"
        });
        this.map.mapTypes.set('StyledMap', styledMap);
        this.map.setMapTypeId('StyledMap');
    }
}
    
// Function to geocode human
octoMap.prototype.geocode = function(address, cb) {
    var result = '';
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode( {
        'address': address
    }, function(results, status) {
        cb(results, status);   
    });    
}

octoMap.prototype.getCenterLtLng = function(positionString, cb) {
    // Get the center position (via human addess or lat,lng)
    var matchLatLng = hasLatLngPattern(positionString);
    var mapCenter = new Object;
    // We check if the input value is latlng or address
    if (matchLatLng) {
        // It is a lat,lng pattern
        var coords = positionString.split(',');
        mapCenter.latLng = new google.maps.LatLng(coords[0], coords[1]);
        cb(mapCenter);
    } else {
        // This a "human" address
        this.geocode(positionString , function(results, status){
            if (status == 'OK') {
                var lat = results[0].geometry.location.lat();
                var lng = results[0].geometry.location.lng();
                mapCenter.latLng = new google.maps.LatLng(lat, lng);
                mapCenter.viewport = results[0].geometry.viewport;
                cb(mapCenter);
            } else {
                cb(false);
            
            } 
        });   
    }
}

// Add a marker to a map
// - Choose icon
// - Set center either lat,lng or human adress
// - Set a description
// - Set gmarker options
octoMap.prototype.addMarker = function(positionString, iconUrl, description) {
    var marker = new google.maps.Marker();
    var map = this.map;
    if (iconUrl) {
        marker.setIcon(iconUrl);
    }
    this.getCenterLtLng(positionString, function(markerPosition) {
        marker.setPosition(markerPosition.latLng);
        marker.setMap(map);
    });
    if (description) {
        google.maps.event.addListener(marker, 'click', function() {
            var infowWindow = new google.maps.InfoWindow({
                content: description
            });
            infowWindow.open(map, marker);
        });
    }
    return marker;
}


// Check if a string is lat,lng pattern or not
function hasLatLngPattern(string) {
    var matchLatLng = string.match(/^\-?\d+\.\d+?,\s*\-?\d+\.\d+?$/); 
    if (matchLatLng) {
        return matchLatLng;
    } else {
        return false;
    }
}


