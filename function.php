<?php

function get_terminal_info($where)
{
	return $result = $GLOBALS['db']->getAll("select latlng,formatted_address,kiosk_asset_id,kiosk_address,kiosk_floor_id,kiosk_location,kiosk_location_2 from kiosk_information $where");//20个点位同时infoWindow.open client performance达到极限。
}
function get_terminal_count($where)
{
	return $result = $GLOBALS['db']->getOne("select count(1) from kiosk_information $where");
}
function get_nearby_place($kiosk_asset_id)
{
	return $result = $GLOBALS['db']->getAll("select kiosk_asset_id,pkid,guid,name,lat,lon,link,tel,addr,dist from jiepang_didian_content where kiosk_asset_id ='$kiosk_asset_id' ");
}
function get_icon($pkid)
{
	$icon_path=$GLOBALS['db']->getOne("select categories_icon from jiepang_didian_content_categories where parentpkid ='$pkid' and categories_is_primary = '1'");
	if($icon_path)
		return 'http://www.jiepang.com'.$icon_path;
	else
		return 'http://www.jiepang.com/static/img/categories/public/default.gif';
}