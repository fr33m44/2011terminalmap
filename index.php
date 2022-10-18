<?php
	require_once('init.php');
	$result_checkbox = get_terminal_info("order by kiosk_asset_id");
	
?>
<html>
<head>
<title>terminalmap</title>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<link href="style.css" type="text/css" rel="stylesheet" />
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
		var loading = document.getElementById("loadingspan");
		loading.innerHTML='<img src="loading.gif" />';
		//clear markers
		deleteOverlays();
		//console.log('key up');
		xhr.open('POST','search.php',true);
		//xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=UTF-8");
		if(obj.tagName == 'TEXTAREA')//如果搜索
		{
			xhr.send("address="+obj.value+"&shownearby="+gid("shownearby").checked);
		}
		else//列表多选
		{
			var str="";
			var nodelen = obj.length;
			for(var i=0;i<nodelen;i++)
			{
				if(obj[i].checked)
				{
					str+=obj[i].value+" ";
				}
			}
			if(str=="")
			{
				//deleteOverlays();
				loading.innerHTML='';
				return;
			}
			else
			{
				xhr.send("address="+str+"&shownearby="+gid("shownearby").checked);
			}
		}
		xhr.onreadystatechange = function()
		{
			if(xhr.readyState != 4)
			{
				return;
			}
			if(xhr.status == 200)
			{
				var ie = !-[1,];
				if(ie)
					execScript(xhr.responseText);
				else
				{
					eval(xhr.responseText);
				}
				loading.innerHTML='';
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
	function addMarker(title,location,iwcontent,iwtitle)
	{
		marker = new google.maps.Marker({
			position:location,
			map:map,
			title:title
		});
		marker.infowindow = new google.maps.InfoWindow({
			content: iwcontent,
			title: iwtitle
		});
		markersArray.push(marker);
	}
	function initialize() {
		//setup
		latlng = new google.maps.LatLng(29.584, 106.506);
		var myOptions = {
			zoom: 11,
			center: latlng,
			mapTypeId: google.maps.MapTypeId.SATELLITE
		};
		map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
		//var latlng1= new google.maps.LatLng(29.552747,106.56888900000001);
		//addMarker('xx',latlng1);

	}
	function gname(obj)
	{
		return document.getElementsByName(obj);
	}
	function gid(obj)
	{
		return document.getElementById(obj);
	}
	function clear1(obj)
	{
		deleteOverlays();
		var nodelen = obj.length;
		for(var i=0;i<nodelen;i++)
		{
			obj[i].checked = false;
		}
		
	}
</script>
</head>
<body onload="initialize()">
<div> 
	<div id="wrapper">
		<div id="termlist">
			<fieldset>
				<legend>终端搜索</legend>
				<!--
				<input id="address" type="text" value=""> 
				-->
				<textarea id="address"></textarea>
				<div>
					<label for="shownearby">显示附近地点<input id="shownearby" type="checkbox" /></label>
					<input type="button" value="搜索" onClick="codeAddress(gid('address'))" />
				</div>
				
				
			</fieldset>
			<fieldset>
				<legend>终端列表</legend>
					<div><input type="button" id="clear" value="重置" onclick="clear1(gname('termcb'))" /></div>
					<ul>
					<?php foreach($result_checkbox as $cb) { ?>
						<li><label for="term<?php echo $cb['kiosk_asset_id'] ?>"><input name="termcb" id="term<?php echo $cb['kiosk_asset_id'] ?>" type="checkbox" value="<?php echo $cb['kiosk_asset_id'] ?>" onclick="codeAddress(gname('termcb'))" /><?php echo $cb['kiosk_asset_id'] ?></label></li>
					<?php } ?>
					</ul>
			</fieldset>
			<fieldset>
				<legend>about</legend>
				<p>version: 1.0.1</p>
				<p><a href="changelog.html" target="_blank">changelog</a></p>
			</fieldset>
		</div>
		<div id="map_canvas"></div>
		<span id="loadingspan"></span>
	</div>
	
</div> 
</body>
</html>
