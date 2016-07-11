<?php 
	//setJsFile ('js/jquery-1.3.1.js');
	//setJsFile ('js/common.js');
?>

<form action="<?php echo urlForAdmin('modulemanager/managemodule')?>" method="post" >
  <div class="product">
    <div class="product_1"></div>
    <div class="product_2">Manage <?php echo strtoupper($module);?></div>
    <div class="product_3"></div>
  </div>
  <div class="task">
    <div class="task_1">&nbsp;&nbsp;&nbsp;</div>
    <div class="task_2" style=" overflow:auto;">
      <table width="895" border="0" align="left" >
        <tr class="sku">
          <td class="sku_1" >Name</td>
          <td class="sku_1" >Status</td>
          <td class="sku_1" >Description</td>
        </tr>
        <?php 
				//print_r ($installed_module);
				$tmp_count = 0;
				foreach ($modules as $module):
					
					$filename = getConfigValue ('module').$module.'/description.txt';	
					$handle = fopen($filename, "rb");
					$description = fread($handle, filesize($filename));
					
					$installed = 0;
					if ( in_array ($module, $reserved_module) )
							$installed = -1;
					else if ( in_array ($module, $installed_module) )
							$installed = 1;
				?>
        <tr class="tr<?php echo ($tmp_count%3)?>">
          <td class="flash_03"><?php echo $module?></td>
          <td class="flash_03"><?php 
									if ($installed == -1) 
										echo '&nbsp;NA';
									else{
									?>
            <a href="<?php echo urlForAdmin('modulemanager/managemodule')?>?name=<?php echo $module?>&install=<?php echo ($installed==1)?'0':'1'?>&peform_action=moduleinstaller"> <?php echo ($installed==1)?'Install':'Uninstall'?> </a>
            <?php
									}
								?></td>
          <td class="flash_03"><?php echo $description?></td>
        </tr>
        <?php
				endforeach; 
				?>
        <tr>
        	<td colspan="3">&nbsp;</td>
      	</tr>
      
      </table>
      <!--End of flash_9-->
    </div>
    <!--End of task_2-->
    <div class="bottom">&nbsp;&nbsp;&nbsp;</div>
  </div>
</form>
