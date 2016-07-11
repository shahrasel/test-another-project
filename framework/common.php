<?php
if ( !session_id() )
	session_start();

function convertDBFormatToCustom ($date, $seperator='-'){
		
	$date_array = explode ($seperator, $date);
	$date_format = "yy-mm-dd";//getConfigValue ('date_format');
	$format_array = explode ($seperator, $date_format);
	
	$final_array[$format_array[0]] = $date_array[0];
	$final_array[$format_array[1]] = $date_array[1];
	$final_array[$format_array[2]] = $date_array[2];
	
	//print_r ();
	
	return ($final_array['mm'].'-'.$final_array['dd'].'-'.$final_array['yy']);
				
}

function convertCustomToDBFormat ($date, $seperator='-'){
		
	$date_array = explode ($seperator, $date);
	$date_format = "mm-dd-yy";//getConfigValue ('date_format');
	$format_array = explode ($seperator, $date_format);
	
	$final_array[$format_array[0]] = $date_array[0];
	$final_array[$format_array[1]] = $date_array[1];
	$final_array[$format_array[2]] = $date_array[2];
	
	//print_r ();
	
	return ($final_array['yy'].'-'.$final_array['mm'].'-'.$final_array['dd']);
				
}

function escape_string_alt ($val){
	$tmp_val;
	
	$gpc = ini_get("magic_quotes_gpc");
	
	if ($gpc == 1){
		$tmp_val = stripslashes($val);
	}
	else{
		$tmp_val = $val;
	}
	
	return $tmp_val;
}
	
function clearCache (){
		 
	 $handle = opendir(getConfigValue('cache'));	
	 while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && $file != '.DS_Store') 
				unlink (getConfigValue('cache').$file);
				//echo "$file\n";
	}
	$query = ' TRUNCATE TABLE '.getConfigValue('table_prefix').'cacheinfo';
	getConfigValue('dbhandler')->db->Execute($query);

}

function makeCache ($url, $output){
	
	//$db = getConfigValue ('dbhandler');
	$record = array();
	$record['request_url'] = escape_string_alt ($url);
	getConfigValue('dbhandler')->db->AutoExecute(getConfigValue('table_prefix').'cacheinfo', $record, 'INSERT');
	
	$cache_file = getConfigValue('cache').getConfigValue('dbhandler')->db->Insert_ID();
	file_put_contents ($cache_file, $output);
	
}

function alreadyInCache ($url){
	
	$found = 0;
	$query = "Select * From ".getConfigValue('table_prefix')."cacheinfo where request_url='$url'";
	$rs =  getConfigValue('dbhandler')->db->Execute($query);
	
	$found = $rs->RecordCount();
	return $found;
}

function returnCache ($url){
	$result = array();
	$query = "Select * From ".getConfigvalue('table_prefix')."cacheinfo where request_url='$url'";
	$result = getConfigValue('dbhandler')->db->GetRow ($query);
	$output = file_get_contents (getConfigValue('cache').$result['id']);
	return $output;
	//print_r ($result);
}

function chkFAuthentication ( $from_url='' ){
	
	//echo $from_url;
	$user_role = getSessionValue('fuser_role');
	//echo getConfigValue('fauth_value') ;
	
	if ( getSessionValue(getConfigValue('fauth_key')) == getConfigValue('fauth_value') 
			&& !empty($user_role) )
		return true;
	else{
		//echo '|-|'.$from_url;	
		if ( !empty ($from_url) )
			setSessionValue ('from_url', $from_url);
		return false;
	}
	
}
		
function chkAuthentication (){

	$user_role = getSessionValue('user_role');
	if ( getSessionValue(getConfigValue('auth_key')) == getConfigValue('auth_value') 
			&& !empty($user_role) )
		return true;
	else
		return false;
}

function chkLogin (){
	$role_info = getSessionValue('user_role');
		//print_r ($security_role_array);
	if ( !empty ($role_info['title']) ){
		return $role_info['title'];
	}
	//setConfigValue ('login.php', 'execute_template');
	return '';
}

function unsetSessionValue ($key){
	$_SESSION['cms'][$key] = '';
	unset ($_SESSION['cms'][$key]);
}
	
