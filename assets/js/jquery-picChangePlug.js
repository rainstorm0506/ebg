$(function($){
/*
 * function：图层轮转效果
 * author: j+2,
 * date：2012-06-06
 * modify：2013-08-21
 */
$.fn.picChangePlug = function(){
	
	$(this).click(function(){
		
		var $img = $(this).find('img')
		
		$(this).parent().prev().find('img').attr({
				 'src':$img.attr('src'),
			'data-src':$img.attr('data-src')
		});
		
		$(this).addClass('current').siblings().removeClass('current');
		
		return false;
		
	});
	
	$(this).each(function(){
		$(this).parent().children().eq(0).addClass('current');
	})
}
});