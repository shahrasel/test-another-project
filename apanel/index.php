<?php
require_once ('../pre_index.php');
require_once (getConfigValue('framework').'common.php');

//echo realpath(dirname(__FILE__));
require_once (getConfigValue('framework').'common.php');
require_once (getConfigValue('framework').'dbhandler.php');
require_once (getConfigValue('framework').'parser.php');
require_once (getConfigValue('framework').'rxmlParser.php');

require_once (getConfigValue('module').'adminpage/adminpage.php');
require_once (getConfigValue('module').'adminnavigation/adminnavigation.php');

//echo getConfigValue('module');

$dbhandler = new Dbhandler ();

setConfigValue ('dbhandler', $dbhandler);
setConfigValue ('user_request_type', 'backend');
//echo getConfigValue (table_prefix);
//$rs = $dbhandler->db->Execute('select * from table1');
//print_r($rs->GetRows());
//print_r ($_GET);
$tmp_config_data = $dbhandler->db->GetAll ("select * from ".getConfigValue('table_prefix')."config");
//echo $dbhandler->db->ErrorMsg(); 
//print_r ($tmp_config_data);
		
$config_data = array();
foreach ($tmp_config_data as $config){
	//$config_data[$config['cnfig_key']] = $config['cnfig_value'];
	setConfigValue ($config['cnfig_key'], $config['cnfig_value']);
}
//print_r ($config_data);

if ( getConfigValue('debug_mode') == 1){
	error_reporting(E_ALL ^ E_NOTICE);	
	//error_reporting(E_ALL);	
	//error_reporting (1);
}
else
	error_reporting(0);
//display_errors1 (0);	
	
//echo 1/0

//setConfigValue ('site_name', $config_data['site_name']);
/*$_SESSION['UserFilesPath'] = $config_data['UserFilesPath'];
setConfigValue ('base_url', $config_data['base_url']);
setConfigValue ('base_admin_url', $config_data['base_admin_url']);
setConfigValue ('media_url', $config_data['media_url']);
setConfigValue ('js', $config_data['js']);
setConfigValue ('css', $config_data['css']);
setConfigValue ('fckeditor', $config_data['fckeditor']);*/
//setConfigValue ('template_suffix', $config_data['template_suffix']);

//setConfigValue ('table_prefix', $config_data['table_prefix']);
//setConfigValue ('page_suffix', $config_data['page_suffix']);
//setConfigValue ('invalid_page', $config_data['invalid_page']);
//setConfigValue ('auth_key', $config_data['auth_key']);
//setConfigValue ('auth_value', $config_data['auth_value']);
///////////

initCssFile(); 
initJsFile();
			
$adminPage = new AdminPage ();
//print_r($adminPage->protocols['adminrouting_instance']);
$navigation = new AdminNavigation();

$template_path = getConfigValue('module').'adminpage/templates/';
$adminPage->protocols['content_instance']->setValue('menu', $navigation->getMenu());

/*
$adminPage->setPageId (1);
$adminPage->initFilter ();
$adminPage->processFilter ();
*/
//echo $_GET['request_url'];

$adminPage->protocols['adminrouting_instance']->parseUrl($_GET['request_url']);
//echo '123';
//print_r ($adminPage->protocols['adminrouting_instance']);
//echo $adminPage->protocols['adminrouting_instance']->performInstance->module_name;

$performInstanceOutPut = '';
if ( is_object ($adminPage->protocols['adminrouting_instance']->performInstance) ){
	//echo $adminPage->protocols['adminrouting_instance']->performInstance->module_name;
	logBackend ($_GET['request_url'], $adminPage->protocols['adminrouting_instance']->performInstance->module_name, $adminPage->protocols['adminrouting_instance']->action);
	
	$tmp_action = $adminPage->protocols['adminrouting_instance']->action;
	setConfigValue ('current_action', $tmp_action);
	$performInstanceOutPut = $adminPage->protocols['adminrouting_instance']->performInstance->$tmp_action();
}	
//performInstanceOutPut
	
//echo getConfigValue ('current_action');
//setConfigValue ('common.php', 'execute_template');
//$dbhandler = new Dbhandler ();
//$adminPage->protocols['content_instance']->setValue('menu', $navigation->getMenu());

$subMenu = $navigation->getSubMenu($adminPage->protocols['adminrouting_instance']->module);
$adminPage->protocols['content_instance']->setValue('submenu', $subMenu);

$adminPage->protocols['content_instance']->setValue('performInstanceOutPut', $performInstanceOutPut);
//$adminPage->protocols['content_instance']->setValue('a', 123);

$is_login = chkLogin ();
if ( empty ($is_login) || strcasecmp($adminPage->protocols['adminrouting_instance']->action,'unauthorize') == 0 ){
	$adminPage->protocols['content_instance']->setTemplate($template_path.'login.php');
}
else{
	$adminPage->protocols['content_instance']->setTemplate($template_path.'common.php');
}
$pageHtml = $adminPage->protocols['content_instance']->contentView();

$adminPage->displayPage($pageHtml);
//print_r ($_SERVER);
//echo basename($_GET['request_url'], '.html');
?>