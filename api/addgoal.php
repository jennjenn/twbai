<?php
require_once('../initial.php');
header("Content-Type: application/json");

// print_r($_POST);
$uid = mysql_real_escape_string($_POST['uid']);
$goalraw = $_POST['goal'];
$goal = mysql_real_escape_string($goalraw);

$result = array();
$errors = array();

function goalExists($goal){
	$q = mysql_query("SELECT * FROM goals WHERE goal = '$goal'");
	if(mysql_num_rows($q) > 0){
		$r = mysql_fetch_assoc($q);
		$gid = $r['gid'];
		return $gid;
	}else{
		return false;
	}
}

function createGoal($goal, $uid){
	$q = mysql_query("INSERT INTO goals(goal, first_adder) VALUES('$goal', $uid)");
}

function addGoal($gid, $uid){
	$q = mysql_query("INSERT INTO daily_goals(uid,gid) VALUES($uid, $gid)");
}


if(empty($uid)){
	if(!isset($errors["uid"])){ $errors["uid"] = array(); }
	array_push($errors["uid"], "You're not logged in!");
}

if(empty($goal)){
	if(!isset($errors["empty_goal"])){ $errors["empty_goal"] = array(); }
	array_push($errors["empty_goal"], "Whoops! Looks like you forgot to tell us what would make today awesome!");
}

// if(hasActiveGoal($uid)){
// 	if(!isset($errors["done_today"])){ $errors["done_today"] = array(); }
// 	array_push($errors["done_today"], "Looks like you've already told us what would make today awesome! Did you want tell us how today went?");
// }


if(!empty($errors)){
	$results['success'] = false;
	$results['errors'] = $errors;
}else{
	//everything passed. add the goal.
	$gid = goalExists($goal);
	if($gid > 0){
		addGoal($gid, $uid);
	}else{
		createGoal($goal, $uid);
		$gid = goalExists($goal);
		addGoal($gid,$uid);
	}
	$results['success'] = true;
	$results['goal'] = $goalraw;
}

    echo json_encode($results);
?>