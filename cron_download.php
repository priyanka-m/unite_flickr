<?php


  function flickr_cron_download() {
	require('../unite/lib/plugin_util.php');
	require_once('../../lib/base.php');
	require( '../../lib/template.php' );
	require('api/phpFlickr.php');
	require('api/secrets.php');
  
    /* 
     * This function should contain the logic for reading the unite
     * table and downloading the files which are eligible for 
     * downloading, in the respective owncloud users directory
     *
     */
     
     if(!OC_Filesystem::is_dir('/unite/unite_flickr')) {
		if(!OC_Filesystem::is_dir('/unite')) {
			OC_Filesystem::mkdir('/unite');
        }
		OC_Filesystem::mkdir('/unite/unite_flickr');
     }
      
     Plugins_Util::connect_OC_DB();
     
     $result=Plugins_Util::query("SELECT sync FROM unite WHERE resource IS NULL");
     
     if(is_array($result)) {
		$flickr = new phpFlickr($secret['api_key'],$secret['api_secret']);
		$result1=Plugins_Util::query_and_fetchall("SELECT DISTINCT service_user FROM unite");
		$flickr_username=$result1[0]['service_user'];
		$uid=$flickr->people_findByUsername($flickr_username);
		$photosets=$flickr->photosets_getList($uid['nsid']);
         
		foreach($photosets['photoset'] as $photoset) {
			$photoset_id=$photoset['id'];
			$dir_path="/unite/unite_flickr/".$photoset_id;
		
			if(!OC_Filesystem::is_dir($dir_path)) {
				OC_Filesystem::mkdir($dir_path);
			}
			
			$photos=$flickr->photosets_getPhotos($photoset_id);
			$save_to="/unite/unite_flickr/".$photoset_id."/";
		
			foreach($photos['photoset']['photo'] as $photo) {
				$url = $flickr->buildPhotoURL($photo);
				$file_name=basename($url);
		
				if(!OC_Filesystem::is_file($save_to.$file_name)) {
					$g=$save_to.$file_name;;
					$ch=curl_init($url);
					$fp=OC_Filesystem::fopen($g,'w');
					
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_FILE, $fp);
					curl_setopt ($ch, CURLOPT_HEADER ,0);
					curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,60);
					$con=curl_exec($ch); 
					curl_close($ch);
				
				}
			}
		}
   }
   
   else {
	$save_to="/unite/unite_flickr/";
	$result2=Plugins_Util::query_and_fetchall("SELECT * FROM unite WHERE service_name ='flickr' AND sync IS NULL");
    
     //Now processing single images which have to be downloaded immediately
    
    if(is_array($result2) and isset($result2[0])) {
		
		foreach($result2 as $row) {
			$resource = $row['resource'];
			$g=$save_to.basename($resource);
			$ch=curl_init($resource);
			$fp=OC_Filesystem::fopen($g,'w');
			
			curl_setopt($ch, CURLOPT_URL, $resource);
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_setopt ($ch, CURLOPT_HEADER ,0);
			curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,60);
			$con=curl_exec($ch); 
			curl_close($ch);
	  } 
    }
   } 
   
   Plugins_Util::disconnect_OC_DB();
  
  }

?>
