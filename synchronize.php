<?php

require_once('../../lib/base.php');
require( '../../lib/template.php' );

if( !OC_USER::isLoggedIn()){
	header( 'Location: '.OC_HELPER::linkTo('unite_flickr','index.php' ));
	exit();
}
	
OC_UTIL::addStyle( 'unite_flickr', 'style' );
OC_UTIL::addScript( 'unite_flickr', 'script' );
OC_APP::setActiveNavigationEntry( 'unite_flickr_synchronize');

$tmpl = new OC_Template( "unite_flickr", "synchronize", "user" );
$tmpl->printPage();

?>
