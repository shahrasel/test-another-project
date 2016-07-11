var VALIDATION = new Array();
var VALIDATION_KEY = new Array();
var VALIDATION_VALID = new Array();

var VALID = true;

function checkregiform(frm){
	return validForm();
	
}

function printdata(){
	
}

/*$(document).ready(function(){	
	alert (123);
	//$("form input:checkbox").attr('checked', 'checked');
	//alert ( $('#id_all_up').attr('checked') );
	
});*/


function setValidInfo(inputid, valid){
	//alert(inputid+' '+ valid);
	child_array = new Array();
	valid_int = valid;
	count = 0;
	for (i=0; i<VALIDATION.length; i++){
		//child_array = VALIDATION[i];
		//alert('c q: '+ child_array );
		//alert('count: '+ count);
		if( VALIDATION[i] == inputid ){
			//alert('equal');
			break;
		}
		count++;
	}
	//alert(count);	
	//child_array[0] = inputid;
	//child_array[1] = valid_int;
	VALIDATION[count] = inputid;
	VALIDATION_KEY[count] = valid;
	//alert('total: '+ VALIDATION.length);
	/*for (i=0; i<VALIDATION.length; i++){
		alert('v: '+ VALIDATION[i]+' ' +VALIDATION_KEY[i] );
	}*/
	
}


function validForm(){
	//alert(VALIDATION.length);
	for (i=0; i<VALIDATION.length; i++){
		//child_array = VALIDATION[i];
		//alert(child_array[0]);
		if( VALIDATION_KEY[i] == 0 ){
			return false;
			//child_array[1] = valid_int;
			break;
		}		
	}
	return true;
}


function alterChecking (chek_all){
	
	//alert ($(top_all).attr('checked'));
	if ( $(chek_all).attr('checked') ){
		$("form input:checkbox").attr('checked', 'checked');
	}
	else{
		$("form input:checkbox").attr('checked', false);
	}
}





//email validation start
function checkValidation(formInput) {

    if (typeof(formInput) != "object") {
        alert("Validation not supported on this browser.");
        return(false);
    }

    var message;

    if (stringEmpty(formInput.value)) {
        message = "Error! There is no input value entered.";
        //alert(message);
    } else if (noAtSign( formInput.value )) {
        message = "Error! The address \"" + formInput.value + "\" does not contain an '@' character.";
        //alert(message);
    } else if (nothingBeforeAt(formInput.value)) {
        message = "Error! The address \"" + formInput.value;
        message += "\" must contain at least one character before the '@' character";
        //alert(message);
    } else if (noLeftBracket(formInput.value)) {
        message = "Error! The address \"" + formInput.value;
        message += "\" contains a right square bracket ']',\nbut no corresponding left square bracket '['.";
        //alert(message);
    } else if (noRightBracket(formInput.value)) {
        message = "Error! The address \"" + formInput.value;
        message += "\" contains a left square bracket '[',\nbut no corresponding right square bracket ']'.";
        //alert( message);
    } else if (noValidPeriod(formInput.value)) {
        message = "Error! The address \"" + formInput.value + "\" must contain a period ('.') character.";
        //alert(message);
    } else if (noValidSuffix(formInput.value)) {
        message = "Error! The address \"" + formInput.value;
        message += "\" must contain a two, three or four character suffix.";
        //alert(message);
    } else {
        message = "Success! The email address \"" + formInput.value + "\" validates OK.";
        //alert(message);
		return true;
    }

    var objType = typeof(formInput.focus);
    if (objType == "object" || objType == "function") {
         formInput.focus();
    }

    return false;
}

function checkValid (formField) {
    if ( checkValidation ( formField ) == true ) {
        //alert ( 'E-Mail Address Validates OK' );
		return true;
    }
	else{
	    return false;
	}
}

function stringEmpty (formField) {
    // CHECK THAT THE STRING IS NOT EMPTY
    if ( formField.length < 1 ) {
        return ( true );
    } else {
        return ( false );
    }
}

function noAtSign (formField) {
    // CHECK THAT THERE IS AN '@' CHARACTER IN THE STRING
    if (formField.indexOf ('@', 0) == -1) {
        return ( true )
    } else {
        return ( false );
    }
}

