<?php
	require_once('init.php');
	require_once('function.php');

	//$result = get_terminal_info("where latlng is not null and kiosk_location like '%金色%' ");
?>
<html>
<head>
<title>terminalmap</title>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<script type="text/javascript" src="https://ditu.google.com/maps/api/js?sensor=false&language=zh-cn&region=zh"></script>
<script type="text/javascript">
	//global define
	var map;
	var latlng;
	var marker;
	var markersArray = [];
	var xhr;
	//clearMarkers
	google.maps.Map.prototype.clearMarkers = function() {
		for(var i=0; i < this.markers.length; i++){
			this.markers[i].setMap(null);
		}
		this.markers = new Array();
	};
	
	xhr =  getXMLHttpObj();
	function getXMLHttpObj()
	{
		if(typeof(XMLHttpRequest)!='undefined')
			return new XMLHttpRequest();

		var axO=['Msxml2.XMLHTTP.6.0', 'Msxml2.XMLHTTP.4.0',
		'Msxml2.XMLHTTP.3.0', 'Msxml2.XMLHTTP', 'Microsoft.XMLHTTP'], i;
		for(i=0;i<axO.length;i++)
		try{
			return new ActiveXObject(axO[i]);
		}catch(e){}
		return null;
	}
	//autocomplete
	function codeAddress(obj)
	{
		//console.log('key up');
		xhr.open('POST','search.php',true);
		xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xhr.send("address="+obj.value);
		xhr.onreadystatechange = function()
		{	
			if(xhr.readyState != 4)
			{
				return;
			}
			if(xhr.status == 200)
			{
				eval(xhr.responseText);
			}
			else
			{
				return;
			}
		}
	}
	//delete all marker from map
	function deleteOverlays()
	{
		if(markersArray)
		{
			for(i in markersArray)
			{
				markersArray[i].setMap(null);
			}
			markersArray.length = 0;
		}
	}
	function addMarker(title,location)
	{
		marker = new google.maps.Marker({
			position:location,
			map:map,
			title:title
		});
		markersArray.push(marker);
	}
	function initialize() {
		//setup
		var latlng = new google.maps.LatLng(29.584, 106.506);
		var myOptions = {
			zoom: 11,
			center: latlng,
			mapTypeId: google.maps.MapTypeId.SATELLITE
		};
		var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
		/*
		//all the marker here from database
		<?php foreach($result as $key => $value) { ?> 
		var latlng<?php echo $value['kiosk_asset_id'] ?> = new google.maps.LatLng(<?php echo $value['latlng'] ?>);
		var marker<?php echo $value['kiosk_asset_id'] ?> = new google.maps.Marker({
			position: latlng<?php echo $value['kiosk_asset_id'] ?>,
			map: map,
			title: '<?php echo $value['kiosk_location_2'] ?>'
		});
		var infowindow<?php echo $value['kiosk_asset_id'] ?> = new google.maps.InfoWindow({
			content: '<h3><b><?php echo $kiosk_location_2=$value['kiosk_location_2'] ?><b></h3><?php $result2 = get_terminal_info("where kiosk_location_2='$kiosk_location_2'");foreach($result2 as $r2)
						{
							echo "<p style=\"white-space:nowrap;\">$r2[kiosk_asset_id]/$r2[kiosk_address]/$r2[kiosk_floor_id]</p>";
						}
					?>',
			title: '<?php echo $value['kiosk_location_2'] ?>'
		});
		google.maps.event.addListener(marker<?php echo $value['kiosk_asset_id'] ?>, 'click', function () {
			infowindow<?php echo $value['kiosk_asset_id'] ?>.open(map, marker<?php echo $value['kiosk_asset_id'] ?>);
		});
		<?php } ?>
		*/
	}
</script>
</head>
<body onload="initialize()">
<div> 
	<!--
	<div>
		<input id="address" type="text" onKeyUp="suggest(this);" > 
		<input type="button" value="根据地址搜索" onClick="codeAddress('address')" />
	</div>
	-->
	<div>
		<input id="address" type="text" value=""> 
		<input type="button" value="根据终端号搜索" onClick="codeAddress(document.getElementById('address'))" />
	</div>
	
</div> 
<div id="map_canvas" style="width:100%; height:100%"></div>
</body>
</html>
