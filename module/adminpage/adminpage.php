<?php
class adminpage 
{	
	
	var $protocols;
	protected $pageId;
	protected $filters;
	var $protocolsList = array('content', 'filter', 'adminrouting');
		
	function adminpage (){
		initProtocols (getConfigValue('protocol'), $this->protocolsList);	
		$this->protocols = loadProtocols ($this->protocolsList);
	}
	
	function displayPage ($pageHtml){
		echo $pageHtml;
	}
	
	function setPageId ($val){
		$this->pageId = $val;
	}
		
	
	function processFilter (){
		
		foreach ($this->filters as $filter){
				
				$filter_criteria = explode ('_', $filter);
				$tmp_value = $this->protocols['filter_instance']->getOutPut($filter_criteria);
				$this->protocols['content_instance']->setValue($filter, $tmp_value);
		}
		
	}
	
	
}

?>