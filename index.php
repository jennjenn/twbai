<?php
require_once('initial.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>today would be awesome if...</title>
	<!--[if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<!-- <link href='http://fonts.googleapis.com/css?family=Cabin:400,500,600,700,400italic,500italic,600italic,700italic|Cabin+Condensed:400,500,600,700' rel='stylesheet' type='text/css'> -->
	<link href='http://fonts.googleapis.com/css?family=Amatic+SC:400,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="/style.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
</head>
<body>
	<div id="header">
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
<h1 id="masthead">today would be <span class="awesome">awesome</span> if...</h1>
<?php
if(!$loginstate){
	//not logged in. show today's awesome.
	$todaysawesome = getAllTodaysAwesome();
//	print_r($todaysawesome);
	?>
	<ul id="todays-goal" class="todays-goal-list">
		<?php foreach($todaysawesome as $awesome){ ?>	
		<li><?php echo $awesome['goal']; ?></li>
<?php } ?>
	</ul>
	<div id="todays-awesome" class="clearfix"><span class="awesome-share">What would make your day awesome?</span> <div class="fb-login-button"><a class="fb_button fb_button_xlarge" href="<?php echo $fburl; ?>"><span class="fb_button_text">Log In with Facebook</span></a></div></div>
	<?php
}else{
	if(!doneToday($uid)){ ?>
		<form id='goal-create' method="POST" action="/api/addgoal.php">
			<div id="goal-input">
				<input type='hidden' name='uid' value="<?php echo $uid; ?>">
				<input type="text" id="goal" name="goal" />
				<input type="submit" id="submit-goal" name="add_goal" value="let's do it" />
			</div>
		</form>
		<div id='todays-goal'></div>
		<div class="result"></div>
		<?php
}else{ 
	$todaysgoal = doneToday($uid);
	$goaltext = getGoalText($todaysgoal);
	?>
	<div id='todays-goal'><?php echo $goaltext; ?></div>
	<?php
	if(!todayStatus($uid)){
		?>
		<div id='todays-awesome' class="clearfix">
			So&hellip; was today awesome?
			<div id='awesome-buttons'>
				<div id='awesome-yes' class="button">Yes!</div>
				<div id='awesome-no' class="button">No :(</div>
			</div>
		</div>
		<?php
}else{

	$status = getTodayStatus($uid, $todaysgoal);
	if($status == 1){
		$goalencode = urlencode("I said today would be awesome if $goaltext... and it was! What would make your day awesome? http://todaywouldbeawesomeif.com #twbai");
		$string = "Today was <span class='awesome'>awesome</span>! Yay! <div id='awesome-buttons' class='awesome-share'>Share the awesome: <a onclick='postToFeed(); return false;'><img src='/imgs/icon-facebook.png'></a> <a href='https://twitter.com/intent/tweet?button_hashtag=twbai&text=$goalencode' class='class=twitter-share-button' data-related='twbai' data-url='http://todaywouldbeawesomeif.com'><img src='/imgs/icon-twitter.png'></a></div>";
	}elseif($status == 2){
		$string = "OK so today wasn't so awesome. No big! We can fix that tomorrow!";
	}
	?>
	<div id="todays-awesome"><?php echo $string; ?></div>
	<?php
}
}
}
?>
<div id="fb-root"></div>
<script>
$('#goal-create').submit(function(e){
	e.preventDefault();
	$(".result").empty();
	$.ajax({
		type: "POST",
		url: "api/addgoal.php",
		data: $(this).serialize(),
		async: false,
		dataType: "json",
	}).done(function(msg){
		if(msg.success){
			$('#goal-create').hide();
			$('#todays-goal').html(msg.goal);

		}else{
			$.each(msg.errors, function(type, errors) {
				$(".result").html("<p>" + errors + "</p>");
			});
		}
	});
});

$('#awesome-yes').click(function(){
	$.ajax({
		type: "POST",
		url: "api/updategoal.php",
		data: {uid: <?php echo $uid; ?>, status: 1},
		async: false,
		dataType: "json",
	}).done(function(msg){
		if(msg.success){
			$('#todays-awesome').empty();
			$('#todays-awesome').html('nicely done!');

		}else{
			$.each(msg.errors, function(type, errors) {
				$("#todays-awesome").html("<p>" + errors + "</p>");
			});
		}
	});
});

$('#awesome-no').click(function(){
	$.ajax({
		type: "POST",
		url: "api/updategoal.php",
		data: {uid: <?php echo $uid; ?>, status: 2},
		async: false,
		dataType: "json",
	}).done(function(msg){
		if(msg.success){
			$('#todays-awesome').empty();
			$('#todays-awesome').html('nicely done!');

		}else{
			$.each(msg.errors, function(type, errors) {
				$("#todays-awesome").html("<p>" + errors + "</p>");
			});
		}
	});
});
</script>
<script>
window.fbAsyncInit = function() {
	FB.init({
		appId      : '346619492058435', // App ID
		// channelUrl : '//WWW.YOUR_DOMAIN.COM/channel.html', // Channel File
		status     : true, // check login status
		cookie     : true, // enable cookies to allow the server to access the session
		xfbml      : true  // parse XFBML
	});

	// Additional initialization code here
};

// Load the SDK Asynchronously
(function(d){
	var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
	if (d.getElementById(id)) {return;}
	js = d.createElement('script'); js.id = id; js.async = true;
	js.src = "//connect.facebook.net/en_US/all.js";
	ref.parentNode.insertBefore(js, ref);
	}(document));
	</script>
	<script> 
	FB.init({appId: "346619492058435", status: true, cookie: true});

	function postToFeed() {

		// calling the API ...
		var obj = {
			method: 'feed',
			link: 'http://todaywouldbeawesomeif.com',
			picture: 'http://fbrell.com/f8.jpg',
			name: 'Today would be awesome if <?php echo $goaltext; ?>',
			caption: 'What would make your day awesome? Post your wish on todaywouldbeawesomeif...',
			// description: 'What would make your day awesome?'
		};

		function callback(response) {
			document.getElementById('msg').innerHTML = "Post ID: " + response['post_id'];
		}

		FB.ui(obj, callback);
	}

	</script>
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
	<script type="text/javascript">

	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-585912-20']);
	_gaq.push(['_trackPageview']);

	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();

		</script>
	</body>
	</html>