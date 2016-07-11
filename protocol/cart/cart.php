<?php
abstract class cart extends parser
{   
		var $cart_table;
		var $session_table;
		var $payment_method ;
		var $wishlist_table;
		
		var $protocols;
		var $protocolsList = array('fundingmanager');
	
		public function cart(){
		
			require_once ("paypal/PaypalPayment.php"); 
			require_once ("internal/InternalPayment.php"); 
			
			$this->payment_via_paypal = new PaypalPayment();
			$this->payment_via_internal = new InternalPayment();
			//$this->payment_method = new GcheckoutPayment();
			
			$this->cart_table = getConfigValue('table_prefix').'cart';
			$this->session_table = getConfigValue('table_prefix').'session';
			$this->wishlist_table = getConfigValue('table_prefix').'wishlist';
			
			initProtocols (getConfigValue('protocol'), $this->protocolsList);
			$this->protocols = loadProtocols ($this->protocolsList);
		
		}
		
		public function addToCart ($product_table, $product_id, $qty, $price){
			
			$record = array();
			$record['product_table'] = $product_table;
			$record['product_id'] = $product_id;
			
			if (empty ($qty) )
				$qty = 1;
				
			$record['qty'] = $qty;
			$record['price'] = $price;
			$record['session_id'] = session_id();
			$tmp_user = getSessionValue ('fuser_info');
			$record['customer_id'] = $tmp_user['id'];
			$record['active'] = 'Active'; 
			
			//print_r ($record);
			//echo $this->session_table;
			
			/*$chk_record = $this->numOfRecord ("Select * From ".$this->session_table." Where id=".$xls_data[$i][1]);
			if ($chk_record > 0){
				getConfigValue('dbhandler')->db->AutoExecute($this->table_name, $record, 'UPDATE',  'id='.$xls_data[$i][1]);
			}*/	
			getConfigValue('dbhandler')->db->AutoExecute($this->session_table, $record, 'INSERT');
			//echo $this->getError();
			//$this->executeAdd( $this->table_name, array('title','price','description','active') );
			return getConfigValue('dbhandler')->db->Insert_ID ();
			//print_r ($reord);
		}
		public function addToWishlist ($product_table, $product_id){
			
			$record = array();
			$record['product_table'] = $product_table;
			$record['product_id'] = $product_id;
			
			if (empty ($qty) )
				$qty = 1;
				
			//$record['qty'] = $qty;
			//$record['price'] = $price;
			$record['session_id'] = session_id();
			$tmp_user = getSessionValue ('fuser_info');
			$record['customer_id'] = $tmp_user['id'];
			$record['active'] = 'Active'; 
			
			//print_r ($record);
			//echo $this->session_table;
			
			/*$chk_record = $this->numOfRecord ("Select * From ".$this->session_table." Where id=".$xls_data[$i][1]);
			if ($chk_record > 0){
				getConfigValue('dbhandler')->db->AutoExecute($this->table_name, $record, 'UPDATE',  'id='.$xls_data[$i][1]);
			}*/	
			getConfigValue('dbhandler')->db->AutoExecute($this->wishlist_table, $record, 'INSERT');
			//echo $this->getError();
			//$this->executeAdd( $this->table_name, array('title','price','description','active') );
			return getConfigValue('dbhandler')->db->Insert_ID ();
			//print_r ($reord);
		}
		
		public function updateCart($product_table, $product_id, $qty, $price){
			
			$record = array();
			$record['product_table'] = $product_table;
			$record['product_id'] = $product_id;
			$record['qty'] = $qty;
			$record['price'] = $price;
			$record['session_id'] = session_id();
			$tmp_user = getSessionValue ('fuser_info');
			$record['customer_id'] = $tmp_user['id'];
						
			getConfigValue('dbhandler')->db->AutoExecute($this->session_table, $record, 'UPDATE', " product_table='$product_table' and product_id=$product_id and session_id='".session_id()."'");
			//echo $this->getError();
			return getConfigValue('dbhandler')->db->Affected_Rows();
		}
		
		public function viewCart ($product_table){
			
			$tmp_user = getSessionValue ('fuser_info');
			$query = "Select $this->session_table.id as su_id,$this->session_table.qty, $this->session_table.product_table, $product_table.* FROM ".$this->session_table.','.$product_table." where product_table='$product_table' and session_id='".session_id()."' and customer_id=".$tmp_user['id']." and $product_table.id=".$this->session_table.".product_id";
			return $this->executeAll($query);
			
		}
		
		public function orderStatus ($product_table, $session_id=''){
			
			$tmp_user = getSessionValue ('fuser_info');
			$query = "Select $this->cart_table.id as su_id,$this->cart_table.qty,$this->cart_table.update_time,$this->cart_table.payment_status as payment_status, $product_table.* FROM ".$this->cart_table.','.$product_table." where product_table='$product_table' ";

			if ($session_id)
				$query .=  " and $this->cart_table.session_id='$session_id' ";	
			
			$query .= " and customer_id=".$tmp_user['id']." and $product_table.id=".$this->cart_table.".product_id order by update_time desc";
			return $this->executeAll($query);
			
		}
		
