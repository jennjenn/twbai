<?php
session_start();
require_once('connect.php');
require_once('facebook-php-sdk/src/facebook.php');

$facebook = new Facebook(array(
	//DEV
// 'appId'  => '346619492058435',
// 'secret' => 'e60ab60e2421aaf31b85b3aa766a6101',

//PROD
'appId'  => '294829963933906',
	'secret' => '7337ff3d6e2b1c9999e638773fa51880',
	));

$fbid = $facebook->getUser();

//See if this user is logged in
if($fbid){
	try{
		// Proceed knowing you have a logged in user who's authenticated.
		$fbinfo = $facebook->api('/me');
		$token = $facebook->getAccessToken();
	}catch (FacebookApiException $e){
		error_log($e);
		$fbid = null;
	}
}

// Login or logout url will be needed depending on current user state.
if($fbid){
	$loginstate = true;
	$fburl = $facebook->getLogoutUrl();
}else{
	$loginstate = false;
	$fburl = $facebook->getLoginUrl(
		array('scope'	=>	'email,publish_stream,user_birthday,user_location')
		);
}

require_once('functions.php');
?>