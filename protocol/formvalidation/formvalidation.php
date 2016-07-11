<?php
abstract class formvalidation
{   
	
	//############################# validation #############################
	
	function isvalid($formvalue, $formValidationSchema){
		unsetSessionValue('_formValidation');
		$FORM_VALID = true;
		
		$valid = true;
		if( empty($formvalue) && empty($formValidationSchema) ){
			
			return $FORM_VALID;
		}
		else{
			
			//print_r ($formvalue);
			//foreach( $formvalue as $key=>$value ){
			foreach( $formValidationSchema as $schema ){
				
				$key = $schema['name'];
				$value = $formvalue[$key];	
				//echo $key.',';				
				
				
				//################## start validation function ####################
				$validation = $formValidationSchema[$key] ;
				//print_r ($validation);
				if( isset($validation) && !empty($validation) ){
					
					/** -- edited code --**/
					$isFile = false;
					if(  strstr ($key, 'file') ){
						$isFile = true;
					}
					if( $isFile == true ){
						
						//print_r($formvalue); 
						//print_r ($validation);
						$current_action = getConfigValue ('current_action');
						if (
								($current_action == 'edit' && $value['name']) ||
								$current_action != 'edit' 
						){
							
							//echo $value['name'];
							//exit();
							
							if( $validation['rule']=='notempty' || $value['name']){
								
								
								//exit();	
								$formname = $formvalue['_formName'];
								$inputid = $key;
								
								$warning = '';
								// why issset
								if( isset($validation['message']) && !empty($validation['message']) ){
									$warning = $validation['message'];
								}
								
								$path_info = pathinfo($formvalue[$key]['name']);
								$value['type'] = $path_info['extension'];
								
								
								if( $value['name'] == '' && $validation['rule']=='notempty'){
									$formValidation = getSessionValue('_formValidation');
									$formValidation[$formvalue['_formName']][$key]['error'] = 1;
									$formValidation[$formvalue['_formName']][$key]['errormsg'] = $warning ;
									$formValidation[$formvalue['_formName']][$key]['value'] = $value ;
									setSessionValue('_formValidation', $formValidation);
									$FORM_VALID = false;
								}
								elseif( !empty($value['name']) && !empty($validation['validtype']) && !in_array($value['type'], $validation['validtype'])  ){
									$formValidation = getSessionValue('_formValidation');
									$formValidation[$formvalue['_formName']][$key]['error'] = 1;
									$formValidation[$formvalue['_formName']][$key]['errormsg'] = $warning ;
									$formValidation[$formvalue['_formName']][$key]['value'] = $value ;
									setSessionValue('_formValidation', $formValidation);
									$FORM_VALID = false;
								}
								else if ( $validation['width'] || $validation['height'] ){
									$image_info = getimagesize($value['tmp_name']) ;								
									
								
												
									if ( $validation['width'] && $validation['width'] != $image_info[0]){
										$formValidation = getSessionValue('_formValidation');
										$formValidation[$formvalue['_formName']][$key]['error'] = 1;
										$formValidation[$formvalue['_formName']][$key]['errormsg'] = $warning ;
										$formValidation[$formvalue['_formName']][$key]['value'] = $value ;
										setSessionValue('_formValidation', $formValidation);
										$FORM_VALID = false;
										//print_r ($image_info);
									}
									
									if ( $validation['height'] && $validation['height'] != $image_info[1]){
										$formValidation = getSessionValue('_formValidation');
										$formValidation[$formvalue['_formName']][$key]['error'] = 1;
										$formValidation[$formvalue['_formName']][$key]['errormsg'] = $warning ;
										$formValidation[$formvalue['_formName']][$key]['value'] = $value ;
										setSessionValue('_formValidation', $formValidation);
										$FORM_VALID = false;
										
									}
								}
								else{
									//echo $value['name'];
									//exit();
									$formValidation = getSessionValue('_formValidation');
									$formValidation[$formvalue['_formName']][$key]['error'] = 0;
									$formValidation[$formvalue['_formName']][$key]['errormsg'] = '' ;
									$formValidation[$formvalue['_formName']][$key]['value'] = $value ;
									setSessionValue('_formValidation', $formValidation);
								}
							}
						}
										
					}
					/** -- edited code --**/
					else{
					
						//echo '123';
						//exit();
						$maxlength = 0;
						$minlength = 0;
						if( !empty($validation['maxlength']) ){
							$maxlength = $validation['maxlength'];
						}
						if( !empty($validation['minlength']) ){
							$minlength = $validation['minlength'];
						}			
						
						if( $validation['rule']=='notempty' ){
																
							$formname = $formvalue['_formName'];
							$inputid = $key;
							
							$warning = '';
							/*if( empty($validation['message']) ){
								$warning = $validation['message'];
							}
							else{
								if ($validation['rule'] == 'notempty'){ 
									//print_r ($validation);
									$warning = $validation['message'];
									//echo $warning;
									
								}
							
							}*/
							
							if( !empty($validation['message']) ){
								$warning = $validation['message'];
							}
							$input = $this->checkNotEmpty( $formname, $inputid, $value, $warning, $maxlength, $minlength );
							if( $input==true ){
								$FORM_VALID = false;
							}
								
							$formValidation = getSessionValue('_formValidation');
							$formValidation[$formvalue['_formName']][$key]['value'] = $value ;
							setSessionValue('_formValidation', $formValidation);
							
						}
						elseif( $validation['equalto'] ){
							
							$formname = $formvalue['_formName'];
							$inputid = $key;
							
							$warning = '';
							if( isset($validation['message']) && !empty($validation['message'])  ){
								$warning = $validation['message'];
							}
							if( !isset($validation['required']) || empty($validation['required']) ){
								$validation['required'] = 'true';
							}
							
							$input = $this->checkNotEmptyAndEqualTo( $formname, $inputid, $value, $warning, $formvalue[$validation['equalto']], $maxlength, $minlength );
							if( $input==true ){
								$FORM_VALID = false;
							}
							$formValidation = getSessionValue('_formValidation');
							$formValidation[$formvalue['_formName']][$key]['value'] = $value ;
							setSessionValue('_formValidation', $formValidation);
							
						}
						elseif( $validation['rule']=='date'  ){
							
							
							$formname = $formvalue['_formName'];		
							$inputid = $key;
							
							$warning = '';
							if( isset($validation['message']) && !empty($validation['message'])  ){
								$warning = $validation['message'];
							}
							if( !isset($validation['required']) || empty($validation['required']) ){
								$validation['required'] = 'true';
							}
							
							$input = $this->checkValidDate( $formname, $inputid, $value, $warning, $validation['required'], $maxlength, $minlength );
							if( $input==true ){
								$FORM_VALID = false;										
								
							}
							$formValidation = getSessionValue('_formValidation');
							$formValidation[$formvalue['_formName']][$key]['value'] = $value ;
							setSessionValue('_formValidation', $formValidation);
							
						}
						elseif( $validation['rule']=='email'  ){
							$formname = $formvalue['_formName'];
							$inputid = $key;
							
							$warning = '';
							if( isset($validation['message']) && !empty($validation['message'])  ){
								$warning = $validation['message'];
							}
							if( !isset($validation['required']) || empty($validation['required']) ){
								$validation['required'] = 'true';
							}
							/*if( isset($validation['equalto']) && !empty($validation['equalto']) ){
								$input = $this->checkValidEmailEqualTo( $formname, $inputid, $value, $warning, $validation['required'], $formvalue[$validation['equalto']], $maxlength, $minlength );
								if( $input==true ){
									$FORM_VALID = false;
								}
							}
							else{*/
							$input = $this->checkValidEmail( $formname, $inputid, $value, $warning, $validation['required'], $maxlength, $minlength );
							if( $input==true ){
								$FORM_VALID = false;										
								/*}*/
							}
							$formValidation = getSessionValue('_formValidation');
							$formValidation[$formvalue['_formName']][$key]['value'] = $value ;
							setSessionValue('_formValidation', $formValidation);
						}
						elseif( $validation['rule']=='numeric' ){
							$formname = $formvalue['_formName'];
							$inputid = $key;
							$warning = '';
							if( isset($validation['message']) && !empty($validation['message']) ){
								$warning = $validation['message'];
							}
							if( !isset($validation['required']) || empty($validation['required']) ){
								$validation['required'] = 'true';
							}
							
							/*if( isset($validation['equalto']) && !empty($validation['equalto']) ){
								$input = $this->checkNumeric( $formname, $inputid, $value, $warning, $validation['required'], $formvalue[$validation['equalto']], 'true', $maxlength, $minlength );
								if( $input==true ){
									$FORM_VALID = false;
								}
							}
							else{*/
							$input = $this->checkNumeric( $formname, $inputid, $value, $warning, $validation['required'], $formvalue[$validation['equalto']], 'false', $maxlength, $minlength );
							if( $input==true ){
								$FORM_VALID = false;
								/*}*/
							}
							$formValidation = getSessionValue('_formValidation');
							$formValidation[$formvalue['_formName']][$key]['value'] = $value ;
							setSessionValue('_formValidation', $formValidation);
						}
						elseif( $validation['rule']=='alpha' ){
							$formname = $formvalue['_formName'];
							$inputid = $key;
							$warning = '';
							if( isset($validation['message']) && !empty($validation['message']) ){
								$warning = $validation['message'];
							}
							if( !isset($validation['required']) || empty($validation['required']) ){
								$validation['required'] = 'true';
							}
							/*if( isset($validation['equalto']) && !empty($validation['equalto']) ){
								$input = $this->checkAlpha( $formname, $inputid, $value, $warning, $validation['required'], $formvalue[$validation['equalto']], 'true', $maxlength, $minlength );
								if( $input==true ){
									$FORM_VALID = false;
								}
							}
							else{*/
							$input = $this->checkAlpha( $formname, $inputid, $value, $warning, $validation['required'], $formvalue[$validation['equalto']], 'false', $maxlength, $minlength );
							if( $input==true ){
								$FORM_VALID = false;
							}
							/*}*/
							$formValidation = getSessionValue('_formValidation');
							$formValidation[$formvalue['_formName']][$key]['value'] = $value ;
							setSessionValue('_formValidation', $formValidation);
						}
						elseif( $validation['rule']=='alphanumeric' ){
							$formname = $formvalue['_formName'];
							$inputid = $key;
							$warning = '';
							if( isset($validation['message']) && !empty($validation['message']) ){
								$warning = $validation['message'];
							}
							if( !isset($validation['required']) || empty($validation['required']) ){
								$validation['required'] = 'true';
							}
							/*if( isset($validation['equalto']) && !empty($validation['equalto']) ){
								$input = $this->checkAlphaNumeric( $formname, $inputid, $value, $warning, $validation['required'], $formvalue[$validation['equalto']], 'true', $maxlength, $minlength );
								if( $input==true ){
									$FORM_VALID = false;
								}
							}
							else{*/
							$input = $this->checkAlphaNumeric( $formname, $inputid, $value, $warning, $validation['required'], $formvalue[$validation['equalto']], 'false', $maxlength, $minlength );
							if( $input==true ){
								$FORM_VALID = false;
							}
							/*}*/
							$formValidation = getSessionValue('_formValidation');
							$formValidation[$formvalue['_formName']][$key]['value'] = $value ;
							setSessionValue('_formValidation', $formValidation);
						}
						elseif( $validation['rule']=='custom' && !empty($validation['custom']) ){
							$formname = $formvalue['_formName'];
							$inputid = $key;
							$warning = '';
							if( isset($validation['message']) && !empty($validation['message']) ){
								$warning = $validation['message'];
							}
							if( !isset($validation['required']) || empty($validation['required']) ){
								$validation['required'] = 'true';
							}
							/*if( isset($validation['equalto']) && !empty($validation['equalto']) ){
								$input = $this->checkCustom( $formname, $inputid, $value, $warning, $validation['required'], $formvalue[$validation['equalto']], 'true', $maxlength, $minlength, $validation['custom'] );
								if( $input==true ){
									$FORM_VALID = false;
								}
							}
							else{*/
							$input = $this->checkCustom( $formname, $inputid, $value, $warning, $validation['required'], $formvalue[$validation['equalto']], 'false', $maxlength, $minlength, $validation['custom'] );
							if( $input==true ){
								$FORM_VALID = false;
							}
							/*}*/
							$formValidation = getSessionValue('_formValidation');
							$formValidation[$formvalue['_formName']][$key]['value'] = $value ;
							setSessionValue('_formValidation', $formValidation);
						}
						else{
								//echo '--1';
								//echo $value;		
								//if ($key == 'active')
									//echo '11--';
								$formValidation[$formvalue['_formName']][$key]['value'] = $value ;
								//print_r ($formValidation);
								setSessionValue('_formValidation', $formValidation);
						}
						
						if( $validation['rule']=='required' ){
							$formname = $formvalue['_formName'];
							$inputid = $key;
							
							$warning = '';
							if( !empty($validation['message']) ){
								$warning = $validation['message'];
							}
							if( $value == '' ){
								$formValidation = getSessionValue('_formValidation');
								$formValidation[$formvalue['_formName']][$key]['error'] = 1;
								$formValidation[$formvalue['_formName']][$key]['errormsg'] = $warning ;
								$formValidation[$formvalue['_formName']][$key]['value'] = $value ;
								setSessionValue('_formValidation', $formValidation);
								$FORM_VALID = false;
							}
							else{
								echo '1--';
								$formValidation = getSessionValue('_formValidation');
								$formValidation[$formvalue['_formName']][$key]['error'] = 0;
								$formValidation[$formvalue['_formName']][$key]['errormsg'] = '' ;
								$formValidation[$formvalue['_formName']][$key]['value'] = $value ;
								setSessionValue('_formValidation', $formValidation);
							}																
						}
						
					}
				}
				//################## start validation function ####################
					
				
				else{
					
					//echo '1--';
					$formValidation = getSessionValue('_formValidation');
					$formValidation[$formvalue['_formName']][$key]['error'] = 0;
					$formValidation[$formvalue['_formName']][$key]['errormsg'] = '' ;
					$formValidation[$formvalue['_formName']][$key]['value'] = $value ;
					setSessionValue('_formValidation', $formValidation);
				}
				
			}
			/*if ($FORM_VALID === true){
				echo '12';
				unsetSessionValue('_formValidation');
			}*/
						
			return $FORM_VALID;
		}
	}
	
	
	function checkValidDate( $formname, $inputid, $value, $warning, $required, $maxlength, $minlength ){
		
		//echo $value;
		
		$ret = false;
		if( $maxlength >0 ){
			$ret = $this->checkMaxLength( $formname, $value, $inputid, $maxlength );
		}
		if( $minlength >0 && $ret==false ){
			$ret = $this->checkMinLength( $formname, $value, $inputid, $minlength );
		}
		
		if( $warning == '' ){
			$warning = 'Must be a valid Date Field';
		}
		$check = false;
		if( $required == 'true' ){		
			$check = true;
		}
		else{
			if( $value == '' ){
				$check = false;
			}
			else{
				$check = true;
			}
		}
		
		if( $check == true ){
			if( $ret == false ){
			
				$date_pattern = getConfigValue('date_format');
				$pattern_seperator = '';
				
				if (strstr($value, '-') )
					$pattern_seperator = '-';
				elseif (strstr($value, '/') )
					$pattern_seperator = '/';
				elseif (strstr($value, '.') )
					$pattern_seperator = '.';	
				else
					$pattern_seperator = '-';
					
				//echo $pattern_seperator;
				$tag = 0;
				if ($pattern_seperator){
					$patter_array = explode ($pattern_seperator, $date_pattern);
					$value_array = explode ($pattern_seperator, $value);
					
					//print_r ($value_array);
					//$date_array = date_parse_from_format($date_pattern, $value);
					$date_array = array();
					foreach ($patter_array as $key=>$val){
						
						if (strlen ($val) < strlen ($value_array[$key]) || empty ($value_array[$key]) ){
							//echo $val.'-'.$value_array[$key]; 
							$tag = 1;
							break;
						}
										
					}
				}
				//echo $tag;
												
				if ( $tag == 0){
					$formValidation = getSessionValue('_formValidation');
					$formValidation[$formname][$inputid]['error'] = 0;
					$formValidation[$formname][$inputid]['errormsg'] = '';
					setSessionValue('_formValidation', $formValidation);
					return false;
				}
				else{
					$formValidation = getSessionValue('_formValidation');
					$formValidation[$formname][$inputid]['error'] = 1;
					$formValidation[$formname][$inputid]['errormsg'] = $warning;
					setSessionValue('_formValidation', $formValidation);
					return true;				
				}
			}
			else{
				return true;
			}
		}
		else{
			$formValidation = getSessionValue('_formValidation');
			$formValidation[$formname][$inputid]['error'] = 0;
			$formValidation[$formname][$inputid]['errormsg'] = '';
			setSessionValue('_formValidation', $formValidation);
			return false;
		}
		
	}
	
