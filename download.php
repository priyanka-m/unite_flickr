<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = 'abcd';
mysql_connect($dbhost, $dbuser, $dbpass) or die('Error connecting to mysql');

$dbname = 'owncloud';
mysql_select_db($dbname) or die("error selecting database");
$query = ("SELECT *
FROM downloads");
$result = mysql_query($query);
while ($row = mysql_fetch_object($result))
{
	$download_now=$row->url;
	echo $download_now, "\n";
	$save_to="/home/priyanka/Desktop";
	$g=$save_to.basename($download_now);
	if(!is_file($g)){
		$conn=curl_init($download_now);
		$fp=fopen($g,"w");
		curl_setopt ($conn, CURLOPT_FILE, $fp);
		curl_setopt ($conn, CURLOPT_HEADER ,0);
		curl_setopt($conn,CURLOPT_CONNECTTIMEOUT,60);
		curl_exec($conn);
		if(curl_errno($conn)==0){
			mysql_query("DELETE FROM downloads WHERE url='$download_now'") or die(mysql_error()); 
		}
		curl_close($conn);
		fclose($fp);
 }
}
mysql_close();
?>
