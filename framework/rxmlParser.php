<?php
	/**
 * Returns the data between to XML tags.
 *
 * @param	string	$tag
 * @param	string	$xml
 * @return	boolean
 */
function xml_get_all_value ($tag, $xml, $match_offset){
	
	$result_array = array();
	$match_offset = 0;
	
	while (1){
	
		$tmp_result = xml_get_value($tag, $xml, $match_offset);
		//print_r ($tmp_result);
		if ( !empty ($tmp_result['offset']) ){
			$result_array[] = $tmp_result['value'];
			$match_offset = $tmp_result['offset'];
		}
		else{
			break;
		}
		
	}
	//print_r ($result_array);
	return $result_array;
	
}
 
function xml_get_value($tag, $xml, $match_offset) {
	//echo '#<'.$tag.'>(.*)<\/'.$tag.'>#sU';
	
	if(preg_match('#<'.$tag.'>(.*)<\/'.$tag.'>#sU', $xml, $matches, PREG_OFFSET_CAPTURE, (int)$match_offset)) {
		
		$result = array();
		$result['value'] = trim($matches[1][0]);
		$result['offset'] = $matches[1][1];
		
		//print_r ($result);
		
		return $result;
	} else {
		return '';
	}

}

function xml_get_value_v2($tag, $xml, $match_offset) {
	//echo '#<'.$tag.'>(.*)<\/'.$tag.'>#sU';
	
	if(preg_match('#<'.$tag.'.*>(.*)<\/'.$tag.'>#sU', $xml, $matches, PREG_OFFSET_CAPTURE, (int)$match_offset)) {
		
		$result = array();
		$result['value'] = trim($matches[1][0]);
		$result['offset'] = $matches[1][1];
		
		//print_r ($result);
		
		return $result;
	} else {
		return '';
	}

}

function xml_get_value_v3($tag, $xml, $match_offset) {
	//echo '#<'.$tag.'>(.*)<\/'.$tag.'>#sU';
	
	if(preg_match('#<'.$tag.'(.*)\/>#sU', $xml, $matches, PREG_OFFSET_CAPTURE, (int)$match_offset)) {
		
		$result = array();
		$result['value'] = trim($matches[1][0]);
		$result['offset'] = $matches[1][1];
		
		//print_r ($result);
		
		return $result;
	} else {
		return '';
	}

}


function reset_value(){
	$value = array ("offset"=>"", "value"=>"");
	return $value;
}

function io_file_get_contents($filename) {

	if(function_exists('file_get_contents')) {
		$content = file_get_contents($filename);
	} else {
		$content = '';

		$fp = fopen($filename, 'r');
		while (!feof($fp)) {
			$content .= fgets($fp, 8192);
		}
		fclose ($fp);
	}
	
	return $content;

}
?>