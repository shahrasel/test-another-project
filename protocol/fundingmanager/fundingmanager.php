<?php
abstract class fundingmanager extends parser
{   
		var $tmpinternalfunding_table ;
		var $internalfunding_table ;
		
		var $session_table;
		var $promotion_table;
		var $giftcertificate_table;
		var $giftcard_table;
		
		//var $protocols;
		//var $protocolsList = array('');
		
		public function fundingmanager(){
		
			//require_once ("paypal/PaypalPayment.php"); 
			//require_once ("gcheckout/GcheckoutPayment.php"); 
			//$this->payment_method = new PaypalPayment();
			//$this->payment_method = new GcheckoutPayment();
			
			$this->tmpinternalfunding_table = getConfigValue('table_prefix').'sessioninternalfunding';
			$this->internalfunding_table = getConfigValue('table_prefix').'internalfundingdetails';
			$this->giftcard_table = getConfigValue('table_prefix').'giftcard';
			$this->giftcertificate_table = getConfigValue('table_prefix').'giftcertificate';
			$this->promotion_table = getConfigValue('table_prefix').'promotion';
			$this->session_table = getConfigValue('table_prefix').'session';
			//initProtocols (getConfigValue('protocol'), $this->protocolsList);
		  //$this->protocols = loadProtocols ($this->protocolsList);
			
		}
		
		function validateInternalFunding ($funding_type, $funding_code, $funding_pin){
			
			if ($funding_type == 'promotion'){
				$query = "Select * FROM ".$this->promotion_table." where promo_code='$funding_code' and from_date<='".date('Y-m-d', time())."' and to_date >='".date('Y-m-d', time())."' and active='Active'";
				
				$promotion_info = $this->executeOne($query);
				//print_r ($promotion_info);
				return $promotion_info;
			}
			
			if ($funding_type == 'giftcard'){
				$query = "Select * FROM ".$this->giftcard_table." where card_no='$funding_code' and card_pin='$funding_pin' and active='Active' and amount > 0";
				
				$giftcard_info = $this->executeOne($query);
				//print_r ($promotion_info);
				return $giftcard_info;
			}
			
			if ($funding_type == 'giftcertificate'){
				
				$tmp_user = getSessionValue('fuser_info');
				
				$query = "Select * FROM ".$this->giftcertificate_table." where certificate_code='$funding_code' and receiver_email='{$tmp_user['email']}' and active='Active' and amount > 0";
				
				$giftcertificate_info = $this->executeOne($query);
				//print_r ($giftcertificate_info);
				return $giftcertificate_info;
			}
			
			return array();
		}
		
		function isFundingExist ($funding_table, $funding_id) {
			
			if ($funding_table == $this->promotion_table) {
				$query = "Select * FROM ".$this->tmpinternalfunding_table." where product_table='$funding_table' and  
				session_id='".session_id()."'";
			}
			else{
				$query = "Select * FROM ".$this->tmpinternalfunding_table." where product_table='$funding_table' and 
				product_id=$funding_id and session_id='".session_id()."'";
						
			}
			
			$funding_info = $this->executeOne($query);
			//print_r ($promotion_info);
			return $funding_info;
			
			/*
			if ($funding_type == 'promotion'){
				$query = "Select * FROM ".$this->tmpinternalfunding_table." where product_table='$this->promotion_table' and session_id='".session_id()."'";
				
				$promotion_info = $this->executeOne($query);
				//print_r ($promotion_info);
				return $promotion_info;
			}
			
			if ($funding_type == 'giftcard'){
				$query = "Select * FROM ".$this->tmpinternalfunding_table." where product_table='$this->giftcard_table' and session_id='".session_id()."'";
			}
			
			if ($funding_type == 'giftcertificate'){
			
			}
			*/
			
		}
		
		function resetFunding(){
			$tmp_user = getSessionValue ('fuser_info');
			$query = "Update $this->tmpinternalfunding_table set amount=0 where session_id='".session_id()."' and 	customer_id=".$tmp_user['id']." and product_table <> 'abm_promotion'";
			$this->executeQuery($query);
			//exit();
		}
		
