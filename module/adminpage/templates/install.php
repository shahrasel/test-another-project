<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CN Admin Pannel</title>
<script language="javascript" type="text/javascript" src="/cms/js/jquery-1.3.1.js"></script>
<script language="javascript" type="text/javascript" src="/cms/js/hoverIntent.js"></script>
<script language="javascript" type="text/javascript" src="/cms/js/superfish.js"></script>
<script language="javascript" type="text/javascript" src="/cms/js/menu_help.js"></script>
		
<?php setCssFile ('corecss/cms_core.css'); ?>
<?php setCssFile ('corecss/superfish.css'); ?>
<?php setJsFile ('js/common.js'); ?>

<?php echo getCss(); ?>
<?php echo getJs(); 
	
?>

		
</head>

<body>

<div align="center">
<div id="wrap">
<div id="wrap3">

	<div id="top">
		<div id="logo">
			<h1><?php 
					//print_r ($GLOBALS['cms_config']); 
					echo getConfigValue('site_name')?>
			</h1>
		</div>
		<div class="top_text">
			<ul >
				<li style="background:none; padding-left:0px;"><a href="#">MY INFO</a></li>
				<li><a href="#">SETTINGS</a></li>
				<li><a href="<?php echo urlForAdmin('user/logout')?>">LOG OUT</a></li>
			</ul>
		</div>
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
		<div class="font" align="right">Cybernetikz 2.2.3 <br />Licensed to Cybernetikz</div>
	</div>
	<!-- page bottom end-->	
	
	<?php //echo $performInstanceOutPut; ?>
	
</div>
</div>
</div>

</body>
</html>
