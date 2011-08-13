<?php

require('apps/unite/lib/plugin_util.php');
require('api/phpFlickr.php');
require('api/secrets.php');

$flickr = new phpFlickr($secret['api_key'],$secret['api_secret']);

$download=$_['download'];
$sync=$_['sync'];
$url=$_['url'];
$flickr_username=$_['flickr_username'];
$flickr_userid=$_['flickr_userid'];
$service_name="flickr";
$owncloud_username=$_SESSION['user_id'];




foreach($download as $index){
    $query="SELECT * FROM unite WHERE service_name='flickr' AND resource='$url[$index]'";
	$result=Plugins_Util::query_and_fetchall($query);
    if(!(is_array($result) and isset($result[0]))) {
         Plugins_Util::query("INSERT INTO unite(oc_username,service_name,service_user,resource)
         VALUES('$owncloud_username','$service_name','$flickr_username','$url[$index]')");
         Plugins_Util::OC_DB_insertid();
    } 
}




Plugins_Util::disconnect_OC_DB();
echo '<br/><br/>';
echo 'Files successfully queued for downloading.';
?>
