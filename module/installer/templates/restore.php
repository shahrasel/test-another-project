<?php 
	//setJsFile ('js/jquery-1.3.1.js');
	//setJsFile ('js/common.js');
?>	
	<form action="<?php echo urlForAdmin('modulemanager/restore')?>" method="post" >
		<?php //print_r ($table_data);?>
		<div id="main_body">	
			<div class="list_bg">
				<div class="list">
					<h1>Manage Module</h1>
				</div>
				<div class="list2" >
					
				</div>
			</div>
			<div class="main_body2">
			<div class="main_body2a">
				<?php echo $_err; ?>
				<table width="919" border="0" cellpadding="0" cellspacing="0"  >
						<tr class="title">
							<td class="font4">File Name</td>
							<td class="font4">Restore</td>
							<td class="font4">Remove</td>
						</tr>		
		
					<?php 
					//print_r ($installed_module);
					$tmp_count = 0;
					foreach ($files as $file):
						
						
					?>
					<tr class="tr<?php echo ($tmp_count%3)?>">
						<td class="font4"><?php echo $file?></td>
						<td class="font4">
							<a href="<?php echo urlForAdmin('modulemanager/restore').'?restore_file='.$file.'&perform_module='.$perform_module;; ?>">
								Restore
							</a>
						</td>
						<td class="font4">
							<a href="<?php echo urlForAdmin('modulemanager/restore').'?remove_file='.$file.'&perform_module='.$perform_module; ?>"><img src="<?php echo getConfigValue('media_url')?>images/b_drop.png" border="0" /> </a>
						</td>
						
																
					</tr>
			<?php
				$tmp_count++;
			endforeach; 
			?>			
			
		</table>
		
				</div>
			</div>
			<div class="main_body3"><img src="<?php echo getConfigValue('base_url')?>coreimages/cn_main_body_bottom.gif" alt="" /></div>
		</div>			
	</form>	
