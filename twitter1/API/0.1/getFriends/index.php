<?
require_once('../../../config.php');
require_once('../../../lib/twitterAPIExchange/TwitterAPIExchange.php');
require_once('../../../lib/i24k/twitterAPIFunctions.php');
?>
<?php

//$limit = 177;
//showRateLimit($settings);

$user = "juanantoniobm";
$userToDetect = "isuriv";
isFriend($settings,$user,$userToDetect);

//$result = isFollowedBy($settings,$user,$userToDetect);
//var_dump($result);

?>