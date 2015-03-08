<?
require_once('../../../config.php');
require_once('../../../lib/twitterAPIExchange/TwitterAPIExchange.php');
require_once('../../../lib/i24k/twitterAPIFunctions.php');
?>
<?php

//$limit = 177;
//showRateLimit($settings);

$userFrom = "";
$userTo = "";

if (isset($_GET['userFrom'])) {$userFrom = $_GET['userFrom'];} else {$userFrom = "juanantoniobm";}
if (isset($_GET['userTo'])) {$userTo = $_GET['userTo'];} else {$userTo = "DepressedDarth";}

//echo $userFrom;
//echo $userTo;
//$userFrom = "juanantoniobm";
//$userTo = "DepressedDarth";

$flag = isFollowedBy($settings,$userFrom,$userTo);

//var_dump($result);
header('Content-Type: application/json');

$arrResult = array(
    "userFrom" => $userFrom,
    "userTo" => $userTo,
    "isFollowedBy" => $flag
);

echo json_encode($arrResult);
?>