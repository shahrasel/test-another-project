<?php
//print_r ($_FILES);
require_once ('../pre_index.php');

$file_tmp_name = $_FILES['upload']['tmp_name'];
$uploadfile =getConfigValue('ckfiles').$_FILES['upload']['name'];

$ok = @move_uploaded_file($file_tmp_name, $uploadfile);  
if ( !$ok ) echo 'Can not upload the file.'; 
else echo 'File uploaded successfully.'; 
				 
?>