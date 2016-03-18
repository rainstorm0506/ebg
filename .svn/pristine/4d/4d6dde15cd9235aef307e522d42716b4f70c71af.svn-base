$(function($){
/**
 * function：简单弹出层
 * author：j+2
 * date：2015-04-09
 */
 
~function(){
	$.fn.simplePup = function(option){
		var opt = {
			wrap : "#floatWraper",
			mask : "#maskbox",
		   close : "#close",
		   initCallback : function(){},	// 自动运行函数
		   openCallback : function(){},	// 弹层开口时执行
		   closeCallback : function(){} // 弹层关闭时执行
		}
		
		$.extend(opt,option);
		
		var $wrap = $(opt.wrap);
		var $mask = $(opt.mask);
		var $this = $(this);
		
		opt.initCallback()
		
		$(this).click(function(){
			$wrap.fadeIn();
			$mask.fadeIn();
			opt.openCallback($(this));
			return false;
		});
		
		$(opt.close).click(function(){
			$wrap.fadeOut();
			$mask.fadeOut();
			opt.closeCallback($this);
			return false;
		});
	}
}()
});