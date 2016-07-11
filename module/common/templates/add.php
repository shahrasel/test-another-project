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
													'action'=> urlForAdmin($module.'/add') 
													) 
												 ); 
												 
	//echo 	formHelper::getName();
	?>

<div class="product">
  <div class="product_1"></div>
  <div class="product_2">Add 
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
          
      <?php
				foreach ($validation_rules as $rule):	
					//print_r ($rule);
				?>
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
							echo formHelper::input($rule['name'], $tmp_array);
											
						elseif ($rule['type'] != 'select' && $rule['type'] != 'selectgroup'):
							$tmp_array['value'] = $$rule['name'];
							echo formHelper::input($rule['name'],	$tmp_array);
																			
						else :	
							$select_value = 'optionvalue_'.$rule['name'];
							$tmp_array['options'] = $$select_value;
							//echo $page_title;
							$tmp_array['value'] = $$rule['name'];
							echo formHelper::input($rule['name'],	$tmp_array);
						endif;
						
						
					?>
      </div>
      <?php
				endforeach;	
				?>
      <div class="box_1_add">
        <input type="submit" name="submit" id="submit" value="Submit">
      </div>
    </div>
    <!--End of name_1-->
    <div class="name_2">&nbsp;&nbsp;&nbsp;</div>
    <!--<div class="flash_8"></div>-->
  </div>
  <!--End of task_2-->
  <div class="bottom">&nbsp;&nbsp;&nbsp;</div>
</div>

</form>
