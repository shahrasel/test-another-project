
//alert ("Browser Name: " + navigator.appName);

window.onerror=function(desc,page,line,chr){
/* alert('JavaScript error occurred! \n'
  +'\nError description: \t'+desc
  +'\nPage address:      \t'+page
  +'\nLine number:       \t'+line
 );*/
}

$(function(){
 $('a').focus(function(){this.blur();});
 SI.Files.stylizeAll();
 slider.init();

 $('input.text-default').each(function(){
  $(this).attr('default',$(this).val());
 }).focus(function(){
  if($(this).val()==$(this).attr('default'))
   $(this).val('');
 }).blur(function(){
  if($(this).val()=='')
   $(this).val($(this).attr('default'));
 });

 $('input.text,textarea.text').focus(function(){
  $(this).addClass('textfocus');
 }).blur(function(){
  $(this).removeClass('textfocus');
 });

 var popopenobj=0,popopenaobj=null;
 $('a.popup').click(function(){
  var pid=$(this).attr('rel').split('|')[0],_os=parseInt($(this).attr('rel').split('|')[1]);
  var pobj=$('#'+pid);
  if(!pobj.length)
   return false;
  if(typeof popopenobj=='object' && popopenobj.attr('id')!=pid){
   popopenobj.hide(50);
   $(popopenaobj).parent().removeClass(popopenobj.attr('id').split('-')[1]+'-open');
   popopenobj=null;
  }
  return false;
 });
 $('p.images img').click(function(){
  var newbg=$(this).attr('src').split('bg/bg')[1].split('-thumb')[0];
  $(document.body).css('backgroundImage','url('+_siteRoot+'images/bg/bg'+newbg+'.jpg)');
 
  $(this).parent().find('img').removeClass('on');
  $(this).addClass('on');
  return false;
 });
 $(window).load(function(){
  
	/*
	$.each(css_ims,function(){(new Image()).src=_siteRoot+'css/images/'+this;});
  $.each(css_cims,function(){
   var css_im=this;
   $.each(['blue','purple','pink','red','grey','green','yellow','orange'],function(){
    (new Image()).src=_siteRoot+'css/'+this+'/'+css_im;
   });
  });
	*/
	
 }); 
 $('div.sc-large div.img:has(div.tml)').each(function(){
  $('div.tml',this).hide();
  $(this).append('<a href="#" class="tml_open">&nbsp;</a>').find('a').css({
   left:parseInt($(this).offset().left)+206,top:parseInt($(this).offset().top)+1
  }).click(function(){
   $(this).siblings('div.tml').slideToggle();
   return false;
  }).focus(function(){this.blur();}); 
 });
});

