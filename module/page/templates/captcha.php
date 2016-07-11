<?php
useHelper('captchaHelper.php');

$captchaHelper = new captchaHelper();
//$captchaHelper->captcha->stringGen();

$rand = rand(5, 7);
$captchaHelper->captcha->createCaptcha($rand,'blurWithImage');
//$captchaHelper->captcha->getCaptchaString();

?>