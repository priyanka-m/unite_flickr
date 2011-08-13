<?php

require_once('../../lib/base.php');
require( '../../lib/template.php' );

require('../unite/lib/plugin_util.php');

if( !OC_USER::isLoggedIn()){
	header( 'Location: '.OC_HELPER::linkTo("",'index.php' ));
	exit();
} 
	
OC_UTIL::addStyle( 'unite_flickr', 'style' );
OC_UTIL::addScript( 'unite_flickr', 'script' );
OC_APP::setActiveNavigationEntry( 'unite_flickr_index' );

$tables_setup = plugins_util::check_unite_tables_setup();
$flickr_username=$_POST['flickr_username'];


if($tables_setup) {
  if($flickr_username) {
    $tmpl = new OC_TEMPLATE( 'unite_flickr', 'getPhotos', 'user' );
    $tmpl->assign('flickr_username',$flickr_username);
    $tmpl->printPage();
    
  }
  else {
    $tmpl = new OC_TEMPLATE( 'unite_flickr', 'index', 'user' );
    $tmpl->printPage();
  }
} 

else {
  $create_tables=plugins_util::construct_unite_tables();  
  header( 'Location: '.OC_HELPER::linkTo( 'unite_flickr','index.php' ));
}

?>
