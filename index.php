<?php
function authenticate()
{
	global $GONX;
    header('WWW-Authenticate: Basic realm="'.str_replace("&nbsp;","","This Site Is Under Maintainence").'"');
    header('HTTP/1.0 401 Unauthorized');
    echo "<title>"."admin authentication"."</title>
	<style type=\"text/css\" media=\"screen\">@import \"style.css\";</style>
	<li><h2>"."user"." :</h2>"."admin".".\n";
	
    exit;
}
/*
if ( !isset($_SERVER['PHP_AUTH_USER']) ) {
    authenticate();
} elseif ($_SERVER['PHP_AUTH_USER']!="admin" or $_SERVER['PHP_AUTH_PW']!="an2628") {
     authenticate();
}
*/

//print_r ($_SERVER);
//exit();

require_once ('pre_index.php');
//echo substr (md5('localhost'), 10);
//print_r ($_GET);
//echo $_GET['request_url'];
//if ($_GET['request_url'] == 'apanel/anlock/'.substr (md5($_SERVER['SERVER_NAME']), 10))
	//lockProject();
	
//unlockProject ('localhost', '::1', '2009-9-01', '2010-9-31');
//checkProject ();

require_once (getConfigValue('framework').'rxmlParser.php');
		
require_once (getConfigValue('framework').'dbhandler.php');
require_once (getConfigValue('framework').'parser.php');
require_once (getConfigValue('framework').'Routing.php');
require_once (getConfigValue('module').'page/page.php');

$dbhandler = new Dbhandler ();
setConfigValue ('dbhandler', $dbhandler);

setConfigValue ('user_request_type', 'frontend');
//$rs = $dbhandler->db->Execute('select * from table1');
//print_r($rs->GetRows());
$tmp_config_data = $dbhandler->db->GetAll ("select * from ".getConfigValue('table_prefix')."config");
//print_r ($tmp_config_data);
		
$config_data = array();
foreach ($tmp_config_data as $config){
	//$config_data[$config['cnfig_key']] = $config['cnfig_value'];
	setConfigValue ($config['cnfig_key'], $config['cnfig_value']);
}
if ( getConfigValue('debug_mode') == 1){
	error_reporting(E_ALL ^ E_NOTICE);	
	
	//error_reporting(E_ALL);	
	//error_reporting (1);
}
else{
	//echo '123';
	error_reporting(0);
}
//print_r ($GLOBALS);
	
//print_r ($config_data);
//$query = "Select * From ".getConfigValue('table_prefix')."Page ";
					
//echo getPath('css');
//$_SESSION['UserFilesPath'] = $config_data['UserFilesPath'];
//setConfigValue ('base_url', $config_data['base_url']);
//setConfigValue ('base_admin_url', $config_data['base_admin_url']);
//setConfigValue ('media_url', $config_data['media_url'] );
//setConfigValue ('js', $config_data['js']);
//setConfigValue ('css', $config_data['css']);
//setConfigValue ('fckeditor', $config_data['fckeditor']);
//setConfigValue ('template_suffix', $config_data['template_suffix']);
/*
setConfigValue ('page_suffix', $config_data['page_suffix']);
setConfigValue ('invalid_page', $config_data['invalid_page']);
setConfigValue ('auth_key', $config_data['auth_key']);
setConfigValue ('auth_value', $config_data['auth_value']);
*/
///////////

$routing = new Routing();
if ( empty($_GET['request_url']) ){
	$routing->parseUrl('index');
        $_GET['request_url'] = 'index';
}
else
	$routing->parseUrl($_GET['request_url']);
//echo $_GET['request_url'];
//exit();
//print_r ($routing);

setConfigValue ('perform_data', $routing->perform_data);
//echo $routing->perform_page;
//exit();
//print_r ($_POST);
//echo $_POST['rr'];
$page = new Page ();
$page->initPageId($routing->perform_page);
//print_r ($page);
//echo $page->isAjax;

if ( $page->isAjax == 1 ){
	//echo $routing->perform_page;
	//echo '123';
	$page->processAjax ($routing->perform_page);
	logFrontend ($_GET['request_url'], $routing->perform_page);
}
else{
	
	initCssFile(); 
	initJsFile();
	
	if (empty ($routing->perform_page) ){
		$page->initPageId( getConfigValue('invalid_page') );
		logFrontend ($_GET['request_url'], 'invalid_page');
	}
	else{
		$page->initPageId($routing->perform_page);
		logFrontend ($_GET['request_url'], $routing->perform_page);
	}
	
	
	//echo $page->getPageCache();
	if ($page->getPageCache() == 1){
		
		
		if (alreadyInCache ($_GET['request_url']) ){
			
			$pageHtml = returnCache($_GET['request_url']);
		}
		else{
						
			$page->processFilter ();
			$page->protocols['content_instance']->setValue('unique_name', getConfigValue('unique_name') );
			$template_path = getConfigValue('module').'page/templates/'.$page->template;
			$page->protocols['content_instance']->setTemplate($template_path);
			$pageHtml = $page->protocols['content_instance']->contentView();
					
			makeCache ($_GET['request_url'], $pageHtml);
		}
	}	
	else{
		//echo '23';
		
		$page->processFilter ();
		
		$page->protocols['content_instance']->setValue('unique_name', getConfigValue('unique_name') );
		$template_path = getConfigValue('module').'page/templates/'.$page->template;
		$page->protocols['content_instance']->setTemplate($template_path);
		$pageHtml = $page->protocols['content_instance']->contentView();
		
		//print_r ($GLOBALS['cms_js']);
		//exit();
	}
	
	//print_r ($GLOBALS['cms_js']);
	$page->displayPage($pageHtml);
	
	//makeCache ($_GET['request_url'], $pageHtml);
	//echo alreadyInCache ($_GET['request_url']);
	//returnCache ($_GET['request_url']);
}
/*
if (extension_loaded('xlst')) {
	echo '--exist--';
}*/


?>