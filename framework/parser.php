<?php
abstract class parser {
	 
	var $data;
	var $routing_data;
	var $use_template;
	var $paging_controlling_field;
	
	var $module_name = '';
	var $table_name = '';
		
	var $CodigoError;
	var $TextoError;
	
	var $error_string;			
	//protected function add();
	//protected function edit();
	//protected function manage();
		
	//abstract protected function getFilterOutPut($filter);
	
	var $ErrorDetected = false;
	var $_Err = '';
	var $_Msg = '';	
	
	function getFromInfo($module, $validation_file){
		
		$xml = io_file_get_contents(getConfigValue('module').$module."/".$validation_file);
		$match_offset = 0;
		$count = 0;
		$match = 0;
		//print_r ($xml);
		
		$validation_rules = array();
		while (1){
									
			$field_array = xml_get_value('field', $xml, $match_offset);
			$next_field_array = xml_get_value('field', $xml, $field_array['offset']);
			if ( ($count > 0 && empty($match_offset)) || empty($field_array) )
				break;
			//print_r ($field_array);
							
			$label_array = xml_get_value('label', $xml, $field_array['offset']);	
			if ($label_array['offset'] > $next_field_array['offset'] && !empty ($next_field_array['offset']) )
				$label_array = reset_value();
					
			$name_array = xml_get_value('name', $xml, $field_array['offset']);	
			if ($name_array['offset'] > $next_field_array['offset'] && !empty ($next_field_array['offset']) )
				$name_array = reset_value();
					
			$type_array = xml_get_value('type', $xml, $field_array['offset']);	
			if ($type_array['offset'] > $next_field_array['offset'] && !empty ($next_field_array['offset']) )
				$type_array = reset_value();
			
			$value_array = xml_get_value('value', $xml, $field_array['offset']);	
			if ($value_array['offset'] > $next_field_array['offset'] && !empty ($next_field_array['offset']) )
				$value_array = reset_value();
										
			$class_array = xml_get_value('class', $xml, $field_array['offset']);	
			if ($class_array['offset'] > $next_field_array['offset'] && !empty ($next_field_array['offset']) )
				$class_array = reset_value();
			
			$message_array = xml_get_value('message', $xml, $field_array['offset']);	
			if ($message_array['offset'] > $next_field_array['offset'] && !empty ($next_field_array['offset']) )
				$message_array = reset_value();	
						
			$required_array = xml_get_value('required', $xml, $field_array['offset']);	
			if ($required_array['offset'] > $next_field_array['offset'] && !empty ($next_field_array['offset']) )
				$required_array = reset_value();
			
			$rule_array = xml_get_value('rule', $xml, $field_array['offset']);	
			if ($rule_array['offset'] > $next_field_array['offset'] && !empty ($next_field_array['offset']) )
				$rule_array = reset_value();
			
			$maxlength_array = xml_get_value('maxlength', $xml, $field_array['offset']);	
			if ($maxlength_array['offset'] > $next_field_array['offset'] && !empty ($next_field_array['offset']) )
				$maxlength_array = reset_value();
				
			$minlength_array = xml_get_value('minlength', $xml, $field_array['offset']);	
			if ($minlength_array['offset'] > $next_field_array['offset'] && !empty ($next_field_array['offset']) )
				$minlength_array = reset_value();	
			
			$equal_array = xml_get_value('equalto', $xml, $field_array['offset']);	
			if ($equal_array['offset'] > $next_field_array['offset'] && !empty ($next_field_array['offset']) )
				$equal_array = reset_value();
				
			$custom_array = xml_get_value('custom', $xml, $field_array['offset']);	
			if ($custom_array['offset'] > $next_field_array['offset'] && !empty ($next_field_array['offset']) )
				$custom_array = reset_value();
			
			$validtype_array = xml_get_value('validtype', $xml, $field_array['offset']);	
			if ($validtype_array['offset'] > $next_field_array['offset'] && !empty ($next_field_array['offset']) )
				$validtype_array = reset_value();
			
			$readonly_array = xml_get_value('readonly', $xml, $field_array['offset']);	
			if ($readonly_array['offset'] > $next_field_array['offset'] && !empty ($next_field_array['offset']) )
				$readonly_array = reset_value();
			
			$width_array = xml_get_value('width', $xml, $field_array['offset']);	
			if ($width_array['offset'] > $next_field_array['offset'] && !empty ($next_field_array['offset']) )
				$width_array = reset_value();
				
			$height_array = xml_get_value('height', $xml, $field_array['offset']);	
			if ($height_array['offset'] > $next_field_array['offset'] && !empty ($next_field_array['offset']) )
				$height_array = reset_value();	
							
			$tmp_rules = array();
			
			$validation_rules[$name_array	['value']] = array(
																											'name'	  	=> $name_array['value'],
																											'label' 		=> $label_array['value'],
																											'class' 		=> $class_array['value'],
																											'type' 			=> $type_array['value'],
																											'rule' 			=> $rule_array['value'] ,
																											'required'	=> $required_array['value'] ,
																											'message' 	=> $message_array['value'],
																											'equalto' 	=> $equal_array['value'],
																											'custom' 		=> $custom_array['value'],
																											'maxlength'	=> $maxlength_array['value'],
																											'minlength'	=> $minlength_array['value'],
																											'validtype' => explode(',', $validtype_array['value']),
																											'readonly'  => $readonly_array['value'],
																											'width'  => $width_array['value'],
																											'height'  => $height_array['value']
																										);
		
			
			//exit();
			
			//$this->optionvalue_role();
			if (empty ($readonly_array['value']) ){
				unset($validation_rules[$name_array	['value']]['readonly']);
			}
			
			if ($type_array['value'] == 'checkbox'){
				//$validation_rules[$name_array	['value']]['value'] = 1;
			}
			if ($type_array['value'] == 'select' || $type_array['value'] == 'selectgroup'){
				$target_action = 'optionvalue_'.$name_array['value'];
				$target_action_value = $this->$target_action();
				//print_r ($target_action);
				$this->protocols['content_instance']->setValue($target_action, $target_action_value);
			}
			
			if ($type_array['value'] == 'radio'){
				$target_action = 'radiovalue_'.$name_array['value'];
				$target_action_value = $this->$target_action();
				//print_r ($target_action);
				$this->protocols['content_instance']->setValue($target_action, $target_action_value);
			}
			
			$match_offset = $field_array['offset'];
			$count++;		
		}
		//print_r ($validation_rules);
		return $validation_rules;
		
	}
	
