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
	echo "You are about to download the following files.";
	echo '<br/>';
	echo $url[$index];
	echo '<br/><br/>';
	$result=Plugins_Util::query("SELECT * FROM unite WHERE resource='$url[$index]'");
	$num_rows = mysql_num_rows($result);
	echo '<br/>';
    if($num_rows == 0) {
         Plugins_Util::query("INSERT INTO unite(oc_username,service_name,service_user,resource)
         VALUES('$owncloud_username','$service_name','$flickr_username','$url[$index]')")or die(mysql_error());  
         Plugins_Util::OC_DB_insertid();
         echo '<br/><br/>';
    }
    else {
		echo 'Already downloaded.';
		echo '<br/><br/>';
	}
  
}


foreach($sync as $index_sync) {
	echo $index_sync," ";
	echo $url[$index_sync];
	echo '<br/><br/>';
	Plugin_Util::query("INSERT INTO unite(oc_username,
			 service_name,
			 service_user,
			 resource,
			 sync,
			 cron_pattern) VALUES('$owncloud_username','$service_name','$flickr_username','$url[$index_sync]','1','00***')") or die(mysql_error());
	echo '<br/><br/>';
}

Plugins_Util::disconnect_OC_DB();


?>
