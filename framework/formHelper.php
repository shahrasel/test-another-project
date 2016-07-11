<?php
class formHelper{	
	
	var $from_name;
	var $validation_rules = array();
	
	function getName(){
		return $this->form_name;
	}
	
	function initForm( $validation_rules ){
		
		$this->validation_rules = $validation_rules;	
				
	}
	
	function create( $name, $options = array() ){

		$this->form_name = $name;
		//$validation_rules_array = array();
		//require_once ( getConfigValue('formvalidator').$name.".php" );
		//$validation_rules_array = $$name;
		//print_r ($validation_rules_array);
		//setConfigValue('validation_rules', $validation_rules_array);			
		
		//$GLOBALS['validation']['FormName'] = $name;
		$validation_rules_array = getConfigValue('validation_rules');
		$validation_rules_array[$name]['FormName'] = $name;
		
		$form = "<form name=\"".$name."\" action=\"".$options['action']."\" enctype=\"multipart/form-data\" ";
		if( isset($options['method']) && !empty($options['method']) ){
			$form = $form . " method=\"".$options['method']."\" ";
		}
		else{
			$form = $form . " method=\"post\" ";
		}
		
		if( isset($options['onsubmit']) && !empty($options['onsubmit']) ){
			$form = $form . " onsubmit=\"".$options['onsubmit']."\" ";
		}
		$form = $form . "  >  <input type=\"hidden\" name=\"_formName\" id=\"_formName\" value=\"".$name."\" />  ";
		return $form;
	}
	
