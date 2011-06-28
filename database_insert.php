<?php
//$RUNTIME_NOAPPS=true; 
//$RUNTIME_NOSETUPFS=true; 

require_once('../../lib/base.php');
require( '../../lib/template.php' );

if( !OC_USER::isLoggedIn()){
	header( 'Location: '.OC_HELPER::linkTo('unite_flickr','index.php' ));
	exit();
} 
	
OC_UTIL::addStyle( 'unite_flickr', 'style' );
OC_UTIL::addScript( 'unite_flickr', 'script' );
OC_APP::setActiveNavigationEntry( 'unite_flickr_database');


$tmpl = new OC_TEMPLATE( "unite_flickr", "database_insert", "user" );
$tmpl->assign('download',$_POST['download']);
$tmpl->assign('url',$_POST['url']);
$tmpl->assign('sync',$_POST['sync']);
$tmpl->assign('flickr_username',$_POST['flickr_username']);

$tmpl->printPage();

?>
