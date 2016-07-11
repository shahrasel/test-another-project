<?php
class user extends parser
{ 
	
	var $module_name = 'user';
	var $table_name = 'user';
	
	var $protocols;
	var $protocolsList = array('content', 'formvalidation', 'xlsmanager', 'usertransaction', 'mailmanager', 'giftcertificatemanager');
		
	function user(){
		initProtocols (getConfigValue('protocol'), $this->protocolsList);
		$this->protocols = loadProtocols ($this->protocolsList);
		$this->table_name = getConfigValue('table_prefix').$this->table_name;
	}
	
	function unauthorize(){
		/*unsetSessionValue ('auth_value');
		unsetSessionValue ('user_role');
					
		setcookie ("user_info_id", "", time() - 3600 , "/", "", FALSE, TRUE);
		setcookie ("user_info_name", "", time() - 3600 , "/", "", FALSE, TRUE);
		setcookie ("auth_key", "", time() - 3600 , "/", "", FALSE, TRUE);*/
			
		//header ("location:".urlForAdmin('user/login'));
		$this->protocols['content_instance']->setValue('err_msg', 'Unauthorized Access');
		$value = $this->parseTemplate($this->module_name, 'unauthorize', '');
		return $value;
	}
	
	function logout(){
		unsetSessionValue (getConfigValue('auth_key'));
		//unsetSessionValue (getConfigValue('auth_value'));
		unsetSessionValue ('user_info');
		unsetSessionValue ('user_role');
		unsetSessionValue ('personal_info');
						
		setcookie ("user_info_id", "", time() - 3600 , "/", "", FALSE, TRUE);
		setcookie ("user_info_name", "", time() - 3600 , "/", "", FALSE, TRUE);
		setcookie ("auth_key", "", time() - 3600 , "/", "", FALSE, TRUE);
			
		header ("location:".urlForAdmin('user/login'));
		exit();
	}
	
	function flogout(){
		//echo '123';
		unsetSessionValue (getConfigValue('fauth_key'));
		//print_r (getConfigValue('fauth_key'));
		//unsetSessionValue (getConfigValue('auth_value'));
		unsetSessionValue ('fuser_info');
		unsetSessionValue ('fuser_role');
		unsetSessionValue ('personal_info');
						
		setcookie ("fuser_info_id", "", time() - 3600 , "/", "", FALSE, TRUE);
		setcookie ("fuser_info_name", "", time() - 3600 , "/", "", FALSE, TRUE);
		setcookie ("fauth_key", "", time() - 3600 , "/", "", FALSE, TRUE);
		session_regenerate_id ();		
		
		header ("location:".urlFor('index', array('')));
		exit();
	}
	
	function flogin(){
		
		$this->initPageValue();
		//$is_login = chkFrontendLogin ();
		$is_auth = chkFAuthentication ();
		
		
		
		if ( $is_auth ){
			 $role_info = getSessionValue('fuser_role');
			 header ("location:".urlFor('index', array('')));
			 exit();
		}
		
		if ( $_COOKIE[getConfigValue('fauth_key')] == getConfigValue('fauth_value') && !empty($_COOKIE['fuser_info_id']) && !empty($_COOKIE['fuser_info_name']) ){
				
			$user_info = $this->executeOne ('select * from '.$this->table_name.' where login_name=\''.
										$_COOKIE['fuser_info_name'].'\' and id='.$_COOKIE['fuser_info_id']." and active='Active'");
			$user_role = $this->executeOne ('select * from '.getConfigValue('table_prefix').'userrole'.' where id='.
										$user_info['role']." and active='Active'");
			$personal_info = $this->executeOne ('select * from '.getConfigValue('table_prefix').'userinfo'.
											' where user_id='.$user_info['id']);
			
			if ($user_info){
				//setSessionValue (getConfigValue('auth_key'), getConfigValue('auth_value'));
				setSessionValue ('fuser_info', $user_info);
				setSessionValue ('fuser_role', $user_role);
				setSessionValue ('personal_info', $personal_info);
				
				setSessionValue (getConfigValue('fauth_key'), getConfigValue('fauth_value'));
				
				//$role_info = getSessionValue('fuser_role');
				header ("location:".urlFor('index', array('')));					
		  }
		}
		
		//echo $this->data['signin'];
		if ($this->data['signin'] == 'signin'){
						
			$user_info = $this->executeOne ('select * from '.$this->table_name.' where login_name=\''.$this->data['login_name'].'\' and login_password=\''.md5($this->data['login_password']).'\'');
									
			$user_role = $this->executeOne ('select * from '.getConfigValue('table_prefix').'userrole'.' where id='.$user_info['role']." and active='Active'");
			
			$personal_info = $this->executeOne ('select * from '.getConfigValue('table_prefix').'userinfo'.
											' where user_id='.$user_info['id']);
			//print_r ($user_role); exit();
					
			if ($user_info){
				//setSessionValue (getConfigValue('auth_key'), getConfigValue('user'));
				setSessionValue ('fuser_info', $user_info);
				setSessionValue ('fuser_role', $user_role);
				setSessionValue ('personal_info', $personal_info);
				
				setSessionValue (getConfigValue('fauth_key'), getConfigValue('fauth_value'));
								
				if ($this->data['remember_me'] == 'remember'){
					$tmp_key = getConfigValue('fauth_key');
					$tmp_val = getConfigValue('fauth_value');
					
					setcookie($tmp_key, $tmp_val, time() + (60*60*24*30), "/", "", FALSE, TRUE);
					setcookie("fuser_info_id", $user_info['id'], time() + (60*60*24*30), "/", "", FALSE, TRUE);
					setcookie("fuser_info_name", $user_info['login_name'], time() + (60*60*24*30), "/", "", FALSE, TRUE);
				}
							
				//$role_info = getSessionValue('user_role');
				header ("location:".urlFor('index', array('')));
				exit();
		
			}
			else{
				echo 'Login failed';
			}	
			
		}
		
	}
	
