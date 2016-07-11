<?php 
useHelper('htmlHelper.php');
htmlHelper::initTinyMCE();
htmlHelper::initInputForMCE('description');

htmlHelper::initFCK();
//$short = 

?>

<form action="<?php echo urlForAdmin('news/edit')?>" method="post" enctype="multipart/form-data" >

	Input Display Form<br/>
	Title--<input type="text" value="<?php echo $edit_info[title]?>" name='title' id="title"/><br/>
	Short--<!--<textarea name='short' ><?php echo $edit_info[short]?></textarea>-->
	<?php echo 	htmlHelper::initInputForFCK('short', $edit_info[short] )?>
	<br/>
	Description--<textarea name='description' id="description"><?php echo $edit_info[description]?></textarea><br/>
	
	News File--<input type="file" id="newsfile" name="newsfile" /><br/>
	
	<input type="hidden" id="id" name="id" value="<?php echo $edit_info[id];?>" />
	<input type="submit" value="update" name="update" id="update" />
	
	<input type="hidden" name="perpage" value="<?php echo $perpage?>" />
	<input type="hidden" name="current_page" value="<?php echo $perpage?>" />
	<input type="hidden" name="order_by" value="<?php echo $perpage?>" />
	<input type="hidden" name="order_title" value="<?php echo $perpage?>" />
		
</form>	
