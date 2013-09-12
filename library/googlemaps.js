document.write('<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>');

var map;
var geocoder;
var marker;
var latlng;

function getLatLngFromAddress(address,onComplete) {
    if(!geocoder) 
    geocoder = new google.maps.Geocoder();    
    geocoder.geocode( { 'address': address, 'region':'ch'},function(results, status) {
  if (status==google.maps.GeocoderStatus.OK) { 
     latlng=results[0].geometry.location;
          
  }
  window.setTimeout(onComplete,200); 
});

}



function getCoordinateChooser(div,lat,lng) {  
   geocoder = new google.maps.Geocoder();
   
   var coord = new google.maps.LatLng(lat, lng);     
   var myOptions = {       
       mapTypeId: google.maps.MapTypeId.HYBRID     
   };     
   map = new google.maps.Map(document.getElementById(div), myOptions);
   
   var location;
   var zoom; 
   if (lat != 0) {
    location = coord;
    zoom=17;
   } else {
    location = new google.maps.LatLng(46.680345967890176, 7.867110002163713);
    zoom=10;
   }
            
     map.setCenter(location);
     map.setZoom(zoom);
     latlng=location;
     marker = new google.maps.Marker({               
         map: map,                
         position: location,
         draggable: true                                    
     });
     google.maps.event.addListener(marker, 'dragend', function() { 
          latlng=marker.getPosition();
                
      });         
      
}
