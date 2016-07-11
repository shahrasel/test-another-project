<?php
abstract class comments extends parser
{   
		var $comments_table;
				
		public function comments(){
			//require_once ("paypal/PaypalPayment.php"); 
			//require_once ("gcheckout/GcheckoutPayment.php"); 
			
			//$this->payment_method = new GcheckoutPayment();
			$this->comments_table = getConfigValue('table_prefix').'comments';
			//$this->user_transaction_table = getConfigValue('table_prefix').'UserTransaction';
		}
		
		public function updateComments ($product_table, $product_id, $active='Inactive'){
			
			$this->error_string = '';
		
			$record['active'] = $active; 
			getConfigValue('dbhandler')->db->AutoExecute($this->session_table, $record, 'UPDATE', " product_table='$product_table' and product_id=$product_id ");
			
			return getConfigValue('dbhandler')->db->Affected_Rows();
		}
		
		public function addComments ($product_table, $product_id, $comment, $active='Inactive'){
			
			$tmp_user = getSessionValue ('fuser_info');
			
			//$query = "Select * FROM ".getConfigValue('table_prefix').'userinfo'." where user_id={$tmp_user['id']}";
			//$tmp_user_info = $this->executeOne($query);
			$this->error_string = '';
			$record = array();
			$record['product_table'] = $product_table;
			$record['product_id'] = $product_id;
			$record['comment'] = $comment;
			$record['user_id'] = $tmp_user['id'];
			//$record['user_name'] = $tmp_user_info['name'];
			$record['active'] = $active; 
			$record['date'] = time(); 		
			
			//print_r ($record);
			getConfigValue('dbhandler')->db->AutoExecute($this->comments_table, $record, 'INSERT');
			//echo $this->error_string;
			//echo $this->getError();
			//$this->executeAdd( $this->table_name, array('title','price','description','active') );
			return getConfigValue('dbhandler')->db->Insert_ID ();
		}
			
		public function getCommentsList ($product_table, $product_id, $active='Active', $max_comment=0){
						
			//echo $active;		
			$user_info_table = getConfigValue ('table_prefix').'user'; 
			
			$query = 'Select '.$user_info_table.'.login_name, '.$this->comments_table.'.* FROM '.$this->comments_table.','.$user_info_table." where product_table='$product_table' and  product_id='$product_id' and ".$this->comments_table.'.user_id='.$user_info_table.'.id' ;
						
			if ($active != 'All'){
				$query .= " and ".$this->comments_table.".active='$active'"; 
			}
			$query .=" order by id desc";
			if ($max_comment > 0)
				$query .= ' limit '.$max_comment; 
				
			//echo $query;
			$comments_array = $this->executeAll($query);
			//print_r ($comments_array);
			//$total_balance = $balance_array['amount'];
						
			return $comments_array;
			
		}
							
}
?>