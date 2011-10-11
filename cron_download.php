<?php

function flickr_cron_download() {
	require('../unite/lib/plugin_util.php');
	require_once('../../lib/base.php');
	require('api/phpFlickr.php');
	require('api/secrets.php');
  
if(!OC_Filesystem::is_dir('/unite/unite_flickr')) {
	if(!OC_Filesystem::is_dir('/unite')) {
		OC_Filesystem::mkdir('/unite');
	}
	OC_Filesystem::mkdir('/unite/unite_flickr');
	}
	
$need_sync = Plugins_Util::query_and_fetchall("SELECT sync FROM unite WHERE resource IS NULL AND sync = 1 AND service_name = 'flickr'");
if($need_sync){
	$flickr = new phpFlickr($secret['api_key'],$secret['api_secret']);
	$sync_for_users = Plugins_Util::query_and_fetchall("SELECT DISTINCT service_user FROM unite WHERE resource IS NULL");

	foreach($sync_for_users as $sync_for_user) {
		$flickr_username = $sync_for_user['service_user'];
		$uid = $flickr->people_findByUsername($flickr_username);
		$photosets = $flickr->photosets_getList($uid['nsid']);
         
		foreach($photosets['photoset'] as $photoset) {
			$photoset_id = $photoset['id'];
			$dir_path = "/unite/unite_flickr/".$photoset_id;
			if(!OC_Filesystem::is_dir($dir_path)) {
				OC_Filesystem::mkdir($dir_path);
			}
		
			$photos = $flickr->photosets_getPhotos($photoset_id);
			$save_to = "/unite/unite_flickr/".$photoset_id."/";
		
			foreach($photos['photoset']['photo'] as $photo) {
				$url = $flickr->buildPhotoURL($photo,"large");
				$file_name = basename($url);
				if(!OC_Filesystem::is_file($save_to.$file_name)) {
					$g = $save_to.$file_name;
					$ch = curl_init($url);
					$fp = OC_Filesystem::fopen($g,'w');
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_FILE, $fp);
					curl_setopt ($ch, CURLOPT_HEADER ,0);
					curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,60);
					$con = curl_exec($ch);
					curl_close($ch);
				}
			}
		}
	} 
}
   
$save_to = "/unite/unite_flickr/";
$to_be_downloaded = Plugins_Util::query_and_fetchall("SELECT * FROM unite WHERE service_name = 'flickr' AND sync IS NULL");
    
    //Now processing single images which have to be downloaded immediately
    
if(is_array($to_be_downloaded) and isset($to_be_downloaded[0])) {
	foreach($to_be_downloaded as $row) {
		$resource = $row['resource'];
		$g = $save_to.basename($resource);
		$ch = curl_init($resource);
		$fp = OC_Filesystem::fopen($g,'w');
		curl_setopt($ch, CURLOPT_URL, $resource);
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt ($ch, CURLOPT_HEADER ,0);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,60);
		curl_exec($ch);
		curl_close($ch);
		if(!curl_errno($ch))
			Plugins_Util::query("DELETE FROM unite WHERE resource = '$resource' ");
		} 
	}  
}

?>
