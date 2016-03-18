// ====================================== 导航
define(['angularAMD'],function(AngularAMD){
	AngularAMD.directive('checkboxs',function(){
		return {
			restrict : 'E',
			replace : true,
			template : '<div class="checbox-wrap"><label><b><i></i></b><input type="checkbox"></label></div>',
			link : function(scope,elem,attr){
				elem.find('input').on('change',function(){
					var $this = angular.element(this);
					var $i = $this.parent().find('i');
					if($this.prop('checked')){
						$i.css('display','block');
					}else{
						$i.css('display','none');
					}
				})
			}
		}
	})
})