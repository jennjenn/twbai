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
		?>
		<div class="result"></div>
		<?php
		require_once('modules/wish-input.php');
		?>
		<div id='no-active-goal'>
			<div id='todays-goal'></div>
			<?php
		require('modules/awesome-update-prompt.php');
		$ugid = getActiveGoal($uid);
		$goalencode = urlencode("Today would be awesome if... $goaltext! What would make your day awesome? http://twbai.me/if/$ugid #twbai");
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
	<div class="result"></div>
	<div id='todays-goal' ugid="<?php echo $ugid; ?>"><a href="/if/<?php echo $ugid; ?>"><?php echo $goaltext; ?></a></div>
	<?php
		// this goal hasn't been updated today
		require_once('modules/awesome-update-prompt.php');
		$goalencode = urlencode("Today would be awesome if... $goaltext! What would make your day awesome? http://twbai.me/if/$ugid #twbai");
		require_once('modules/share-the-awesome.php');
		?>
		<div id="no-active-goal">
			<?php require_once('modules/wish-input.php'); ?>
		</div>
		<?php
if(!doneToday($uid)){
	$goalencode = urlencode("Today would be awesome if... $goaltext! What would make your day awesome? http://twbai.me/if/$ugid #twbai"); ?>

	<div id='share-the-awesome' style="display:none">
		<?php $goalencode = urlencode("I said today would be awesome if... $goaltext! What would make your day awesome? http://twbai.me/if/$ugid #twbai"); ?>
			<div id='awesome-buttons' class='awesome-share'>Share the awesome: <a onclick='postToFeed(); return false;'><img src='/imgs/icon-facebook.png'></a> <a href='https://twitter.com/intent/tweet?button_hashtag=twbai&text=<?php echo $goalencode;?>' class='class=twitter-share-button' data-related='twbai' data-url='http://todaywouldbeawesomeif.com'><img src='/imgs/icon-twitter.png'></a></div>
		</div>
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
			console.log(msg);
			var ugid = msg.ugid;
			// $('#goal-create').hide();
			// $('.result').hide();
			// $('#no-active-goal').fadeIn();
			// $('#todays-goal').html(msg.goal);
			// $('#todays-goal').attr('ugid', ugid);			
			// $('#share-the-awesome').fadeIn();
			// $('#todays-awesome').show();
			window.location.href = "/if/" + ugid;
		}else{
			$.each(msg.errors, function(type, errors) {
				$(".result").html("<p>" + errors + "</p>");
			});
		}
	});
	return false;
});

$('#awesome-yes').click(function(){
	var ugid = $('#todays-goal').attr('ugid');
	console.log(ugid);
	$.ajax({
		type: "POST",
		url: "api/updategoal.php",
		data: {ugid: ugid, uid: <?php echo $uid; ?>, status: 1},
		async: false,
		dataType: "json",
	}).done(function(msg){
		if(msg.success){
			$('#todays-awesome').hide();
			$('#todays-goal').empty();
			$('#share-the-awesome').hide();
			$('#goal-create').show();			
			$('.result').html('nicely done!');
			if(msg.newgoal){
				$('.result').append(" what's next?");
				$('#no-active-goal').fadeIn();
			}
			$('.result').fadeIn();
		}else{
			$.each(msg.errors, function(type, errors) {
				$("#todays-awesome").html("<p>" + errors + "</p>");
			});
		}
	});
});

$('#awesome-no').click(function(){
	var ugid = $('#todays-goal').attr('ugid');
	$.ajax({
		type: "POST",
		url: "api/updategoal.php",
		data: {ugid: ugid, uid: <?php echo $uid; ?>, status: 2},
		async: false,
		dataType: "json",
	}).done(function(msg){
		if(msg.success){
			$('#todays-awesome').hide();
			$('#todays-goal').empty();
			$('#share-the-awesome').hide();				
			$('#goal-create').show();		
			$('.result').html("sorry to hear your day wasn't awesome. Try again?").fadeIn();
			if(msg.newgoal){
				$('#no-active-goal').fadeIn();
			}
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