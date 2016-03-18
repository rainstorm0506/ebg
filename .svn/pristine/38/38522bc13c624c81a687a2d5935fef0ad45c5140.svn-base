// ============================================================ 密码一致性验证
define(['angularAMD'],function(angularAMD){
	'use strict';
	angularAMD.directive('calculate',function(){
		return function(scope,elem,attr){
			var num = scope.calNum;
			var $first = elem.children().eq(0);
			var $input = elem.children().eq(1);
			var $last = elem.children().eq(2);
			var max = parseInt(attr.calculate);

			scope.add = function(){
				num = parseInt($input.val()) + 1;
				num > 1 && $first.removeClass("disabled");
				if(num >= max) {
					$last.addClass('disabled');
					return false
				}else{
					$last.hasClass('disabled') && $last.removeClass('disabled');
				}
				scope.calNum = num;
			}
			scope.reduction = function(){
				num = parseInt($input.val()) - 1;
				if(num <= 1){
					num = 1;
					$first.addClass("disabled");
				}
				
				num < max && $last.removeClass("disabled");
				
				scope.calNum = num;
			}
			scope.inputKeyUp = function(){
				var num = scope.calNum;
				num > 1 ? $first.removeClass("disabled") : $first.addClass("disabled");
				
				if(num < 0){
					scope.calNum = 1;
				}
				
				if(num >= max){
					$last.addClass('disabled');
					scope.calNum = max;
				}else{
					$last.hasClass('disabled') && $last.removeClass('disabled');
				}
			}
			scope.inputBlur = function(){
				if(scope.calNum <= 0){
					scope.calNum = 1;
				}
			}
		}
	})
})