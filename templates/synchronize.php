<?php

require('apps/unite/lib/plugin_util.php');
require('api/phpFlickr.php');
require('api/secrets.php');

$flickr_username=$_['flickr_username'];
$service_name="flickr";
$owncloud_username=$_SESSION['user_id'];

Plugins_Util::connect_OC_DB();

$sync=Plugins_Util::query_and_fetchall("SELECT sync FROM unite WHERE resource IS NULL");

if($sync) {
	Plugins_Util::query("DELETE FROM unite WHERE resource IS NULL");
	echo "Synchronization Disabled ! Go back to download your files !";
}
else {
	Plugins_Util::query("INSERT INTO unite(oc_username,service_name,service_user,sync) 
	VALUES('$owncloud_username','$service_name','$flickr_username','1')");
	echo "Files successfully queued for Synchronization ";
}
  

Plugins_Util::disconnect_OC_DB;
?>
