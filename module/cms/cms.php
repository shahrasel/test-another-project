<?php
class cms extends parser
{ 
	
	//var $module_name = 'News';
	//var $table_name = 'News';
	
	var $protocols;
	var $protocolsList = array('content', 'filemanager', 'xlsmanager', 'cart', 'formvalidation', 'ratings');
	
	function cms(){
		
		$this->module_name = 'cms';
		$this->table_name = 'cms';
	
		initProtocols (getConfigValue('protocol'), $this->protocolsList);
		$this->protocols = loadProtocols ($this->protocolsList);
		$this->table_name = getConfigValue('table_prefix').$this->table_name;
		
	}
	
	function optionvalue_active (){
		$optionvalue_array = array();
		$optionvalue_array['Active'] = 'Active';
		$optionvalue_array['Inactive'] = 'Inactive';
		
		return $optionvalue_array;			
	}
	
	function userregistration (){
		$this->initPageValue();
		
		$user_info = $this->executeOne ('select * from yps_registration where username =\''.$this->data['username'].'\' ');
		
		
		if (count($user_info)>0){
			echo 'registration failed';
		}
		
		else {
			$this->executeAdd( 'yps_registration', array('username','password','email', 'confirmpass') );
			echo 'registration succeed';
		}
		exit;	
				
	}
	
	function userlogin (){
		$this->initPageValue();
		
		$user_info = $this->executeOne ('select * from yps_registration where username =\''.$this->data['username'].'\' and password=\''.$this->data['password'].'\'');
		
		
		if (count($user_info)>0){
			echo 'loggedin succeed';
		}
		
		else 
			echo 'failed';
			
		exit;	
	}
	
	function unicode_string_to_array( $string ) { 
		$strlen = mb_strlen($string);
		while ($strlen) {
			$array[] = mb_substr( $string, 0, 1, "UTF-8" );
			$string = mb_substr( $string, 1, $strlen, "UTF-8" );
			$strlen = mb_strlen( $string );
		}
		return $array;
	}

	function unicode_entity_replace($c) { 
		$h = ord($c{0});    
		if ($h <= 0x7F) { 
			return $c;
		} else if ($h < 0xC2) { 
			return $c;
		}
		
		if ($h <= 0xDF) {
			$h = ($h & 0x1F) << 6 | (ord($c{1}) & 0x3F);
			$h = "&#" . $h . ";";
			return $h; 
		} else if ($h <= 0xEF) {
			$h = ($h & 0x0F) << 12 | (ord($c{1}) & 0x3F) << 6 | (ord($c{2}) & 0x3F);
			$h = "&#" . $h . ";";
			return $h;
		} else if ($h <= 0xF4) {
			$h = ($h & 0x0F) << 18 | (ord($c{1}) & 0x3F) << 12 | (ord($c{2}) & 0x3F) << 6 | (ord($c{3}) & 0x3F);
			$h = "&#" . $h . ";";
			return $h;
		}
	}
	
	function UTF8entities($content="") { 
		
		if( trim($content) == "" or empty($content) ){
			return $content;
		}
		$content = htmlspecialchars( $content ) ; 
		//if($content=="cname1  & asdsa"){echo $content;exit();}
		$contents = $this->unicode_string_to_array($content);
		$swap = "";
		$iCount = count($contents);
		for ($o=0;$o<$iCount;$o++) {
			$contents[$o] = $this->unicode_entity_replace($contents[$o]);
			$swap .= $contents[$o];
		}
		return mb_convert_encoding($swap,"UTF-8"); 
	}

	
	
	function newsplist () {
		$news_lists = $this->executeAll ('select * from an_news');
		
		$str = '<?xml version="1.0" encoding="UTF-8"?>
        <!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
        <plist version="1.0">
        <array>';
		
		if(!empty($news_lists)) {
			foreach($news_lists as $news_list ) {
				$str .= '<dict>';
				$str .= '<key>forward_url</key>';
				$str .= '<string>'.$this->UTF8entities($news_list['url']).'</string>';
				$str .= '<key>title</key>';
				$str .= '<string>'.$this->UTF8entities($news_list['title']).'</string>';
				$str .= '<key>news_details</key>';
				$str .= '<string>'.$this->UTF8entities($news_list['description']).'</string>';
				$str .= '</dict>';
			}	
		}
		echo $str .= '</array></plist>';
		exit;
	}
	
