// JavaScript Document
function load_books_details_info (title){
	
	//postval = "" ;
	jQuery ('#ajax_loader').css('display', 'block');
	//jQuery ('#ajax_loader').html('roni');
	
	//alert (123);
	jQuery.ajax({
   type: "GET",
	 async: false,
   url: "/amarboimela/get_books_details/"+title+".html",
   	 
   success: function(html){
     //alert( "Data Saved: " + msg );
		  //update_rank_images_tbl(rank_title+'_tbl_', 'mark_', 'unmark_', 1, rank, 5);
		 jQuery('#books_details').html(html);				
		 regenerateRatings();
   }
 	});
	
}

function load_books_review_info (title){
	
	//postval = ""
	jQuery ('#ajax_loader').css('display', 'block');
	
	jQuery.ajax({
   type: "GET",
	 async: false,
   url: "/amarboimela/get_books_review/"+title+".html",
   
   success: function(html){
     //alert( "Data Saved: " + msg );
		  //update_rank_images_tbl(rank_title+'_tbl_', 'mark_', 'unmark_', 1, rank, 5);
		 jQuery('#books_details').html(html);	
		 regenerateRatings();
   }
 	});
	
}

function regenerateRatings (){
	
	p_rank_str = jQuery ('#rank_books_id').html() ;
	if (p_rank_str){
		p_rank_id = p_rank_str.split('|');
		p_rank_str = jQuery ('#rank_books_value').html() ;
		if (p_rank_str){
			p_rank_value = p_rank_str.split('|');
		
			for ( r=0; p_rank_id[r]; r++ ){
				//alert (p_rank_value[r])	
				rank_title = p_rank_id[r];
				prev_rank = p_rank_value[r];
				
				create_rank_stars(rank_title, prev_rank, total_rank, each_rank_class, each_rank_img_width, each_rank_img_height);
				update_rank_images_tbl(rank_title+'_tbl_', 'mark_', 'unmark_', 1, prev_rank, 5);
			}
		}
	}
	
}