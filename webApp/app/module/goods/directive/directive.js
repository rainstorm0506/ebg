﻿// ====================================== 重置搜索
define(['angularAMD'],function(angularAMD){
	angularAMD.directive('searchReset',function(){
		return {
			restrict : 'A',
			link : function(scope,elem,attr){
				var $reset = angular.element(document.querySelectorAll('.sc-nav'));
				elem.on('touchstart mousedown',function(){
					$reset.children().removeClass('current');
					angular.forEach($reset,function(data,index){
						$reset.eq(index).children().eq(0).addClass('current');
						$reset.find('input').val('')
					})
				})
			}
		}
	});
	
	// ====================================== 加入收藏
	angularAMD.directive('collCart',function($ajax,$stateParams){
		return {
			restrict : 'E',
			replace : true,
			template : '<nav>' +
							'<a href="javascript:;" ng-click="collection()" ng-class="{current:hasCollection}"><i class="ico-4"></i></a>' +
							'<a ui-sref="cart"><i class="ico-3"></i><b ng-bind="addTotal" ng-if="addTotal > 0"></b></a>' +
						'</nav>',
			link : function(scope,elem,attr){
				var id = $stateParams.id ? $stateParams.id : scope.param();
				scope.collection = function(){
					$ajax.post(API.goodsInfo,{id:id,type:scope.cType},function(data){
						if(data.code == 0){
							scope.promt('恭喜商品收藏成功');
						}else{
							scope.promt('对不起，收藏商品失败');
						}
					})
				}
			}
		}
	});
	// ====================================== 购买及加入购物车
	angularAMD.directive('buy',function(){
		return {
			restrict : 'E',
			replace : false,
			templateUrl : 'view/goods/common/buy.html',
			controller : function($scope,$ajax,$stateParams,$state,$timeout,$localStorage,$rootScope){
				var id = $stateParams.id ? $stateParams.id : $scope.param();
				$scope.carNum = function(){
					$ajax.posts(API.cartNum,{apt:E.time()},function(data){
						$scope.addTotal = data.data.number;
					})
				}
				$scope.addToCart = function(){
					if($scope.stock != -999){
						if($scope.stock <= 0){
							$scope.promt('库存为0，不能加入购物车');
							return;
						}
					}
					
					var data = {
						gid:id,
						type:$scope.cType,
						amount:$scope.calculate.cNumber
					}
					
					$ajax.post(API.cartJoin,data,function(data){
						if(data.code == 0){
							$scope.promt('恭喜加入购物车成功');
							$scope.carNum();
							$timeout(function(){
								$scope.isBuy = false;
							},1500);
						}else{
							$scope.promt('对不起，加入购物车失败');
						}
					})
				}
				$scope.rightBuy = function(){
					if($scope.stock != -999){
						if($scope.stock <= 0){
							$scope.promt('库存为0，不能购买');
							return;
						}
					}
					//$scope.promt('恭喜购买成功');
					var param = {
									   gid : $scope.data.id,
						 			  type : $scope.bType,
						            amount : $scope.calculate.cNumber,
						attrs_1_unite_code : 0,
						attrs_2_unite_code : 0,
						attrs_3_unite_code : 0
					}
					
					$ajax.post(API.promptly,param,function(data){
						if(data.code === 0){
							$timeout(function(){
								$scope.go('orderConfirm');
							})
						}
					})
				}
				// ====================== 两两对比，产生价格
				$scope.model = {
					size : {}
				}
				
				$scope.finishSize = function(){
					// 设置默认值
					var attrs = $scope.attrs;
					var i = 0;
					angular.forEach(attrs,function(data,key){
						angular.forEach(data,function(v,k){
							if(!$scope.model.size[i]){
								$scope.model.size[i] = k;
							}
							return false;
						});
						i++;
					});
					// 监听变化，并设置不同配合
					$scope.$watch('model.size',function(newval,oldval){
						var size = $scope.model.size;
						console.log(size)
						if(!size) return;
						if($scope.attrsLength === 2){
							$scope.price = $scope.attrVal.price[size[0]][size[1]];
							$scope.stock = $scope.attrVal.price[size[0]][size[1]];
						}else if($scope.attrsLength === 3){
							$scope.price = $scope.attrVal.price[size[0]][size[1]][size[2]];
							$scope.stock = $scope.attrVal.price[size[0]][size[1]][size[2]];
						}
						
					},true)
				}
			}
		}
	});
	// ================================================== 购物车列表单选/全选操作
	angularAMD.directive('shoppingList',function($rootScope){
		return {
			link : function(scope,elem,attr){
				scope.isCartEditor = {}
				// 编辑局部
				scope.cartEditor = function(key){
					scope.isCartEditor[key] = true;
					$rootScope.isEditor = true;
					//$rootScope.model.all = false; // 清空所有选择
					$rootScope.escModel();
				}
				scope.editorComplete = function(key){
					scope.isCartEditor[key] = false;
					$rootScope.isEditor = false;
					$rootScope.escModel();
				}
				// 编辑全部
				$rootScope.editorAll = function(){
					$rootScope.isEditorAll = true;
					$rootScope.isCartEditorAll = true;
					//$rootScope.model.all = false;
					$rootScope.escModel();
				}
				$rootScope.editorAllComplete = function(){
					$rootScope.isCartEditorAll = false;
					$rootScope.isEditorAll = false;
					//$rootScope.model.all = false;
					$rootScope.escModel();
					$rootScope.isEditor = false;
					angular.forEach(scope.isCartEditor,function(data,key){
						scope.isCartEditor[key] = false;
					});
				}
			},
			controller : function($scope,$rootScope,$timeout,ePublic){
				$rootScope.model = {
					all : {}
				}
				$scope.model = {
					store :{},
					goods : {}
				}
				$rootScope.checkboxModel = $scope.model;
				var store = $scope.model.store;
				var goods = $scope.model.goods;
				// 全选局部
				$scope.storeChange = function(key){
					var store = $scope.model.store[key];
					var goods = $scope.model.goods[key];
					angular.forEach(goods,function(data,key){
						goods[key] = store;
					});
					judgeAll()
				}
				// 单选判断是否全选
				$scope.goodsChange = function(key){
					var index = 0;
					var goods = $scope.model.goods[key];
					var len = ePublic.dataSize(goods)
					angular.forEach(goods,function(data,key){
						if(data){
							index ++;
						}
					})
					$scope.model.store[key] = index === len ? true : false;
					judgeAll();
				}
				
				var judgeAll = function(){
					var store = $scope.model.store;
					var len = ePublic.dataSize(store);
					var index = 0;
					angular.forEach(store,function(data,key){
						if(data){
							index ++;
						}
					});
					$rootScope.model.all = index === len  ? true : false;
				}
				// 全选所有
				$rootScope.selectAll = function(){
					var all = $rootScope.model.all
					angular.forEach(store,function(data,key){
						store[key] = all;
						angular.forEach(goods[key],function(data,index){
							goods[key][index] = all;
						})
					})
				}
				
				// 取消所有
				
				$rootScope.escModel = function(){
					$rootScope.model.all = false;
					angular.forEach(store,function(data,key){
						store[key] = false;
						angular.forEach(goods[key],function(data,index){
							goods[key][index] = false;
						})
					})
				}
				
				
				$rootScope.totalMoney = 0;
				
				
				//var goods = $scope.model.goods;
				$timeout(function(){
					var $money = angular.element(document.querySelectorAll('.money'));
				
					// 监听所有
					$scope.$watch('model.goods',function(){
						var price = 0;
						
						$rootScope.goodsKey = [];
						$money.ready(function(){
							angular.forEach($money,function(data,key){
								var $this = $money.eq(key);
								if($this.prop('checked')){
									price += $this.val()*1;
									// 将所有选择商品 key 加入一个数组
									$rootScope.goodsKey.push($this.attr('key'));
								}
							});
							$rootScope.totalMoney = price;
							$scope.$apply();
						});
					},true)
				},10)
			}
		}
	});
	// ====================================== 计算器
	angularAMD.directive('calculate',function($timeout,$ajax){
		return {
			restrict : 'E',
			replace : true,
			template : '<div class="calculate">\
							<a href="javascript:;" ng-click="reduction()" ng-class="{disabled:calculate.cNumber<=1 || aFlag}">-</a>\
							<input type="number" ng-bind="calculate.cNumber" ng-model="calculate.cNumber" ng-change="numChange()" ng-blur="inputBlur()">\
							<a href="javascript:;" ng-click="add()" ng-class="{disabled:calculate.cNumber>1000 || bFlag}">+</a>\
						</div>',
			link : function(scope,elem,attr){
				scope.add = function(){
					scope.calculate.cNumber++
				}
				scope.reduction = function(){
					scope.calculate.cNumber --
				}
				
				
				scope.$watch('calculate.cNumber',function(newVal,oldVal){
					if(newVal < 1){
						scope.calculate.cNumber = 1;
					}
					if(newVal > 1000){
						scope.calculate.cNumber = 1000;
					}
					if(attr.flag == 'cart'){
						if(newVal !== oldVal && scope.calculate.key !== undefined && newVal >0 && newVal < 1000){
							$timeout(function(){
								var param = {
									   key : scope.calculate.key,
									amount : scope.calculate.cNumber
								}
								console.log(param)
								$ajax.posts(API.changeAmount,param,function(data){
									scope.price = data;
								})
							},500)
						}
					}
				});
			}
		}
	});
	// ====================================== 模拟 radio box
	// ================================================== 过滤器
});