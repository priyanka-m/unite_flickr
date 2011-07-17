<?php

require('apps/unite/lib/plugin_util.php');

$download=$_['download'];
$sync=$_['sync'];
$url=$_['url'];
$flickr_username=$_['flickr_username'];
$service_name="flickr";
$owncloud_username=$_SESSION['user_id'];

Plugins_Util::connect_OC_DB();

foreach($download as $index){
    $query="SELECT * FROM unite WHERE service_name='flickr' AND resource='$url[$index]'";
	$result=Plugins_Util::query_and_fetchall($query);
    if(!(is_array($result) and isset($result[0]))) {
         Plugins_Util::query("INSERT INTO unite(oc_username,service_name,service_user,resource)
         VALUES('$owncloud_username','$service_name','$flickr_username','$url[$index]')");
         Plugins_Util::OC_DB_insertid();
    } 
}

foreach($sync as $index_sync) {
	Plugin_Util::query("INSERT INTO unite(oc_username,
			 service_name,
			 service_user,
			 resource,
			 sync,
			 cron_pattern) VALUES('$owncloud_username','$service_name','$flickr_username','$url[$index_sync]','1','00***')");
}

Plugins_Util::disconnect_OC_DB();
echo 'Files successfully queued for downloading.';
?>
