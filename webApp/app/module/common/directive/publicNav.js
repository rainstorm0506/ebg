// ====================================== 导航
define(['angularAMD'],function(AngularAMD){
	AngularAMD.directive('publicNav',function($sessionStorage){
		return {
			restrict : 'E',
			replace : true,
			scope :{},
			template : '<nav class="home-nav">\
							<a href="index.html" ng-class="{current:cpage === P.index}"><i class="ico-n-1"></i><span>首页</span></a>\
							<a href="procurement.html" ng-class="{current:cpage === P.procurement}"><i class="ico-n-2"></i><span>集采</span></a>\
							<a href="store.html#/floor" ng-class="{current:cpage === P.floor}"><i class="ico-n-3"></i><span>逛一逛</span></a>\
							<a href="index.html#/cart" ng-class="{current:cpage === P.cart}"><i class="ico-n-4"></i><span>购物车</span></a>\
							<a href="center.html" ng-class="{current:cpage === P.center}"><i class="ico-n-5"></i><span>我的</span></a>\
						</nav>',
			link : function(scope){
				scope.cpage = $sessionStorage.currentPage;
				scope.P = {
					index : 'index',
					procurement : 'procurement',
					floor : 'floor',
					cart : 'cart',
					center : 'center'
				};
			}
		}
	})
})