	function checkMaxLength($formname, $value, $inputid, $length){
		$warning = 'Input Maximum length ' . $length;
		$valueLength = strlen($value);		
		if ( $valueLength > $length ) {	
			$formValidation = getSessionValue('_formValidation');
			$formValidation[$formname][$inputid]['error'] = 1;
			$formValidation[$formname][$inputid]['errormsg'] = $warning;
			setSessionValue('_formValidation', $formValidation);
			return true;
		}
		else{
			$formValidation = getSessionValue('_formValidation');
			$formValidation[$formname][$inputid]['error'] = 0;
			$formValidation[$formname][$inputid]['errormsg'] = '';
			setSessionValue('_formValidation', $formValidation);
			return false;
		}
	}
	
	function checkMinLength($formname, $value, $inputid, $length){
		$warning = 'Input Minimum length ' . $length;
		$valueLength = strlen($value);
		if ( $valueLength < $length ) {
			$formValidation = getSessionValue('_formValidation');
			$formValidation[$formname][$inputid]['error'] = 1;
			$formValidation[$formname][$inputid]['errormsg'] = $warning;
			setSessionValue('_formValidation', $formValidation);
			return true;
		}
		else{
			$formValidation = getSessionValue('_formValidation');
			$formValidation[$formname][$inputid]['error'] = 0;
			$formValidation[$formname][$inputid]['errormsg'] = '';
			setSessionValue('_formValidation', $formValidation);
			return false;
		}
	}
	