	function login(){
		//echo '123';exit();
		unsetSessionValue('_formValidation');
		
		$this->initPageValue();
		$is_login = chkLogin ();
		
		if ( !empty ($is_login) ){
			 
			 $role_info = getSessionValue('user_role');
			 if ($role_info['title'] == 'publisher'){
			 	 //print_r ($role_info);
				 header ("location:".urlForAdmin('books/manage_publisher_books'));
				 exit();
			 }
			 else{	
				 header ("location:".urlForAdmin('pushnotification/manage'));
				 //header ("location:".urlForAdmin('city/manage'));
				 exit();
			}
		}
		
		if ( $_COOKIE[getConfigValue('auth_key')] == getConfigValue('auth_value') && !empty($_COOKIE['user_info_id']) && !empty($_COOKIE['user_info_name']) ){
			
			//echo $_COOKIE['user_info_name'];
			//exit();
			
			$user_info = $this->executeOne ('select * from '.$this->table_name.' where login_name=\''.$_COOKIE['user_info_name'].'\' and id='.$_COOKIE['user_info_id']." and active='Active'");
			
			$user_role = $this->executeOne ('select * from '.getConfigValue('table_prefix').'userrole'.' where id='.$user_info['role']." and active='Active'");
			
			$personal_info = $this->executeOne ('select * from '.getConfigValue('table_prefix').'userinfo'.
											' where user_id='.$user_info['id']);
				
			if ($user_info){
			
				//setSessionValue (getConfigValue('auth_key'), getConfigValue('auth_value'));
				setSessionValue ('user_info', $user_info);
				setSessionValue ('user_role', $user_role);
				setSessionValue ('personal_info', $personal_info);
				
				setSessionValue (getConfigValue('auth_key'), getConfigValue('auth_value'));
				
				 $role_info = getSessionValue('user_role');
				 if ($role_info['title'] == 'publisher'){
					 //print_r ($role_info);
					 header ("location:".urlForAdmin('books/manage_publisher_books'));
					 exit();
				 }
				 else{	
					 header ("location:".urlForAdmin('pushnotification/manage'));
					 //header ("location:".urlForAdmin('city/manage'));
					 exit();
				}
						
			  //header ("location:".urlForAdmin('page/manage'));
			  //exit();
		  }
		}
		/*
		//-------------------------------------------------------------------------------//
		*																				*
		*																				*
		*																				*
		//-------------------------------------------------------------------------------//
		*/
		if ($this->data['submit'] == 'Login'){
			
			
			$user_info = $this->executeOne ('select * from '.$this->table_name.' where login_name=\''.$this->data['login_name'].'\' and login_password=\''.md5($this->data['login_password']).'\'');
			
			
			$user_role = $this->executeOne ('select * from '.getConfigValue('table_prefix').'userrole'.' where id='.$user_info['role']." and active='Active'");
			
			$personal_info = $this->executeOne ('select * from '.getConfigValue('table_prefix').'userinfo'.
											' where user_id='.$user_info['id']);
			
					
			if ($user_info){
				
				setSessionValue (getConfigValue('auth_key'), getConfigValue('user'));
				setSessionValue ('user_info', $user_info);
				setSessionValue ('user_role', $user_role);
				setSessionValue ('personal_info', $personal_info);
				
				setSessionValue (getConfigValue('auth_key'), getConfigValue('auth_value'));
								
				if ($this->data['remember_me'] == 'remember'){
					$tmp_key = getConfigValue('auth_key');
					$tmp_val = getConfigValue('auth_value');
					
					setcookie($tmp_key, $tmp_val, time() + (60*60*24*30), "/", "", FALSE, TRUE);
					//echo $this->data['remember_me'];
					setcookie("user_info_id", $user_info['id'], time() + (60*60*24*30), "/", "", FALSE, TRUE);
					setcookie("user_info_name", $user_info['login_name'], time() + (60*60*24*30), "/", "", FALSE, TRUE);
				}
				
				//print_r ($user_info['login_name']);
				//echo ($_COOKIE['user_info_id']);
				//exit();		
				/*$role_info = getSessionValue('user_role');
				if ($role_info['title'] == 'publisher'){
					 header ("location:".urlForAdmin('books/manage_publisher_books'));
					 exit();
				}
				else{	
					 header ("location:".urlForAdmin('item/manage'));
					 exit();
				}*/	
				header ("location:".urlForAdmin('pushnotification/manage'));
				exit();
			}
			else{
				//echo 'Login';
				$this->protocols['content_instance']->setValue('err_msg', 'Unauthorized Access');
			}	
			
		}
						
		$value = $this->parseTemplate($this->module_name, 'login', '');
		return $value;
	}
	
	function radiovalue_cache(){
		$radiovalue_array = array();
		$radiovalue_array[0] = 'Inactive';
		$radiovalue_array[1] = 'Active';
		return $radiovalue_array;			
	}
	
	function optionvalue_active (){
		$optionvalue_array = array();
		$optionvalue_array['Inactive'] = 'Inactive';
		$optionvalue_array['Active'] = 'Active';
		return $optionvalue_array;			
	}
	
