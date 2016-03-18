// ============================================================ 密码一致性验证
define(['angularAMD'],function(angularAMD){
	'use strict';
	angularAMD.directive('pwCheck',function(){
		return{
			require : 'ngModel',	// 指定了控制器来自哪个标识符
			link : function(scope,elem,attr,ctr){
				var pass = angular.element(document.getElementById(attr.pwCheck));
				elem.on('keyup',pwCheck);
				pass.on('keyup',pwCheck)
				
				function pwCheck(){
					scope.$apply(function(){
						var v = elem.val() === pass.val();
						ctr.$setValidity('pwmatch',v);	// 改变验证状态，以及在控制变化的验证标准时通知表格
					})
				}
			}
		}
	})
})