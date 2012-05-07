<?php
//FACEBOOK
$app_id = "346619492058435";
$app_secret = "e60ab60e2421aaf31b85b3aa766a6101";
$my_url = "http://twbai.jennvargas.com/";

session_start();

$code = $_REQUEST["code"];

if(empty($code)) {
	$_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
	$dialog_url = "https://www.facebook.com/dialog/oauth?client_id=" 
	. $app_id . "&redirect_uri=" . urlencode($my_url) . "&scope=email,publish_actions&state="
		. $_SESSION['state'];

	$loginstate = false;
}
if($_REQUEST['state'] == $_SESSION['state']) {
	$token_url = "https://graph.facebook.com/oauth/access_token?"
	. "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
		. "&client_secret=" . $app_secret . "&code=" . $code;

	$response = file_get_contents($token_url);
	$params = null;
	parse_str($response, $params);

	$graph_url = "https://graph.facebook.com/me?access_token=" 
	. $params['access_token'];

	$user = json_decode(file_get_contents($graph_url));
	//print_r($user);
	$loginstate = true;
	//    
}else{
	//echo("The state does not match. You may be a victim of CSRF.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>today would be awesome if...</title>
	<!--[if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<link rel="stylesheet" type="text/css" href="/style.css" />
</head>
<body>
	<?php
	if($loginstate){
		echo("<div id='loggedin'><div id='user-photo'><img src='https://graph.facebook.com/$user->id/picture?access_token=" . $params['access_token']. "'></div><div id='user-name'>" . $user->name . "</div><div id='logout-prompt'>(<a href='https://www.facebook.com/logout.php?next=http://twbai.jennvargas.com&access_token=".$params['access_token']."'>log out</a>)</div></div>");
	}else{
		echo("<div id='loggedout'><a href='$dialog_url'>sign in / sign up</a></div>");
	}
	?>
	<h1 id="masthead">today would be awesome if...</h1>
	<form method="POST" action="/api/addgoal.php">
		<div id="goal-input">
		<input type="text" id="goal" name="goal" />
		<input type="submit" name="add_goal" value="let's do it" />
		</div>
	</form>
</body>
</html>