function alterChecking (chek_all){
	
	//alert ($(top_all).attr('checked'));
	if ( $(chek_all).attr('checked') ){
		$("form input:checkbox").attr('checked', 'checked');
	}
	else{
		$("form input:checkbox").attr('checked', false);
	}
}


function toogleSection (id){
	$('#'+id).toggle('medium');
	if ( $('#'+id+"_sign").html() == '+' ){
		//alert ('#'+id+"_sign");
		$('#'+id+"_sign").html( '-');
	}
	else{
		//alert ('#'+id+"_sign");
		$('#'+id+"_sign").html( '+' );
	}
}

$(document).ready(function(){			
	
	$('#option_inner_page').css ('display', 'none');
	$('#feature_inner_section').css ('display', 'none');
	$('#corporate_inner_section').css ('display', 'none');
	$('#template_inner_section').css ('display', 'none');
	
});

function reloadCaptcha(){
	//alert ('123');
	$("#captcha_img").attr('src', '/annanovas/captcha.html?img='+Math.random());
}
//function displayE