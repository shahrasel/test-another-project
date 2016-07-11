<script type="text/javascript">

    function showMessage(txt,index){
		
		if(index == 1){
			//alert(txt);
			document.getElementById('loginId2').innerHTML = txt;
		}
		else if(index == 2){
			document.getElementById('email2').innerHTML=txt;
			//return false;
		}
		else if(index == 3){
			document.getElementById('password2').innerHTML=txt;
			//return false;
		}
		
	}
	
	function formValidation(){
		var userid = document.getElementById("loginId").value;	
		//alert (userid);
		var email = document.getElementById("email").value;
		var password = document.getElementById("password").value;
		var atpos = email.indexOf("@");
		
		if (userid == null || userid == ""){
			showMessage("You must enter your Login Id.", 1); 
			//return false; 		  	
  		}
		else if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length){
			showMessage("It's not a valid email.You must enter a valid emil address.", 2);
			//return false;
		}
		else if(password == null || password == ""){
			showMessage("You must enter your Login Password.", 3);
			//return false;
		}
		else{
			return true;	
		}
	}  
</script>	


<form id="userAc" method="post" action="<?php echo urlFor('newuser', array())?>" onsubmit="return formValidation();"> 
	
    <div>
    	<div>User LogIn ID:<span style="color:#F00">*</span><input type="text" id="loginId" name="loginId" /></div>
        <div style="color:#F00" id="loginId2"></div>
        <div>Email:<span style="color:#F00">*</span><input type="text" id="email" name="email" /></div>
        <span style="color:#F00"><p id="email2"></p></span>
        <div>User LogIn Password:<span style="color:#F00">*</span><input type="password" id="password" name="password" /></div>
        <span style="color:#F00"><p id="password2"></p></span>
        <div><spam>User Role: <select name="role">
        	<option>Enduser</option>
        </select></samp></div>
        <div><spam>User Status: <select name="status">
        	<option>Active</option>
        </select></samp></div>
    </div>
    <input type="submit" value="Submit" name="submit" id="submit" /> 
</form>


