<?php
class adminnavigation
{
	var $protocols;
	var $protocolsList = array('content');
	
	function adminnavigation(){
		initProtocols (getConfigValue('protocol'), $this->protocolsList);
		$this->protocols = loadProtocols ($this->protocolsList);
	}
	
	function getMenu(){
		
		
		$module_list = getCommonUserModuleList();
		//print_r ($module_list);
		$installable_list = getInstallableModuleList();
		//$module_list = sort ($module_list);
		$result = array();
		$query = "Select * From ".getConfigvalue('table_prefix')."modulemanager ";
		$result = getConfigValue('dbhandler')->db->GetAll ($query);
		
		$install_list = array();
		if ($result){
			foreach ($result as $tmp_moule){
				$install_list[] = $tmp_moule['name'];
			}
		}
		//print_r ($install_list);
		
		$authorize_menu_array = array();
		
		//print_r ($install_list);
		//echo '123';
		//exit ();
		
		if ( $module_list ){
			
			foreach ($module_list as $module){
				
				if ( 
					(in_array ($module, $install_list) && in_array ($module, $installable_list) ) 
					||
					( !in_array ($module, $installable_list) )
				){
				   	//echo $module;
						$xml = io_file_get_contents(getConfigValue('module').$module."/Submenu.xml");
						$submenu_array = xml_get_all_value ('submenu', $xml, 0);
						//print_r ($submenu_array);
						
						$role_info = getSessionValue('user_role');
						$security_xml = io_file_get_contents(getConfigValue('module').$module."/Security.xml");		
						
						
						$authorize_menu_tag = 0;
						
						if ($submenu_array){
							foreach ($submenu_array as $submenu){
								//echo $submenu_array[0];
								$security_array =  xml_get_value($submenu, $security_xml, 0);
								$security_role_array = xml_get_all_value ('role', $security_array['value'], 0);
								//print_r ($security_role_array);
								
								if ( empty($security_role_array) || in_array($role_info['title'], $security_role_array) ){
									 $authorize_menu_tag = 1;
								}
								
							}//foreach
						}
						if ($authorize_menu_tag == 1)
							$authorize_menu_array[] = $module;		
				  }
					
			}// foreach 
		} // if
		//print_r ($authorize_menu_array);
		
		$template_path = getConfigValue('module').'adminnavigation/templates/';
		$this->protocols['content_instance']->setTemplate($template_path.'menu.php');
		$this->protocols['content_instance']->setValue('menu_array', $authorize_menu_array);
		$value = $this->protocols['content_instance']->contentView();
		return $value;
		
	}
		
	function getSubMenu($module){
			
		//print_r ($security_xml);
		$xml = io_file_get_contents(getConfigValue('module').$module."/Submenu.xml");
		$submenu_array = xml_get_all_value ('submenu', $xml, 0);
		//print_r ($submenu_array);
		$role_info = getSessionValue('user_role');
		$security_xml = io_file_get_contents(getConfigValue('module').$module."/Security.xml");
		//print_r ($security_xml);
		
		$authorize_submenu_array = array();
		if ($submenu_array){
			foreach ($submenu_array as $submenu){
				//echo $submenu_array[0];
				$security_array =  xml_get_value($submenu, $security_xml, 0);
				$security_role_array = xml_get_all_value ('role', $security_array['value'], 0);
				//print_r ($security_role_array);
				
				if ( empty($security_role_array) || in_array($role_info['title'], $security_role_array) ){
					 $authorize_submenu_array[] = $submenu;
				}
				
			}
		}
			
		$template_path = getConfigValue('module').'adminnavigation/templates/';
		$this->protocols['content_instance']->setTemplate($template_path.'submenu.php');
		$this->protocols['content_instance']->setValue('module', $module);
		$this->protocols['content_instance']->setValue('submenu_array', $authorize_submenu_array);
		$value = $this->protocols['content_instance']->contentView();
		return $value;
	}		
	
	
}


?>