<?php 
 class filemanager
 {
	
	var $_sql, $_err;
  // You must give input file path and either new width or new height 
  // You can also give both the new width and height,give new path to save new image
	//
	function imageResizeSave ($input_file_path = "", $new_width = 0, $new_height = 0, $save_path = "./",$desired = 1, $quality = 100, $imagelib='gd') 
	{ 
		
		$this->_err = "";
		
		if ($imagelib == 'gd'){
		
			$input_file_name = substr($input_file_path, strrpos($input_file_path, "/")+1); 
			$file_path = substr($input_file_path, 0, strrpos($input_file_path, "/")+1); 
			
			if ( $input_file_name == "" ) return false;
			if ( $input_file_path == "" ) return false;		
			// first, grab the dimensions of the pic 
			$imagedata = getimagesize("$input_file_path"); 
			$image_width = $imagedata[0]; 
			$image_height = $imagedata[1]; 
			$image_type = $imagedata[2]; 		// type definitions  1 = GIF, 2 = JPG, 3 = PNG, 4 = SWF, 5 = PSD, 6 = BMP  
																			//7 = TIFF(intel byte order), 8 = TIFF(motorola byte order) 9 = JPC, 10 = JP2, 11 = JPX 
			
			if ( !isset($image_width) || !isset($image_height) )
			{
				$this->_err .= "Given file does not exist<br>";
				return false;
			}
			
			if ( ($new_width == 0 || $new_width == "") && ($new_height == 0 || $new_height == "") )
			{
				$this->_err .= "Both the new width and height are zero<br>";
				return false;
			}
	
			$shrinkage = 1; 		
			if ( $new_width == 0 || $new_width == "" )
			{
				$shrinkage = $new_height/$image_height; 
				$new_width = $shrinkage * $image_width; 
				if ( $new_width <= 1 ) $this->_err .= "Thumbnail width is less than zero<br>";
			}
			elseif ( $new_height == 0 || $new_height == "" )
			{
				$shrinkage = $new_width/$image_width; 
				$new_height = $shrinkage * $image_height; 
				if ( $new_height < 1 ) $this->_err .= "Thumbnail height is less than zero<br>";
			}
			elseif ( $desired == 1)
			{
				 $desired_width = $new_width;
				 $desired_height = $new_height;
				 $perc_size = ($image_width >= $image_height) ? ($desired_width * 100 / $image_width) :
								 ($desired_height * 100 / $image_height);
								
				 $new_width = round($image_width * $perc_size / 100);
				 $new_height = round($image_height * $perc_size / 100);
				 if ($new_width > $desired_width || $new_height > $desired_height )
				 {
					$perc_size = ($new_width > $desired_width) ? ($desired_width * 100 / $new_width) : ($desired_height * 100 / $new_height);
					$new_width = round($new_width * $perc_size / 100);
					$new_height = round($new_height * $perc_size / 100);
				 } 
			
			
			}
		  switch($image_type)
		  {
				case 3: $src_img = imagecreatefrompng($input_file_path); break;
				case 2: $src_img = imagecreatefromjpeg($input_file_path); break;
				case 1: $src_img = imagecreatefromgif($input_file_path); break;
				default: return false;
			 }
	
			 $dst_img = imagecreatetruecolor($new_width, $new_height); 
			 imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height); 
			
			switch($image_type)
			{
				case 1:
				{
	
					if (function_exists('imagegif'))
					{
						imagegif($dst_img,$save_path);break;
					}
				}
				case 3: imagepng($dst_img, $save_path);break;
				case 2: imagejpeg($dst_img, $save_path, $quality);
						break;
			}
			imagedestroy($src_img); 
			imagedestroy($dst_img); 
			return $save_path;
		}
		
		else if ($imagelib == 'imageagic'){
			$output = array();
			exec("convert ".$input_file_path." -resize $new_widthx$new_height  -coalesce ".$save_path, $output, $return_var);
			
			//$output ouput null means ok
			
		}
		 
	}
	
	function upload($file_data = "", $upload_path = "./" )
  {  
				
		//echo $file_data;
		if ( !$file_data ) { $this->_err = 'Field name of the file is not given'; return false; }
		//print_r ($file_data);
		//echo $upload_path;
				
		$file_name = $file_data["name"];
		//$file_type = substr($file_data["type"], 1+strrpos ($file_data["type"], ".") );
		$file_type = $file_data["type"];
		$file_size = $file_data["size"];
		$file_tmp_name = $file_data["tmp_name"];
		
		if ( $file_name != '' || $file_size > 0 )
		{
			 if ( $file_name == '') $this->_err = 'File has no name<br>';
			 elseif( $file_size == 0) $this->_err = $file_name . ' file size is zero<br>';		
			 /*
			 elseif ( !is_array($file_ext) ) $this->_err =  $file_name . ' file extension array is not given<br>';
			 elseif ( !empty($file_ext) )
			 { 
				$type_match = "";
				$type = "";
				foreach($file_ext as $k => $ext)
				{
					if ( $k > 0 ) $type .= ", ";
					//echo $ext.'--'.$file_type;
					if( eregi($ext, $file_type) ) $type_match = "matched";	
					$type .= $ext;
				}
				if ( empty($type_match) ) $this->_err = "Valid file type - " . $type . " <br>";
			 }
			 */
			 //echo $type_match;	 
			 if ( $this->_err == '' )
			 {
				 $uploadfile = $upload_path . $file_name; 
				 $file = $file_size;
	
				 $ok = @move_uploaded_file($file_tmp_name, $uploadfile);  
				 if ( !$ok ) $MyLoad->_err = 'Can not upload the file.<br>'; 
			}
		} 
		else
		{
			$this->_err .= 'Please select upload file and try again.<br>';
		}
 	 	
		echo $this->_err;
		return $this->_err;
 	}
 
 // First parameter is the field name of the file type. It must be given.
 // Second parameter is the optional upload path. If upload path is not given then file is uploaded in the same directory
 // Third parameter is optional file extension array.
 //
 	function uploadRename($file_data = "", $upload_path = "./",$rename = "")
 	{  
	      
		if ( $file_data == "" ) { $this->_err = 'Field name of the file is not given'; return false; }
		
		//print_r ($file_data);		
		$file_name = $file_data["name"];
		//$file_type = substr($file_data["type"], 1+strrpos ($file_data["type"], ".") );
		$file_type = $file_data["type"];
		$file_size = $file_data["size"];
		$file_tmp_name = $file_data["tmp_name"];
			
		if ( $file_name != '' || $file_size > 0 )
		{
			 if ( $file_name == '') $this->_err = 'File has no name<br>';
			 elseif( $file_size == 0) $this->_err = $file_name . ' file size is zero<br>';		
			 /*	
			 elseif ( !is_array($file_ext) ) $this->_err =  $file_name . ' file extension array is not given<br>';
			 elseif ( !empty($file_ext) )
			 { 
				$type_match = "";
				$type = "";
				foreach($file_ext as $k => $ext)
				{
					if ( $k > 0 ) $type .= ", ";
					if( eregi($ext, $file_type) ) $type_match = "matched";	
					$type .= $ext;
				}
				if ( empty($type_match) ) $MyLoad->_err = "Valid file type - " . $type . " <br>";
			 }
			*/	 
			 if ( $this->_err == '' )
			 {
				 $uploadfile = $upload_path . $rename; 
				 $file = $file_size;
	
				 $ok = @move_uploaded_file($file_tmp_name, $uploadfile);  
				 if ( !$ok ) $this->_err = 'Can not upload the file.<br>'; 
			}
		} 
		else
		{
			//$MyLoad->_err = 'Please select upload file and try again.<br>';
		}
 	 
 	}
 
	function downloadV2 ($file_name, $basedir){
	
		$file_path = $basedir . $file_name;
		$len = filesize($file_path);
	
		//First, see if the file exists
		if (!is_file($file_path)) { $this->_err .= "File not found!<br>"; }
		
		$file_extension = strtolower(substr(strrchr($file_name,"."),1));
		//This will set the Content-Type to the appropriate setting for the file
		switch( $file_extension )
		{
			 case "pdf": $ctype="application/pdf"; break;
			 case "exe": $ctype="application/octet-stream"; break;
			 case "zip": $ctype="application/zip"; break;
			 case "doc": $ctype="application/msword"; break;
			 case "xls": $ctype="application/x-msexcel"; break;
			 case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
			 case "gif": $ctype="application/gif"; break;
			 case "png": $ctype="application/png"; break;
			 case "jpeg":
			 case "jpg": $ctype="application/jpg"; break;
			 case "mp3": $ctype="audio/mpeg"; break;
			 case "wav": $ctype="audio/x-wav"; break;
			 case "mpeg":
			 case "mpg":
			 case "mpe": $ctype="video/mpeg"; break;
			 case "mov": $ctype="video/quicktime"; break;
			 case "avi": $ctype="video/x-msvideo"; break;
		
			 //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
			 case "php":
			 case "htm":
			 case "html":
			 case "txt": die("<b>Cannot be used for ". $file_extension ." files!</b>"); break;
		
			 default: $ctype="application/force-download";
		}
		//echo $ctype;
		//exit();
			
		header("Content-Type: $ctype; name=\"$file_name\"");
		header("Content-Disposition: inline; filename=\"$file_name\"");
		$fh=fopen($file_path, "rb");
		fpassthru($fh);
		//unlink($file_path.$fname);
		
	}

 	function download($file_name, $basedir)
 	{
   	
		 //Gather relevent info about file
		 //$basedir = "/Apache2/htdocs/cn/upload/"; 
		 $file_path = $basedir . $file_name;
		 $len = filesize($file_path);
	
		 //First, see if the file exists
		 if (!is_file($file_path)) { $this->_err .= "File not found!<br>"; }
	
	
		 $file_extension = strtolower(substr(strrchr($file_name,"."),1));
	
		 //This will set the Content-Type to the appropriate setting for the file
		 switch( $file_extension )
		 {
		 case "pdf": $ctype="application/pdf"; break;
		 case "exe": $ctype="application/octet-stream"; break;
		 case "zip": $ctype="application/zip"; break;
		 case "doc": $ctype="application/msword"; break;
		 case "xls": $ctype="application/vnd.ms-excel"; break;
		 case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
		 case "gif": $ctype="image/gif"; break;
		 case "png": $ctype="image/png"; break;
		 case "jpeg":
		 case "jpg": $ctype="image/jpg"; break;
		 case "mp3": $ctype="audio/mpeg"; break;
		 case "wav": $ctype="audio/x-wav"; break;
		 case "mpeg":
		 case "mpg":
		 case "mpe": $ctype="video/mpeg"; break;
		 case "mov": $ctype="video/quicktime"; break;
		 case "avi": $ctype="video/x-msvideo"; break;
	
		 //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
		 case "php":
		 case "htm":
		 case "html":
		 case "txt": die("<b>Cannot be used for ". $file_extension ." files!</b>"); break;
	
		 default: $ctype="application/force-download";
		 }
		
		 //Begin writing headers
		 header("Pragma: public");
		 header("Expires: 0");
		 header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		 header("Cache-Control: public"); 
		 header("Content-Description: File Transfer");
		 
		//Use the switch-generated Content-Type
		 header("Content-Type: $ctype");
	
		 //Force the download
		 $header="Content-Disposition: attachment; filename=".$file_name."";
		 header($header );
		 header("Content-Transfer-Encoding: binary");
		 header("Content-Length: ".$len);
		 @readfile($file_path);
		 exit;
		 
	 }
 
	
 }
?>
