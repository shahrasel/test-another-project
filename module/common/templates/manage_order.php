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
												'action'=> urlForAdmin($module.'/'.$action) 
												) 
											 ); 
				 
?>	
  
<div class="product">
  <div class="product_1"></div>
  <div class="product_2">Manage Top <?php echo strtoupper($module);?> Order</div>
  <div class="product_3"></div>
</div>


<div style="padding-bottom:10px" >

  <?php
	if ($validation_rules){
	?>
  
   
  <div class="task">
  	<div class="task_1">&nbsp;&nbsp;&nbsp;</div>
  	<div class="task_2" style=" overflow:auto; padding-top:0px;">
  <?php	
		foreach ($validation_rules as $rule):	
	//print_r ($rule);
		
	?>
 
    <div class="box_s_wrapper" >
      <div class="box_s1_add"><?php echo $rule['label'];?></div>
      <div class="box_s2_add">
      <?php 
                        
      if ($rule['type'] == 'radio'):
        $radio_value = 'radiovalue_'.$rule['name'];
        echo formHelper::input($rule['name'], 
            array('type'=>$rule['type'],
                  'options'=>$$radio_value,
                  'selected'=>$$rule['name'] 
                ) 
            );
                
      elseif ($rule['type'] != 'select' ):
				
        echo formHelper::input($rule['name'], 
                  array('type'=>$rule['type'], 
                  'class'=>$rule['class'], 
                  'tooltip'=>'', 
                  'value'=>$$rule['name'],
                   
                  )
                );
                                
      else :	
        $select_value = 'optionvalue_'.$rule['name'];
				//echo 	$rule['class'];
        echo formHelper::input($rule['name'], 
            array('type'=>$rule['type'],
                  'options'=>$$select_value,
									'class'=>$rule['class'],
                  'selected'=>$$rule['name'] 
                  ) 
            );
      endif;
      
      ?>
      </div>
    </div>
    
    <?php
      endforeach;	
    ?>	
    
    <div class="box_1_add">
      <input type="submit" value="Search" name="search" />
    </div>
    
    </div>
    <div class="bottom">&nbsp;&nbsp;&nbsp;</div>
  </div>
	<?php
  }	
  ?>
      
   	
   
</div>

<div class="task">
  <div class="task_1">&nbsp;&nbsp;&nbsp;</div>
  <div class="task_2" style=" overflow:auto;">
  	
     <?php 
      //$err =  'Error Check';	
      if ( !empty($err) || !empty($msg) ): ?>
      <div align="center">
        <div class="<?php echo ( !empty($err)?'err_div':'msg_div');?>" >
          <?php echo  ( !empty($err)?$err:$msg); ?>
        </div>
      </div>
      <?php endif?>
      
    <table width="895" border="0" align="left" >
      
      <tr class="sku">
       
        <?php 
				$count = 0;
				foreach ($table_header_column as $table_head):
				?>
      	  <td class="sku_1" ><?php echo $table_head?></td>
        <?php endforeach; ?>
      </tr>
      
      <?php 
			$tmp_count = 0;
			foreach ($table_data as $row_data):	
			?>
      <tr class="tr<?php echo ($tmp_count%3)?>">
        
        <?php foreach ($table_row_column as $table_column):?>
        <td  class="flash_03">
					<?php 
						if ( strstr ($table_column, 'file') ){
								$ext = strtolower (pathinfo($row_data[$table_column], PATHINFO_EXTENSION));
								if ( in_array($ext, array('jpeg','jpg','gif','png')) ){
										echo '<img src="'.getConfigValue('base_url').'media/'.$module.'/thumb/'.$row_data[$table_column].'" width="100px">';
								}
						}
						else{
								echo $row_data[$table_column];
						}
					?>
        </td>
        
        <?php endforeach; ?>
        <td class="flash_03">
          <input type="text" id="<?php echo $field_name.'_'.$row_data['id'];?>" name="<?php echo $field_name.'_'.$row_data['id'];?>" value="<?php echo $row_data[$field_name];?>" class="text_box_250" />
        </td>
        
      </tr>
      <?php
				$tmp_count++;
			endforeach; 
			?>
      
      <tr>
        <td colspan="<?php echo count($table_row_column)?>">
        	<input type="submit" id="update" name="update" value="Update Order">
         </td>
      </tr>
      
      <tr>
        <td colspan="<?php echo count($table_row_column)?>">&nbsp;</td>
      </tr>
      
      
    </table>
    <!--End of flash_9-->
  </div>
  <!--End of task_2-->
  <div class="bottom">&nbsp;&nbsp;&nbsp;</div>
</div>
</form>