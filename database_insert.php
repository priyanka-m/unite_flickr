<?php

require_once('../../lib/base.php');
require(	'../../lib/template.php'	);

if(	!OC_USER::isLoggedIn()){
	header(	'Location:	'.OC_HELPER::linkTo('unite_flickr','index.php'));
	exit();
}	
	
OC_UTIL::addStyle('unite_flickr','style');
OC_UTIL::addScript('unite_flickr','script');
OC_APP::setActiveNavigationEntry('unite_flickr_database');

$download = $_POST['download'];
$url = $_POST['url'];
$form_used = $_POST['form_used'];
$flickr_username = $_POST['flickr_username'];
$flickr_userid = $_POST['flickr_userid']);
$service_name = "flickr";
$owncloud_username = $_SESSION['user_id'];

if($form_used == 'synchronize') {

$query = "SELECT sync FROM unite WHERE service_user = '$flickr_username' AND service_name = '$service_name' AND (resource IS NULL) ";
$sync_enabled = Plugins_Util::query_and_fetchall($query);

if($sync_enabled) {
	Plugins_Util::query("DELETE FROM unite WHERE resource IS NULL");
	$str = "Synchronization Disabled.";
}

else {
	Plugins_Util::query("INSERT INTO unite(oc_username,service_name,service_user,sync) 
	VALUES('$owncloud_username','$service_name','$flickr_username','1')");
	$msg = "Files successfully queued for Synchronization. ";
}
echo '<br/><br/>';  
}

else {
	foreach($download as $index) {
		$query = "SELECT * FROM unite WHERE service_name = 'flickr' AND resource = '$url[$index]'";
		$result = Plugins_Util::query_and_fetchall($query);
		if(!(is_array($result) and isset($result[0]))) {
			Plugins_Util::query("INSERT INTO unite(oc_username,service_name,service_user,resource)
			VALUES('$owncloud_username','$service_name','$flickr_username','$url[$index]')");
		} 
	}
echo '<br/><br/>';
$msg = "Files successfully queued for downloading";
}

$tmpl = new OC_TEMPLATE("unite_flickr","database_insert","user"	);
$tmpl->assign('msg',$msg);
$tmpl->printPage();

?>
