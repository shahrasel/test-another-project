<?php
abstract class giftcardmanager extends parser
{   
		var $giftcard_table;
		var $transaction_table;
		
		var $payment_method ;
		var $wishlist_table;
		
		public function giftcardmanager(){
		
			//require_once ("paypal/PaypalPayment.php"); 
			//require_once ("gcheckout/GcheckoutPayment.php"); 
			//$this->payment_method = new PaypalPayment();
			//$this->payment_method = new GcheckoutPayment();
			
			$this->giftcard_table = getConfigValue('table_prefix').'giftcard';
			$this->transaction_table = getConfigValue('table_prefix').'usertransaction';
			
		}
		
		function getCardNo ($prefix, $len=10){
			
			$tmp_user = getSessionValue ('fuser_info');
					
			$tmp_code = 'rcard'.$tmp_user['id'].time().$tmp_user['email'];
			$code  =  substr (md5 ($tmp_code), 0, $len);
			
			if ($prefix)
				$code  = $prefix .'-'. $code;   
			return $code;
		}
		
		function getCardPin ($len=6){
			
			$tmp_user = getSessionValue ('fuser_info');
					
			$tmp_code = 'rpin'.$tmp_user['id'].time().$tmp_user['email'];
			$code  =  substr (md5 ($tmp_code), 0, $len);
			
			return $code;
		}
		
		public function createGiftCard ($customer_id, $card_no, $card_pin, $amount, $active='Active', $prefix, $len=10 ){
			
			$record = array();
			
			if ( $amount && $amount > 0)
				$record['amount'] = $amount;
							
			$record['active'] = $active;
			
			if (empty ($card_no) )
				$record['card_no'] = $this->getCardNo ($prefix, $len);
			else
				$record['card_no'] = $card_no;
			
			if (empty ($card_pin) )
				$record['card_pin'] = $this->getCardPin (6);
			else
				$record['card_pin'] = $card_pin;
			
			//$tmp_user = getSessionValue ('fuser_info');
			//$record['customer_id'] = $tmp_user['id'];
			if ($customer_id)
				$record['customer_id'] = $customer_id;
						
			$record['cdate'] = time(); 
			$record['udate'] = time();
			//print_r ($record);
			
			getConfigValue('dbhandler')->db->AutoExecute($this->giftcard_table, $record, 'INSERT');
			//echo $this->getError();
			//$this->executeAdd( $this->table_name, array('title','price','description','active') );
			return getConfigValue('dbhandler')->db->Insert_ID ();
			//print_r ($reord);
		}	
		
		public function updateGiftCard($id, $customer_id, $card_no, $card_pin, $amount, $active='Active', $prefix='' ,$len=10){
			
			$record = array();
			
			if (empty ($card_no) )
				$record['card_no'] = $this->getCardNo ($prefix, $len);
			else
				$record['card_no'] = $card_no;
			
			if (empty ($card_pin) )
				$record['card_pin'] = $this->getCardPin (6);
			else
				$record['card_pin'] = $card_pin;
				
			if ( $amount && $amount > 0)
				$record['amount'] = $amount;
			
			if ($customer_id)
				$record['customer_id'] = $customer_id;
				
			$record['active'] = $active;
			$record['udate'] = time();
						
			getConfigValue('dbhandler')->db->AutoExecute($this->giftcard_table, $record, 'UPDATE', " id='$id' ");
			//echo $this->getError();
			return getConfigValue('dbhandler')->db->Affected_Rows();
		}
		
		public function getGiftCardBalance ($card_no){
			
			//$tmp_user = getSessionValue ('fuser_info');
			$query = "Select * From ".$this->giftcard_table." where card_no='$card_no' ";
			
			return $this->executeOne ($query);
			
		}
		
		public function viewAllCard ($customer_id=0){
			
			if ($customer_id == 0){
				$tmp_user = getSessionValue ('fuser_info');
				$customer_id = $tmp_user['id'];
			}
			
			$where = '';
			if ($customer_id)
				$where = " and customer_id=".$customer_id;
						
			
			$query = "Select * FROM ".$this->giftcard_table." where 1=1 $where";
			return $this->executeAll($query);
			
		}
		
		public function viewAllCardInfo ($gift_card_id=''){
									
			$where = '';
			if ($gift_card_id)
				$where = " and $this->giftcard_table.id=".$gift_card_id;
						
			
			$table_userinfo = getConfigValue('table_prefix').'userinfo';
			$table_user = getConfigValue('table_prefix').'user';
			
			echo $query = "Select $this->giftcard_table.* FROM ".$this->giftcard_table." , $table_user  where 1=1 $where and $table_user.id=$this->giftcard_table.customer_id";
			$query = "select * from abm_giftcard";
			
			$rerult = '';
			if ($gift_card_id)
				$rerult = $this->executeOne($query);
			else
				$rerult = $this->executeAll($query);
			
			print_r ($rerult);
			return $rerult;
		}
				
		public function viewCardTransaction ($cert_id){
			
			//$tmp_user = getSessionValue ('fuser_info');
			/*$query = "Select $this->session_table.id as su_id,$this->session_table.qty, $product_table.* FROM ".$this->session_table.','.$product_table." where product_table='$product_table' and session_id='".session_id()."' and customer_id=".$tmp_user['id']." and $product_table.id=".$this->session_table.".product_id";
			return $this->executeAll($query);*/
			
			
		}							
}
?>