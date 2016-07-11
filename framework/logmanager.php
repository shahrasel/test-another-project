<?php

function getIpAddress (){
	$ipaddress = $_SERVER['REMOTE_ADDR'];
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != $_SERVER['REMOTE_ADDR']){
			$ipaddress = $_SERVER['REMOTE_ADDR'].' ('.$_SERVER['HTTP_X_FORWARDED_FOR'].')';
	}
	return $ipaddress;
}

function logBackend ($url, $module, $action){
	
	$user = getSessionValue('user_info');
	$user_name = $user['login_name'];
	$user_ip = getIpAddress();
	$execution_time = time();
	$execute_url = $url;
	$execute_module = $module;
	$execute_action = $action;
	
	$log_str = "<log>\n$user_name\n$user_ip\n$execution_time\n$execute_url\n$execute_module\n$execute_action\n</log>\n";
	writeToFile ('backend.txt', $log_str);
}

function logFrontend ($url, $page){
	$user = getSessionValue('user_info');
	$user_name = $user['login_name'];
	$user_ip = getIpAddress();
	$execution_time = time();
	$execute_url = $url;
	$execute_page = $page;
	
	$log_str = "<log>\n$user_name\n$user_ip\n$execution_time\n$execute_url\n$execute_page\n</log>\n";
	writeToFile ('frontend.txt', $log_str);
}


function writeToFile ($filename, $data){
	
	$old_data = file_get_contents (getConfigValue('log').$filename);
	$new_data = $data.$old_data;
	file_put_contents (getConfigValue('log').$filename, $new_data);
		
}

function viwBackendLog(){
}

function viewFrontendLog(){
}

?>