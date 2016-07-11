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
							'action'=> urlForAdmin($module.'/manage') 
							) 
						 ); 
					 
	?>
<?php //print_r ($table_data);?>

<div class="product">
  <div class="product_1"></div>
  <div class="product_2">
  	
    <div style="float:left">Manage <?php echo strtoupper($module);?></div>
    
    <div style="float:right; padding-top:0px; font-size:12px; padding-right:10px;">
      <a href="<?php echo urlForAdmin($module.'/importxls')?>">Import</a>&nbsp;&nbsp;
      <a href="<?php echo urlForAdmin($module.'/exportxls').'?'.$page_controlling_value?>">Export</a> &nbsp;&nbsp;
      Page: <input type="text" style="width:20px; height:12px;" value="<?php echo $current_page ?>" name="current_page"/>
      Show: <input type="text" style="width:20px; height:12px;" value="<?php echo $perpage ?>" name="perpage"/>
      <input type="submit" value="Show" name="search" />
    </div>
    
  </div>
  <div class="product_3"></div>
</div>
<!--End of product-->
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
        <td width="38" align="center" class="flash_03"><input type="checkbox" value="" id="id_all_up" name="id_all_up" onclick="alterChecking('#'+this.id)" /></td>
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
        <td align="center" class="flash_03"><input type="checkbox" value="<?php echo $row_data[id]?>" name="chk_id[]" /></td>
        <?php foreach ($table_row_column as $table_column):?>
        <td  class="flash_03">
					<?php 
						if ( strstr ($table_column, 'qtime') ){
							echo date('m-d-Y', $row_data[$table_column]);
						}
						else if ( strstr ($table_column, 'data') ){
							$data = unserialize ($row_data[$table_column]);
							echo $data['contactname'].' - '. $data['companyname'].' - '. $data['email'].'<br />'.$data['quote_describe_goal'];
						}
						else if ( strstr ($table_column, 'file') ){
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
        	<a href="<?php echo urlForAdmin($module.'/view')?>?id=<?php echo $row_data[id]?><?php echo $page_controlling_value?>"> View</a>
        </td>
        
        <td class="flash_03">
        	<a href="<?php echo urlForAdmin($module.'/manage')?>?id=<?php echo $row_data[id]?>&active=<?php echo ($row_data[active]=='Active')?'Inactive':'Active'?><?php echo $page_controlling_value?>"> <?php echo ($row_data[active]=='Active')?'Active':'Inactive'?></a>
        </td>
        
        <td class="flash_03">
        	<a href="<?php echo urlForAdmin($module.'/manage')?>?id=<?php echo $row_data[id]?>&delete=yes"><img src="<?php echo getConfigValue('media_url')?>images/b_drop.png" border="0"></a>
        </td>
        
      </tr>
      <?php
				$tmp_count++;
			endforeach; 
			?>
      
      <tr >
        <td class="flash_03">
        	<input type="checkbox" value="" id="id_all_down" name="id_all_down" onclick="alterChecking('#'+this.id)" />
        </td>
        <td colspan="<?php echo count($table_row_column)+3?>" align="left"  class="flash_03">
        	<select name="action_all" id="action_all" style="width:100px;">
            <option value=""></option>
            <option value="Delete">Delete</option>
            <option value="Active">Active</option>
            <option value="Inactive">Inctive</option>
          </select>
          <input type="submit" value="Execute All" /></td>
      </tr>
      
      <tr>
        <td colspan="<?php echo count($table_row_column)+3?>">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="<?php echo count($table_row_column)+4?>"  align="center" >
					<div style="margin-left:400px; float:left; width:200px;"><?php echo $str_paging ?></div>
        </td>
      </tr>
      
      <tr>
        <td colspan="<?php echo count($table_row_column)+3?>">&nbsp;</td>
      </tr>
      
    </table>
    <!--End of flash_9-->
  </div>
  <!--End of task_2-->
  <div class="bottom">&nbsp;&nbsp;&nbsp;</div>
</div>
</form>
