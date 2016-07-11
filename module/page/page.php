<?php
class page extends parser
{	
	
	var $module_name = 'page';
	var $table_name = 'page';
	
	var $protocols;
	var $isCache;
	var $isAjax;
	var $isSecure;
	var $pageId;
	var $filters;
	var $template;
	var $protocolsList = array('content', 'filter', 'xmlmanager', 'formvalidation', 'xlsmanager','filemanager', 'mailmanager');
	
	function getPageCache(){
		return $this->isCache;
	}
	
	function getPageId(){
		return $this->pageId;
	}
	
	function displayPage ($pageHtml){
		
		//print_r ($GLOBALS['cms_js']);
		echo $pageHtml;
		
	}
		
	function initPageId ($title){
		//echo "select id,filter,template from ".$this->table_name." where title='$title'"; 
		//exit(0);
		//echo "select id,filter,template,is_cache from ".$this->table_name." where menu_title='$title'";
		setConfigValue('unique_name', $title);
		$info = getConfigValue('dbhandler')->db->GetRow("select id,filter,template,is_cache,is_ajax,is_secure,meta_keyword,meta_description,page_title from ".$this->table_name." where unique_name='$title' and active='Active'");
		//print_r ($info);
		//exit();
		
		setGlobalValue ('page_title', $info['page_title']);
		setGlobalValue ('meta_keyword', $info['meta_keyword']);
		setGlobalValue ('meta_description', $info['meta_description']);
		
		$this->pageId = $info[id];
		$this->isCache = $info[is_cache];
		$this->isAjax = $info[is_ajax];
		$this->isSecure = $info[is_secure];
		
		if($info[filter]){		
			$this->filters = explode(',', $info[filter]);
			//print_r ($this->filters);
		}
		$this->template = $info[template];
		//exit();
	}
	
	function processAction (){
		//print_r (getConfigValue ('perform_data'));
		
		$tmp_data = getConfigValue ('perform_data');
		if ($tmp_data['cms_action']){
			
			$perform_module = ($tmp_data['cms_module']);
			$perform_action = $tmp_data['cms_action'];
			
			if ( !class_exists($perform_module.'_instance') ){
				require_once (getConfigValue('module').$perform_module.'/'.$perform_module.'_instance.php');
			}
			
			$tmp_instance = $perform_module.'_instance';
			$perform_class = new $tmp_instance();
			
			//
			$perform_class->$perform_action(); 
					
		}
		
	}
	
	function processFilter (){
		
		$this->processAction();
		
		//echo '123';
		//print_r ($this->filters);
		
		if($this->filters){
			foreach ($this->filters as $filter){
				$filter = trim($filter);
				$filter_criteria = explode ('_', $filter);
				$tmp_value = $this->protocols['filter_instance']->getOutPut($filter_criteria);
				$this->protocols['content_instance']->setValue($filter, $tmp_value);
			}
		}
		
	}
	
	function processAjax ($target_page){
		if ( !class_exists('Ajax_instance') ){
			require_once (getConfigValue('module').'ajax/'.'ajax_instance.php');
		}
		//echo $target_page;
		Ajax_instance::executeFile ($target_page);
	}
	
	function optionvalue_active (){
		$optionvalue_array = array();
		$optionvalue_array['Active'] = 'Active';
		$optionvalue_array['Inactive'] = 'Inactive';
		
		return $optionvalue_array;			
	}
		
