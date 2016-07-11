 <?php 
 	//echo getConfigValue('current_action') 
 	//print_r ($submenu_array);
	//echo getConfigValue('page_suffix');
 ?>
 
 <div class="nav_3">
    <ul>
    <?php 
    if ($submenu_array)	:
      foreach ($submenu_array as $submenu):
        $tmp_submenu = ucwords( str_replace ('_',' ', $submenu) );
    ?>
          <li class="<?php echo (getConfigValue('current_action')==$submenu)?'actie_submenu':'inactie_submenu'?>">
          	<a href="<?php echo getConfigValue('base_url')?>apanel/<?php echo $module.'/'.$submenu.getConfigValue('page_suffix');?>" > 
							<?php echo ucwords($tmp_submenu);?> 
            </a>
          </li>
     <?php
			endforeach;
		endif;
		
		?>    
  	</ul>
</div>
      
 <?php /*?><div id="brows_clients">
		<?php 
		if ($submenu_array)	:
			foreach ($submenu_array as $submenu):
				$tmp_submenu = ucwords( str_replace ('_',' ', $submenu) );
		?>
			<div id="<?php echo (getConfigValue('current_action')==$submenu)?'actie_submenu':'inactie_submenu'?>">
				<h1><a href="<?php echo getConfigValue('base_url')?>apanel/<?php echo $module.'/'.$submenu.getConfigValue('page_suffix');?>" > <?php echo ucwords($tmp_submenu);?> </a></h1>
			</div>
		<?php
			endforeach;
		endif;
		
		?>
		<!--<div id="search">
			<h1><a href="#">Search</a></h1>
		</div>-->
	</div><?php */?>