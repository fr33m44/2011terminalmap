<html>
<head>
<title>坐标定位v2</title>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<link href="style.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="http://ditu.google.cn/maps/api/js?sensor=false"></script>
<script>
	window.onload=function()
	{	
		var geocoder = new google.maps.Geocoder();
		var xhr =  getXMLHttpObj();
		var map;
		var i;
		var ul;
		function gname(obj)
		{
			return document.getElementsByName(obj);
		}
		function gid(obj)
		{
			return document.getElementById(obj);
		}
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
		function init()
		{
			var latlng = new google.maps.LatLng(29.584, 106.506);
			var myOptions = {
				zoom: 11,
				center: latlng,
				mapTypeId: google.maps.MapTypeId.SATELLITE
			};
			map = new google.maps.Map(gid("map_canvas"), myOptions);
		}
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
		function geocode(location)
		{
			geocoder.geocode({
				'address': location,
				'bounds': (google.maps.LatLng(29.793412752904757,106.1102599802017),google.maps.LatLng(29.056901552395107,107.13267514133452))
			}, function (results, status) {
				if (status == google.maps.GeocoderStatus.OK){
					map.setCenter(results[0].geometry.location);
					var marker = new google.maps.Marker({
						map: map,
						position: results[0].geometry.location,
						title: results[0].formatted_address
					});
					ul = gid('resultlist');
					ul.innerHTML='';
					for(i=0;i<results.length;i++)
					{
						var li = document.createElement('li');
						li.innerHTML=results[i].formatted_address;
						ul.appendChild(li);
					}
					//console.log(location + ' 被解析为：' + results[0].formatted_address + ', 坐标(latlng):' + results[0].geometry.location.Ja + ',' + results[0].geometry.location.Ka);
					//xhr.open('GET', 'latlng.php?kiosk_asset_id='+asset_id+'&latlng='+results[0].geometry.location.Ja + ',' + results[0].geometry.location.Ka + '&formatted_address='+results[0].formatted_address,true); //2th argument->bAsync
					//xhr.send('kiosk_asset_id='+asset_id+'&latlng='+results[0].geometry.location.Ja + ',' + results[0].geometry.location.Ka);
					//xhr.send();
				}
				else if (status == google.maps.GeocoderStatus.ERROR){
					gid('termlist').innerHTML="连接google服务时出错";
					//console.log("错误码: " + status + "，没有搜索结果：" + location);
				}
				else if (status == google.maps.GeocoderStatus.INVALID_REQUEST){
					gid('termlist').innerHTML="GeocoderRequest无效";
					//console.log("错误码: " + status + "，没有搜索结果：" + location);
				}
				else if (status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT){
					gid('termlist').innerHTML="网页发出请求的频率过高，超过了最短时间限制。";
					//console.log("错误码: " + status + "，没有搜索结果：" + location);
				}
				else if (status == google.maps.GeocoderStatus.REQUEST_DENIED){
					gid('termlist').innerHTML="允许网页使用地址解析器。";
					//console.log("错误码: " + status + "，没有搜索结果：" + location);
				}
				else if (status == google.maps.GeocoderStatus.UNKNOWN_ERROR){
					gid('termlist').innerHTML="由于服务器错误而无法处理地址解析请求。如果您再试一次，该请求可能会成功。";
					//console.log("错误码: " + status + "，没有搜索结果：" + location);
				}
				else if (status == google.maps.GeocoderStatus.ZERO_RESULTS){
					gid('termlist').innerHTML="没有结果";
					//console.log("错误码: " + status + "，没有搜索结果：" + location);
				}
			});
		}
		gid('geocodebtn').onclick = function()
		{
			geocode(gid('address').value);
		}
		init();
	}
</script>
</head>
<body>
<div> 
	<div style="margin:10px;">
		<input id="address" type="textbox" size="70" style="line-height:1.3em;font-size:1.3em;font-weight:bold;"> 
		<input id="geocodebtn" type="button" value="搜索" style="line-height:1.3em;font-size:1.3em;font-weight:bold;" />
	</div>
	<div>
		<div id="termlist" style="float:left;width:20%;height:100%">
		<ul id="resultlist"></ul>
		</div>
		<div id="map_canvas" style="width:80%; height:100%"></div>
	</div>
	
</div> 

</body>
</html>