	function checkNotEmptyAndEqualTo( $formname, $inputid, $value, $warning, $equalvalue, $maxlength, $minlength ){
		//echo $formname.' '.$inputid.' '.$value.' '.$warning.' '.$equalvalue.' '.$maxlength.' '.$minlength;
		$ret = false;
		$current_action = getConfigValue ('current_action');
		
		if ( 
			($current_action == 'edit' && !strstr($inputid, 'password') ) ||
			($current_action == 'edit' && strstr($inputid, 'password') && $equalvalue)||
			($current_action != 'edit')
		){
		
			if( $maxlength >0 ){
				$ret = $this->checkMaxLength( $formname, $value, $inputid, $maxlength );
			}
			if( $minlength >0 && $ret==false ){
				$ret = $this->checkMinLength( $formname, $value, $inputid, $minlength );
			}
			
			if( $ret == false ){
				if ( $value == '' ) {
					if( $warning == '' ){
						$warning = ' Can not be empty';
					}
					$formValidation = getSessionValue('_formValidation');
					$formValidation[$formname][$inputid]['error'] = 1;
					$formValidation[$formname][$inputid]['errormsg'] = $warning;
					setSessionValue('_formValidation', $formValidation);
					return true;
				}
				else if ( $value <> $equalvalue ) {
					if( $warning == '' ){
						$warning = ' Invalid Input ' ;
					}
					$formValidation = getSessionValue('_formValidation');
					$formValidation[$formname][$inputid]['error'] = 1;
					$formValidation[$formname][$inputid]['errormsg'] = $warning;
					setSessionValue('_formValidation', $formValidation);
					return true;
				}
				else{
					$formValidation = getSessionValue('_formValidation');
					$formValidation[$formname][$inputid]['error'] = 0;
					$formValidation[$formname][$inputid]['errormsg'] = '';
					setSessionValue('_formValidation', $formValidation);
					return false;
					//echo 'here';exit();
				}
			}
			else{
				return true;
			}
		}
		return $ret;
		
	}
	
	
	function checkNotEmpty( $formname, $inputid, $value, $warning, $maxlength, $minlength ){
		//echo $formname.' '.$inputid.' '.$value.' '.$warning.' '.$maxlength.' '.$minlength;
		
		$current_action = getConfigValue ('current_action');
		$ret = false;
		
		//echo '11='.$value;
		if ( 
			($current_action == 'edit' && !strstr($inputid, 'password') ) ||
			($current_action == 'edit' && strstr($inputid, 'password') && $value) ||
			($current_action != 'edit')
		){
						
					
			if( $maxlength >0 ){
				$ret = $this->checkMaxLength( $formname, $value, $inputid, $maxlength );
			}
			if( $minlength >0 && $ret==false ){
				$ret = $this->checkMinLength( $formname, $value, $inputid, $minlength );
			}
			
			if( $ret == false ){
				
				if (  !($value === 0) && empty($value) ) {
					if( $warning == '' ){
						$warning = ' Can not be empty';
					}
					$formValidation = getSessionValue('_formValidation');
					$formValidation[$formname][$inputid]['error'] = 1;
					$formValidation[$formname][$inputid]['errormsg'] = $warning;
					setSessionValue('_formValidation', $formValidation);
					return true;
				}
				else{
					$formValidation = getSessionValue('_formValidation');
					$formValidation[$formname][$inputid]['error'] = 0;
					$formValidation[$formname][$inputid]['errormsg'] = '';
					setSessionValue('_formValidation', $formValidation);
					return false;
					echo 'here';exit();
				}
			}
			else{
				return true;
			}
		}
		return $ret;
	}
	
	
	function checkValidEmail( $formname, $inputid, $value, $warning, $required, $maxlength, $minlength ){
		//echo $formname.' '.$inputid.' '.$value.' '.$warning.' '.$required.' '.$maxlength.' '.$minlength; exit();
		$ret = false;
		if( $maxlength >0 ){
			$ret = $this->checkMaxLength( $formname, $value, $inputid, $maxlength );
		}
		if( $minlength >0 && $ret==false ){
			$ret = $this->checkMinLength( $formname, $value, $inputid, $minlength );
		}
		
		if( $warning == '' ){
			$warning = 'Must be a valid Email Address';
		}
		$check = false;
		if( $required == 'true' ){		
			$check = true;
		}
		else{
			if( $value == '' ){
				$check = false;
			}
			else{
				$check = true;
			}
		}
		
		if( $check == true ){
			if( $ret == false ){
				$pattern = '/^[a-z0-9_\-]+(\.[_a-z0-9\-]+)*@([_a-z0-9\-]+\.)+([a-z]{2}|aero|arpa|biz|com|coop|edu|gov|info|int|jobs|mil|museum|name|nato|net|org|pro|travel)$/';
				preg_match($pattern, $value, $matches, PREG_OFFSET_CAPTURE);
				if ( empty($matches) ){
					$formValidation = getSessionValue('_formValidation');
					$formValidation[$formname][$inputid]['error'] = 1;
					$formValidation[$formname][$inputid]['errormsg'] = $warning;
					setSessionValue('_formValidation', $formValidation);
					return true;
				}
				else{
					$formValidation = getSessionValue('_formValidation');
					$formValidation[$formname][$inputid]['error'] = 0;
					$formValidation[$formname][$inputid]['errormsg'] = '';
					setSessionValue('_formValidation', $formValidation);
					return false;
				}
			}
			else{
				return true;
			}
		}
		else{
			$formValidation = getSessionValue('_formValidation');
			$formValidation[$formname][$inputid]['error'] = 0;
			$formValidation[$formname][$inputid]['errormsg'] = '';
			setSessionValue('_formValidation', $formValidation);
			return false;
		}
	}
		
	
	function checkValidEmailEqualTo( $formname, $inputid, $value, $warning, $required, $equalvalue, $maxlength, $minlength ){
		//echo $formname.' '.$inputid.' '.$value.' '.$warning.' '.$required.' '.$equalvalue.' '.$maxlength.' '.$minlength; exit();
		$ret = false;
		if( $maxlength >0 ){
			$ret = $this->checkMaxLength( $formname, $value, $inputid, $maxlength );
		}
		if( $minlength >0 && $ret==false ){
			$ret = $this->checkMinLength( $formname, $value, $inputid, $minlength );
		}
		
		if( $warning == '' ){
			$warning = 'Must be a valid Email Address';
		}
		$check = false;
		if( $required == 'true' ){
			$check = true;
		}
		else{
			if( $value == '' ){
				$check = false;
			}
			else{
				$check = true;
			}
		}
		
		if( $check == true ){
			if( $ret == false ){
				$pattern = '/^[a-z0-9_\-]+(\.[_a-z0-9\-]+)*@([_a-z0-9\-]+\.)+([a-z]{2}|aero|arpa|biz|com|coop|edu|gov|info|int|jobs|mil|museum|name|nato|net|org|pro|travel)$/';
				preg_match($pattern, $value, $matches, PREG_OFFSET_CAPTURE);
				if ( empty($matches) ){
					$formValidation = getSessionValue('_formValidation');
					$formValidation[$formname][$inputid]['error'] = 1;
					$formValidation[$formname][$inputid]['errormsg'] = $warning;
					setSessionValue('_formValidation', $formValidation);
					return true;
				}
				else if ( $value <> $equalvalue  ) {
					if( $warning == '' ){
						$warning = 'Must be a Addresses must be same';
					}
					$formValidation = getSessionValue('_formValidation');
					$formValidation[$formname][$inputid]['error'] = 1;
					$formValidation[$formname][$inputid]['errormsg'] = $warning;
					setSessionValue('_formValidation', $formValidation);
					return true;
				}
				else{
					$formValidation = getSessionValue('_formValidation');
					$formValidation[$formname][$inputid]['error'] = 0;
					$formValidation[$formname][$inputid]['errormsg'] = '';
					setSessionValue('_formValidation', $formValidation);
					return false;
				}
			}
			else{	
				return true;
			}
		}
		else{
			$formValidation = getSessionValue('_formValidation');
			$formValidation[$formname][$inputid]['error'] = 0;
			$formValidation[$formname][$inputid]['errormsg'] = '';
			setSessionValue('_formValidation', $formValidation);
			return false;
		}
	}
	