	function getFilterOutPut($action) {
		//call_user_func($this->filter1);
		$reserve_module = getReservedModuleList();
		//echo $this->module_name;
		//print_r ($reserve_module);
		
		if ($this->chkModule($this->module_name) != true && !in_array ($this->module_name, $reserve_module) )
			return 'please install '.$this->module_name. ' module first';	
		
		$filter_name = $this->module_name.'_'.$action;
		$table_data = $this->$action($filter_name);
								
		if ($this->use_template == 1){
			//$this->protocols['content_instance']->setValue('title', $title);
			$this->protocols['content_instance']->setValue('table_data', $table_data);
			$value = $this->parseTemplate($this->module_name, $filter_name, '');
			return $value;
		}
		else{
			$value = $table_data;
		}
		
		return $value;
	}
		
	function is_comment($text){
		if ($text != ""){
			$fL = $text[0];
			$sL = $text[1];
			switch($fL){
				case "#":
					$text = "";
					break;
				case "/":
					if ($sL == "*")
						$text = "";
					break;
				case "-":
					if ($sL == "-")
						$text = "";
					break;
					
			}
		}
		return $text;
	}
	
	function importxls(){
		
		$this->initPageValue();
		$this->chkAuthorization ($this->module_name);
		$validation_rules = $this->getFromInfo ('common', 'Import.xml');
		
		//print_r ($this->data['importfile']);
		 //$path_info = pathinfo($this->data['importfile']['name']);
     //echo $path_info['extension'];


		if ( $this->data['import'] == 'Import File'){
			
			$valid = $this->protocols['formvalidation_instance']->isvalid($this->data, $validation_rules);
			if ($valid){
				//echo '123';
				$tmp_upload_name = getConfigValue('media').$this->module_name.'/';
				$this->protocols['filemanager_instance']->upload($this->data['importfile'], $tmp_upload_name, array("application/octet-stream") );
										
				$xls_file = $this->protocols['xlsmanager_instance']->readXls($tmp_upload_name.$this->data['importfile']['name']);	
				$xls_data = $xls_file->sheets[0]['cells'];
				
				if (count ($xls_data) ){
					
					$tmp_table_colum =  array_values($xls_data[1]) ;
					//print_r ($tmp_table_colum);
					
					for ($i=2; $xls_data[$i]; $i++){
						
						$record = array();
						for ($j=1; $tmp_table_colum[$j]; $j++){
							//echo $xls_data[$i][$j+1];
							if ( !empty($xls_data[$i][$j+1]) )
								$record[$tmp_table_colum[$j]] = $xls_data[$i][$j+1];
						}
						
						$chk_record = $this->numOfRecord ("Select * From ".$this->table_name." Where id=".$xls_data[$i][1]);
						if ($chk_record > 0){
							getConfigValue('dbhandler')->db->AutoExecute($this->table_name, $record, 'UPDATE',  'id='.$xls_data[$i][1]);
						}	
						else{
							$record[$tmp_table_colum[0]] = $xls_data[$i][1];
							getConfigValue('dbhandler')->db->AutoExecute($this->table_name, $record, 'INSERT');
						}	
						$err .= $this->getError();
					}// end for
				
					if ( empty($err) )
						$msg = 'File Imported Successfully';
				}
				
				$this->protocols['content_instance']->setValue('err', $err);
				$this->protocols['content_instance']->setValue('msg', $msg);
			}// end valid
			
			//print_r ($xls_value);
			
		}// end if	
		
		$this->protocols['content_instance']->setValue('validation_rules', $validation_rules);
		$value = $this->parseTemplate($this->module_name, 'import');
		return $value;
	}
	
	function exportxls(){
		
		$filename = $this->module_name.'('.date('m-d-Y', time()).').xls'; 
		
		$this->initPageValue();
		$where_sql = '';
		if ( !empty($this->data[search_value]) )
			 $where_sql = " title like '%{$this->data[search_value]}%' and description like '%{$this->data[search_value1]}%' ";
		
		$sql = 'select * from '.$this->table_name;
		
		if ( !empty ($where_sql) )
			$sql = $sql.' WHERE '.$where_sql;
		
		if ($this->data['order_type'] && $this->data['order_by'])
			$sql .= ' Order By '.$this->data['order_by'].' '.$this->data['order_type'];	
		
		$num_rows = $this->numOfRecord ($sql);
		
		if ($num_rows > 0){
			
			$table_data = $this->executeAll($sql);
			$tmp_table_colum = ( array_keys($table_data[0]) );
			$table_colum = array();
			
			
			//print_r ($table_colum);
			$this->protocols['xlsmanager_instance']->initXls($filename);
			$heading  = $this->protocols['xlsmanager_instance']->workbook->addformat(array(
																					bold    => 1,
																					color   => 'blue',
																					size    => 12,
																					
																					));
			
			$xls_row = 0;
			$xls_col = 0;
			foreach ($tmp_table_colum as $tmp_name){
				if (!is_numeric ($tmp_name) ){
					$table_colum[] = $tmp_name;
					$this->protocols['xlsmanager_instance']->worksheet->write($xls_row, $xls_col, $tmp_name, $heading);
					$xls_col++;
				}
			}
			
			foreach ($table_data as $table_row){
				$xls_row++;
				$xls_col = 0;	
				foreach ($table_colum as $col){
					$this->protocols['xlsmanager_instance']->worksheet->write($xls_row, $xls_col, $table_row[$col]);
					$xls_col++;
				}
			}
			$this->protocols['xlsmanager_instance']->downloadXls($filename);
			
		}
				
	}
	