	function optionvalue_role (){
		
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
		//print_r ($validation_rules);
		
		$valid = $this->protocols['formvalidation_instance']->isvalid($this->data, $validation_rules);
		if ($valid){
			if ($this->data['submit'] == 'Submit'){
				
				if ( !empty ($this->data['login_password']) )
					$this->data['login_password'] = md5 ($this->data['login_password']);
				
				$this->executeAdd( $this->table_name, array('login_name','login_password','role','active', 'email') );
				/*$this->protocols['filemanager_instance']->imageResizeSave ($this->data['newsfile']['tmp_name'], 100, 100, 
																									getConfigValue('media').'News/'.$this->data['newsfile']['name']);*/
				header ("location:".urlForAdmin($this->module_name.'/manage'));
				exit();
			}
		}
		
		$this->protocols['content_instance']->setValue('validation_rules', $validation_rules);
		//$this->protocols['content_instance']->setValue('cache', 'yes');
		
		$value = $this->parseTemplate($this->module_name, 'add');
		unsetSessionValue('_formValidation');
		return $value;
	}
	
	function my_login_info(){
		
		unsetSessionValue('_formValidation');
		
		$this->initPageValue();
		$is_auth = chkAuthentication ();
		if ( !$is_auth ){
			 header ("location:".urlForAdmin('user/unauthorize'));
			 exit();
		}
		$this->chkAuthorization ($this->module_name);
		
		$validation_rules = $this->getFromInfo ($this->module_name, 'Form1.xml');
		//print_r ($validation_rules);
		$user_info = getSessionValue('user_info');
		//print_r ($user_info);
		//login_password
		
		if ($this->data['update'] == 'Update'){
			
			setConfigValue ('current_action', 'edit');						
			$valid = $this->protocols['formvalidation_instance']->isvalid($this->data, $validation_rules);
			if($valid && ( md5($this->data['old_password']) == $user_info['login_password']  || empty ($this->data['old_password']) ) ){
			//$this->data['short']  = str_replace(array("\r\n", "\r", "\n"), "", $this->data['short'] );
				//echo '123';
				if ( !empty ($this->data['login_password']) ){
					$this->data['login_password'] = md5 ($this->data['login_password']);
					$this->executeEdit( $this->table_name, array('login_name','login_password','email') );
				}
				else			
					$this->executeEdit( $this->table_name, array('login_name','email') );
			
				if ( !$this->getError() ){
					//header ("location:".urlForAdmin($this->module_name.'/my_login_info'));
					//exit();
					$this->protocols['content_instance']->setValue('msg', 'Information Updated Sucessfully');
				}
				//echo $this->getError();
			}
			
			else if(md5($this->data['old_password']) != $user_info['login_password'] && !empty ($this->data['old_password']) ){
				//echo '123';
				$this->protocols['content_instance']->setValue('err', 'Please Enter Your Old Password Correctly');
			}
			
		}
		
		
		$edit_info = $this->executeOne('select * from '.$this->table_name.' where id='.$user_info['id']);
		$this->protocols['content_instance']->setValue('edit_info', $edit_info);
		$this->protocols['content_instance']->setValue('form_action', 'my_login_info');
		
		$role_info = $this->executeAll('select * from '.$this->table_name.' where active=1');
		$this->protocols['content_instance']->setValue('role_info', $role_info);
		//$this->data['role_info'] = $role_info;
		//print_r($role_info);
		
		$this->protocols['content_instance']->setValue('validation_rules', $validation_rules);
		$value = $this->parseTemplate($this->module_name, 'edit');
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
		//print_r ($validation_rules);
		
		if (empty ($this->data['perpage']) )
			$this->data['perpage'] = getConfigValue('per_page');
			
		$this->paging_controlling_field = array('perpage','order_by','order_type','current_page');
		$page_controlling_value = $this->renderPageControllingLink();
		$this->protocols['content_instance']->setValue('page_controlling_value',  $page_controlling_value);
		
		if ($this->data['update'] == 'Update'){
								
			$valid = $this->protocols['formvalidation_instance']->isvalid($this->data, $validation_rules);
			if($valid){
			//$this->data['short']  = str_replace(array("\r\n", "\r", "\n"), "", $this->data['short'] );
				if ( !empty ($this->data['login_password']) ){
					
					$this->data['login_password'] = md5 ($this->data['login_password']);
					$this->executeEdit( $this->table_name, array('login_name','login_password','role','active', 'email') );
				}
				else			
					$this->executeEdit( $this->table_name, array('login_name','role','active', 'email') );
					
				
				if ( !$this->getError() ){
					header ("location:".urlForAdmin($this->module_name.'/manage').'?'.html_entity_decode($page_controlling_value));
					exit();
				}
			}
		}
		
		
		$edit_info = $this->executeOne('select * from '.$this->table_name.' where id='.$this->data['id']);
		$this->protocols['content_instance']->setValue('edit_info', $edit_info);
		
		$role_info = $this->executeAll('select * from '.$this->table_name.' where active=1');
		$this->protocols['content_instance']->setValue('role_info', $role_info);
		//$this->data['role_info'] = $role_info;
		//print_r($role_info);
		
		$this->protocols['content_instance']->setValue('validation_rules', $validation_rules);
		$value = $this->parseTemplate($this->module_name, 'edit');
		unsetSessionValue('_formValidation');
		return $value;
	}
	