function setSessionValue ($key, $value){
	$_SESSION['cms'][$key] = $value;
}

function getSessionValue ($key){
	return $_SESSION['cms'][$key];
}

function setConfigValue ($index, $val){
	$GLOBALS['cms_config'][$index] = $val;
	//echo $GLOBALS['cms_config'][$index];
}

function getConfigValue ($index){
	//print_r($GLOBALS['cms_config']);
	return $GLOBALS['cms_config'][$index];
}

function setGlobalValue ($index, $val){
	$GLOBALS['cms_global'][$index] = $val;
	//echo $GLOBALS['cms_config'][$index];
}

function getGlobalValue ($index){
	//print_r($GLOBALS['cms_config']);
	return $GLOBALS['cms_global'][$index];
}


function getJs(){
	//print_r($GLOBALS['cms_js']);
	//return $GLOBALS['cms_config'][$index];
	$js_html = '';
	if ($GLOBALS['cms_js']){
		foreach ($GLOBALS['cms_js'] as $js){
			 //echo $css;
			 //<link type="text/css" rel="stylesheet" href="css.css"/>
			 $js_html .= '<script src="'.getConfigValue('base_url').$js.'" type="text/javascript" language="javascript"></script>';
		}
	}
	
	return $js_html;
}

function getCss(){
	//print_r ($GLOBALS['cms_css']);
		
	$css_html = '';
	if ($GLOBALS['cms_css']){
		
		foreach ($GLOBALS['cms_css'] as $css){
			 //echo $css;
			 //<link type="text/css" rel="stylesheet" href="css.css"/>
			 $css_html .= '<link type="text/css" rel="stylesheet" href="'. getConfigValue('base_url').$css.'"/>';
		}
	}
	
	//echo $css_html;
	return $css_html;
}

function initJsFile(){
	
	$GLOBALS['cms_js'] = array();
}

function setJsFile($val){
	if ( !in_array($val, (array)$GLOBALS['cms_js']) )
		$GLOBALS['cms_js'][] = $val;	
}

function initCssFile(){
	$GLOBALS['cms_css'] = array();
}

function setCssFile($val){
	if ( !in_array($val, $GLOBALS['cms_css']) )
		$GLOBALS['cms_css'][] = $val;	
}

function urlForAdmin($val){
	
	$tmp = getConfigValue('base_admin_url').$val.getConfigValue('page_suffix');
	return $tmp;
}

function urlFor($page, $values){
	
	if (is_array($values) )
		$data = implode('/', $values);
	else
		$data .= '?'.$values;
		
	if ($data)
		$tmp = getConfigValue('base_url').$page.'/'.$data.getConfigValue('page_suffix');
	else
		$tmp = getConfigValue('base_url').$page.getConfigValue('page_suffix');
		
	return $tmp;
}

function initProtocols ($extension_path, $protocols){
	//print_r ($protocols);
	foreach($protocols as $protocol){
		
		if ( !class_exists($protocol.'_instance') )
			require_once ($extension_path.$protocol.'/'.$protocol.'_instance.php');
			
	}

}

function loadProtocols ($protocols){
	
	$tmp_protocols = array();	
	//print_r ($protocols);
	foreach( $protocols as $protocol){
		$tmp_instance_name = $protocol.'_instance';
		$tmp_protocols[$protocol.'_instance'] = new $tmp_instance_name();	
	}
	return $tmp_protocols;
}

function initModule ($module_path, $module){
	
	if ( !class_exists($module.'_instance') )
			require_once ($module_path.$module.'/'.$module.'_instance.php');
}

function loadModule ($module){
	
	$tmp_instance_name = $module.'_instance';
	$tmp_module = new $tmp_instance_name();	
	return $tmp_module;
	
}

function useHelper ($helper){
	require_once (getConfigValue('framework').$helper);
}

function getReservedModuleList (){
	$reserved_module = array ('adminnavigation', 'adminpage', 'ajax', 'modulemanager', 'page', 'user','userrole', 'datamanager');	
	return $reserved_module;
}