	function input( $name, $options = array() ){
		
		$fh = new formHelper();
		$formValidation = getSessionValue('_formValidation');
		//print_r ($formValidation);
		//$activeFormName = $GLOBALS['validation']['FormName'] ;
		$activeFormName = $this->form_name ;
		$formValue = $formValidation[$activeFormName][$name]['value'];  
		/** -- modified code --**/
		$isFile = false;
		if( strlen($name)>4 ){
			if( substr($name, (strlen($name)-4), 4) == '' ){
				$isFile = true;
			}
		}
		
		if( $options['type']=='file' ){
			
			$input = '<input type="'.$options['type'].'" name="'.$name.'" id="'.$name.'" ';
			foreach( $options as $key=>$value ){
				if($key<>'type' && $key<>'name' && $key<>'validtype' && $key<>'id' && $value){
					$input = $input .' '. $key .'="'.$value.'" ' ;
				}				
			}
			$input = $input . ' ' .$fh->getValidationScriptFile($name, $this->validation_rules[$name] ) . ' />';
		}		
		
		elseif( $options['type']=='textarea' ){
			
			//print_r ($options);
			//exit();
			
			$input = '<textarea name="'.$name.'" id="'.$name.'" '.$fh->getValidationScript($name, $this->validation_rules[$name]).'  rows="2" cols="2" ';
			
			foreach( $options as $key=>$value ){
				if($key<>'type' && $key<>'name' && $key<>'validtype' && $key<>'id' && $key<>'value' && $value){					
					$input = $input .' '. $key .'="'.$value.'" ' ;					
				}				
			}
			
			
			$formValue = $formValidation[$activeFormName][$name]['value'];
			
			if( !empty($formValue) ){
				
				$input = $input .' >'.$formValue.'' ;
			}
			else{
				$formValue = $options['value'];
				$input = $input .' >'.$formValue.'' ;
			}
			$input = $input . '</textarea> ';
			
		}
		
		/** -- modified code --**/
		elseif ($options['type']=='tinymce' ){
			
			$input = '<textarea name="'.$name.'" id="'.$name.'" '.$fh->getValidationScript($name, $this->validation_rules[$name]).' ';
			
			foreach( $options as $key=>$value ){
				if($key<>'type' && $key<>'name' && $key<>'validtype' && $key<>'id' && $key<>'value' && $value ){					
					$input = $input .' '. $key .'="'.$value.'" ' ;					
				}				
			}
			
			$formValue = $formValidation[$activeFormName][$name]['value'];
			
			if( !empty($formValue) ){
				
				$input = $input .' >'.$formValue.'' ;
			}
			else{
				$formValue = $options['value'];
				$input = $input .' >'.$formValue.'' ;
			}
			$input = $input . '</textarea> ';
			
			htmlHelper::initTinyMCE();
			htmlHelper::initInputForTinyMCE($name);
		}
		
		elseif ($options['type']=='fck' ){
						
			$formValue = $formValidation[$activeFormName][$name]['value'];
			//echo $options['value'];
			if( empty($formValue) ){
				$formValue = $options['value'];
			}
					
			htmlHelper::initFCK();
			$input = htmlHelper::initInputForFCK($name, $formValue);
		}
		
		elseif ($options['type']=='ck' ){
					
			$input = '<textarea name="'.$name.'" id="'.$name.'" '.$fh->getValidationScript($name, $this->validation_rules[$name]).' ';
			
			foreach( $options as $key=>$value ){
				if($key<>'type' && $key<>'name' && $key<>'validtype' && $key<>'id' && $key<>'value' && $value){					
					$input = $input .' '. $key .'="'.$value.'" ' ;					
				}				
			}
			
			$formValue = $formValidation[$activeFormName][$name]['value'];
			
			if( !empty($formValue) ){
				
				$input = $input .' >'.$formValue.'' ;
			}
			else{
				$formValue = $options['value'];
				$input = $input .' >'.$formValue.'' ;
			}
			$input = $input . '</textarea> ';
			
			htmlHelper::initCK();
			$input .= "<script type='text/javascript'>
									var editor = CKEDITOR.replace( '".$name."',
										{
											skin : 'kama',
											filebrowserBrowseUrl : '".getConfigValue('base_url')."ckfiles/'
        							
										});
										CKFinder.SetupCKEditor( editor, { BasePath : '".getConfigValue('base_url')."lib/ckeditor/ckfinder/', RememberLastFolder : false } ) ;
								</script>";
		}
		
		elseif( $options['type']=='selectgroup' ){
			
			//print_r ($options['options']['optionsgroup']);
			//print_r ($formValidation[$activeFormName]);
			
			$input = '<select id="'.$name.'" name="'.$name.'"  ';
			foreach( $options as $key=>$value ){
				if($key<>'type' && $key<>'name' && $key<>'validtype' && $key<>'id' && $key<>'options' && $key<>'selected' && $value){
					$input = $input .' '. $key .'="'.$value.'" ' ;
				}
			}
						
			$input = $input . ' ' .$fh->getValidationScriptSelect($name, $this->validation_rules[$name] ) . ' >';
			$optionValue = '';
			if( !empty($options['options']['optionsgroup']) ){
				
				$formValue = $formValidation[$activeFormName][$name]['value'];
				if ( !empty($formValue) ){
					$options['selected'] = $formValue;
				}
				//echo $options['selected'];
				
				foreach( $options['options']['optionsgroup'] as $optionkey=>$optionvalue ){					
					$optionValue = $optionValue .'<optgroup label="'.$optionkey.'">';
					foreach( $optionvalue as $key=>$value ){					
						if( !empty($options['selected']) && $options['selected']==$value ){
							$optionValue = $optionValue . '<option value="'.$value.'" selected="selected">'.$value.'</option>' ;
						}
						else{
							$optionValue = $optionValue . '<option value="'.$value.'" >'.$value.'</option>' ;
						}					
					}
					$optionValue = $optionValue .'</optgroup>';
				}
				
			}
			$input = $input .' '. $optionValue ;			
			$input = $input . '</select>';
		}			
		elseif( $options['type']=='select' ){
			
			//print_r ($options);
			//print_r ($formValidation);
			
			$input = '<select id="'.$name.'" name="'.$name.'"  ';
			foreach( $options as $key=>$value ){
				if($key<>'type' && $key<>'name' && $key<>'validtype' && $key<>'id' && $key<>'options' && $key<>'selected' && $value){
					$input = $input .' '. $key .'="'.$value.'" ' ;
				}
			}
						
			$input = $input . ' ' .$fh->getValidationScriptSelect($name, $this->validation_rules[$name] ) . ' >';
			$optionValue = '';
			if( !empty($options['options']) ){
				
				$formValue = $formValidation[$activeFormName][$name]['value'];
				if ( !empty($formValue) ){
					$options['selected'] = $formValue;
				}
				
				foreach( $options['options'] as $key=>$value ){					
					if( !empty($options['selected']) && $options['selected']==$key ){
						$optionValue = $optionValue . '<option value="'.$key.'" selected="selected">'.$value.'</option>' ;
					}
					else{
						$optionValue = $optionValue . '<option value="'.$key.'" >'.$value.'</option>' ;
					}					
				}
			}
			$input = $input .' '. $optionValue ;			
			$input = $input . '</select>';
		}
		elseif( $options['type']=='radio' ){
			
			//print_r ($options);
			//exit();
			//echo $name;
			$input_option = '';
			foreach( $options as $key=>$value  ){
				if($key<>'type' && $key<>'name' && $key<>'validtype' && $key<>'id' && $key<>'options' && $key<>'selected' && $value){
					$input_option = $input_option .' '. $key .'="'.$value.'" ' ;
				}
			}
			
			$formValue = $formValidation[$activeFormName][$name]['value'];
			//exit();
			if ( !empty($formValue) ){
				$options['selected'] = $formValue;
			}
			
			//$input = $input . ' ' .$fh->getValidationScriptSelect($name, $this->validation_rules[$name] ) . ' />';
			//$optionValue = '';
			if( !empty($options['options']) ){
				
				
				$formValue = $formValidation[$activeFormName][$name]['value'];
				if ( !empty($formValue) ){
					$options['selected'] = $formValue;
				}
				
				$count =0;
				foreach( $options['options'] as $key=>$value ){					
					if( !empty($options['selected']) && $options['selected']==$key ){
						//$optionValue = $optionValue . '<option value="'.$key.'" selected="selected">'.$value.'</option>' ;
						//'<select id="'.$name.'" name="'.$name.'"  ';
						$input .= '<input type="radio" id="'.$name.$count.'" name="'.$name.'" '.$input_option.' checked="checked" value="'.$key.'" />'.$value ;
					}
					else{
						//$optionValue = $optionValue . '<option value="'.$key.'">'.$value.'</option>' ;
						$input .= '<input type="radio" id="'.$name.$count.'" name="'.$name.'" '.$input_option.' value="'.$key.'" />'.$value ;
					}
					$count++;
				}
			}
			/*foreach( $options['options'] as $key=>$value ){					
				if( !empty($options['selected']) && $options['selected']==$key  ){
					$input .= '<input type="radio" id="'.$name.'" name="'.$name.'" value="'.$key.'" checked="checked" />'.$value ;
				}
				else{
					$input .= '<input type="radio" id="'.$name.'" name="'.$name.'" value="'.$key.'" value="'.$key.'" />'.$value ;
				}					
			}*/
			//echo $input;			
			//$input = $input . ' ' .$fh->getValidationScript($name, $this->validation_rules[$name] ) . ' />';
			
		}
		elseif( $options['type']=='checkbox' ){
			
			$input = '<input type="'.$options['type'].'" name="'.$name.'" id="'.$name.'" ';
			foreach( $options as $key=>$value ){
				if($key<>'type' && $key<>'name' && $key<>'id' && $key<>'value' && $value){
					$input = $input .' '. $key .'="'.$value.'"' ;
				}
			}
			
			//echo $value;
			
			$formValue = $formValidation[$activeFormName][$name]['value'];  //exit();
			if( !empty($formValue) ){
				if ($formValue == 1)
					$input = $input .' checked="checked" ' ;
			}
			else{
				if ($options['value'] == 1)
					$input = $input .' checked="checked" ' ;
			}
			//$input = $input .' value="'.$options['value'].'"' ;
		  $input = $input .' value="1"' ;
					
			$input = $input . ' ' .$fh->getValidationScript($name, $this->validation_rules[$name] ) . ' />';
			
		}
		
		elseif( $options['type'] == 'date' ){
			
			$input = '<input type="'.$options['type'].'" name="'.$name.'" id="'.$name.'" ';
			foreach( $options as $key=>$value ){
				if($key<>'type' && $key<>'name' && $key<>'validtype' && $key<>'id' && $key<>'value' && $value){
					$input = $input .' '. $key .'="'.$value.'"' ;
				}
			}
				
			//print_r ($formValidation);	
			$formValue = $formValidation[$activeFormName][$name]['value'];  //exit();
			if( !empty($formValue) ){
					$input = $input .' value="'.$formValue.'" ' ;
			}
			else{
					$input = $input .' value="'.$options['value'].'" ' ;
		  }
			//echo getConfigValue('date_format');		
			//print_r ($GLOBALS);
			
			$input = $input . ' ' .$fh->getValidationScript($name, $this->validation_rules[$name] ) . ' />';
			$input .= "
								<script type='text/javascript'>
								$(function() {
			
									$('#".$name."').datepicker({
										changeMonth: true,
										changeYear: true,
										showOn: 'button',
										buttonImage: '".getConfigValue('base_url')."coreimages/calender/dp.gif', 
										buttonImageOnly: true,
										dateFormat: '".getConfigValue('date_format')."'
									});
								});
								</script>
								";
		}
		
		elseif( $options['type']<>'' ){
			
			
			//if ($name == 'project_name'){
				//print_r ($options);
			//}
			
			$input = '<input type="'.$options['type'].'" name="'.$name.'" id="'.$name.'" ';
			foreach( $options as $key=>$value ){
				if($key<>'type' && $key<>'name' && $key<>'validtype' && $key<>'id' && $key<>'value' && $value){
					$input = $input .' '. $key .'="'.$value.'" ' ;
				}
			}
						
			if ( !strstr ($name, 'password') )	{
				
				//echo $activeFormName;	
				//print_r ($formValidation);	
			
				$formValue = $formValidation[$activeFormName][$name]['value'];  //exit();
				if( !empty($formValue) ){
						$input = $input .' value="'.$formValue.'" ' ;
				}
				else{
						$input = $input .' value="'.$options['value'].'" ' ;
				}
				
				$input .= $fh->getValidationScript($name, $this->validation_rules[$name] );
			}
			else{
				$input .= $fh->getValidationScript($name, $this->validation_rules[$name] );
			}
					
			$input = $input . ' />';
		}
		else{
			
			$input = '<input type="text" name="'.$name.'" id="'.$name.'" ';
			foreach( $options as $key=>$value ){
				if($key<>'type' && $key<>'name' && $key<>'validtype' && $key<>'id' && $key<>'value'){
					$input = $input .' '. $key .'="'.$value.'" ' ;
				}
			}
			
			
			$formValue = $formValidation[$activeFormName][$name]['value'];  //exit();
			if( !empty($formValue) ){
					$input = $input .' value="'.$formValue.'"' ;
			}
			else{
					$input = $input .' value="'.$options['value'].'" ' ;
		  }
					
			$input = $input . ' ' .$fh->getValidationScript($name, $this->validation_rules[$name] ) . ' />';
			
		}
		
		
		
		if( isset( $formValidation[$activeFormName][$name] ) && !empty( $formValidation[$activeFormName][$name] ) && $formValidation[$activeFormName][$name]['error']==1 ){
			$input = $input . '<div id="errordiv'.$name.'" name="errordiv'.$name.'" class="mylebel1" style="color:#F00; display:block; width:100%;">'.$formValidation[$activeFormName][$name]['errormsg'].'</div>';
		}
		else{
			$input = $input . '<div id="errordiv'.$name.'" class="mylebel1" style="color:#F00; display:none; width:100%;"></div>';
		}
		
		return $input;
	}
		
