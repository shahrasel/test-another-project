<?php

setCssFile ('module/News/templates/css/news.css');
setJsFile ('module/News/templates/js/news.js');

?>
<?php echo $title;//print_r ($table_data);?>


News Details<br/><br/>

<div class="title" onclick="test();"><b><?php echo $table_data[title]?></b></div><br/>
<?php echo $table_data[description]?>