	function moregamesplist () {
		$games_lists = $this->executeAll ('select * from an_moregames');
		
		$str = '<?xml version="1.0" encoding="UTF-8"?>
        <!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
        <plist version="1.0">
        <array>';
		
		if(!empty($games_lists)) {
			foreach($games_lists as $games_list ) {
				$str .= '<dict>';
				$str .= '<key>forward_url</key>';
				$str .= '<string>'.$this->UTF8entities($games_list['url']).'</string>';
				$str .= '<key>image</key>';
				$str .= '<string>'.$this->UTF8entities($games_list['imagefile']).'</string>';
				$str .= '<key>image_url</key>';
				$str .= '<string>'.$this->UTF8entities(getConfigValue('site_url').'media/moregames/thumb/'.$games_list['imagefile']).'</string>';
				$str .= '<key>Title</key>';
				$str .= '<string>'.$this->UTF8entities($games_list['title']).'</string>';
				$str .= '<key>Description</key>';
				$str .= '<string>'.$this->UTF8entities($games_list['description']).'</string>';
				$str .= '</dict>';
			}	
		}
		echo $str .= '</array></plist>';
		exit;
	}
	
	function addpost () {
		$this->initPageValue();
		if	(!empty($this->data['post']))
			$this->executeAdd( 'yps_posts', array('username','post') );
		echo '<array>';
		echo '<dict>';
		echo "<key>id</key>";
		echo '<string></string>';
		echo '<key>post</key>';
		echo '<string>#*#*#post#*#*#added#*#*#</string>';
		echo '<key>commentsno</key>';
		echo '<string></string>';
		echo '<key>rating</key>';
		echo '<string></string>';
		echo '</dict>';
		echo '</array>';
		exit;	
	}
	
	function viewposts () {
		
		$this->initPageValue();
		$sql_str = "1=1 ";
		if	(!empty($this->data['searchtext']))
				$sql_str .= " and (yps_posts.post like '%".$this->data['searchtext']."%' or yps_comments.comments like '%".$this->data['searchtext']."%')";
		
		if	(!empty($this->data['username'])) {
			$post_lists = $this->executeAll ("SELECT distinct yps_posts.id, yps_posts.* FROM yps_posts LEFT join yps_comments ON yps_posts.id = yps_comments.post_id where $sql_str and yps_posts.username = '".$this->data['username']."'" );
			//echo "SELECT yps_posts.* FROM yps_posts LEFT join yps_comments ON yps_posts.id = yps_comments.post_id where $sql_str and yps_posts.username = '".$this->data['username']."'";
		
		}
		else {
			$post_lists = $this->executeAll ("SELECT distinct yps_posts.id, yps_posts.* FROM yps_posts LEFT join yps_comments ON yps_posts.id = yps_comments.post_id where $sql_str ");
			//echo "SELECT yps_posts.* FROM yps_posts LEFT join yps_comments ON yps_posts.id = yps_comments.post_id where $sql_str ";
			
		}
			
			
			//"SELECT * FROM yps_posts LEFT yps_comments ON yps_posts.id = yps_comments.post_id where yps_posts.post like '%".$this->data['searchtext']."%' or yps_comments.comments like '%".$this->data['searchtext']."%' "; 

		
		
		echo '<array>';
		/*echo "<dict>";
		echo "<key>id</key>";
		echo '<string></string>';
		echo "<key>post</key>";
		echo '<string></string>';
		echo '<key>commentsno</key>';
		echo '<string></string>';
		echo '<key>rating</key>';
		echo '<string></string>';
		echo "</dict>";*/
		foreach($post_lists as $post_list) {
			echo "<dict>";
				echo "<key>id</key>";
				echo "<string>".$post_list['id']."</string>";	
				echo "<key>post</key>";
				echo "<string>".$post_list['post']."</string>";	
			
			
			
			
				echo "<key>commentsno</key>";
				//echo 'select * from yps_comments where post_id =\''.$post_list['id'].'\' ';
				$comments_lists = $this->executeAll ('select * from yps_comments where post_id =\''.$post_list['id'].'\' ');
				echo '<string>'.count($comments_lists).'</string>';
				
				
				
				
				echo "<key>rating</key>";
					$rating_lists = $this->executeAll ('select * from yps_rating where post_id =\''.$post_list['id'].'\' ');
					if (count($rating_lists) >0) {
						$count = 0;
						foreach($rating_lists as $rating_list) {
							$count += $rating_list['rating'];	
						}	
						
						echo "<string>";
						printf ("%.2f",$count / count($rating_lists)) ;
						echo "</string>";
					}
					
					else 
						echo '<string>0</string>';
						
				echo "</dict>";	
			
			
		}
		echo '</array>';	
		exit;
	}
	
	
	
