﻿// 注册整个应用程序的各个模块，每个模块中定义各自的路由。
// 定义应用的模块，加载模块。配置拦截机
window.title = 'e办公移动平台——';
window.API = {
	// ========================= 个人中心API
	userToCompany : 'usercenter.userToCompany',		// 获取省市联动
    userToCompany : 'usercenter.userToCompany',		// 会员升级为业用户
         userInfo : 'usercenter.userInfo',			// 基本信息
       myComments : 'usercenter.myComments',		// 我的评价
      setComments : 'usercenter.setComments',		// 提交评价
       addAddress : 'usercenter.addAddress',		// 添加收货地址 
       delAddress : 'usercenter.delAddress',		// 删除收货地址 
      modifyPhone : 'usercenter.modifyPhone',		// 修改手机号码 
        updatePwd : 'usercenter.updatePwd',		    // 修改密码
    
         myOrders : 'usercenter.myOrders',		    // 我的订单列表
      orderDetail : 'usercenter.orderDetail',		// 我的订单详情
         delOrder : 'usercenter.delOrder',		    // 我的订单详情
        allReason : 'usercenter.allReason',			// 取消订单原因
        
          getMyac : 'usercenter.getMyac',		    // 我的优惠券列表
    getMyWithdraw : 'usercenter.getMyWithdraw',		// 我的提现列表
      setWithdraw : 'usercenter.setWithdraw',		// 申请提现
    getStoreLists : 'usercenter.getStoreLists',		// 收藏店铺/商品 列表)
    
}
define(['app/module/center/common'],function(angularAMD){
	'use strict';
	var app = angular.module('ebangong',angular.modules).config(function($stateProvider,$urlRouterProvider){
		
		$stateProvider
		// ==================================== 我的个人中心
		.state('/',{
			url : '/',
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
			controller : function($scope,$rootScope,$ajax){
				$rootScope.title = title + "我的订单";
				$scope.$on('order',function(e,newVal){
					$ajax.query(API.myOrders,{type:newVal},function(data){
						$rootScope.data = data;
					});
				})
				
			}
		}).state('order.all',{	// 全部
			url : '/all',
			views : {
				'viewOrder' : {
					templateUrl : 'view/center/order/list.html',
					controller : function($scope,$rootScope,$ajax){
						$scope.$emit('order','0');
					}
				}
			}
		}).state('order.pay',{	// 待付款 101
			url : '/pay',
			views : {
				'viewOrder' : {
					templateUrl : 'view/center/order/list.html',
					controller : function($scope,$rootScope){
						$scope.$emit('order','101');
					}
				}
			}
		}).state('order.delivery',{	// 待发货 103
			url : '/delivery',
			views : {
				'viewOrder' : {
					templateUrl : 'view/center/order/list.html',
					controller : function($scope,$rootScope){
						$scope.$emit('order','103');
					}
				}
			}
		}).state('order.goods',{	// 待收货货 106
			url : '/goods',
			views : {
				'viewOrder' : {
					templateUrl : 'view/center/order/list.html',
					controller : function($scope,$rootScope){
						$scope.$emit('order','106');
					}
				}
			}
		}).state('order.commit',{ // 待评论/交易完成 107
			url : '/commit',
			views : {
				'viewOrder' : {
					templateUrl : 'view/center/order/list.html',
					controller : function($scope,$rootScope){
						$scope.$emit('order','107');
					}
				}
			}
		})
		// ==================================== 订单详情
		.state('orderDetail',{
			url : '/detail/:id',
			templateUrl : 'view/center/detail.html',
			controller : function($scope,$rootScope,$state,$stateParams,$ajax){
				$rootScope.title = title + "订单详情";
				var num = $stateParams.id;
				$ajax.post(API.orderDetail,{'order_sn' : num},function(data){
					$scope.data = data.data[0];
					$scope.data.orderNumber = num;
				})
			}
		})
		// ==================================== 查看物流
		.state('logistics',{
			url : '/logistics/:id',
			templateUrl : 'view/center/logistics.html',
			controller : function($scope,$rootScope,$stateParams){
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
			controller : function($scope,$rootScope,$ajax){
				$rootScope.title = title + "提现";
				$scope.submitForm = function(valid){
					$rootScope.isSubmit = true;
					$scope.promt('正在提现中，请稍候!');
					if(valid){	// 验证成功
						var data = {
							'account' : $scope.account,
							   'bank' : $scope.bank,
							 'amount' : $scope.amount
						}
						$ajax.post(API.setWithdraw,data,function(data){
							var code = data.code;
							if(code === 0){
								$scope.promt('恭喜提现成功!');
								$timeout(function(){
									//$state.go('addressList');
									$scope.go('record');
								},1500)
							}else{
								$scope.promt('提现失败!');
								$rootScope.isSubmit = false;
							}
						})
					}else{	// 验证失败
						$scope.promt('信息填写有误，请检查!');
					}
				}
			}
		}).state('commission.record',{
			url : '/record',
			templateUrl : 'view/center/commission/record.html',
			controller : function($scope,$rootScope,$ajax){
				$rootScope.title = title + "提现记录";
				$ajax.query(API.getMyWithdraw,{},function(data){
					$scope.data = data;
				})
			}
		})
		// ==================================== 评价
		.state('commitList',{
			url : '/commitList',
			templateUrl : 'view/center/commit-list.html',
			controller : function($scope,$rootScope,$ajax){
				$rootScope.title = title + "我的评价";
				$ajax.query(API.myComments,{},function(data){
					$scope.data = data;
				})
			}
		}).state('commit',{
			url : '/commit/:id',
			templateUrl : 'view/center/commit.html',
			controller : function($scope,$rootScope,$timeout,$state,$stateParams,$ajax){
				$rootScope.title = title + "评价";
				var num = $stateParams.id;
				
				$scope.validation = true;
				
				$scope.validate = function(newVal){
					$scope.validation = newVal =='' || newVal == undefined || $scope.score == undefined ? true : false;
				}
				
				$scope.$watch('content',function(newVal,oldVal){
					$scope.validate(newVal);
				})
				
				$scope.checkScore = function(){
					if($scope.score == undefined){
						$scope.promt('不要忘记评分哦!');
					}
				}
				
				$ajax.post(API.orderDetail,{'order_sn' : num},function(data){
					$scope.data = data.data[0];
					$scope.subCommint = function(valid){
						$rootScope.isSubmit = true;
						console.log($scope.score)
						$scope.promt('正在提交评论，请稍候!');
						
						if(!$scope.contentNull){	// 验证成功
							var param = {
								   'order_sn' : num,
								   'goods_id' : $scope.data.goods_id,
								    'content' : $scope.content,
								'goods_score' : $scope.score,
								        'src' : $scope.isUpload,
							}
							console.log(param)
							$ajax.post(API.setComments,param,function(data){
								var code = data.code;
								if(code === 0){
									$scope.promt('提交评论成功!');
									$timeout(function(){
										//$state.go('commitList');
										$scope.go('commitList');
									},1500)
								}else{
									$scope.promt('提交评论失败!');
									$rootScope.isSubmit = false;
								}
							})
						}else{	// 验证失败
							$scope.promt('信息填写有误，请检查!');
						}
					}
				})
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
				/*
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
				*/
				$ajax.query(E.API.myAddress,{},function(data){
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
			}
		}).state('address',{
			templateUrl : 'view/common/header-public.html'
		}).state('address.editor',{
			url : '/editor/:id',
			templateUrl : 'view/center/address/add.html',
			controller : function($scope,$rootScope,$stateParams,$state,$timeout,$ajax,ePublic){
				$rootScope.title = title + "编辑收货地址";
				// 绑定数据
				$ajax.query(E.API.myAddress,{id : $stateParams.id},function(data){
					var data = data[0];
					$ajax.query(E.API.dictShow,{one:data['dict_one_id'],two:data['dict_two_id'],three:data['dict_three_id']},function(dict){
						var check = data['is_default'] === '1' ? true : false;
						
						$scope.aData = dict['province'];
						$scope.bData = dict['city'];
						$scope.cData = dict['county'];
						
						$scope.address = {
							name: data.consignee,
							phone: parseInt(data.phone),
							address: data.address,
							check: check,
							province: data['dict_one_id'],
							city: data['dict_two_id'],
							county : data['dict_three_id']
						};
						
						var obj = {
							name: data.consignee,
							phone: data.phone,
							address: data.address,
							check: check,
							province: data['dict_one_id'],
							city: data['dict_two_id'],
							county : data['dict_three_id']
						}
						
						ePublic.checkChange($scope,'address',obj);	// 检查是否有修改
					});
				});
				
				$scope.submitForm = function(valid){
					$rootScope.isSubmit = true;
					$scope.promt('正在修改中，请稍候!');
					if(valid){	// 验证成功
						var data = {
									     'id' : $stateParams.id,
								  'consignee' : $scope.address.name,
							          'phone' : $scope.address.phone,
							    'dict_one_id' : $scope.address.province,
				 			    'dict_two_id' : $scope.address.city,
				 			  'dict_three_id' : $scope.address.county,
				 			  	    'address' : $scope.address.address,
							     'is_default' : ($scope.address.check ? 1 : 0)
						}
						
						$ajax.post(API.addAddress,data,function(data){
							var code = data.code;
							if(code === 0){
								$scope.promt('修改地址成功!');
								$timeout(function(){
									//$state.go('addressList');
									$scope.go('address');
								},1500)
							}else{
								$scope.promt('修改失败!');
								$rootScope.isSubmit = false;
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
			controller : function($scope,$rootScope,$timeout,$state,$localStorage,$ajax){
				$rootScope.title = title + "添加收货地址";
				$scope.isSubmit = false;
				$scope.add = true;
				$scope.address = {};
				
				if($localStorage.preveAddress){	// 从确认订单来
					$scope.mustDefault = true;
				}
				$scope.submitForm = function(valid){
					$rootScope.isSubmit = true;
					$scope.promt('正在保存中，请稍候!');
					if(valid){	// 验证成功
						var data = {
								  'consignee' : $scope.address.name,
							          'phone' : $scope.address.phone,
							    'dict_one_id' : $scope.address.province,
				 			    'dict_two_id' : $scope.address.city,
				 			  'dict_three_id' : $scope.address.county,
				 			  	    'address' : $scope.address.address,
							     'is_default' : ($scope.address.check ? 1 : 0)
						}
						
						if($localStorage.preveAddress){	// 从确认订单来
							data.is_default = 1;
						}
						
						$ajax.post(API.addAddress,data,function(data){
							var code = data.code;
							if(code === 0){
								$scope.promt('保存地址成功!');
								$timeout(function(){
									//$state.go('addressList');
									if($localStorage.preveAddress){
										$scope.replace('index.html#/orderConfirm');
										delete $localStorage.preveAddress; // 清除订单无地址记录存储
									}else{
										$scope.go('address');
									}
								},1500)
							}else{
								$scope.promt('保存失败!');
								$rootScope.isSubmit = false;
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
				$rootScope.modTel = true;
				$scope.submitForm = function(valid){
					$rootScope.isSubmit = true;
					$scope.promt('正在修改中，请稍候!');
					if(valid){	// 验证成功
						var data = {
							     phone : $scope.tel,
							    vxCode : $scope.telImgCode,
							      code : $scope.telCode
						}
						$ajax.post(API.modifyPhone,data,function(data){
							var code = data.code;
							if(code === 0){
								$scope.promt('恭喜修改手机成功!');
								
								$timeout(function(){
									//$state.go('/');
									$scope.go();
								},2000)
							}else{
								$rootScope.isSubmit = false;
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
					$rootScope.isSubmit = true;
					$scope.promt('正在修改中，请稍候!');
					if(valid){	// 验证成功
						var data = {
							'old_pwd' : $scope.oldPwd,
							'new_pwd' : $scope.apassword,
							'con_pwd' : $scope.bpassword
						}
						if($scope.oldPwd === $scope.apassword){
							$scope.promt('新密码与旧密码不能一样!');
							$rootScope.isSubmit = false;
							return false;
						}
						$ajax.post(API.updatePwd,data,function(data){
							var code = data.code;
							if(code === 0){
								$scope.promt('恭喜修改密码成功!');
								
								$timeout(function(){
									//$state.go('account.confirm');
									$scope.go('confirm');
								},2000)
							}else{
								$rootScope.isSubmit = false;
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
			controller : function($scope,$rootScope,$ajax){
				$rootScope.title = title + "我的优惠券";
				$ajax.query(API.getMyac,{},function(data){
					$scope.data = data;
				})
			}
		})
		// ==================================== 商品收藏
		.state('goodsCollection',{
			url : '/goodsCollection',
			templateUrl : 'view/center/collection-goods.html',
			controller : function($scope,$rootScope,$ajax){
				$rootScope.title = title + "商品收藏";
				$scope.editorTxt = '编辑';
				$ajax.query(API.getStoreLists,{type:1},function(data){
					$rootScope.data = data;
				});
				
				$rootScope.checkSubmit = function(){
					if($rootScope.pid.length === 0){
						$rootScope.disSubmit = true;
						$rootScope.promt('您至少需要选择一项!');
					}else{
						$rootScope.disSubmit = false;
					}
				}
		
				$scope.editor = function(){
					
					if($scope.isEditor){
						$scope.checkSubmit();
					}else{
						$scope.isEditor = true;
						$scope.editorTxt = '删除';
					}
				}
			}
		})
		// ==================================== 店铺收藏
		.state('storeCollection',{
			url : '/storeCollection',
			templateUrl : 'view/center/collection-store.html',
			controller : function($scope,$rootScope,$ajax){
				$rootScope.title = title + "店铺收藏";
				$ajax.query(API.getStoreLists,{type:2},function(data){
					$scope.data = data;
				})
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
		$urlRouterProvider.otherwise('/');
		
	}).config(function($httpProvider,publicProvider){
		publicProvider.$get($httpProvider)
	}).run(function(ePublic){
		ePublic.run();
	});
	return angularAMD.bootstrap(app);	// angularAMD启动 angular
})