function nothingBeforeAt (formField) {
    // CHECK THERE IS AT LEAST ONE CHARACTER BEFORE THE '@' CHARACTER
    if ( formField.indexOf ( '@', 0 ) < 1 ) {
        return ( true )
    } else {
        return ( false );
    }
}

function noLeftBracket (formField) {
    // IF EMAIL ADDRESS IN FORM 'user@[255,255,255,0]', THEN CHECK FOR LEFT BRACKET
    if ( formField.indexOf ( '[', 0 ) == -1 && formField.charAt (formField.length - 1) == ']') {
        return ( true )
    } else {
        return ( false );
    }
}

function noRightBracket (formField) {
    // IF EMAIL ADDRESS IN FORM 'user@[255,255,255,0]', THEN CHECK FOR RIGHT BRACKET
    if (formField.indexOf ( '[', 0 ) > -1 && formField.charAt (formField.length - 1) != ']') {
        return ( true );
    } else {
        return ( false );
    }
}

function noValidPeriod (formField) {
    // IF EMAIL ADDRESS IN FORM 'user@[255,255,255,0]', THEN WE ARE NOT INTERESTED
    if (formField.indexOf ( '@', 0 ) > 1 && formField.charAt (formField.length - 1 ) == ']')
        return ( false );

    // CHECK THAT THERE IS AT LEAST ONE PERIOD IN THE STRING
    if (formField.indexOf ( '.', 0 ) == -1)
        return ( true );

    return ( false );
}

function noValidSuffix(formField) {
    // IF EMAIL ADDRESS IN FORM 'user@[255,255,255,0]', THEN WE ARE NOT INTERESTED
    if (formField.indexOf('@', 0) > 1 && formField.charAt(formField.length - 1) == ']') {
        return ( false );
    }

    // CHECK THAT THERE IS A TWO OR THREE CHARACTER SUFFIX AFTER THE LAST PERIOD
    var len = formField.length;
    var pos = formField.lastIndexOf ( '.', len - 1 ) + 1;
    if ( ( len - pos ) < 2 || ( len - pos ) > 4 ) {
        return ( true );
    } else {
        return ( false );
    }
}
//email validation end

function checkMaxLength(inputid, length){
	if( length == -1 ){
		document.getElementById('errordiv'+inputid).style.display = "none";
		return false;
	}
	warning = 'Input Maximum length ' + length;
	//alert(length);
	valueLength = document.getElementById(inputid).value.length ;	
	if ( valueLength > length ) {
		document.getElementById('errordiv'+inputid).innerHTML = warning ;
		document.getElementById('errordiv'+inputid).style.display = "block";		
		return true;
	}
	else{
		document.getElementById('errordiv'+inputid).style.display = "none";
		return false;
	}
}

function checkMinLength(inputid, length){
	if( length == -1 ){
		document.getElementById('errordiv'+inputid).style.display = "none";
		return false;
	}
	warning = 'Input Minimum length ' + length;
	//alert(length);
	valueLength = document.getElementById(inputid).value.length ;	
	if ( valueLength < length ) {
		document.getElementById('errordiv'+inputid).innerHTML = warning ;
		document.getElementById('errordiv'+inputid).style.display = "block";
		return true;
	}
	else{
		document.getElementById('errordiv'+inputid).style.display = "none";
		return false;
	}
}


function checkNotEmpty( inputid, warning, maxlength, minlength ){
	ret = false;
	if( maxlength >0 ){
		ret = checkMaxLength(inputid, maxlength);
	}
	if( minlength >0 && ret==false ){
		ret = checkMinLength(inputid, minlength);
	}
	
	if( ret == false ){
		if( warning == '' ){
			warning = 'Required';
		}
		if ( document.getElementById(inputid).value == '' ) {
			document.getElementById('errordiv'+inputid).innerHTML = warning ;
			document.getElementById('errordiv'+inputid).style.display = "block";			
			VALID = false;
			setValidInfo(inputid, 0);
			return true;
		}
		else{
			document.getElementById('errordiv'+inputid).style.display = "none";
			setValidInfo(inputid, 1);
			return false;
		}
	}
	else{
		VALID = false;
		setValidInfo(inputid, 0);
		return true;
	}
	
}

