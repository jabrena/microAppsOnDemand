<?
require_once('../../../config.php');
require_once('../../../lib/twitterAPIExchange/TwitterAPIExchange.php');
require_once('twitterAPIFunctions.php');
?>
<?php

$limit = 177;

//echo showRateLimit($settings);

$user = "juanantoniobm";
//$friendCounter = getFriendCount($settings,$user);
//echo $friendCounter;

if(showRateLimit($settings) > $limit){
	$userToDetect = "isuriv";
	isFriend($settings,$user,$userToDetect);
}

?>