	function addcomments () {
		$this->initPageValue();
		if	(!empty($this->data['comments'])) {
			$this->data ['created'] = time();
			$this->executeAdd( 'yps_comments', array('username','comments','post_id', 'created') );
		}
		//echo '<array>';
		echo '<dict>';
		echo "<key>id</key>";
		echo '<string></string>';
		echo '<key>comment</key>';
		echo '<string>#*#*#comments#*#*#added#*#*#</string>';
		echo "<key>rating</key>";
		$rating_lists = $this->executeAll ('select * from yps_rating where post_id =\''.$this->data['post_id'].'\' ');
		if (count($rating_lists) >0) {
			$count = 0;
			foreach($rating_lists as $rating_list) {
				$count += $rating_list['rating'];	
			}	
			
			echo "<string>";
			printf ("%.2f",$count / count($rating_lists)) ;
			echo "</string>";
		}
		
		else 
			echo '<string>0</string>';
		echo "<key>commentuser</key>";
		echo "<string></string>";	
		echo "<key>created</key>";
		echo "<string></string>";		
		echo '</dict>';
		//echo '</array>';
		exit;	
	}
	
	function viewcomments () {
		
		$this->initPageValue();
		/*
		if	(!empty($this->data['username']))
			$comments_lists = $this->executeAll ('select * from yps_comments where post_id =\''.$this->data['post_id'].'\' ');
		else
			$comments_lists = $this->executeAll ('select * from yps_comments');
		*/
		
		$comments_lists = $this->executeAll ('select * from yps_comments where post_id =\''.$this->data['post_id'].'\' ');
		
		echo '<array>';
		echo "<dict>";
		echo "<key>id</key>";
		echo '<string></string>';
		echo "<key>comment</key>";
		echo '<string></string>';
		echo "<key>rating</key>";
		$rating_lists = $this->executeAll ('select * from yps_rating where post_id =\''.$this->data['post_id'].'\' ');
		if (count($rating_lists) >0) {
			$count = 0;
			foreach($rating_lists as $rating_list) {
				$count += $rating_list['rating'];	
			}	
			
			echo "<string>";
			printf ("%.2f",$count / count($rating_lists)) ;
			echo "</string>";
		}
		
		else 
			echo '<string>0</string>';
			
		echo "<key>commentuser</key>";
		echo "<string></string>";	
		echo "<key>created</key>";
		echo "<string></string>";	
					
		echo "</dict>";
		foreach($comments_lists as $comments_list) {
			echo "<dict>";
				echo "<key>id</key>";
				echo "<string>".$comments_list['id']."</string>";	
				echo "<key>comment</key>";
				echo "<string>".$comments_list['comments']."</string>";	
				echo "<key>username</key>";
				echo "<string>".$comments_list['username']."</string>";	
				echo "<key>created</key>";
				echo "<string>". date('m-d-Y', $comments_list['created'])."</string>";	
			
				echo "<key>rating</key>";
					$rating_lists = $this->executeAll ('select * from yps_rating where post_id =\''.$this->data['post_id'].'\' ');
					if (count($rating_lists) >0) {
						$count = 0;
						foreach($rating_lists as $rating_list) {
							$count += $rating_list['rating'];	
						}	
						
						echo "<string>";
						printf ("%.2f",$count / count($rating_lists)) ;
						echo "</string>";
					}
					
					else 
						echo '<string>0</string>';
			
			echo "</dict>";
			
		}
		echo '</array>';	
		exit;
	}
	
