<?php
	/********************************************
	PayPal API Module
	 
	Defines all the global variables and the wrapper functions 
	********************************************/
	class InternalPayment extends parser{
	
		function addTransactionInfo ($product_table, $product_id, $is_flat, $amount, $funding_priority){
			
			//echo getConfigValue('table_prefix');
			$this->data['session_id'] = session_id();
			$tmp_user = getSessionValue ('fuser_info');
					
			$this->data['customer_id'] = $tmp_user['id'];
			//$this->data['transactionid'] = $TRANSACTIONID;
			
			$this->data['product_table'] = $product_table;
			$this->data['product_id'] = $product_id;
			
			$this->data['is_flat'] = $is_flat;
			$this->data['amount'] = $amount;
			$this->data['funding_priority'] = $funding_priority;
						//if ($product_table && pro)
			return $this->executeAdd( getConfigValue('table_prefix').'internalfundingdetails', array('session_id', 'customer_id','transactionid', 'product_table', 'amount', 'product_id', 'funding_priority', 'is_flat') );
			
			
		}
		
		function addUsertTransactionInfo ($amount, $transaction_for, $payment_gateway, $payment_table, $payment_id, $transaction_type){
			
			//echo getConfigValue('table_prefix');
			//$this->data['session_id'] = session_id();
			$this->data['session_id'] = session_id();
			
			$this->data['amount'] = $amount;
			$this->data['transaction_for'] = $transaction_for;
			$this->data['payment_gateway'] = $payment_gateway;
			$this->data['payment_table'] = $payment_table;
			$this->data['payment_id'] = $payment_id;
			$this->data['transaction_type'] = $transaction_type;
			
			$tmp_user = getSessionValue ('fuser_info');
			$this->data['customer_id'] = $tmp_user['id'];
			
			$this->data['date'] = time();
			$this->data['active'] = 'Active';
			//$this->data['transactionid'] = $TRANSACTIONID;
			
			return $this->executeAdd( getConfigValue('table_prefix').'usertransaction', array('amount', 'transaction_for','payment_gateway', 'payment_table', 'payment_id', 'transaction_type', 'customer_id', 'date', 'active') );
			
			
		}
	
	}
?>