function checkNotEmptyAndEqualTo(inputid, warning, equalinputid, maxlength, minlength ){
	ret = false;
	if( maxlength >0 ){
		ret = checkMaxLength(inputid, maxlength);
	}
	if( minlength >0 && ret==false ){
		ret = checkMinLength(inputid, minlength);
	}
	
	if( ret == false ){
		if ( document.getElementById(inputid).value == '' ) {
			if( warning == '' ){
				warning = inputid+' Can not be empty';
			}
			document.getElementById('errordiv'+inputid).innerHTML = warning ;
			document.getElementById('errordiv'+inputid).style.display = "block";
			VALID = false;
			setValidInfo(inputid, 0);
			return true;
		}
		else if ( document.getElementById(inputid).value != document.getElementById(equalinputid).value  ) {
			if( warning == '' ){
				warning = inputid.toUpperCase() + ' must be the same with '+equalinputid.toUpperCase() ;
			}		
			document.getElementById('errordiv'+inputid).innerHTML = warning ;
			document.getElementById('errordiv'+inputid).style.display = "block";
			VALID = false;
			setValidInfo(inputid, 0);
			return true;
		}
		else{
			document.getElementById('errordiv'+inputid).style.display = "none";
			setValidInfo(inputid, 1);
			return false;
		}
	}
	else{
		VALID = false;
		setValidInfo(inputid, 0);
		return true;
	}
}

function checkEqualTo(inputid, warning, equalinputid){
	if( warning == '' ){
		warning = inputid+' must be the same with '+equalinputid;
	}
	if ( document.getElementById(inputid).value == document.getElementById(equalinputid).value  ) {
		warning = inputid+' must be the same with '+equalinputid;
		document.getElementById('errordiv'+inputid).innerHTML = warning ;
		document.getElementById('errordiv'+inputid).style.display = "block";
		VALID = false;
		return true;
	}
}


function checkValidEmail( inputid, warning, required, maxlength, minlength ){
	ret = false;
	if( maxlength >0 ){
		ret = checkMaxLength(inputid, maxlength);
	}
	if( minlength >0 && ret==false ){
		ret = checkMinLength(inputid, minlength);
	}
	
	if( warning == '' ){
		warning = 'Must be a valid Email Address';
	}
	
	var check = false;
	if( required == 'true' ){		
		check = true;
	}
	else{
		if( document.getElementById(inputid).value == '' ){
			check = false;
		}
		else{
			check = true;
		}
	}
	
	if( check == true ){
		if( ret == false ){
			if ( checkValid(document.getElementById(inputid)) == false ) {
				document.getElementById('errordiv'+inputid).innerHTML = warning ;
				document.getElementById('errordiv'+inputid).style.display = "block";
				VALID = false;
				setValidInfo(inputid, 0);
				return true;
			}
			else{
				document.getElementById('errordiv'+inputid).style.display = "none";
				setValidInfo(inputid, 1);
				return false;
			}
		}
		else{
			VALID = false;
			setValidInfo(inputid, 0);
			return true;
		}
	}
	else{
		document.getElementById('errordiv'+inputid).style.display = "none";
		setValidInfo(inputid, 1);
		return false;
	}
}


function checkValidEmailEqualTo( inputid, warning, equalinputid, required, maxlength, minlength ){
	ret = false;
	if( maxlength >0 ){
		ret = checkMaxLength(inputid, maxlength);
	}
	if( minlength >0 && ret==false ){
		ret = checkMinLength(inputid, minlength);
	}
	
	if( warning == '' ){
		warning = 'Must be a valid Email Address';
	}
	var check = false;
	if( required == 'true' ){
		check = true;
	}
	else{
		if( document.getElementById(inputid).value == '' ){
			check = false;
		}
		else{
			check = true;
		}
	}
	
	if( check == true ){
		if( ret == false ){
			if ( checkValid(document.getElementById(inputid)) == false ) {
				document.getElementById('errordiv'+inputid).innerHTML = warning ;
				document.getElementById('errordiv'+inputid).style.display = "block";
				VALID = false;
				setValidInfo(inputid, 0);
				return true;
			}
			else if ( document.getElementById(inputid).value != document.getElementById(equalinputid).value  ) {
				if( warning == '' ){
					warning = 'Must be a Addresses must be same';
				}
				document.getElementById('errordiv'+inputid).innerHTML = warning ;
				document.getElementById('errordiv'+inputid).style.display = "block";
				VALID = false;
				setValidInfo(inputid, 0);
				return true;
			}
			else{
				document.getElementById('errordiv'+inputid).style.display = "none";
				setValidInfo(inputid, 1);
				return false;
			}
		}
		else{
			VALID = false;
			setValidInfo(inputid, 0);
			return true;
		}
	}
	else{
		document.getElementById('errordiv'+inputid).style.display = "none";
		setValidInfo(inputid, 1);
		return false;
	}
}