	//
	function export($table_name='', $db_name='', $nodata = false,$nostruct = false) {

        // Set content-type and charset
        #header ('Content-Type: text/html; charset=iso-8859-1');

        // Connect to database
				//getConfigValue('dbhandler')->db->Execute
        
				/*$db = @mysql_select_db($database);

        if (!empty($db)) {

            // Get all table names from database
            $c = 0;
            $result = mysql_list_tables($database);
            for($x = 0; $x < mysql_num_rows($result); $x++) {
                $table = mysql_tablename($result, $x);
                if (!empty($table)) {
                    $arr_tables[$c] = mysql_tablename($result, $x);
                    $c++;
                }
            }
						*/
						/*$db_name = 'rcms';
						if ( !empty($db_name) ){
							$c = 0;
							$result = mysql_list_tables($database);
							print_r ($result);
							for($x = 0; $x < mysql_num_rows($result); $x++) {
									$table = mysql_tablename($result, $x);
									if (!empty($table)) {
											$arr_tables[$c] = mysql_tablename($result, $x);
											$c++;
									}
							}
						}
						exit();*/
						
		if (empty ($db_name))
			$arr_tables = array($table_name);
		// List tables
		
		$dump = '';
		$dump .= "-- \n";
		$dump .= '-- MySQL DATABASE DUMPER. Copyright Cybernetikz &reg;\n\n '."\n";
		$dump .= "-- \n\n";
		
		for ($y = 0; $y < count($arr_tables); $y++){

			// DB Table name
			$table = $arr_tables[$y];
			if($nostruct == false){

				$structure .= "-- ------------------------------------------------ \n";
				$structure .= "-- Dropping  table `{$table}`  >>> \n";
				$structure .= "DROP TABLE IF EXISTS `{$table}`; \n";
				// Structure Header
				$structure .= "-- ------------------------------------------------ \n";
				$structure .= "-- Table structure for table `{$table}` started >>> \n";

				// Dump Structure
				
				// TRUNCATE TABLE `news`  
				$structure .= "CREATE TABLE IF NOT EXISTS `{$table}` (\n";
				
				//$result = mysql_db_query($database, "SHOW FIELDS FROM `{$table}`");
				$query = "SHOW FIELDS FROM `{$table}`";
				
				$result = getConfigValue('dbhandler')->db->Execute($query) ;
														
				//while($row = mysql_fetch_object($result)) {
				while ( $row = $result->FetchNextObject() ) {
						
					//print_r ($row);
					//echo $row->FIELD;
					//exit();											
					$structure .= "  `{$row->FIELD}` {$row->TYPE}";
					if($row->Default != 'CURRENT_TIMESTAMP'){
						$structure .= (!empty($row->DEFAULT)) ? " DEFAULT '{$row->DEFAULT}'" : false;
					}else{
						$structure .= (!empty($row->DEFAULT)) ? " DEFAULT {$row->DEFAULT}" : false;
					}
					$structure .= ($row->NULL != "YES") ? " NOT NULL" : false;
					$structure .= (!empty($row->EXTRA)) ? " {$row->EXTRA}" : false;
					$structure .= ",\n";

				}

				$structure = ereg_replace(",\n$", "", $structure);
				// Save all Column Indexes in array
				unset($index);
				//$result = mysql_db_query($database, "SHOW KEYS FROM `{$table}`");
				$result = getConfigValue('dbhandler')->db->Execute ("SHOW KEYS FROM `{$table}`");
				//while($row = mysql_fetch_object($result)) {
				while ( $row = $result->FetchRow() ) {
						
					//print_r ($row);
					if (($row[Key_name] == 'PRIMARY') AND ($row[Index_type] == 'BTREE')) {
							$index['PRIMARY'][$row[Key_name]] = $row[Column_name];
					}

					if (($row[Key_name] != 'PRIMARY') AND ($row[Non_unique] == '0') AND ($row[Index_type] == 'BTREE')) {
							$index['UNIQUE'][$row[Key_name]] = $row[Column_name];
					}

					if (($row[Key_name] != 'PRIMARY') AND ($row[Non_unique] == '1') AND ($row[Index_type] == 'BTREE')) {
							$index['INDEX'][$row[Key_name]] = $row[Column_name];
					}

					if (($row[Key_name] != 'PRIMARY') AND ($row[Non_unique] == '1') AND ($row[Index_type] == 'FULLTEXT'))
					{
							$index['FULLTEXT'][$row[Key_name]] = $row[Column_name];
					}

				}
					
				//print_r ($index);	
				// Return all Column Indexes of array
				if (is_array($index)) {
					foreach ($index as $xy => $columns) {

						$structure .= ",\n";

						$c = 0;
						foreach ($columns as $column_key => $column_name) {

								$c++;

								$structure .= ($xy == "PRIMARY") ? "  PRIMARY KEY  (`{$column_name}`)" : false;
								$structure .= ($xy == "UNIQUE") ? "  UNIQUE KEY `{$column_key}` (`{$column_name}`)" : false;
								$structure .= ($xy == "INDEX") ? "  KEY `{$column_key}` (`{$column_name}`)" : false;
								$structure .= ($xy == "FULLTEXT") ? "  FULLTEXT `{$column_key}` (`{$column_name}`)" : false;

								$structure .= ($c < (count($index[$xy]))) ? ",\n" : false;

						}

					}

				}

				$structure .= "\n)";

			}
			
			$query = "SHOW TABLE STATUS WHERE Name = '{$table}'";
			$result = getConfigValue('dbhandler')->db->Execute($query) ;
			//print_r ($result);
			while ( $row = $result->FetchRow() ) {
				//print_r ($row);
				if ( !empty ($row[Engine]) )
					$structure .= ' ENGINE='.$row[Engine];
				
				if ( !empty ($row[Auto_increment]) )
					$structure .= ' AUTO_INCREMENT='.$row[Auto_increment];	
					
			}
				
			//SHOW TABLE STATUS WHERE Name = 'news' 
			//ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;
				
			$structure .= ";\n\n-- Table structure for table `{$table}` finished <<< \n";
			$structure .= "-- ------------------------------------------------- \n";
			
			//$structure .= "TRUNCATE TABLE `{$table}`;\n\n";
			// Dump data
			if($nodata == false) {

				$structure .= " \n\n";
			
				//$result     = mysql_query("SELECT * FROM `$table`");
				$result = getConfigValue('dbhandler')->db->Execute ("SELECT * FROM `$table`");	
				//$num_rows   = mysql_num_rows($result);
				$num_rows = $result->RecordCount();
				//$num_fields = mysql_num_fields($result);
				$num_fields = $result->FieldCount();
				//echo $num_fields; exit(); 
				
				$sql_data .= "-- -------------------------------------------- \n";
				$sql_data .= "-- Dumping data for table `$table` started >>> \n";

				for ($i = 0; $i < $num_rows; $i++) {

					//$row = mysql_fetch_object($result);
					$row = $result->FetchRow();
					//print_r ($row);
					$sql_data .= "INSERT INTO `$table` (";

					// Field names
					for ($x = 0; $x < $num_fields; $x++) {

						//$field_name = mysql_field_name($result, $x);
						$field_name = $result->FetchField ($x);
						//print_r ($field_name);exit();
						
						//$sql_data .= "`{$field_name}`";
						$sql_data .= "`{$field_name->name}`";
						//exit();
						
						$sql_data .= ($x < ($num_fields - 1)) ? ", " : false;

					}

					$sql_data .= ") VALUES (";

					// Values
					for ($x = 0; $x < $num_fields; $x++) {
					
						//$field_name = mysql_field_name($result, $x);
						$field_name = $result->FetchField ($x);
						//$sql_data .= "'" . str_replace('\"', '"', mysql_escape_string($row->$field_name)) . "'";												
						$tmp_val = $field_name->name;	
						//exit();
						//$sql_data .= "'" . str_replace('\"', '"', mysql_escape_string($row[$tmp_val])) . "'";
						$sql_data .= "'" . addslashes($row[$tmp_val]). "'";
						$sql_data .= ($x < ($num_fields - 1)) ? ", " : false;

					}

					$sql_data.= ");\n";
							
				}
				
				$sql_data .= "-- Dumping data for table `$table` finished <<< \n";
				$sql_data .= "-- -------------------------------------------- \n\n";
					
				$sql_data .= "\n";
			}
			 
		}
		$dump .= $structure . $sql_data;
		
		//}
		$backup_file = getConfigValue ('module').$table_name.'/backup/'.date("F j, Y, g.i a").'.sql';
		$fp = fopen ($backup_file, 'w+');
		fclose ($fp);
		file_put_contents($backup_file, $dump); 
		
		//return $dump;

  }
		
