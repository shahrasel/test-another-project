<?php 

require_once ('../framework/common.php');
require_once ('ClassSQLimporter.php');

function getPath($target_path){
	
	
	if (strpos($target_path, '/') === FALSE)
	{
		if (function_exists('realpath') AND @realpath(dirname(__FILE__)) !== FALSE)
		{
			$target_path = realpath(dirname(__FILE__)).'/'.$target_path;
		}
	}
	else
	{
		// Swap directory separators to Unix style for consistency
		$target_path = str_replace("\\", "/", $target_path); 
	}
	$target_path .= '/';
	
	return $target_path;
}


$replace = array('<', '>', '*', '\'', '"');
$site_base = str_replace($replace, '', dirname(env('PHP_SELF')));
if ( strstr ($site_base, 'install') ){
	$webroot = rtrim ($site_base, 'install');
}
else{
	$webroot .= '/';
}

//$file_path = getPath('config').'config.php';
$file_path = '../config/config.php';
//echo $webroot;
$writable_array = array('../config','../cache', '../media', '../userfiles');
//echo is_writable ($cache);

$submit_tag = 0;
foreach ($writable_array as $writable):
	//echo $writable;
	if ( !is_writable ($writable) ):
			$submit_tag = 1;
		break;
	endif;
endforeach;
			
if ($_POST['save'] == 'Install' && $submit_tag == 0){
		
		//print_r ($_POST);
		
		$required_array = array('site_name', 'page_suffix', 'auth_key', 
													 'auth_value', 'db_driver', 'db_host', 'db_database', 'db_user', 
													 'db_password', 'save');
		
		$storage_value = array('site_name', 'page_suffix', 'auth_key', 
													 'auth_value', 'db_prefix', 'save');
		$validation_tag = 0;
		foreach ($required_array as $required):
			if ( empty($_POST[$required]) ):
				$validation_tag = 1;
				break;
			endif;
		endforeach;
		
		
				
		if ($validation_tag == 0){
				
				$link = @mysql_connect($_POST['db_host'], $_POST['db_user'], $_POST['db_password']);
				if (!$link) {
						$_err .= 'Could not connect.<br/>';
				}
				
				if (!@mysql_select_db($_POST['db_database'], $link)) {
   				 $_err .= 'Could not select database.<br/>';
					
				}
				
				$sqlFile = "cncms.sql";
				$newImport = new sqlImport ($_POST['db_host'], $_POST['db_user'], $_POST['db_password'], $sqlFile);
				$newImport->import();
				$import = $newImport -> ShowErr ();
				if ($import[0] == true)
				{
					//echo "Congratulations! The file: $sqlFile has been imported successfully";
													
				} 
				else {
					$_err .= "Ops! The file: $sqlFile had errors during the importing.<br>";
					foreach($import[1] as $index => $value){
						$_err .= $import [1][$index].": ".$import [2][$index].".<br>";
					}
				}
									
				//echo '12';
				//SHOW TABLE STATUS FROM 
				//RENAME TABLE cncms.User  TO cncms.cn_User ;

				if (empty($_err)){
					foreach ($_POST as $key=>$value ){
		
						if ($key != 'request_url' && $key != 'submit' && in_array ($key, $storage_value) && empty ($_err) ){
							
							//$is_exist = $this->numOfRecod ("select * from Config where cnfig_key='$key'");
							$sql = "select * from Config where cnfig_key='$key'";
							$result = @mysql_query($sql, $link);
							$is_exist = @mysql_num_rows($result);
		
							if ($is_exist == 1){
								//$this->executeQuery ('Update config set cnfig_value='."'$value' where cnfig_key='$key'");
								$sql = 'Update config set cnfig_value='."'$value' where cnfig_key='$key'";
							}
							else{
								//$this->executeQuery ("Insert into config (cnfig_key,cnfig_value) values ('$key', '$value')");
								$sql = "Insert into config (cnfig_key,cnfig_value) values ('$key', '$value')";
							}
							
							
							$result = @mysql_query($sql, $link);
							
							if ( !$result )
								$_err .= 'Error on Insert/Update Config Value For '.$key.'.<br/>';
									
						}// if
						
					}// foreach
				}//if
				
				//mysql_free_result($result);
				
				
				if ($_POST['db_prefix'] && empty ($_err)){
					
					$sql = "SHOW TABLE STATUS FROM ".$_POST['db_database'] ;
					$result = @mysql_query($sql, $link);
					
					while ($row = @mysql_fetch_assoc($result)) {
						$sql = 'RENAME TABLE '.$_POST['db_database'].'.'.$row['Name'].' TO '.$_POST['db_database'].'.'.$_POST['db_prefix'].$row['Name'];
						$sql_status = @mysql_query($sql, $link);
						
						if (!$sql_status)
							$_err .= 'Error on adding prefix before table '.$row['Name'].'.<br/>';
					}
				
				}
				
				@mysql_close($link);
					
				//writing config file
				
				/*
				if (empty ($_err)){
					
					$fp = fopen ($file_path, 'w+');
					$data = '<?php 
									$db_driver = "'.$_POST['db_driver'].'"; 
									$db_host = "'.$_POST['db_host'].'";
									$db_user = "'.$_POST['db_user'].'";
									$db_password = "'.$_POST['db_password'].'";
									$db_database = "'.$_POST['db_database'].'";
									$db_table_prefix = "'.$_POST['table_prefix'].'";
									?>';
									
					$write_status = file_put_contents($file_path, $data);
					
					if ( !$write_status )
						$_err .= "Cofig file initilizing error.<br/>";
				}
				*/
				//$file_path = $webroot.'config/'
		}
		else{
			//$this->protocols['Content_Instance']->setValue('_err', "Please Fill all The Field");	
				$_err .= "Please fill all The required field";
		}
		
		
		
}
		
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CN Admin Pannel</title>
<script language="javascript" type="text/javascript" src="<?php echo $webroot;?>js/jquery-1.3.1.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $webroot;?>js/hoverIntent.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $webroot;?>js/superfish.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $webroot;?>js/menu_help.js"></script>
		

