<?php
	
class xlsHelper{	
	
	var $workbook;
	var $worksheet;
	var $fname;
	
	function initXls(){
		//echo '12';
		require_once (getConfigValue('lib').'php_writeexcel/class.writeexcel_workbook.inc.php');
		require_once (getConfigValue('lib').'php_writeexcel/class.writeexcel_worksheet.inc.php');
		
		$this->fname = tempnam("/tmp", "simple.xls");
		//fname = getConfigValue('media').'';
		$this->workbook = &new writeexcel_workbook ($this->fname);
		$this->worksheet = &$workbook->addworksheet ();
		
	}
		

}

?>