	//retrieving the vars
	//Processing and importing of the SQL statements
	function import ($sqlpath) 
	{   
		
		$this->initPageValue();
   	//opening and reading the .sql file
		$f = fopen($sqlpath,"r+");
		$sqlFile = fread($f, filesize($sqlpath));
		// processing and parsing the content
		$sqlFile = str_replace("\r","%BR%",$sqlFile);
		$sqlFile = str_replace("\n","%BR%",$sqlFile);
		$sqlFile = str_replace("%BR%%BR%","%BR%",$sqlFile);
		$sqlArray = explode('%BR%', $sqlFile);
		$sqlArrayToExecute;
		foreach ($sqlArray as $stmt) 
		{
			$stmt = $this->is_comment($stmt);
			if ($stmt != '')
				$sqlArrayToExecute[] = $stmt;
		}
		$sqlFile = implode("%BR%",$sqlArrayToExecute);
		unset($sqlArrayToExecute);
		$sqlArray = explode(';%BR%', $sqlFile);
		unset($sqlFile);
		//making a loop with all the valid statements
		foreach ($sqlArray as $stmt){
			$stmt = str_replace("%BR%"," ",$stmt);
			$stmt = trim($stmt);
			//$sqlArrayToExecute[] = $stmt;
			// making the query
			//$result = mysql_query($stmt,$this->con);
			getConfigValue('dbhandler')->db->Execute($stmt);
			if (getConfigValue('dbhandler')->db->ErrorNo() > 0)
			{
				// if we aren't connected throw an error
				$this->ErrorDetected = true;
				//$this->CodigoError[] = mysql_errno($this->con);
				//$this->TextoError[] = mysql_error($this->con);
				
				$this->CodigoError[] = getConfigValue('dbhandler')->db->ErrorNo();
				$this->TextoError[] = getConfigValue('dbhandler')->db->ErrorMsg();
			}
		}
		
		//print_r( $this->TextoError );
    if ($this->ErrorDetected == false){
			//print_r (getConfigValue('dbhandler')->db);
			//echo getConfigValue('dbhandler')->db->database;
			$sql = 'RENAME TABLE '.getConfigValue('dbhandler')->db->database.'.'.$this->data['name'].' TO '.getConfigValue('dbhandler')->db->database.'.'.getConfigValue('table_prefix').$this->data['name'];
			
			//echo $sql;
			//exit();
			
			$sql_status = getConfigValue('dbhandler')->db->Execute($sql);
			
			if (getConfigValue('dbhandler')->db->ErrorNo() > 0){
				//$_err .= 'Error on adding prefix before table '.$row['Name'].'.<br/>';
				$this->CodigoError[] = getConfigValue('dbhandler')->db->ErrorNo();
				$this->TextoError[] = getConfigValue('dbhandler')->db->ErrorMsg();
			}
			
		} 	
     	 
	}//End of importing
	
	//Controlling and displaying the errors on the process
	function showError () 
	{	
  	 	if ($this->ErrorDetected == false)
   		{
				$OutPut [0] =  true;
			}
			else{
				$OutPut [0] =  false;   		
				$OutPut [1] = $this->CodigoError;
				$OutPut [2] = $this->TextoError;
   		}

			return $OutPut;
	}
	/*
	protected function getError(){
		
		$this->_Err = '';
		
		if (getConfigValue('dbhandler')->db->ErrorNo() > 0)
			{								
				$this->_Err .= getConfigValue('dbhandler')->db->ErrorNo().'  ';
				$this->_Err .= getConfigValue('dbhandler')->db->ErrorMsg();
			}	
	}
	*/ 	  
	protected function escape_string($str) {
		
		$value = "";
		// Check if this function exists
		if ($str) {
			if(function_exists('mysql_real_escape_string')) {
				$value = mysql_real_escape_string($str);
			} else {
				$value = mysql_escape_string($str);
			}
		}	
		return $value;
	}
	
	protected function escape_string_alt ($val){
		$tmp_val;
		
		$gpc = ini_get("magic_quotes_gpc");
		
		if ($gpc == 1){
			$tmp_val = stripslashes($val);
		}
		else{
			$tmp_val = $val;
		}
		
		return $tmp_val;
	}
	
	protected function initRoutingValue(){
		$this->routing_data =  getConfigValue ('perform_data'); // parse data from seo friendly url
	}
	
	
	protected function initPageValue(){
		
		$gpc = ini_get("magic_quotes_gpc");
		
		if ( count($_GET) >0 )
		{
			foreach ($_GET as $key=>$val){
				if ($gpc == 1){
					if ( is_array ($val) && $val )
						$this->data[$key] = $val;	
					else	
						$this->data[$key] = stripslashes($val);
				}
				else{
					$this->data[$key] = $val;
				}
			}
			
		}
		
		if ( count($_POST) >0 )
		{
			foreach ($_POST as $key=>$val){
				//if ($key == 'chk_id')
					//print_r ($val);
				if ($gpc == 1){
					if ( is_array ($val) && $val )
						$this->data[$key] = $val;	
					else	
						$this->data[$key] = stripslashes($val);
				}
				else{
					$this->data[$key] = $val;
				}
				
			}
		}
		
		if ( count($_FILES) >0 )
		{			
			foreach ($_FILES as $key=>$val){
				$this->data[$key] = $val;
				//print_r ($val);
				
				if ($gpc == 1)
					$this->data[$key]['name'] = stripslashes($this->data[$key]['name']);
			}
			//echo '---------------------';
			//print_r ($this->data['newsfile']);
			//exit();
		}
				
	}
	
