<?php

require('api/phpFlickr.php');
require('api/secrets.php');

$flickr_username = $_['flickr_username'];

$flickr = new phpFlickr($secret['api_key'],$secret['api_secret']);

$uid=$flickr->people_findByUsername($flickr_username);

$photos = $flickr->people_getPublicPhotos($uid['nsid']);

$index=0;
$index_sync=0;

echo '<form action="database_insert.php" method="POST">';
if(empty($photos['photos'])) {
  echo '<b>Sorry, no photos found for this user</b>';
}
else {
  echo '<b>Public photos:</b><br/>';
  echo '<table>';
  echo '<tr><td><small>Download</small></td><td>&nbsp;</td><td><small>Synchronize</small></td></tr>';
  foreach($photos['photos']['photo'] as $photo) {
	echo '<tr>';
    $small_url = $flickr->buildPhotoURL($photo, "square");
    $url = $flickr->buildPhotoURL($photo, "large");
    echo '<td><input type="checkbox" NAME="download[]" VALUE="'.$index.'"/></td>';
    echo '<input type="hidden" name="url[]" value="'.$url.'"/>';
    echo '<input type="hidden" name="flickr_username" value="'.$flickr_username.'"/>';
    echo '<td>&nbsp;</td>';
    echo '<td><input type="checkbox" name="sync[]" value="'.$index_sync.'"/></td>';
    echo '<td><kimg src="'.$small_url.'" height="75" width="75"/></td>';
    echo '<td><small>'.$url.'</small></td>';
    echo '</tr>';
    $index++;
	$index_sync++;
  }
  echo '</table>';
  echo '<input type="submit" name="submit" value="Submit"/>';
  echo '</form>';
}
?>