function getInstallableModuleList (){
	$dir = getConfigValue('module');
	$dh  = opendir($dir);
	$reserved_module = array ('adminnavigation', 'adminpage', 'ajax', 'modulemanager', 'page', 'user','userrole', 'datamanager');	
	
	$modules = array();
	while (false !== ($modulename = readdir($dh))) {
		if ($modulename != "." && $modulename != ".." && $modulename != '.DS_Store' && 
				$modulename != 'common' && !in_array($modulename, $reserved_module) ) 
			$modules[] = $modulename;
	}
	return $modules;
} 

function getCommonUserModuleList (){
	
	$dir = getConfigValue('module');
	$dh  = opendir($dir);
	$reserved_module = array ('adminnavigation', 'adminpage', 'ajax','installer' );	
	
	$modules = array();
	while (false !== ($modulename = readdir($dh))) {
		if ($modulename != "." && $modulename != ".." && $modulename != '.DS_Store' && 
				$modulename != 'common' && !in_array($modulename, $reserved_module) ) 
			$modules[] = $modulename;
	}
	asort($modules);
	return $modules;
}

function getAllModuleList (){
	$dir = getConfigValue('module');
	$dh  = opendir($dir);
	
	$modules = array();	
	while (false !== ($modulename = readdir($dh))) {
		if ($modulename != "." && $modulename != ".." && $modulename != '.DS_Store' && $modulename != 'common' && $modulename != 'ajax' && $modulename != 'installer') 
			$modules[] = $modulename;
	}
	asort($modules);
	return $modules;
}

function env($key) {
	if ($key == 'HTTPS') {
		if (isset($_SERVER['HTTPS'])) {
			return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
		}
		return (strpos(env('SCRIPT_URI'), 'https://') === 0);
	}

	if ($key == 'SCRIPT_NAME') {
		if (env('CGI_MODE') && isset($_ENV['SCRIPT_URL'])) {
			$key = 'SCRIPT_URL';
		}
	}

	$val = null;
	if (isset($_SERVER[$key])) {
		$val = $_SERVER[$key];
	} elseif (isset($_ENV[$key])) {
		$val = $_ENV[$key];
	} elseif (getenv($key) !== false) {
		$val = getenv($key);
	}

	if ($key === 'REMOTE_ADDR' && $val === env('SERVER_ADDR')) {
		$addr = env('HTTP_PC_REMOTE_ADDR');
		if ($addr !== null) {
			$val = $addr;
		}
	}

	if ($val !== null) {
		return $val;
	}

	switch ($key) {
		case 'SCRIPT_FILENAME':
			if (defined('SERVER_IIS') && SERVER_IIS === true) {
				return str_replace('\\\\', '\\', env('PATH_TRANSLATED'));
			}
		break;
		case 'DOCUMENT_ROOT':
			$name = env('SCRIPT_NAME');
			$filename = env('SCRIPT_FILENAME');
			$offset = 0;
			if (!strpos($name, '.php')) {
				$offset = 4;
			}
			return substr($filename, 0, strlen($filename) - (strlen($name) + $offset));
		break;
		case 'PHP_SELF':
			return str_replace(env('DOCUMENT_ROOT'), '', env('SCRIPT_FILENAME'));
		break;
		case 'CGI_MODE':
			return (PHP_SAPI === 'cgi');
		break;
		case 'HTTP_BASE':
			$host = env('HTTP_HOST');
			if (substr_count($host, '.') !== 1) {
				return preg_replace('/^([^.])*/i', null, env('HTTP_HOST'));
			}
		return '.' . $host;
		break;
	}
	return null;
}

function get_n_words($longtext,$n) {
    $first_n = preg_split('/[\s,]+/',$longtext);
    
		$first_n_count = count($first_n);
    $returned = '';
    for($i=0; $i<$n; $i++) {
        if($i == $n - 1) {
            $returned .= $first_n[$i];//preg_replace('/([^\s]+).*$/',"\\1",$first_n[$i]);
        }
        else {
            $returned .= $first_n[$i].' ';
        }
        if($i>=$first_n_count) break;
    }
		//echo $first_n_count.'--'.$i;
		//print_r ($first_n);
		//exit();
		
    return $returned;
}  
//retrieving the vars
//Processing and importing of the SQL statements



	 
?>