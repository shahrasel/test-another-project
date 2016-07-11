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
<?php setJsFile ('js/formhelper.js'); ?>
<?php 
echo formHelper::create($module.'form', 
												array( 'onsubmit'=>'return checkregiform(this);',
												'action'=> urlFor( 'logininfo', array() ) 
												) 
											 ); 
												 
//echo 	formHelper::getName();
?>

<div class="top_sellers">
  <div class="top_sellers_left">
    <h2>Update Login Info </h2>
  </div>
  <div class="top_sellers_mid">
    <div class="number" id="numeric_controller"> </div>
  </div>
</div>
<div class="top_sellers_body">
  <div class="top_sellers_body2">
   
    <div class="top_sellers_body2b" style="float:none;" align="center" >
      <div style="width:450px; background-color:#EDEFD1; padding:10px;" align="center">
      	 <?php 
					//echo  $err;	
					if ( !empty($err) || !empty($msg) ): ?>
					
						<div align="center">
							<div class="<?php echo ( !empty($err)?'err_div':'msg_div');?>" > 
								<?php echo  ( !empty($err)?$err:$msg); ?>
               </div>
						</div>
					
					<?php endif?>
        <table  cellspacing="0" cellpadding="0" border="0" align="center" bgcolor="">
          <tbody>
            <?php
            foreach ($validation_rules as $rule):	
              //print_r ($rule);
            ?>
            <tr>
              <td><span class="font11"><?php echo $rule['label'];?></span><br/>
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
									$tmp_array['selected'] = $edit_info[$rule['name']];
									echo formHelper::input($rule['name'], $tmp_array);
													
								elseif ($rule['type'] != 'select' ):
									$tmp_array['value'] = $edit_info[$rule['name']];
									echo formHelper::input($rule['name'],	$tmp_array);
																					
								else :	
									$select_value = 'optionvalue_'.$rule['name'];
									$tmp_array['options'] = $$select_value;
									$tmp_array['selected'] = $edit_info[$rule['name']];
									echo formHelper::input($rule['name'],	$tmp_array);
								endif;
								
                ?></td>
            </tr>
            <?php
          endforeach;	
        ?>
          </tbody>
        </table>
        <div align="center">
          <input height="22" width="84" type="image" border="0" style="padding: 15px 0px 0px;" src="http://images.booksamillion.com/images/login/butn_signin.gif" id="update" name="update" value="update"/>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="main_body4 prepand8"><img src="<?php echo getConfigValue('base_url');?>images/abm_top_sellers_bottom.jpg" alt="" /></div>
<input type="hidden" id="id" name="id" value="<?php echo $edit_info[id];?>" />
</form>


<?php /*?><?php 

useHelper('formHelper.php');
useHelper('htmlHelper.php');

//htmlHelper::initTinyMCE();
//htmlHelper::initInputForTinyMCE('description');
//htmlHelper::initFCK();

//htmlHelper::initCK();
//print_r ($validation_rules);
formHelper::initForm( $validation_rules );
?>
<?php setJsFile ('js/formhelper.js'); ?>


<?php 
echo formHelper::create($module.'form', 
												array( 'onsubmit'=>'return checkregiform(this);',
												'action'=> urlFor( 'logininfo', array() ) 
												) 
											 ); 
												 
//echo 	formHelper::getName();
?>	
	
	<?php 
	//echo  $err;	
	if ( !empty($err) || !empty($msg) ): ?>
	<div align="center">
		<div class="<?php echo ( !empty($err)?'err_div':'msg_div');?>" >
			<?php echo  ( !empty($err)?$err:$msg); ?>
		</div>
	</div>
	
	<?php endif?>														
	<table width="188" cellspacing="0" cellpadding="0" border="0" align="center">
		<tbody>
			
			<?php
			foreach ($validation_rules as $rule):	
				//print_r ($rule);
			?>
			<tr>
				<td>
					<span class="style1"><?php echo $rule['label'];?></span><br/>
									
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
						$tmp_array['selected'] = $edit_info[$rule['name']];
						echo formHelper::input($rule['name'], $tmp_array);
										
					elseif ($rule['type'] != 'select' ):
						$tmp_array['value'] = $edit_info[$rule['name']];
						echo formHelper::input($rule['name'],	$tmp_array);
																		
					else :	
						$select_value = 'optionvalue_'.$rule['name'];
						$tmp_array['options'] = $$select_value;
						$tmp_array['selected'] = $edit_info[$rule['name']];
						echo formHelper::input($rule['name'],	$tmp_array);
					endif;
					
					
					?>
				</td>
			</tr>
			<?php
			endforeach;	
			?>
						
			
		</tbody>
	</table>
	<div align="center">
		<input height="22" width="84" type="image" border="0" style="padding: 15px 0px 0px;" src="http://images.booksamillion.com/images/login/butn_signin.gif" id="update" name="update" value="update"/>
	</div>
	<input type="hidden" id="id" name="id" value="<?php echo $edit_info[id];?>" />
</form><?php */?>