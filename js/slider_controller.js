
jQuery(document).ready(function(){	
	
	change_slide_top();
	change_slide_abm();
});	

function change_slide_top(){
	
	jQuery("#top_slider").easySlider({
		controlsBefore:	'<p id="controls">',
		controlsAfter:	'</p>',		
		prevId: 'prevBtn',
		continuous:false,
		nextId: 'nextBtn',
		optionnumeric: true,
		numericId: 'topcontroll',
		numericControll: 'numeric_controller'
	});	
	
}

function change_slide_abm(){
	
	jQuery("#abm_slider").easySlider({
		controlsBefore:	'<p id="controls2">',
		controlsAfter:	'</p>',		
		prevId: 'prevBtn2',
		continuous:false,
		nextId: 'nextBtn2',
		optionnumeric: true,
		numericId: 'abmcontroll',
		numericControll: 'abm_numeric_controller'
	});	
	
}


function load_select_category (cat_id, $sel_id){
	
	var post_data;
	post_data = 'catid='+cat_id;
	
	
	//top_sellers_text2
	//jQuery('.top_sellers_body2a div').toggleClass('top_sellers_text2', 'top_sellers_text');
	jQuery('.top_sellers_body2a div').removeClass('top_sellers_text2');
	jQuery('.top_sellers_body2a div').addClass('top_sellers_text');
	jQuery($sel_id).removeClass('top_sellers_text');
	jQuery($sel_id).addClass ('top_sellers_text2');
	
	jQuery ('#ajax_top_loader').css('display', 'block');
	jQuery ('#ajax_top_loader').removeClass('top_sellers_text');
	jQuery ('#ajax_top_loader div').removeClass('top_sellers_text');
	
	jQuery.ajax({
   type: "POST",
   url: "/amarboimela/get_top_sells_books.html",
   data: post_data,
	 async: false,
	 dataType: 'html',
   success: function(msg){
     //alert( "Data Saved: " + msg );
		 jQuery('#top_slider').html (msg);
		 change_slide_top();
		 jQuery ('#ajax_top_loader').css('display', 'none');
   }
 });

}

function load_abmselect_category (cat_id, $sel_id){
	
	var post_data;
	post_data = 'catid='+cat_id;
	
	
	
	//top_sellers_text2
	//jQuery('.top_sellers_body2a div').toggleClass('top_sellers_text2', 'top_sellers_text');
	jQuery('.bam_recommends_body2a div').removeClass('top_sellers_text2');
	jQuery('.bam_recommends_body2a div').addClass('top_sellers_text');
	jQuery($sel_id).removeClass('top_sellers_text');
	jQuery($sel_id).addClass ('top_sellers_text2');
	
	jQuery ('#ajax_abm_loader').css('display', 'block');
	jQuery ('#ajax_abm_loader').removeClass('top_sellers_text');
	jQuery ('#ajax_abm_loader div').removeClass('top_sellers_text');
	
	jQuery.ajax({
   type: "POST",
   url: "/amarboimela/get_abm_recomended_books.html",
   data: post_data,
	 async: false,
	 dataType: 'html',
   success: function(msg){
     //alert( "Data Saved: " + msg );
		 jQuery('#abm_slider').html (msg);
		 change_slide_abm();
		 jQuery ('#ajax_abm_loader').css('display', 'none');
   }
 });

}

// JavaScript Document