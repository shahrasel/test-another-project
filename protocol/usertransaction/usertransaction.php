<?php
abstract class usertransaction extends parser
{   
		var $user_balance_table;
		var $user_transaction_table;
		var $str_paging;	
		
		public function UserTransaction(){
			//require_once ("paypal/PaypalPayment.php"); 
			//require_once ("gcheckout/GcheckoutPayment.php"); 
			
			//$this->payment_method = new GcheckoutPayment();
			$this->user_balance_table = getConfigValue('table_prefix').'UserBalance';
			$this->user_transaction_table = getConfigValue('table_prefix').'UserTransaction';
		}
		
		public function addToUserTransaction ($amount, $transaction_for, $payment_gateway, $payment_table, $payment_id, $transaction_type){
			
			$tmp_user = getSessionValue ('fuser_info');
			
			$record = array();
			$record['amount'] = $amount;
			$record['transaction_for'] = $transaction_for;
			$record['payment_gateway'] = $payment_gateway;
			$record['payment_table'] = $payment_table;
			$record['payment_id'] = $payment_id;
			$record['transaction_type'] = $transaction_type;
			
			$record['customer_id'] = $tmp_user['id'];
			$record['active'] = 'Active'; 
			$record['date'] = time(); 		
			
			getConfigValue('dbhandler')->db->AutoExecute($this->user_transaction_table, $record, 'INSERT');
			//echo $this->getError();
			//$this->executeAdd( $this->table_name, array('title','price','description','active') );
			return getConfigValue('dbhandler')->db->Insert_ID ();
		}
		
		public function updateUserTransaction ($id){
		
			/*$record = array();
			$record['product_table'] = $product_table;
			$record['product_id'] = $product_id;
			$record['transaction_type'] = $transaction_type;
			$record['amount'] = $price;
			$record['particulars'] = $particulars;
			//$tmp_user = getSessionValue ('user_info');
			
			$record['customer_id'] = $tmp_user['id'];
			$record['active'] = 'Active'; 
			
			getConfigValue('dbhandler')->db->AutoExecute($this->session_table, $record, 'UPDATE', "id=$id");
			return getConfigValue('dbhandler')->db->Insert_ID ();*/
			
		}
		
		public function getUserBalance ($user_id, $particulars='', $from_date='', $to_date=''){
		
			$credit_amount = $this->getParticularsBalance ($user_id, $particulars, 'credit', $from_date, $to_date);						
			$debit_amount = $this->getParticularsBalance ($user_id, $particulars, 'debit', $from_date, $to_date);						
			
			$balance = $credit_amount - $debit_amount;
			return $balance;
		}
		
		public function getUserTransactionInfo ($user_id, $paging_controlling_aray){
						
			$this->str_paging = '';
			$this->paging_controlling_field = array_keys($paging_controlling_aray);
			//print_r ($this->paging_controlling_field);
			 
			$query = "Select * FROM ".$this->user_transaction_table." where customer_id=".$user_id;
		
			foreach ($paging_controlling_aray as $key=>$value):
				
				$this->data[$key] = $value;
				
				if ($value && ($key != 'page' && $key != 'perpage' && $key != 'order_by' && $key != 'order_type'&&$key != 'current_page') )
					$query .= " and $key='$value'";
			endforeach;
			
			if ($paging_controlling_aray['order_type'] && $paging_controlling_aray['order_by']){
				$query .= ' Order By '.$paging_controlling_aray['order_by'].' '.$paging_controlling_aray['order_type'];	
			}
			
			//echo $query;
			$num_rows = $this->numOfRecord ($query);
			//echo $num_rows; exit();
			
			$perpage = $paging_controlling_aray['perpage'];
			$this->str_paging = $this->renderPagination (urlForAdmin('User'.'/transaction'), $num_rows, $perpage, $paging_controlling_aray['current_page'], TRUE);
			//$this->protocols['Content_Instance']->setValue('str_paging', $str_paging);
			
			if ($paging_controlling_aray['current_page'] > 1)
				$start_limit = ($paging_controlling_aray['current_page'] - 1) * $perpage;
			else
				$start_limit = 0;
			if ($paging_controlling_aray['perpage'])
			{
				$query .= " LIMIT $start_limit, $perpage";
			}	
			
			//echo $query;
			$transaction_array = $this->executeAll($query);
			//$total_balance = $balance_array['amount'];
						
			return $transaction_array;
			
		}
		
		//public function 
		public function getParticularsBalance ($user_id, $particulars, $transaction_type, $from_date, $to_date){
			
			$query = "Select SUM(amount) as total_amount FROM ".$this->user_transaction_table." where customer_id=".$user_id;
			if ($particulars)
				$query .= " and particulars='$particulars'";
			if ($transaction_type)
				$query .= " and transaction_type='$transaction_type'";
			if ($from_date)
				$query .= " and from_date >= '$from_date'";
			if ($to_date)
				$query .= " and to_date >= '$to_date'";
			
			//echo $query;
			$balance_info = $this->executeOne ($query);
			return $balance_info['total_amount'];
			
		}
				
						
}
?>