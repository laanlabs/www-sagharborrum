// Javascript for Admin Area
// Google Map API Standard Code

var map;

var geocoder;

var region = 'us';

var markers = new Array();

$(document).ready(function(){

	if($('#add_edit_body #map_canvas').length) {

		var lat = 33.8683;
		var lng = 2.592773;

		var gmap_marker = false;
		if($('#latitude').length) {
		
			val = $('#latitude').val()*1;
			
			if(val != '' && !isNaN(val)) {
				lat = val;
				gmap_marker = true;
			}
			
		}
		if($('#longitude').length) {
		
			val = $('#longitude').val()*1;
			
			if(val != '' && !isNaN(val)) {
				lng = val;
			}
		}

		geocoder = new google.maps.Geocoder();

		var latlng = new google.maps.LatLng(lat,lng);

		var myOptions = {
			zoom: 9,
			center: latlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};

		map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

		if(gmap_marker) {

			var marker = new google.maps.Marker({
				map: map,
				position: latlng
			});
		}
	}

	if($('#add_edit_body #address').length) {

		$('#add_edit_body #address').blur(function(){

			var address = $(this).val();

			if(address != '') {

				get_coordinate(address,region);
			}
		});
	}
});


/**
 * Get address location
 */
 
function get_coordinate(address, region) {
	
	if(region==null || region == '' || region == 'undefined') {
		region = 'us';
	}


	if(address != '') {
		$('#ajax_msg').html('<p>Loading location</p>');

		geocoder.geocode( {'address':address,'region':region}, function(results, status) {

			if(status == google.maps.GeocoderStatus.OK) {
				$('#ajax_msg').html('<p></p>');
				// populate form field with geo location
				$('#latitude').val( results[0].geometry.location.lat() );
				$('#longitude').val( results[0].geometry.location.lng() );

				map.setZoom(10);

				map.setCenter(results[0].geometry.location);

				// Google Map Marker
				var marker = new google.maps.Marker({
					map: map,
					position: results[0].geometry.location
				});
			} else {

				$('#ajax_msg').html('<p>Google map geocoder failed: '+status+'</p>');
			}
		});
	}
}

