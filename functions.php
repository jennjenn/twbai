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
	$q = mysql_query("INSERT INTO users(fbid, email, first_name, last_name, time_zone) VALUES('$fbid', '$email', '$fname', '$lname', '$timezone')");
}

function getUID($fbid){
	$q = mysql_query("SELECT * FROM users WHERE fbid = $fbid");
	$r = mysql_fetch_assoc($q);
	$uid = $r['uid'];
	return $uid;
}

if($loginstate){
	//user is logged in.

	//check to see if this is their first time:
	$fbinfo = $facebook->api('/me');
	if(!isUser($fbid)){
		$email = $fbinfo['email'];
		$fname = $fbinfo['first_name'];
		$lname = $fbinfo['last_name'];
		$timezone = $fbinfo['timezone'];
		createUser($fbid, $email, $fname, $lname, $timezone);
		$uid = getUID($fbid);
	}else{
		$uid = getUID($fbid);
		$email = $fbinfo['email'];
		$fname = $fbinfo['first_name'];
	}

}

function doneToday($uid){
	if(!empty($uid)){
		$q = mysql_query("SELECT * FROM daily_goals WHERE uid = $uid and DATE(goal_date) = DATE(NOW())");
		if(mysql_num_rows($q) > 0){
			return true;
		}else{
			return false;
		}
	}
}

function myGoalToday($uid){
	$q = mysql_query("SELECT * FROM daily_goals WHERE uid = $uid and DATE(goal_date) = DATE(NOW())");
	$r = mysql_fetch_assoc($q);
	$gid = $r['ugid'];
	return $gid;
}

function getGoalText($ugid){
	$q = mysql_query("SELECT * FROM goals NATURAL JOIN daily_goals WHERE ugid = $ugid");
	$r = mysql_fetch_assoc($q);
	$text = $r['goal'];
	return $text;
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
?>