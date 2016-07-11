<?php 

useHelper('formHelper.php');
useHelper('htmlHelper.php');

//htmlHelper::initTinyMCE();
//htmlHelper::initInputForTinyMCE('description');
//htmlHelper::initFCK();

//htmlHelper::initCK();
//print_r ($validation_rules);
formHelper::initForm( $validation_rules );
?>

<form action="<?php echo urlForAdmin($module.'/transaction')?>" method="post" >
		<?php //print_r ($table_data);?>
					
		<div id="main_body">	
			<div class="list_bg">
				<div class="list">
					<h1>Account Balance <?php /*?><?php echo strtoupper($module);?><?php */?></h1>
				</div>
				<div class="list2" >
					<div style="float:right; padding-top:6px; font-size:12px; padding-right:10px;">
						Page: <input type="text" style="width:20px; height:12px;" value="" name="current_page"/>
						Show: <input type="text" style="width:20px; height:12px;" value="<?php echo $perpage ?>" name="perpage"/>
						<input type="submit" value="Show" name="search" />		
					</div>
					<!--<div style="float:right;">
						<ul >
							<li class="list2_fixed">ACTIVE</li>
							<li><a href="#">INACTIVE</a></li>
							<li><a href="#">FRAUD</a></li>
						</ul>
					</div>-->
				</div>
			</div>
			<div class="main_body2">
				<div class="main_body2a">
					
					<?php 
					//echo  $err;	
					if ( !empty($err) || !empty($msg) ): ?>
					<div align="center">
						<div class="<?php echo ( !empty($err)?'err_div':'msg_div');?>" >
							<?php echo  ( !empty($err)?$err:$msg); ?>
						</div>
					</div>
					<?php endif?>
						
					
					
					<table width="912" border="0" cellpadding="0" cellspacing="0"  >
						<tr class="title">
													
							<td class="font4"><input type="checkbox" value="" id="id_all_up" name="id_all_up" onclick="alterChecking('#'+this.id)" />
							</td>
							<?php foreach ($table_header_column as $table_head):?>
								<td class="font4"><?php echo $table_head?></td>	
							<?php endforeach; ?>
											
						</tr>
					
						
						<?php 
						$tmp_count = 0;
						foreach ($table_data as $row_data):	
						?>
							
							<tr class="tr<?php echo ($tmp_count%3)?>">
								
									<td class="font4"><input type="checkbox" value="<?php echo $row_data[id]?>" name="chk_id[]" /></td>
								<?php foreach ($table_row_column as $table_column):?>
									<td class="font4">
										<?php 
												if ( strstr ($table_column, 'file') ){
													$ext = strtolower (pathinfo($row_data[$table_column], PATHINFO_EXTENSION));
													if ( in_array($ext, array('jpeg','jpg','gif','png')) ){
														echo '<img src="'.getConfigValue('base_url').'media/'.$module.'/'.$row_data[$table_column].'" width="100px">';
													}
												}
												else{
													echo $row_data[$table_column];
												}
										?>
									</td>	
								<?php endforeach; ?>
									<td class="font4"><a href="<?php echo urlForAdmin($module.'/manage')?>?id=<?php echo $row_data[id]?>&active=<?php echo ($row_data[active]=='Active')?'Inactive':'Active'?><?php echo $page_controlling_value?>">
											<?php echo ($row_data[active]=='Active')?'Active':'Inactive'?></a>
									</td>
									<td class="font4"><a href="<?php echo urlForAdmin($module.'/edit')?>?id=<?php echo $row_data[id].$page_controlling_value?>"><img src="<?php echo getConfigValue('media_url')?>images/b_edit.png" border="0"></a>
									</td>
									<td class="font4"><a href="<?php echo urlForAdmin($module.'/manage')?>?id=<?php echo $row_data[id]?>&delete=yes"><img src="<?php echo getConfigValue('media_url')?>images/b_drop.png" border="0"></a>
									</td>
								
							</tr>
													
						<?php
							$tmp_count++;
						endforeach; 
						?>
									
						<tr class="bottom">
							<td class="font4"><input type="checkbox" value="" id="id_all_down" name="id_all_down" onclick="alterChecking('#'+this.id)" /></td>
							<td colspan="<?php echo count($table_row_column)+3?>" align="left" >
								<select name="action_all" id="action_all" style="width:100px;"> 
									<option value=""></option>
									<option value="Delete">Delete</option>
									<option value="Active">Active</option>
									<option value="Inactive">Inctive</option>
								</select>
								<input type="submit" value="Execute All" />
							</td>
						</tr>
						
						<tr>
							<td colspan="<?php echo count($table_row_column)+4?>"  align="center"><?php echo $str_paging ?></td>						</tr>
		
					</table>
					
				</div>
			</div>
			<div class="main_body3"><img src="<?php echo getConfigValue('base_url')?>coreimages/cn_main_body_bottom.gif" alt="" /></div>
		</div>
</form>	
		