	function getValidationScript($name, $validation){
		
		if( isset($validation) && !empty($validation) ){
			
			$maxlength = 0;
			$minlength = 0;
			if( isset($validation['maxlength']) && !empty($validation['maxlength']) ){
				$maxlength = $validation['maxlength'];
			}
			if( isset($validation['minlength']) && !empty($validation['minlength']) ){
				$minlength = $validation['minlength'];
			}
						
			if( $validation['rule']=='notempty' || $validation['rule']=='date' ){
				$warning = '';
				if( isset($validation['message']) && !empty($validation['message']) ){
					$warning = $validation['message'];
				}
				if( isset($validation['equalto']) && !empty($validation['equalto']) ){
					$input = 'onblur="checkNotEmptyAndEqualTo(\''.$name.'\', \''.$warning.'\', \''.$validation['equalto'].'\', \''.$maxlength.'\', \''.$minlength.'\');"';
					return $input;
				}
				else{
					$input = 'onblur="checkNotEmpty(\''.$name.'\', \''.$warning.'\', \''.$maxlength.'\', \''.$minlength.'\' );"';
					return $input;
				}
			}
			if( $validation['rule']=='email' ){
				$warning = '';
				if( isset($validation['message']) && !empty($validation['message'])  ){
					$warning = $validation['message'];
				}
				if( !isset($validation['required']) || empty($validation['required']) ){
					$validation['required'] = 'true';
				}
				if( isset($validation['equalto']) && !empty($validation['equalto']) ){					
					$input = 'onblur="checkValidEmailEqualTo(\''.$name.'\', \''.$warning.'\', \''.$validation['equalto'].'\', \''.$validation['required'].'\', \''.$maxlength.'\', \''.$minlength.'\');"' ;
					return $input;
				}
				else{
					$input = 'onblur="checkValidEmail(\''.$name.'\', \''.$warning.'\', \''.$validation['required'].'\', \''.$maxlength.'\', \''.$minlength.'\');"' ;					
					return $input;
				}
			}
			if( $validation['rule']=='numeric' ){
				$warning = '';
				if( isset($validation['message']) && !empty($validation['message']) ){
					$warning = $validation['message'];
				}
				if( !isset($validation['required']) || empty($validation['required']) ){
					$validation['required'] = 'true';
				}
				if( isset($validation['equalto']) && !empty($validation['equalto']) ){
					$input = 'onblur="checkNumeric(\''.$name.'\', \''.$warning.'\', \''.$validation['equalto'].'\', \''.$validation['required'].'\', \''.$maxlength.'\', \''.$minlength.'\');"';
					return $input;
				}
				else{
					$input = 'onblur="checkNumeric(\''.$name.'\', \''.$warning.'\', \''.$validation['equalto'].'\', \''.$validation['required'].'\', \''.$maxlength.'\', \''.$minlength.'\');"';
					return $input;
				}
			}
			if( $validation['rule']=='alpha' ){
				$warning = '';
				if( isset($validation['message']) && !empty($validation['message']) ){
					$warning = $validation['message'];
				}
				if( !isset($validation['required']) || empty($validation['required']) ){
					$validation['required'] = 'true';
				}
				if( isset($validation['equalto']) && !empty($validation['equalto']) ){
					$input = 'onblur="checkAlpha(\''.$name.'\', \''.$warning.'\', \''.$validation['equalto'].'\', \''.$validation['required'].'\', \''.$maxlength.'\', \''.$minlength.'\');"';
					return $input;
				}
				else{
					$input = 'onblur="checkAlpha(\''.$name.'\', \''.$warning.'\', \''.$validation['equalto'].'\', \''.$validation['required'].'\', \''.$maxlength.'\', \''.$minlength.'\');"';
					return $input;
				}
			}
			if( $validation['rule']=='alphanumeric' ){
				$warning = '';
				if( isset($validation['message']) && !empty($validation['message']) ){
					$warning = $validation['message'];
				}
				if( !isset($validation['required']) || empty($validation['required']) ){
					$validation['required'] = 'true';
				}
				if( isset($validation['equalto']) && !empty($validation['equalto']) ){
					$input = 'onblur="checkAlphaNumeric(\''.$name.'\', \''.$warning.'\', \''.$validation['equalto'].'\', \''.$validation['required'].'\', \''.$maxlength.'\', \''.$minlength.'\');"';
					return $input;
				}
				else{
					$input = 'onblur="checkAlphaNumeric(\''.$name.'\', \''.$warning.'\', \''.$validation['equalto'].'\', \''.$validation['required'].'\', \''.$maxlength.'\', \''.$minlength.'\');"';
					return $input;
				}
			}
			if( $validation['rule']=='custom' ){
				$warning = '';
				if( isset($validation['message']) && !empty($validation['message']) ){
					$warning = $validation['message'];
				}
				if( !isset($validation['required']) || empty($validation['required']) ){
					$validation['required'] = 'true';
				}
				if( isset($validation['equalto']) && !empty($validation['equalto']) ){
					$input = 'onblur="checkCustom(\''.$name.'\', \''.$warning.'\', \''.$validation['equalto'].'\', \''.$validation['required'].'\', \''.$maxlength.'\', \''.$minlength.'\', \''.$validation['custom'].'\');"';
					return $input;
				}
				else{
					$input = 'onblur="checkCustom(\''.$name.'\', \''.$warning.'\', \''.$validation['equalto'].'\', \''.$validation['required'].'\', \''.$maxlength.'\', \''.$minlength.'\', \''.$validation['custom'].'\');"';
					return $input;
				}
			}
		}
		return '';
	}
	
