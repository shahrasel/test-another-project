<?php
	
class mailmanager{	
	
	var $mailcontroller;
		
	function mailmanager(){
		//echo '12';
		require_once (getConfigValue('lib').'PHPMailer/class.phpmailer.php');
		$this->mailcontroller = new PHPMailer();
	}
	//php_readexcel
	function sendMailViaPhpMailer ($from, $from_name, $to, $subject, $body, $attachment=array() ){
		
		$body             = $body;
		//$body             = eregi_replace("[\]",'',$body);
		
		$this->mailcontroller->From 			= $from;
		$this->mailcontroller->FromName 	= $from_name;
		
		$this->mailcontroller->Subject    = $subject;
		
		$this->mailcontroller->AltBody    = $body; // optional, comment out and test
		
		$this->mailcontroller->MsgHTML($body);
		
		foreach ($to as $key=>$to_email){
			$this->mailcontroller->AddAddress($to_email['mail'], $to_email['name']);
		}
		
		if ($attachment){
			foreach ($attachment as $key=>$file){
				$this->mailcontroller->AddAttachment($file);             // attachment
			}
		}
		
		if(!$this->mailcontroller->Send()) {
			return "Mailer Error: " . $this->mailcontroller->ErrorInfo;
		} else {
			return "Message sent!";
		}
	}
	
	function sendMailViaSendMail ($from, $from_name, $to, $subject, $body, $attachmen=array() ){
		
		$body             = $body;
		$body             = eregi_replace("[\]",'',$body);
		
		$this->mailcontroller->IsSendmail(); // telling the class to use SendMail transport
		
		$this->mailcontroller->From 			= $from;
		$this->mailcontroller->FromName 	= $from_name;
		
		$this->mailcontroller->Subject    = $subject;
		
		$this->mailcontroller->AltBody    = $body; // optional, comment out and test
		
		$this->mailcontroller->MsgHTML($body);
		
		foreach ($to as $key=>$to_email){
			$this->mailcontroller->AddAddress($to_email['mail'], $to_email['name']);
		}
		
		if ($attachment){
			foreach ($attachment as $key=>$file){
				$this->mailcontroller->AddAttachment($file);             // attachment
			}
		}
		
		if(!$this->mailcontroller->Send()) {
			return "Mailer Error: " . $this->mailcontroller->ErrorInfo;
		} else {
			return "Message sent!";
		}
	}
	
	function sendMailViaSmtp ($from, $from_name, $to, $subject, $body, $attachment=array(), $host, $port='', $smtp_secure=false, $smtp_auth=false, $smtp_user='', $smtp_pass=''  ){
		
		$body             = $body;
		$body             = eregi_replace("[\]",'',$body);
		
		$this->mailcontroller->IsSMTP();
		
		if ($smtp_auth){
			$this->mailcontroller->SMTPAuth   = true;                  // enable SMTP authentication
			$this->mailcontroller->Username   =  $smtp_user;  // GMAIL username
			$this->mailcontroller->Password   = $smtp_pass;            // GMAIL password
		}
	
		if ($smtp_secure)
			$this->mailcontroller->SMTPSecure = "ssl";                 // sets the prefix to the servier
		
		$this->mailcontroller->Host       = $host;      // sets GMAIL as the SMTP server
		if ($port)
			$this->mailcontroller->Port       = $port;                   // set the SMTP port for the GMAIL server
				
		$this->mailcontroller->AddReplyTo($from,$from_name);
		
		$this->mailcontroller->From       = $from;
		$this->mailcontroller->FromName   = $from_name;
	
		$this->mailcontroller->Subject    = $subject;
		
		$this->mailcontroller->AltBody    = $body; // optional, comment out and test
		
		$this->mailcontroller->MsgHTML($body);
		
		foreach ($to as $key=>$to_email){
			$this->mailcontroller->AddAddress($to_email['mail'], $to_email['name']);
		}
		
		if ($attachment){
			foreach ($attachment as $key=>$file){
				$this->mailcontroller->AddAttachment($file);             // attachment
			}
		}
		
		if(!$this->mailcontroller->Send()) {
			return "Mailer Error: " . $this->mailcontroller->ErrorInfo;
		} else {
			return "Message sent!";
		}
	}
}

?>