<?php
class Dbhandler {
	 
	 var $db;
	 var $select = '';
	 var $where = '';
	 var $order = '';
	 var $group = '';
	 var $table = '';
	 
	 function Dbhandler (){
	 		
			//echo getConfigValue('lib');
			//exit();			
			require_once (getConfigValue('lib').'adodb/adodb.inc.php');
			require_once (getConfigValue('config').'config.php');
			
			setConfigValue ('table_prefix', $db_table_prefix);
			//$dsn = 'mysql://'.$db_username.':'.$db_password.'@'.$db_host.'/'.$db_name;
			$this->db = NewADOConnection($db_driver);
			//$this->db->NConnect($db_host, $db_user, $db_password, $db_database);
			$this->db->Connect($db_host, $db_user, $db_password, $db_database);
			//echo $this->db->ErrorMsg();
			//$rs = $db->Execute('select * from table1');
      //print_r($rs->GetRows());
	 }
	 
	 function reOpenConnection(){
	 		require_once (getConfigValue('lib').'adodb/adodb.inc.php');
			require_once (getConfigValue('config').'config.php');
			
			setConfigValue ('table_prefix', $db_table_prefix);
			//$dsn = 'mysql://'.$db_username.':'.$db_password.'@'.$db_host.'/'.$db_name;
			$this->db = NewADOConnection($db_driver);
			//$this->db->NConnect($db_host, $db_user, $db_password, $db_database);
			$this->db->Connect($db_host, $db_user, $db_password, $db_database);
	 }
	 /*function exexuteSelectOne($sql){
	 		$rs = $db->Execute('select * from table1');
      print_r($rs->GetRows());
	 }
	 
	 function exexuteInsert($table, $values){
	 		$db->AutoExecute($table, $values,'INSERT');
			return $db->Insert_Id();
	 }
	 
	 function exexuteUpdate($table, $values, $where){
	 		$db->AutoExecute($table, $values,'UPDATE', $where);
	 }*/
	 
}
  
?>