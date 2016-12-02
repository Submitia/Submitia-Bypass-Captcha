# Decaptcha / Bypass Captcha / Solver
A RESTful Captcha Solver or Bypass Captcha Service, check out http://www.submitia.com or http://www.submitia.com/de-captcha.php decoding captchas at $0.0025 per image captcha solved.

----
Usage

```php
<?php
	include ('lib/Submitia.php');
	
	$image = 'http://www.captchacreator.com/files/captchac_code.php';
	
	$key = "[<YOUR API KEY]"; // Get your own key at http://www.solvecaptchas.com/
	$secret = "[<YOUR SECRET KEY]"; // Get your own key at http://www.solvecaptchas.com/
	
	$submitia = new Submitia($key, $secret);
	
	//print $submitia->balance('sptest');
	print $submitia->decode($image);	
```

----
Sponsors:

- https://www.captchasolutions.com/
- https://www.isnare.com/
- https://articlefr.cf/
- http://www.solvecaptchas.com/
