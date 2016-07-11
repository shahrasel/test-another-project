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

 <?php 
 	echo formHelper::create($module.'form', 
													array( 'onsubmit'=>'return checkregiform(this);',
													'action'=> urlForAdmin($module.'/withdrawl') 
													) 
												 ); 
												 
	//echo 	formHelper::getName();
	?>	


	<div id="main_body">
	    
			<div class="new_clients_bg">
		      <h1>New<?php /*?> <?php echo strtoupper($module);?><?php */?></h1>
		  </div>
			
		  <div class="main_body2">
		    
				<div class="main_body2a">
			      <h1><?php echo strtoupper($module);?></h1>
			  </div>
				
			  <div class="main_body2b">
					<?php 
					//echo  $err;	
					if ( !empty($err) || !empty($msg) ): ?>
					<div align="center">
						<div class="<?php echo ( !empty($err)?'err_div':'msg_div');?>" >
							<?php echo  ( !empty($err)?$err:$msg); ?>
						</div>
					</div>
					<?php endif?>
				
			    <table width="845" border="0" cellpadding="2" cellspacing="0">
				    
						<?php
						foreach ($validation_rules as $rule):	
							//print_r ($rule);
						?>
						<tr>
						  <td width="340px" style="padding-right:14px; text-align:right;" class="font4"><?php echo $rule['label'];?>:</td>
							<td width="500px">
								
								<?php 
								
								$tmp_array = array();
								foreach ($rule as $key=>$val){
									if ($key != 'label' && $key != 'rule' && 
											$key != 'required' && $key != 'message' )
											$tmp_array[$key] = $val;
								}
																			
								if ($rule['type'] == 'radio'):
									$radio_value = 'radiovalue_'.$rule['name'];
									$tmp_array['options'] = $$radio_value;
									echo formHelper::input($rule['name'], $tmp_array);
													
								elseif ($rule['type'] != 'select' ):
									$tmp_array['value'] = $$rule['name'];
									echo formHelper::input($rule['name'],	$tmp_array);
																					
								else :	
									$select_value = 'optionvalue_'.$rule['name'];
									$tmp_array['options'] = $$select_value;
									echo formHelper::input($rule['name'],	$tmp_array);
								endif;
								
								
								?>
							</td>
						</tr>
						<?php
						endforeach;	
						?>
																		
				   </table>
					 					 
					 <div class="main_body2b" style="padding-bottom:0px; text-align:right;">
					 	<?php echo formHelper::input('withdrawl', array('type'=>'submit','value'=>'withdrawl') );?>
					 </div>
					 
			  </div>
			  
	  </div>
		  
		<div class="main_body3"><img src="<?php echo getConfigValue('base_url')?>coreimages/cn_main_body_bottom.gif" alt="" /></div>
		
	</div>
		
</form>	