	function getValidationScriptSelect($name, $validation){
		//print_r($validation); exit();
		if( isset($validation) && !empty($validation) ){
						
			if( $validation['rule']=='notempty' ){
				$warning = '';
				if( isset($validation['message']) && !empty($validation['message']) ){
					$warning = $validation['message'];
				}				
				$input = 'onblur="checkSelected(\''.$name.'\', \''.$warning.'\' );"';
				return $input;				
			}
		}
		return '';
	}
	
	function getValidationScriptTextArea($name, $validation){
		//print_r($validation); exit();
		if( isset($validation) && !empty($validation) ){
						
			if( $validation['rule']=='notempty' ){
				$warning = '';
				if( isset($validation['message']) && !empty($validation['message']) ){
					$warning = $validation['message'];
				}
				if( isset($validation['equalto']) && !empty($validation['equalto']) ){
					$input = 'onChange="checkNotEmptyAndEqualTo(\''.$name.'\', \''.$warning.'\', \''.$validation['equalto'].'\', \''.$maxlength.'\', \''.$minlength.'\');"';
					return $input;
				}
				else{
					$input = 'onChange="checkNotEmpty(\''.$name.'\', \''.$warning.'\', \''.$maxlength.'\', \''.$minlength.'\' );"';
					return $input;
				}
			}
		}
		return '';
	}
	
	function getValidationScriptFile($name, $validation){
		//print_r($validation); exit();
		if( isset($validation) && !empty($validation) ){
						
			if( $validation['rule']=='notempty' ){
				$warning = '';
				if( isset($validation['message']) && !empty($validation['message']) ){
					$warning = $validation['message'];
				}
				$input = 'onblur="checkNotEmpty(\''.$name.'\', \''.$warning.'\', \''.$maxlength.'\', \''.$minlength.'\' );"';
				return $input;				
			}
		}
		return '';
	}
	
	
}

?>