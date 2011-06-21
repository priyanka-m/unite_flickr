<?php

require('api/flickr_api.php');
require('api/secrets.php');

$flickr_uname = $_['flickr_username'];
$flickr = new flickr($secret);

$uid=$flickr->peopleFindByUsername($flickr_uname);
$photos = $flickr->peopleGetPublicPhotos($uid);

$index=0;
$index_sync=0;
echo '<form action="#" method="POST">';

if(empty($photos['photos'])) {
  echo '<b>Sorry, no photos found for this user</b>';
}
else {
  echo '<b>Public photos:</b><br/>';
  echo '<table>';
  foreach($photos['photos'] as $photo)
  {
	echo '<tr>';
    $url = $flickr->getPhotoURL($photo);
    echo '<td>download:<input type="checkbox" NAME="dld[]" VALUE="'.$index.'"/></td>';
    echo '<td><input type="hidden" name="url[]" value="'.$url.'"/></td>';
    echo '<td>sync:<input type="checkbox" name="sync[]" value="'.$index_sync.'"/></td>';
    echo '<td><img src="'.$url.'" height="75" width="75"/></td>';
    echo '<td>'.$url.'</td>';
    echo '</tr>';
    $index++;
	$index_sync++;
  }
  echo '</table>';
}
?>
<input type="submit" name="submit" value="submit"/>
</form>
