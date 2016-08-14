var relnet_com = 'http://'+ window.location.host +'/RelNet/public/' ;
//var relnet_com = 'http://'+ window.location.host +'/' ;

function rhtmlspecialchars(str) {
	if (typeof(str) == "string") {
		str = str.replace(/&gt;/ig, ">");
		str = str.replace(/&lt;/ig, "<");
		str = str.replace(/&#039;/g, "'");
		str = str.replace(/&quot;/ig, '"');
		str = str.replace(/&amp;/ig, '&'); /* must do &amp; last */
	}
	return str;
}

var geocoder;
var map;
var oms ;//= new OverlappingMarkerSpiderfier(map);
var iw ;//= new gm.InfoWindow();
var gm = google.maps;

function initialize() {
  geocoder = new google.maps.Geocoder();
  var latlng = new google.maps.LatLng(-34.397, 150.644);
  var mapOptions = {
    zoom: 2,
    center: latlng
  }
  
  //map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
  map = new gm.Map(document.getElementById('map-canvas'), mapOptions);
  
  oms = new OverlappingMarkerSpiderfier(map, {markersWontMove: true, markersWontHide: true, keepSpiderfied: true});
  
  
}

function codeAddress(myaddress, id,name) {
  //var address = document.getElementById('address').value;
  var address = myaddress;
  geocoder.geocode( { 'address': address}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      map.setCenter(results[0].geometry.location);
      var marker = new google.maps.Marker({
          map: map,
          position: results[0].geometry.location,
    	  animation: google.maps.Animation.DROP,
      });
      //console.log(myaddress + " OK " + results[0].geometry.location);
    } else {
     // alert('Geocode was not successful for the following reason: ' + status);
     //console.log('Geocode was not successful for the following reason: ' + status + ' address ' + myaddress);
    }
    marker.desc = id;
    marker.setTitle(name);
    oms.addMarker(marker);
  });
}

//google.maps.event.addDomListener(window, 'load', initialize);

$(function(){ 
	var locdata = JSON.parse(rhtmlspecialchars(membersdata) ) ;
	// console.log(locdata);

	initialize();
	//clearMarkers();
	$.each(locdata, function( index, value ) {
	  //alert( index + ": " + value );
	  if(value.city + value.state +value.country != ""){
	  	window.setTimeout(function(){
	  		codeAddress(value.city + " ," + value.state + " ," + value.country, value.id, value.name);	
	  	}, index * 150)
	  	
	  }
	  //console.log(index + value.city + " ," + value.state + " ," + value.country);
	});
	iw = new gm.InfoWindow();
	oms.addListener('click', function(marker, event) {
	  if(map.getZoom() < 6){
	  	map.setZoom(6);
	  }
  	  map.setCenter(marker.getPosition());
  	 // options.keepSpiderfied(true);
	  //iw.setContent(marker.desc);
	  // iw.setContent(getmemberinfo(marker.desc));
	  getmemberinfo(marker.desc,marker);
	  // iw.open(map, marker);
	  //console.log("MARKER Clicked");
	});

});

function getmemberinfo(id,marker){
	var htmlstr ;
	$.get(relnet_com + "/family/member/info",{id: id},
		function(data){	
			 htmlstr = '<div class="memberinfoboxmap"><div class="row" ><div class="col-md-2 membox" id="imagerow">';
			 if(data.image != null){
				//$(".memberinfobox #imagerow").prepend('<img src='+relnet_com+'img/' + data.image +' />')
				htmlstr = htmlstr + '<img src="' + relnet_com + 'img/' + data.image + '">' ;
			}
			 htmlstr = htmlstr + '</div><div class="col-md-10 membox"><div class="row" id="memboxname" >' + data.name  + '</div>' ; 
			 if(data.city != null && data.city != ""){
			 	htmlstr = htmlstr + '<div class="row" id="memboxloc">' + data.city + ", " + data.relative + "'s " + data.relation + '</div>			</div></div>			</div>' ;
			 }
			 else {
			 	htmlstr = htmlstr + '<div class="row" id="memboxloc">' + data.relative + "'s " + data.relation + '</div>			</div></div>			</div>' ;	
			 }
			 // console.log(htmlstr);
			 iw.setContent(htmlstr);
	  		 iw.open(map, marker);		
	});

}



/*
1. get a list of all ID,City,State,Country  (data will come in view JSOn format)
2. Get loc data like - ID,Log,Lat 
3. foreach data in step 2 setMap Marker

To check for icon http://www.w3schools.com/googleapi/tryit.asp?filename=tryhtml_map_overlays_icon

https://stackoverflow.com/questions/3548920/google-maps-api-v3-multiple-markers-on-exact-same-spot

http://www.ejw.de/ejw-vor-ort/

*/