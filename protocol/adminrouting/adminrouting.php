<?php
abstract class adminRouting
{
    var $template;
		var $action;
		var $module;
		var $performInstance; 
		// Common method
    public function parseUrl($url) {
    	//echo $url;
			//exit();
			if (!empty ($url) )	{
				//echo $url;
				$url_array = explode('/', $url);
				//echo count ($url_array);
				//exit();
				
				if (count($url_array) >= 2){
					//cho getConfigValue('template_ext');
					//exit();
					$this->module = $url_array[0];
					
					if (empty($url_array[1]))
					{
						$this->action = 'manage';
						$this->template = 'manage.php';
					}
					else
					{
						$this->action = basename($url_array[1], getConfigValue('page_suffix'));
						$this->template = $url_array[1];
					}
						
					$tmp_module =  ($this->module);
					initModule (getConfigValue('module') ,$tmp_module);
					$this->performInstance = loadModule($tmp_module);
				}
				//$this->performInstance->$acton();
				
				else if (count($url_array) >= 1){
					//echo $url;
					//exit();
					
					$this->module = basename($url_array[0], getConfigValue('page_suffix'));
									
					//exit();
					if ( $role_info['title'] != 'admin'){
						
						$role_info = getSessionValue('user_role');
						$xml = io_file_get_contents(getConfigValue('module').$this->module."/Submenu.xml");
						$submenu_array = xml_get_all_value ('submenu', $xml, 0);
						$security_xml = io_file_get_contents(getConfigValue('module').$this->module."/Security.xml");
						
						$authorize_submenu = '';
						if ($submenu_array){
							foreach ($submenu_array as $submenu){
								//echo $submenu_array[0];
								$security_array =  xml_get_value($submenu, $security_xml, 0);
								$security_role_array = xml_get_all_value ('role', $security_array['value'], 0);
								//print_r ($security_role_array);
								
								if ( empty($security_role_array) || in_array($role_info['title'], $security_role_array) ){
									 $authorize_submenu = $submenu;
									 break;
								}
								
							}
						}
						$this->action = $authorize_submenu;
					
					}
					else{
						$this->action = 'manage';
					}
					//$this->template = 'manage.php';
					
					$tmp_module =  ($this->module);
					initModule (getConfigValue('module') ,$tmp_module);
					$this->performInstance = loadModule($tmp_module);
				}
			}
			else{
				//echo '123';
				//exit();
				$this->module = basename('user');
				$this->action = 'login';
				$this->template = 'login.php';
				
				$tmp_module =  ($this->module);
				initModule (getConfigValue('module') ,$tmp_module);
				$this->performInstance = loadModule($tmp_module);
			}
			
    }
}


?>