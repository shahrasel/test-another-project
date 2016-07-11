<?php 
useHelper('htmlHelper.php');
htmlHelper::initTinyMCE();
htmlHelper::initInputForMCE('description');

htmlHelper::initFCK();


?>
<script>
	//changeEnter();
</script>

<form action="<?php echo urlForAdmin('news/add')?>" method="post" enctype="multipart/form-data">

	Input News Form<br/>
	Title--<input type="text" value="" name='title' id="title"/><br/>
	Short--<!-- <textarea name='short' id="short" cols="10" rows="10" ></textarea> -->
	<?php echo 	htmlHelper::initInputForFCK('short', $edit_info[short] )?>	
	<br/>
	
	Description--<textarea name='description' id="description"></textarea><br/>
	
	News File--<input type="file" id="newsfile" name="newsfile" /><br/>
			
	<input type="submit" value="submit" name="submit" id="submit" />
</form>	