		public function viewCartList ($product_table, $session_id=''){
			
			$tmp_user = getSessionValue ('fuser_info');
			$query = "Select $this->cart_table.session_id, id, update_time FROM ".$this->cart_table." where product_table='$product_table' ";
		
			$query .= " and customer_id=".$tmp_user['id']." group by session_id order by update_time desc";
			return $this->executeAll($query);
			
		}
		
		
		public function viewWishlist ($product_table){
			
			$tmp_user = getSessionValue ('fuser_info');
			$query = "Select $this->wishlist_table.id as su_id,$this->wishlist_table.qty, $product_table.* FROM ".$this->wishlist_table.','.$product_table." where product_table='$product_table' and session_id='".session_id()."' and customer_id=".$tmp_user['id']." and $product_table.id=".$this->wishlist_table.".product_id";
			return $this->executeAll($query);
			
		}
		
		public function removeFromCart($product_table, $product_id){
			
			$query = 'Delete From '.$this->session_table.' Where ';
			$data = array();
			$where_sql = '';
			
			$where_sql .= 'product_table=? and product_id=? and session_id=?';
			$data[] = $product_table;
			$data[] = $product_id;
			$data[] = session_id();
			
			//print_r ($data);
			$sql = $query.$where_sql;
			$stmt = getConfigValue('dbhandler')->db->Prepare($sql);
			//print_r ($stmt);
			getConfigValue('dbhandler')->db->Execute($stmt, $data);
			//echo $this->showError();
			return getConfigValue('dbhandler')->db->Affected_Rows();
		}
		public function removeFromWishlist($product_table, $product_id){
			
			$query = 'Delete From '.$this->wishlist_table.' Where ';
			$data = array();
			$where_sql = '';
			
			$where_sql .= 'product_table=? and product_id=? and session_id=?';
			$data[] = $product_table;
			$data[] = $product_id;
			$data[] = session_id();
			
			//print_r ($data);
			$sql = $query.$where_sql;
			$stmt = getConfigValue('dbhandler')->db->Prepare($sql);
			//print_r ($stmt);
			getConfigValue('dbhandler')->db->Execute($stmt, $data);
			//echo $this->showError();
			return getConfigValue('dbhandler')->db->Affected_Rows();
		}
		
		public function getProductDetailsFromCart ($product_table, $product_id){
			
			$query = "SELECT * FROM ".$this->session_table." WHERE product_table='$product_table' and product_id=$product_id and session_id='".session_id()."'";
			$product_details = $this->executeOne($query);
			return $product_details;
			
		}
		public function getProductDetailsFromWishlist ($product_table, $product_id){
			
			$query = "SELECT * FROM ".$this->wishlist_table." WHERE product_table='$product_table' and product_id=$product_id and session_id='".session_id()."'";
			$product_details = $this->executeOne($query);
			return $product_details;
			
		}
		
		public function getTotalAmount ( $product_table ){
		
			$tmp_user = getSessionValue ('fuser_info');
			//print_r ($tmp_user);
			$query = "Select * FROM ".$this->session_table." where product_table='$product_table' and session_id='".session_id()."' and customer_id=".$tmp_user['id'];
			
			$cart_array = $this->executeAll($query);
			$total_price = 0;
			foreach ($cart_array as $cart_info){
				$total_price += $cart_info['qty']*$cart_info['price'];			
			}
			
			//echo $total_price;
			$funding_array = $this->protocols['fundingmanager_instance']->getTempFundingInfo ();
			//print_r ($funding_array);
			//exit();
			$funding_total = 0;
			
			if ($funding_array):
				foreach ($funding_array as $funding_info):
					$funding_total += ($funding_info['is_flat']==1)?$funding_info['amount']:($total_price*$funding_info['amount']/100);
				endforeach;
			endif;
			
			return ($total_price-$funding_total);
		}
		
		
		public function existInCart($product_table, $product_id){
			
			$query = "SELECT * FROM ".$this->session_table." WHERE product_table='$product_table' and product_id=$product_id and session_id='".session_id()."'";
			return $this->numOfRecord ($query) ;
			
		} 
		
		public function existInWishlist($product_table, $product_id){
			
			$query = "SELECT * FROM ".$this->wishlist_table." WHERE product_table='$product_table' and product_id=$product_id and session_id='".session_id()."'";
			return $this->numOfRecord ($query) ;
			
		} 
		
		function noOfRecordInCart ($product_table = ''){
			
				$query = "SELECT * FROM ".$this->session_table." WHERE session_id='".session_id()."'";
				if ($product_table )
					$query .= " and product_table='$product_table' "; 		
				
				//$_no = $this->numOfRecord ($query);
				return $this->numOfRecord ($query);
		}
		
		function noOfRecordInWishlist ($product_table = ''){
			
				$query = "SELECT * FROM ".$this->wishlist_table." WHERE session_id='".session_id()."'";
				if ($product_table )
					$query .= " and product_table='$product_table' "; 		
					
				return $this->numOfRecord ($query) ;
		}
}
?>