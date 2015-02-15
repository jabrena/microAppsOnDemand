<?
require_once('./config.php');
require_once('./lib/twitterAPIExchange/TwitterAPIExchange.php');
?>
<?php

/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
$settings = array(
'oauth_access_token' => $ACCESS_TOKEN,
'oauth_access_token_secret' => $ACCESS_TOKEN_SECRET,
'consumer_key' => $CONSUMER_KEY,
'consumer_secret' => $CONSUMER_SECRET
);
$url = "https://api.twitter.com/1.1/statuses/user_timeline.json";


//https://dev.twitter.com/rest/reference/get/friends/list
$url = "https://api.twitter.com/1.1/friends/list.json";

$url = "https://api.twitter.com/1.1/users/show.json";



//?cursor=-1&screen_name=twitterapi&skip_status=true&include_user_enti

$requestMethod = "GET";
if (isset($_GET['user'])) {$user = $_GET['user'];} else {$user = "juanantoniobm";}
if (isset($_GET['count'])) {$user = $_GET['count'];} else {$count = 20;}
$getfield = "?screen_name=$user&count=$count";
$twitter = new TwitterAPIExchange($settings);
$string = json_decode($twitter->setGetfield($getfield)
->buildOauth($url, $requestMethod)
->performRequest(),$assoc = TRUE);

//var_dump($string);

echo $string["friends_count"];

//$users = $string["users"];

//echo $string["next_cursor"];

//var_dump(json_decode($string));

//$json_string = json_encode($string, JSON_PRETTY_PRINT);

/*
$i=0;
foreach ($users as &$user) {
	$screenName = $user["screen_name"];
	$i++;
	echo $i;
	echo $screenName;
	echo "<br/>";
    //var_dump($user);
}
*/


//prettyPrint($string);

/*
if($string["errors"][0]["message"] != "") {echo "<h3>Sorry, there was a problem.</h3><p>Twitter returned the following error message:</p><p><em>".$string[errors][0]["message"]."</em></p>";exit();}
foreach($string as $items)
    {
        echo "Time and Date of Tweet: ".$items['created_at']."<br />";
        echo "Tweet: ". $items['text']."<br />";
        echo "Tweeted by: ". $items['user']['name']."<br />";
        echo "Screen name: ". $items['user']['screen_name']."<br />";
        echo "Followers: ". $items['user']['followers_count']."<br />";
        echo "Friends: ". $items['user']['friends_count']."<br />";
        echo "Listed: ". $items['user']['listed_count']."<br /><hr />";
    }
    */
?>