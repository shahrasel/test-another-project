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
												'action'=> urlFor( 'signin', array() )
												) 
											 ); 
												 
//echo 	formHelper::getName();
?>	
<div class="top_sellers">
  <div class="top_sellers_left">
    <h2>Create A New Account</h2>
  </div>
  <div class="top_sellers_mid">
    <div class="number" id="numeric_controller"> </div>
  </div>
</div>
<div class="top_sellers_body">
    
  <div class="top_sellers_body2">
		
    
    <div class="top_sellers_body2b" style="float:none;" align="center" > 
			
     	<div style="float:left; width:45px;">&nbsp;</div>
      
      <div style="width:380px; background-color:#EDEFD1; padding:10px; float:left;" align="left">
      	
        <div style="height: 135px; font-size:12px;" id="login_info" class="font11" >
        	Start here to create an account. You'll be able to:
					<div style="margin: 19px 25px 0px; height: 139px; color:#636612; font-size:11px;" >
            <div id="signup_list" >
              <li>Save items to a wish list</li>
              <li>Check out faster by storing shipping addresses and credit cards</li>
              <li>Post customer reviews</li>
              <li>Track packages and access your order history</li>
            </div>		
            
				 </div>
		 	  </div>
        
        <div align="center">
          <a href="<?php echo urlFor('signup', array())?>" >
          <img height="22" width="198" border="0" style="padding: 2px 0px 0px;" src="http://images.booksamillion.com/images/login/butn_setupanewaccount.gif" id="submit">
          </a>
          
        </div>
      </div>
      
      <div style="float:left; width:28px;">&nbsp;</div>
      <div style="width:380px; background-color:#EDEFD1; padding:10px; float:left;" align="center" >
      	
      <div style="min-height:118px; padding-top:20px;" class="signin_div">
      	<?php 
				//echo  $err;	
				if ( !empty($err) || !empty($msg) ): ?>
				<!--<div class="top_sellers_body2a">-->
					<div align="center">
						<div class="<?php echo ( !empty($err)?'err_div':'msg_div');?>" > 
							<?php echo  ( !empty($err)?$err:$msg); ?> 
						</div>
					</div>
				<!--</div>-->
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
          	<tr>
            	<td style="padding-top:5px;"><a href="<?php echo urlFor('forgot-password', array())?>">I can't access my account</a></td>
            </tr>
              
          </tbody>
        </table>
       </div>
       
      <div align="center">
        <input  type="image" src="http://images.booksamillion.com/images/login/butn_signin.gif" name="signin" value="signin" />
      </div>

      </div>
     
    </div>
    
  </div>
</div>
<div class="main_body4 prepand8"><img src="<?php echo getConfigValue('base_url');?>images/abm_top_sellers_bottom.jpg" alt="" /></div>

</form>