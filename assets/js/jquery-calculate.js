$(function($){
/**
 * function：数量加减
 * author：j+2
 * date：2015-04-06
 */
 
$.fn.calculate = function(option){
	var opt = {
		callback : function(val){
			if(val >5){
				alert(0);
				return false;
			}
			return true
		}
	}
	$.extend(opt,option)
	$(this).each(function(){
		var $prev = $(this).children().first();
		var $input = $prev.next();
		var $next = $(this).children().last();

		$prev.click(function(){
			var val = getVal();
			val--;
			if(val <= 1){
				val = 1;
				$prev.addClass("disabled");
			}
			$input.val(val);
			return false;
		}).eq(0).click();
		
		$next.click(function(){
			var val = getVal();
			val++;
			val > 1 && $prev.removeClass("disabled");
			
			if(!opt.callback($input,val)){
				return;
			}
			
			$input.val(val);
			return false;
		});
		
		$input.keyup(function(){
			var val = getVal();
			if(val <= 1){
				$prev.addClass("disabled");
				$(this).val(val);
			}else{
				$prev.removeClass("disabled");
			}
			opt.callback($(this),val)
		})
		
		function getVal(){
			var val = $.trim($input.val());
				val = parseInt(val);
				val = typeof val === 'number' && val>0 ? val : 1;
			return val;
		}
	})
}
});