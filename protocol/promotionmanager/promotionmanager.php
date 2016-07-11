<?php
abstract class promotionmanager extends parser
{   
		var $promotion_table;
		var $transaction_table;
		
		var $payment_method ;
		var $wishlist_table;
		
		public function promotionmanager(){
		
			//require_once ("paypal/PaypalPayment.php"); 
			//require_once ("gcheckout/GcheckoutPayment.php"); 
			//$this->payment_method = new PaypalPayment();
			//$this->payment_method = new GcheckoutPayment();
			
			$this->promotion_table = getConfigValue('table_prefix').'promotion';
			$this->transaction_table = getConfigValue('table_prefix').'usertransaction';
			
		}
		
		function getPromoCard ($prefix, $len=6){
			
			$tmp_user = getSessionValue ('fuser_info');
					
			$tmp_code = 'rpromo'.$tmp_user['id'].time().$tmp_user['email'];
			$code  =  substr (md5 ($tmp_code), 0, $len);
			
			if ($prefix)
				$code = $prefix.'-'.$code;
				
			return $code;
		}
		
		public function createPromotion ($promo_code, $amount, $is_flat, $total_gt, $from_date, $to_date, $active, $prefix, $len=6){
			
			$record = array();
			
			//$record['amount'] = $amount;
			$record['active'] = $active;
			if (empty ($promo_code) )
				$record['promo_code'] = $this->getPromoCard ($prefix, $len);
			else
				$record['promo_code'] = $promo_code;
				
			if ($is_flat)
				$record['is_flat'] = $is_flat;
			if ($total_gt)
				$record['total_gt'] = $total_gt;	
			
			$record['amount'] = $amount;
			$record['from_date'] = $this->mystrtotime ($from_date);
			$record['to_date'] = $this->mystrtotime ($to_date);
			
			$tmp_user = getSessionValue ('user_info');
			$record['customer_id'] = $tmp_user['id'];
						
			$record['cdate'] = time(); 
			$record['udate'] = time();
			//print_r ($record);
			
			getConfigValue('dbhandler')->db->AutoExecute($this->promotion_table, $record, 'INSERT');
			//echo $this->getError();
			//$this->executeAdd( $this->table_name, array('title','price','description','active') );
			return getConfigValue('dbhandler')->db->Insert_ID ();
			//print_r ($reord);
		}	
		
		public function updatePromotion($id, $promo_code, $amount, $is_flat, $total_gt, $from_date, $to_date, $active, $prefix, $len=6){
			
			$record = array();
			if (empty ($promo_code) )
				$record['promo_code'] = $this->getPromoCard ($prefix, $len);
			else
				$record['promo_code'] = $promo_code;
				
			$record['amount'] = $amount;
			
						
			$record['active'] = $active;
			if ($is_flat)
				$record['is_flat'] = $is_flat;
			if ($total_gt)
				$record['total_gt'] = $total_gt;	
			
			$record['amount'] = $amount;
						
			$record['from_date'] = $this->mystrtotime ($from_date);
			//echo ( date('m-d-Y', $record['from_date']) );
			//exit();
			
			$record['to_date'] = $this->mystrtotime ($to_date);
			
			$record['udate'] = time();
						
			getConfigValue('dbhandler')->db->AutoExecute($this->promotion_table, $record, 'UPDATE', " id='$id' ");
			//echo $this->getError();
			return getConfigValue('dbhandler')->db->Affected_Rows();
		}
		
		function mystrtotime ($date, $seperator='-'){
			if ($date){
				
				$date_array = explode ($seperator, $date);
				$date_format = getConfigValue ('date_format');
				$format_array = explode ($seperator, $date_format);
				
				$final_array[$format_array[0]] = $date_array[0];
				$final_array[$format_array[1]] = $date_array[1];
				$final_array[$format_array[2]] = $date_array[2];
				
				//$final_array['yy'].'-'.$final_array['mm'].'-'.$final_array['dd'];
				
				
				return strtotime ($final_array['yy'].'-'.$final_array['mm'].'-'.$final_array['dd']);
				
			}
		}
		
		public function getPromotionInfo ($promo_code){
			
			//$tmp_user = getSessionValue ('fuser_info');
			$query = "Select * From ".$this->promotion_table." where promo_code='$promo_code' ";
			
			return $this->executeOne ($query);
			
		}
					
				
		public function viewCardTransaction ($cert_id){
			
			//$tmp_user = getSessionValue ('fuser_info');
			/*$query = "Select $this->session_table.id as su_id,$this->session_table.qty, $product_table.* FROM ".$this->session_table.','.$product_table." where product_table='$product_table' and session_id='".session_id()."' and customer_id=".$tmp_user['id']." and $product_table.id=".$this->session_table.".product_id";
			return $this->executeAll($query);*/
			
			
		}							
}
?>