		function updateAllFunding (){
			
			$this->resetFunding();
			$funding_array = $this->getTempFundingInfo();
			
			foreach ($funding_array as $funding_info){
				
				if ( $funding_info['product_table'] == $this->promotion_table ){
					
					$this->addFunding ('promotion', $funding_info['info']['promo_code'], '');
				}
				
				else if ( $funding_info['product_table'] == $this->giftcard_table ){
					
					
					$this->addFunding ('giftcard', $funding_info['info']['card_no'], $funding_info['info']['card_pin']);
				}
				
				else if ( $funding_info['product_table'] == $this->giftcertificate_table ){
					
					//$this->resetFunding($funding_info['id']);
					$this->addFunding ('giftcertificate', $funding_info['info']['certificate_code'], '');
				}
				
			}
			
		}
		
		function addFunding ($funding_type, $funding_code, $funding_pin){
			
			$tmp_user = getSessionValue('fuser_info');
			
			$funding_info = $this->validateInternalFunding ($funding_type, $funding_code, $funding_pin);
			//echo $funding_type.', '.$funding_code.', '.$funding_pin;
			//print_r ($funding_info);
			//exit();
			
			if ($funding_info){
				
				$record = array();
				$record['is_flat'] = 1;
								
				if ($funding_type == 'promotion') {
					$record['funding_priority'] = 1;
					
					if ( !$funding_info['is_flat'] )
						$record['is_flat'] = 0;
										
					$record['product_table'] = $this->promotion_table;
					$exist_info = $this->isFundingExist ($this->promotion_table, $funding_info['id']);
				}
					
				if ($funding_type == 'giftcertificate'){
					$record['funding_priority'] = 2;
					
					$record['product_table'] = $this->giftcertificate_table;
					$exist_info = $this->isFundingExist ($this->giftcertificate_table, $funding_info['id']);
				}
								
				if ($funding_type == 'giftcard'){
					$record['funding_priority'] = 3;
					
					$record['product_table'] = $this->giftcard_table;
					$exist_info = $this->isFundingExist ($this->giftcard_table, $funding_info['id']);
				}
				
				$record['product_id'] = $funding_info['id'];
				$record['session_id'] = session_id();
				$record['customer_id'] = $tmp_user['id'];
				
				if ($funding_type != 'promotion') {
					$paymentAmount = $this->getTotalAmount($record['product_table'], $record['product_id']);
					//exit();
					//echo $paymentAmount.',';
					//exit();
					if ($paymentAmount <= 0)
						return -1;
						
					$record['amount'] = ($paymentAmount < $funding_info['amount'])?$paymentAmount:$funding_info['amount'];
				}
				else {
					
					$paymentAmount = $this->getCartAmount();
					//echo $paymentAmount.',';
					//exit();
					
					if ($funding_info['total_gt'] > 0 && $paymentAmount >= $funding_info['total_gt'] )
						$record['amount'] = $funding_info['amount'];
					else
						$record['amount'] = 0;
					
					//echo $record['amount'].',';
					//exit();
				}
				
				if ($exist_info['id']) {
					//echo $exist_info['id'];
					getConfigValue('dbhandler')->db->AutoExecute($this->tmpinternalfunding_table, $record, 'UPDATE', "id={$exist_info['id']}");
					return getConfigValue('dbhandler')->db->Affected_Rows();
				}
				else{
					//echo $this->tmpinternalfunding_table;
					
					getConfigValue('dbhandler')->db->AutoExecute($this->tmpinternalfunding_table, $record, 'INSERT');
					//print_r ($record);
					return getConfigValue('dbhandler')->db->Insert_ID ();
				}
				//echo getConfigValue('dbhandler')->db->ErrorMsg();
				
			}
			
			return -1;
		}
		
		
		function getTempFundingInfo ($except_table='', $except_id=''){
			
			$tmp_user = getSessionValue('fuser_info');
			$query = "Select * from $this->tmpinternalfunding_table where session_id='".session_id()."' and customer_id=".$tmp_user ['id'];
			
			if ($except_table && $except_id)
				$query .= " and (product_table <> '".$except_table."' and product_id <> ".$except_id.") or product_table <> '$except_table' ";	
			
			$query .= " order by funding_priority asc";
			$funding_array = $this->executeAll($query);
			
			if ( $funding_array ){
				
				foreach ($funding_array as $key=>$funding_info){
					
					$query = "select * from ".$funding_info['product_table']." where id=".$funding_info['product_id'];
					$funding_details = $this->executeOne($query);
					$funding_array[$key]['info'] = $funding_details;
					
				}
				
			}
			
			//print_r ($funding_array);
			return $funding_array;
			
		}
		