	function checkNumeric( $formname, $inputid, $value, $warning, $required, $equalvalue, $equalcheck, $maxlength, $minlength ){
		//echo $formname.' '.$inputid.' '.$value.' '.$warning.' '.$required.' '.$equalvalue.' '.$equalcheck.' '.$maxlength.' '.$minlength; exit();
		$ret = false;
		if( $maxlength >0 ){
			$ret = $this->checkMaxLength( $formname, $value, $inputid, $maxlength );
		}
		if( $minlength >0 && $ret==false ){
			$ret = $this->checkMinLength( $formname, $value, $inputid, $minlength );
		}
		
		if( $warning == '' ){
			$warning = 'Must be a Numeric';
		}
		$check = false;
		if( $required == 'true' ){
			$check = true;
		}
		else{
			if( $value == '' ){
				$check = false;
			}
			else{
				$check = true;
			}
		}
		
		if( $check == true ){
			if( $ret == false ){
				$pattern = '/^[\d]*[\.]*[\d]+$/';
				preg_match($pattern, $value, $matches, PREG_OFFSET_CAPTURE);
				if ( empty($matches) ){
					$formValidation = getSessionValue('_formValidation');
					$formValidation[$formname][$inputid]['error'] = 1;
					$formValidation[$formname][$inputid]['errormsg'] = $warning;
					setSessionValue('_formValidation', $formValidation);
					return true;
				}
				else if ( $equalcheck=='true' && $value <> $equalvalue  ) {
					if( $warning == '' ){
						$warning = 'Must be same';
					}	
					$formValidation = getSessionValue('_formValidation');
					$formValidation[$formname][$inputid]['error'] = 1;
					$formValidation[$formname][$inputid]['errormsg'] = $warning;
					setSessionValue('_formValidation', $formValidation);
					return true;
				}
				else{
					$formValidation = getSessionValue('_formValidation');
					$formValidation[$formname][$inputid]['error'] = 0;
					$formValidation[$formname][$inputid]['errormsg'] = '';
					setSessionValue('_formValidation', $formValidation);
					return false;
				}
			}
			else{	
				return true;
			}
		}
		else{
			$formValidation = getSessionValue('_formValidation');
			$formValidation[$formname][$inputid]['error'] = 0;
			$formValidation[$formname][$inputid]['errormsg'] = '';
			setSessionValue('_formValidation', $formValidation);
			return false;
		}
	}

