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
													'action'=> urlForAdmin($module.'/edit').'?'.$page_controlling_value 
													) 
												 ); 
												 
	//echo 	formHelper::getName();
	//print_r ($edit_info);
	//echo $edit_info['is_cache'];
	?>

<div class="product">
  <div class="product_1"></div>
  <div class="product_2">Edit 
    <?php echo strtoupper($module);?>
  </div>
  <div class="product_3"></div>
</div>
<!--End of product-->
<div class="task">
  <div class="task_1_add">&nbsp;&nbsp;&nbsp;</div>
  <div class="task_2_add">
    <div class="name_add"></div>
    <div class="name_1_add">
      
      <?php 
      //$err =  'Error Check';	
      if ( !empty($err) || !empty($msg) ): ?>
      <div align="center">
        <div class="<?php echo ( !empty($err)?'err_div':'msg_div');?>" >
          <?php echo  ( !empty($err)?$err:$msg); ?>
        </div>
      </div>
      <?php endif?>
      
      
						
       <div class="box_1_add">Category:</div>
       <div class="box_2_add">
            
          <?php 
            
            if ( $validation_failed != 'failed')
              $selected_cat_array = explode (',', $edit_info['catid']);
            else
              $selected_cat_array = $catidarray;
            
            //print_r ($selected_cat_array);
            
            foreach ($cat_array as $key=>$val): 
              if ($val):
          ?>
                <input type="checkbox" id="catidarray" name="catidarray[]" value="<?php echo $key;?>" <?php echo in_array($key, $selected_cat_array)?'checked="checked"':'';?> />&nbsp;<span class="font4"><?php echo $val;?></span> 
              
          <?php
              endif;
            endforeach;
          ?>
      </div>  
            
      <?php
			foreach ($validation_rules as $rule):	
					//print_r ($rule);
			?>
      <?php 
					//echo $edit_info[$rule['name']];
					if ($rule['type'] == 'file' && $edit_info[$rule['name']]):
						//echo $edit_info[$rule['name']];
				?>
      		<div class="box_1_add"> <img src="<?php echo getConfigValue('base_url').'media/'.$module.'/thumb/'.$edit_info[$rule['name']]?>" width="100px">
        <input type="checkbox" value="remove" name="<?php echo $rule['name'];?>_remove" />
        Remove Image </div>
      <?php endif; ?>
      <div class="box_1_add"><?php echo $rule['label'];?></div>
      <div class="box_2_add">
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
											
						elseif ($rule['type'] != 'select' && $rule['type'] != 'selectgroup'):
							$tmp_array['value'] = $edit_info[$rule['name']];
							echo formHelper::input($rule['name'],	$tmp_array);
																			
						else :	
							$select_value = 'optionvalue_'.$rule['name'];
							$tmp_array['options'] = $$select_value;
							$tmp_array['selected'] = $edit_info[$rule['name']];
							echo formHelper::input($rule['name'],	$tmp_array);
						endif;
						
						
					?>
      </div>
      <?php
				endforeach;	
				?>
      <div class="box_1_add">
        <input type="submit" value="Update" name="update" id="update" />
      </div>
    </div>
    <!--End of name_1-->
    <div class="name_2">&nbsp;&nbsp;&nbsp;</div>
    <!--<div class="flash_8"></div>-->
  </div>
  <!--End of task_2-->
  <div class="bottom">&nbsp;&nbsp;&nbsp;</div>
</div>
<input type="hidden" id="id" name="id" value="<?php echo $edit_info[id];?>" />
</form>
