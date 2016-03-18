// ====================================== 导航
define(['angularAMD'],function(AngularAMD){
	AngularAMD.directive('selectOption',function(){
		return function(scope,elem,attr){
			var $target = angular.element(document.querySelector('#' + attr.selectOption));
			elem.children().on('touchstart mousedown',function(){
				var $this = angular.element(this);
				var oTxt = $this.text();
				var sTxt = $target.text();
				$target.text(oTxt);
				$this.text(sTxt);
				$this.parent().removeClass('current');
				scope.$apply(function(){
					scope.isName = false;
					scope.isFloor = false;
				})
			})
		}
	})
})