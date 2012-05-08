<div id='share-the-awesome' class="clearfix">
	<?php $goalencode = urlencode("Today would be awesome if... $goaltext! What would make your day awesome? http://todaywouldbeawesomeif.com #twbai"); ?>
		<div id='awesome-buttons' class='awesome-share'>
			Share the awesome: <a onclick='postToFeed(); return false;'><img src='/imgs/icon-facebook.png'></a> <a href='https://twitter.com/intent/tweet?button_hashtag=twbai&text=<?php echo $goalencode; ?>' class='class=twitter-share-button' data-related='twbai' data-url='http://todaywouldbeawesomeif.com'><img src='/imgs/icon-twitter.png'></a>
		</div>
	</div>