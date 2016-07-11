<?php
	
class mailHelper{	

	function initMAIL(){
		//echo '12';
		require_once (getConfigValue('lib').'PHPMailer/class.phpmailer.php');
		$mailer = new PHPMailer();
		return $mailer; 
	}
		
}

?>