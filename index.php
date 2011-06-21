<?php
$RUNTIME_NOAPPS=true; //no need to load the apps
$RUNTIME_NOSETUPFS=true; //don't setup the fs yet

require_once('../../lib/base.php');
require( '../../lib/template.php' );


/* TODO: Add the functions related to the plugin tables to the below library */
require('../unite/lib/plugin_util.php');


if( !OC_USER::isLoggedIn()){
	header( 'Location: '.OC_HELPER::linkTo('index.php' ));
	exit();
} 
	
OC_UTIL::addStyle( 'unite_flickr', 'style' );
OC_UTIL::addScript( 'unite_flickr', 'script' );
OC_APP::setActiveNavigationEntry( 'unite_flickr_index' );

/* TODO: Check if the database is setup properly */
// $database_setup = are_unite_tables_setup();
$tables_setup = plugins_util::check_unite_tables_setup();
$flickr_username=$_POST['flickr_username'];
if($tables_setup) {
  if($flickr_username) {
    $tmpl = new OC_TEMPLATE( 'unite_flickr', 'getPhotos', 'user' );
    $tmpl->assign('flickr_username',$flickr_username);
    $ret=$tmpl->printPage();
    echo $ret;
  }
  else {
    $tmpl = new OC_TEMPLATE( 'unite_flickr', 'index', 'user' );
    $tmpl->printPage();
  }
} 

else {
  /* TODO: Write function to setup database */
  $create_tables=plugins_util::construct_unite_tables();  
  header( 'Location: '.OC_HELPER::linkTo( 'unite_flickr','index.php' ));
  /* TODO: Also pass the error */
  //exit();
}

?>
