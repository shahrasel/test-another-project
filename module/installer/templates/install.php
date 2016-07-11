<?php 
	//setJsFile ('js/jquery-1.3.1.js');
	//setJsFile ('js/common.js');
?>	
	<form action="" method="post" >
		<?php //print_r ($table_data);?>
		<div id="main_body">	
			<div class="list_bg">
				<div class="list">
					<h1>Installing CnCms</h1>
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
				<div align="center"><?php echo $_err; ?></div>
					
				<table width="919" border="0" cellpadding="0" cellspacing="0"  >
						
					<tr>
						<td width="140px" style="padding-right:14px; text-align:right;" class="font4">Site Name:</td>
						<td width="256px"><input type="text" value="" name='site_name' id="site_name" class="input_box"/></td>
					</tr>
					
					<tr>
						<td width="140px" style="padding-right:14px; text-align:right;" class="font4">Page Suffix:</td>
						<td width="256px"><input type="text" value="" name='page_suffix' id="page_suffix" class="input_box"/></td>
					</tr>
					
					<tr>
						<td width="140px" style="padding-right:14px; text-align:right;" class="font4">Template Suffix:</td>
						<td width="256px"><input type="text" value="" name='template_suffix' id="template_suffix" class="input_box"/></td>
					</tr>
					
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
						<td width="140px" style="padding-right:14px; text-align:right;" class="font4">Database User:</td>
						<td width="256px"><input type="text" value="" name='db_user' id="db_user" class="input_box"/></td>
					</tr>
					
					<tr>
						<td width="140px" style="padding-right:14px; text-align:right;" class="font4">Database Password:</td>
						<td width="256px"><input type="text" value="" name='db_password' id="db_password" class="input_box"/></td>
					</tr>
					
					
					
				</table>
				<div class="main_body2b" style="padding-bottom:0px; text-align:right;"> <input type="submit" value="Save" name="save" id="save" /> </div>
				
				</div>
			</div>
			<div class="main_body3"><img src="<?php echo getConfigValue('base_url')?>coreimages/cn_main_body_bottom.gif" alt="" /></div>
		</div>			
	</form>	
