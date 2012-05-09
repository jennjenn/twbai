<?php
require_once('initial.php');
$ugid = mysql_real_escape_string($_GET['if']);
$singlegoal = getGoalText($ugid);
?>
<head prefix="og: http://ogp.me/ns# awesomeif: 
http://ogp.me/ns/apps/awesomeif#">
<meta property="og:url" content="http://todaywouldbeawesomeif.com/if/<?php echo $ugid; ?>">
<meta property="og:title" content="Today would be awesome if...<?php echo $singlegoal;?>" /> 
</head>
	<?php
require_once('header.php');
require_once('functions-single.php');
?>


<div id="single-if">
	<h1 id="masthead">today would be <span class="awesome">awesome</span> if&hellip;</h1>
	<div id="todays-goal"><?php echo $singlegoal; ?></div>
	<?php
	$goalencode = urlencode("Today would be awesome if... $singlegoal! What would make your day awesome? http://twbai.me/if/$ugid #twbai");
	require_once('modules/share-the-awesome.php'); ?>
	<div id="supporters">
		<h3>we think this is awesome</h3>
		<p>help spread this wish - tweet it and your support will appear below!</p>
			<ul>
				<?php 
			$tweets = getTweets($ugid);
			$tweets = $tweets['results'];
			//print_r($tweets);
			foreach($tweets as $tw){
				$avatar = $tw['profile_image_url'];
				$user = $tw['from_user'];	
				echo "<li><a href='http://twitter.com/$user'><img src='$avatar' alt='$user' /></a></li>";
			}
			?>
		</ul>
	</div>
</div>

<script src='http://connect.facebook.net/en_US/all.js'></script>
<script> 
FB.init({appId: "294829963933906", status: true, cookie: true});

function postToFeed() {

	// calling the API ...
	var obj = {
		method: 'feed',
		link: 'http://todaywouldbeawesomeif.com/if/<?php echo $ugid; ?>',
		name: "Today would be awesome if... <?php echo $goaltext; ?>",
		caption: 'What would make your day awesome? Post your wish on Today would be awesome if...',
		// description: 'What would make your day awesome?'
	};

	function callback(response) {
		document.getElementById('msg').innerHTML = "Post ID: " + response['post_id'];
	}

	FB.ui(obj, callback);
}

</script>

	<?php require_once('footer.php'); ?>
