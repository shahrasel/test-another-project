
<form action="<?php echo urlForAdmin('user/login')?>" method="post" enctype="multipart/form-data" name="login_form"  id="login_form">

<div id="wrapper_login">
	<div align="center" style="color:#009"><?php echo getConfigValue('site_name')?> </div>
  
  <div id="main_body_login">
    <div class="main_body_1_login">
      <div class="main_body_2_login">
        
        <h2 class="login_tittle" align="center" style="width:402px; ">To Get Access Please Log In</h2>
        
        <br clear="all" />
        <div align="center" class="err_div" style="width:425px;"> <?php echo $err_msg ?> </div>
        
        <div class="login_body">
          <div class="login_body_1">
            <div class="login_body_2">
              <div class="user_name_login">USER NAME:</div>
              <div class="anna_logo_login" style="width:60px; padding-left:50px;"><?php /*?><img src="<?php echo getConfigValue('base_url');?>coreimages/logo.png"  height="57" /><?php */?></div>
              <div class="user_name_text_login">
                <input type="text" name="login_name" id="login_name" class="user_text_login" />
              </div>
              <div class="user_passwoard_login">PASSWORED:</div>
              <div class="user_name_text">
                <input type="password" name="login_password" id="login_password" class="user_text_login" />
              </div>
              <div class="login_button_login">
              	<input type="hidden" name="submit" id="submit" value="Login" />
                <!--img src="/annanovas/coreimages/login_button.gif" height="36" width="62" alt="Login"  onclick="document.login_form.submit();"/>-->
                <input type="image" src="<?php echo getConfigValue('base_url');?>coreimages/login_button.gif" width="62" height="36" border="0" alt="button" />
              </div>
            </div>
            <!--End of login_body_2-->
          </div>
          <!--End of login_body_1-->
        </div>
        <!--End of login_body-->
        <!--<div class="request_passwoard_login"><a href="#">Request new password</a></div>-->
        <br clear="all"  />
        <div class="annanovas_new" style="padding-right:44px;padding-top:10px;">Copyright @ <a href="http://xtremedesignandengineering.com">xtremedesignandengineering.com</a></div>
      </div>
      <!--End of main_body_1-->
    </div>
    <!--End of main_body_1-->
  </div>
  <!--End of main_body-->
  <!--End of wrapper_block2-->
  <!--End of wrapper_block1-->
</div>
</form>	

