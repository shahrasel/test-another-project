var current_sliding_page = 1;
var sliding_direction = '';
var total_sliding_page = 1;
$(document).ready(function(){
	$("#left_arrow_key").css('display', 'none');	
	//alert (document.getElementById("new_books_link_first").getElementsByTagName("div").length);
	var ct1 = $('.new_books_link').children().length; 
	var ct2 = $('.new_books_link_first').children().length; 
	
	var ct = ct1+ct2;
	total_sliding_page = parseFloat(ct*1.0/5);
	//alert (ct);
	
	$('#page_tab_content_2').css('display', 'none');
	$('#page_tab_content_3').css('display', 'none');
	
	$('#page_tab_1').click(
			
			function () { 
      	//alert (123);
				$('#page_tab_1').attr('class', 'page_tab_selected');
				$('#page_tab_2').attr('class', 'page_tab');
				$('#page_tab_3').attr('class', 'page_tab');
				
				$('#page_tab_content_1').css('display', 'block');
				$('#page_tab_content_2').css('display', 'none');
				$('#page_tab_content_3').css('display', 'none');
				
    	}
	);
	
	$('#page_tab_2').click(
			
			function () { 
      	//alert (123);
				$('#page_tab_2').attr('class', 'page_tab_selected');
				$('#page_tab_1').attr('class', 'page_tab');
				$('#page_tab_3').attr('class', 'page_tab');
				
				$('#page_tab_content_1').css('display', 'none');
				$('#page_tab_content_2').css('display', 'block');
				$('#page_tab_content_3').css('display', 'none');
				
    	}
	);
	
	$('#page_tab_3').click(
			
			function () { 
      	//alert (123);
				$('#page_tab_3').attr('class', 'page_tab_selected');
				$('#page_tab_2').attr('class', 'page_tab');
				$('#page_tab_1').attr('class', 'page_tab');
				
				$('#page_tab_content_1').css('display', 'none');
				$('#page_tab_content_2').css('display', 'none');
				$('#page_tab_content_3').css('display', 'block');
    	}
	);
	
	$("#left_arrow").click(function(){
		$(".new_books_mid_bg").animate({"left": "+=775px"}, "slow", 'linear', regenerate_slider_left);
	});
	
	$("#right_arrow").click(function(){
		$(".new_books_mid_bg").animate({"left": "-=775px"}, "slow", 'linear', regenerate_slider_right);
		
	});
	
	$('#top_search_value').click(function(){
		
		if ($( '#top_search_value').val() == 'Books Name')
				$( '#top_search_value').val('');														 
																	 
	});
	
	$('#top_search_value').blur(function(){
		if ($( '#top_search_value').val() == '' || $( '#top_search_value').val() == null)
			$( '#top_search_value').val('Books Name');			
	});
	
});
 
function regenerate_slider_left(){
	sliding_direction = 'left';
	current_sliding_page--;
	manageSlider();
	//getNewBooks();
}

function regenerate_slider_right(){
	sliding_direction = 'right';	
	current_sliding_page++;
	manageSlider();
	//getNewBooks();
}

function manageSlider(){
		
	if (current_sliding_page >= total_sliding_page)
		$("#right_arrow_key").css('display', 'none');	
	else
		$("#right_arrow_key").css('display', 'block');	
	
	if (current_sliding_page <= 1)
		$("#left_arrow_key").css('display', 'none');	
	else
		$("#left_arrow_key").css('display', 'block');	
}

function getNewBooks(){
	
	request_val = "current_sliding_page="+current_sliding_page;
	$.ajax({
	 type: "POST",
	 url: "/amarboimela/get_new_books.html",
	 data: request_val,
	 success: function(msg){
		 //alert( "Data Saved: " + msg );
		 if (sliding_direction == 'left')
		 	current_sliding_page--;
		else
			current_sliding_page++;
			
		 //$("#new_books_mid_bg").html(msg);
		 $("#new_books_mid_bg").css('left', '-776px');
	 }
 });

} 

function countElementsByClass(className, elementTag){
	var count = new Array();
	var elements = document.getElementsByTagName(elementTag);
	for (var i = 0; i < elements.length; i++){
		if (elements[i].className == className){
			count[i] = "";
		}
	}
	return count.length;
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