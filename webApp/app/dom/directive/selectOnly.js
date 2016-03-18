// ============================================================ 选择唯一
define(['angularAMD'],function(angularAMD){
	'use strict';
	angularAMD.directive('selectOnly',function(){
		return function(scope,elem,attr){
			angular.element(document).ready(function(){
				setTimeout(function() {
					elem.children().on('touchstart mousedown',function(index){
						var $this = angular.element(this);
						var $child = $this.parent().children();
						
						angular.forEach($child,function(data,index){
							if($child.eq(index).html() === $this.html()){
								$this.addClass('current');
								//if(opt && opt.yesFn) opt.yesFn(scope,elem,attr,$this);
							}else{
								$child.eq(index).removeClass('current');
							}
						});
						//if(opt && opt.noFn) opt.noFn(scope,elem,attr,$this);
					});
				}, 1);
			})
		}
	});
})