function checkNumeric( inputid, warning, equalinputid, required, maxlength, minlength ){
	ret = false;
	if( maxlength >0 ){
		ret = checkMaxLength(inputid, maxlength);
	}
	if( minlength >0 && ret==false ){
		ret = checkMinLength(inputid, minlength);
	}
	
	if( warning == '' ){
		warning = 'Must be a Numeric';
	}
	var check = false;
	if( required == 'true' ){
		check = true;
	}
	else{
		if( document.getElementById(inputid).value == '' ){
			check = false;
		}
		else{
			check = true;
		}
	}
	
	if( check == true ){
		if( ret == false ){
			if ( !document.getElementById(inputid).value.match(/^[\d]*[\.]*[\d]+$/) ) {
				document.getElementById('errordiv'+inputid).innerHTML = warning ;
				document.getElementById('errordiv'+inputid).style.display = "block";		
				VALID = false;
				setValidInfo(inputid, 0);
				return true;
			}
			else if ( equalinputid!='' && document.getElementById(inputid).value != document.getElementById(equalinputid).value  ) {
				if( warning == '' ){
					warning = 'Must be same';
				}	
				document.getElementById('errordiv'+inputid).innerHTML = warning ;
				document.getElementById('errordiv'+inputid).style.display = "block";
				VALID = false;
				setValidInfo(inputid, 0);
				return true;
			}
			else{
				document.getElementById('errordiv'+inputid).style.display = "none";
				setValidInfo(inputid, 1);
				return false;
			}
		}
		else{
			VALID = false;
			setValidInfo(inputid, 0);
			return true;
		}
	}
	else{
		document.getElementById('errordiv'+inputid).style.display = "none";
		setValidInfo(inputid, 1);
		return false;
	}
}

function checkAlpha( inputid, warning, equalinputid, required, maxlength, minlength ){
	ret = false;
	if( maxlength >0 ){
		ret = checkMaxLength(inputid, maxlength);
	}
	if( minlength >0 && ret==false ){
		ret = checkMinLength(inputid, minlength);
	}
	
	if( warning == '' ){
		warning = 'Must be Alpha';
	}
	var check = false;
	if( required == 'true' ){
		check = true;
	}
	else{
		if( document.getElementById(inputid).value == '' ){
			check = false;
		}
		else{
			check = true;
		}
	}
	
	if( check == true ){
		if( ret == false ){
			if ( !document.getElementById(inputid).value.match(/^[a-zA-Z]+$/) ) {
				document.getElementById('errordiv'+inputid).innerHTML = warning ;
				document.getElementById('errordiv'+inputid).style.display = "block";		
				VALID = false;
				setValidInfo(inputid, 0);
				return true;
			}
			else if ( equalinputid!='' && document.getElementById(inputid).value != document.getElementById(equalinputid).value  ) {
				if( warning == '' ){
					warning = 'Must be same';
				}	
				document.getElementById('errordiv'+inputid).innerHTML = warning ;
				document.getElementById('errordiv'+inputid).style.display = "block";
				VALID = false;
				setValidInfo(inputid, 0);
				return true;
			}
			else{
				document.getElementById('errordiv'+inputid).style.display = "none";
				setValidInfo(inputid, 1);
				return false;
			}
		}
		else{
			VALID = false;
			setValidInfo(inputid, 0);
			return true;
		}
	}
	else{
		document.getElementById('errordiv'+inputid).style.display = "none";
		setValidInfo(inputid, 1);
		return false;
	}
}


