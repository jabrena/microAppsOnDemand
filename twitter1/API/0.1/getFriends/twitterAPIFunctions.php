<?
$settings = array(
'oauth_access_token' => $ACCESS_TOKEN,
'oauth_access_token_secret' => $ACCESS_TOKEN_SECRET,
'consumer_key' => $CONSUMER_KEY,
'consumer_secret' => $CONSUMER_SECRET
);


function showRateLimit($settings){

	$url = "https://api.twitter.com/1.1/application/rate_limit_status.json";
	
	$requestMethod = "GET";
	$getfield = "?resources=help,users,search,statuses";
	$twitter = new TwitterAPIExchange($settings);
	$string = json_decode($twitter->setGetfield($getfield)
	->buildOauth($url, $requestMethod)
	->performRequest(),$assoc = TRUE);

	//return $string["friends_count"];
	//var_dump($string);

	return intval($string ["resources"]["users"]["/users/show/:id"]["remaining"]);
}

function getFriendCount($settings,$user){

	$url = "https://api.twitter.com/1.1/users/show.json";

	$requestMethod = "GET";
	$getfield = "?screen_name=$user";
	$twitter = new TwitterAPIExchange($settings);
	$string = json_decode($twitter->setGetfield($getfield)
	->buildOauth($url, $requestMethod)
	->performRequest(),$assoc = TRUE);

	return $string["friends_count"];
}

function isFriend($settings,$user,$userToDetect){

	$nextCursor = 0;

	$flag = true;
	while ($flag) {
		$nextCursor = getFriendPage($settings,$user,$nextCursor);

		if($nextCursor == 0){
			$flag = false;
		}
	}

}

function getFriendPage($settings,$user,$nextCursor){

	$url = "https://api.twitter.com/1.1/friends/list.json";

	$requestMethod = "GET";
	
	//https://dev.twitter.com/overview/api/cursoring
	if($nextCursor != 0){
		$getfield = "?screen_name=$user&count=200&cursor=$nextCursor";
	}else{
		$getfield = "?screen_name=$user&count=200";
	}
	$twitter = new TwitterAPIExchange($settings);
	$string = json_decode($twitter->setGetfield($getfield)
	->buildOauth($url, $requestMethod)
	->performRequest(),$assoc = TRUE);
	$users = $string["users"];
	//var_dump($users);

	$i=0;
	foreach ($users as &$user) {
		$screenName = $user["screen_name"];
		$i++;
		echo $i . " ";
		echo $screenName;
		echo "<br/>";
	    //var_dump($user);
	}
	//echo $string["next_cursor"];

	return $string["next_cursor"];
}
?>