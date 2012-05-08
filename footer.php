<div id="fb-root"></div>
<script>
window.fbAsyncInit = function() {
	FB.init({
		appId      : '294829963933906', // App ID
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

	<!-- Start of Woopra Code -->
	<script type="text/javascript">
	function woopraReady(tracker) {
		tracker.setDomain('todaywouldbeawesomeif.com');
		tracker.setIdleTimeout(300000);
		tracker.track();
		return false;
	}
	(function() {
		var wsc = document.createElement('script');
		wsc.src = document.location.protocol+'//static.woopra.com/js/woopra.js';
		wsc.type = 'text/javascript';
		wsc.async = true;
		var ssc = document.getElementsByTagName('script')[0];
		ssc.parentNode.insertBefore(wsc, ssc);
		})();
		</script>
		<!-- End of Woopra Code -->
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