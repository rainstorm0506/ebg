// ====================================== 导航
define(['angularAMD'],function(AngularAMD){
	AngularAMD.directive('searchReset',function(){
		return {
			restrict : 'A',
			link : function(scope,elem,attr){
				var $reset = angular.element(document.querySelectorAll('.sc-nav'));
				elem.on('touchstart mousedown',function(){
					$reset.children().removeClass('current');
					angular.forEach($reset,function(data,index){
						$reset.eq(index).children().eq(0).addClass('current');
						$reset.find('input').val('')
					})
				})
			}
		}
	})
})