function checkAlphaNumeric( inputid, warning, equalinputid, required, maxlength, minlength ){
	ret = false;
	if( maxlength >0 ){
		ret = checkMaxLength(inputid, maxlength);
	}
	if( minlength >0 && ret==false ){
		ret = checkMinLength(inputid, minlength);
	}
	
	if( warning == '' ){
		warning = 'Must be Alpha';
	}
	var check = false;
	if( required == 'true' ){
		check = true;
	}
	else{
		if( document.getElementById(inputid).value == '' ){
			check = false;
		}
		else{
			check = true;
		}
	}
	
	if( check == true ){
		if( ret == false ){
			if ( !document.getElementById(inputid).value.match(/^[0-9a-zA-Z]+$/) ) {
				document.getElementById('errordiv'+inputid).innerHTML = warning ;
				document.getElementById('errordiv'+inputid).style.display = "block";		
				VALID = false;
				setValidInfo(inputid, 0);
				return true;
			}
			else if ( equalinputid!='' && document.getElementById(inputid).value != document.getElementById(equalinputid).value  ) {
				if( warning == '' ){
					warning = 'Must be same';
				}	
				document.getElementById('errordiv'+inputid).innerHTML = warning ;
				document.getElementById('errordiv'+inputid).style.display = "block";
				VALID = false;
				setValidInfo(inputid, 0);
				return true;
			}
			else{
				document.getElementById('errordiv'+inputid).style.display = "none";
				setValidInfo(inputid, 1);
				return false;
			}
		}
		else{
			VALID = false;
			setValidInfo(inputid, 0);
			return true;
		}
	}
	else{
		document.getElementById('errordiv'+inputid).style.display = "none";
		setValidInfo(inputid, 1);
		return false;
	}
}

function checkCustom( inputid, warning, equalinputid, required, maxlength, minlength, rule ){
	
	ret = false;
	if( maxlength >0 ){
		ret = checkMaxLength(inputid, maxlength);
	}
	if( minlength >0 && ret==false ){
		ret = checkMinLength(inputid, minlength);
	}
	
	if( warning == '' ){
		warning = 'Must be Alpha';
	}
	var check = false;
	if( required == 'true' ){
		check = true;
	}
	else{
		if( document.getElementById(inputid).value == '' ){
			check = false;
		}
		else{
			check = true;
		}
	}
	
	if( check == true ){
		if( ret == false ){
			if ( !document.getElementById(inputid).value.match(rule) ) {
				document.getElementById('errordiv'+inputid).innerHTML = warning ;
				document.getElementById('errordiv'+inputid).style.display = "block";
				VALID = false;
				setValidInfo(inputid, 0);
				return true;
			}
			else if ( equalinputid!='' && document.getElementById(inputid).value != document.getElementById(equalinputid).value  ) {
				if( warning == '' ){
					warning = 'Must be same';
				}	
				document.getElementById('errordiv'+inputid).innerHTML = warning ;
				document.getElementById('errordiv'+inputid).style.display = "block";
				VALID = false;
				setValidInfo(inputid, 0);
				return true;
			}
			else{
				document.getElementById('errordiv'+inputid).style.display = "none";
				setValidInfo(inputid, 1);
				return false;
			}
		}
		else{
			VALID = false;
			setValidInfo(inputid, 0);
			return true;
		}
	}
	else{
		document.getElementById('errordiv'+inputid).style.display = "none";
		setValidInfo(inputid, 1);
		return false;
	}
}


function checkSelected( inputid, warning ){
	
	if( warning == '' ){
		warning = 'Required';
	}
	if ( document.getElementById(inputid).value == '' ) {
		document.getElementById('errordiv'+inputid).innerHTML = warning ;
		document.getElementById('errordiv'+inputid).style.display = "block";
		setValidInfo(inputid, 0);
		return true;
	}
	else{
		document.getElementById('errordiv'+inputid).style.display = "none";
		setValidInfo(inputid, 1);
		return false;
	}	
	
}



function setToValid(inputid){
	for (i = 0; i < VALIDATION.length; i++){
		if( VALIDATION[i] == inputid ){
			VALIDATION_VALID[i] = 1;
		}
	}
}

function setToInvalid(inputid){
	for (i = 0; i < VALIDATION.length; i++){
		if( VALIDATION[i] == inputid ){
			VALIDATION_VALID[i] = 0;
		}
	}
}




function validate(){
	var usrid=document.getElementById("txtUserid"); //txtUserid-->ID of textbox
	var alphanum=/^[0-9a-bA-B]+$/; //This contains A to Z , 0 to 9 and A to B
	if(usrid.value.match(alphanum)){
		return true;
	}
	else{
		alert('Put a Valid Userid Name');
		return false;
	}
}