	function manage(){
	
		//return 'aas';
		$this->initPageValue();
		$is_auth = chkAuthentication ();
		if ( !$is_auth ){
			 header ("location:".urlForAdmin('user/unauthorize'));
			 exit();
		}
		$this->chkAuthorization ($this->module_name);
		
		if (empty ($this->data['perpage']) )
			$this->data['perpage'] = getConfigValue('per_page');
		
		$where_sql = '';
						
		$this->paging_controlling_field = array('perpage','order_by','order_type', 'current_page');
		$table_data =  $this->executeManage ($this->table_name, $this->data['perpage'], $where_sql );
		$this->protocols['content_instance']->setValue('table_data', $table_data);
		
		$this->protocols['content_instance']->setValue('page_controlling_value',  $this->renderPageControllingLink());	
		$this->protocols['content_instance']->setValue('perpage', $this->data['perpage']);
		
		$login_name_header = $this->renderingOrder ($this->module_name,'User Name', 'login_name');
		$role_header = $this->renderingOrder ($this->module_name,'Role', 'role');
		
		$this->protocols['content_instance']->setValue('table_header_column', array('ID', $login_name_header, $role_header, 'Status', 'Edit', 'Delete' ) );
		$this->protocols['content_instance']->setValue('table_row_column', array('id', 'login_name', 'role') );
		
		//echo 'Select * FROM '.getConfigValue('table_prefix').'userrole where active=1';
		//$userrole_info = $this->executeAll ('Select * FROM '.getConfigValue('table_prefix').'userrole where active=1' );	
		//print_r ($userrole_info);
		
		$value = $this->parseTemplate($this->module_name, 'manage');
		return $value;
	}
	
	function balance (){
		$tmpuser = getSessionValue('user_info');
		//echo 'id--'.$tmpuser['id'];
		$balance = $this->protocols['usertransaction_instance']->getUserBalance($tmpuser['id']);	
		//echo $balance;
		$this->protocols['content_instance']->setValue ('balance', $balance);
		$value = $this->parseTemplate($this->module_name, 'balance', '');
		return $value;
	}

	function transaction (){
		
		$this->initRoutingValue();
		$this->initPageValue();
			
		//$this->data['rr'] = 'roni';
		$tmpuser = getSessionValue('user_info');
		
		$where_sql = '';
		/*if ( !empty($this->data[stitle]) )
			 $where_sql = " title like '%{$this->data[stitle]}%' ";
		if ( !empty($this->data[scatid])){
			if ( !empty( $where_sql) )
				 $where_sql = ' and ';
			$where_sql = " catid={$this->data[scatid]} ";
		}*/	 
			 				
		$paging_controlling_aray['perpage'] = $this->data['perpage'];
		$paging_controlling_aray['order_by'] = $this->data['order_by'];
		$paging_controlling_aray['order_type'] = $this->data['order_type'];
		$paging_controlling_aray['current_page'] = $this->data['current_page'];
		
		$paging_controlling_aray['particulars'] = $this->data['particulars'];
		$paging_controlling_aray['transaction_type'] = $this->data['transaction_type'];
		$paging_controlling_aray['from_date'] = $this->data['from_date'];
		$paging_controlling_aray['to_date'] = $this->data['to_date'];
			
		
		//$table_data =  $this->executeManage ($this->data['perpage'], $where_sql );
		
		$transaction_arry = $this->protocols['usertransaction_instance']->getUserTransactionInfo($tmpuser['id'], $paging_controlling_aray);	
		
		foreach ($transaction_arry as $key=>$transaction):
			//echo $transaction['product_table'];		
			if ( !empty($transaction['product_table'])  && $transaction['product_table'] != 'na' && $transaction['product_table'] != NULL):	
				//print_r ($transaction);
				$sql = 'Select * from '.$transaction['product_table'].' where id='.$transaction['product_id'];
				$product = $this->executeOne($sql);
				$transaction_arry[$key][$transaction['product_table']] = $product;
				
			endif;
		endforeach;
		
		//print_r ($transaction_arry);
		
		$this->paging_controlling_field = array_keys($paging_controlling_aray);
		
		$this->protocols['content_instance']->setValue('table_data', $transaction_arry);
		$this->protocols['content_instance']->setValue('page_controlling_value',  $this->renderPageControllingLink());	
		$this->protocols['content_instance']->setValue('perpage', $this->data['perpage']);
			
		$title_header = $this->renderingOrder ($this->module_name,'Title', 'title');
		//$short_desc_header = $this->renderingOrder ($this->module_name,'Short Description', 'sdesc');
		$price_header = $this->renderingOrder ($this->module_name,'Amount', 'amount','transaction');
		//echo $this->renderPageControllingLink();
		
		$str_paging = $this->renderPagination (urlForAdmin($this->module_name.'/transaction'), $num_rows, $perpage, $this->data['current_page'], TRUE);
		$this->protocols['content_instance']->setValue('str_paging', $this->protocols['usertransaction_instance']->str_paging);
		
				
		$this->protocols['content_instance']->setValue('table_header_column', array('ID', $title_header, 'Image',$price_header,'Active','Edit', 'Delete' ) );
		$this->protocols['content_instance']->setValue('table_row_column', array('id', 'title', 'booksfile', 'amount') );
		
		$this->protocols['content_instance']->setValue ('transaction_arry', $transaction_arry);
		$value = $this->parseTemplate($this->module_name, 'transaction', '');
		return $value;
	}
	
