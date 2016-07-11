<?php

setCssFile ('module/News/templates/css/news.css');

?>

<?php //print_r ($table_data);?>
News List<br/><br/>
<table border="0" cellpadding="0" cellspacing="0" >
	
	<?php 
	foreach ($table_data as $row_data):	
	?>
		<tr>
			<td>
				<b><div class="title"><a href="<?php echo urlFor( 'news-details', array($row_data[title]) );?>"><?php echo $row_data[title]?></a></div></b>
				<br />
				<?php echo $row_data[short]?>
			</td>
		</tr>
	<?php
	endforeach; 
	?>
</table>

