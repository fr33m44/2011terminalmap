<?php
	require_once('init.php');
	//$db->autoExecute('kiosk_information',$_GET['latlng'],'UPDATE',"kiosk_asset_id = $_GET['asset_id']");
	$inf=array();
	$info['latlng']= $_GET['latlng'];
	$info['formatted_address'] = $_GET['formatted_address'];
	$asset_id = $_GET['kiosk_asset_id'];
	$query = $db->autoExecute('kiosk_information', $info, 'UPDATE', "kiosk_asset_id = $asset_id", 'SILENT');