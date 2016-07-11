<?php
class userrole extends parser
{	
	
	var $module_name = 'userrole';
	var $table_name = 'userrole';
	
	var $protocols;
	//protected $pageId;
	protected $filters;
	var $template;
	
	var $protocolsList = array('content', 'filter', 'formvalidation', 'xlsmanager');
	
	
	function optionvalue_active (){
		$optionvalue_array = array();
		$optionvalue_array['Inactive'] = 'Inactive';
		$optionvalue_array['Active'] = 'Active';
		return $optionvalue_array;			
	}
	
	function processFilter (){
		
		if($this->filters){
			foreach ($this->filters as $filter){
				$filter_criteria = explode ('_', $filter);
				$tmp_value = $this->protocols['filter_instance']->getOutPut($filter_criteria);
				$this->protocols['content_instance']->setValue($filter, $tmp_value);
			}
		}
		
	}
	
	function processAjax (){
		if ( !class_exists('Ajax_instance') ){
			require_once (getConfigValue('module').'Ajax/'.'Ajax_instance.php');
		}
		Ajax_instance::executeFile ();
	}
		
	function userrole (){
		initProtocols (getConfigValue('protocol'), $this->protocolsList);	
		$this->protocols = loadProtocols ($this->protocolsList);
		$this->table_name = getConfigValue('table_prefix').$this->table_name;
	}
	
	function add(){
		unsetSessionValue('_formValidation');
		
		$this->initPageValue();
		$is_auth = chkAuthentication ();
		if ( !$is_auth ){
			 header ("location:".urlForAdmin('user/unauthorize'));
			 exit();
		}
		$this->chkAuthorization ($this->module_name);
		
		$validation_rules = $this->getFromInfo ($this->module_name, 'Form.xml');
		
		if ($this->data['submit'] == 'Submit'){
			
			$valid = $this->protocols['formvalidation_instance']->isvalid($this->data, $validation_rules);
												
			if($valid){
			
				$this->executeAdd( $this->table_name, array('title','active') );
				header ("location:".urlForAdmin($this->module_name.'/manage'));
			}
			
		}
		
		$this->protocols['content_instance']->setValue('validation_rules', $validation_rules);
		$value = $this->parseTemplate($this->module_name, 'add');
		unsetSessionValue('_formValidation');
		return $value;
			
	}
	
	function edit(){
		unsetSessionValue('_formValidation');
		
		$this->initPageValue();
		$is_auth = chkAuthentication ();
		if ( !$is_auth ){
			 header ("location:".urlForAdmin('user/unauthorize'));
			 exit();
		}
		$this->chkAuthorization ($this->module_name);
		
		$validation_rules = $this->getFromInfo ($this->module_name, 'Form.xml');
		
		if (empty ($this->data['perpage']) )
			$this->data['perpage'] = getConfigValue('per_page');
		$this->paging_controlling_field = array('perpage','order_by','order_type','current_page');
		$page_controlling_value =  $this->renderPageControllingLink();
		$this->protocols['content_instance']->setValue('page_controlling_value', $page_controlling_value);	
			
		if ($this->data['update'] == 'Update'){
			
			$valid = $this->protocols['formvalidation_instance']->isvalid($this->data, $validation_rules);
												
			if($valid){
				$this->executeEdit( $this->table_name, array('title','active') );
				header ("location:".urlForAdmin($this->module_name.'/manage').'?'.html_entity_decode($page_controlling_value));
			}
		}
		
		$edit_info = $this->executeOne('select * from '.$this->table_name.' where id='.$this->data['id']);
		$this->protocols['content_instance']->setValue('edit_info', $edit_info);
		
		$this->protocols['content_instance']->setValue('validation_rules', $validation_rules);
		$value = $this->parseTemplate($this->module_name, 'edit');
		unsetSessionValue('_formValidation');
		return $value;
		
	}
	
	function manage(){
				
		$this->initPageValue();
		$is_auth = chkAuthentication ();
		if ( !$is_auth ){
			 header ("location:".urlForAdmin('user/unauthorize'));
			 exit();
		}
		$this->chkAuthorization ($this->module_name);
		
		$validation_rules = $this->getFromInfo ($this->module_name, 'Filter.xml');
		
		if (empty ($this->data['perpage']) )
			$this->data['perpage'] = getConfigValue('per_page');
		
		$where_sql = '';
		//if ( !empty($this->data[search_value]) )
			 //$where_sql = " title like '%{$this->data[search_value]}%' and description like '%{$this->data[search_value1]}%' ";
				
		$this->paging_controlling_field = array('perpage','order_by','order_type','current_page');
		$table_data =  $this->executeManage ($this->table_name, $this->data['perpage'], $where_sql );
		$this->protocols['content_instance']->setValue('table_data', $table_data);
		
		$this->protocols['content_instance']->setValue('page_controlling_value',  $this->renderPageControllingLink());	
		$this->protocols['content_instance']->setValue('perpage', $this->data['perpage']);
		$title_header = $this->renderingOrder ($this->module_name,'Title', 'title');			
		
		$this->protocols['content_instance']->setValue('validation_rules', $validation_rules);
		$this->protocols['content_instance']->setValue('table_header_column', array('ID', $title_header,'Active','Edit', 'Delete' ) );
		$this->protocols['content_instance']->setValue('table_row_column', array('id', 'title') );
		//$this->protocols['content_instance']->setValue('search_value', $this->data[search_value]);
		//$this->protocols['content_instance']->setValue('search_value1', $this->data[search_value1]);
			
		$value = $this->parseTemplate($this->module_name, 'manage');
		return $value;
	}
		
	
}
?>