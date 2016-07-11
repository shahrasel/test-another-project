<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;  charset=iso-8859-1"    />
<title><?php echo getConfigValue('site_name')?> | Manage</title>

<meta name="keywords" content=""/>
<meta name="description" content=""/>
<script language="javascript" type="text/javascript" src="<?php echo getConfigValue('base_url')?>js/jquery-1.3.1.js"></script>
<link rel="shortcut icon" href="<?php echo getConfigValue('base_url');?>images/favicon.ico" type="image/x-icon" /> 

<?php setJsFile ('js/menu.js'); ?>
<?php setCssFile ('corecss/annanovas-style.css'); ?>
<?php setCssFile ('corecss/field.css'); ?>
<?php setCssFile ('corecss/menu.css'); ?>
<?php setCssFile ('corecss/jquery-ui-1.7.2.custom.css'); ?>
<?php setCssFile ('corecss/superfish.css'); ?>
<?php setJsFile ('js/formhelper.js'); ?>
<?php setJsFile ('js/jquery-ui-1.7.2.custom.min.js'); ?>

<?php echo getCss(); ?>
<?php echo getJs(); 
	
?>
</head>
<body >

<div style="opacity: 0.7; display: none;" id="backgroundPopup"></div>
<div id="popupContact" style="width:582px;height:370px;display:none">
<div id="formdiv" style="width:450px;float:left;position:relative;font-size:23px;font-weight:bold;padding-bottom:10px;color:#000;">
    
</div>
<div style="width:100px;float:left;position:relative;font-size:22px;font-weight:bold;text-align:right;padding-bottom:10px;">
    
</div>
    <a style="padding-top:10px" id="popupContactClose">x</a>
</div>

<div  id="wrapper_block1">
  <div id="wrapper_block2">
    <div id="main_body">
      <div class="logo" style="height:67px; display:table-cell; vertical-align:middle; float:none;">
				<?php /*?><img src="<?php echo getConfigValue('base_url'); ?>coreimages/logo.png"  alt="annanovas" border="0" /><?php */?>
        <?php echo getConfigValue('site_name')?>
      </div>
     
     	
     <?php echo $menu; ?>
     
      
     <?php echo $submenu; ?>
      <!--End of nav_2-->
     
       
	   <?php echo $performInstanceOutPut; ?>
       
   	</div>
    <!--End of main_body-->
    
    <div class="wrapper_block02">
      <div class="main_body0">
        <div class="annanovas_new">Copyright @ <a href="http://xtremedesignandengineering.com">xtremedesignandengineering.com</a></div>
      </div>
      <!--End of main_body0-->
    </div>
    <!--End of wrapper_block02-->
    
  </div>
  <!--End of wrapper_block2-->
</div>
<!--End of wrapper_block1-->

<?php 
/*
<div align="center">
  <div id="wrap">
    <div id="wrap3">
      <div id="top">
        <div id="logo">
          <h1>
            <?php 
					//print_r ($GLOBALS['cms_config']); 
					echo getConfigValue('site_name')?>
          </h1>
        </div>
        <div class="top_text">
          <ul >
            <li style="background:none; padding-left:0px;"><a href="<?php echo urlForAdmin('modulemanager/config')?>">SETTINGS</a></li>
            <li><a href="<?php echo urlForAdmin('user/logout')?>">LOG OUT</a></li>
          </ul>
        </div>
        <?php 
			if ( file_exists ( getConfigValue('install') ) )
				echo '<div style="color:#FF0000;"> Please Remove Install Folder From Your Project</div>';
			//if ()
		?>
      </div>
      <!-- Navigation Start-->
      <?php echo $menu; ?>
      <!-- Navigation End-->
      <div class="wrap_main_body">
        <!-- submenu  submenu-->
        <?php echo $submenu; ?>
        <!-- submenu bar end-->
        <!-- Main Body start-->
        <?php echo $performInstanceOutPut; ?>
        <!-- Main Body end-->
      </div>
      <!-- page bottom start-->
      <div id="bottom">
        <div class="font" align="right">Annanovas 1.0 <br />
          Licensed to Annanovas</div>
      </div>
      <!-- page bottom end-->
      <?php //echo $performInstanceOutPut; ?>
    </div>
  </div>
</div>
*/
?>

</body>
</html>
