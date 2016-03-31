﻿// 注册整个应用程序的各个模块，每个模块中定义各自的路由。
// 定义应用的模块，加载模块。配置拦截机
window.title = 'e办公移动平台——';
window.API = {
		// ========================= 集采  API
	 myPurchaseList : 'companyUser.myPurchaseList',		// 集采列表
	 createPurchase : 'companyUser.createPurchase',		// 集采发布
  	       getSplit : 'companyUser.getSplit',			// 集采拆分之后的详情
}
define(['app/module/procurement/common'],function(angularAMD){
	'use strict';
	var app = angular.module('ebangong',angular.modules).config(function($stateProvider,$urlRouterProvider){
		
		$stateProvider
		// ==================================== 企业集采
		.state('/',{
			url : '/',
			templateUrl : 'view/procurement/company.html',
			controller :function($scope,$rootScope,$sessionStorage,$ajax){
				$rootScope.title = title + "企业集采";
				$sessionStorage.currentPage = 'procurement';
			}
		})
		// ==================================== 我的采购单
		.state('my',{
			url : '/my',
			templateUrl : 'view/procurement/my.html',
			controller :function($scope,$rootScope,$ajax){
				$rootScope.title = title + '我的采购单';
			}
		})
		// ==================================== 发布采购单
		.state('publish',{
			url : '/publish',
			templateUrl : 'view/procurement/publish.html',
			controller :function($scope,$rootScope,$timeout,$ajax){
				$rootScope.title = title + '发布采购单';
				$scope.jiCai = true;	// 判断验证发送类型
				$scope.publish = function(flag){
					if(flag) return;
					var param = {
						       link_man : $scope.name,
						          phone : $scope.tel,
						          vxCode : $scope.telImgCode,
						           code : $scope.telCode,
						is_tender_offer : ($scope.isTender = $scope.isTender ? 1 : 0),
						   is_interview : ($scope.isTalk = $scope.isTalk ? 1 : 0),
						      file_data : $scope.uploadFile
					}
					$ajax.post(API.createPurchase,param,function(data){
						if(data.code == 0){
							$scope.pop = true;
							$timeout(function(){
								//$scope.pop = false;
								$rootScope.go();
							},2000)
						}else{
							$scope.promt('发布失败，请检查填写信息!');
						}
					})
					
				}
			}
		})
		// ==================================== 报价详情
		.state('offerDetail',{
			url : '/offerDetail/:id',
			templateUrl : 'view/procurement/offer-detail.html',
			controller :function($scope,$rootScope,$ajax){
				$rootScope.title = title + '报价详情';
			}
		})
		// ==================================== 采购单详情
		.state('detail',{
			url : '/detail/:id',
			templateUrl : 'view/procurement/detail.html',
			controller :function($scope,$rootScope,$stateParams,$ajax){
				$rootScope.title = title + '采购单详情';
				$ajax.query(API.getSplit,{purchase_sn:$stateParams.id},function(data){
					$scope.data = data;
				})
			}
		})
		// ==================================== 404
		.state('404',{
			url : '/404',
			templateUrl : '404.html',
			controller :function($rootScope){
				$rootScope.title = '404';
			}
		})
		// ==================================== 重定向
		$urlRouterProvider.otherwise('/');
		
	}).config(function($httpProvider,publicProvider){
		publicProvider.$get($httpProvider)
	}).run(function(ePublic){
		ePublic.run();
	})
	return angularAMD.bootstrap(app);	// angularAMD启动 angular
})