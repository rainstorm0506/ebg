// ============================================================ 公共模块指令  ============================================================
define(['angularAMD'],function(angularAMD){
	'use strict';
	// ================================================== 标题模块指令
	angularAMD.directive('headerPublic',function(){
		return{
			restrict : 'E',
			replace : true,
			template : '<header class="header"><a href="javascript:window.history.go(-1);"><s class="tr-l"><i></i><b></b></s></a>\
							<h1 ng-bind="title | fTitle">e办公移动平台</h1>\
						</header>',
			link : function(scope,elem,attr){
				
			}
		}
	})
	angularAMD.filter('fTitle',function(){
		return function(name){
			if(name)
				return name.replace(title,'');
		}
	})
	// ================================================== 搜索模块指令
	angularAMD.directive('headerSearch',function(){
		return{
			restrict : 'E',
			replace : true,
			template : '<header class="header"><div class="search-box"><input id="search" type="text" placeholder="您想购买什么呢？" ng-model="search"><i></i></div></header>',
			link : function(scope,elem,attr){
				
			}
		}
	})
	// ================================================== 导航模块指令
	angularAMD.directive('publicNav',function($sessionStorage){
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
	// ================================================== 公共小模块
	angularAMD.directive('promt',function($timeout,$rootScope){
		return {
			restrict : 'E',
			replace : true,
			template : '<box>\
							<p class="promt" ng-if="!isLogin" ng-class="{current:flag}"><span ng-bind="promtText"></span></p>\
							<div class="loading-wrap" ng-if="loading"><p><i></i><span ng-bing="loadingTxt"></span></p></div>\
						</box>',
			link : function(scope,elem,attr){
				$rootScope.loading = false;

				$rootScope.promt = function(txt){
					scope.promtText = txt;
					scope.flag = true;
					$timeout(function(){
						scope.flag = false;
					},1500);
				}
			}
		}
	})
	// ================================================== 暂无数据
	angularAMD.directive('noData',function(){
		return {
			restrict : 'E',
			replace : true,
			template : '<div ng-if="data.length===0 || noData" class="no-data animated" ng-class="{flip:true}">暂无数据</div>',
			link : function(scope,elem,attr){
				
			}
		}
	})
	// ================================================== 确认弹窗
	angularAMD.directive('confirm',function(){
		return {
			restrict : 'E',
			replace : true,
			template : '<section class="pop-wrap confirm-wrap animated fadeIn" ng-if="pop">\
							<div>\
								<h6 ng-bind="yesText"></h6>\
								<nav class="wrap-nav">\
									<a class="btn-1-2" href="javascript:;" ng-click="yes()">确认</a>\
									<a class="btn-1" href="javascript:;" ng-click="no()">取消</a>\
								</nav>\
							</div>\
						</section>',
			link : function(scope,elem,attr){
				scope.pop = false;
			}
		}
	})
	
	
})