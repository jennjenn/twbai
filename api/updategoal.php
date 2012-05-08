<?php
require_once('../initial.php');
header("Content-Type: application/json");

// print_r($_POST);
$uid = mysql_real_escape_string($_POST['uid']);
//$goal = mysql_real_escape_string($_POST['gid']);
$status = mysql_real_escape_string($_POST['status']);

$result = array();
$errors = array();

function updateStatus($uid, $status){
	$q = mysql_query("UPDATE daily_goals SET completed = $status WHERE uid = $uid AND DATE(goal_date) = DATE(NOW())");
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
	
	updateStatus($uid, $status);
	$results['success'] = true;
	$results['status'] = $status;
}

    echo json_encode($results);
?>