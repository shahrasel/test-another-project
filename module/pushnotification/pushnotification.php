<?php
class pushnotification extends parser
{	
	var $module_name = 'pushnotification';
	var $table_name = 'pushnotification';
	
	var $protocols;
	//protected $pageId;
	protected $filters;
	var $template;
	
	var $protocolsList = array('content', 'filter', 'formvalidation', 'xlsmanager');
	
	function pushnotification (){
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
				$this->data['created_at'] = date('m-d-y');
				$this->executeAdd( $this->table_name, array('pushnotification_area', 'district_area', 'active', 'created_at', 'updated_at', 'modified_user_id', 'modified_user_email') );
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
				$this->data['created_at'] = date('m-d-y');
				$date_arry = explode ('-', $this->data['created_at']);
				$this->data['created_at'] = mktime(0,0,0, $date_arry[0], $date_arry[1], $date_arry[2]);
				
				$user_info = getSessionValue('user_info');
								
				$this->data['updated_at'] =	$this->data['created_at'];
				$this->data['modified_user_id'] = $user_info['id'];
				$this->data['modified_user_email'] = $user_info['email'];
				
				$this->executeEdit( $this->table_name, array('pushnotification_area', 'district_area', 'active', 'updated_at', 'modified_user_id', 'modified_user_email') );
				header ("location:".urlForAdmin($this->module_name.'/manage').'?'.html_entity_decode($page_controlling_value));
			}
		}
		
		$edit_info = $this->executeOne('select * from '.$this->table_name.' where id='.$this->data['id']);
		$edit_info['created_at'] = date('m-d-Y', $edit_info['created_at']);
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
		
		//print_r($this->data);
		
		
		$where_sql = ' 1=1 ';
		
		if(!empty($this->data['flang'])) {
			$where_sql .= " and language = '".$this->data['flang']."' ";
			$this->protocols['content_instance']->setValue('flang', $this->data['flang']);
		}
						
		$this->paging_controlling_field = array('perpage','order_by','order_type','current_page','flang');
		$table_data =  $this->executeManage ($this->table_name, $this->data['perpage'], $where_sql );
		
		$this->protocols['content_instance']->setValue('table_data', $table_data);
		
		$this->protocols['content_instance']->setValue('page_controlling_value',  $this->renderPageControllingLink());	
		$this->protocols['content_instance']->setValue('perpage', $this->data['perpage']);
		$id_header = $this->renderingOrder ($this->module_name,'ID', 'id');
		$udid_header = $this->renderingOrder ($this->module_name,'udid', 'udid');
		
		
		$this->protocols['content_instance']->setValue('validation_rules', $validation_rules);
		$this->protocols['content_instance']->setValue('table_header_column', array($id_header, $udid_header,'Notify','Delete') );
		$this->protocols['content_instance']->setValue('table_row_column', array('id', 'udid') );
		
			
		$value = $this->parseTemplate($this->module_name, 'manage','');
		return $value;
	}
	
	function sendnotification() {
		$this->initPageValue();
		
		
		if(!empty($this->data['hiddenval'])) {
			$device_info = $this->executeOne("select * from an_pushnotification where id =".$this->data['hiddenval']); 
			/*print_r($device_info);
			exit;*/
			if(!empty($device_info)) {
				//print_r($device_info);
				//print_r($this->data);
				$apnsHost = 'gateway.push.apple.com';
				//$apnsHost = 'gateway.push.apple.com';

				$apnsPort = 2195;
				$apnsCert = getConfigValue('lib').'certificates_prod_berca.pem';
				
				$data['id'] = 'userid';
				$data['info'] = 'userinfo';
				$payload['aps'] = array('alert' => $this->data['mtext'], 'badge' => 1, 'sound' => 'default', 'userinfo' => $data);
				$payload = json_encode($payload);
				$streamContext = stream_context_create();
				stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
				$apns = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 60, STREAM_CLIENT_CONNECT, $streamContext);
				if (!$apns) {
					print "Failed to connect $error $errorString";
					return;
				}
				else {	
					print "Connection OK\n";
				}
				/*$healthy = array("<", ">");
				$yummy   = array("", "");
				$deviceToken = str_replace($healthy, $yummy, $device_info['udid']);*/
				$deviceToken = $device_info['udid'];
				//echo $deviceToken.'\n';
				$apnsMessage = chr(0) . pack("n",32) . pack('H*', str_replace(' ', '', $deviceToken)) . pack("n",strlen($payload)) . $payload;
				//print "sending message :" . $payload . "n";  
				fwrite($apns, $apnsMessage);
				//echo 'errorString:'.$errorString;
				//echo 'error:'.$error;	
				fclose($apns);
				
				
			}			
			
		}
		
		setSessionValue('sess_status','Notification Sent Successfully');
		header ("location:".urlForAdmin('pushnotification/manage'));
		exit();
		
	}
	
	function pushnotificationTotalUser() {
		$this->initPageValue();
		
		/*$this->executeQuery("DELETE FROM `an_tmpnotification`");
		
		if(!empty($this->data['udid'])) {
			foreach($this->data['udid'] as $id) {
				$this->data['udid'] = $id;	
				$this->executeAdd( 'an_tmpnotification', array('udid') );
			}	
		}*/
		
		$user_lists = $this->executeAll("select * from an_pushnotification");
		echo count($user_lists);
		
		
		//print_r($this->data);
		exit;	
	}
	
	
	function pushnotificationscr(){
		$this->initPageValue();
		
		$selLimit = 10;
		
		
		$user_lists = $this->executeAll("select * from an_pushnotification limit ".$this->data['start'].",".$selLimit);
		
			
		echo count($user_lists);
		
		//exit;	
		if (!empty($user_lists)) {
			
			$apnsHost = 'gateway.push.apple.com';
			//$apnsHost = 'gateway.sandbox.push.apple.com';

			$apnsPort = 2195;
			
			//$apnsCert = getConfigValue('lib').'apns-dev.pem';
			$apnsCert = getConfigValue('lib').'certificates_prod_berca.pem';

			//exit;
			//if(file_exists())
			$data['id'] = 'userid';
			$data['info'] = 'userinfo';
			
			
			$payload['aps'] = array('alert' => $this->data['mtext'], 'badge' => 1, 'sound' => 'default', 'userinfo' => $data);
			$payload = json_encode($payload);
			
			
			$streamContext = stream_context_create();
			stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
			
			$apns = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 60, STREAM_CLIENT_CONNECT, $streamContext);
			
			if (!$apns) {
				//print "Failed to connect $err $errstrn";
				return;
			}
			else {	
				//print "Connection OK\n";
			}
			
			
				
			foreach($user_lists as $user_list) {
				
				$deviceToken = $user_list['udid'];
				//echo $deviceToken.'\n';
				
				$apnsMessage = chr(0) . pack("n",32) . pack('H*', str_replace(' ', '', $deviceToken)) . pack("n",strlen($payload)) . $payload;
				//print "sending message :" . $payload . "n";  
				
				fwrite($apns, $apnsMessage);
			
				
				//echo 'errorString:'.$errorString;
				//echo 'error:'.$error;	
				
			}	
		}
		//exit;
		fclose($apns);
				
		exit;
	}
	
	function send_push_notificaton () {
		
		unsetSessionValue('_formValidation');
		
		$this->initPageValue();
		$is_auth = chkAuthentication ();
		if ( !$is_auth ){
			 header ("location:".urlForAdmin('user/unauthorize'));
			 exit();
		}
		$this->chkAuthorization ($this->module_name);
		
		$validation_rules = $this->getFromInfo ($this->module_name, 'Form.xml');
		/*
		if ($this->data['submit'] == 'Submit'){
			
			$valid = $this->protocols['formvalidation_instance']->isvalid($this->data, $validation_rules);
			
			
			
			if($this->data['language'] != 'all') 
				$user_lists = $this->executeAll("select * from an_pushnotification where language = '".$this->data['language']."'");
			else
				$user_lists = $this->executeAll("select * from an_pushnotification where language = 'en' or language = 'nb'");			
												
			if($valid){
				if (!empty($user_lists)) {
					
					//print_r($user_lists);
					//exit;
					$apnsHost = 'gateway.push.apple.com';
					//$apnsHost = 'gateway.sandbox.push.apple.com';

					$apnsPort = 2195;
					
					//$apnsCert = getConfigValue('lib').'apns-dev.pem';
					$apnsCert = getConfigValue('lib').'apns-heliports.pem';

					//exit;
					//if(file_exists())
					$data['id'] = 'userid';
					$data['info'] = 'userinfo';
					
					
					$payload['aps'] = array('alert' => $this->data['mtext'], 'badge' => 1, 'sound' => 'default', 'userinfo' => $data);
					$payload = json_encode($payload);
					
					
					$streamContext = stream_context_create();
					stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
					
					$apns = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 60, STREAM_CLIENT_CONNECT, $streamContext);
					
					if (!$apns) {
						//print "Failed to connect $err $errstrn";
						return;
					}
					else {	
						//print "Connection OK\n";
					}
					
					
						
					foreach($user_lists as $user_list) {
						
						$deviceToken = $user_list['udid'];
						//echo $deviceToken.'\n';
						
						$apnsMessage = chr(0) . pack("n",32) . pack('H*', str_replace(' ', '', $deviceToken)) . pack("n",strlen($payload)) . $payload;
						//print "sending message :" . $payload . "n";  
						
						fwrite($apns, $apnsMessage);
					
						
						//echo 'errorString:'.$errorString;
						//echo 'error:'.$error;	
						
					}	
				}
				//exit;
				fclose($apns);
				
				
				
				
				setSessionValue('sent_message','Notification Sent Successfully');
				
				
				header ("location:".urlForAdmin($this->module_name.'/manage'));
			}
			
		}*/
		
		$user_lists = $this->executeAll("select * from an_pushnotification");
		
		//print_r($user_lists);
		
		$this->protocols['content_instance']->setValue('user_lists', $user_lists);
		$this->protocols['content_instance']->setValue('validation_rules', $validation_rules);
		$value = $this->parseTemplate($this->module_name, 'send_mail','');
		unsetSessionValue('_formValidation');
		return $value;
		
		
	}
	
	function saveudid () {
		
		$this->initPageValue();
		
		if(!empty($this->data['udid'])) {
		
			$this->data['created_at'] = time();
			
			if(!empty($this->data['udid']))
				$this->data['udid'] = trim($this->data['udid'], '<>');
			
			$udid_lists =  $this->executeOne("select * from ".$this->table_name." where udid='".$this->data['udid']."'");
			
			//print_r($this->data);
			
			if(!empty($udid_lists) > 0) {
				echo 'already exists';
			}
			else {
				$inserted_id = $this->executeAdd( $this->table_name, array('udid', 'created_at') );
				if($inserted_id > 0)
					echo 'added successfully';
				else
					echo 'not added successfully';
			}
		}
		exit;
			
	}
	
	function optionvalue_active (){
		$optionvalue_array = array();
		$optionvalue_array['Active'] = 'Active';
		$optionvalue_array['Inactive'] = 'Inactive';
		return $optionvalue_array;			
	}
	
	function optionvalue_flang (){
		$optionvalue_array = array();
		$optionvalue_array[''] = 'All';
		$optionvalue_array['en'] = 'English';
		$optionvalue_array['nb'] = 'Norge';
		return $optionvalue_array;			
	}
		
}
?>