	function checkAlpha( $formname, $inputid, $value, $warning, $required, $equalvalue, $equalcheck, $maxlength, $minlength ){
		//echo $formname.' '.$inputid.' '.$value.' '.$warning.' '.$required.' '.$equalvalue.' '.$equalcheck.' '.$maxlength.' '.$minlength; exit();
		$ret = false;
		if( $maxlength >0 ){
			$ret = $this->checkMaxLength( $formname, $value, $inputid, $maxlength );
		}
		if( $minlength >0 && $ret==false ){
			$ret = $this->checkMinLength( $formname, $value, $inputid, $minlength );
		}
		
		if( $warning == '' ){
			$warning = 'Must be a Alpha';
		}
		$check = false;
		if( $required == 'true' ){
			$check = true;
		}
		else{
			if( $value == '' ){
				$check = false;
			}
			else{
				$check = true;
			}
		}
		
		if( $check == true ){
			if( $ret == false ){
				$pattern = '/^[a-zA-Z]+$/';
				preg_match($pattern, $value, $matches, PREG_OFFSET_CAPTURE);
				if ( empty($matches) ){					
					$formValidation = getSessionValue('_formValidation');
					$formValidation[$formname][$inputid]['error'] = 1;
					$formValidation[$formname][$inputid]['errormsg'] = $warning;
					setSessionValue('_formValidation', $formValidation);
					return true;					
				}
				else if ( $equalcheck=='true' && $value <> $equalvalue  ) {
					if( $warning == '' ){
						$warning = 'Must be same';
					}
					$formValidation = getSessionValue('_formValidation');
					$formValidation[$formname][$inputid]['error'] = 1;
					$formValidation[$formname][$inputid]['errormsg'] = $warning;
					setSessionValue('_formValidation', $formValidation);	
					return true;
				}
				else{
					$formValidation = getSessionValue('_formValidation');
					$formValidation[$formname][$inputid]['error'] = 0;
					$formValidation[$formname][$inputid]['errormsg'] = '';
					setSessionValue('_formValidation', $formValidation);
					return false;
				}
			}
			else{
				return true;
			}
		}
		else{
			$formValidation = getSessionValue('_formValidation');
			$formValidation[$formname][$inputid]['error'] = 0;
			$formValidation[$formname][$inputid]['errormsg'] = '';
			setSessionValue('_formValidation', $formValidation);
			return false;
		}
	}
	
