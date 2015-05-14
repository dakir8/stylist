<?php if(define('common', true)) exit('Access Denine');?><?php include template("include_header",true);?>
<div role="main" data-role="content">
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&language=en"></script>
<script type="text/javascript">
var map;
var defaultLat = 22.396428;
var defaultLng = 114.109497;
var defaultZoom = 10;
var infowindow = new google.maps.InfoWindow();
var shop_list = <?php echo htmlspecialchars(json_encode($result_a), ENT_NOQUOTES).chr(13).chr(10);?>;
var markers = [];
var marker;
var marker_me;
var map_time = 3000;
var map_timer;

google.maps.event.addDomListener(window, 'load', initialize);

function initialize() {
	if (typeof zoom != 'undefined' && zoom){
		defaultZoom = zoom;
	}
	var latlng = new google.maps.LatLng(defaultLat, defaultLng);
	var myOptions = {
		zoom: defaultZoom,
		center: latlng,
		mapTypeControl: true,
		disableDefaultUI: true,
		mapTypeControlOptions: {
			style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
		},
		navigationControl: true,
		navigationControlOptions: {
			style: google.maps.NavigationControlStyle.DEFAULT
		},
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	
	if(navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(function(position) {
			var pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
			addMarker(pos, '您的位置', true);
			map.setCenter(pos);
		}, function() {
			handleNoGeolocation(true);
		});
	} else {
		// Browser doesn't support Geolocation
		handleNoGeolocation(false);
	}
	get_shop_list(latlng);
	
}
function handleNoGeolocation(errorFlag) {
	if (errorFlag) {
		var content = 'Error: The Geolocation service failed.';
	} else {
		var content = 'Error: Your browser doesn\'t support geolocation.';
	}
	var options = {
		map: map,
		position: new google.maps.LatLng(60, 105),
		content: content
	};
	var infowindow = new google.maps.InfoWindow(options);
	map.setCenter(options.position);
}
function addMarker(latlng, info, flag) {
	var temp_marker = marker;
	var temp_otpion = {
		map: map,
		draggable: false,
		animation: google.maps.Animation.DROP,
		position: latlng
	};
	if(typeof flag != 'undefined' && flag){
		temp_otpion.icon = 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png';
		temp_marker = marker_me;
	}
	temp_marker = marker = new google.maps.Marker(temp_otpion);
	google.maps.event.addListener(temp_marker, 'click', function() {
		infowindow.setContent(info);
		console.log('click : ' + info);
		infowindow.open(map, this);
	});
	markers.push(temp_marker);
}
function get_shop_list(latlng){
	clearTimeout(map_timer);
	map_timer = setTimeout(function(){
		if(typeof shop_list == 'undefined' || shop_list.length <= 0){
			console.log('Get Shop : ' + latlng);
			$.ajax({
				url: 'ajax.php?ct=get_sales_location',
				method: 'POST',
				data: {
					pid: pid,
					lat: latlng.lat(),
					lng: latlng.lng()
				},
				dataType: 'JSON',
				complete: function(data, status){
				},
				success: function(data, status){
					console.log('ajax back: ' + status);
					shop_list = data.list_sales_location;
					if(typeof status != 'undefined' && status == 'success' && typeof shop_list != 'undefined'){
						show_shop_list(shop_list);
					}
				},
				error: function(data, status){
					alert('Error');
				}
			});
		} else {
			show_shop_list(shop_list);
		}
	}, map_time);
}
function show_shop_list(shop_list){
	$.each(shop_list, function(k, v){
		var temp_latlng = new google.maps.LatLng(v.lat, v.lng);
		var temp_name = v.name;
		var temp_address = v.address;
		var temp_description = temp_name+'<br/>'+temp_address;
		addMarker(temp_latlng, temp_description);
	});
}
</script>
<style type="text/css">
.shop-list-container { width: 100%; height: 140px; overflow: auto; -webkit-overflow-scrolling: touch; }
.shop-list-container ul { margin: 0; padding: 0; }
.shop-list-container ul li { padding: 5px 10px !important; }
.map_container { position: relative; display: inline-block; width: 100%; height: 440px; margin: 20px auto; }
.map_container #map_canvas { width: 100%; height: 100%; }
</style>
<div class="shop-list-container"><ul data-role="listview">
<?php foreach($result_a as $key=>$salon){?>
<li><?php echo $salon['name'];?><br/><?php echo $salon['address'];?></li>
<?php }?>
</ul></div>
<hr/>
<div class="map_container">
	<div id="map_canvas"></div>
</div>
</div>
<?php include template("include_footer",true);?>