	function page (){
		
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
				$this->executeAdd( $this->table_name, array('unique_name','meta_keyword','meta_description','page_title','navigation_name','filter','template','active','is_ajax','is_cache','page_bottom','is_secure', 'is_navigate') );
				header ("location:".urlForAdmin($this->module_name.'/manage'));
				exit();
			}
			
		}
				
		//print_r ($validation_rules);
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
		
		$cuser_role = getSessionValue ('user_role');
		if ($cuser_role['title'] == 'admin')
			$validation_rules = $this->getFromInfo ($this->module_name, 'Form.xml');
		else
			$validation_rules = $this->getFromInfo ($this->module_name, 'Form_alt.xml');
		
		if (empty ($this->data['perpage']) )
			$this->data['perpage'] = getConfigValue('per_page');
			
		$this->paging_controlling_field = array('perpage','order_by','order_type','current_page');
		$page_controlling_value = $this->renderPageControllingLink();
		$this->protocols['content_instance']->setValue('page_controlling_value',  $page_controlling_value);
		
		if ($this->data['update'] == 'Update'){
			
			$valid = $this->protocols['formvalidation_instance']->isvalid($this->data, $validation_rules);
			if($valid){
								
				$this->executeEdit( $this->table_name, array('unique_name','meta_keyword','meta_description','page_title','navigation_name','filter','template','active','is_ajax','is_cache','page_bottom','is_secure', 'is_navigate') );
				
				echo $this->getError();
				//echo html_entity_decode($page_controlling_value);
				//exit();
				
				header ("location:".urlForAdmin($this->module_name.'/manage').'?'.html_entity_decode($page_controlling_value));
				exit();
			}
			
		}
		
		
		$this->protocols['content_instance']->setValue('validation_rules', $validation_rules);
		
		$edit_info = $this->executeOne('select * from '.$this->table_name.' where id='.$this->data['id']);
		//print_r ($edit_info);
		$this->protocols['content_instance']->setValue('edit_info', $edit_info);
		
		$value = $this->parseTemplate($this->module_name, 'edit');
		unsetSessionValue('_formValidation');
		return $value;
	}
	
	function manage(){
		
		$cuser_role = getSessionValue ('user_role');
				
		$this->initPageValue();
		$is_auth = chkAuthentication ();
		if ( !$is_auth ){
			 header ("location:".urlForAdmin('user/unauthorize'));
			 exit();
		}
		$this->chkAuthorization ($this->module_name);
		
		$validation_rules = $this->getFromInfo ($this->module_name, 'Filter.xml');
						
		//echo '---'.$this->data['current_page'];
		if (empty ($this->data['perpage']) )
			$this->data['perpage'] = $this->data['perpage'] = getConfigValue('per_page');
		
		if (empty ($this->data['current_page']) )
			$this->protocols['content_instance']->setValue('current_page', $this->data['current_page']);
		//echo $this->data['current_page'];
		
		$where_sql = '';
		//if ( !empty($this->data[search_value]) )
			 //$where_sql = " title like '%{$this->data[search_value]}%' and description like '%{$this->data[search_value1]}%' ";
				
		$this->paging_controlling_field = array('perpage','order_by','order_type','current_page');
		$table_data =  $this->executeManage ($this->table_name, $this->data['perpage'], $where_sql );
		//print_r ($table_data);
		$this->protocols['content_instance']->setValue('table_data', $table_data);
		
		$this->protocols['content_instance']->setValue('page_controlling_value',  $this->renderPageControllingLink());	
		$this->protocols['content_instance']->setValue('perpage', $this->data['perpage']);
		
		$id_header = $this->renderingOrder ($this->module_name,'ID', 'id');
		$unique_name_header = $this->renderingOrder ($this->module_name,'Unique Name', 'unique_name');
		$navigation_name_header = $this->renderingOrder ($this->module_name,'Navigation Name', 'navigation_name');
		$template_header = $this->renderingOrder ($this->module_name,'Template', 'template');
		
		//$this->protocols['content_instance']->setValue('search_value', $this->data[search_value]);
		//$this->protocols['content_instance']->setValue('search_value1', $this->data[search_value1]);
			
		$this->protocols['content_instance']->setValue('validation_rules', $validation_rules);
		
		if ($cuser_role['title'] == 'admin')
			$this->protocols['content_instance']->setValue('table_header_column', array($id_header, $unique_name_header, $navigation_name_header, $template_header,'Filter','Cache','Ajax','Secure','Navigation','Active','Edit', 'Delete' ) );
		else
			$this->protocols['content_instance']->setValue('table_header_column', array($id_header, $unique_name_header, $navigation_name_header, $template_header,'Filter','Cache','Ajax','Secure','Navigation','Active','Edit') );
				
		$this->protocols['content_instance']->setValue('table_row_column', array('id', 'unique_name', 'navigation_name', 'template', 'filter', 'is_cache', 'is_ajax', 'is_secure','is_navigate') );
		
		$this->protocols['content_instance']->setValue('msg', $this->data['msg']);
		if ($cuser_role['title'] == 'admin')
			$value = $this->parseTemplate($this->module_name, 'manage');
		else
			$value = $this->parseTemplate($this->module_name, 'manage_alt');
			
		return $value;
	}
	
	function clear_cache (){
		
		
		 $directory = getConfigValue('cache');			
		 if( !$dirhandle = @opendir($directory) )
				return;

			while( ($filename = readdir($dirhandle)) ) {
				
				  $filename = $directory.$filename;
					@unlink($filename);
				
			}
			
			header ("location:".urlForAdmin($this->module_name.'/manage').'?&msg=Cach Clear Successfully');
			exit();
								
	}
	
	function getMainMenu (){
		
		$this->initRoutingValue();
		$this->initPageValue(); // rturn all the post value as its field name		
		
		$table_data = $this->executeAll('select unique_name,navigation_name from '.$this->table_name.' where is_navigate=1 order by navigation_order ');
		//print_r ($edit_info);
		return $table_data;
		
	}
	
	function navigation_order(){
		unsetSessionValue('_formValidation');
		
		$this->initPageValue();
		$is_auth = chkAuthentication ();
		if ( !$is_auth ){
			 header ("location:".urlForAdmin('user/unauthorize'));
			 exit();
		}
		$this->chkAuthorization ($this->module_name);
		
		//$where_sql = ' show_on_top=1 ';
		//$table_data =  $this->executeManage ($this->table_name, $this->data['perpage'], $where_sql );
		//print_r ($this->data);
		if ($this->data['update'] == 'Update Order'){
			
			//print_r ($this->data['top_cat_order']);
			foreach ( $this->data as $key=>$val ){
				
				if ( strstr($key, 'navigation_order_') ){
					 $target_id_array = explode ('navigation_order_', $key);
					
					 $query = " update $this->table_name set navigation_order=".$val." where id=".$target_id_array[1];
					 $this->executeQuery ($query);
				}
				
				
			}
			
		}
		
		$query = " Select * from $this->table_name where is_navigate=1 and active='Active' order by  navigation_order";

		$table_data = $this->executeAll($query);
		
		$this->protocols['content_instance']->setValue('table_data', $table_data);
		
		$this->protocols['content_instance']->setValue('table_header_column', array('ID', 'Page Name','Menu Order (Asc to Desc Order)' ) );
		$this->protocols['content_instance']->setValue('table_row_column', array('', 'unique_name') );
		
		$this->protocols['content_instance']->setValue('field_name', 'navigation_order');
		$this->protocols['content_instance']->setValue('action', 'navigation_order');
		
		
		$value = $this->parseTemplate($this->module_name, 'manage_order');
		return $value;
	}
	
	
	function loginForm($filter_name){
		$this->initRoutingValue();
		$this->initPageValue(); // rturn all the post value as its field name		
		
		$this->use_template = 1;
	}
	
	function contactUs ($filter_name){
		
		unsetSessionValue('_formValidation');
		
		$this->initRoutingValue();
		$this->initPageValue(); // rturn all the post value as its field name		
		
		
		//$this->use_template = 1;
		$validation_rules = $this->getFromInfo ($this->module_name, 'Contact-Form.xml');
		
		$_actCaptcha =  getSessionValue ('captcha_string');
		$_inputCaptcha = $this->data['captcha_string'];
		
		
		if ($this->data['submit'] == 'Submit'){
			if ( strcasecmp( $_actCaptcha, $_inputCaptcha) === 0 ){
					
				if ($this->data['submit'] == 'Submit'){
					//print_r ($validation_rules);
					$valid = $this->protocols['formvalidation_instance']->isvalid($this->data, $validation_rules);
					if($valid){
						//$this->executeAdd( $this->table_name, array('name','email','message') );
						//echo '12';
						
						$to['name'] = getConfigValue('admin_name');
						$to['mail'] = getConfigValue('admin_email');
						$to_array[] = $to;
						
						//print_r ($to);
												
						//$recover_url = getConfigValue('site_url').'recover/user/password'.'?recover_value='.$_recover_value;
						
						$subject = 'Annanovas Contact US Mail';
						$alt_body = '--Mail Sent From Viewer--';
						
						//$this->protocols['content_instance']->setValue('mail_content', $alt_body );
						
						$body = "Mail From :{$this->data['name']} <br />Email:{$this->data['email']} <br />{$this->data['message']}";
		
						
						$mailer_status = $this->protocols['mailmanager_instance']->sendMailViaPhpMailer ($this->data['email'], $this->data['name'], $to_array, $subject, $body);
						
						//mail command :)
						
						if ( $mailer_status=='Message sent!' ){
							$this->protocols['content_instance']->setValue('msg', 'Thanks For Your mail<br />We will contact with you within shortest possible time.');	
						}
						else{
							
							//$this->protocols['content_instance']->setValue('err', $mailer_status);
							$this->protocols['content_instance']->setValue('err', 'Mail Send Failor, Please Try Again');	
						}
						
						//header ("location:".urlFor('contact-us', array() ));
						//exit();
					}
					
				}
			}
			else{
				//echo 'error_'.$_actCaptcha.'-'.$_inputCaptcha;
				$valid = $this->protocols['formvalidation_instance']->isvalid($this->data, $validation_rules);
				$this->protocols['content_instance']->setValue('err', 'Please Fill the Verification Code Correctly');
			}
		}
		//print_r ($validation_rules);
		//$this->protocols['content_instance']->setValue('validation_rules', $validation_rules);
		$this->protocols['content_instance']->setValue('validation_rules', $validation_rules);
		$value = $this->parseTemplate($this->module_name, 'page_contactUs', '');
		//unsetSessionValue('_formValidation');
		
		return $value;
		
	}
	
	function search ($filter_name){
		
		$this->initRoutingValue();
		$this->initPageValue();
		
		$search_value  = ($this->data['search_value'])?$this->data['search_value']:$this->routing_data['search_value'];
		$this->protocols['content_instance']->setValue('search_value', $search_value);
		
		$this->use_template = 1;
		
	}
	
	
}
?>