<link type="text/css" rel="stylesheet" href="<?php echo $webroot;?>corecss/cms_core.css"/>
<link type="text/css" rel="stylesheet" href="<?php echo $webroot;?>corecss/superfish.css"/>
<script src="<?php echo $webroot;?>js/common.js" type="text/javascript" language="javascript"/></script>

		
</head>

<body>

<div align="center">
<div id="wrap">
<div id="wrap3">

	<div id="top">
		<div id="logo">
			<h1>CN-Cms			</h1>
		</div>

		</div>
	

<br clear="all" />

<div class="menu">
	 <ul class="sf-menu">
					
	 </ul>
</div>	
	<!-- Navigation End-->
	
	<div class="wrap_main_body">
		<!-- submenu  submenu-->
		  
 <div id="brows_clients">
			
		<!--<div id="search">
			<h1><a href="#">Search</a></h1>
		</div>-->
	</div>	
		<!-- submenu bar end-->

		
		<!-- Main Body start-->
			
	<form action="" method="post" >
		<?php //print_r ($table_data);?>
		<div id="main_body">	
			<div class="list_bg">
				<div class="list">
					<h1>Installing CN-Cms</h1>
				</div>
				<div class="list2" >
					<div style="float:right; padding-top:6px; font-size:12px; padding-right:10px;">
						
					</div>
					
				</div>
			</div>
			<div class="main_body2">
			<div class="main_body2a">
				<h1>CMS Config Information</h1>
			</div>
			<div class="main_body2b">	
				<div align="center">
					<?php 
						if ( empty($_err) && $_POST['save'] == 'Install' ){
							echo 'CN-CMS Installed Successfully.   '. ' <a href="../admin">Admin Panel</a> ';
						}
						
						echo $_err; 
						//echo 'CN-CMS Installed Successfully.   '. ' <a href="../admin">Admin Panel</a> ';
					?>
				</div>
				
				<div align="main_body2a">	
					<table width="919" border="0" cellpadding="0" cellspacing="0"  >
						<tr class="title">
							<td width="140px" style="padding-right:14px; text-align:right;" class="font4">Folder Name</td>
							<td width="256px" class="font4">Folder Permission</td>
						</tr>		
										
					<?php 
					$tmp_count = 0;
					foreach ($writable_array as $writable):
						//echo $writable;
						$file_paths = pathinfo($writable);
						if ( is_writable ($writable) ):
					?>
							<tr class="tr<?php echo ($tmp_count%3)?>" >
								<td width="140px" style="padding-right:14px; text-align:right;" class="font4"><?php echo $file_paths['filename']; ?></td>
								<td width="256px" class="font4"><span style="color:#00FF00">Ok</span></td>
							</tr>
					<?php	
						else:
					?>
							<tr class="tr<?php echo ($tmp_count%3)?>">
								<td width="140px" style="padding-right:14px; text-align:right;" class="font4"><?php echo $file_paths['filename']; ?></td>
								<td width="256px" class="font4"><span style="color:#FF0000">Change Folder Mod at 0777</span></td>
							</tr>
					<?php		
						endif;
						$tmp_count++;
					endforeach;
					?>
					</table>
				</div>
				<br/>
					
				<table width="919" border="0" cellpadding="0" cellspacing="0"  >
						
					<tr>
						<td width="140px" style="padding-right:14px; text-align:right;" class="font4">Site Name:</td>
						<td width="256px"><input type="text" value="" name='site_name' id="site_name" class="input_box"/></td>
					</tr>
					
					<tr>
						<td width="140px" style="padding-right:14px; text-align:right;" class="font4">Page Suffix:</td>
						<td width="256px"><input type="text" value="" name='page_suffix' id="page_suffix" class="input_box"/></td>
					</tr>
					
					<!--<tr>
						<td width="140px" style="padding-right:14px; text-align:right;" class="font4">Template Suffix:</td>
						<td width="256px"><input type="text" value="" name='template_suffix' id="template_suffix" class="input_box"/></td>
					</tr>-->
					
					<tr>
						<td width="140px" style="padding-right:14px; text-align:right;" class="font4">Auth Key:</td>
						<td width="256px"><input type="text" value="" name='auth_key' id="auth_key" class="input_box"/></td>
					</tr>
					
					<tr>
						<td width="140px" style="padding-right:14px; text-align:right;" class="font4">Auth Value:</td>
						<td width="256px"><input type="text" value="" name='auth_value' id="auth_value" class="input_box"/></td>
					</tr>
					
					<tr>
						<td width="140px" style="padding-right:14px; text-align:right;" class="font4">Driver:</td>
						<td width="256px"><input type="text" value="" name='db_driver' id="db_driver" class="input_box"/></td>
					</tr>
					
					<tr>
						<td width="140px" style="padding-right:14px; text-align:right;" class="font4">Database Host:</td>
						<td width="256px"><input type="text" value="" name='db_host' id="db_host" class="input_box"/></td>
					</tr>
					
					<tr>
						<td width="140px" style="padding-right:14px; text-align:right;" class="font4">Database:</td>
						<td width="256px"><input type="text" value="" name='db_database' id="db_database" class="input_box"/></td>
					</tr>
					
					<tr>
						<td width="140px" style="padding-right:14px; text-align:right;" class="font4">Table Prefix:</td>
						<td width="256px"><input type="text" value="" name='table_prefix' id="table_prefix" class="input_box"/></td>
					</tr>
					
					
					<tr>
						<td width="140px" style="padding-right:14px; text-align:right;" class="font4">Database User:</td>
						<td width="256px"><input type="text" value="" name='db_user' id="db_user" class="input_box"/></td>
					</tr>
					
					<tr>
						<td width="140px" style="padding-right:14px; text-align:right;" class="font4">Database Password:</td>
						<td width="256px"><input type="text" value="" name='db_password' id="db_password" class="input_box"/></td>
					</tr>
					
					
					
				</table>
				<div class="main_body2b" style="padding-bottom:0px; text-align:right;"> <input type="submit" value="Install" name="save" id="save" <?php echo ($submit_tag != 0?'disabled':'')?> /> </div>
				
				</div>
			</div>
			<div class="main_body3"><img src="/cms/coreimages/cn_main_body_bottom.gif" alt="" /></div>
		</div>			
	</form>
				<!-- Main Body end-->
	</div>
	<!-- page bottom start-->	
	<div id="bottom">

		<div class="font" align="right">Cybernetikz 2.2.3 <br />Licensed to Cybernetikz</div>
	</div>
	<!-- page bottom end-->	
	
		
</div>
</div>
</div>

</body>
</html>
