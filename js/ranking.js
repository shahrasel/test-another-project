var image_path = '/amarboimela/images/';
var unmark_img = image_path + 'p_star_icon_border.gif';
var mark_img = image_path + 'p_star_icon.jpg';
var total_rank = 5;
var each_rank_img_width = 15;
var each_rank_img_height = 19;
var each_rank_class = 'each_star_class';	
var tmp_rank = 0;
var tmp_rank_title = 0;

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
	
	a_rank_str = jQuery ('#rank_books_id').html() ;
	if (a_rank_str){
		a_rank_id = a_rank_str.split('|');
		a_rank_str = jQuery ('#rank_books_value').html() ;
		if (a_rank_str){	
			a_rank_value = a_rank_str.split('|');
			
			for ( r=0; a_rank_id[r]; r++ ){
				rank_title = a_rank_id[r];
				prev_rank = a_rank_value[r];
				each_rank_class = 'each_star_class';
				create_rank_stars(rank_title, prev_rank, total_rank, each_rank_class, each_rank_img_width, each_rank_img_height);
				update_rank_images_tbl(rank_title+'_tbl_', 'mark_', 'unmark_', 1, prev_rank, 5);
			}
			
		}
	}
	
	
});

function create_rank_stars(rank_title, prev_rank_no, total_stars_no, each_star_class, each_star_width, each_star_height){
	
	var star_code = '<table cellpadding="0" cellspacing="0" border="0" style="width:'+(total_stars_no*each_star_width)+'px; height:'+each_star_height+'px;" ><tr>';
	
	for(i=1; i<=total_stars_no; i++){
		star_code += '<td class="each_star_class" style="cursor:pointer;" onmouseover="update_rank_images_tbl(\''+rank_title+'_tbl_\',\'mark_\',\'unmark_\', 1, '+i+', '+total_stars_no+')" onmouseout="update_rank_images_tbl(\''+rank_title+'_tbl_\',\'mark_\',\'unmark_\', 1, '+prev_rank_no+', '+total_stars_no+')" onclick="update_rank(\''+rank_title+'_tbl_\','+i+')"><table cellpadding="0" cellspacing="0" border="0" style="width:'+each_star_width+'px; cursor:pointer; height:'+each_star_height+'px;" id="'+rank_title+'_tbl_'+i+'"><tr style="width:'+each_star_width+'px; height:'+each_star_height+'px;"><td id="'+rank_title+'_mark_'+i+'" style="background:url('+image_path+'p_star_icon.jpg) left;"></td><td id="'+rank_title+'_unmark_'+i+'" style="background:url('+image_path+'/p_star_icon_border.gif) right;"></td></tr></table></td>';
	}
	star_code += '</tr></table>';	
	//alert (rank_title);
	
	jQuery('#'+rank_title).html(star_code);
	
}

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

function dealyrating(){
	update_rank_images_tbl(tmp_rank_title+'_tbl_', 'mark_', 'unmark_', 1, tmp_rank, 5);
}

function update_rank_images_tbl(rank_id_prefix, mark_id_prefix, unmark_id_prefix, mark_id_seq_start, mark_id_seq_end, last_rank_id_seq){
	
	tmp_rank_id = rank_id_prefix.split('_tbl_');
	rank_id = (tmp_rank_id[0]);
	//alert (rank_id)
	
	if(mark_id_seq_start >= 0 && mark_id_seq_end >= 0 && last_rank_id_seq >= 0){
		for(i=mark_id_seq_start; i<=mark_id_seq_end; i++){
			
			jQuery('#'+rank_id+'_'+mark_id_prefix+i).css('width','100%');
			jQuery('#'+rank_id+'_'+unmark_id_prefix+i).css('width','0%');
			
		}
		for(j=(Math.floor(mark_id_seq_end)+1); j<=last_rank_id_seq; j++){
			//alert('#'+rank_id_prefix+j+'#'+mark_id_prefix+j);
			//alert ('#'+rank_id+'_'+mark_id_prefix+j)
			jQuery('#'+rank_id+'_'+mark_id_prefix+j).css('width','0%');
			jQuery('#'+rank_id+'_'+unmark_id_prefix+j).css('width','100%');
		}
		
		var fraction_star = mark_id_seq_end - (i-1);
		
		if(fraction_star>0){
			jQuery('#'+rank_id+'_'+mark_id_prefix+i).css('width',(fraction_star*100)+'%');
			jQuery('#'+rank_id+'_'+unmark_id_prefix+i).css('width',(100-(fraction_star*100))+'%');	
		}
	}
	
}

function change_alt_star(target_id){
	
	unmark_img = image_path + 'p_star_a_icon_border.gif';
	mark_img = image_path + 'p_star_a_icon.png';
	
	for(i=1; i<=5; i++){
		jQuery('#'+target_id+'_mark_'+i).css('background-image','url('+mark_img+')');
		jQuery('#'+target_id+'_unmark_'+i).css('background-image','url('+unmark_img+')');
	}
	
}

function change_default_star(target_id){
	
	unmark_img = image_path + 'p_star_icon_border.gif';
	mark_img = image_path + 'p_star_icon.jpg';
	
	for(i=1; i<=5; i++){
		jQuery('#'+target_id+'_mark_'+i).css('background-image','url('+mark_img+')');
		jQuery('#'+target_id+'_unmark_'+i).css('background-image','url('+unmark_img+')');
	}
	
}