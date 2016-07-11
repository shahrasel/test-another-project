
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
 slider2.init();

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

var slider2={
 lock_slide:0,	
 num:-1,
 cur:0,
 cr:[],
 al:null,
 at:5*1000,
 ar:true,
 init:function(){
  if(!slider2.data || !slider2.data.length)
   return false;

  var d=slider2.data;
  slider2.num=d.length;
  var pos=Math.floor(Math.random()*1);//slider2.num);
  for(var i=0;i<slider2.num;i++){
   $('#'+d[i].id).css({left:((i-pos)*206)});
	 
   $('#slide-nav2').append('<a id="slide-link2-'+i+'" href="#" onclick="slider2.slide('+i+');return false;" onfocus="this.blur();">'+'</a>');
  }

  $('img,div#slide-controls2',$('div#slide-holder2')).fadeIn();
  slider2.text(d[pos]);
  slider2.on(pos);
  slider2.cur=pos;
  window.setTimeout('slider2.auto();',slider2.at);
 },
 auto:function(){
  if(!slider2.ar)
   return false;

  var next=slider2.cur+1;
  //if(next>=slider2.num) next=0;
  slider2.slide(next);
	
	
 },
 
 nextslide:function(){
  var next=slider2.cur+1;
  //if(next>=slider2.num) next=0;
	//alert (next);
  slider2.slide(next);
 },
 previousslide:function(){
  
	
	var previous=slider2.cur-1;
  //alert (previous);
	
	if(previous < 0 ) {
			
			if (slider2.lock_slide == 1)
				return;
		
			slider2.lock_slide = 1;
			//alert ('-'+previous);
			var d=slider2.data;
			$('#'+d[slider2.num-1].id).css ('left', -206);	
			//alert (123);
			$('#'+d[slider2.num-1].id).css ('opacity', 1);
			
			$('#'+d[slider2.num-1].id).stop().animate
													(
														{
															left:0,
															opacity: 1
														},
														500,
														'linear'
														
													);
			
			//slider2.cur = slider2.num-1;
			$('#'+d[0].id).stop().animate
													(
														{
															left:206,
															opacity: 1
														},
														570,
														function() {
															
															pos = slider2.num-1;
															for (i=0; i<slider2.num-1; i++ ){
																$('#'+d[i].id).css ('left', (i-pos)*206);
															}
																					
															slider2.on(slider2.num-1);
															slider2.text(d[slider2.num-1]);
															slider2.cur = slider2.num-1;
															slider2.lock_slide = 0;
															//alert (slider2.cur);
  													}
														
														
													);
													
  		//slider2.cur=slider2.num-1;
	}
	//alert (previous);
	else{
		//alert (previous);
  	slider2.slide(previous);
	}
	
 },
 
 slide:function(pos){
	
	//alert (slider2.lock_slide);
	
	if (slider2.lock_slide == 1)
		return;
		
	slider2.lock_slide = 1;
  //if(pos<0 || pos>=slider2.num || pos==slider2.cur)
	if(pos == slider2.cur){
   	//alert ('unlock lock');
		slider2.lock_slide  = 0;
		return;
	}
	//alert (slider2.cur);
	//slider2.cur = 4;
  window.clearTimeout(slider2.al);
  slider2.al=window.setTimeout('slider2.auto();',slider2.at);

  var d=slider2.data;
	//alert (pos);
	//alert (slider2.cur);
	if (pos == slider2.num) {
		
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
														'linear'
														
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
		$('#'+d[slider2.cur].id).stop().animate
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
															for (i=1; i<slider2.num; i++ ){
																$('#'+d[i].id).css ('left', i*206);
															}
															//$('#'+d[slider2.cur].id).css ('left', (slider2.cur)*206);
															//slider2.on(0);
															//slider2.text(d[0]);
															//slider2.cur = 0;
															
															slider2.lock_slide = 0;
															
  													}
														
													);
		
		
		//alert (slider2.cur);	
	}
	
	else{
  	for(var i=0;i<slider2.num;i++){
   		
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
															slider2.lock_slide = 0;
														}
														
													);
			}
			
		}
		
	}
	
	//if (slider2.cur == slider2.num)
		//pos = 0;
		
  slider2.on(pos);
  slider2.text(d[pos]);
  slider2.cur=pos;
 },
 on:function(pos){
  $('#slide-nav2 a').removeClass('select');
  $('#slide-nav2 a#slide-link2-'+pos).addClass('select');
 },
 text:function(di){
  slider2.cr['a']=di.title;
  slider2.cr['b']=di.desc;
	
	$('#slide-title2 a').attr('href', di.forward);	
  slider2.ticker('#slide-title2 span',di.title,0,'a');
  slider2.ticker('#slide-desc2',di.desc,0,'b');
 },
 ticker:function(el,text,pos,unique){
  if(slider2.cr[unique]!=text)
   return false;

  ctext=text.substring(0,pos)+(pos%2?'-':'_');
  $(el).html(ctext);

  if(pos==text.length)
   $(el).html(text);
  else
   window.setTimeout('slider2.ticker("'+el+'","'+text+'",'+(pos+1)+',"'+unique+'");',30);
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