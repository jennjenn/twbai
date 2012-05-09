<?php
function getTweets($ugid){
	$encoded = urlencode("http://todaywouldbeawesomeif.com/if/$ugid OR http://twbai.me/if/$ugid");
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://search.twitter.com/search.json?q=$encoded&rpp=1500&include_entities=true&result_type=recent");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$tweets = curl_exec($ch);
	curl_close($ch);
	return json_decode($tweets, true);
}
?>