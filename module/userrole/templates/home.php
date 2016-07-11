<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<title>Untitled Document</title>
	<link media="all" type="text/css" rel="stylesheet" href="<?php echo getConfigValue('css');?>css.css"/>
	
	<?php echo getCss(); ?>
	<?php echo getJs(); ?>
	
</head>
<?php //echo getConfigValue('css');?>
<body>
	
	Testing Home page
	
	<br/>
	<br/>
	Testing News Module with filtering
	<br/>
	<form action="<?php echo urlFor('news-details', array())?>" method="post" >
	Search News: <input type="text" value="" id="title" name="title" />
	<input type="submit" value="Submit" />
		
	</form>
	..................................................................
	<br/>
	<?php echo $News_filter1;?>	
	
	<br/>
	..................................................................
	
</body>
</html>
