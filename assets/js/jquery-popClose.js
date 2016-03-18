$(function($){
// 关闭弹层
$.fn.popClose = function(option){
	$(this).click(function(){
		$(window.parent.document).find('.pop-iframe').fadeOut()
	})
}
$('#close').popClose()
});