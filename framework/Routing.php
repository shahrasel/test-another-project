<?php
class Routing
{   
		var $perform_page;
		var $perform_data;
			
    public function parseUrl($url) {
			//echo $url;
			//$url_array = explode('/', $url);
			//print_r ($url_array);
			
			$xml = io_file_get_contents(getConfigValue('config')."Routing.xml");
			$match_offset = 0;
			$count = 0;
			$match = 0;
			
			$match_pattern_key = '';
			$match_pattern_val = '';
			
			while(1){
				//echo count($url_array);
				if ( count($url_array) >= 2 || $count == 0 ){
					$url_array = xml_get_value('url', $xml, $match_offset);
					
					$exp_array = xml_get_value('exp', $xml, $url_array['offset']);	
					$perform_array = xml_get_value('perform', $xml, $url_array['offset']);	
					
					//print_r ($exp_array);
					if ( preg_match("@^{$exp_array['value']}@i", $url) ){
						$match = 1;
						$match_pattern_val = $perform_array['value'];
						break;
						//$match_pattern_key = $exp_array['value'];
					}
										
				}
				else
					break;
				$match_offset = $url_array['offset'];
				$count++;
			}
			
			//echo $url;			
			
			$tmp_key_array = explode('/', $match_pattern_val);
			$tmp_val_array = explode('/', $url);
			
			//print_r ($tmp_key_array);
			//print_r ($tmp_val_array);
			
			$this->perform_page = $tmp_key_array[0];
			if ($this->perform_page == 'cms_page')
				$this->perform_page = basename($tmp_val_array[0], getConfigValue('page_suffix')); 
			
			$gpc = ini_get("magic_quotes_gpc");
			
			for ($i=1; $i < count($tmp_val_array); $i++){
				//echo $tmp_val_array[$i].'__';
				if ($i == (count($tmp_val_array)-1) ){
					//print_r ($this->perform_data);
					//exit();
					if ($gpc == 1)
						$this->perform_data[$tmp_key_array[$i]] = stripslashes( basename($tmp_val_array[$i], getConfigValue('page_suffix')) );		
					else
						$this->perform_data[$tmp_key_array[$i]] = basename($tmp_val_array[$i], getConfigValue('page_suffix'));			 
				}
				else{
					//echo $tmp_val_array[1];
					if ($gpc == 1)
						$this->perform_data[$tmp_key_array[$i]] = stripslashes($tmp_val_array[$i]);
					else
						$this->perform_data[$tmp_key_array[$i]] = $tmp_val_array[$i];
				}
			}
			
			//print_r ($this->perform_data);	
	  }
					
}
?>