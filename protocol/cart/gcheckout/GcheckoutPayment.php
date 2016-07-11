<?php
	/********************************************
	PayPal API Module
	 
	Defines all the global variables and the wrapper functions 
	********************************************/
	class GcheckoutPayment {
		
		var $merchant_id ;  // Your Merchant ID
		var $merchant_key;  // Your Merchant Key
		var $server_type;
		var $currency ;
		//var $cart ;	
		function GcheckoutPayment(){
			
			$this->merchant_id = "338743201215293";  // Your Merchant ID
			$this->merchant_key = "DDliexl-LE7a6JD5jlvDIw";  // Your Merchant Key
			$this->server_type = "sandbox";
			$this->currency = "USD";
		
			require_once('library/googlecart.php');
		  require_once('library/googleitem.php');
		  require_once('library/googleshipping.php');
			require_once('library/googletax.php');	
			
			//$this->cart = new GoogleCart($this->merchant_id, $this->merchant_key, $this->server_type, $this->currency);
		
		}
	
		function addItem($item_no, $item_name, $item_desc, $item_qty, $item_price){
		
			$item = 'item_'.$item_no;
			$$item = new GoogleItem($item_name,      // Item name
                               $item_desc, // Item      description
                             	 $item_qty, // Quantity
                               $item_price); // Unit price
      $this->cart->AddItem($$item);
			
		}
		
		function addShipping(){
					
		}
		
		function addTax(){
		}
}
?>