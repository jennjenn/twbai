<?php

function isUser($fbid){
	$q = mysql_query("SELECT * FROM users WHERE fbid = $fbid");
	if(mysql_num_rows($q) > 0){
		return true;
	}else{
		return false;
	}
}

function createUser($fbid,$email,$fname,$lname,$timezone){
	$q = mysql_query("INSERT INTO users(fbid, email, first_name, last_name, location, time_zone) VALUES('$fbid', '$email', '$fname', '$lname', '$location', '$timezone')");
}

function getUID($fbid){
	$q = mysql_query("SELECT * FROM users WHERE fbid = $fbid");
	$r = mysql_fetch_assoc($q);
	$uid = $r['uid'];
	return $uid;
}

function getEmail($uid){
	$q = mysql_query("SELECT * FROM users WHERE uid = $uid");
	$r = mysql_fetch_assoc($q);
	$email = $r['email'];
	return $email;
}

function setEmail($uid, $email){
	$q = mysql_query("UPDATE users SET email = '$email' WHERE uid = $uid");
}

function getLocation($uid){
	$q = mysql_query("SELECT * FROM users WHERE uid = $uid");
	$r = mysql_fetch_assoc($q);
	$location = $r['location'];
	return $location;
}

function setLocation($uid, $location){
	$q = mysql_query("UPDATE users SET location = '$location' WHERE uid = $uid");
}

function getFBLatLong($locID, $token){
	$facebook = new Facebook(array(

	'appId'  => '294829963933906',
	'secret' => '7337ff3d6e2b1c9999e638773fa51880',
	));
	$q = "SELECT latitude, longitude FROM place WHERE page_id = $locID";
	$latlong = $facebook->api(array(
		'method' => 'fql.query',
		'query' => $q
		)
	);
	$lat = $latlong[0]['latitude'];
	$long = $latlong[0]['longitude'];
	$latlong = "$lat,$long";
	return $latlong;
}

function getLatLong($uid){
	$q = mysql_query("SELECT * FROM users WHERE uid = $uid");
	$r = mysql_fetch_assoc($q);
	$d = $r['loc_lat_long'];
	return $d;
}

function setLatLong($uid, $latlong){
	$q = mysql_query("UPDATE users SET loc_lat_long = '$latlong' WHERE uid = $uid");
}

if($loginstate){
	//user is logged in.

	//check to see if this is their first time:
	$fbinfo = $facebook->api('/me');
		// error_log(print_r($fbinfo));
	if(!isUser($fbid)){
		$email = $fbinfo['email'];
		$fname = $fbinfo['first_name'];
		$lname = $fbinfo['last_name'];
		$timezone = $fbinfo['timezone'];
		$location = $fbinfo['location']['name'];
		createUser($fbid, $email, $fname, $lname, $loction, $timezone);
		$uid = getUID($fbid);
	}else{
		$uid = getUID($fbid);
		$email = getEmail($uid);
		$location = getLocation($uid);
		if(empty($email)){
			$email = $fbinfo['email'];			
			setEmail($uid, $email);
		}
		if(empty($location)){
			$locID = $fbinfo['location']['id'];
			$latlong = getFBLatLong($locID, $token);
			$location = $fbinfo['location']['name'];		
			setLocation($uid, $location);
			setLatLong($uid, $latlong);
		}
		$fname = $fbinfo['first_name'];
	}
}

function doneToday($uid){
	if(!empty($uid)){
		$q = mysql_query("SELECT * FROM daily_goals WHERE uid = $uid AND completed != 0 ORDER BY goal_date DESC LIMIT 1");
		if(mysql_num_rows($q) > 0){
			return true;
		}else{
			return false;
		}
	}
}

function myGoalToday($uid){
	if(!empty($uid)){
		$q = mysql_query("SELECT * FROM daily_goals WHERE uid = $uid and DATE(goal_date) = DATE(NOW())");
		$r = mysql_fetch_assoc($q);
		$gid = $r['ugid'];
		return $gid;
	}
}

function getGoalText($ugid){
	if(!empty($ugid)){
		$q = mysql_query("SELECT * FROM goals NATURAL JOIN daily_goals WHERE ugid = $ugid");
		$r = mysql_fetch_assoc($q);
		$text = $r['goal'];
		return $text;
	}
}

function todayStatus($uid){
	$q = mysql_query("SELECT * FROM daily_goals WHERE uid = $uid AND DATE(goal_date) = DATE(NOW())");
	$r = mysql_fetch_assoc($q);
	if($r > 0){
		return true;
	}else{
		return false;
	}
}

function getTodayStatus($uid){
	$q = mysql_query("SELECT * FROM daily_goals WHERE uid = $uid AND DATE(goal_date) = DATE(NOW())");
	$r = mysql_fetch_assoc($q);
	$status = $r['completed'];
	return $status;
}

function getAllTodaysAwesome(){
	$q = mysql_query("SELECT * FROM daily_goals NATURAL JOIN goals WHERE DATE(goal_date) = DATE(NOW()) ORDER BY goal_date DESC");
	$goals = array();
	while($r = mysql_fetch_assoc($q)){
		$goals[] = array('uid' => $r['uid'], 'ugid' => $r['ugid'], 'goal' => $r['goal']);
	}
	return $goals;
}

function hasActiveGoal($uid){
	$q = mysql_query("SELECT * FROM daily_goals WHERE uid = $uid AND (completed = 0 OR DATE(goal_date) = DATE(NOW())) ORDER BY goal_date DESC LIMIT 1");
	if(mysql_num_rows($q) > 0){
		return true;
	}else{
		return false;
	}
}

function getActiveGoal($uid){
	$q = mysql_query("SELECT * FROM daily_goals WHERE uid = $uid AND (completed = 0 OR DATE(goal_date) = DATE(NOW())) ORDER BY goal_date DESC LIMIT 1");
	// echo "SELECT * FROM daily_goals WHERE uid = $uid AND (completed = 0 OR DATE(goal_date) = DATE(NOW())) ORDER BY goal_date DESC LIMIT 1";
	$r = mysql_fetch_assoc($q);
	$ugid = $r['ugid'];
	return $ugid;
}
?>