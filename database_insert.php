<?php
$RUNTIME_NOAPPS=true; //no need to load the apps
$RUNTIME_NOSETUPFS=true; //don't setup the fs yet

require_once('../../lib/base.php');
require( '../../lib/template.php' );

require('../unite/lib/plugin_util.php');

if( !OC_USER::isLoggedIn()){
	header( 'Location: '.OC_HELPER::linkTo('index.php' ));
	exit();
} 

//TODO:make the page stylized
	
OC_UTIL::addStyle( 'unite_flickr', 'style' );
OC_UTIL::addScript( 'unite_flickr', 'script' );
OC_APP::setActiveNavigationEntry( 'unite_flickr_index' );

$dld=$_POST["dld"];
$url=$_POST["url"];
$sync=$_POST["sync"];
$flickr_username=$_POST["flickr_username"];
$service_name="flickr";

Plugins_Util::connect_OC_DB();

foreach($dld as $index){
	echo "You are about to download the following files!!";
	echo '<br/>';
	echo $index." ";
	echo $url[$index];
	echo '<br/><br/>';
	$result=Plugins_Util::query("SELECT * FROM unite where resource='$url[$index]'");
	$num_rows = mysql_num_rows($result);
	echo '<br/>';
    if($num_rows == 0){
         Plugins_Util::query("INSERT INTO unite(oc_username,service_name,service_user,resource)
         VALUES('$owncloud_username','$service_name','$flickr_username','$url[$index]')")or die(mysql_error());  
         Plugins_Util::OC_DB_insertid();
         echo '<br/><br/>';
    }
    else{
		echo 'Already downloaded!!';
		echo '<br/><br/>';
	}
  
}


foreach($sync as $index_sync)
{
	echo $index_sync," ";
	echo $url[$index_sync];
	echo '<br/><br/>';
	mysql_query("insert into unite1(oc_username,
			 service_name,
			 service_user,
			 resource,
			 sync,
			 cron_pattern) values('$owncloud_username','$service_name','$flickr_username','$url[$index_sync]','1','00***')") or die(mysql_error());
	echo '<br/><br/>';
}

Plugins_Util::disconnect_OC_DB();
?>
