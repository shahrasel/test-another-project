<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;  charset=iso-8859-1"    />
<title><?php echo getConfigValue('site_name')?>  | Apanel</title>

<meta name="keywords" content=""/>
<meta name="description" content=""/>
<link rel="shortcut icon" href="<?php echo getConfigValue('base_url');?>images/favicon.ico" type="image/x-icon" /> 
<script language="javascript" type="text/javascript" src="<?php echo getConfigValue('base_url')?>js/jquery-1.3.1.js"></script>

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
<body style="background:#d4d4d4;">


	<!-- Main Body start-->
	<?php echo $performInstanceOutPut; ?>
	<!-- Main Body end-->
	
		

</body>
</html>
