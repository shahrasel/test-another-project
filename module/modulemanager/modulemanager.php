<?php

class modulemanager extends parser
{ 
	
	var $module_name = 'modulemanager';
	var $table_name = 'modulemanager';
	
	var $protocols;
	var $protocolsList = array('content', 'formvalidation');
	
	function modulemanager(){
		initProtocols (getConfigValue('protocol'), $this->protocolsList);
		$this->protocols = loadProtocols ($this->protocolsList);
		$this->table_name = getConfigValue('table_prefix').$this->table_name;
	}
	
	function optionvalue_default_user_role (){
		
		$userrole_info = $this->executeAll ("Select * FROM ".getConfigValue('table_prefix')."userrole where active='Active'" );	
		//print_r ($userrole_info);
		$role_array = array();
		$role_array[] = '';
		
		if ($userrole_info){
			foreach ($userrole_info as $user){
					$role_array[$user['id']] = $user['title'];
			}
		}
		return $role_array;
	}
	
	function restore(){
		
		$this->initPageValue();
		$is_auth = chkAuthentication ();
		if ( !$is_auth ){
			 header ("location:".urlForAdmin('user/unauthorize'));
			 exit();
		}
		$this->chkAuthorization ($this->module_name);
				
		//print_r ($this->data);exit();
		$dir = getConfigValue('module').$this->data['perform_module'].'/backup/';
		
		if ( !empty($this->data[remove_file]) ){
			unlink($dir.$this->data[remove_file]);
		}
		
		if ( !empty($this->data[restore_file]) ){
	
			$this->import ($dir.$this->data[restore_file]);
			$result = $this->ShowErr();
			if (!$result[0]){
				//echo 'Can\'t install module';
				//print_r ($result);
				$_err = implode("<br/>", $result[2]);
				//exit();
			}	
				
		}
		
		$dh  = opendir($dir);
		$files = array();	
		while (false !== ($filename = readdir($dh))) {
			if ($filename != "." && $filename != ".." && $filename != '.DS_Store' && $filename != 'common') {
				//echo filemtime($dir.$filename);
				$files[filemtime($dir.$filename)] = $filename;
			}
		}
		krsort ($files);
		//print_r ($files);
		$this->protocols['content_instance']->setValue('perform_module', $this->data['perform_module']);
		$this->protocols['content_instance']->setValue('files', $files);
		
		$this->protocols['content_instance']->setValue('_err', $_err);
		$value = $this->parseTemplate($this->module_name, 'restore', '');
		return $value;
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
	
	/*function parse_mysql_dump($url){
		
		 $file_content = file_get_contents($url);
		 echo $db_driver;
		 //echo di($file_content);
		 exit();
		 //print_r ( $file_content);exit();
		 
		 foreach($file_content as $sql_line){
			 if(trim($sql_line) != "" && strpos($sql_line, "--") === false){
					$this->executeQuery($sql_line);
			 }
		 }
  }*/
	
	function config(){

		$this->initPageValue();
		$is_auth = chkAuthentication ();
		if ( !$is_auth ){
			 header ("location:".urlForAdmin('user/unauthorize'));
			 exit();
		}
		$this->chkAuthorization ($this->module_name);
		
		$validation_rules = $this->getFromInfo ($this->module_name, 'Config.xml');
		
	
		if ($this->data['save'] == 'Save'){
			$valid = $this->protocols['formvalidation_instance']->isvalid($this->data, $validation_rules);
		
			if ($valid){
			
				if ( empty ($this->data['debug_mode'])  )	
					$this->data['debug_mode'] = 0;
					
				foreach ($this->data as $key=>$value){
					if ($key != 'request_url' && $key != 'submit'){
						
						$is_exist = $this->numOfRecord ("select * from ".getConfigValue('table_prefix')."config where cnfig_key='$key'");
						if ($is_exist == 1){
							$this->executeQuery ('Update '.getConfigValue('table_prefix').'config set cnfig_value='."'$value' where cnfig_key='$key'");
						}
						else{
							$this->executeQuery ("Insert into ".getConfigValue('table_prefix')."config (cnfig_key,cnfig_value) values ('$key', '$value')");
						}
						
					}
				}
			}
		}
		
		$tmp_config_data = $this->executeAll ("select * from ".getConfigValue('table_prefix')."config");
		
		$config_data = array();
		foreach ($tmp_config_data as $config){
			//print_r ($config);
			$config_data[$config['cnfig_key']] = $config['cnfig_value'];
		}
		$this->protocols['content_instance']->setValue('config_data', $config_data);
		//print_r ($config_data);
		$this->protocols['content_instance']->setValue('validation_rules', $validation_rules);
		$value = $this->parseTemplate($this->module_name, 'config', '');
		return $value;
	}
	
	function paypalconfig(){

		$this->initPageValue();
		$is_auth = chkAuthentication ();
		if ( !$is_auth ){
			 header ("location:".urlForAdmin('user/unauthorize'));
			 exit();
		}
		$this->chkAuthorization ($this->module_name);
		
		$validation_rules = $this->getFromInfo ($this->module_name, 'Paypalconfig.xml');

		
		if ($this->data['save'] == 'Save'){
			//echo "select * from config where cnfig_key='$key'";
			//print_r ($this->data);
			$valid = $this->protocols['formvalidation_instance']->isvalid($this->data, $validation_rules);
			if ($valid){
			
				if ( empty ($this->data['paypal_api_useproxy'])  )	
					$this->data['paypal_api_useproxy'] = 0;
				if ( empty ($this->data['paypal_api_usesandbox'])  )	
					$this->data['paypal_api_usesandbox'] = 0;
					
				if ( empty ($this->data['debug_mode'])  )	
					$this->data['debug_mode'] = 0;
										
				foreach ($this->data as $key=>$value){
					if ($key != 'request_url' && $key != 'submit'){
						
						$is_exist = $this->numOfRecord ("select * from ".getConfigValue('table_prefix')."config where cnfig_key='$key'");
						if ($is_exist == 1){
							$this->executeQuery ('Update '.getConfigValue('table_prefix').'config set cnfig_value='."'$value' where cnfig_key='$key'");
						}
						else{
							$this->executeQuery ("Insert into ".getConfigValue('table_prefix')."config (cnfig_key,cnfig_value) values ('$key', '$value')");
						}
						
					}
				}
			}
			
		}
		
		$tmp_config_data = $this->executeAll ("select * from ".getConfigValue('table_prefix')."config");
		
		$config_data = array();
		foreach ($tmp_config_data as $config){
			//print_r ($config);
			$config_data[$config['cnfig_key']] = $config['cnfig_value'];
		}
		$this->protocols['content_instance']->setValue('config_data', $config_data);
		//print_r ($config_data);
		$this->protocols['content_instance']->setValue('validation_rules', $validation_rules);
		$value = $this->parseTemplate($this->module_name, 'paypalconfig', '');
		return $value;
	}
	
	function managemodule(){
		
		$this->initPageValue();
		$is_auth = chkAuthentication ();
		if ( !$is_auth ){
			 header ("location:".urlForAdmin('user/unauthorize'));
			 exit();
		}
		$this->chkAuthorization ($this->module_name);
		//print_r ($this->data);
		
		if ($this->data['peform_action'] == 'createbackup'){
			$this->export($this->data['perform_module']);
		}
		
		if ($this->data['peform_action'] == 'moduleinstaller'){
			
			if ($this->data['install'] == 0){
				//DROP TABLE `news
				
				$this->numOfRecord ('Delete From '.$this->table_name.' Where name=\''.$this->data['name'].'\'');
				$msg = 'Module Remove Successfully';
			}
			else if ($this->data['install'] == 1){
				$url = getConfigValue('module').$this->data['name'].'/'.$this->data['name'].'.sql';
				//$this->parse_mysql_dump ($url);
				$this->import ($url);
				
				if ( !is_dir (getConfigValue('media').$this->data['name']) )
					mkdir (getConfigValue('media').$this->data['name']);
				
				if ( !is_dir (getConfigValue('media').$this->data['name'].'/thumb') )
					mkdir (getConfigValue('media').$this->data['name'].'/thumb');
				if ( !is_dir (getConfigValue('media').$this->data['name'].'/enlarge') )
					mkdir (getConfigValue('media').$this->data['name'].'/enlarge');	
				
				$this->executeAdd	($this->table_name, array('name'));
				$err .= $this->getError();
				$msg = 'Module Install Successfully';
			}
			
		}
				
		
		$modules = getAllModuleList();
		sort($modules);
				
		if (empty ($this->data['perpage']) )
			$this->data['perpage'] = 200;
		
		$where_sql = '';
		//if ( !empty($this->data[search_value]) )
			 //$where_sql = " title like '%{$this->data[search_value]}%' and description like '%{$this->data[search_value1]}%' ";
				
		$this->paging_controlling_field = array('perpage','order_by','order_type', 'current_page');
		
		$table_data =  $this->executeManage ($this->table_name, $this->data['perpage'], $where_sql );
		$installed_module = array();
		if ($table_data)
			foreach ($table_data as $row)
				$installed_module[] = $row['name'];
		
		$reserved_module = getReservedModuleList();		
		//print_r ($installed_module);
		//exit();
		 
		$this->protocols['content_instance']->setValue('err', $err);
		$this->protocols['content_instance']->setValue('msg', $msg);
		
		$this->protocols['content_instance']->setValue('modules', $modules);
		$this->protocols['content_instance']->setValue('installed_module', $installed_module);
		$this->protocols['content_instance']->setValue('reserved_module', $reserved_module);
		
		$this->protocols['content_instance']->setValue('table_data', $table_data);
		
		//$this->protocols['content_instance']->setValue('page_controlling_value',  $this->renderPageControllingLink());	
		//$this->protocols['content_instance']->setValue('perpage', $this->data['perpage']);
		//$this->protocols['content_instance']->setValue('name_header', $this->renderingOrder ($this->module_name,'Title', 'title'));
						
		$value = $this->parseTemplate($this->module_name, 'managemodule', '');
		return $value;
		
	}
	function manage(){
		//print_r ($_SERVER);
		//return 'aas';
		header ("location:".urlForAdmin($this->module_name.'/managemodule') );
		exit();
		
	}
	
	// for frontend	

}

?>