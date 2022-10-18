<?php
if(empty($_POST))
{
	die("forbidden");
}
sleep(1);
require_once('init.php');
$address = $_POST['address'];
if($address == '')
{
	die();
}
preg_match_all("/[0-9]{6}/",$address, $matches);
$match_result = $matches[0];
$terminal_str = implode(",",$match_result);
$result = get_terminal_info("where kiosk_asset_id in ($terminal_str) group by kiosk_location_2");
?>
<?php foreach($result as $r) {
$count = get_terminal_count("where kiosk_asset_id in ($terminal_str) and kiosk_location_2='$r[kiosk_location_2]'");
?>

var latlng<?php echo $r['kiosk_asset_id'] ?> = new google.maps.LatLng(<?php echo $r['latlng'] ?>);
marker<?php echo $r['kiosk_asset_id'] ?> = new google.maps.Marker({
	position:latlng<?php echo $r['kiosk_asset_id'] ?>,
	map:map,
	title: '<?php echo $r['kiosk_location_2'].'('.$count.'台)';?>'
});
marker<?php echo $r['kiosk_asset_id'] ?>.infowindow = new google.maps.InfoWindow({
	content: '<h3><b><?php echo $kiosk_location_2=$r['kiosk_location_2'] ?><b></h3><?php $result2 = get_terminal_info("where kiosk_asset_id in ($terminal_str) and kiosk_location_2='$r[kiosk_location_2]'");foreach($result2 as $r2)
						{
							echo "<p>$r2[kiosk_asset_id]/$r2[kiosk_address]/$r2[kiosk_floor_id]</p>";
						}
					?>',
	title: '<?php echo $r['kiosk_location_2'].'('.$count.'台)';?>'
});
google.maps.event.addListener(marker<?php echo $r['kiosk_asset_id'] ?>, 'click', function () {
	marker<?php echo $r['kiosk_asset_id'] ?>.infowindow.open(map, marker<?php echo $r['kiosk_asset_id'] ?>);
});
var latlngCenter = new google.maps.LatLng(<?php echo $r['latlng']?>);
map.setCenter(latlngCenter);
markersArray.push(marker<?php echo $r['kiosk_asset_id'] ?>);

<?php } ?>

<?php
$shownearby = $_POST['shownearby'];
if($shownearby == 'true')
{
	//get nearby place info
	foreach($match_result as $asset)
	{
		$ps = get_nearby_place($asset);
		foreach($ps as $p)
		{
	?>
		var latlng<?php echo $p['guid'] ?> = new google.maps.LatLng(<?php echo $p['lat'] ?>,<?php echo $p['lon'] ?>);
		marker<?php echo $p['guid'] ?> = new google.maps.Marker({
			position:latlng<?php echo $p['guid'] ?>,
			map:map,
			title: '<?php echo $p['name'] ?>',
			icon: '<?php echo get_icon($p["pkid"]) ?>'
		});
		marker<?php echo $p['guid'] ?>.infowindow = new google.maps.InfoWindow({
			content: '<h3><?php echo $p['name'] ?></h3><p>地址：<?php echo $p['addr'] ?></p><p>链接：<a href="<?php echo $p['link'] ?>"><?php echo $p['link'] ?></a></p><p>距离终端<?php echo $p['kiosk_asset_id'] ?> ：<?php echo $p['dist'] ?></p>',
			title: '<?php echo $p['name']; ?>'
		});
		google.maps.event.addListener(marker<?php echo $p['guid'] ?>, 'click', function () {
			marker<?php echo $p['guid'] ?>.infowindow.open(map, marker<?php echo $p['guid'] ?>);
		});
		markersArray.push(marker<?php echo $p['guid'] ?>);

	<?php
		}
	}
}
?>

/*
console.dir(markersArray);
for(i in markersArray)
{
	google.maps.event.addListener(markersArray[i], 'click', function () {
		markersArray[i].infowindow.open(map, markersArray[i]);
		//console.dir(markersArray[i].position.Ja);
	});
}
*/