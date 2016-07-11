function initialize() {
	
	var info =''+ 
	 '<div style="width:300px; float:left; position:relative;">'+
   '<div style="width:100px; float:left; padding-top:10px;"><img src="/annanovas/images/annanovas-logo.png" width="100" height="75"/></div>'+
   '<div style="width:185px; float:right; font-family:Lucida Grande,Lucida Sans Unicode,Arial,Verdana,sans-serif; font-size:11px; padding-left:15px; ">'+
     '<b>Annanovas</b><br />'+
     '23/1, Dhanmondi, Zigatola<br />'+
     '2nd Floor, Dhaka-1209<br />'+
     'Bangladesh.<br />'+
     '<b> <br /> </b>'+
     '<b>Email:</b><br />'+
     'info@annanovas.com<br />admin@annanovas.com'+
   '</div>'+
 '</div>';
	//alert (info);
	if (GBrowserIsCompatible()) {
		var map = new GMap2(document.getElementById("map_canvas"));
		map.setCenter(new GLatLng(23.738667, 90.373849), 13);
		map.setUIToDefault();

		// Create a base icon for all of our markers that specifies the
		// shadow, icon dimensions, etc.
		var baseIcon = new GIcon(G_DEFAULT_ICON);
		baseIcon.shadow = "/annanovas/images/an_marker_shadow.png";
		baseIcon.iconSize = new GSize(31, 51);
		baseIcon.shadowSize = new GSize(48, 60);
		
		baseIcon.iconAnchor = new GPoint(9, 52);
		baseIcon.infoWindowAnchor = new GPoint(9, 2);
		
		function createMarker(point, index) {
			// Create a lettered icon for this point using our icon class
		 // var letter = String.fromCharCode("A".charCodeAt(0) + index);
			var letteredIcon = new GIcon(baseIcon);
			letteredIcon.image = "/annanovas/images/an_marker.png";

			// Set up our GMarkerOptions object
			markerOptions = { icon:letteredIcon };
			var marker = new GMarker(point, markerOptions);

			GEvent.addListener(marker, "click", function() {
				marker.openInfoWindowHtml(info);
			});
			return marker;
		}
		
		var point = new GLatLng(23.738667, 90.373849);
		
		var marker_m = createMarker (point, 0);
		map.addOverlay(marker_m);
		marker_m.openInfoWindowHtml(info);
		// Creates a marker whose info window displays the letter corresponding
		// to the given index.
		

		// Add 10 markers to the map at random locations
		
	}
}