	function createQuery ($filter){
		
		$xml = io_file_get_contents(getConfigValue('module').$this->module_name."/filter/$filter.xml");
				
		$usetemplate_array = xml_get_value('usetemplate', $xml, 0);
		$limit_array = xml_get_value('limit', $xml, $usetemplate_array['offset']);
		//print_r ($limit_array);
		$this->use_template  = $usetemplate_array['value'];
					
		$sql_array = xml_get_value('sql', $xml, $perpage_array['offset']);
		
		$select_array = xml_get_value('select', $xml, $sql_array['offset']);
		if ( empty($select_array['value']) )
			$select_array['value'] = '*';
		
		$table_array = xml_get_value('table', $xml, $select_array['offset']);
		$where_array = xml_get_value('where', $xml, $table_array['offset']);
		$orderby_array = xml_get_value('orderby', $xml, $where_array['offset']);
		$groupby_array = xml_get_value('groupby', $xml, $where_array['offset']);
		
		$offset = $where_array['offset'];
		$endoffset = $orderby_array['offset'];
				
		$query = '';
		if ( !empty($sql_array['value']) ){
		
			$query = $sql_array['value'];
			if ( !empty($limit_array['value']) )
				$query .= " LIMIT {$limit_array['value']}";
				
		}
		else{
		
			$query = "SELECT {$select_array['value']} From "."{$table_array['value']} ";
			if ( !empty($where_array['value']) )
				$query .= "WHERE {$where_array['value']} ";
			if ( !empty($orderby_array['value']) )
				$query .= "ORDER BY {$orderby_array['value']} ";
			if ( !empty($groupby_array['value']) )
				$query .= "GROUP BY {$groupby_array['value']} ";					
			if ( !empty($limit_array['value']) )
				$query .= " LIMIT {$limit_array['value']}";
				
		}
		
		return $query;
		
	}	
	
	function executeAdd ($table, $field_array){
		
		$record = array();
		if ($field_array){
			
			//if (!empty ($this->data['page_bottom']) )
				//echo '12';
			foreach ($field_array as $filed){
				
				if ( !empty($this->data[$filed]) || $this->data[$filed] === 0 ){
				
					if ( strstr ($filed, 'file') && !empty($this->data[$filed]['name']) ){
						$record[$filed] = $this->data[$filed]['name'];
					}	
					else if (!strstr ($filed, 'file')){
						$record[$filed] = $this->data[$filed];
					}
					
				}
				
			}
			
			//print_r ($record);
			//echo $table;
			//exit();	
			getConfigValue('dbhandler')->db->AutoExecute($table, $record, 'INSERT');
			$this->error_string .= $this->getError();
			
			return getConfigValue('dbhandler')->db->Insert_ID ();
		}
		
	}
	
	function executeEdit ($table, $field_array){
		$record = array();
		//print_r ( $this->data );
		//exit();
		
		$sql = "SHOW COLUMNS FROM $table";
		$columns_info = $this->executeAll($sql);
		
		foreach ($columns_info as $column ){
			
			if (  strstr($column['Type'], 'int') && empty ($this->data[$column['Field']])){
				$this->data[$column['Field']] = 0;
				//echo $column['Field'];
			}
			else if (empty ($this->data[$column['Field']])){
				$this->data[$column['Field']] = '';
			}
			
		}
		
		//print_r ($columns_info);
		//exit();
		 
		if ($field_array){
				
			foreach ($field_array as $filed){
				
				//if ( !empty($this->data[$filed]) || $this->data[$filed] === 0 ){
					//echo $this->data[$filed].'-';
						
					if ( strstr ($filed, 'file') && !empty($this->data[$filed]['name']) ){
						$record[$filed] = $this->data[$filed]['name'];
					}	
					else if (!strstr ($filed, 'file')){
						$record[$filed] = $this->data[$filed];
					}
					
				//}
			}
			//print_r ($record);
			getConfigValue('dbhandler')->db->AutoExecute($table, $record, 'UPDATE',  'id='.$this->data['id']);
			$this->error_string .= $this->getError();
			
			return getConfigValue('dbhandler')->db->Affected_Rows();
		}
		
	}// end executeEdit
	
	function executeManage ($table, $per_page, $where_sql='', $query_fields='', $action='manage'){
		
		//print_r ($_SESSION);
		//echo '123'.$sql;
		//print_r ($this->data);
		if ( !empty($this->data['action_all']) ){
		
			$selected_id = '';
			if ( is_array($this->data['chk_id']) && !empty($this->data['chk_id']) )
				 $selected_id = implode(',', $this->data['chk_id']);
				
			$record = array();	
			if ($this->data['action_all'] == 'Active'){
				//echo "update $table set active=1 where id in ($selected_id)";
				getConfigValue('dbhandler')->db->Execute("update $table set active='Active' where id in ($selected_id)");		
				//getConfigValue('dbhandler')->db->Affected_Rows();		
			}
			
			else if ($this->data['action_all'] == 'Inactive'){
				
				getConfigValue('dbhandler')->db->Execute("update $table set active='Inactive' where id in ($selected_id)");
				//getConfigValue('dbhandler')->db->Affected_Rows();		
			}
			
			else if ($this->data['action_all'] == 'Delete'){
				getConfigValue('dbhandler')->db->Execute("Delete From $table where id in ($selected_id)");
				//getConfigValue('dbhandler')->db->Affected_Rows();		
			}
			
			$this->error_string .= $this->getError();
		}
		
		if ($this->data['active'] == 'Active' || $this->data['active'] == 'Inactive'){
			//echo '12';
			$this->executeEdit( $table, array('active') );
			//echo $this->getError();
		}
		
		if ($this->data['delete'] == 'yes' ){
			$this->executeDelete ($table, array('id'));
		}
		//echo $this->data['order_type'];
		if ( empty($query_fields) )
			$sql = 'select * from '.$table;
		else
			$sql = "select $query_fields from ".$table;
			
		if ( !empty ($where_sql) )
			$sql = $sql.' WHERE '.$where_sql;
		
		if ($this->data['order_type'] && $this->data['order_by']){
			$sql .= ' Order By '.$this->data['order_by'].' '.$this->data['order_type'];	
			$this->protocols['content_instance']->setValue('order_type', $this->data['order_type']);
			$this->protocols['content_instance']->setValue('order_by', $this->data['order_by']);
		}
		
		//echo $sql;
		//$rs = $this->executeAll($sql);
		$num_rows = $this->numOfRecord ($sql);
		//echo $this->getError();
		//echo $num_rows; exit();
		$request_type = getConfigValue ('user_request_type');;
		
		if ($request_type != 'frontend'){
			$str_paging = $this->renderPagination (urlForAdmin($this->module_name.'/'.$action), $num_rows, $per_page, $this->data['current_page'], TRUE);
		}
		else{
			$str_paging = $this->renderPagination (urlFor($action, array()), $num_rows, $per_page, $this->data['current_page'], TRUE);
		}
		
		$this->protocols['content_instance']->setValue('str_paging', $str_paging);
		
		if ($this->data['current_page'] > 1)
			$start_limit = ($this->data['current_page'] - 1) * $per_page;
		else
			$start_limit = 0;
		
		if ($per_page)
		{
			$sql .= " LIMIT $start_limit, $per_page";
		}	
		
		
		
		$table_data = $this->executeAll($sql);
		return $table_data;
	}
	

	
	function numOfRecord ($query){
		//echo $query; 
		//$total = $rs->RecordCount();
		$rs =  getConfigValue('dbhandler')->db->Execute($query);
		$total = $rs->RecordCount();
		$this->error_string .= $this->getError();
		
		return $total;
	}
	
