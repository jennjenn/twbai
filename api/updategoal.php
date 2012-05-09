<?php
require_once('../initial.php');
header("Content-Type: application/json");

// print_r($_POST);
$ugid = mysql_real_escape_string($_POST['ugid']);
$uid = mysql_real_escape_string($_POST['uid']);
//$goal = mysql_real_escape_string($_POST['gid']);
$status = mysql_real_escape_string($_POST['status']);

$result = array();
$errors = array();

function updateStatus($ugid, $status){
	$q = mysql_query("UPDATE daily_goals SET completed = $status WHERE ugid = $ugid");
}

if(empty($uid)){
	if(!isset($errors["uid"])){ $errors["uid"] = array(); }
	array_push($errors["uid"], "You're not logged in!");
}

if(!empty($errors)){
	$results['success'] = false;
	$results['errors'] = $errors;
}else{
	//everything passed. add the goal.
	
	updateStatus($ugid, $status);
	
	//can this user add a new goal today?
	$todaygoal = myGoalToday($uid);
		$results['newgoal'] = true;

	
	//move on.
	$results['success'] = true;
	$results['status'] = $status;
}

    echo json_encode($results);
?>