	function withdrawl (){
		
		$this->initRoutingValue();
		$this->initPageValue(); // rturn all the post value as its field name
		$tmpuser = getSessionValue('user_info');
		
		$this->error_string = '';
		$validation_rules = $this->getFromInfo ($this->module_name, 'withdrawl.xml');
		
				
		if ($this->data['withdrawl'] == 'withdrawl'){
				
			$valid = $this->protocols['formvalidation_instance']->isvalid($this->data, $validation_rules);
			if ($valid){
				//$this->executeAdd( $this->table_name, array('','login_password','role','active', 'email') );
				$product_table = 'na';
				$product_id = -1;
				$transaction_type = 'debit';
				$particulars = 'withdraw request';
				$qty = 1;
				$amount = $this->data['wamount'];
							
				$this->protocols['usertransaction_instance']->addToUserTransaction ($product_table, $product_id, $transaction_type, $particulars, $qty, $amount);
				
				if ( $this->error_string  ){
					$this->protocols['content_instance']->setValue('err', $this->error_string);
					//header ("location:".urlForAdmin($this->module_name.'/my_login_info'));
					//exit();
				}
				else{
					$this->protocols['content_instance']->setValue('msg', 'Withdraw Information Submitted');
				}
				
			}
			
		}
		
		$balance = $this->protocols['usertransaction_instance']->getUserBalance($tmpuser['id']);
		
		$this->protocols['content_instance']->setValue('validation_rules', $validation_rules);
		$this->protocols['content_instance']->setValue('balance', $balance);
		
		$value = $this->parseTemplate($this->module_name, 'withdrawl', '');
		unsetSessionValue('_formValidation');
		return $value;
		
	}
	
	function account (){
		
		$this->initRoutingValue();
		$this->initPageValue(); // rturn all the post value as its field name
		
		$activation_table = getConfigValue ('table_prefix').'useractivation';
		$query = "select * from $activation_table where activation_value='{$this->data['activation_value']}'";
		
		$activation_info = $this->executeOne ($query);
		
		if ($activation_info['user_id']){
			
			$query = "Update $this->table_name set active='Active' where id={$activation_info['user_id']}";
			$this->executeQuery ($query);
			
			$user_info = $this->executeOne ('select * from '.$this->table_name.' where id='.$activation_info['user_id']." and active='Active'");
			$user_role = $this->executeOne ('select * from '.getConfigValue('table_prefix').'userrole'.' where id='.
										$user_info['role']." and active='Active'");
					
			if ($user_info){
				//setSessionValue (getConfigValue('auth_key'), getConfigValue('auth_value'));
				setSessionValue ('fuser_info', $user_info);
				setSessionValue ('fuser_role', $user_role);
				setSessionValue (getConfigValue('fauth_key'), getConfigValue('fauth_value'));
				$this->protocols['giftcertificatemanager_instance']->receivePendingGift ();
				
				//$role_info = getSessionValue('fuser_role');
				header ("location:".urlFor('login-info', array('')));					
				exit();
			}
			
		}
		header ("location:".urlFor('404', array('')));					
		exit();
	}
	
	function forgotPass ($filter_name){
		
		unsetSessionValue('_formValidation');
		$this->initRoutingValue();
		$this->initPageValue(); // rturn all the post value as its field name
		
		$this->error_string = '';
		$validation_rules = $this->getFromInfo ($this->module_name, 'Forgot.xml');
		
		if ($this->data['recover'] == 'recover'){
			
			$_actCaptcha =  getSessionValue ('captcha_string');
			$_inputCaptcha = $this->data['captcha_string'];
			
			if ( strcasecmp( $_actCaptcha, $_inputCaptcha) === 0 ){
				
				$valid = $this->protocols['formvalidation_instance']->isvalid($this->data, $validation_rules);
							
				if($valid){
					//echo '123';
					$user_info = $this->executeOne('select * from '.$this->table_name." where email='{$this->data['email']}'");
					//print_r (	$user_info);
					
					if ($user_info){
						$_recover_value = md5 (time().'_'.$this->data['email'].'_'.$user_info['id'].'_r_valid');
						$recover_table = getConfigValue ('table_prefix').'userforgot';
						
						$this->executeQuery('update '.$recover_table." set active='Inactive' where user_id={$user_info['id']}");
						 
						$this->data['user_id'] = $user_info['id'];
						$this->data['recover_value'] = $_recover_value;
						$this->data['active'] = 'Active';
												
						$this->executeAdd( $recover_table, array('user_id','recover_value', 'active') );
						$personal_table = getConfigValue('table_prefix').'userinfo';
						$personal_info = $this->executeOne('select * from '.$personal_table." where user_id={$user_info['id']}");
						
						$to['name'] = $personal_info['first_name'].' '.$personal_info['last_name'];
						$to['mail'] = $user_info['email'];
						$to_array[] = $to;
												
						$recover_url = getConfigValue('site_url').'recover/user/password'.'?recover_value='.$_recover_value;
						
						$subject = 'AmarBoimela Password Recovery Mail';
						$alt_body = '--This is an automated mail, please dont reply--';
						$alt_body .= '<br /><br />'.'To recover your password '.'<a href="'.$recover_url.'">Click Here</a> or You may put this url:'.$recover_url.' in your browser.';
						$alt_body .= '<br /><br />'.'<strong>Note: Your previous password will be work untill you use this link</strong><br /><br />'.'<strong>Note: For Further Inquery, please send mail  @ '.getConfigValue('admin_email').' </strong>';
						$alt_body .= '<br /><br />'.'--'.getConfigValue('admin_email').'<br />'.'--'.getConfigValue('site_name');
						
						$this->protocols['content_instance']->setValue('mail_content', $alt_body );
						
						$body = $this->parseTemplate($this->module_name, 'mail_template_info');
		
						
						$mailer_status = $this->protocols['mailmanager_instance']->sendMailViaPhpMailer (getConfigValue('admin_email'), getConfigValue('admin_name'), $to_array, $subject, $body);
						
						
						if ( $mailer_status=='Message sent!' ){
							$this->protocols['content_instance']->setValue('msg', 'An Email sent to your email address, to recover password, you have to follow instraction on that mail');	
						}
						else{
							$this->protocols['content_instance']->setValue('err', 'Recover Porcedure Failor, Please Try Again');	
						}
						/*echo $this->protocols['mailmanager_instance']->sendMailViaSmtp ('roni@annanovas.com', 'K Roni Alam', $to_array, 'Test Message', 'Hi this is roni vai smtp', '', 'mail.annanovas.com','25',false,true,'roni@annanovas.com', 'rana686950');*/
					}
					
					
				}
					
			}
			else{
				//echo 'error_'.$_actCaptcha.'-'.$_inputCaptcha;
				$this->protocols['content_instance']->setValue('err', 'Please Fill the Captcha Correctly');
			}
		}
		

		$this->protocols['content_instance']->setValue('validation_rules', $validation_rules);
		$this->use_template = 1;	
	}
	