	function renderPageControllingLink(){
		
		$paging_cotrolling_filed_url = '';
		if ($this->paging_controlling_field)
			foreach ($this->paging_controlling_field as $field){
				$paging_cotrolling_filed_url .= '&amp;'.$field.'='.$this->data[$field];
			}
		return $paging_cotrolling_filed_url;
	}
	
	function renderingOrder ($module, $label, $column, $action='manage'){
			
			$paging_cotrolling_filed_url = '';
			if ($this->paging_controlling_field)
				foreach ($this->paging_controlling_field as $field){
					if ($field != 'order_by' && $field != 'order_type' )
						$paging_cotrolling_filed_url .= '&amp;'.$field.'='.$this->data[$field];
				}
			
			$tmp_str = '';
			$tmp_str .= '<a href="'.urlForAdmin($module.'/'.$action).'?order_by='.$column.'&amp;order_type='.(($this->data[order_type] == 'desc' && $this->data[order_by] == $column)?'asc':'desc').$paging_cotrolling_filed_url.'">'.$label.'</a>&nbsp;<img src="'.getConfigValue('media_url').'images/'.(($this->data[order_type] == 'desc' && $this->data[order_by] == $column)?'s_desc.png':'s_asc.png').'" border="0" />';
			//echo $tmp_str;
			//exit();
			return $tmp_str; 
	}
	
	function renderingFOrder ($module, $label, $column, $action='manage'){
			
			$paging_cotrolling_filed_url = '';
			if ($this->paging_controlling_field)
				foreach ($this->paging_controlling_field as $field){
					if ($field != 'order_by' && $field != 'order_type' )
						$paging_cotrolling_filed_url .= '&amp;'.$field.'='.$this->data[$field];
				}
			
			$tmp_str = '';
			$tmp_str .= '<a href="'.urlFor($action, array()).'?order_by='.$column.'&amp;order_type='.(($this->data[order_type] == 'desc' && $this->data[order_by] == $column)?'asc':'desc').$paging_cotrolling_filed_url.'">'.$label.'</a>&nbsp;<img src="'.getConfigValue('media_url').'images/'.(($this->data[order_type] == 'desc' && $this->data[order_by] == $column)?'s_desc.png':'s_asc.png').'" border="0" />';
			//echo $tmp_str;
			//exit();
			return $tmp_str; 
	}
	
