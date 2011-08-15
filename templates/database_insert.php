<?php

require_once('apps/unite/lib/plugin_util.php');
require('api/phpFlickr.php');
require('api/secrets.php');

$flickr = new phpFlickr($secret['api_key'],$secret['api_secret']);

$download=$_['download'];
$form_used=$_['form_used'];
$url=$_['url'];
$flickr_username=$_['flickr_username'];
$flickr_userid=$_['flickr_userid'];
$service_name="flickr";
$owncloud_username=$_SESSION['user_id'];

Plugins_Util::connect_OC_DB();

if($form_used=='synchronize') {

$query="SELECT sync FROM unite WHERE service_user='$flickr_username' AND service_name='$service_name' AND (resource IS NULL) ";
$sync_enabled=Plugins_Util::query_and_fetchall($query);

if($sync_enabled) {
	Plugins_Util::query("DELETE FROM unite WHERE resource IS NULL");
	$str = "Synchronization Disabled.";
}

else {
	Plugins_Util::query("INSERT INTO unite(oc_username,service_name,service_user,sync) 
	VALUES('$owncloud_username','$service_name','$flickr_username','1')");
	Plugins_Util::OC_DB_insertid();
	$str = "Files successfully queued for Synchronization. ";
}

echo '<br/><br/>';  
echo '<b>&nbsp;';
echo $str;
echo '<b/>';	

}

else {
    foreach($download as $index) {
      $query="SELECT * FROM unite WHERE service_name='flickr' AND resource='$url[$index]'";
	  $result=Plugins_Util::query_and_fetchall($query);
      if(!(is_array($result) and isset($result[0]))) {
         Plugins_Util::query("INSERT INTO unite(oc_username,service_name,service_user,resource)
         VALUES('$owncloud_username','$service_name','$flickr_username','$url[$index]')");
         Plugins_Util::OC_DB_insertid();
    } 
}


echo '<br/><br/>';
echo '<b>&nbsp;Files successfully queued for downloading.</b>';
}
Plugins_Util::disconnect_OC_DB();
?>