	function password (){
		
		$this->initRoutingValue();
		$this->initPageValue(); // rturn all the post value as its field name
		
		$recover_table = getConfigValue ('table_prefix').'userforgot';
		$recover_user_info = $this->executeOne('select * from '.$recover_table." where recover_value='{$this->data['recover_value']}' and active='Active'");
		
		if ($recover_user_info['id']){
			
			$this->executeQuery('Delete From '.$recover_table." where user_id={$recover_user_info['user_id']}");
			//print_r ($this->error_string);
			//echo 'Delete From '.$recover_table." where user_id={$recover_user_info['id']}";
			//exit();
			$user_info = $this->executeOne ('select * from '.$this->table_name.' where id='.$recover_user_info['user_id']." and active='Active'");
			$user_role = $this->executeOne ('select * from '.getConfigValue('table_prefix').'userrole'.' where id='.
										$user_info['role']." and active='Active'");
					
			if ($user_info){
				//setSessionValue (getConfigValue('auth_key'), getConfigValue('auth_value'));
				setSessionValue ('fuser_info', $user_info);
				setSessionValue ('fuser_role', $user_role);
				setSessionValue (getConfigValue('fauth_key'), getConfigValue('fauth_value'));
			
				//$role_info = getSessionValue('fuser_role');
				header ("location:".urlFor('login-info', array('')));					
				exit ();
			}
		}
		//print_r ($this->data);
		
		header ("location:".urlFor('login-info', array('')));					
		exit ();
	}
	
	function signup ($filter_name){
		
		unsetSessionValue('_formValidation');
		//echo '12';
		$this->initRoutingValue();
		$this->initPageValue(); // rturn all the post value as its field name
		
		$this->error_string = '';
		$validation_rules = $this->getFromInfo ($this->module_name, 'Signup.xml');
		//print_r ($validation_rules);
		
		if ($this->data['signup'] == 'signup'){
			
			$_actCaptcha =  getSessionValue ('captcha_string');
			$_inputCaptcha = $this->data['captcha_string'];
			
			if ( strcasecmp( $_actCaptcha, $_inputCaptcha) === 0 ){
				
				$valid = $this->protocols['formvalidation_instance']->isvalid($this->data, $validation_rules);
				if($valid){
					//echo '123';
					$this->data['login_password'] = md5 ($this->data['login_password']);
					$this->data['active'] = 'Inactive';
					$this->data['email'] = $this->data['login_name'];
					$this->data['role'] = getConfigValue('default_user_role');
					$user_id = $this->executeAdd( $this->table_name, array('login_name','login_password','role','active', 'email') );
					//print_r ($this->error_string);
					//exit();
					if ( empty ($this->error_string) ){
						
						$_activation_value = md5 (time().'_'.$this->data['email'].'_'.$user_id.'_r_valid');
						$activation_table = getConfigValue ('table_prefix').'useractivation';
						
						/*$this->executeQuery('update '.$activatation_table." set active='Inactive' where user_id={$user_info['id']}");*/
						 
						$this->data['user_id'] = $user_id;
						$this->data['activation_value'] = $_activation_value;
						$this->data['active'] = 'Active';
						$this->executeAdd( $activation_table, array('user_id','activation_value', 'active') );
						
						$to['name'] = $this->data['email'];
						$to['mail'] = $this->data['email'];
						$to_array[] = $to;
						
						$activation_url = getConfigValue('site_url').'activation/user/account'.'?activation_value='.$_activation_value;
						
						$subject = 'AmarBoimela Account Activation Mail';
						$alt_body = '--This is an automated mail, please dont reply--';
						$alt_body .= '<br /><br />'.'To activate your account'.'<a href="'.$activation_url.'"> Click Here</a> or You may put this url:'.$activation_url.' in your browser.';
						$alt_body .= '<br /><br />'.'<strong>Note: Still can\'t acivate your account, please send mail  @ '.getConfigValue('admin_email').'.</strong>';
						$alt_body .= '<br /><br />'.'--'.getConfigValue('admin_email').'<br />'.'--'.getConfigValue('site_name');
						
						
						
						$this->protocols['content_instance']->setValue('mail_content', $alt_body );
						
						$body = $this->parseTemplate($this->module_name, 'mail_template_info');
						//echo $body;
						//exit();
												
						$mailer_status = $this->protocols['mailmanager_instance']->sendMailViaPhpMailer (getConfigValue('admin_email'), getConfigValue('admin_name'), $to_array, $subject, $body);
						
						
						if ( $mailer_status !== 'Message sent!' ){
												
							$this->protocols['content_instance']->setValue('err', 'Activation Porcedure Failor, Please Try Again');	
						}


						header ("location:".urlFor('signup confirmation', array('')));
						exit();
					}
					else{
						$this->protocols['content_instance']->setValue('err', $this->error_string);
					}
					
				}
				
			}// end of validation of captcha
			else{
				//echo 'error_'.$_actCaptcha.'-'.$_inputCaptcha;
				$this->protocols['content_instance']->setValue('err', 'Please Fill the Captcha Correctly');
			}
			
		}
		$this->protocols['content_instance']->setValue('validation_rules', $validation_rules);
		$this->use_template = 1;
	}	
	
