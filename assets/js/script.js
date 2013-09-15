var directionsDisplay;
var directionsService = new google.maps.DirectionsService();
var map;

function initialize()
{
	directionsDisplay 	= new google.maps.DirectionsRenderer();
	var map_open 		= new google.maps.LatLng(uzaklab_settings.lat, uzaklab_settings.lng);
	var map 			= new google.maps.Map(document.getElementById('map-canvas'), {
		zoom		:7,
		mapTypeId 	: google.maps.MapTypeId.ROADMAP,
		center 		: map_open
	});

	directionsDisplay.setMap(map);
}

function calcRoute()
{
	var map_start			= uzaklab_settings.lat + ',' + uzaklab_settings.lng;
	var elementId 			= document.getElementById('map_target');
	var value				= elementId.value;
	var value_latlng 		= elementId.options[elementId.selectedIndex].getAttribute('value_latlng');
	var value_price 		= elementId.options[elementId.selectedIndex].getAttribute('value_price');
	var value_delivery_time	= elementId.options[elementId.selectedIndex].getAttribute('value_delivery_time');
	var request 			= {
		origin 		: map_start,
		destination : value_latlng,
		travelMode 	: google.maps.DirectionsTravelMode.DRIVING
	};

	directionsService.route(request, function(response, status)
	{
		if (status == google.maps.DirectionsStatus.OK)
		{
			var content_html = 	'<h4>' + value + '</h4>' +
								'<span class="price">' + value_price + '</span>' +
								(value_delivery_time ? '<span class="time">' +value_delivery_time+'</span>' : '');
			
			directionsDisplay.setDirections(response);
			document.getElementById('panel_result').innerHTML = content_html;
		}
	});
}

google.maps.event.addDomListener(window, 'load', initialize);






