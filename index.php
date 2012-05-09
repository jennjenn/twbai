<?php
require_once('header.php');
?>
<div id="fb-root"></div>
<h1 id="masthead">today would be <span class="awesome">awesome</span> if&hellip;</h1>
<?php
if(!$loginstate){
	//not logged in. ==============================================

	//show today's awesome
	require_once('modules/awesome-list.php');
	?>
	<div id="login-prompt" class="clearfix">
		<span class="awesome-share">What would make your day awesome?</span> 
		<div class="fb-login-button"><a class="fb_button fb_button_xlarge" href="<?php echo $fburl; ?>"><span class="fb_button_text">Log In with Facebook</span></a></div>
	</div>

	<?php
}else{
	//logged in ==============================================

	// is there an active goal? 
	if(!hasActiveGoal($uid)){
		// NO active goal
		require_once('modules/wish-input.php');
		?>
		<div id='no-active-goal'>
			<div id='todays-goal'></div>
			<?php
		require_once('modules/awesome-update-prompt.php');
		require_once('modules/share-the-awesome.php');	
		?>
		<div id="todays-goals-all">
			<h2>Others have said today would be <span class="awesome">awesome</span> if&hellip;</h2>
			<?php require_once('modules/awesome-list.php'); ?>
		</div>
	</div>
	<?php
}else{
	// YES there's an active goal
	$ugid = getActiveGoal($uid);
	$goaltext = getGoalText($ugid);
	?>
	<div id='todays-goal'><?php echo $goaltext; ?></div>
	<?php
	if(getTodayStatus($uid) == 0){
		// this goal hasn't been updated today
		require_once('modules/awesome-update-prompt.php');
		require_once('modules/share-the-awesome.php');
		?>
		<div id="no-active-goal">
			<?php require_once('modules/wish-input.php'); ?>
		</div>
		<?php
}else{
	// this goal was updated today.
	$status = getTodayStatus($uid, $gid);
	if($status == 1){
		// completed!
		$goalencode = urlencode("I said today would be awesome if $goaltext... and it was! What would make your day awesome? http://todaywouldbeawesomeif.com #twbai");
		$string = "Today was <span class='awesome'>awesome</span>! Yay! <div id='awesome-buttons' class='awesome-share'>Share the awesome: <a onclick='postToFeed(); return false;'><img src='/imgs/icon-facebook.png'></a> <a href='https://twitter.com/intent/tweet?button_hashtag=twbai&text=$goalencode' class='class=twitter-share-button' data-related='twbai' data-url='http://todaywouldbeawesomeif.com'><img src='/imgs/icon-twitter.png'></a></div>";

	}elseif($status == 2){
		// not completed. boo.
		$string = "Today wasn't so awesome? No big! We can fix that tomorrow!";
	}
}

if(!doneToday($uid)){ ?>

	<div id='share-the-awesome' style="display:none">
		<?php $goalencode = urlencode("I said today would be awesome if... $goaltext! What would make your day awesome? http://todaywouldbeawesomeif.com #twbai"); ?>
			<div id='awesome-buttons' class='awesome-share'>Share the awesome: <a onclick='postToFeed(); return false;'><img src='/imgs/icon-facebook.png'></a> <a href='https://twitter.com/intent/tweet?button_hashtag=twbai&text=<?php echo $goalencode;?>' class='class=twitter-share-button' data-related='twbai' data-url='http://todaywouldbeawesomeif.com'><img src='/imgs/icon-twitter.png'></a></div>
		</div>
		<div class="result"></div>
		<?php
}else{ 
	?>
	<!-- <div id="todays-awesome" class="clearfix"><?php //echo $string; ?></div> -->
	<?php
}
?>
<div id="todays-goals-all">
	<h2>Others have said today would be <span class="awesome">awesome</span> if&hellip;</h2>
	<?php require_once('modules/awesome-list.php'); ?>
</div>
<?php
}
}
$ugid = getActiveGoal($uid);
if(empty($ugid)){$ugid = 0;}
?>
<script>
$('#goal-create').submit(function(e){
	e.preventDefault();
	//$(".result").empty();
	$.ajax({
		type: "POST",
		url: "api/addgoal.php",
		data: $(this).serialize(),
		async: false,
		dataType: "json",
	}).done(function(msg){
		if(msg.success){
			$('#goal-create').hide();
			$('#no-active-goal').fadeIn();
			$('#todays-goal').html(msg.goal);
			$('#share-the-awesome').fadeIn();
			$('#todays-awesome').hide();
		}else{
			$.each(msg.errors, function(type, errors) {
				$(".result").html("<p>" + errors + "</p>");
			});
		}
	});
	return false;
});

$('#awesome-yes').click(function(){
	$.ajax({
		type: "POST",
		url: "api/updategoal.php",
		data: {ugid: <?php echo $ugid; ?>, uid: <?php echo $uid; ?>, status: 1},
		async: false,
		dataType: "json",
	}).done(function(msg){
		if(msg.success){
			$('#todays-awesome').empty();
			$('#todays-goal').empty();
			$('#share-the-awesome').hide();
			$('#todays-awesome').html('nicely done!');
			if(msg.newgoal){
				$('#todays-awesome').append(' what about today?');
				$('#no-active-goal').fadeIn();
			}else{
				$('#todays-awesome').append(' check back tomorrow to add a new goal!');
			}

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
		data: {ugid: <?php echo $ugid; ?>, uid: <?php echo $uid; ?>, status: 2},
		async: false,
		dataType: "json",
	}).done(function(msg){
		if(msg.success){
			$('#todays-awesome').empty();
			$('#todays-awesome').html('boo. sorry to hear your day was less than awesome.');

		}else{
			$.each(msg.errors, function(type, errors) {
				$("#todays-awesome").html("<p>" + errors + "</p>");
			});
		}
	});
});
</script>
<script src='http://connect.facebook.net/en_US/all.js'></script>
<script> 
FB.init({appId: "294829963933906", status: true, cookie: true});

function postToFeed() {

	// calling the API ...
	var obj = {
		method: 'feed',
		link: 'http://todaywouldbeawesomeif.com',
		name: "Today would be awesome if <?php echo $goaltext; ?>",
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