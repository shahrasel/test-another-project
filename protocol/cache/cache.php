<?php
	
class cache{	
	
	var $xml_object;
	//var $fpath;
	
	function cache(){
		//require_once (getConfigValue('lib').'xmlwriter/xmlwriterclass.php');
		//$this->xml_object = &new xml_writer_class;
		//print_r ($this->xml_object);
		//echo '12';
	}
	
	function clearCache (){
		 
		 $handle = opendir(getConfigValue('cache'))	
		 while (false !== ($file = readdir($handle))) {
        echo "$file\n";
    }
		
	}
	
	function makeCache (){
	}
			
}

?>