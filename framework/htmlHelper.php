<?php
class htmlHelper{	
	
	var $is_tinymce_init = 0;
	var $is_fck_init = 0;
	var $is_ck_init = 0;
	
	function initCK(){
		if ($this->is_ck_init == 0){
			setJsFile ('lib/ckeditor/ckeditor.js');
			setJsFile ('lib/ckeditor/ckfinder/ckfinder.js');
			$this->is_ck_init = 1;
		}
	}
	
	function initTinyMCE(){
		//echo '12';
		if ($this->is_tinymce_init == 0){
			setJsFile ('lib/tinymce/jscripts/tiny_mce/tiny_mce.js');
			$this->is_tinymce_init = 1;
		}
	}
	
	function initInputForTinyMCE($target_id){
		//$target = 'short';
		if ($this->is_tinymce_init == 1){
			setJsFile ('lib/tinymce/jscripts/tiny_mce/tiny_mce_init.js');
			$this->is_tinymce_init = 2;
		}
		
		echo "<script>setIdForTinyMCE('$target_id')</script>";
		
	}
	
	function initFCK(){
		require_once (getConfigValue('lib').'fckeditor/fckeditor.php');
		//echo '12';
		//setJsFile ('lib/ckeditor/ckeditor.js');
	}
	function initInputForFCK($input, $value, $width='', $height=''){
		$oFCKeditor = new FCKeditor($input) ;
		$oFCKeditor->BasePath	= getConfigValue('fckeditor');
		$oFCKeditor->Value = $value;
		
		if ( !empty($width) )
			$oFCKeditor->Width = $width;
		if ( !empty($height) )
			$oFCKeditor->Height = $height;
		
		return $oFCKeditor->CreateHtml() ;
	}
	
	function displayFCK($target_id){
		//$target = 'short';
		//setJsFile ('lib/ckeditor/ckeditor_init.js');
				
	}
	
	function renderTable( $data ){
		if( isset($data) && !empty($data) ){
			$tableData = '';
			$tableData = '<table cellspacing="0" cellpadding="0" border="1">';
			foreach( $data as $key=>$value ){
				if( $key==0 ){
					$tableData= $tableData . '<tbody><tr>';
					foreach( $value as $key1=>$value1){
						$tableData= $tableData . '<th>'.$value1.'</th>';
					}
					$tableData= $tableData . '</tr>';
				}
				else{
					$tableData= $tableData . '<tr>';
					foreach( $value as $key1=>$value1){
						$tableData= $tableData . '<td>'.$value1.'</td>';
					}
					$tableData= $tableData . '</tr>';
				}
			}
			$tableData= $tableData . '</tbody></table>';
			return $tableData;
		}
	}

}

?>