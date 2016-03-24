﻿// 注册整个应用程序的各个模块，每个模块中定义各自的路由。
// 定义应用的模块，加载模块。配置拦截机
window.title = 'e办公移动平台——';
window.API = {
		// ========================= 首页  API
		 commodities : 'home.commodities',				// 办公用品
		// ========================= 二手市场 API
		   goodsList : 'goods.list',					// 全新市场模块
     goodsConditions : 'used.conditions',				// 全新商品筛选条件
		   goodsInfo : 'goods.info',					// 全新商品详情
		// ========================= 二手市场 API
	      secondHand : 'used.list',						// 二手市场模块
	secondConditions : 'used.conditions',				// 二手商品筛选条件
	      secondInfo : 'used.info',						// 二手商品详情
	       // ========================= 积分商城 API
	     creditsList : 'credits.list',					// 积分商城
	     creditsInfo : 'credits.info',					// 积分商城详情
	      // ========================= 收藏商品 API
	   collectCreate : 'collect.create',
	     // ========================= 加入购物车 API
	        cartJoin : 'cart.join',
            cartList : 'cart.index',					// 购物车列表
            cartClear : 'cart.clear',					// 清除购物车
}
define(['app/module/goods/common'],function(angularAMD){
	'use strict';
	var app = angular.module('ebangong',angular.modules).config(function($stateProvider,$urlRouterProvider){
		
		$stateProvider.state('/',{
			url :'/',
			templateUrl : 'view/index.html',
			controller : function($scope,$rootScope,$sessionStorage,$ajax){
				$rootScope.title = title + "首页";
				$sessionStorage.currentPage = 'index';
				$ajax.query(API.commodities,{oneid:'2135'},function(data){
					$rootScope.BAN = data;
				});
			}
		})
		// ==================================== 商品搜索，排序
		.state('goods',{
			url : '/goods',
			templateUrl : 'view/goods/goods.html',
			controller :function($scope,$state,$rootScope,ePublic){
				$rootScope.title = title + "商品列表";
				$rootScope.hasBtn = false;
				$scope.isGoodsListAll = true;
			}
		}).state('goods.all',{
			url : '/all',
			views : {
				'viewMain' : {
					templateUrl : 'view/common/list.html',
					controller : function($scope,$rootScope,ePublic){
						$rootScope.hasBtn = false;
						ePublic.sort($scope,{
								api : 'goodsList',
							     by : 'putaway',
							    asc : 'allAsc',
							 method : 'byAll'
						})
					}
				}
			}
		}).state('goods.sales',{
			url : '/sales',
			views : {
				'viewMain' : {
					templateUrl : 'view/common/list.html',
					controller : function($scope,$rootScope,ePublic){
						$rootScope.hasBtn = false;
						ePublic.sort($scope,{
								api : 'goodsList',
							     by : 'detail',
							    asc : 'salesAsc',
							 method : 'bySales'
						})
					}
				}
			}
		}).state('goods.price',{
			url : '/price',
			views : {
				'viewMain' : {
					templateUrl : 'view/common/list.html',
					controller : function($scope,$rootScope,ePublic){
						$rootScope.hasBtn = false;
						ePublic.sort($scope,{
								api : 'goodsList',
							     by : 'price',
							    asc : 'priceAsc',
							 method : 'byPrice'
						})
					}
				}
			}
		}).state('goods.screening',{
			url : '/screening',
			views : {
				'viewMain' : {
					templateUrl : 'view/goods/goods-search/search.html',
					cache : 'true',
					controller : function($scope,$rootScope,$stateParams,$ajax,mData){
						$rootScope.hasBtn = true;
						mData.init($scope,$ajax,$stateParams);
					},
					resolve : {
						mData: function() {
							return {
								init: function($scope,$ajax){
									$ajax.query(API.goodsConditions,{apt:E.time()},function(data){
										$scope.data = data;
									})
								}
							}
						}
					}
				}
			}
		})
		// ==================================== 商品详情
		.state('detail',{
			url : '/detail/:id',
			templateUrl : 'view/goods/detail.html',
			controller :function($scope,$rootScope,$stateParams,$location,$ajax){
				$rootScope.title = title + "商品详情";
				$scope.cType = 1;
				var id = $stateParams.id ? $stateParams.id : $location.search().id;
				$ajax.query(API.goodsInfo,{id:$stateParams.id},function(data){
					$scope.data = data;
				})
			}
		})
		// ==================================== 二手商品搜索，排序
		.state('secondHand',{
			url : '/secondHand',
			templateUrl : 'view/goods/second-hand.html',
			controller :function($scope,$rootScope){
				$rootScope.title = title + "二手商品";
				$rootScope.hasBtn = false;
				$scope.isGoodsList = true;
			},
			onEnter : function(){				
				setTimeout(function(){
					angular.element(document.querySelector('body')).addClass('second-hand');
				},1)
			},
			onExit : function(){
				angular.element(document.querySelector('body')).removeClass('second-hand');
			}
		}).state('secondHand.all',{
			url : '/all',
			views : {
				'viewMain' : {
					templateUrl : 'view/common/list.html',
					controller : function($scope,$rootScope,ePublic){
						$rootScope.hasBtn = false;
						ePublic.sort($scope,{
								api : 'secondHand',
							     by : 'putaway',
							    asc : 'allAsc',
							 method : 'byAll'
						})
					}
				}
			}
		}).state('secondHand.sales',{
			url : '/sales',
			views : {
				'viewMain' : {
					templateUrl : 'view/common/list.html',
					controller : function($scope,$rootScope,ePublic){
						$rootScope.hasBtn = false;
						ePublic.sort($scope,{
								api : 'secondHand',
							     by : 'detail',
							    asc : 'salesAsc',
							 method : 'bySales'
						})
					}
				}
			}
		}).state('secondHand.price',{
			url : '/price',
			views : {
				'viewMain' : {
					templateUrl : 'view/common/list.html',
					controller : function($scope,$rootScope,ePublic){
						$rootScope.hasBtn = false;
						ePublic.sort($scope,{
								api : 'secondHand',
							     by : 'price',
							    asc : 'priceAsc',
							 method : 'byPrice'
						})
					}
				}
			}
		}).state('secondHand.screening',{
			url : '/screening',
			views : {
				'viewMain' : {
					templateUrl : 'view/goods/goods-search/search.html',
					cache : 'true',
					controller : function($scope,$rootScope,$ajax,mData){
						$rootScope.hasBtn = true;
						mData.init($scope,$ajax,$stateParams);
					},
					resolve : {
						mData: function() {
							return {
								init: function($scope,$ajax){
									$ajax.query(API.secondConditions,{apt:E.time()},function(data){
										$scope.data = data;
									})
								}
							}
						}
					}
				}
			}
		})
		// ==================================== 二手商品详情
		.state('secondDetail',{
			url : '/secondDetail/:id',
			templateUrl : 'view/goods/second-detail.html',
			controller :function($scope,$rootScope,$state,$stateParams,$ajax,mData){
				$rootScope.title = title + "二手商品详情";
				$scope.cType = 3;
				mData.init($scope,$ajax,$stateParams)
			},
			resolve : {
				mData: function() {
					return {
						init: function($scope,$ajax,$stateParams){
							$ajax.query(API.secondInfo,{id:$stateParams.id},function(data){
								$scope.data = data;
							})
						}
					}
				}
			}
		})
		// ==================================== 积分商城
		.state('integral',{
			url : '/integral',
			templateUrl : 'view/goods/integral.html',
			controller :function($scope,$rootScope){
				$rootScope.title = title + "积分商城";
				$scope.isIntegral = true;
			}
		}).state('integral.all',{
			url : '/all',
			views : {
				'viewMain' : {
					templateUrl : 'view/common/list.html',
					controller : function($scope,$rootScope,$ajax){
						$ajax.query(API.creditsList,{opt:E.time()},function(data){ // 默认降序
							$scope.data = data;
						})
					}
				}
			}
		}).state('integral.integral',{
			url : '/integral',
			views : {
				'viewMain' : {
					templateUrl : 'view/common/list.html',
					controller : function($scope,$rootScope,ePublic){
						ePublic.sort($scope,{
								api : 'creditsList',
							     by : 'points',
							    asc : 'integralAsc',
							 method : 'byIntegral'
						})
					}
				}
			}
		}).state('integral.amount',{
			url : '/amount',
			views : {
				'viewMain' : {
					templateUrl : 'view/common/list.html',
					controller : function($scope,$rootScope,ePublic){
						ePublic.sort($scope,{
								api : 'creditsList',
							     by : 'detail',
							    asc : 'amountAsc',
							 method : 'byAmount'
						})
					}
				}
			}
		}).state('integral.time',{
			url : '/time',
			views : {
				'viewMain' : {
					templateUrl : 'view/common/list.html',
					controller : function($scope,$rootScope,ePublic){
						ePublic.sort($scope,{
								api : 'creditsList',
							     by : 'putaway',
							    asc : 'timeAsc',
							 method : 'byTime'
						})
					}
				}
			}
		})
		// ==================================== 积分商城详情
		.state('integralDetail',{
			url : '/integralDetail/:id',
			templateUrl : 'view/goods/integral-detail.html',
			controller :function($scope,$rootScope,$stateParams,$ajax){
				$rootScope.title = title + "积分商城详情";
				$ajax.query(API.creditsInfo,{id:$stateParams.id},function(data){
					$scope.data = data;
				})
			}
		})
		// ==================================== 购物车
		.state('cart',{
			url : '/cart',
			templateUrl : 'view/goods/cart.html',
			controller :function($scope,$rootScope,$sessionStorage,$ajax,ePublic){
				$rootScope.title = title + "购物车";
				$scope.shoppingAmount = 1;
				$sessionStorage.currentPage = 'cart';
				//$scope.isCartEditor = true;
				$ajax.query(API.cartList,{apt:E.time()},function(data){
					$scope.data = data;
					$scope.dataLength = ePublic.dataSize(data);
					// 删除购物车商品
					//console.log(data[82]['goods']['3bf8d3b78db08072a0742915a22a5d68']['id'])
					$scope.delCart = function(id,goods,index){
						goods.splice(index,1);
						/*
						$ajax.post(API.cartClear,{key:id},function(del){
							if(del.code === 0){
								
								
								$scope.promt('删除购物车商品成功!');
							}else{
								$scope.promt('删除购物车商品失败!');
							}
						})*/
					}
				})
				
			}
		})
		// ==================================== 确认订单
		.state('orderConfirm',{
			url : '/orderConfirm',
			templateUrl : 'view/goods/order/confirm.html',
			controller : function($scope,$rootScope,$state){
				$rootScope.title = title + "确认订单";
				$scope.pay = {
					openPayDetail : function(){
						$scope.isPayDetail = true;
						$scope.isPay = false;
					},
					colsePayDetail : function(){$scope.isPayDetail = false;},
					openPay : function(){
						$scope.isPay = true;
						$scope.isPayDetail = false;
					},
					closePay : function(){$scope.isPay = false;},
					success : function(){
						$state.go('orderSuccess');
					}
				}
			}
		})
		// ==================================== 订单成功
		.state('orderSuccess',{
			url : '/orderSuccess',
			templateUrl : 'view/goods/order/success.html',
			controller : function($scope,$rootScope){
				$rootScope.title = title + "订单成功";
			}
		})
		// ==================================== 商品评价
		.state('goodsCommit',{
			url : '/goodsCommit',
			templateUrl : 'view/goods/commit.html',
			controller : function($scope,$rootScope){
				$rootScope.title = title + "商品评价";
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
		
		$urlRouterProvider.otherwise('/');
		
	}).config(function($httpProvider,publicProvider){
		publicProvider.$get($httpProvider)
	}).run(function(ePublic,$sessionStorage){
		ePublic.run();
	});
	
	return angularAMD.bootstrap(app);	// angularAMD启动 angular
})