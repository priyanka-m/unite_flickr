<?php
  require('apps/unite/lib/plugin_util.php');
  
  function flickr_cron_download() {
    /* 
     * This function should contain the logic for reading the unite
     * table and downloading the files which are eligible for 
     * downloading, in the respective owncloud users directory
     *
     */
     //TODO Complete this function
     Plugins_Util::connect_OC_DB();
     $result=Plugins_Util::query("SELECT * FROM unite WHERE service_name ='flickr'");
	 if(is_array($result) and isset($result[0])) {
	   //Something needs to be acted upon.
     }
	 Plugins_Util::disconnect_OC_DB();
  }
?>
