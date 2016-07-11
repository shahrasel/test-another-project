<?php
	
class captchaHelper{	
	
	var $captcha;
	function captchaHelper(){
		//echo '12';
		require_once (getConfigValue('lib').'captcha/class/captcha.class.php');
				
		$this->captcha = new captcha(); 
		//return $captcha;
	}
		
}

?>