	function addrating () {
		$this->initPageValue();
		//echo "select * from yps_rating where post_id =".$this->data['post_id']." and username = '".$this->data['username']."' ";
		$rating_list = $this->executeOne ("select * from yps_rating where post_id =".$this->data['post_id']." and username = '".$this->data['username']."' ");
		if	(count($rating_list) >0) {
			//echo '<array>';
			echo '<dict>';
			echo "<key>id</key>";
			echo '<string></string>';
			echo '<key>comment</key>';
			echo '<string>#*#*#rating#*#*#not added#*#*#</string>';
			
			echo "<key>rating</key>";
			$rating_lists = $this->executeAll ('select * from yps_rating where post_id =\''.$this->data['post_id'].'\' ');
			if (count($rating_lists) >0) {
				$count = 0;
				foreach($rating_lists as $rating_list) {
					$count += $rating_list['rating'];	
				}	
				
				echo "<string>";
				printf ("%.2f",$count / count($rating_lists)) ;
				echo "</string>";
			}
			
			else 
				echo '<string>0</string>';
			
			
			echo '</dict>';
			//echo '</array>';
		}
		else {
		
			$this->executeAdd( 'yps_rating', array('post_id','rating','username') );
			
			//echo '<array>';
			echo '<dict>';
			echo "<key>id</key>";
			echo '<string></string>';
			echo '<key>comment</key>';
			echo '<string>#*#*#rating#*#*#added#*#*#</string>';
			
			echo "<key>rating</key>";
			$rating_lists = $this->executeAll ('select * from yps_rating where post_id =\''.$this->data['post_id'].'\' ');
			if (count($rating_lists) >0) {
				$count = 0;
				foreach($rating_lists as $rating_list) {
					$count += $rating_list['rating'];	
				}	
				
				echo "<string>";
				printf ("%.2f",$count / count($rating_lists)) ;
				echo "</string>";
			}
			
			else 
				echo '<string>0</string>';
			
			
			echo '</dict>';
			//echo '</array>';
			
		}
		exit;	
	}
	
	function showrating () {
		
		$this->initPageValue();
		
		//echo '<array>';
		echo '<dict>';
		echo "<key>id</key>";
		echo '<string></string>';
		echo '<key>comment</key>';
		echo '<string></string>';
		
		echo "<key>rating</key>";
		$rating_lists = $this->executeAll ('select * from yps_rating where post_id =\''.$this->data['post_id'].'\' ');
		if (count($rating_lists) >0) {
			$count = 0;
			foreach($rating_lists as $rating_list) {
				$count += $rating_list['rating'];	
			}	
			
			echo "<string>";
			printf ("%.2f",$count / count($rating_lists)) ;
			echo "</string>";
		}
		
		else 
			echo '<string>0</string>';
		
		
		echo '</dict>';
		//echo '</array>';
		
		exit;
		
	}
	
	
	
	function add(){
		unsetSessionValue('_formValidation');
		
		$this->initPageValue();
		$is_auth = chkAuthentication ();
		if ( !$is_auth ){
			 header ("location:".urlForAdmin('user/unauthorize'));
			 exit();
		}
		$this->chkAuthorization ($this->module_name);
		
		$validation_rules = $this->getFromInfo ($this->module_name, 'Form.xml');
		//print_r ($this->data);
		//exit();
		if ($this->data['submit'] == 'Submit'){
			
			$valid = $this->protocols['formvalidation_instance']->isvalid($this->data, $validation_rules);
												
			if($valid){
			
				$this->executeAdd( $this->table_name, array('name','body','active', 'meta_description','meta_keyword','page_title') );
				
				header ("location:".urlForAdmin($this->module_name.'/manage'));
				exit();
			}
		}
		
		$this->protocols['content_instance']->setValue('validation_rules', $validation_rules);
		//getConfigValue('dbhandler')->db->Close();
		$value = $this->parseTemplate($this->module_name, 'add');
		unsetSessionValue('_formValidation');
		return $value;
	}
	
