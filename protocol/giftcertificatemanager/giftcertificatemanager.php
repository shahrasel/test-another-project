<?php
abstract class giftcertificatemanager extends parser
{   
		var $giftcertificate_table;
		var $transaction_table;
		
		var $payment_method ;
		var $wishlist_table;
		
		public function giftcertificatemanager(){
		
			//require_once ("paypal/PaypalPayment.php"); 
			//require_once ("gcheckout/GcheckoutPayment.php"); 
			//$this->payment_method = new PaypalPayment();
			//$this->payment_method = new GcheckoutPayment();
			
			$this->giftcertificate_table = getConfigValue('table_prefix').'giftcertificate';
			$this->transaction_table = getConfigValue('table_prefix').'usertransaction';
			
		}
		
		function getCertificateCode ($prefix, $len=10){
			
			$tmp_user = getSessionValue ('fuser_info');
					
			$tmp_code = 'rcertificate'.$tmp_user['id'].time().$tmp_user['email'];
			$code  =  substr (md5 ($tmp_code), 0, $len);
			
			if ($prefix)
				$code  = $prefix.'-'.$code ;
				
			return $code;
		}
		
		public function createGiftCertificate ($certificate_code, $sender_email, $receiver_email, $amount, $additional_text, $active='Active', $prefix='' ,$len=10 ){
						
			$user_table = getConfigValue('table_prefix').'user';
			
			$record = array();
			$record['receiver_email'] = $receiver_email;
			
			//echo 'select * from '.$user_table." where email='".$receiver_email."'";
			$receiver_info = $this->executeOne('select * from '.$user_table." where email='".$receiver_email."'");
			//print_r ($receiver_info);
			
			if ($receiver_info)
				$record['receiver_id'] = $receiver_info['id'];
			
			$record['active'] = $active;
			$record['amount'] = $amount;
			$record['addition_text'] = $addition_text;
			$record['active'] = $active;
			
			if (empty ($certificate_code) )
				$record['certificate_code'] = $this->getCertificateCode ($prefix, $len);
			else
				$record['certificate_code'] = $certificate_code;
			
			$tmp_user = getSessionValue ('fuser_info');
			//$record['sender_id'] = $sender_id;
			$record['sender_email'] = $sender_email; 
			$sender_info = $this->executeOne('select * from '.$user_table." where email='".$sender_email."'");
			if ($sender_info)
				$record['sender_id'] = $sender_info['id'];
			
			if ($additional_text)
				$record['additional_text'] = $additional_text;
			
			$record['cdate'] = time(); 
			$record['udate'] = time();
			//print_r ($record);
			
			getConfigValue('dbhandler')->db->AutoExecute($this->giftcertificate_table, $record, 'INSERT');
			//echo $this->getError();
			//$this->executeAdd( $this->table_name, array('title','price','description','active') );
			return getConfigValue('dbhandler')->db->Insert_ID ();
			//print_r ($reord);
		}	
		
		public function updateGiftCertificate( $id, $certificate_code, $sender_email, $receiver_email, $amount, $additional_text, $active='Active', $prefix='' ,$len=10 ){
			
			$user_table = getConfigValue('table_prefix').'user';
			$record = array();
			 			
			$record['receiver_email'] = $receiver_email;
			//echo 'select * from '.$this->giftcertificate_table." where email='".$receiver_email."'";
			$receiver_info = $this->executeOne('select * from '.$user_table." where email='".$receiver_email."'");
			if ($receiver_info)
				$record['receiver_id'] = $receiver_info['id'];
			
			$record['active'] = $active;
			$record['amount'] = $amount;
			$record['addition_text'] = $addition_text;
			$record['active'] = 'Active';
			
			if (empty ($certificate_code) )
				$record['certificate_code'] = $this->getCertificateCode ($prefix, $len);
			else
				$record['certificate_code'] = $certificate_code;
				
			$tmp_user = getSessionValue ('fuser_info');
			//$record['sender_id'] = $sender_id;
			$record['sender_email'] = $sender_email; 
			$sender_info = $this->executeOne('select * from '.$user_table." where email='".$sender_email."'");
			if ($sender_info)
				$record['sender_id'] = $sender_info['id'];
			
			if ($additional_text)
				$record['additional_text'] = $additional_text;
			
			//print_r ($record);
			getConfigValue('dbhandler')->db->AutoExecute($this->giftcertificate_table, $record, 'UPDATE', " id='$id' ");
			//echo $this->getError();
			return getConfigValue('dbhandler')->db->Affected_Rows();
		}
		
		public function getCertificateBalance ($certificate_code){
			
			//$tmp_user = getSessionValue ('fuser_info');
			$query = "Select * From ".$this->giftcertificate_table." where certificate_code='$certificate_code' ";
			
			return $this->executeOne ($query);
			
		}
		
		public function viewSentCertificate ($prefix='', $id=0){
			
			if ($id == 0){
				$tmp_user = getSessionValue ('fuser_info');
				$id = $tmp_user['id'];
			}
			
			$where = '';
			if ($id)
				$where = " and sender_id=".$id;
			
			if ($prefix)
				$where .= " and certificate_code like '$prefix%' ";
			
			$query = "Select * FROM ".$this->giftcertificate_table." where 1=1 $where";
			return $this->executeAll($query);
			
		}
					
		public function viewReceivedCertificate ($prefix='', $id=0){
			
			if ($id == 0){
				$tmp_user = getSessionValue ('fuser_info');
				$id = $tmp_user['id'];
			}
			
			$where = '';
			if ($id)
				$where = " and receiver_id=".$id;
			
			if ($prefix)
				$where .= " and certificate_code like '$prefix%' ";
			
			$query = "Select * FROM ".$this->giftcertificate_table." where 1=1 $where";
			return $this->executeAll($query);
			
		}
		
		public function viewAllCertificateInfo ($giftcertificate_id=''){
									
			$where = '';
			if ($giftcertificate_id)
				$where = " and $this->giftcertificate_table.id=".$giftcertificate_id;
						
			
			$table_userinfo = getConfigValue('table_prefix').'userinfo';
			$table_user = getConfigValue('table_prefix').'user';
			
			$query = "Select abm_giftcertificate.* FROM ".'abm_giftcertificate'.",$table_user  where 1=1 $where and $table_user.id=abm_giftcertificate.receiver_id";
			
			if ($giftcertificate_id)
				return $this->executeOne($query);
			else
				return $this->executeAll($query);
		}
				
		public function viewCertificateTransaction ($cert_id){
			
			//$tmp_user = getSessionValue ('fuser_info');
			/*$query = "Select $this->session_table.id as su_id,$this->session_table.qty, $product_table.* FROM ".$this->session_table.','.$product_table." where product_table='$product_table' and session_id='".session_id()."' and customer_id=".$tmp_user['id']." and $product_table.id=".$this->session_table.".product_id";
			return $this->executeAll($query);*/
			
			
		}						
		
		function receivePendingGift (){
			
			$tmp_user = getSessionValue ('fuser_info');
			$query = "Update $this->giftcertificate_table set receiver_id = ".$tmp_user['id']. " Where receiver_email='".$tmp_user['email']."'";
			$this->executeQuery ($query);
			
			
		}
}
?>