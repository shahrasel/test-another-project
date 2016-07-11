
jQuery(document).ready(function(){
	
	
	/*	
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
	*/
	
	
	
});


function update_rank(rank_title, rating){
	
	
	tmp_str = rank_title.split('_tbl_');
	tmp_info = tmp_str[0];
	
	tmp_rank_info = tmp_info.split('_rank_');
	
	product_table = tmp_rank_info[0];
	product_id = tmp_rank_info[1];
	
	//alert(product_table+' '+product_id+' '+rating);
	postval = "product_table="+product_table+"&product_id="+product_id+"&rating="+rating;
	//postval = "product_table:"+product_table+",product_id:"+product_id+",rating:"+rating;
	//alert (postval);
	
	/*$.post("/peacecorp/add rating.html", { product_table:product_table, product_id:product_id,rating:rating },
  function(data){
    //process(data);
		update_rank_images_tbl(rank_title+'_tbl_', 'mark_', 'unmark_', 1, data, 5);
  });*/
	jQuery.ajax({
   type: "POST",
	 async: false,
   url: "/amarboimela/add rating.html",
   data: postval,
   success: function(rank){
     //alert( "Data Saved: " + msg );
		 
		 if (rank == -2){
		 	alert ('you already rated once');
		 }
		 
		 else if (rank == -3){
		 	window.location = '/amarboimela/unauthorize.html';
		 }
		 else if (rank > 0){
			 alert ('your rating submitted');
			 //tmp_rank = rank;
			 //tmp_rank_title = rank_title;
			 //setTimeout('dealyrating()',500);
			 update_rank_images_tbl(rank_title+'_tbl_', 'mark_', 'unmark_', 1, rank, 5);
			 //jQuery('#'+rank_title).html(rank);
		 }
		 
   }
 });
		
}
