<?php
	
class xmlmanager{	
	
	var $xml_object;
	//var $fpath;
	
	function xmlmanager(){
		require_once (getConfigValue('lib').'xmlwriter/xmlwriterclass.php');
		$this->xml_object = &new xml_writer_class;
		//print_r ($this->xml_object);
		//echo '12';
	}
	
	function initXml (){
		
	}
	
	function saveXml ($file_path, $content){
	
		$handle = fopen ("$file_path", "w+");
		file_put_contents ("$file_path", $content);
		fclose ($handle);		
	}	
	
	function downloadXml ($file_path, $file_name){
		
		header("Content-Type: text/xml; name=\"$file_name\"");
		header("Content-Disposition: inline; filename=\"$file_name\"");
		
		$fh = fopen ($file_path, "rb");
		fpassthru ($fh);
		unlink($file_path);
			
	}
	
	function saveAndDownloadXml ($file_path, $file_name, $content){
			
		$handle = fopen ("$file_path", "w+");
		file_put_contents ("$file_path", $content);
		
		header("Content-Type: application; name=\"$file_name\"");
		header("Content-Disposition: inline; filename=\"$file_name\"");
		
		$fh=fopen($file_path, "rb");
		fpassthru($fh);
		//unlink($file_path);
		
	}
		
}

?>