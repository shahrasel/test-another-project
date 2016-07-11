<?php 

?>

<form action="<?php echo urlForAdmin('modulemanager/config')?>" method="post" >
		
	<div id="main_body">
	
		<div class="new_clients_bg">
			<h1>NEW NEWS</h1>
		</div>
		<div class="main_body2">
		  <div class="main_body2a">
				<h1>News Information</h1>
			</div>
			<div class="main_body2b">
				
				<table width="445" border="0" cellpadding="0" cellspacing="0">
					
					<tr>
						<td width="140px" style="padding-right:14px; text-align:right;" class="font4">Site Name:</td>
						<td width="256px"><input type="text" id="site_name" name="site_name" value="<?php echo $config_data[site_name]?>" /></td>
					</tr>
					
					<tr>
						<td width="140px" style="padding-right:14px; text-align:right;" class="font4">Base Url:</td>
						<td width="256px"><input type="text" id="base_url" name="base_url" value="<?php echo $config_data[base_url]?>" /></td>
					</tr>
					<tr>
						<td width="140px" style="padding-right:14px; text-align:right;" class="font4">Base Admin Url:</td>
						<td width="256px"><input type="text" id="base_admin_url" name="base_admin_url" value="<?php echo $config_data[base_admin_url]?>" /></td>
					</tr>
					<tr>
						<td width="140px" style="padding-right:14px; text-align:right;" class="font4">Media Url:</td>
						<td width="256px"><input type="text" id="media_url" name="media_url" value="<?php echo $config_data[media_url]?>" /></td>
					</tr>
					<tr>
						<td width="140px" style="padding-right:14px; text-align:right;" class="font4">Js:</td>
						<td width="256px"><input type="text" id="js" name="js" value="<?php echo $config_data[js]?>" /></td>
					</tr>
					<tr>
						<td width="140px" style="padding-right:14px; text-align:right;" class="font4">Css:</td>
						<td width="256px"><input type="text" id="css" name="css" value="<?php echo $config_data[css]?>" /></td>
					</tr>
					<tr>
						<td width="140px" style="padding-right:14px; text-align:right;" class="font4">Fckeditor:</td>
						<td width="256px"><input type="text" id="fckeditor" name="fckeditor" value="<?php echo $config_data[fckeditor]?>" /></td>
					</tr>
					<tr>
						<td width="140px" style="padding-right:14px; text-align:right;" class="font4">Page Suffix:</td>
						<td width="256px"><input type="text" id="page_suffix" name="page_suffix" value="<?php echo $config_data[page_suffix]?>" /></td>
					</tr>
					<tr>
						<td width="140px" style="padding-right:14px; text-align:right;" class="font4">Template Prefix:</td>
						<td width="256px"><input type="text" id="template_suffix" name="template_suffix" value="<?php echo $config_data[template_suffix]?>" /></td>
					</tr>
					<tr>
						<td width="140px" style="padding-right:14px; text-align:right;" class="font4">Invalid Page:</td>
						<td width="256px"><input type="text" id="invalid_page" name="invalid_page" value="<?php echo $config_data[invalid_page]?>" /></td>
					</tr>
					<tr>
						<td width="140px" style="padding-right:14px; text-align:right;" class="font4">Auth Key:</td>
						<td width="256px"><input type="text" id="auth_key" name="auth_key" value="<?php echo $config_data[auth_key]?>" /></td>
					</tr>
					<tr>
						<td width="140px" style="padding-right:14px; text-align:right;" class="font4">Auth Value:</td>
						<td width="256px"><input type="text" id="auth_value" name="auth_value" value="<?php echo $config_data[auth_value]?>" /></td>
					</tr>
					<tr>
						<td width="140px" style="padding-right:14px; text-align:right;" class="font4">User Files Path:</td>
						<td width="256px"><input type="text" id="UserFilesPath" name="UserFilesPath" value="<?php echo $config_data[UserFilesPath]?>" /></td>
					</tr>
																
				 </table>
					
					 
				 <div class="main_body2b" style="padding-bottom:0px; text-align:right;"> <input type="submit" value="Save" name="save" id="save" /> </div>
					 	
			  </div>
			  
	 		</div>
		  <div class="main_body3"><img src="<?php echo getConfigValue('base_url')?>coreimages/cn_main_body_bottom.gif" alt="" /></div>
	  </div>
		
</form>	

