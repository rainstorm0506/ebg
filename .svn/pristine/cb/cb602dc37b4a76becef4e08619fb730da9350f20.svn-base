// 注册整个应用程序的各个模块，每个模块中定义各自的路由。
// 定义应用的模块，加载模块。配置拦截机
window.title = 'e办公移动平台——';
define(['app/module/procurement/common'],function(angularAMD){
	'use strict';
	var app = angular.module('ebangong',angular.modules).config(function($stateProvider,$urlRouterProvider){
		
		$stateProvider
		// ==================================== 企业集采
		.state('/',{
			url : '',
			templateUrl : 'view/procurement/company.html',
			controller :function($scope,$rootScope,$sessionStorage){
				$rootScope.title = title + "企业集采";
				$sessionStorage.currentPage = 'procurement'
			}
		})
		// ==================================== 我的采购单
		.state('my',{
			url : '/my',
			templateUrl : 'view/procurement/my.html',
			controller :function($scope,$rootScope){
				$rootScope.title = title + "我的采购单";
			}
		})
		// ==================================== 发布采购单
		.state('publish',{
			url : '/publish',
			templateUrl : 'view/procurement/publish.html',
			controller :function($scope,$rootScope,$timeout){
				$rootScope.title = title + "发布采购单";
				$scope.publish = function(){
					$scope.pop = true;
					$timeout(function(){
						$scope.pop = false;
					},3000)
				}
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
		$urlRouterProvider.otherwise('');
		
	}).config(function($httpProvider,publicProvider){
		publicProvider.$get($httpProvider)
	}).run(function(ePublic){
		ePublic.run();
	})
	return angularAMD.bootstrap(app);	// angularAMD启动 angular
})