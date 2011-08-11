<?php
 require('../unite/lib/plugin_util.php');
 require_once('../../lib/base.php');
 require( '../../lib/template.php' );
  
  function flickr_cron_download() {
    /* 
     * This function should contain the logic for reading the unite
     * table and downloading the files which are eligible for 
     * downloading, in the respective owncloud users directory
     *
     */
     
     if(!OC_FILESYSTEM::is_dir('/unite/unite_flickr')) {
		if(!OC_FILESYSTEM::is_dir('/unite')) {
			OC_FILESYSTEM::mkdir('/unite');
        }
	    OC_FILESYSTEM::mkdir('/unite/unite_flickr');
     }
     $save_to="/unite/unite_flickr/";
     
     Plugins_Util::connect_OC_DB();
     $result=Plugins_Util::query_and_fetchall("SELECT * FROM unite WHERE service_name ='flickr' AND sync IS NULL");
     
     //Now processing single images which have to be downloaded immediately
     if(is_array($result) and isset($result[0])) {
	   foreach($result as $row) {
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
	 Plugins_Util::disconnect_OC_DB();
  }

?>