	function signin ($filter_name){
		
		unsetSessionValue('_formValidation');
		
		$this->initPageValue();
		$this->initRoutingValue();
		
		//echo '-r-'.getSessionValue ('from_url');;
		//print_r ($_SERVER);
		//$is_login = chkFAuthentication ();
		//print_r ($this->data);
		
		$validation_rules = $this->getFromInfo ($this->module_name, 'Signin.xml');
		
		if ($this->data['signin'] == 'signin' || isset($this->data['signin_x'])){
			//print_r ($this->data);
			 $valid = $this->protocols['formvalidation_instance']->isvalid($this->data, $validation_rules);
			if($valid){
		
				
				
				if ( $_COOKIE[getConfigValue('fauth_key')] == getConfigValue('fauth_value') && !empty($_COOKIE['fuser_info_id']) && !empty($_COOKIE['fuser_info_name']) ){
						
					$user_info = $this->executeOne ('select * from '.$this->table_name.' where login_name=\''.
												$_COOKIE['fuser_info_name'].'\' and id='.$_COOKIE['fuser_info_id']." and active='Active'");
					$user_role = $this->executeOne ('select * from '.getConfigValue('table_prefix').'userrole'.' where id='.
												$user_info['role']." and active='Active'");
					$personal_info = $this->executeOne ('select * from '.getConfigValue('table_prefix').'userinfo'.
												' where user_id='. $user_info['id']);
					
					if ($user_info){
						//setSessionValue (getConfigValue('auth_key'), getConfigValue('auth_value'));
						setSessionValue ('fuser_info', $user_info);
						setSessionValue ('fuser_role', $user_role);
						setSessionValue ('personal_info', $personal_info);
						
						setSessionValue (getConfigValue('fauth_key'), getConfigValue('fauth_value'));
						
						//$role_info = getSessionValue('fuser_role');
						$from_url = getSessionValue ('from_url');
						if ($from_url){
							uenseSessionValue ('from_url');
							header ("location:".getConfigValue('base_url').$from_url);						
						}
						else{
							header ("location:".urlFor('index', array('')));					
						}
						exit;
						
					}
				}
				
				//echo $this->data['signin'];
				if ($this->data['signin'] == 'signin' || isset($this->data['signin_x']) ){
					
					//echo '-r-'.getSessionValue ('from_url');;
					//exit();
					
					$user_info = $this->executeOne ('select * from '.$this->table_name.' where login_name=\''.$this->data['login_name'].'\' and login_password=\''.md5($this->data['login_password']).'\'');
											
					$user_role = $this->executeOne ('select * from '.getConfigValue('table_prefix').'userrole'.' where id='.$user_info['role']." and active='Active'");
					//print_r ($user_role); exit();
							
					if ($user_info){
						//setSessionValue (getConfigValue('auth_key'), getConfigValue('user'));
						setSessionValue ('fuser_info', $user_info);
						setSessionValue ('fuser_role', $user_role);
						setSessionValue (getConfigValue('fauth_key'), getConfigValue('fauth_value'));
										
						if ($this->data['remember_me'] == 'remember'){
							$tmp_key = getConfigValue('fauth_key');
							$tmp_val = getConfigValue('fauth_value');
							
							setcookie($tmp_key, $tmp_val, time() + (60*60*24*30), "/", "", FALSE, TRUE);
							setcookie("fuser_info_id", $user_info['id'], time() + (60*60*24*30), "/", "", FALSE, TRUE);
							setcookie("fuser_info_name", $user_info['login_name'], time() + (60*60*24*30), "/", "", FALSE, TRUE);
						}
									
						//$role_info = getSessionValue('user_role');
						$from_url = getSessionValue ('from_url');
						
						if ($from_url){
							unsetSessionValue ('from_url');
							header ("location:".getConfigValue('base_url').$from_url);						
						}
						else{
							header ("location:".urlFor('index', array('')));					
						}
						exit;
				
					}
					else{
						$err =  'Login failed, Invalid Information';
						$this->protocols['content_instance']->setValue('err', $err);
					}	
					
				}
			}
			
		}// siginin
		
		$this->protocols['content_instance']->setValue('validation_rules', $validation_rules);
		$this->use_template = 1;
		
	}
	
	function logininfo ($filter_name){
		
		unsetSessionValue('_formValidation');
		
		$this->initRoutingValue();
		$this->initPageValue();
		$is_auth = chkFAuthentication ();
		if ( !$is_auth ){
			 header ("location:".urlFor('signin', array()));
			 exit();
		}
		//$this->chkFAuthorization ($this->module_name);
		$this->chkFilterAuthorization ($this->module_name, $filter_name);
		//echo $filter_name;
		
		$this->error_string = '';
		//print_r ($this->data);
		
		$validation_rules = $this->getFromInfo ($this->module_name, 'logininfo.xml');
		
		$user_info = getSessionValue('fuser_info');
		$is_auth = chkFAuthentication ();
								
		if ( !$is_auth ){
			 //$role_info = getSessionValue('fuser_role');
			 header ("location:".urlFor('signin', array('')));
			 exit();
		}
				
		if ($this->data['update'] == 'update'){
			
			setConfigValue ('current_action', 'edit');						
			$valid = $this->protocols['formvalidation_instance']->isvalid($this->data, $validation_rules);
			
			if($valid){
				//$this->data['short']  = str_replace(array("\r\n", "\r", "\n"), "", $this->data['short'] );
				if ( !empty ($this->data['login_password']) ){
					$this->data['login_password'] = md5 ($this->data['login_password']);
					$this->executeEdit( $this->table_name, array('login_name','login_password') );
				}
				else {			
					$this->executeEdit( $this->table_name, array('login_name') );
				}
				
				if ( $this->error_string  ){
					$this->protocols['content_instance']->setValue('err', $this->error_string);
					//header ("location:".urlForAdmin($this->module_name.'/my_login_info'));
					//exit();
				}
				else{
					$this->protocols['content_instance']->setValue('msg', 'Information Updated');
				}
				
			}
		}
		
		$edit_info = $this->executeOne('select * from '.$this->table_name.' where id='.$user_info['id']);
		$this->protocols['content_instance']->setValue('edit_info', $edit_info);
		
		$role_info = $this->executeAll('select * from '.$this->table_name.' where active=1');
		$this->protocols['content_instance']->setValue('role_info', $role_info);
		
		$this->protocols['content_instance']->setValue('validation_rules', $validation_rules);
		$this->use_template = 1;
		
	}
	
