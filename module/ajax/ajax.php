<?php
class ajax extends parser
{	
	
	function add(){
	}
	
	function edit(){
	}
	
	function manage(){
	}
	
	function getFilterOutPut($filter){
	}
	
	function executeFile ($target_page){
		$perform_data = getConfigValue('perform_data');
		
		$this->initRoutingValue();
		$this->initPageValue();
		//$page_data = $this->data;
		//$routing_data = $this->routing_data;
		
		//getConfigValue('module').'Page/templates/'.$target_page.getConfigValue('template_suffix');
		//print_r ($this->routing_data);
		//$post_data = $this->initPageValue();
		//echo getConfigValue('page_suffix');
		//$template_name = basename($perform_data[file_name], getConfigValue('page_suffix'));
		require_once (getConfigValue('module').'page/templates/'.$target_page.getConfigValue('template_suffix'));
	}	
}
?>