<?php

/**
 * MySQL Backup Pro index
 * 
 * @package GONX
 * @author Ben Yacoub Hatem <hatem@php.net>
 * @copyright Copyright (c) 2004
 * @version $Id$ - 08/04/2004 16:20:30 - index.php
 * @access public
 **/

session_start();
@extract($_GET);@extract($_POST);

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

error_reporting(0);
require_once("init.php");


/*
* Locale Setting
*/
$locale = gonxlocale::init();
if (!isset($locale) or $locale=="") {
    $locale = $GonxAdmin["locale"];
}
require_once("locale/".$locale.".php");
/*
* End Locale Setting
*/

/**
* Auth
*/

/*
function authenticate()
{
	//global $GONX;
	$GONX = getConfigValue('GONX');
    header('WWW-Authenticate: Basic realm="'.str_replace("&nbsp;","",$GONX["title"]).'"');
    header('HTTP/1.0 401 Unauthorized');
    echo "<title>".$GONX["title"]."</title>
	<style type=\"text/css\" media=\"screen\">@import \"style.css\";</style>
	<li><h2>".$GONX["title"]." :</h2>".$GONX["autherror"].".\n";
    exit;
}
if ( !isset($_SERVER['PHP_AUTH_USER']) || 
     ($_POST['SeenBefore'] == 1 && $_POST['OldAuth'] == $_SERVER['PHP_AUTH_USER'])) {
    authenticate();
} elseif ($_SERVER['PHP_AUTH_USER']!=$GonxAdmin["login"] or $_SERVER['PHP_AUTH_PW']!=$GonxAdmin["pass"]) {
     authenticate();
}
*/
/**
* end Auth
*/

$menus = array("home"=>$GONX["home"],
				"create"=>$GONX["create"],
				"list"=>$GONX["list"],
				"optimize"=>$GONX["optimize"],
				"monitor"=>$GONX["monitor"]);

//$GLOBALS[menus] = $menus;
//setConfigValue ('menu', $menu);
//print_r (getConfigValue('module') );

$res =  $GONX["header"];
if (!isset($go)) {
    $go = "home";
}

$t = new gonxtabs();
$res .=  "";

//menu will be here
$res .= $t->create($menus,$go,755);

switch($go){
	case "create": 
		$page = "<li><a href=\"?go=generate\" class=tab-s>".$GONX["backupholedb"]." <b>".$GonxAdmin["dbname"]."</b></a></li><br><br>
		<HR align=left width=\"100%\" color=#aaaaaa noShade SIZE=1>
		<li><span class=tab-s>".$GONX["selecttables"]."</span></li>";
		
		
		$b = new backup;
		$b->dbconnect($GonxAdmin["dbhost"],$GonxAdmin["dbuser"],$GonxAdmin["dbpass"],$GonxAdmin["dbname"]);
		$page .= $b->tables_menu();
	break;
	
	case "backuptables":
		$b = new backup;
		$b->dbconnect($GonxAdmin["dbhost"],$GonxAdmin["dbuser"],$GonxAdmin["dbpass"],$GonxAdmin["dbname"]);
		$page = $b->tables_backup($tables,$structonly);
		$page = $page.$b->listbackups();
	break;
	
	case "generate":
		$b = new backup;
		$b->dbconnect($GonxAdmin["dbhost"],$GonxAdmin["dbuser"],$GonxAdmin["dbpass"],$GonxAdmin["dbname"]);
		$page = $b->generate();
		$page = $page.$b->listbackups();
	break;
	
    case "list":
		$b = new backup;
		$page = $b->listbackups();
	break;
	
	case "delete":
		$b = new backup;
		$page = $b->delete($fname);
		$page = $page.$b->listbackups();
	break;
	
	case "import":
		$b = new backup;
		$b->dbconnect($GonxAdmin["dbhost"],$GonxAdmin["dbuser"],$GonxAdmin["dbpass"],$GonxAdmin["dbname"]);
		$page = $b->import($bfile);
		$page = $page. $b->listbackups();
	break;
	
	case "importfromfile":
		$b = new backup;
		$b->dbconnect($GonxAdmin["dbhost"],$GonxAdmin["dbuser"],$GonxAdmin["dbpass"],$GonxAdmin["dbname"]);
		$page = $b->importfromfile();
		$page = $page.$b->listbackups();
	break;
	
	case "optimize":
		$b = new backup;
		$b->dbconnect($GonxAdmin["dbhost"],$GonxAdmin["dbuser"],$GonxAdmin["dbpass"],$GonxAdmin["dbname"]);
		$page = $b->optimize();
	break;
	
	case "config":
		$b = new backup;
		$page = $b->configure();
	break;
	
	case "monitor":
		$b = new backup;
		$b->dbconnect($GonxAdmin["dbhost"],$GonxAdmin["dbuser"],$GonxAdmin["dbpass"],$GonxAdmin["dbname"]);
		$page = $b->monitor();
	break;
	
	case "saveconfig":
		$b = new backup;
		$b->saveconfig();
		$page = $b->configure();
	break;
	
	case "getbackup":
		$b = new backup;
		$b->getbackup($bfile);
	break;
	
	default:
		$page = $GONX['homepage'];
		
		$db = new db;
		$db->dbconnect($GonxAdmin["dbhost"],$GonxAdmin["dbuser"],$GonxAdmin["dbpass"],$GonxAdmin["dbname"]);
		$page .= $db->signature();

$table = "<br/><br/><table width=\"100%\" border=\"1\" style=\"border-collapse: collapse\" bordercolor=#CCCCCC>
	<tr><td align=\"center\"><b>".$GONX["compression"]."</b></td></tr>\n\r";
foreach($GonxAdmin["compression"] as $v){
	$isdef = get_extension_funcs($v);
	if ($isdef) {
	    $table .= "	<tr><td align=\"center\"><font color=green>$v ".$GONX["installed"]."</font></td></tr>\n\r";
	} else {
	    $table .= "	<tr><td align=\"center\"><font color=red>$v ".$GONX["notinstalled"]."</font></td></tr>\n\r";
	}
}
$table .= "</table><br/>\n\r";
		$page .= $table;
	break;
} // switch


$res .= $t->block($page,755 );

echo $res;
?>