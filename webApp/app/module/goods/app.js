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
	     creditsInfo : 'credits.info',					// 积分商城详情
	  confirmConvert : 'credits.confirmConvert',		// 积分兑换（确认收货）
	   getMyconverts : 'usercenter.getMyconverts',	    // 我的兑换
	      // ========================= 收藏商品 API
	   collectCreate : 'collect.create',
	     // ========================= 加入购物车 API
	        cartJoin : 'cart.join',						// 加载购物车
            cartList : 'cart.index',					// 购物车列表
            cartNum : 'cart.cartNum',					// 加载购物车
           cartClear : 'cart.clear',					// 清除购物车
        changeAmount : 'order.changeAmount	',			// 异步请求改变商品数量
              settle : 'order.settle',					// 购物车结算
            promptly : 'order.promptly',				// 立即购买
             closing : 'order.closing',					// 立即购买
         submitOrder : 'order.submitOrder'				// 确认订单信息
	      
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
			controller :function($scope,$rootScope,$stateParams,$location,$ajax,ePublic){
				$rootScope.title = title + "商品详情";
				$scope.cType = 1;
				$scope.bType = 1;	// 商品的类型 type
				var id = $stateParams.id ? $stateParams.id : $location.search().id;
				$ajax.query(API.goodsInfo,{id:$stateParams.id},function(data){
					$scope.data = data;
					$scope.attrs = data.attrs.attrs;
					$scope.attrsLength = ePublic.dataSize(data.attrs.attrs);
					$scope.attrVal = data.attrs.attrVal;
					$scope.carNum();
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
				$scope.bType = 2;	// 商品的类型 type
				mData.init($scope,$ajax,$stateParams)
			},
			resolve : {
				mData: function() {
					return {
						init: function($scope,$ajax,$stateParams){
							$ajax.query(API.secondInfo,{id:$stateParams.id},function(data){
								$scope.data = data;
								$scope.carNum();
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
			templateUrl : 'view/goods/integral/detail.html',
			controller :function($scope,$rootScope,$stateParams,$ajax){
				$rootScope.title = title + '积分商城详情';
				$ajax.query(API.creditsInfo,{id:$stateParams.id},function(data){
					$scope.data = data;
				});
			}
		})
		// ==================================== 兑换确认
		.state('integralExchange',{
			url : '/integralExchange/{id}',
			templateUrl : 'view/goods/integral/confirm.html',
			controller : function($scope,$rootScope,$stateParams,$ajax){
				$rootScope.title = title + '我的兑换';
				$ajax.query(API.creditsInfo,{id:$stateParams.id},function(data){
					$scope.data = data;
				});
			}
		})
		// ==================================== 我的兑换
		.state('exchange',{
			url : '/exchange',
			templateUrl : 'view/goods/integral/exchange.html',
			controller : function($scope,$rootScope,$ajax){
				$rootScope.title = title + '我的兑换';
				$ajax.query(API.getMyconverts,{},function(data){
					$scope.data = data;
				})
			}
		})
		// ==================================== 购物车
		.state('cart',{
			url : '/cart',
			templateUrl : 'view/goods/cart.html',
			controller :function($scope,$rootScope,$sessionStorage,$localStorage,$state,$timeout,$ajax,ePublic){
				$rootScope.title = title + '购物车';
				$scope.shoppingAmount = 1;
				$sessionStorage.currentPage = 'cart';
				// 读取购物车数据
				$ajax.query(API.cartList,{apt:E.time()},function(data){
					$scope.data = data;
					delete $scope.data.select;
					$scope.dataLength = ePublic.dataSize(data);
				})
				
				// 删除单选购物车商品
				$scope.delCart = function(key,id,goodsId){
					
					$ajax.post(API.cartClear,{key:id},function(del){
						if(del.code === 0){
							delete $scope.data[key].goods[id];
							if(ePublic.dataSize($scope.data[key].goods) == 0){
								delete $scope.data[key];
								$scope.dataLength = ePublic.dataSize($scope.data);
							}
							ePublic.removeStr($localStorage.cartId,goodsId);
							$scope.promt('删除购物车商品成功!');
						}else{
							$scope.promt('删除购物车商品失败!');
						}
					})
				}
				
				// 删除多选购物车商品
				$scope.delCartAll = function(){
					var goods = $scope.checkboxModel.goods;
					var flag = true;
					var stack = [];
					angular.forEach(goods,function(data,key){
						angular.forEach(goods[key],function(data,i){
							if(goods[key][i]){
								flag = false;
								stack.push(i);
							}
						})
					})
					if(flag){
						$scope.promt('您还未选择删除项!')
					}else{
						$ajax.post(API.cartClear,{key:stack},function(del){
							if(del.code === 0){
								angular.forEach(stack,function(data,index){ // 数姐
									angular.forEach(goods,function(val,key){	// store
										angular.forEach(goods[key],function(tt,i){ // goods i = 568897982ad3c9ad8183931ebb562b0b
											if(data === i){
												ePublic.removeStr($localStorage.cartId,$scope.data[key].goods[i].id);
												delete $scope.data[key].goods[i];
												delete goods[key][i];
												if(ePublic.dataSize($scope.data[key].goods) == 0){
													delete $scope.data[key];
													delete goods[key];
													$scope.dataLength = ePublic.dataSize($scope.data);
												}
											}
										})
									})
								})
								$scope.promt('删除购物车商品成功!');
							}else{
								$scope.promt('删除购物车商品失败!');
							}
						})
					}
				}
				// 购物车结算
				$scope.cartSettlement = function(){
					var goods = $scope.checkboxModel.goods;
					var len = 0;
					angular.forEach(goods,function(data,key){
						angular.forEach(data,function(d,k){
							if(d){
								len++;
							}
						})
					})
					//if($scope.totalMoney == 0){
					if(len == 0){
						$scope.promt('您还未选择任何商品!');
					}else{
						$ajax.post(API.settle,{goods:$scope.goodsKey},function(data){
							if(data.code === 0){
								$scope.promt('结算成功，请稍候!');
								$timeout(function(){
									$state.go('orderConfirm');
								},2000)
							}
						})
					}
				}
			}
		})
		// ==================================== 确认订单
		.state('orderConfirm',{
			url : '/orderConfirm',
			templateUrl : 'view/goods/order/confirm.html',
			controller : function($scope,$rootScope,$state,$localStorage,$timeout,$ajax,ePublic){
				$rootScope.title = title + '确认订单';
				// 付款页面显示隐藏
				$scope.pay = {
					openPayDetail : function(){
						$scope.isPayDetail = true;
						$scope.isPay = false;
					},
					colsePayDetail : function(){$scope.isPayDetail = false;},
					openPay : function(){
						if(!$scope.hasDefault){ // 无默认值
							$scope.promt('您还没有选择收货地址，请先选择!');
							return;
						}
						$scope.isPay = true;
						$scope.isPayDetail = false;
					},
					closePay : function(){$scope.isPay = false;},
					success : function(){
						var param = {
							userAddressID : $scope.address.id,
							payType : $scope.payType,
							deliveryWay : $scope.sendType,
							changeLock : '6d0647529e4a71f317fe0d1f0ca7aa14',
							remark : $scope.message
						}
						console.log(param)
						$ajax.post(API.submitOrder,param,function(data){
							if(data === 0){
								$scope.promt('结算成功!')
							}
						})
						//$state.go('orderSuccess');
					}
				}
				// 收货地址判断
				var mid = 0;
				$ajax.query(E.API.myAddress,{},function(data){
					angular.forEach(data,function(val,key){
						if(val['is_default'] != 0){
							$scope.hasDefault = true;
							$scope.address = val;
						}
					})
					$scope.confirmAddress = function(){
						if(ePublic.dataSize(data) === 0){ // 无地址
							$scope.promt('您还没有添加收货地址，去添加!');
							$timeout(function(){
								$scope.href('center.html#/add');
								$localStorage.preveAddress = 'index.html#/orderConfirm';	// 地址记录
							},2000)
							return;
						}
						if(!$scope.hasDefault){ // 无默认值
							selectAddress();
						}
					}
					
					// 从购物车列表读立即购买
					$ajax.query(API.cartList,{apt:E.time()},function(data){
						var stack = {
							goods : {},
							store : {}
						};
						for(var i in data.select){
							var select = i;
						}
						angular.forEach(data,function(value,key){
							angular.forEach(value.goods,function(v,i){
								if(select === i){
									$scope.goods = v;
									$scope.storeName = value.store_name;
								}
							})
						})
						
						// 订单接口
						$ajax.query(API.closing,{},function(data){
							$scope.order = data;
							//console.log(data.reduction)
						})
					})
				})
				
				var selectAddress = function(){
					$scope.openAddress = true;	// 打开地址栏
					$ajax.query(E.API.myAddress,{},function(data){
						$scope.sAddress = data;
					})
				}
				
				$scope.selectAddress = function(){
					selectAddress();
				}
				
				$scope.sAddressFinish = function(){
					var $this = angular.element(document.querySelector("#sAddressFinish"));
					$this.ready(function(){
						$this.find('li').on('touchstart mousedown',function(){
							var $this = angular.element(this);
							$ajax.query(E.API.myAddress,{id:$this.attr('uid')},function(data){
								$scope.address = data[0];
								$scope.openAddress = false;
								$scope.hasDefault = true;
							})
						})
					})
				}
			}
		})
		// ==================================== 订单成功
		.state('orderSuccess',{
			url : '/orderSuccess',
			templateUrl : 'view/goods/order/success.html',
			controller : function($scope,$rootScope){
				$rootScope.title = title + '订单成功';
			}
		})
		// ==================================== 商品评价
		.state('goodsCommit',{
			url : '/goodsCommit',
			templateUrl : 'view/goods/commit.html',
			controller : function($scope,$rootScope){
				$rootScope.title = title + '商品评价';
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
		
		$urlRouterProvider.otherwise('/');
		
	}).config(function($httpProvider,publicProvider){
		publicProvider.$get($httpProvider)
	}).run(function(ePublic,$sessionStorage){
		ePublic.run();
	});
	
	return angularAMD.bootstrap(app);	// angularAMD启动 angular
})