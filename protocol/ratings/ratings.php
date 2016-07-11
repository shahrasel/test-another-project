<?php
abstract class ratings extends parser
{   
		var $ratings_table;
				
		public function ratings(){
			//require_once ("paypal/PaypalPayment.php"); 
			//require_once ("gcheckout/GcheckoutPayment.php"); 
			
			//$this->payment_method = new GcheckoutPayment();
			$this->ratings_table = getConfigValue('table_prefix').'ratings';
			//$this->user_transaction_table = getConfigValue('table_prefix').'UserTransaction';
		}
		
		public function addRatings ($product_table, $product_id, $rating, $active='Inactive'){
			
			$is_auth = chkFAuthentication ();
			
			if ($is_auth){
				
				$tmp_user = getSessionValue ('fuser_info');
							
				$is_rated = $this->checkAlreadyRated ($product_table, $product_id, $tmp_user['id']);
				
				if ( $is_rated <= 0) {
	
					$this->error_string = '';
					$record = array();
					$record['product_table'] = $product_table;
					$record['product_id'] = $product_id;
					$record['rating'] = $rating;
					$record['user_id'] = $tmp_user['id'];
					//$record['user_name'] = $tmp_user_info['name'];
					$record['active'] = $active; 
					$record['date'] = time(); 		
					
					//print_r ($record);
					getConfigValue('dbhandler')->db->AutoExecute($this->ratings_table, $record, 'INSERT');
					//echo $this->error_string;
					//echo $this->getError();
					
					$is_save = getConfigValue('dbhandler')->db->Insert_ID ();
					
					if ($is_save){
						//return 2.3;
						return $this->getRatedValue  ($product_table, $product_id);
					}
				}
				
				return -2; // already rated
			}
			else{
				return -3;//un authetetic access
			}
		}
		
		public function checkAlreadyRated ($product_table, $product_id, $user_id){
		
			$query = "Select * FROM ".getConfigValue('table_prefix').'ratings'." where user_id=$user_id and product_table='$product_table' and product_id=$product_id";
			$is_exist = 0;
			$is_exist = $this->numOfRecord ($query);
			return $is_exist;
		}	
		
		public function getRatingInfo ($product_table, $product_id, $active='Active'){
			
			$no_of_rating = $this->getNoOfRating ($product_table, $product_id, $active);
			$rating_value =  $this->getRatedValue ($product_table, $product_id, $active);
			
			$ratings = array();
			
			$ratings['no_of_rating'] = $no_of_rating;
			$ratings['rating_value'] = $rating_value;
			
			return $ratings;
		}
		
		public function getNoOfRating ($product_table, $product_id, $active='Active'){
			
			$query = 'Select COUNT(rating) as no_of_rating FROM '.$this->ratings_table." where product_table='$product_table' and  product_id=$product_id ";
			
			if ($active != 'All'){
				$query .= " and active='$active' order by id desc "; 
			}
			$no_of_rating_array = $this->executeOne($query);
			return $no_of_rating_array['no_of_rating'];
		}
				
			
		public function getRatedValue ($product_table, $product_id, $active='Active'){
									
			//$user_info_table = getConfigValue ('table_prefix').'user'; 
			$query = 'Select SUM(rating) as total_rating FROM '.$this->ratings_table." where product_table='$product_table' and  product_id=$product_id ";
			
			if ($active != 'All'){
				$query .= " and active='$active' order by id desc "; 
			}
			$total_rating_array = $this->executeOne($query);
			//return $total_rating_array[total_rating];
			
			$query = 'Select COUNT(rating) as no_of_rating FROM '.$this->ratings_table." where product_table='$product_table' and  product_id=$product_id ";
			
			if ($active != 'All'){
				$query .= " and active='$active' order by id desc "; 
			}
			$no_of_rating_array = $this->executeOne($query);
			//return $no_of_rating_array['no_of_rating'];
			
			
			$rate = @round ( ($total_rating_array[total_rating]*1.0/$no_of_rating_array['no_of_rating']), 2);
			//print_r ($ratings_array);
			//$total_balance = $balance_array['amount'];
						
			return $rate;
			
		}
							
}
?>