	function checkAlphaNumeric( $formname, $inputid, $value, $warning, $required, $equalvalue, $equalcheck, $maxlength, $minlength ){
		//echo $formname.' '.$inputid.' '.$value.' '.$warning.' '.$required.' '.$equalvalue.' '.$equalcheck.' '.$maxlength.' '.$minlength; exit();
		$ret = false;
		if( $maxlength >0 ){
			$ret = $this->checkMaxLength( $formname, $value, $inputid, $maxlength );
		}
		if( $minlength >0 && $ret==false ){
			$ret = $this->checkMinLength( $formname, $value, $inputid, $minlength );
		}
		
		if( $warning == '' ){
			$warning = 'Must be a AlphaNumeric';
		}
		$check = false;
		if( $required == 'true' ){
			$check = true;
		}
		else{
			if( $value == '' ){
				$check = false;
			}
			else{
				$check = true;
			}
		}
		
		if( $check == true ){
			if( $ret == false ){
				$pattern = '/^[0-9a-zA-Z]+$/';
				preg_match($pattern, $value, $matches, PREG_OFFSET_CAPTURE);
				if ( empty($matches) ){					
					$formValidation = getSessionValue('_formValidation');
					$formValidation[$formname][$inputid]['error'] = 1;
					$formValidation[$formname][$inputid]['errormsg'] = $warning;
					setSessionValue('_formValidation', $formValidation);
					return true;					
				}
				else if ( $equalcheck=='true' && $value <> $equalvalue  ) {
					if( $warning == '' ){
						$warning = 'Must be same';
					}
					$formValidation = getSessionValue('_formValidation');
					$formValidation[$formname][$inputid]['error'] = 1;
					$formValidation[$formname][$inputid]['errormsg'] = $warning;
					setSessionValue('_formValidation', $formValidation);
					return true;
				}
				else{
					$formValidation = getSessionValue('_formValidation');
					$formValidation[$formname][$inputid]['error'] = 0;
					$formValidation[$formname][$inputid]['errormsg'] = '';
					setSessionValue('_formValidation', $formValidation);
					return false;
				}
			}
			else{
				return true;
			}
		}
		else{
			$formValidation = getSessionValue('_formValidation');
			$formValidation[$formname][$inputid]['error'] = 0;
			$formValidation[$formname][$inputid]['errormsg'] = '';
			setSessionValue('_formValidation', $formValidation);
			return false;
		}
	}
	
