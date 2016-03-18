﻿// 注册整个应用程序的各个模块，每个模块中定义各自的路由。
// 定义应用的模块，加载模块。配置拦截机
window.title = 'e办公移动平台——';
define(['app/module/store/common'],function(angularAMD){
	'use strict';
	var app = angular.module('ebangong',angular.modules).config(function($stateProvider,$urlRouterProvider){
		
		$stateProvider.state('store',{
			url : '/store',
			templateUrl : 'view/store/store.html',
			controller :function($scope,$rootScope){
				$rootScope.title = title + "店铺首页";
				$rootScope.isHeader = false;
				$scope.isStoreList = true;
				
			}
		})
		// ==================================== 店铺首页
		.state('store.all',{
			url : '/all',
			views : {
				'viewStoreGoods' : {
					templateUrl : 'view/common/list.html',
					controller : function($scope,$rootScope){
						$rootScope.isSearch = true;
						$rootScope.isHeader = false;
					}
				}
			}
		}).state('store.sales',{
			url : '/sales',
			views : {
				'viewStoreGoods' : {
					templateUrl : 'view/common/list.html',
					controller : function($scope,$rootScope){
						$rootScope.isSearch = true;
						$rootScope.isHeader = false;
					}
				}
			}
		}).state('store.price',{
			url : '/price',
			views : {
				'viewStoreGoods' : {
					templateUrl : 'view/common/list.html',
					controller : function($scope,$rootScope){
						$rootScope.isSearch = true;
						$rootScope.isHeader = false;
					}
				}
			}
		}).state('store.time',{
			url : '/time',
			views : {
				'viewStoreGoods' : {
					templateUrl : 'view/common/list.html',
					controller : function($scope,$rootScope){
						$rootScope.isSearch = true;
						$rootScope.isHeader = false;
					}
				}
			}
		})
		// ==================================== 店铺详情
		.state('store.detail',{
			url : '/detail',
			views : {
				'viewStoreGoods' : {
					templateUrl : 'view/store/detail.html',
					controller : function($scope,$rootScope){
						$rootScope.title = title + "店铺详情";
						$rootScope.isSearch = false;
						$rootScope.isHeader = true;
					}
				}
			}
		})
		// ==================================== 产品分类
		.state('classify',{
			url : '/classify',
			templateUrl : 'view/store/classify.html',
			controller :function($scope,$rootScope){
				$rootScope.title = title + "产品分类";
			}
		})
		// ==================================== 地图中查看
		.state('map',{
			url : '/map',
			templateUrl : 'view/store/map.html',
			controller :function($scope,$rootScope){
				$rootScope.title = title + "地图详情";
			}
		})
		// ==================================== 逛一逛
		.state('floor',{
			url : '/floor',
			templateUrl : 'view/store/floor.html',
			controller :function($scope,$rootScope){
				$rootScope.title = title + "逛一逛";
				
			}
		})
		// ==================================== 404
		.state('404',{
			url : '/404',
			templateUrl : '404.html',
			controller :function($rootScope){
				$rootScope.title = "404";
			}
		})
		// ==================================== 重定向
		$urlRouterProvider.when('','store/all')
						  .when('/','store/all')
						  .when('/store','store/all')
						  .otherwise('/');
		
	}).config(function($httpProvider,publicProvider){
		publicProvider.$get($httpProvider)
	}).run(function(ePublic){
		ePublic.run();
	});
	return angularAMD.bootstrap(app);	// angularAMD启动 angular
})