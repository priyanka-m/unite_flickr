<?php

require('api/flickr_api.php');
require('api/secrets.php');

$flickr_username = $_['flickr_username'];
$flickr = new flickr($secret);

$uid=$flickr->peopleFindByUsername($flickr_username);
$photos = $flickr->peopleGetPublicPhotos($uid);

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
  foreach($photos['photos'] as $photo)
  {
	echo '<tr>';
    $url = $flickr->getPhotoURL($photo);
    echo '<td><input type="checkbox" NAME="download[]" VALUE="'.$index.'"/></td>';
    echo '<input type="hidden" name="url[]" value="'.$url.'"/>';
    echo '<input type="hidden" name="flickr_username" value="'.$flickr_username.'"/>';
    echo '<td>&nbsp;</td>';
    echo '<td><input type="checkbox" name="sync[]" value="'.$index_sync.'"/></td>';
    echo '<td><img src="'.$url.'" height="75" width="75"/></td>';
    echo '<td><small>'.$url.'</small></td>';
    echo '</tr>';
    $index++;
	$index_sync++;
  }
  echo '</table>';
}
?>
<input type="submit" name="submit" value="Submit"/>
</form>