	function renderSeoPagination ($base_page, $num_items, $per_page, $on_page, $no_of_display_page=5, $add_prevnext_text = TRUE){
				
		if ($num_items <= 0)
			return '';
				
		$paging_cotrolling_filed_url = '';
		$paging_controlling_array = array();
	
		$paging_controlling_array []  = '';
		if ($this->data['perpage'])
			$paging_controlling_array []  = $this->data['perpage'];
		
		
		if ($this->paging_controlling_field)
			foreach ($this->paging_controlling_field as $field){
				
				if ($field != 'current_page' && $field != 'perpage' && ($this->data[$field]) ){
					$paging_cotrolling_filed_url .= '&amp;'.$field.'='.$this->data[$field];
					//$paging_controlling_array [] = $field;
					//$paging_controlling_array [] = $this->data[$field];
				}
				
			}
		//echo $paging_cotrolling_filed_url;
					
		$total_pages = ceil($num_items/$per_page);
		$no_of_each_side_page = floor($no_of_display_page/2);
		
		$page_string_before = '<div class="pagenav"><ul class="pagelist">';
		
		if ( $total_pages == 1 )
		{
			return '';
		}
		if ( empty($on_page) )
			$on_page = 1;
			
		if ( $total_pages <= $no_of_display_page){
			
			for( $i=1; $i <=$total_pages; $i++)
			{
				$paging_controlling_array [0] = $i;
				$page_string .= ( $i == $on_page ) ? '<li><span>' . $i . '</span></li>' : '<li><a href="' .urlFor($base_page,$paging_controlling_array).( empty($paging_cotrolling_filed_url)?'':'?'.$paging_cotrolling_filed_url ). '">' . $i . '</a></li><li></li>';	
			
			}
		
		}
		else{
			
			$left_counter = 0;
			
			if ($on_page > $no_of_each_side_page )
				$left_limit = $on_page - $no_of_each_side_page;
			else
				$left_limit = 1;
				
				
			if ($total_pages-$on_page >= $no_of_each_side_page)
				$right_limit = $on_page + $no_of_each_side_page;
			else
				$right_limit = $total_pages;
			
			if ( ($on_page - $left_limit) < $no_of_each_side_page)
				$right_limit += ($no_of_each_side_page - ($on_page - $left_limit) );
				
			if ( ($right_limit-$on_page) < $no_of_each_side_page)
				$left_limit -= ($no_of_each_side_page - ($right_limit-$on_page) );	
			
			if ($left_limit == 0 )
				$left_limit = 1;
			
			//echo 'l:'.$left_limit.'|r:'.$right_limit;
			
			for ($i = $left_limit; $i <= $right_limit ;$i++){
				$paging_controlling_array [0] = $i;
				$page_string .= ( $i == $on_page ) ? '<li><span>' . $i . '</span></li>' : '<li><a href="' .urlFor($base_page,$paging_controlling_array).( empty($paging_cotrolling_filed_url)?'':'?'.$paging_cotrolling_filed_url ). '">' . $i . '</a></li><li></li>';
				
			}			
			
		}
			
		
		if ( $add_prevnext_text )
		{
			
			if ( $on_page > 1 )
			{
				$paging_controlling_array [0] = $on_page - 1;
				$page_string = '<li><a href="'.urlFor($base_page, $paging_controlling_array).$paging_cotrolling_filed_url.'">' .'Prev' . '</a></li><li></li>' . $page_string;
			}
			if ( $on_page < $total_pages )
			{
				$paging_controlling_array [0] = $on_page + 1;
				$page_string .= '<li><a href="'.urlFor($base_page, $paging_controlling_array).$paging_cotrolling_filed_url.'">' . 'Next' . '</a></li>';
			}
		}
	
		//$page_string = '&nbsp;'. ' ' . $page_string;
		$page_string = $page_string_before.$page_string.'</ul></div>';
		return  $page_string;
	}
	
	
	function renderFPagination ($base_url, $num_items, $per_page, $on_page, $add_prevnext_text = TRUE){
				
		if ($num_items <= 0)
			return '';
		
		
		
		$paging_cotrolling_filed_url = '';
		if ($this->paging_controlling_field)
			foreach ($this->paging_controlling_field as $field){
				if ($field != 'current_page' && $this->data[$field])
					$paging_cotrolling_filed_url .= '&amp;'.$field.'='.$this->data[$field];
			}
		//echo $paging_cotrolling_filed_url;
		//print_r ($this->data);
		
		$total_pages = ceil($num_items/$per_page);
		if ( $total_pages == 1 )
		{
			return '';
		}
		if ( empty($on_page) )
			$on_page = 1;
		//$on_page = floor($start_item / $per_page) + 1;
		$page_string_before = '<div class="priveous">
      <ul>';
		$page_string = '';
					
		for($i = ($on_page < $total_pages)?$on_page-1:$on_page-2; $i <$on_page && $on_page !=1 ; $i++)
		{
			if ($i != 0)
				$page_string .= ( $i == $on_page ) ? '<li class="select">' . $i . '</li>' : '<li><a href="' .$base_url . "?1=1&amp;current_page=" .$i.$paging_cotrolling_filed_url. '">' . $i . '</a></li><li></li>';	
		}
		$tmp = ($on_page < $total_pages)?$on_page+1:$on_page;
		
		for($i = $on_page; $i <= $tmp ; $i++)
		{
			$page_string .= ( $i == $on_page ) ? '<li class="select">' . $i . '</li>' : '<li><a href="' .$base_url . "?1=1&amp;current_page=" .$i.$paging_cotrolling_filed_url. '">' . $i . '</a></li><li></li>';	
		}
			
		if ( $add_prevnext_text )
		{
			
			if ( $on_page > 1 )
			{
				$page_string = '<li class="frist"><a href="'.$base_url ."?1=1&amp;current_page=".( $on_page - 1 ).$paging_cotrolling_filed_url.'"><span>' .'Previous' . '</span></a></li><li></li>' . $page_string;
			}
			if ( $on_page < $total_pages )
			{
				$page_string .= '<li class="last"><a href="'.$base_url."?1=1&amp;current_page=".( $on_page + 1 ).$paging_cotrolling_filed_url.'"><span>' . 'Next' . '</span></a></li>';
			}
		}
	
		//$page_string = '&nbsp;'. ' ' . $page_string;
		$page_string = $page_string_before.$page_string.'</ul>
    </div>';
		return  $page_string;
	}
	
	function renderPagination ($base_url, $num_items, $per_page, $on_page, $add_prevnext_text = TRUE){
				
		if ($num_items <= 0)
			return '';
		
		
		
		$paging_cotrolling_filed_url = '';
		if ($this->paging_controlling_field)
			foreach ($this->paging_controlling_field as $field){
				if ($field != 'current_page' && $this->data[$field])
					$paging_cotrolling_filed_url .= '&amp;'.$field.'='.$this->data[$field];
			}
		//echo $paging_cotrolling_filed_url;
					
		$total_pages = ceil($num_items/$per_page);
		if ( $total_pages == 1 )
		{
			return '';
		}
		if ( empty($on_page) )
			$on_page = 1;
		//$on_page = floor($start_item / $per_page) + 1;
		$page_string_before = '<div class="flash_11" align="center"><ul style="float:left;">';
		$page_string = '';
					
		for($i = ($on_page < $total_pages)?$on_page-1:$on_page-2; $i <$on_page && $on_page !=1 ; $i++)
		{
			if ($i != 0)
				$page_string .= ( $i == $on_page ) ? '<li class="select-page"><span>' . $i . '</span></li>' : '<li><a href="' .$base_url . "?1=1&amp;current_page=" .$i.$paging_cotrolling_filed_url. '"><span>' . $i . '</span></a></li><li></li>';	
		}
		$tmp = ($on_page < $total_pages)?$on_page+1:$on_page;
		
		for($i = $on_page; $i <= $tmp ; $i++)
		{
			$page_string .= ( $i == $on_page ) ? '<li class="select-page"><span>' . $i . '</span></li>' : '<li><a href="' .$base_url . "?1=1&amp;current_page=" .$i.$paging_cotrolling_filed_url. '"><span>' . $i . '</span></a></li><li></li>';	
		}
			
		if ( $add_prevnext_text )
		{
			
			if ( $on_page > 1 )
			{
				$page_string = '<li class="previous"><a href="'.$base_url ."?1=1&amp;current_page=".( $on_page - 1 ).$paging_cotrolling_filed_url.'"><span>' .'Previous' . '</span></a></li><li></li>' . $page_string;
			}
			if ( $on_page < $total_pages )
			{
				$page_string .= '<li class="previous"><a href="'.$base_url."?1=1&amp;current_page=".( $on_page + 1 ).$paging_cotrolling_filed_url.'"><span>' . 'Next' . '</span></a></li>';
			}
		}
	
		//$page_string = '&nbsp;'. ' ' . $page_string;
		$page_string = $page_string_before.$page_string.'</ul></div>';
		return  $page_string;
	}
	
	function getError(){
		
		$tmp_error = '';
		
		if ( getConfigValue('dbhandler')->db->ErrorNo() ){
			//$tmp_error .= getConfigValue('dbhandler')->db->ErrorNo();
			$tmp_error .= ' '.getConfigValue('dbhandler')->db->ErrorMsg()."<br/>";
		}
		
		return $tmp_error;
	}
	function executeQuery ($query){
		$rs =  getConfigValue('dbhandler')->db->Execute($query);
		$this->error_string .= $this->getError();
	}
	
