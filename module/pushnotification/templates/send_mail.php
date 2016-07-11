<style>
	.box_1_add_cust {
		color: #6C6C6C;
		font-family: Tahoma,Geneva,sans-serif;
		font-size: 11px;
	}
</style>
<script type="text/javascript">
var totalNoUser = 0;
var startno = 0;
<!--
function selectAllList() {
	var aSelectLen = <?php echo count($user_lists); ?>;
	for(i = 0; i < aSelectLen; i++) {
		document.getElementById('userid_'+i).checked = true;
	}
}

function deselectAllList() {
	var aSelectLen = <?php echo count($user_lists); ?>;
	for(i = 0; i < aSelectLen; i++) {
		document.getElementById('userid_'+i).checked = false;
	}
}

function submitVal() {
	//if(startno < totalNoUser) {
	   var dataString = 'mtext='+$("#mtext").val()+'&start='+startno;
		$.ajax({  
		  type: "POST",  
		  url: "<?php echo getConfigValue('site_url') ?>pushnotificationscr.html",  
		  data: dataString,  
		  success: function(value) {  
			startno +=10;
			if(value==10) {
				submitVal();
			}
			else {
				$("#loadingimage").css('display','none');
				$("#finishedmessage").css('display','block');
			}
		 }  
	   });	
	//}
}

function submitValue() {
	startno = 0;
	$("#loadingimage").css('display','block');
	var dataString = 'language='+$("#language").val();
	$.ajax({  
      type: "POST",  
      url: "<?php echo getConfigValue('site_url') ?>pushnotificationTotalUser.html",  
      data: $("#idForm").serialize(), // serializes the form's elements.
      success: function(data)
      {
	     totalNoUser = data;
		 submitVal();
      } 
   });	
	return false;	
}

</script>
<?php 
useHelper('formHelper.php');
useHelper('htmlHelper.php');
formHelper::initForm( $validation_rules );
?>
<?php 
 	/*echo formHelper::create($module.'form', 
													array( 'onsubmit'=>'return checkregiform(this);',
													'action'=> urlForAdmin($module.'/send_push_notificaton') 
													) 
												 ); */
?>



<div style="width:100px;height:100px;position:absolute;top:200px;left:400px;display:none;z-index:10000" id="loadingimage">
    <img src="<?php echo getConfigValue('site_url') ?>images/ajax-loader-large.gif" style="width:100px;height:100px"/>
</div>

<form action="" method="post" id="idForm">

<div class="product">
  <div class="product_1"></div>
  <div class="product_2">Send Push Notification
  </div>
  <div class="product_3"></div>
  <div style="width:300px;float:left;position:relative;font-size:16px;color:#F00;margin-left:350px;display:none" id="finishedmessage">
  	Notification Send Successfully
  </div>
</div>
<!--End of product-->
<div class="task">
  <div class="task_1_add">&nbsp;&nbsp;&nbsp;</div>
  <div class="task_2_add">
    <div class="name_add"></div>
    <div class="name_1_add">
      
          
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
                
      <?php /*?><div class="box_1_add">Select Users</div>
      <div class="box_2_add">
      	<?php if(count($user_lists)>0):  ?>
          <table cellpadding="0" cellspacing="0" border="0" width="100%">
          	<?php for($i =0; $i<count($user_lists); $i+=2): ?>
            	<tr>
                    <td width="50%">
                    	<?php if(!empty($user_lists[$i])): ?>
                        	<input type="checkbox" id="userid_<?php echo $i; ?>" name="udid[]" value="<?php echo $user_lists[$i]['udid'] ?>"><span class="box_1_add_cust"><?php echo $user_lists[$i]['udid']; ?></span>
                        <?php endif; ?>
                    </td>
                    <td width="50%">
                    	<?php if(!empty($user_lists[$i+1])): ?>
                        	<input type="checkbox" name="udid[]" id="userid_<?php echo $i+1; ?>" value="<?php echo $user_lists[$i+1]['udid'] ?>"><span class="box_1_add_cust"><?php echo $user_lists[$i+1]['udid']; ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endfor; ?>
          </table>	
          <div class="box_1_add" style="padding-top:5px"><input type="button" onclick="selectAllList()" value="Select All User">&nbsp;&nbsp;&nbsp;<input type="button" onclick="deselectAllList()" value="Deselect All User"></div>
          <!--<div class="box_1_add">*If you select none, the package will be valid form all user</div>-->
        <?php endif; ?>
      </div> <?php */?>
                
      <div class="box_1_add">
        <input type="button" name="submit" id="submit" value="Send Notification" onclick="return submitValue()">
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
