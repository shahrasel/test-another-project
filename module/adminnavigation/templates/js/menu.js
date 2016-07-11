$(function(){

    $("ul.dropdown li").hover(function(){
    
        $(this).addClass("hover");
        $('ul:first',this).css('visibility', 'visible');
				//$('ul:first',this).slideDown('fast');
    		//$(this).parent().find("ul.subnav").slideUp('slow');
				//$('ul:first',this).slideDown('fast').show();
    }, function(){
    
        $(this).removeClass("hover");
        $('ul:first',this).css('visibility', 'hidden');
    
    });
    
    $("ul.dropdown li ul li:has(ul)").find("a:first").append(" &raquo; ");

});