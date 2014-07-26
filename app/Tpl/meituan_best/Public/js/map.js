function initialize(address) {
	 var geocoder = new google.maps.Geocoder();
   	 geocoder.geocode({'address': address}, function(results, status) {
      if(status == google.maps.GeocoderStatus.OK) {
		var myOptions = {
		  zoom: 14,
		  center: results[0].geometry.location,
		  mapTypeId: google.maps.MapTypeId.ROADMAP,
		  disableDefaultUI: true
		}
 		map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);
		
		 var marker = new google.maps.Marker({
	        position: results[0].geometry.location, 
	        map: map
	    });
	    google.maps.event.addListener(marker);
        map.setCenter(results[0].geometry.location);
      } 
    });
}