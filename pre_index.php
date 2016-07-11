<?php
session_start();
require_once (getPath('framework').'common.php');
require_once (getPath('framework').'logmanager.php');
$cms_config = array();

function getPath($target_path){
	
	if (strpos($target_path, '/') === FALSE)
	{
		if (function_exists('realpath') AND @realpath(dirname(__FILE__)) !== FALSE)
		{
			$target_path = realpath(dirname(__FILE__)).'/'.$target_path;
		}
	}
	else
	{
		// Swap directory separators to Unix style for consistency
		$target_path = str_replace("\\", "/", $target_path); 
	}
	$target_path .= '/';
	
	return $target_path;
}
/*
function unlockProjectFor ($host, $ip, $from, $to){
	
	$file_name = getConfigValue('module').'ProjectController/'.$host.'.txt';

	if (file_exists ($file_name) && is_writable($file_name)){
		
		$content = ''.	
					'This project developed by annanovas||'.
					'valid from->'.$from.'||'.
					'valid till->'.$to.'||'.
					'host->'.$host.'||'.
					'ip->'.$ip.'||'.
					'this project was secured.';
		
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$key = "cyber project unlock key";
		
		$crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $content, MCRYPT_MODE_ECB, $iv);
		file_put_contents($file_name, $crypttext);
		
		return 'Project unlock till : '.$valid_untill;
	}
	else{
		echo "Invalid Acess.<br/>";
		echo "Check config Folder Inseide Your Project There Should Be a File Name As ".$host.'.txt'.".<br/>";
		echo "That File Shoud Be Writable. <br/t>";
		die();
	}
	
}
*/
/*
function unlockProject ($host, $ip, $from, $to){
	
	$file_name = getConfigValue('config').'key.txt';

	if (file_exists ($file_name) && is_writable($file_name)){
		
		$content = ''.	
					'This project developed by annanovas||'.
					'valid from->'.$from.'||'.
					'valid till->'.$to.'||'.
					'host->'.$host.'||'.
					'ip->'.$ip.'||'.
					'this project was secured.';
		
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$key = "cyber project unlock key";
		
		$crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $content, MCRYPT_MODE_ECB, $iv);
		file_put_contents($file_name, $crypttext);
		
		return 'Project unlock till : '.$valid_untill;
	}
	else{
		echo "Invalid Acess.<br/>";
		echo "Check config Folder Inseide Your Project There Should Be a File Name key.txt.<br/>";
		echo "That File Shoud Be Writable. <br/t>";
		die();
	}
	
}
*/
function lockProject (){
	
	$file_name = getConfigValue('config').'key.txt';
	
	if (file_exists ($file_name) && is_writable($file_name)){
			
		$content = 'this project was terminated for unavoidable circumstances.';
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$key = "annanovas project lock key";
		
		$crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $content, MCRYPT_MODE_ECB, $iv);
		file_put_contents($file_name, $crypttext);
		
		return 'Project Locked';
	}
	else{
		echo '<div style="width:100%;text-align:center;padding-top:50px;"><h2>This site is now unvailable for unavoidable circumstances.</h2></div>';
		die();
	}
	
}

function checkProject(){
	
	$file_name = getConfigValue('config').'key.txt';
	
	/*print_r ($_SERVER);
	$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
	if ($ip_address == NULL)
		$ip_address = $_SERVER[REMOTE_ADDR]; 
	echo $ip_address;*/
		
	if (!file_exists ($file_name) || !is_writable($file_name)){
		echo '<div style="width:100%;text-align:center;padding-top:50px;"><h2>This site is now unvailable for unavoidable circumstances.</h2></div>';
		die();
	}
		
	$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	$key = "annanovas project unlock key";
		
	$content = file_get_contents($file_name);
	$crypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $content, MCRYPT_MODE_ECB, $iv);
	
	$_info = explode ('||', $crypttext);
	//print_r ($_info);
	//exit();
	$from_date = $_info[1];
	$check_from_date = explode ('->', $from_date);
	$from_date = $check_from_date[1];
	
	$untill_date = $_info[2];
	$check_untill_date = explode ('->', $untill_date);
	$untill_date = $check_untill_date[1];
	
	//echo 'here';
	/*********Check Domain**************/
	$domain_name_info = $_info[3];
	$domain_name_info_array = explode ('->', $domain_name_info);
	$domain_name = $domain_name_info_array[1];
	/************************************/
	
	/*********Check Ip**************/
	$ip_address_info = $_info[4];
	$ip_address_info_array = explode ('->', $ip_address_info);
	$ip_address = $ip_address_info_array[1];
	/************************************/
		
	$untill_time = strtotime($untill_date);
	$from_time = strtotime($from_date);
	
	$server_name = $_SERVER['SERVER_NAME'];
	if ( strstr ($domain_name, 'www.') && !strstr ($server_name, 'www.'))
		$server_name = 'www.'.$server_name;
	else if ( !strstr ($domain_name, 'www.') && strstr ($server_name, 'www.'))	
		$domain_name = 'www.'.$domain_name;
	
	//echo $server_name.'--'.$domain_name;	
	if ($domain_name != $server_name || $ip_address != $_SERVER['SERVER_ADDR']){
		echo '<div style="width:100%;text-align:center;padding-top:50px;color:F00;"><h2>This website is inactive for unavoidable circumstances.</h2></div>';
		die();
	
	}
	
	if ( ($from_time > time() && $from_date != -1) || (time() > $untill_time && $untill_date != -1) ){
		//echo 'You project Expired Validation Period. Contact sales@annanovas.com';
		echo '<div style="width:100%;text-align:center;padding-top:50px;"><h2>This site is now unvailable for unavoidable circumstances.</h2></div>';
		die();
	}
	
}

$replace = array('<', '>', '*', '\'', '"');
$site_base = str_replace($replace, '', dirname(env('PHP_SELF')));
setConfigValue ('site_base', $site_base);

$webroot = '';
$adminroot = '';

if ( strstr ($site_base, 'apanel') ){
	$webroot = rtrim ($site_base, 'apanel');
	$adminroot = $site_base.'/';
}
else{
	if ($site_base != '/'){
		$webroot = $site_base.'/';
	 	$adminroot = $site_base.'/apanel/';
	}
	else{
		$webroot = $site_base;
	 	$adminroot = $site_base.'apanel/';
	}
}
//echo $adminroot;

$_SESSION['UserFilesPath'] = $webroot.'userfiles/';
setConfigValue ('base_url', $webroot);
setConfigValue ('base_admin_url', $adminroot);
setConfigValue ('media_url', $webroot.'media/');
setConfigValue ('js', $webroot.'js/');
setConfigValue ('css', $webroot.'css/');
//setConfigValue ('corecss', $webroot.'corecss/');
setConfigValue ('fckeditor', $webroot.'lib/fckeditor/');

setConfigValue ('template_suffix', '.php');

setConfigValue ('ckfiles', getPath('ckfiles'));
setConfigValue ('log', getPath('log'));
setConfigValue ('media', getPath('media'));
setConfigValue ('module', getPath('module'));
setConfigValue ('protocol', getPath('protocol'));
setConfigValue ('framework', getPath('framework'));
setConfigValue ('lib', getPath('lib'));
setConfigValue ('config', getPath('config'));
setConfigValue ('admin', getPath('admin'));
setConfigValue ('cache', getPath('cache'));
setConfigValue ('formvalidator',getPath('formvalidator'));
setConfigValue ('install',getPath('install'));
//unsetSessionValue('captcha_string');
//echo (getPath('install'));
//clearCache();

?>