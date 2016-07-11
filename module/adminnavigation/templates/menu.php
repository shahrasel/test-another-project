<?php $ses_userrole = getSessionValue ('user_role'); ?>
<ul id="nav">
    <li>
    	<?php if($ses_userrole['title'] == 'admin'): ?>
	    	<a href="<?php echo urlForAdmin('page')?>">Home</a>
        <?php else: ?>
        	<a href="<?php echo urlForAdmin('saveid')?>">Home</a>
        <?php endif; ?>
      
      <ul>
	  <?php 
      	foreach ($menu_array as $menu):
          $tmp_menu = ucwords( str_replace ('_',' ', $menu) );
      ?>
          <li class="current">
          	<?php if($menu == 'page'): ?>
            	<?php if($ses_userrole['title'] == 'admin'): ?>
	            	<a href="<?php echo urlForAdmin($menu)?>"><?php echo ucwords($tmp_menu);?></a>
                <?php endif; ?>
            <?php else: ?>
            	<?php if($menu == 'saveid'): ?>
    	        	<a href="<?php echo urlForAdmin($menu)?>">Push Notification</a>
	            <?php else: ?>
	            	<a href="<?php echo urlForAdmin($menu)?>"><?php echo ucwords($tmp_menu);?></a>
                <?php endif; ?>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
                          
      </ul>
        
    </li>
    <?php if($ses_userrole['title'] == 'admin'): ?>
    <li><a href="<?php echo urlForAdmin('user/manage')?>">User</a></li>
    <?php endif; ?>
    <li><a href="<?php echo urlForAdmin('pushnotification/manage')?>">Push Notification</a></li>
    
    <li style="float:right"><a href="<?php echo urlForAdmin('user/logout')?>">Logout</a></li>
    <li style="float:right"><a href="<?php echo urlForAdmin('user/my_login_info')?>">User Security</a>
    <?php if($ses_userrole['title'] == 'admin'): ?>
    <li style="float:right"><a href="<?php echo urlForAdmin('modulemanager')?>">Site Configuration</a>
    <?php endif; ?>
</ul>
    