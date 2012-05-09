<?php
require_once('initial.php');
$todaysgoal = myGoalToday($uid);
$goaltext = getGoalText($todaysgoal);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US"
xmlns:fb="https://www.facebook.com/2008/fbml">
<head prefix="og: http://ogp.me/ns# awesomeif: 
http://ogp.me/ns/apps/awesomeif#">
<meta charset="utf-8" />
<meta property="fb:app_id" content="294829963933906" /> 
<meta property="og:type" content="website" /> 
<meta property="og:title" content="Today would be awesome if...<?php echo $goaltext;?>" /> 
<meta property="og:image" content="http://todaywouldbeawesomeif.com/imgs/if-icon.png" /> 
<meta property="og:description" content="" /> 
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<title>today would be awesome if...</title>
<!--[if IE]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<!-- <link href='http://fonts.googleapis.com/css?family=Cabin:400,500,600,700,400italic,500italic,600italic,700italic|Cabin+Condensed:400,500,600,700' rel='stylesheet' type='text/css'> -->
<link href='http://fonts.googleapis.com/css?family=Amatic+SC:400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="/style.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
</head>
<body onload="initialize()">
	<div id="header">
			<div id="title"><a href="/">today would be <span class="awesome">awesome</span> if...</a></div>
			<div id='whats-this'>(what is this?)</div>
			<div id='whats-this-popup'>
				<h3>What is this, you say?</h3>
				<p>"Today would be <span class="awesome">awesome</span> if..." is just a little experiment. Once a day, come here and tell us what would make your day awesome. At the end of the day you can come back and let us know whether or not your day turned out to be awesome. It's that simple.</p>
				<p>Your awesomeness will be posted <strong>anonymously</strong>. We just use the Facebook connection to link you with your wish so you can update it at the end of the day. If you choose, you can post your awesomeness to Twitter or Facebook, but that's entirely up to you.</p>
				<p><a href="http://twitter.com/twbai">Follow twbai on Twitter</a></p>
			</div>
					<div id='header-user'>
			<?php
		if($loginstate){
			echo("Hey there, <div id='user-name'>" . $fname . "!</div><div id='logout-prompt'>(<a href='".$fburl."'>log out</a>)</div>");
		}else{
			echo("<a href='$fburl'>sign in / sign up with facebook</a>");
		}
		?>
	</div>
</div>