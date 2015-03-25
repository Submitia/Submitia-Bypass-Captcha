<?php
	include ('lib/Submitia.php');
	
	$image = 'http://www.captchacreator.com/files/captchac_code.php';
	
	$key = "[<YOUR API KEY]"; // Get your own key at http://www.submitia.com/de-captcha.php
	$secret = "[<YOUR SECRET KEY]"; // Get your own key at http://www.submitia.com/de-captcha.php
	
	$submitia = new Submitia($key, $secret);
	
	//print $submitia->balance('sptest');
	print $submitia->decode($image);	
?>