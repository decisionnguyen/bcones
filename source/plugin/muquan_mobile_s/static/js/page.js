var auto=1;
var botH=1;
var con=url.indexOf('?')>0?'&':'?';
var rurl=url;
var ajaxpg=$('.ajaxpg');
var ing=$(".ajaxpg #ing");
var more=$(".ajaxpg #more");
function ajaxpage(){
  ing.css('display','block');
  more.css('display','none');
  paged++;
  url=rurl+con+'page='+paged;
  $.get(url,function(s){
	   s = s.substring(s.indexOf("<div id=\"alist\">"), s.indexOf("<div id=\"ajaxshow\"></div>"));
	   $('#ajaxshow').append(s);
	   ing.css('display','none');
	   if(paged>=allpage){
		 ajaxpg.css('display','none');
	   }
	   else{
	     more.css('display','block');
	   }
  });
}
more.click(function(){
    ajaxpage();
});

if(paged>=allpage || allpage<1){ajaxpg.css('display','none');}

if(auto==1){
	$(window).scroll(function(){ 
		var srollPos = $(window).scrollTop(); 
		var winHeight=$(window).height(); 
		var contentH =$(document).height();
		if(contentH - winHeight - srollPos <=botH && paged<allpage) { 
			 ajaxpage();
		}
	}); 
} 