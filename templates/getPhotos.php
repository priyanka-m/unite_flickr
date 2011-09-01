<?php

require('api/phpFlickr.php');
require('api/secrets.php');

$flickr_username = $_['flickr_username'];
$flickr = new phpFlickr($secret['api_key'],$secret['api_secret']);
$uid=$flickr->people_findByUsername($flickr_username);
$photos = $flickr->people_getPublicPhotos($uid['nsid']);

$index=0;

echo '<br/><br/>';


if(empty($photos['photos'])) {
  echo '<b>&nbspSorry, no photos found for this user.</b>';
}
else {
  echo '<form action="database_insert.php" method="POST">';
  echo '<b>&nbspSynchronization: </b>';
  $sync=Plugins_Util::query_and_fetchall("SELECT sync FROM unite WHERE resource IS NULL AND sync=1 AND service_name='flickr' AND service_user='$flickr_username' ");
  
  if($sync) echo '<b><font color="green">Enabled</font></b>';
  else echo '<b><font color="gray">Disabled</font></b>';
  echo '<br/><br/>';
  
  echo '<input type="submit" value="Toggle Synchronization" name="submits"/>';
  echo '<input type="hidden" name="form_used" value="synchronize"/>';
  echo '<input type="hidden" name="flickr_username" value="'.$flickr_username.'"/>';
  echo '<br/><br/>';
  echo '</form>';
  
  
  echo '<form action="database_insert.php" method="POST">';
  echo '<input type="hidden" name="form_used" value="download"/>';
  echo '<b>Public photos:</b><br/>';
  echo '<table>';
  echo '<tr><td><small>Download</small></td><td>&nbsp</td></tr>';
  
  foreach($photos['photos']['photo'] as $photo) {
	echo '<tr>';
    $small_url = $flickr->buildPhotoURL($photo, "square");
    $url = $flickr->buildPhotoURL($photo, "large");
    echo '<td><input type="checkbox" name="download[]" value="'.$index.'"/></td>';
    echo '<input type="hidden" name="url[]" value="'.$url.'"/>';
    echo '<input type="hidden" name="flickr_username" value="'.$flickr_username.'"/>';
    echo '<input type="hidden" name="flickr_userid" value="'.$uid.'"/>';
    echo '<td>&nbsp;</td>';
    //echo '<td><img src='.$small_url.' height="75" width="75"/></td>';
    echo '<td><small>'.$url.'</small></td>';
    echo '</tr>';
    $index++;
  }
 
  echo '</table>';
  echo '<br/><br/>';
  echo '<input type="submit" name="submit" value="Submit"/>';
  echo '</form>';
}
?>
