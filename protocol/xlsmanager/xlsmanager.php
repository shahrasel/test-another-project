<?php
	
class xlsmanager{	
	
	var $workbook;
	var $worksheet;
	var $fname;
	
	function xlsmanager(){
		//echo '12';
		require_once (getConfigValue('lib').'php_writeexcel/class.writeexcel_workbook.inc.php');
		require_once (getConfigValue('lib').'php_writeexcel/class.writeexcel_worksheet.inc.php');
		
		require_once (getConfigValue('lib').'php_readexcel/reader.php');
				
	}
	//php_readexcel
	function readXls($csv_file){
		//$csv_file = $artists_xls_file_name;
		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('CP1251');
		$data->read($_csv_upload_dir.$csv_file);
		return $data;
	}
	
	function initXls ($name){
		
		$this->fname = tempnam("/tmp", $name);
		//fname = getConfigValue('media').'';
		$this->workbook = &new writeexcel_workbook ($this->fname);
		$this->worksheet = &$this->workbook->addworksheet ();
		
	}
	
	function saveXls ($file_path){
		$this->workbook->close();
		
		$content = file_get_contents ($this->fname);
		$handle = fopen ("$file_path", "w+");
		file_put_contents ("$file_path", $content);
		
	}	
	
	function downloadXls ($file_name, $rmove_also = 0){
		$this->workbook->close();
		
		header("Content-Type: application/x-msexcel; name=\"$file_name\"");
		header("Content-Disposition: inline; filename=\"$file_name\"");
		
		$fh=fopen($this->fname, "rb");
		fpassthru($fh);
		unlink($this->fname);
			
	}
	
	function saveAndDownloadXls ($file_path, $file_name){
		$this->workbook->close();
		
		$content = file_get_contents ($this->fname);
		$handle = fopen ("$file_path", "w+");
		file_put_contents ("$file_path", $content);
		
		header("Content-Type: application/x-msexcel; name=\"$file_name\"");
		header("Content-Disposition: inline; filename=\"$file_name\"");
		
		$fh=fopen($this->fname, "rb");
		fpassthru($fh);
		unlink($this->fname);
		
	}
	
	
	
}

?>