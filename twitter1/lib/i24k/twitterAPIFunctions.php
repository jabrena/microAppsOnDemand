<?
$settings = array(
'oauth_access_token' => $ACCESS_TOKEN,
'oauth_access_token_secret' => $ACCESS_TOKEN_SECRET,
'consumer_key' => $CONSUMER_KEY,
'consumer_secret' => $CONSUMER_SECRET
);



function isFollowedBy($settings,$userFrom,$userTo){

	$url = "https://api.twitter.com/1.1/friendships/show.json";
	
	$requestMethod = "GET";
	$getfield = "?source_screen_name=$userFrom&target_screen_name=$userTo";
	$twitter = new TwitterAPIExchange($settings);
	$string = json_decode($twitter->setGetfield($getfield)
	->buildOauth($url, $requestMethod)
	->performRequest(),$assoc = TRUE);

	return $string["relationship"]["source"]["followed_by"];
}

function showRateLimit($settings){

	$url = "https://api.twitter.com/1.1/application/rate_limit_status.json";
	
	$requestMethod = "GET";
	$getfield = "?resources=help,users,search,statuses";
	$twitter = new TwitterAPIExchange($settings);
	$string = json_decode($twitter->setGetfield($getfield)
	->buildOauth($url, $requestMethod)
	->performRequest(),$assoc = TRUE);

	//return $string["friends_count"];
	var_dump($string);

	//echo "DEMO" . $string['resources']['friends']['/friends/list']['remaining'];

	//$limit = intval($string['resources']['friends']['/friends/list']['remaining']);

	//return intval($string ["resources"]["users"]["/users/show/:id"]["remaining"]);

	//return $limit;
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

	$arrFriends = array(); 


try {


	$flag = true;
	while ($flag) {
		//$nextCursor = getFriendPage($settings,$user,$nextCursor);
		$obj2 = getFriendPage($settings,$user,$nextCursor);
		echo $obj2["nextCursor"] . "<br />";
		$nextCursor = $obj2["nextCursor"];

		$friends = $obj2["friends"];
		foreach ($friends as &$friend) {
		    array_push($arrFriends, $friend);
		}

		if($obj2["nextCursor"] == 0){
			$flag = false;
		}

	}


} catch (Exception $e) {
    echo 'Exception: ',  $e->getMessage(), "\n";
}

	var_dump($arrFriends);

}

/*
class friends { 
    private $arrFriends = array(); 
    private $nextCursor = 0; 
    
    
    public function pushFriend($newFriend) { 
        array_push($this->arrFriends, $newFriend);
    }

    public function setFriends($arr){
    	$this->arrFriends = $arr;
    }

    public function getFriends(){
    	return $this->arrFriends;
    }

    public function setNextCursor($cursor){
    	$this->nextCursor = $cursor;
    }

    public function getNextCursor(){
    	return $this->nextCursor;
    }
}
*/

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

	$arrFriends = array(); 

	//echo "CONTADOR" . count($users);

	if(count($users) == 0){
		throw new Exception('This account reaches Twitter API Limit');
	}

	$i=0;
	foreach ($users as &$user) {
		$screenName = $user["screen_name"];

		array_push($arrFriends, $screenName);

	}

	$obj = array(
    	"friends" => $arrFriends,
    	"nextCursor" => $string["next_cursor"]
	);

	return $obj;
}

?>