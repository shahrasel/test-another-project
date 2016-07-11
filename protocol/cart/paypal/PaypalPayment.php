<?php
	/********************************************
	PayPal API Module
	 
	Defines all the global variables and the wrapper functions 
	********************************************/
	class PaypalPayment extends parser{
	
		var $PROXY_HOST = '';
		var $PROXY_PORT = '';
	
		var $SandboxFlag = true;
		
		var $API_UserName="";
		var $API_Password="";
		var $API_Signature="";
		// BN Code 	is only applicable for partners
		var $sBNCode = "";
		var $API_Endpoint;
		var $PAYPAL_URL;
		var $USE_PROXY;
		var $version;
	
		function PaypalPayment(){
			
			if (session_id() == "") 
				session_start();
			
			//echo getConfigValue('module');
			
			$this->PROXY_HOST = getConfigValue ('paypal_api_proxyhost');//'127.0.0.1';
			$this->PROXY_PORT = getConfigValue ('paypal_api_proxyhost');//'808';
		
			$this->SandboxFlag = true;
			
			//echo getConfigValue ('paypal_api_username');
			$this->API_UserName = getConfigValue ('paypal_api_username');//"roni_api1.annanovas.com";
			$this->API_Password = getConfigValue ('paypal_api_pass');//"3XS348A8NFZFJZP2";
			$this->API_Signature = getConfigValue ('paypal_api_signature'); 
			//"A2Ucufe94aDUYq5YJCa.dEVUH6RwAH3R7fVJIWwVWOUXV1Os9O-dHKCc";
			
			$this->sBNCode = getConfigValue ('paypal_api_sbncode'); //"PP-ECWizard";
			
			if (getConfigValue ('paypal_api_sandbox') == 1) 
				$this->SandboxFlag = true;
				
			if ($this->SandboxFlag == true) 
			{
				$this->API_Endpoint = "https://api-3t.sandbox.paypal.com/nvp";
				$this->PAYPAL_URL = "https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&useraction=commit&token=";
			}
			else
			{
				$this->API_Endpoint = "https://api-3t.paypal.com/nvp";
				$this->PAYPAL_URL = "https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=";
			}
			
			if ( getConfigValue ('paypal_api_useproxy') != 1)
				$this->USE_PROXY = false;
			
			$this->version = "2.3";
		}
		
		function addTransactionInfo ($TRANSACTIONID , $product_table='', $product_id=''){
			
			//echo getConfigValue('table_prefix');
			$this->data['session_id'] = session_id();
			$tmp_user = getSessionValue ('fuser_info');
					
			$this->data['customer_id'] = $tmp_user['id'];
			$this->data['transactionid'] = $TRANSACTIONID;
			
			$this->data['product_table'] = $product_table;
			$this->data['product_id'] = $product_id;
						//if ($product_table && pro)
			return $this->executeAdd( getConfigValue('table_prefix').'paypaldetails', array('session_id', 'customer_id','transactionid', 'product_table', 'product_id') );
			
			
		}
		
		function addUsertTransactionInfo ($amount, $transaction_for, $payment_gateway, $payment_table, $payment_id, $transaction_type){
			
			//echo getConfigValue('table_prefix');
			//$this->data['session_id'] = session_id();
			
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
		
		/* An express checkout transaction starts with a token, that
	   identifies to PayPal your transaction
	   In this example, when the script sees a token, the script
	   knows that the buyer has already authorized payment through
	   paypal.  If no token was found, the action is to send the buyer
	   to PayPal to first authorize payment
	*/

	
	
	/*   
	'-------------------------------------------------------------------------------------------------------------------------------------------
	' Purpose: 	Prepares the parameters for the SetExpressCheckout API Call.
	' Inputs:  
	'		paymentAmount:  	Total value of the shopping cart
	'		currencyCodeType: 	Currency code value the PayPal API
	'		paymentType: 		paymentType has to be one of the following values: Sale or Order or Authorization
	'		returnURL:			the page where buyers return to after they are done with the payment review on PayPal
	'		cancelURL:			the page where buyers return to when they cancel the payment review on PayPal
	'--------------------------------------------------------------------------------------------------------------------------------------------	
	*/
		function CallShortcutExpressCheckout( $paymentAmount, $currencyCodeType, $paymentType, $returnURL, $cancelURL) 
		{
			$_SESSION['TOKEN']='';	//------------------------------------------------------------------------------------------------------------------------------------
			// Construct the parameter string that describes the SetExpressCheckout API call in the shortcut implementation
			
			$nvpstr="&Amt=". $paymentAmount;
			$nvpstr = $nvpstr . "&PAYMENTACTION=" . $paymentType;
			$nvpstr = $nvpstr . "&ReturnUrl=" . $returnURL;
			$nvpstr = $nvpstr . "&CANCELURL=" . $cancelURL;
			$nvpstr = $nvpstr . "&CURRENCYCODE=" . $currencyCodeType;
						
			$resArray=$this->hash_call("SetExpressCheckout", $nvpstr);
			$ack = strtoupper($resArray["ACK"]);
			if($ack=="SUCCESS")
			{
				$token = urldecode($resArray["TOKEN"]);
				$_SESSION['TOKEN']=$token;
			} 
			return $resArray;
		}

		/*   
		'-------------------------------------------------------------------------------------------------------------------------------------------
		' Purpose: 	Prepares the parameters for the SetExpressCheckout API Call.
		' Inputs:  
		'		paymentAmount:  	Total value of the shopping cart
		'		currencyCodeType: 	Currency code value the PayPal API
		'		paymentType: 		paymentType has to be one of the following values: Sale or Order or Authorization
		'		returnURL:			the page where buyers return to after they are done with the payment review on PayPal
		'		cancelURL:			the page where buyers return to when they cancel the payment review on PayPal
		'		shipToName:		the Ship to name entered on the merchant's site
		'		shipToStreet:		the Ship to Street entered on the merchant's site
		'		shipToCity:			the Ship to City entered on the merchant's site
		'		shipToState:		the Ship to State entered on the merchant's site
		'		shipToCountryCode:	the Code for Ship to Country entered on the merchant's site
		'		shipToZip:			the Ship to ZipCode entered on the merchant's site
		'		shipToStreet2:		the Ship to Street2 entered on the merchant's site
		'		phoneNum:			the phoneNum  entered on the merchant's site
		'--------------------------------------------------------------------------------------------------------------------------------------------	
		*/
		function CallMarkExpressCheckout( $paymentAmount, $currencyCodeType, $paymentType, $returnURL, 
																		$cancelURL, $shipToName, $shipToStreet, $shipToCity, $shipToState,
									  								$shipToCountryCode, $shipToZip, $shipToStreet2, $phoneNum
																		) 
		{	
		
			$_SESSION['TOKEN']='';					
			$nvpstr="&Amt=". $paymentAmount;
			$nvpstr = $nvpstr . "&PAYMENTACTION=" . $paymentType;
			$nvpstr = $nvpstr . "&ReturnUrl=" . $returnURL;
			$nvpstr = $nvpstr . "&CANCELURL=" . $cancelURL;
			$nvpstr = $nvpstr . "&CURRENCYCODE=" . $currencyCodeType;
			$nvpstr = $nvpstr . "&ADDROVERRIDE=1";
			$nvpstr = $nvpstr . "&SHIPTONAME=" . $shipToName;
			$nvpstr = $nvpstr . "&SHIPTOSTREET=" . $shipToStreet;
			$nvpstr = $nvpstr . "&SHIPTOSTREET2=" . $shipToStreet2;
			$nvpstr = $nvpstr . "&SHIPTOCITY=" . $shipToCity;
			$nvpstr = $nvpstr . "&SHIPTOSTATE=" . $shipToState;
			$nvpstr = $nvpstr . "&SHIPTOCOUNTRYCODE=" . $shipToCountryCode;
			$nvpstr = $nvpstr . "&SHIPTOZIP=" . $shipToZip;
			$nvpstr = $nvpstr . "&PHONENUM=" . $phoneNum;
			
			
			$resArray=$this->hash_call("SetExpressCheckout", $nvpstr);
			$ack = strtoupper($resArray["ACK"]);
			if($ack=="SUCCESS")
			{
				$token = urldecode($resArray["TOKEN"]);
				$_SESSION['TOKEN']=$token;
			}
				 
			return $resArray;
		}
	
		
		function GetShippingDetails( $token )
		{
			$_SESSION['payer_id'] = '';
			
			$nvpstr="&TOKEN=" . $token;
	
			$resArray=$this->hash_call("GetExpressCheckoutDetails",$nvpstr);
			$ack = strtoupper($resArray["ACK"]);
			if($ack == "SUCCESS")
			{	
				$_SESSION['payer_id'] =	$resArray['PAYERID'];
			} 
			return $resArray;
		}
		
	
		function ConfirmPayment( $FinalPaymentAmt, $paymentType,$currencyCodeType,$payerID )
		{
					
			$token 			= urlencode($_SESSION['TOKEN']);
			$serverName 		= urlencode($_SERVER['SERVER_NAME']);
			$nvpstr  = '&TOKEN=' . $token . '&PAYERID=' . $payerID . '&PAYMENTACTION=' . $paymentType . '&AMT=' . $FinalPaymentAmt;
			$nvpstr .= '&CURRENCYCODE=' . $currencyCodeType . '&IPADDRESS=' . $serverName; 
		 
			$resArray=$this->hash_call("DoExpressCheckoutPayment",$nvpstr);
	
			$ack = strtoupper($resArray["ACK"]);
			return $resArray;
		}
	
		function TransactionDetails ($transactionID){
			$nvpStr="&TRANSACTIONID=$transactionID";
	
			
			$resArray=$this->hash_call("gettransactionDetails",$nvpStr);
			return $resArray;
		}
	
		function RefundAmount ($transaction_id, $refundType, $amount, $currency, $memo){
			
			$nvpStr .= "&TRANSACTIONID=$transaction_id";
			$nvpStr .= "&REFUNDTYPE=$refundType";
			$nvpStr .= "&CURRENCYCODE=$currency";
			$nvpStr .= "&NOTE=$memo";
			
			if(strtoupper($refundType)=="PARTIAL") 
				$nvpStr=$nvpStr."&AMT=$amount";
			
			
			$resArray=$this->hash_call("RefundTransaction",$nvpStr);
			
			return $resArray;		
		}
	
		function CreditCardPayment ( $firstName, $lastName, $creditCardType, $creditCardNumber, $expDateMonth,
																 $expDateYear, $cvv2Number, $address1, $address2, $city, $state, $zip, 
																 $amount, $currencyCode 
																)
		{
				
		
			$padDateMonth = str_pad($expDateMonth, 2, '0', STR_PAD_LEFT);
					
			
			$nvpstr.="&PAYMENTACTION=$paymentType";
			$nvpstr.="&AMT=$amount";
			$nvpstr.="&CREDITCARDTYPE=$creditCardType";
			$nvpstr.="&ACCT=$creditCardNumber";
			$nvpstr.="&EXPDATE=".$padDateMonth.$expDateYear;
			$nvpstr.="&CVV2=$cvv2Number";
			$nvpstr.="&FIRSTNAME=$firstName";
			$nvpstr.="&LASTNAME=$lastName";
			$nvpstr.="&STREET=$address1";
			$nvpstr.="&CITY=$city";
			$nvpstr.="&STATE=$state";
			$nvpstr.="&ZIP=$zip";
			$nvpstr.="&COUNTRYCODE=US";
			$nvpstr.="&CURRENCYCODE=$currencyCode";
			
			
			$resArray=$this->hash_call("doDirectPayment",$nvpstr);
			
			
			return $resArray;
		} 
	
		function hash_call($methodName,$nvpStr)
		{
			//declaring of global variables
			/*global $API_Endpoint, $version, $API_UserName, $API_Password, $API_Signature;
			global $USE_PROXY, $PROXY_HOST, $PROXY_PORT;
			global $gv_ApiErrorURL;
			global $sBNCode;*/
	
			//setting the curl parameters.
			//echo $this->API_Endpoint;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->API_Endpoint);
			curl_setopt($ch, CURLOPT_VERBOSE, 1);
	
			//turning off the server and peer verification(TrustManager Concept).
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_POST, 1);
			
			
			if($this->USE_PROXY)
				curl_setopt ($ch, CURLOPT_PROXY, $this->PROXY_HOST. ":" . $this->PROXY_PORT); 
	
			
			$nvpreq="METHOD=" . urlencode($methodName) . "&VERSION=" . urlencode($this->version) . "&PWD=" . urlencode($this->API_Password) . "&USER=" . urlencode($this->API_UserName) . "&SIGNATURE=" . urlencode($this->API_Signature) . $nvpStr . "&BUTTONSOURCE=" . urlencode($this->sBNCode);
	
			
			curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
	
			
			$response = curl_exec($ch);
	
			
			$nvpResArray=$this->deformatNVP($response);
			$nvpReqArray=$this->deformatNVP($nvpreq);
			$_SESSION['nvpReqArray']=$nvpReqArray;
	
			if (curl_errno($ch)) 
			{
				
				$_SESSION['curl_error_no']=curl_errno($ch) ;
				$_SESSION['curl_error_msg']=curl_error($ch);
	
				
			} 
			else 
			{
				
				curl_close($ch);
			}
	
			return $nvpResArray;
		}
	
		
		function RedirectToPayPal ( $token )
		{
			//global $PAYPAL_URL;
			$payPalURL = $this->PAYPAL_URL . $token;
			header("Location: ".$payPalURL);
		}
	
		
		
		function deformatNVP($nvpstr)
		{
			$intial=0;
			$nvpArray = array();
	
			while(strlen($nvpstr))
			{
				
				$keypos= strpos($nvpstr,'=');
				
				$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);
				
				$keyval=substr($nvpstr,$intial,$keypos);
				$valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
				
				$nvpArray[urldecode($keyval)] =urldecode( $valval);
				$nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
			}
			return $nvpArray;
		}
	
		function getPayPalErro ($resArray){
			
			$tmp_error = '';
			$ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
			$ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
			$ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
			$ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);
			
			$tmp_error .= "SetExpressCheckout API call failed. ";
			$tmp_error .= "Detailed Error Message: " . $ErrorLongMsg;
			$tmp_error .= "Short Error Message: " . $ErrorShortMsg;
			$tmp_error .= "Error Code: " . $ErrorCode;
			$tmp_error .= "Error Severity Code: " . $ErrorSeverityCode;
			
			return $tmp_error;
		}
}
?>