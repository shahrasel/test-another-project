<?php  
	
/*
	*
	** Start encoding
	*
*/

	function UTF8entities($content="") { 
		
		if( trim($content) == "" or empty($content) ){
			return $content;
		}
		$content = htmlspecialchars( $content ) ; 
		//if($content=="cname1  & asdsa"){echo $content;exit();}
		$contents = unicode_string_to_array($content);
		$swap = "";
		$iCount = count($contents);
		for ($o=0;$o<$iCount;$o++) {
			$contents[$o] = unicode_entity_replace($contents[$o]);
			$swap .= $contents[$o];
		}
		return mb_convert_encoding($swap,"UTF-8"); 
	}

	function unicode_string_to_array( $string ) { 
		$strlen = mb_strlen ($string);
		while ($strlen) {
			$array[] = mb_substr( $string, 0, 1, "UTF-8" );
			$string = mb_substr( $string, 1, $strlen, "UTF-8" );
			$strlen = mb_strlen( $string );
		}
		return $array;
	}

	function unicode_entity_replace($c) { 
		$h = ord($c{0});    
		if ($h <= 0x7F) { 
			return $c;
		} else if ($h < 0xC2) { 
			return $c;
		}
		
		if ($h <= 0xDF) {
			$h = ($h & 0x1F) << 6 | (ord($c{1}) & 0x3F);
			$h = "&#" . $h . ";";
			return $h; 
		} else if ($h <= 0xEF) {
			$h = ($h & 0x0F) << 12 | (ord($c{1}) & 0x3F) << 6 | (ord($c{2}) & 0x3F);
			$h = "&#" . $h . ";";
			return $h;
		} else if ($h <= 0xF4) {
			$h = ($h & 0x0F) << 18 | (ord($c{1}) & 0x3F) << 12 | (ord($c{2}) & 0x3F) << 6 | (ord($c{3}) & 0x3F);
			$h = "&#" . $h . ";";
			return $h;
		}
	}
/*
	*
	** End of encoding
	*
*/

	$datax = '<?xml version="1.0" encoding="UTF-8"?>
	<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
	<plist version="1.0">
	<dict>';

	$datax .= '
		<key>category</key>
		<array>';
		
		if(count($category_lists)>0) {
			foreach($category_lists as $row) {
					
					$datax .= '
					<dict>
						<key>id</key>
						<string>'.UTF8entities(utf8_encode($row['id'])).'</string>
						<key>title</key>
						<string>'.UTF8entities(utf8_encode($row['title'])).'</string>
						<key>image</key>
						<string>'.UTF8entities(utf8_encode($row['categoryfile'])).'</string>
					</dict>';	
			}	
		}
		$datax .= '
			</array>';
		
		$datax .= '
		<key>items</key>';
		
		$datax .= '
			<array>';
		
		if(count($item_lists)>0) {
			foreach($item_lists as $row) {
					//echo "SELECT title FROM ".getConfigValue('table_prefix')."category where active = 'Active' and id =". $row['category']; exit;
					$result = mysql_query("SELECT * FROM ".getConfigValue('table_prefix')."category where active = 'Active' and id =". $row['category']);
					$cat = mysql_fetch_row($result);
					//echo $cat[1]; exit;
					$datax .= '
					<dict>
						<key>id</key>
						<string>'.UTF8entities(utf8_encode($row['id'])).'</string>
						<key>category</key>
						<string>'.UTF8entities(utf8_encode($cat[1])).'</string>
						<key>name</key>
						<string>'.UTF8entities(utf8_encode($row['item_name'])).'</string>
						<key>description</key>
						<string>'.UTF8entities(utf8_encode($row['description'])).'</string>
						<key>address</key>
						<string>'.UTF8entities(utf8_encode($row['address'])).'</string>
						<key>distance</key>
						<string>'.UTF8entities(utf8_encode($row['addimage_large1file'])).'</string>
						<key>image</key>
						<string>'.UTF8entities(utf8_encode($row['itemfile'])).'</string>
						<key>phone</key>
						<string>'.UTF8entities(utf8_encode($row['phone_no'])).'</string>
						<key>email</key>
						<string>'.UTF8entities(utf8_encode($row['email'])).'</string>
						<key>website</key>
						<string>'.UTF8entities(utf8_encode($row['web_address'])).'</string>
						<key>lattitude</key>
						<string>'.UTF8entities(utf8_encode($row['latitude'])).'</string>
						<key>longitude</key>
						<string>'.UTF8entities(utf8_encode($row['longitude'])).'</string>
						<key>search_tag</key>
						<string>'.UTF8entities(utf8_encode($row['tag'])).'</string>
					</dict>';
			}	
		}
		
		$datax .= '
			</array>';
		
		
		$datax .= '<key>Aboutus</key>
				   <string>Lorem ipsum dolor sit er elit lamet, consectetaur cillium adipisicing pecu, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Nam liber te conscient to factor tum poen legum odioque civiuda. Lorem ipsum dolor sit er elit lamet, consectetaur cillium adipisicing pecu, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Nam liber te conscient to factor tum poen legum odioque civiuda.</string>';
	
	$datax .='
	</dict>
</plist>';

			
	echo $datax; exit;

?>