	function executeOne ($query){
		 $result = array();
		 $result = getConfigValue('dbhandler')->db->GetRow ($query);
		 $this->error_string .= $this->getError();
		 return $result;
	}
	
	function executeAll ($query){
		 $result = array();
		 $result = getConfigValue('dbhandler')->db->GetAll ($query);
		 $this->error_string .= $this->getError();
		 return $result;
	}
	
	function executeDelete ($table, $field_array){
			 
		$query = 'Delete From '.$table.' Where ';
		$data = array();
		$where_sql = '';
		
		if ($field_array){
		
			foreach ($field_array as $filed){
				//$record[$filed] = $this->escape_string($this->data[$filed]);
				$where_sql .= $filed.'=?';
				$data[] = $this->data[$filed];
			}
		}
		$sql = $query.$where_sql;
		
		$stmt = getConfigValue('dbhandler')->db->Prepare($sql);
		getConfigValue('dbhandler')->db->Execute($stmt, $data);
		$no_of_result = getConfigValue('dbhandler')->db->Affected_Rows();
		
		$this->error_string .= $this->getError();
		
		return $no_of_result;
	}
	
	function chkModule ($name){
		$is_installed = $this->numOfRecord ("Select * From ".getConfigValue('table_prefix')."modulemanager where name = '$name'");
		if ($is_installed == 1)
			return true;
		else
			return false;
	}
	
	function parseTemplate($module, $template, $default='common'){
		
		if ($default == 'common')
			$template_path = getConfigValue('module').'common/templates/'.$template.getConfigValue('template_suffix');
		else	
			$template_path = getConfigValue('module').$module.'/templates/'.$template.getConfigValue('template_suffix');
		
		
		
		$this->protocols['content_instance']->setValue ('module', $module);
		$this->protocols['content_instance']->setTemplate($template_path);
		$value = $this->protocols['content_instance']->contentView();
		return $value;
	}
	
	function chkFilterAuthorization ($module_name, $filter_name){
		
		//echo '123';
		//print_r ($_SESSION);
		//echo $filter_name;
		
		$role_info = getSessionValue('fuser_role');
		$xml = io_file_get_contents(getConfigValue('module').$module_name."/Security.xml");
		$security_array =  xml_get_value($filter_name, $xml, 0);
		$security_role_array = xml_get_all_value ('role', $security_array['value'], 0);
		
		//print_r ($security_array);
		//exit();
		
		if ( $security_role_array && !in_array($role_info['title'], $security_role_array) ){
			//print_r ($role_info);
			//return $this->parseTemplate('common', 'unauthorize');
			//setConfigValue ('login.php', 'execute_template');
			if (getConfigValue ('user_request_type') == 'backend' )
				header ("location:".urlForAdmin('user/unauthorize'));
			else
				header ("location:".urlFor(getConfigValue('invalid_page'), array()) );
				
			exit();
		}
		return '';
			
	}
	
	function chkFAuthorization ($module_name){
			//echo '123';
			//echo $this->routing_data['cms_action'];
						
			$role_info = getSessionValue('fuser_role');
			$xml = io_file_get_contents(getConfigValue('module').$module_name."/Security.xml");
			$security_array =  xml_get_value($this->routing_data['cms_action'], $xml, 0);
			$security_role_array = xml_get_all_value ('role', $security_array['value'], 0);
			
			//print_r ($role_info);
			//exit();
			
			if ( $security_role_array && !in_array($role_info['title'], $security_role_array) ){
				//return $this->parseTemplate('common', 'unauthorize');
				//setConfigValue ('login.php', 'execute_template');
				if (getConfigValue ('user_request_type') == 'backend' )
					header ("location:".urlForAdmin('user/unauthorize'));
				else
					header ("location:".urlFor(getConfigValue('invalid_page'), array()) );
					
			 	exit();
			}
			return '';
	}
	
	function chkAuthorization ($module_name){
			//echo '123';
			//print_r ($_SESSION);
			
			$role_info = getSessionValue('user_role');
			$xml = io_file_get_contents(getConfigValue('module').$module_name."/Security.xml");
			$security_array =  xml_get_value(getConfigValue ('current_action'), $xml, 0);
			$security_role_array = xml_get_all_value ('role', $security_array['value'], 0);
			
			//print_r ($role_info);
			//exit();
			
			if ( $security_role_array && !in_array($role_info['title'], $security_role_array) ){
				//return $this->parseTemplate('common', 'unauthorize');
				//setConfigValue ('login.php', 'execute_template');
				if (getConfigValue ('user_request_type') == 'backend' )
					header ("location:".urlForAdmin('user/unauthorize'));
				else
					header ("location:".urlFor(getConfigValue('invalid_page'), array()) );
					
			 	exit();
			}
			return '';
	}
	
	function chkAccessibility ($table_name, $table_id){
		//for accessablity table must have a field name as user_id, which will contain the value of user table id field
		
		$role_info = getSessionValue('user_role');
		//print_r ($role_info);
		
		if ($role_info['title'] == 'admin' || $role_info['title'] == 'backend')
			return true;
		else{
			$user_info = getSessionValue('user_info');
			$is_accessable = $this->numOfRecord('select * from '.$table_name.' where user_id='.$user_info['id'].
																							' and id='.$table_id);
			if ($is_accessable >= 1)
				return true;
		}
		
		if (getConfigValue ('user_request_type') == 'backend' )
			header ("location:".urlForAdmin('user/unauthorize'));
		else
			header ("location:".urlFor(getConfigValue('invalid_page'), array()) );
		exit();
		//return false;		
	}
	
	function chkFAccessibility ($table_name, $table_id){
		//for accessablity table must have a field name as user_id, which will contain the value of user table id field
		
		$role_info = getSessionValue('fuser_role');
		
		if ($role_info['title'] == 'admin')
			return true;
		else{
			$user_info = getSessionValue('fuser_info');
			$is_accessable = $this->numOfRecord('select * from '.$table_name.' where user_id='.$user_info['id'].
																							' and id='.$table_id);
			if ($is_accessable >= 1)
				return true;
		}
		
		if (getConfigValue ('user_request_type') == 'backend' )
			header ("location:".urlFor('unauthorize', array()));
		else
			header ("location:".urlFor('unauthorize', array()) );
			
		exit();
		//return false;		
	}
	
}
  
?>