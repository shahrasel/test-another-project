<?php 

useHelper('formHelper.php');
useHelper('htmlHelper.php');

//htmlHelper::initTinyMCE();
//htmlHelper::initInputForTinyMCE('description');
//htmlHelper::initFCK();

//htmlHelper::initCK();
//print_r ($validation_rules);
formHelper::initForm( $validation_rules );
?>

<form action="<?php echo urlForAdmin($module.'/manage')?>" method="post" >
		<?php //print_r ($table_data);?>
					
		<div id="main_body">	
			<div class="list_bg">
				<div class="list">
					<h1>Account Balance <?php /*?><?php echo strtoupper($module);?><?php */?></h1>
				</div>
				<div class="list2" >
					<div style="float:right; padding-top:6px; font-size:12px; padding-right:10px;">
						
					</div>
					<!--<div style="float:right;">
						<ul >
							<li class="list2_fixed">ACTIVE</li>
							<li><a href="#">INACTIVE</a></li>
							<li><a href="#">FRAUD</a></li>
						</ul>
					</div>-->
				</div>
			</div>
			<div class="main_body2">
				<div class="main_body2a" align="center">
									
					
					<div class="msg_div" align="center" >	
						Your Account Balance : <?php echo $balance;?>
					</div>				
					
					
				</div>
			</div>
			<div class="main_body3"><img src="<?php echo getConfigValue('base_url')?>coreimages/cn_main_body_bottom.gif" alt="" /></div>
		</div>
</form>	
		