	function checkCustom( $formname, $inputid, $value, $warning, $required, $equalvalue, $equalcheck, $maxlength, $minlength, $rule ){
		//echo $formname.' '.$inputid.' '.$value.' '.$warning.' '.$required.' '.$equalvalue.' '.$equalcheck.' '.$maxlength.' '.$minlength.' '.$rule; exit();
		$ret = false;
		if( $maxlength >0 ){
			$ret = $this->checkMaxLength( $formname, $value, $inputid, $maxlength );
		}
		if( $minlength >0 && $ret==false ){
			$ret = $this->checkMinLength( $formname, $value, $inputid, $minlength );
		}
		
		if( $warning == '' ){
			$warning = 'Must be a AlphaNumeric';
		}
		$check = false;
		if( $required == 'true' ){
			$check = true;
		}
		else{
			if( $value == '' ){
				$check = false;
			}
			else{
				$check = true;
			}
		}
		
		if( $check == true ){
			if( $ret == false ){
				$pattern = '/'.$rule.'/';
				preg_match($pattern, $value, $matches, PREG_OFFSET_CAPTURE);
				if ( empty($matches) ){					
					$formValidation = getSessionValue('_formValidation');
					$formValidation[$formname][$inputid]['error'] = 1;
					$formValidation[$formname][$inputid]['errormsg'] = $warning;
					setSessionValue('_formValidation', $formValidation);
					return true;					
				}
				else if ( $equalcheck=='true' && $value <> $equalvalue  ) {
					if( $warning == '' ){
						$warning = 'Must be same';
					}
					$formValidation = getSessionValue('_formValidation');
					$formValidation[$formname][$inputid]['error'] = 1;
					$formValidation[$formname][$inputid]['errormsg'] = $warning;
					setSessionValue('_formValidation', $formValidation);
					return true;
				}
				else{
					$formValidation = getSessionValue('_formValidation');
					$formValidation[$formname][$inputid]['error'] = 0;
					$formValidation[$formname][$inputid]['errormsg'] = '';
					setSessionValue('_formValidation', $formValidation);
					return false;
				}
			}
			else{
				return true;
			}
		}
		else{
			$formValidation = getSessionValue('_formValidation');
			$formValidation[$formname][$inputid]['error'] = 0;
			$formValidation[$formname][$inputid]['errormsg'] = '';
			setSessionValue('_formValidation', $formValidation);
			return false;
		}
	}
	
	
	
	//############################# validation #############################
				
}
?>