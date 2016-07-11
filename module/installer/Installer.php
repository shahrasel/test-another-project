<?php
class installer extends parser
{ 
	
	var $module_name = 'installer';
	var $protocols;
	var $protocolsList = array('content');
	
	function installer(){
		initProtocols (getConfigValue('protocol'), $this->protocolsList);
		$this->protocols = loadProtocols ($this->protocolsList);
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
	
	function manage(){
		
		return $this->install();
	}
			
	function install(){
		//echo 'sdsa';
		$this->initPageValue();
		
		if ($this->data['save'] == 'Save'){
		
			$required_array = array('site_name', 'page_suffix', 'template_suffix', 'auth_key', 
														 'auth_value', 'db_driver', 'db_host', 'db_database', 'db_user', 
														 'db_password', 'save');
			
			$storage_value = array('site_name', 'page_suffix', 'template_suffix', 'auth_key', 
														 'auth_value', 'save');
			$validation_tag = 0;
			foreach ($required_array as $required):
				if ( empty($this->data[$required]) ):
					$validation_tag = 1;
					break;
				endif;
			endforeach;
			
			if ($validation_tag == 0){
				//echo "select * from config where cnfig_key='$key'";
				//print_r ($this->data);
				//echo $this->data['db_driver'];
				require_once (getConfigValue('lib').'adodb/adodb.inc.php');
				$this->db = NewADOConnection($this->data['db_driver']);
				//$this->db->NConnect($db_host, $db_user, $db_password, $db_database);
				if (is_object ($this->db) ) {
				
					$tmp = @$this->db->Connect($this->data['db_host'], $this->data['db_user'], $this->data['db_password'], $this->data['db_database']);
					
					foreach ($this->data as $key=>$value  ){
						if ($key != 'request_url' && $key != 'submit' && in_array ($key, $storage_value) ){
							
							$is_exist = $this->numOfRecord ("select * from config where cnfig_key='$key'");
							if ($is_exist == 1){
								$this->executeQuery ('Update config set cnfig_value='."'$value' where cnfig_key='$key'");
							}
							else{
								$this->executeQuery ("Insert into config (cnfig_key,cnfig_value) values ('$key', '$value')");
							}
							
						}
					}// foreach
					
				}//end if
				else{
					$this->protocols['content_instance']->setValue('_err', "Invalid Information");	
				}
			}
			else{
				$this->protocols['content_instance']->setValue('_err', "Please Fill all The Field");	
			}
			
		}
		$value = $this->parseTemplate($this->module_name, 'install.php', '');
		return $value;
	}
	
	// for frontend	
	function getFilterOutPut ($filter){
		
		
	}
}

?>