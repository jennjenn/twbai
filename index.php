<?php
require_once('connect.php');
require_once('initial.php');
//FACEBOOK
$app_id = "346619492058435";
$app_secret = "e60ab60e2421aaf31b85b3aa766a6101";
$my_url = "http://todaywouldbeawesomeif.com/";

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
	<link href='http://fonts.googleapis.com/css?family=Cabin:400,500,600,700,400italic,500italic,600italic,700italic|Cabin+Condensed:400,500,600,700' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Amatic+SC:400,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="/style.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
</head>
<body>
	<div id="header">
		<div id='header-user'>
			<?php
		if($loginstate){
			echo("Hey there, <div id='user-name'>" . $user->name . "</div><div id='logout-prompt'>(<a href='https://www.facebook.com/logout.php?next=http://todaywouldbeawesomeif.com&access_token=".$params['access_token']."'>log out</a>)</div>");
		}else{
			echo("<a href='$dialog_url'>sign in / sign up</a>");
		}
		?>
	</div>
</div>
<h1 id="masthead">today would be <span class="awesome">awesome</span> if...</h1>
<form method="POST" id='goal-create' action="/api/addgoal.php">
	<div id="goal-input">
		<input type='hidden' id='userid' value="<?php echo $uid; ?>">
		<input type="text" id="goal" name="goal" />
		<input type="submit" id="submit-goal" name="add_goal" value="let's do it" />
	</div>
</form>
<div class="result"></div>
<script>
	$('#goal-create').submit(function(){
		//alert('submitted!');
		$.post('api/addgoal.php', $(this).serialize(), function(data) {
		  $('.result').html(data);
		});
	});
</script>
</body>
</html>