		function getFundingInfo ($session_id='', $except_table='', $except_id=''){
			
			$tmp_user = getSessionValue('fuser_info');
			$query = "Select * from $this->internalfunding_table where customer_id=".$tmp_user ['id'];
			
			if ( empty ($session_id) )
				$query .= "  and session_id='".session_id()."' ";
			else 
				$query .= "  and session_id='".$session_id."' ";
				
			if ($except_table && $except_id)
				$query .= " and (product_table <> '".$except_table."' and product_id <> ".$except_id.") or product_table <> '$except_table' ";	
			
			//9b3aa2221b4d31d56c51a7cef99f21be
			$query .= " order by funding_priority asc";
			$funding_array = $this->executeAll($query);
			
			if ( $funding_array ){
				
				foreach ($funding_array as $key=>$funding_info){
					
					$query = "select * from ".$funding_info['product_table']." where id=".$funding_info['product_id'];
					$funding_details = $this->executeOne($query);
					$funding_array[$key]['info'] = $funding_details;
					
				}
				
			}
			
			//print_r ($funding_array);
			return $funding_array;
			
		}
		
		function removeFunding ($funding_id){
			
			$query = "Delete from $this->tmpinternalfunding_table where id=$funding_id";
			
			$this->executeQuery($query);
			return getConfigValue('dbhandler')->db->Affected_Rows();
			
		}
		
		public function getTotalAmount ( $except_table='', $except_id='' ){
		
			$tmp_user = getSessionValue ('fuser_info');
			//print_r ($tmp_user);
			$query = "Select * FROM ".$this->session_table." where session_id='".session_id()."' and customer_id=".$tmp_user['id'];
					
			
			$cart_array = $this->executeAll($query);
			$total_price = 0;
			
			if ($cart_array){
				foreach ($cart_array as $cart_info){
					$total_price += $cart_info['qty']*$cart_info['price'];			
				}
			}
			
			$funding_array = $this->getTempFundingInfo ($except_table, $except_id);
			//print_r ($funding_array);
			
			//exit();
			$funding_total = 0;
			
			if ($funding_array):
				foreach ($funding_array as $funding_info):
					$funding_total += ($funding_info['is_flat']==1)?$funding_info['amount']:($total_price*$funding_info['amount']/100);
					//echo $funding_total."<br />";
					/*
					if ($funding_type != 'promotion') {
						//$paymentAmount = $this->getTotalAmount();
						//exit();
						//echo $paymentAmount.',';
						echo $record['amount'] = ($paymentAmount < $funding_info['amount'])?$paymentAmount:$funding_info['amount'];
					}
					else {
						
						$paymentAmount = $this->getTotalAmount();
						if ($funding_info['total_gt'] > 0 && $paymentAmount >= $funding_info['total_gt'] )
							$record['amount'] = $funding_info['amount'];
						else
							$record['amount'] = 0;
					}
					*/
										
				endforeach;
			endif;
			
			return ($total_price-$funding_total);
		}
		
		public function getCartAmount (  ){
		
			$tmp_user = getSessionValue ('fuser_info');
			//print_r ($tmp_user);
			$query = "Select * FROM ".$this->session_table." where session_id='".session_id()."' and customer_id=".$tmp_user['id'];
					
			
			$cart_array = $this->executeAll($query);
			$total_price = 0;
			
			if ($cart_array){
				foreach ($cart_array as $cart_info){
					$total_price += $cart_info['qty']*$cart_info['price'];			
				}
			}
			
			return $total_price;
		}
	
}
?>