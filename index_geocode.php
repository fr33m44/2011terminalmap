<?php
	require_once('init.php');
	//print_r($db);
	$result = $db->getAll("select kiosk_asset_id,kiosk_location,kiosk_location_2,kiosk_address from kiosk_information WHERE formatted_address ='中国重庆市九龙坡区' ORDER BY formatted_address,kiosk_location_2,kiosk_location,kiosk_address");
	//print_r($result[0]['kiosk_location_2']);
	
?>
<html>
<head>
<title>terminalmap</title>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<meta http-equiv="content-type" content="text/html; charset=utf-8"/> 
<script type="text/javascript" src="http://ditu.google.cn/maps/api/js?sensor=false&language=zh-cn&region=zh"></script>
<script type="text/javascript">
	//global define
	var geocoder = new google.maps.Geocoder();
	var xhr =  getXMLHttpObj();
	var map;
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
	
	var geocodeInterval = function (location,asset_id,map) {
		geocoder.geocode({
			'address': location
		}, function (results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				map.setCenter(results[0].geometry.location);
				var marker = new google.maps.Marker({
					map: map,
					position: results[0].geometry.location
				});
				console.log(location + ' 被解析为：' + results[0].formatted_address + ', 坐标(latlng):' + results[0].geometry.location.Ja + ',' + results[0].geometry.location.Ka);
				xhr.open('GET', 'latlng.php?kiosk_asset_id='+asset_id+'&latlng='+results[0].geometry.location.Ja + ',' + results[0].geometry.location.Ka + '&formatted_address='+results[0].formatted_address,true); //2th argument->bAsync
				//xhr.send('kiosk_asset_id='+asset_id+'&latlng='+results[0].geometry.location.Ja + ',' + results[0].geometry.location.Ka);
				xhr.send();
			} else {
				console.log("错误码: " + status + "，没有搜索结果：" + location);
			}
		});
	};

	function initialize() {
		//setup
		var latlng = new google.maps.LatLng(29.584, 106.506);
		var myOptions = {
			zoom: 11,
			center: latlng,
			mapTypeId: google.maps.MapTypeId.SATELLITE
		};
		map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

		//all the marker here from database
		<?php foreach($result as $key => $value) { ?> 
			setTimeout("geocodeInterval('<?php echo $value['kiosk_location_2']?>','<?php echo $value['kiosk_asset_id']?>',map)", <?php echo($key + 1) * 1000 ?> ); 
		<?php } ?>
	}
</script>
</head>
<body onload="initialize()">
<div> 
	<div>
		<input id="address" type="textbox" value=""> 
		<input type="button" value="根据地址搜索" onclick="codeAddress('address')" />
	</div>
	<div>
		<input id="address" type="textbox" value=""> 
		<input type="button" value="根据终端号搜索" onclick="codeAddress('termno')" />
	</div>
	
</div> 
<div id="map_canvas" style="width:100%; height:100%"></div>
</body>
</html>
