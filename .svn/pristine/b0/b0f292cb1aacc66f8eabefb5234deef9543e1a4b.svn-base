// ====================================== 重置搜索
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
			controller : function($scope,$ajax,$stateParams,$timeout,$localStorage){
				var id = $stateParams.id ? $stateParams.id : $scope.param();
				$scope.addTotal = $localStorage.cartNum ? $localStorage.cartNum : 0;
				$scope.addToCart = function(){
					if($scope.data.stock != -999){
						if($scope.data.stock <= 0 || $scope.addTotal <=0){
							$scope.promt('库存为0，不能加入购物车');
							return;
						}
					}
					var cartId = $localStorage.cartId;
					for(var i in cartId){
						if(cartId[i] === $scope.data.id){
							$scope.promt('该商品，您已经加入购物车');
							return;
						}
					}
					
					var data = {
						gid:id,
						type:$scope.cType,
						amount:$scope.calNum
					}
					
					$ajax.post(API.cartJoin,data,function(data){
						if(data.code == 0){
							$scope.promt('恭喜加入购物车成功');
							$scope.addTotal = data.data.total;
							$localStorage.cartNum = data.data.total;
							var stack = []
							stack.push($scope.data.id);
							$localStorage.cartId = stack;
							$timeout(function(){
								$scope.isBuy = false;
							},1500)
						}else{
							$scope.promt('对不起，加入购物车失败');
						}
					})
				}
				$scope.rightBuy = function(){
					if($scope.data.stock != -999){
						if($scope.data.stock <= 0 || $scope.addTotal <=0){
							$scope.promt('库存为0，不能购买');
							return;
						}
					}
					$scope.promt('恭喜购买成功');
				}
			}
		}
	});
	// ================================================== 购物车列表 dom操作
	angularAMD.directive('shoppingList',function(){
		return function(scope,elem,attr){
			scope.finish = function(){
				scope.cartChange = function(){
					console.log(scope.cartGoods)
				}
				
			}
			scope.cartEditor = function(key){
				scope.isCartEditor = true;
			}
			
		}
	});
	// ================================================== 过滤器
	// =================================== 过滤库存
	angularAMD.filter('filterStock',function(){
		return function(name){
			if(name == -999){
				return '>100';
			}else{
				return name;
			}
		}
	});
	
});