<?php
class datamanager extends parser
{ 
	
	var $module_name = 'datamanager';
		
	var $protocols;
	var $protocolsList = array('content');
	
	function datamanager(){
		initProtocols (getConfigValue('protocol'), $this->protocolsList);
		$this->protocols = loadProtocols ($this->protocolsList);
	}
	
	function common (){
		//echo getConfigValue('current_action');
		
		$this->initPageValue();
		$is_auth = chkAuthentication ();
		if ( !$is_auth ){
			 header ("location:".urlForAdmin('user/unauthorize'));
			 exit();
		}
		$this->chkAuthorization ($this->module_name);
		//print_r ($_SERVER);
		//return 'aas';
		$this->initPageValue();
		//print_r ($this->data);
		
		require_once("mysqlbackup/init.php");
		require_once("mysqlbackup/locale/"."en.php");
		
		if ($this->data){
			$tmp = $this->data;
			//print_r ($tmp);
			
			foreach ($tmp as $key=>$val)
				$$key = $val;
		}
			
		$menus = array("home"=>$GONX["home"],
				"create"=>$GONX["create"],
				"list"=>$GONX["list"],
				"optimize"=>$GONX["optimize"],
				"monitor"=>$GONX["monitor"]);
		
		//$GLOBALS[menus] = $menus;
		setConfigValue ('GONX', $GONX);
		setConfigValue ('GonxAdmin', $GonxAdmin);
		//print_r (getConfigValue('menus') );


		$res =  $GONX["header"];
		if (!isset($go)) {
				$go = "home";
		}
		
		$t = new gonxtabs();
		$res .=  "";

		//menu will be here
		//$res .= $t->create($menus,$go,755);

		switch($go){
			case "create": 
				$page = "<li><a href=\"?go=generate\" class=tab-s>".$GONX["backupholedb"]." <b>".$GonxAdmin["dbname"]."</b></a></li><br><br>
				<HR align=left width=\"100%\" color=#aaaaaa noShade SIZE=1>
				<li><span class=tab-s>".$GONX["selecttables"]."</span></li>";
				
				//echo $GonxAdmin["dbuser"];
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
				setConfigValue ('page',$page );
				setConfigValue ('orderby', $orderby );
				
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
	
		$before = '<div class="product">
			<div class="product_1"></div>
			<div class="product_2">Manage DB</div>
			<div class="product_3"></div>
		</div>
		
		<div class="task">
			<div class="task_1">&nbsp;&nbsp;&nbsp;</div>
			<div class="task_2" style=" overflow:auto;">
		';
		
		$after = '</div>
  
  		<div class="bottom">&nbsp;&nbsp;&nbsp;</div>
		</div>';
			
		$res .= $t->block($page,755 );
	
								
		//$value = $this->parseTemplate($this->module_name, 'manage');
		//return $value;
		return ($before.$res.$after);
		
	}
		
	function add(){
		
		/*$this->initPageValue();
		if ($this->data['submit'] == 'submit'){
			
			$this->executeAdd( $this->module_name, array('title','short','description','newsfile') );
			$this->protocols['filemanager_instance']->imageResizeSave ($this->data['newsfile']['tmp_name'], 100, 100, 
																								getConfigValue('media').'News/'.$this->data['newsfile']['name']);
			
			header ("location:".urlForAdmin('news/manage'));
			
		}
		$value = $this->parseTemplate($this->module_name, 'add');
		return $value;*/
	}
	
	function edit(){
		
		/*$this->initPageValue();
		if ($this->data['update'] == 'update'){
									
			$this->data['short']  = str_replace(array("\r\n", "\r", "\n"), "", $this->data['short'] );
			$this->executeEdit( $this->module_name, array('title','short','description', 'newsfile') );
				
				
			header ("location:".urlForAdmin('news/manage'));
		}
		
		$edit_info = $this->executeOne('select * from '.$this->module_name.' where id='.$this->data['id']);
		$this->protocols['content_instance']->setValue('edit_info', $edit_info);
		
		$value = $this->parseTemplate($this->module_name, 'edit');
		return $value;*/
	}
	
	function monitor (){
		$this->data['go'] = 'monitor';
		return $this->common ();
	}
	
	function optimize (){
		$this->data['go'] = 'optimize';
		return $this->common ();
	}
	
	function restore (){
		$this->data['go'] = 'list';
		return $this->common ();
	}
	
	function backup (){
		$this->data['go'] = 'create';
		return $this->common ();
	}
	
	function home (){
		return $this->common ();
	}
	
	function manage (){
		return $this->common ();
	}
	
	// for frontend	
	function getFilterOutPut ($filter){
		
		
	}
}

?>