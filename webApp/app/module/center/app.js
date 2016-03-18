﻿// 注册整个应用程序的各个模块，每个模块中定义各自的路由。
// 定义应用的模块，加载模块。配置拦截机
window.title = 'e办公移动平台——';
window.API = {
	// ========================= 个人中心API
    userToCompany : 'usercenter.userToCompany',		// 会员升级为业用户
         userInfo : 'usercenter.userInfo',			// 基本信息
       myComments : 'usercenter.myComments',		// 我的评价
        myAddress : 'usercenter.myAddress',			// 我的收货地址 
       addAddress : 'usercenter.addAddress',		// 添加收货地址 
       delAddress : 'usercenter.delAddress',		// 删除收货地址 
      modifyPhone : 'usercenter.modifyPhone',		// 修改手机号码 
        updatePwd : 'usercenter.updatePwd',		// 修改密码
}
define(['app/module/center/common'],function(angularAMD){
	'use strict';
	var app = angular.module('ebangong',angular.modules).config(function($stateProvider,$urlRouterProvider){
		
		$stateProvider
		// ==================================== 我的个人中心
		.state('/',{
			url : '',
			templateUrl : 'view/center/my.html',
			controller : function($scope,$rootScope,$sessionStorage,$ajax){
				$rootScope.title = title + "我的个人中心";
				$sessionStorage.currentPage = 'center';
				$ajax.query(API.userInfo,{},function(data){
					$scope.userData = data;
					$scope.baseInfo = data.baseinfo;
				})
			}
		})
		// ==================================== 我的订单
		.state('order',{
			url : '/order',
			templateUrl : 'view/center/order.html',
			controller : function($scope,$rootScope){
				$rootScope.title = title + "我的订单";
				$scope.orderNum = 10;
			}
		}).state('order.all',{
			url : '/all',
			views : {
				'viewOrder' : {
					templateUrl : 'view/center/order/list.html',
					controller : function($scope,$rootScope){}
				}
			}
		}).state('order.pay',{
			url : '/pay',
			views : {
				'viewOrder' : {
					templateUrl : 'view/center/order/list.html',
					controller : function($scope,$rootScope){}
				}
			}
		}).state('order.delivery',{
			url : '/delivery',
			views : {
				'viewOrder' : {
					templateUrl : 'view/center/order/list.html',
					controller : function($scope,$rootScope){}
				}
			}
		}).state('order.goods',{
			url : '/goods',
			views : {
				'viewOrder' : {
					templateUrl : 'view/center/order/list.html',
					controller : function($scope,$rootScope){}
				}
			}
		}).state('order.commit',{
			url : '/commit',
			views : {
				'viewOrder' : {
					templateUrl : 'view/center/order/list.html',
					controller : function($scope,$rootScope){}
				}
			}
		})
		// ==================================== 订单详情
		.state('detail',{
			url : '/detail',
			templateUrl : 'view/center/detail.html',
			controller : function($scope,$rootScope){
				$rootScope.title = title + "订单详情";
			}
		})
		// ==================================== 查看物流
		.state('logistics',{
			url : '/logistics',
			templateUrl : 'view/center/logistics.html',
			controller : function($scope,$rootScope){
				$rootScope.title = title + "查看物流";
			}
		})
		// ==================================== 推荐提成
		.state('commission',{
			templateUrl : 'view/common/header-public.html'
		}).state('commission.commission',{
			url : '/commission',
			templateUrl : 'view/center/commission.html',
			controller : function($scope,$rootScope){
				$rootScope.title = title + "推荐提成";
			}
		}).state('commission.withdrawal',{
			url : '/withdrawal',
			templateUrl : 'view/center/commission/withdrawal.html',
			controller : function($scope,$rootScope){
				$rootScope.title = title + "提现";
			}
		}).state('commission.record',{
			url : '/record',
			templateUrl : 'view/center/commission/record.html',
			controller : function($scope,$rootScope){
				$rootScope.title = title + "提现记录";
			}
		})
		// ==================================== 我的兑换
		.state('exchange',{
			url : '/exchange',
			templateUrl : 'view/center/exchange.html',
			controller : function($scope,$rootScope){
				$rootScope.title = title + "我的兑换";
			}
		})
		// ==================================== 评价
		.state('commitList',{
			url : '/commitList',
			templateUrl : 'view/center/commit-list.html',
			controller : function($scope,$rootScope,$ajax){
				$rootScope.title = title + "我的评价";
				$ajax.query(API.myComments,{type:1},function(data){
					$scope.data = data;
					console.log(data)
				})
			}
		}).state('commit',{
			url : '/commit',
			templateUrl : 'view/center/commit.html',
			controller : function($scope,$rootScope){
				$rootScope.title = title + "评价";
			}
		}).state('commitPic',{
			url : '/commitPic',
			templateUrl : 'view/center/commit-pic.html',
			controller : function($scope,$rootScope){
				$rootScope.title = title + "图片管理";
			}
		})
		// ==================================== 收货地址
		.state('addressList',{
			url : '/address',
			templateUrl : 'view/center/address.html',
			controller : function($scope,$rootScope,$ajax){
				$rootScope.title = title + "收货地址";
				
				var $def = angular.element(document.querySelectorAll('.set-def'));
				$def.on('touchstart mousedown',function(){
					var $self = this;
					angular.forEach($def,function(data,index){
						var $this = $def.eq(index);
						if($this.attr('rel') === $self.getAttribute('rel')){
							$this.addClass('current');
						}else{
							$this.removeClass('current');
						}
					})
				})
				
				angular.forEach($def,function(data,index){
					 $def.eq(index).attr('rel',index)
				})
				
				$ajax.query(API.myAddress,{},function(data){
					$scope.data = data;
				})
				
				$scope.del = function(index,id){
					$scope.pop = true;
					$scope.yesText = '你确定要删除此条收货地址？';
					$scope.yes = function(){
						$ajax.post(API.delAddress,{id:id},function(data){
							if(data.code === 0){
								$scope.data.splice(index,1);
								$scope.promt('删除成功!');
								if($scope.data.length === 0){
									$scope.noData = true;
								}
							}else{
								$scope.promt('删除失败!');
							}
							$scope.pop = false;
						})
					}
				}
				
				$scope.no = function(){
					$scope.pop = false;
				}
			}
		}).state('address',{
			templateUrl : 'view/common/header-public.html'
		}).state('address.editor',{
			url : '/editor',
			templateUrl : 'view/center/address/add.html',
			controller : function($scope,$rootScope,$stateParams,$ajax){
				$rootScope.title = title + "编辑收货地址";
				$scope.submitForm = function(valid){
					$scope.isSubmit = true;
					$scope.promt('正在修改中，请稍候!');
					if(valid){	// 验证成功
						var data = {
									   'id' : $stateParams.id,
							    'consignee' : $scope.name,
							        'phone' : $scope.phone,
							  'dict_one_id' : $scope.sheng,
				 			'dict_three_id' : $scope.shi,
				 			'dict_three_id' : $scope.qu,
							   'is_default' : $scope.check
						}
						$ajax.post(API.addAddress,data,function(data){
							var code = data.code;
							if(code === 0){
								$scope.promt('修改地址成功!');
								$timeout(function(){
									$state.go('addressList');
								},1500)
							}else{
								$scope.promt('修改失败!');
								$scope.isSubmit = false;
							}
						})
					}else{	// 验证失败
						$scope.promt('信息填写有误，请检查!');
					}
				}
			}
		}).state('address.add',{
			url : '/add',
			templateUrl : 'view/center/address/add.html',
			controller : function($scope,$rootScope,$ajax){
				$rootScope.title = title + "添加收货地址";
				$scope.submitForm = function(valid){
					$scope.isSubmit = true;
					$scope.promt('正在保存中，请稍候!');
					if(valid){	// 验证成功
						var data = {
							    'consignee' : $scope.name,
							        'phone' : $scope.phone,
							  'dict_one_id' : $scope.sheng,
				 			'dict_three_id' : $scope.shi,
				 			'dict_three_id' : $scope.qu,
							   'is_default' : $scope.check
						}
						$ajax.post(API.addAddress,data,function(data){
							var code = data.code;
							if(code === 0){
								$scope.promt('保存地址成功!');
								$timeout(function(){
									$state.go('addressList');
								},1500)
							}else{
								$scope.promt('保存失败!');
								$scope.isSubmit = false;
							}
						})
					}else{	// 验证失败
						$scope.promt('信息填写有误，请检查!');
					}
				}
			}
		})
		// ==================================== 账号与安全
		.state('account',{
			templateUrl : 'view/common/header-public.html'
		}).state('account.account',{
			url : '/account',
			templateUrl : 'view/center/account/account-security.html',
			controller : function($scope,$rootScope,$ajax){
				$rootScope.title = title + "账号与安全";
				$ajax.query(API.userInfo,{},function(data){
					$scope.baseInfo = data.baseinfo;
				})
			}
		}).state('account.baseInfo',{	// 基本信息
			url : '/baseInfo',
			templateUrl : 'view/center/account/baseInfo.html',
			controller : function($scope,$rootScope,$ajax){
				$rootScope.title = title + "基本信息";
				$ajax.query(API.userInfo,{},function(data){
					$scope.baseInfo = data.baseinfo;
				})
			}
		}).state('account.modTel',{	// 修改手机号
			url : '/modTel',
			templateUrl : 'view/center/account/modTel.html',
			controller : function($scope,$rootScope,$timeout,$state,$ajax){
				$rootScope.title = title + "修改手机号";
				$scope.submitForm = function(valid){
					$scope.isSubmit = true;
					$scope.promt('正在修改中，请稍候!');
					if(valid){	// 验证成功
						var data = {
							'phone' : $scope.username
						}
						$ajax.post(API.modifyPhone,data,function(data){
							var code = data.code;
							if(code === 0){
								$scope.promt('恭喜修改手机成功!');
								
								$timeout(function(){
									$state.go('account.baseInfo');
								},2000)
							}else{
								$scope.isSubmit = false;
								$scope.promt('修改手机失败!');
							}
						})
					}else{	// 验证失败
						$scope.promt('信息填写有误，请检查!');
					}
				}
			}
		}).state('account.modPwd',{	// 修改登陆密码
			url : '/modPwd',
			templateUrl : 'view/center/account/modPwd.html',
			controller : function($scope,$rootScope,$timeout,$state,$ajax){
				$rootScope.title = title + "修改登陆密码";
				
				$scope.submitForm = function(valid){
					$scope.isSubmit = true;
					$scope.promt('正在修改中，请稍候!');
					if(valid){	// 验证成功
						var data = {
							'old_pwd' : $scope.oldPwd,
							'new_pwd' : $scope.newPwd,
							'con_pwd' : $scope.conPwd
						}
						$ajax.post(API.updatePwd,data,function(data){
							var code = data.code;
							if(code === 0){
								$scope.promt('恭喜修改密码成功!');
								
								$timeout(function(){
									$state.go('account.confirm');
								},2000)
							}else{
								$scope.isSubmit = false;
								$scope.promt('修改密码失败!');
							}
						})
					}else{	// 验证失败
						$scope.promt('信息填写有误，请检查!');
					}
				}
			}
		})
		// ==================================== 确认修改密码
		.state('account.confirm',{
			url : '/confirm',
			templateUrl : 'view/center/confirm.html',
			controller :function($scope,$rootScope){
				$rootScope.title = title + '确认修改';
			}
		})
		// ==================================== 服务中心
		.state('service',{	// 服务中心
			url : '/service',
			templateUrl : 'view/center/service-center.html',
			controller : function($scope,$rootScope){
				$rootScope.title = title + "服务中心";
			}
		})
		// ==================================== 我的优惠券
		.state('pcoupons',{
			url : '/pcoupons',
			templateUrl : 'view/center/pcoupons.html',
			controller : function($scope,$rootScope){
				$rootScope.title = title + "我的优惠券";
			}
		})
		// ==================================== 商品收藏
		.state('goodsCollection',{
			url : '/goodsCollection',
			templateUrl : 'view/center/collection-goods.html',
			controller : function($scope,$rootScope){
				$rootScope.title = title + "商品收藏";
			}
		})
		// ==================================== 店铺收藏
		.state('storeCollection',{
			url : '/storeCollection',
			templateUrl : 'view/center/collection-store.html',
			controller : function($scope,$rootScope){
				$rootScope.title = title + "店铺收藏";
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
	});
	return angularAMD.bootstrap(app);	// angularAMD启动 angular
})