	function contactus ($filter_name){
		
		unsetSessionValue('_formValidation');
		
		$this->initRoutingValue();
		$this->initPageValue();
		
		//echo $filter_name;
		
		$this->error_string = '';
		//print_r ($this->data);
		
		$validation_rules = $this->getFromInfo ($this->module_name, 'contactus.xml');
		
		$user_info = getSessionValue('fuser_info');
		//$is_auth = chkFAuthentication ();
				
		if ($this->data['update'] == 'update'){
			
			$_actCaptcha =  getSessionValue ('captcha_string');
			$_inputCaptcha = $this->data['captcha_string'];
			
			if ( strcasecmp( $_actCaptcha, $_inputCaptcha) === 0 ){
				
			
				$valid = $this->protocols['formvalidation_instance']->isvalid($this->data, $validation_rules);
				
				if($valid){
					//$this->data['short']  = str_replace(array("\r\n", "\r", "\n"), "", $this->data['short'] );
				
					$to['name'] = $this->data['username'];
					$to['mail'] = $this->data['email'];
					$to_array[] = $to;
					
					//print_r ($to_array);
					$subject = 'AmarBoimela User Inquery Mail';
					//$alt_body = '--This is an automated mail, please dont reply--';
					//echo $this->data['username'];
					
					$this->protocols['content_instance']->setValue('username', $this->data['username'] );
					$this->protocols['content_instance']->setValue('email', $this->data['email'] );
					$this->protocols['content_instance']->setValue('subject', $this->data['subject'] );
					$this->protocols['content_instance']->setValue('query', $this->data['query'] );
					
					$mail_content = $this->parseTemplate($this->module_name, 'mail_template_inquery', '');
						
					//$alt_body .= '<br /><br />'.'--'.getConfigValue('admin_email').'<br />'.'--'.getConfigValue('site_name');
												
					$this->protocols['content_instance']->setValue('mail_content', $mail_content );
					$body = $this->parseTemplate($this->module_name, 'mail_template_info');
					//echo $body;
					//exit();
															
					$mailer_status = $this->protocols['mailmanager_instance']->sendMailViaPhpMailer (getConfigValue('admin_email'), getConfigValue('admin_name'), $to_array, $subject, $body);
					
					
					if ( $mailer_status == 'Message sent!' ){
											
						$this->protocols['content_instance']->setValue('err', 'Mail Porcedure Failor, Please Try Again');				
						header ("location:".urlFor('contactus confirmation', array('')));
						exit();
					}
					else{
						$this->protocols['content_instance']->setValue('err', "Can not send mail please try again");
					}
				}
				
				
			}
			
			else{
				//echo '123';	
				//echo 'error_'.$_actCaptcha.'-'.$_inputCaptcha;
				$this->protocols['content_instance']->setValue('err', 'Please Fill the Captcha Correctly');
			}
			
		}
				
		$this->protocols['content_instance']->setValue('validation_rules', $validation_rules);
		$this->use_template = 1;
		
	}
	
	function createNewUser(){
	
		unsetSessionValue('_formValidation');
		
		$this->initPageValue();
		$is_auth = chkAuthentication ();
		if ( !$is_auth ){
			 header ("location:".urlForAdmin('user/unauthorize'));
			 exit();
		}
		$this->chkAuthorization ($this->module_name);
		
		if($this->data['submit'] == "Submit"){
			echo "Now we r in filter";
		}
		//print_r ($this->data);
		//echo "Now we r in filter";
		//exit();
		
		//$valid = formValidation($this->data);
		
		//$validation_rules = $this->getFromInfo ($this->module_name, 'Form_registration.xml');
		//print_r ($validation_rules);
		//$valid = $this->protocols['formvalidation_instance']->isvalid($this->data, $validation_rules);
		
		if ($valid){
			if ($this->data['submit'] == 'Submit'){
				
				if ( !empty ($this->data['login_password']) )
					$this->data['login_password'] = md5 ($this->data['login_password']);
				
					$this->executeAdd( $this->table_name, array('login_name','login_password','role','active', 'email') );
					/*$this->protocols['filemanager_instance']->imageResizeSave ($this->data['newsfile']['tmp_name'], 100, 100, 
																									getConfigValue('media').'News/'.$this->data['newsfile']['name']);*/
					header ("location:". urlFor('index', array()));
					exit();
				}
		}
		
		//$this->protocols['content_instance']->setValue('validation_rules', $validation_rules);
		//$this->protocols['content_instance']->setValue('cache', 'yes');
	
		$value = $this->parseTemplate($this->module_name, 'user_createNewUser', '');
		unsetSessionValue('_formValidation');
		return $value;
	}
		
}

?>