var slider={
 lock_slide:0,	
 num:-1,
 cur:0,
 cr:[],
 al:null,
 at:5*1000,
 ar:true,
 init:function(){
  if(!slider.data || !slider.data.length)
   return false;

  var d=slider.data;
  slider.num=d.length;
  var pos=Math.floor(Math.random()*1);//slider.num);
  for(var i=0;i<slider.num;i++){
   $('#'+d[i].id).css({left:((i-pos)*206)});
	 
   $('#slide-nav').append('<a id="slide-link-'+i+'" href="#" onclick="slider.slide('+i+');return false;" onfocus="this.blur();">'+'</a>');
  }

  $('img,div#slide-controls',$('div#slide-holder')).fadeIn();
  slider.text(d[pos]);
  slider.on(pos);
  slider.cur=pos;
  window.setTimeout('slider.auto();',slider.at);
 },
 auto:function(){
  if(!slider.ar)
   return false;

  var next=slider.cur+1;
  //if(next>=slider.num) next=0;
  slider.slide(next);
 },
 
 nextslide:function(){
  var next=slider.cur+1;
  //if(next>=slider.num) next=0;
	//alert (next);
  slider.slide(next);
 },
 previousslide:function(){
  
	
	var previous=slider.cur-1;
  //alert (previous);
	
	if(previous < 0 ) {
			
			if (slider.lock_slide == 1)
				return;
		
			slider.lock_slide = 1;
			//alert ('-'+previous);
			var d=slider.data;
			$('#'+d[slider.num-1].id).css ('left', -206);	
			//alert (123);
			$('#'+d[slider.num-1].id).css ('opacity', 1);
			
			$('#'+d[slider.num-1].id).stop().animate
													(
														{
															left:0,
															opacity: 1
														},
														500,
														'swing'
														
													);
			
			//slider.cur = slider.num-1;
			$('#'+d[0].id).stop().animate
													(
														{
															left:206,
															opacity: 1
														},
														570,
														'swing',
														function() {
															
															pos = slider.num-1;
															for (i=0; i<slider.num-1; i++ ){
																$('#'+d[i].id).css ('left', (i-pos)*206);
															}
																					
															slider.on(slider.num-1);
															slider.text(d[slider.num-1]);
															slider.cur = slider.num-1;
															slider.lock_slide = 0;
															//alert (slider.cur);
  													}
														
														
													);
													
  		//slider.cur=slider.num-1;
	}
	//alert (previous);
	else{
		//alert (previous);
  	slider.slide(previous);
	}
	
 },
 
 slide:function(pos){
	
	//alert (lock_slide );
	
	if (slider.lock_slide == 1)
		return;
		
	slider.lock_slide = 1;
  //if(pos<0 || pos>=slider.num || pos==slider.cur)
	if(pos == slider.cur){
   	//alert ('unlock lock');
		slider.lock_slide  = 0;
		return;
	}
	//alert (slider.cur);
	//slider.cur = 4;
  window.clearTimeout(slider.al);
  slider.al=window.setTimeout('slider.auto();',slider.at);

  var d=slider.data;
	//alert (pos);
	//alert (slider.cur);
	if (pos == slider.num) {
		pos = 0;
		//alert (pos);
		$('#'+d[0].id).css ('left', 206);	
		$('#'+d[0].id).css ('opacity', 1);
		
		$('#'+d[0].id).stop().animate
													(
														{
															left:206,
															opacity: 1
														},
														1,
														'swing'
														
													);
		
		$('#'+d[0].id).stop().animate
													(
														{
															left:0,
															opacity: 1
														},
														500,
														'swing'
														
													);
		$('#'+d[slider.cur].id).stop().animate
													(
														{
															left:-206,
															opacity: .3
														},
														500,
														'swing',
														function() {
    													//alert ('animation done');	
															// Animation complete.
															for (i=1; i<slider.num; i++ ){
																$('#'+d[i].id).css ('left', i*206);
															}
															//$('#'+d[slider.cur].id).css ('left', (slider.cur)*206);
															//slider.on(0);
															//slider.text(d[0]);
															//slider.cur = 0;
															
															slider.lock_slide = 0;
															
  													}
														
													);
		
		
		//alert (slider.cur);	
	}
	
	else{
  	for(var i=0;i<slider.num;i++){
   		
			if ( i == pos ){
				//$('#'+d[i]).css ('opacity', .3);
				$('#'+d[i].id).stop().animate
													(
														{
															left:((i-pos)*206),
															opacity: 1
														},
														500,
														'swing'
													);
			}
			else{
				
				//$('#'+d[0]).css('');
				
				$('#'+d[i].id).stop().animate
													(
														{
															left:((i-pos)*206),
															opacity: .3
														},
														500,
														'swing',
														function() {
															slider.lock_slide = 0;
														}
														
													);
			}
			
		}
		
	}
	
	//if (slider.cur == slider.num)
		//pos = 0;
		
  slider.on(pos);
  slider.text(d[pos]);
  slider.cur=pos;
	
 },
 on:function(pos){
  $('#slide-nav a').removeClass('select');
  $('#slide-nav a#slide-link-'+pos).addClass('select');
 },
 text:function(di){
  slider.cr['a']=di.title;
  slider.cr['b']=di.desc;
  
	$('#slide-title a').attr('href', di.forward);	
	slider.ticker('#slide-title span',di.title,0,'a');
  slider.ticker('#slide-desc',di.desc,0,'b');
 },
 ticker:function(el,text,pos,unique){
  if(slider.cr[unique]!=text)
   return false;

  ctext=text.substring(0,pos)+(pos%2?'-':'_');
  $(el).html(ctext);

  if(pos==text.length)
   $(el).html(text);
  else
   window.setTimeout('slider.ticker("'+el+'","'+text+'",'+(pos+1)+',"'+unique+'");',30);
 }
};
// STYLING FILE INPUTS 1.0 | Shaun Inman <http://www.shauninman.com/> | 2007-09-07
if(!window.SI){var SI={};};
SI.Files={
 htmlClass:'SI-FILES-STYLIZED',
 fileClass:'file',
 wrapClass:'cabinet',
 
 fini:false,
 able:false,
 init:function(){
  this.fini=true;
 },
 stylize:function(elem){
  if(!this.fini){this.init();};
  if(!this.able){return;};
  
  elem.parentNode.file=elem;
  elem.parentNode.onmousemove=function(e){
   if(typeof e=='undefined') e=window.event;
   if(typeof e.pageY=='undefined' &&  typeof e.clientX=='number' && document.documentElement){
    e.pageX=e.clientX+document.documentElement.scrollLeft;
    e.pageY=e.clientY+document.documentElement.scrollTop;
   };
   var ox=oy=0;
   var elem=this;
   if(elem.offsetParent){
    ox=elem.offsetLeft;
    oy=elem.offsetTop;
    while(elem=elem.offsetParent){
     ox+=elem.offsetLeft;
     oy+=elem.offsetTop;
    };
   };
  };
 },
 stylizeAll:function(){
  if(!this.fini){this.init();};
  if(!this.able){return;};
 }
};