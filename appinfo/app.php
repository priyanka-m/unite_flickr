<?php

	OC_APP::addSettingsPage( array( 
		'id' => 'unite_flickr_index', 
		'href' => OC_HELPER::linkTo( 'unite_flickr', 'index.php' ), 
		'name' => 'unite_flickr', 
		'icon' => OC_HELPER::imagePath( 'unite', 'icon.png' )));
?>
