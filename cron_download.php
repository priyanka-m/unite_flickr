<?php
  require('../unite/lib/plugin_util.php');
  
  function flickr_cron_download() {
    /* 
     * This function should contain the logic for reading the unite
     * table and downloading the files which are eligible for 
     * downloading, in the respective owncloud users directory
     *
     */
     //TODO Complete this function
     Plugins_Util::connect_OC_DB();
     $result=Plugins_Util::query_and_fetchall("SELECT * FROM unite WHERE service_name ='flickr' AND sync IS NULL");
     //Now processing single images which have to be downloaded immediately
     if(is_array($result) and isset($result[0])) {
	   foreach($result as $row) {
	     $resource = $row['resource'];
	     //Download the image
	   }
     }
	 Plugins_Util::disconnect_OC_DB();
  }
?>
