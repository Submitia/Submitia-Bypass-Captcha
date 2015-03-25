# Submitia-Bypass-Captcha
A RESTful Bypass Captcha or Decaptcha Web Service, check out http://www.submitia.com or http://www.submitia.com/de-captcha.php decoding captchas at $0.0025 per image captcha solved.

----
Usage

```php
<?php
	include ('lib/Submitia.php');
	
	$image = 'http://www.captchacreator.com/files/captchac_code.php';
	
	$key = "[<YOUR API KEY]"; // Get your own key at http://www.submitia.com/de-captcha.php
	$secret = "[<YOUR SECRET KEY]"; // Get your own key at http://www.submitia.com/de-captcha.php
	
	$submitia = new Submitia($key, $secret);
	
	//print $submitia->balance('sptest');
	print $submitia->decode($image);	
```
----

----
Sponsors:

http://www.isnare.com
http://freereprintables.com
http://embedarticles.com
http://contenthugs.com
----