	function edit(){
		unsetSessionValue('_formValidation');
		
		$this->initPageValue();
		$is_auth = chkAuthentication ();
		if ( !$is_auth ){
			 header ("location:".urlForAdmin('user/unauthorize'));
			 exit();
		}
		$this->chkAuthorization ($this->module_name);
		
		$validation_rules = $this->getFromInfo ($this->module_name, 'Form.xml');
		
		if (empty ($this->data['perpage']) )
			$this->data['perpage'] = getConfigValue('per_page');
			
		$this->paging_controlling_field = array('perpage','order_by','order_type','current_page');
		$page_controlling_value = $this->renderPageControllingLink();
		$this->protocols['content_instance']->setValue('page_controlling_value',  $page_controlling_value);
		
		if ($this->data['update'] == 'Update'){
			
			$valid = $this->protocols['formvalidation_instance']->isvalid($this->data, $validation_rules);
												
			if($valid){
				$this->executeEdit( $this->table_name, array('name', 'body','active','meta_description','meta_keyword','page_title') );
				header ("location:".urlForAdmin($this->module_name.'/manage').'?'.html_entity_decode($page_controlling_value));
				exit();
			}
		}
		
		$edit_info = $this->executeOne('select * from '.$this->table_name.' where id='.$this->data['id']);
		//print_r ($edit_info);
		
		$this->protocols['content_instance']->setValue('edit_info', $edit_info);
		
		$this->protocols['content_instance']->setValue('validation_rules', $validation_rules);
		//getConfigValue('dbhandler')->db->Close();
		$value = $this->parseTemplate($this->module_name, 'edit');
		unsetSessionValue('_formValidation');
		return $value;
	}
	
	/*function exportxls(){
		//$filename = "news.xls"
		//$this->executeExportxls ($filename);
	}*/
	
		
	function manage(){
		//exit();
		
		$cuser_role = getSessionValue ('user_role');
		
		$this->initPageValue();
		$is_auth = chkAuthentication ();
		if ( !$is_auth ){
			 header ("location:".urlForAdmin('user/unauthorize'));
			 exit();
		}
		$this->chkAuthorization ($this->module_name);
		
		$validation_rules = $this->getFromInfo ($this->module_name, 'Filter.xml');
		
		if (empty ($this->data['perpage']) )
			$this->data['perpage'] = getConfigValue('per_page');
		
		$where_sql = '';
						
		$this->paging_controlling_field = array('perpage','order_by','order_type','current_page');
		$table_data =  $this->executeManage ($this->table_name, $this->data['perpage'], $where_sql );
		$this->protocols['content_instance']->setValue('table_data', $table_data);
		
		$this->protocols['content_instance']->setValue('page_controlling_value',  $this->renderPageControllingLink());	
		$this->protocols['content_instance']->setValue('perpage', $this->data['perpage']);
		
		$name_header = $this->renderingOrder ($this->module_name,'content Name', 'name');
					
		$this->protocols['content_instance']->setValue('validation_rules', $validation_rules);
		
		$this->protocols['content_instance']->setValue('table_header_column', array('ID', $name_header,'content','Active','Edit', 'Delete' ) );
		
		if ($cuser_role['title'] == 'admin')
			$this->protocols['content_instance']->setValue('table_header_column', array('ID', $name_header,'content','Active','Edit', 'Delete' ) );
		else
		$this->protocols['content_instance']->setValue('table_header_column', array('ID', $name_header,'content','Active','Edit' ) );
			
		$this->protocols['content_instance']->setValue('table_row_column', array('id', 'name', 'body') );
		
		//$value = $this->parseTemplate($this->module_name, 'manage');
		if ($cuser_role['title'] == 'admin')
			$value = $this->parseTemplate($this->module_name, 'manage');
		else
			$value = $this->parseTemplate($this->module_name, 'manage_alt');
			
		return $value;
	}


