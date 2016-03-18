// ======================  选择当前样式
$(function($){
	$.fn.choiceCurrent = function()
	{
		$(this).not('.last').click(function(){
			$(this).addClass('current').siblings().removeClass('current')
		})
	}
});