	function getpostcode ($filter_name){
		
		$this->initRoutingValue();
		$this->initPageValue(); // rturn all the post value as its field name
			
			
		$query = "SELECT * FROM ".getConfigValue('table_prefix')."postcode where active = 'Active' and district_area = '".$this->data['district_area_id']. "'";
		//echo $query; exit;
		$postcode_lists = $this->executeAll($query);
		
		
		$str = '';
		if(count($postcode_lists) > 0) {
			foreach($postcode_lists as $postcode) {
				//print_r($postcode_lists); exit;
				$str .= "<option value = ".$postcode['id'].">".$postcode['postcode_area']."</option>";	
			}
		}
		else
			$str .= "<option value = ''>-- No Postcode Area is Available --</option>";
		
		echo $str;
		exit;
	}	//	End of getpostcode
	
	function postcode ($filter_name){
		
		$this->initRoutingValue();
		$this->initPageValue(); // rturn all the post value as its field name
			
			
		$query = "SELECT * FROM ".getConfigValue('table_prefix')."postcode where active = 'Active' and district_area = '".$this->data['district_area_id']. "'";
		//echo $query; exit;
		$postcode_lists = $this->executeAll($query);
		
		
		//$str = '';
		
		if(count($postcode_lists) > 0) {
			$str = "<option value = '0'>------------------------ All ----------------------</option>";
			foreach($postcode_lists as $postcode) {
				//print_r($postcode_lists); exit;
				if(!empty($this->data['postcode'])) {
					if($postcode['id']== $this->data['postcode'])
						$str .= "<option selected='selected' value = ".$postcode['id'].">".$postcode['postcode_area']."</option>";
					else
						$str .= "<option value = ".$postcode['id'].">".$postcode['postcode_area']."</option>";
				}
				else
					$str .= "<option value = ".$postcode['id'].">".$postcode['postcode_area']."</option>";	
			}
		}
		else
			$str .= "<option value = ''>-- No Postcode Area is Available --</option>";
		
		echo $str;
		exit;
	}	//	End of postcode
	
	//cms_itemgenerator
	function itemgenerator ($filter_name){
		$this->initRoutingValue();
		$this->initPageValue(); 
		
		
		$cat = $this->executeOne("SELECT id FROM ".getConfigValue('table_prefix')."category where active = 'Active' and title = '". utf8_decode($this->data['category']). "'");
		
		$query = "SELECT * FROM ".getConfigValue('table_prefix')."item where active = 'Active' and category = ". $cat['id'];
		$item_lists = $this->executeAll($query);
		$this->protocols['content_instance']->setValue('item_lists', $item_lists);
		//print_r($item_lists); exit;		

		$value = $this->parseTemplate($this->module_name, 'cms_itemgenerator','');
		return $value;
	}	//	End of cms_itemgenerator
	
	//cms_ypsplistgenerator
	function ypsplistgenerator ($filter_name){
		$this->initRoutingValue();
		$this->initPageValue(); 
		
		$query = "SELECT * FROM ".getConfigValue('table_prefix')."category where active = 'Active'";
		$category_lists = $this->executeAll($query);
		$this->protocols['content_instance']->setValue('category_lists', $category_lists);
		
		$query = "SELECT * FROM ".getConfigValue('table_prefix')."item where active = 'Active'";
		$item_lists = $this->executeAll($query);
		$this->protocols['content_instance']->setValue('item_lists', $item_lists);
		//print_r($item_lists); exit;		

		
				
			
		$value = $this->parseTemplate($this->module_name, 'cms_plistgenerator','');
		return $value;
	}	//	End of cms_ypsplistgenerator
	
	//cms_tagitemgenerator
	function tagitemgenerator ($filter_name){
		$this->initRoutingValue();
		$this->initPageValue(); 

		$query = 'SELECT * FROM '.getConfigValue('table_prefix'). 'item where active = "Active" and tag like '. '"%'. utf8_decode($this->data['tag']). '%"';
		$item_lists = $this->executeAll($query);
		$this->protocols['content_instance']->setValue('item_lists', $item_lists);
		//print_r($item_lists); exit;		

		$value = $this->parseTemplate($this->module_name, 'cms_tagplistgenerator', '');
		return $value;
	}	// End of cms_tagitemgenerator
	
	//cms_forecast
	function forecast ($filter_name){
		$this->initRoutingValue();
		$this->initPageValue(); 
	
		$value = $this->parseTemplate($this->module_name, 'cms_senegalforecast', '');
		return $value;
	}	//	end of cms_forecast
	
}

?>