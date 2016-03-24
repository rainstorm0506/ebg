// ====================================== 导航
define(['angularAMD'],function(angularAMD){
	angularAMD.directive('checkboxs',function(){
		return {
			restrict : 'E',
			replace : true,
			template : '<div class="checbox-wrap" id="{{$p.id}}"><label ng-click="null()"><b><i></i></b><input type="checkbox"></label></div>',
			controller : function($scope,$rootScope,ePublic){
				$rootScope.pid = [];
				
				elem.find('input').on('change',function(){
					var $this = angular.element(this);
					var $i = $this.parent().find('i');
					if($this.prop('checked')){
						$i.css('display','block');
						$rootScope.pid.push(attr.id)
					}else{
						$i.css('display','none');
						ePublic.removeStr($rootScope.pid,attr.id);
					}
					$rootScope.checkSubmit();
				});
			}
		}
	});
	// ====================================== 省份
	angularAMD.directive('province',function(){
		return {
			restrict : 'E',
			replace : true,
			template : '<select ng-model="province" name="province" ng-change="provinceChange()">\
						<option value="">请选择所在省份</option>\
						<option ng-repeat="(key,val) in aData" value="{{key}}" ng-bind="val"></option>\
					</select>',
			controller : function($scope,$rootScope,$timeout,$ajax){
				$scope.checkModel = function(){
					var flag = $scope.province === undefined || 
							   $scope.city === undefined || 
							   $scope.county === undefined || 
							   $scope.province === '' || 
							   $scope.city === '' || 
							   $scope.county === '';
					$scope.isAddress = flag;
				}
				
				$scope.checkModel();
				
				if($scope.add){
					$ajax.query(E.API.dict,{},function(data){
						$scope.aData = data;
					})
				}
				$scope.provinceChange = function(){
					$scope.checkModel();
					$scope.city = '';
					$scope.county = '';
					$scope.bData = [];
					$scope.cData = [];
					
					if($scope.province !== '' && $scope.province !== undefined){
						$ajax.query(E.API.dict,{one:$scope.province},function(data){
							$scope.bData = data
						})
					}else{
						$scope.bData = [];
					}
				}
			}
		}
	});
	// ====================================== 市
	angularAMD.directive('city',function($rootScope,$ajax){
		return {
			restrict : 'E',
			replace : true,
			template : '<select ng-model="city" name="city" ng-change="cityChange()">\
						<option value="">请选择所在城市</option>\
						<option ng-repeat="(key,val) in bData" value="{{key}}" ng-bind="val"></option>\
					</select>',
			controller : function($scope){
				$scope.cityChange = function(){
					$scope.checkModel()
					$scope.county = '';
					$scope.cData = [];
					
					if($scope.city !== '' && $scope.city !== undefined){
						if($scope.province !== '' && $scope.province !== undefined){
							$ajax.query(E.API.dict,{one:$scope.province,two:$scope.city},function(data){
								$scope.cData = data
							})
						}else{
							$scope.county = '';
							$scope.cData = [];
						}
					}else{
						$scope.cData = [];
					}
				}
			}
		}
	});
	// ====================================== 区
	angularAMD.directive('county',function($rootScope,$timeout,$ajax){
		return {
			restrict : 'E',
			replace : true,
			template : '<select ng-model="county" name="county" ng-change="countyChange()">\
						<option value="">请选择所在地区</option>\
						<option ng-repeat="(key,val) in cData" value="{{key}}" ng-bind="val"></option>\
					</select>',
			link : function(scope,elem,attr){
				scope.countyChange = function(){
					scope.checkModel()
					if(scope.city === ''){
						scope.county = '';
					}
				}
			}
		}
	});
	// ================================================== 评分
	angularAMD.directive('score',function(){
		return {
			restrict : 'E',
			replace : true,
			template : '<dl><dd></dd><dd></dd><dd></dd><dd></dd><dd></dd></dl>',
			link : function(scope,elem,attr){
				var $child = elem.children();
				angular.forEach($child,function(data,index){
					$child.eq(index).attr('index',index)
				})
				elem.children().on('tarchstart mousedown',function(){
					var thisIndex = parseInt(angular.element(this).attr('index'));
					angular.forEach($child,function(data,index){
						if(parseInt($child.eq(index).attr('index')) <= thisIndex){
							$child.eq(index).addClass('current');
							scope.score = thisIndex+1;
							scope.validate(scope.content);
						}else{
							$child.eq(index).removeClass('current');
						}
					})
				})
			}
		}
	});
	// ================================================== 取消订单原因
	angularAMD.directive('cancelReason',function(){
		return {
			restrict : 'E',
			replace : true,
			template : '<section ng-class="{animated:cancelReason,fadeIn:cancelReason}" class="pop-wrap cancel-reason-wrap" ng-show="cancelReason">\
							<div ng-class="{animated:cancelReason,fadeInUp:cancelReason}">\
								<ul>\
									<li ng-repeat="$r in reason" repeat-finish="finish()">\
										<label for="{{reason[$index].id}}" class="radio">\
											<i><b></b></i><input id="{{reason[$index].id}}" ng-checked name="reason" type="radio" ng-value="{{reason[$index].id}}" ng-model="radioObj.radioA"><span ng-bind="reason[$index].user_title"></span>\
										</label>\
									</li>\
								</ul>\
								<nav class="wrap-nav">\
									<a class="btn-1-2" href="javascript:;" ng-click="yesReason()">确认</a>\
									<a class="btn-1" href="javascript:;" ng-click="noReason()">取消</a>\
								</nav>\
							</div>\
						</section>',
			link : function(scope,elem,attr){
				scope.finish = function(){
					var $label = elem.find('label');
				 	$label.find('input').on('change',function(){
				 		var $this = angular.element(this).parent();
				 	 		$this.addClass('current');
				 	 		angular.forEach($label,function(data,i){
				 	 			if($this.attr('rel') !== $label.eq(i).attr('rel')){
				 	 				$label.eq(i).removeClass('current');
				 	 			}
				 	 		})
				 	});
				 	angular.forEach($label,function(data,key){
				 		$label.eq(key).attr('rel',key);
				 	})
				}
			},
			controller : function($scope,$ajax,$timeout){
				$scope.radioObj = {}
				//　取消订单
				$scope.orderCancel = function(index,id){
					$scope.cancelReason = true;
					
					!$scope.reason && $ajax.query(API.allReason,{apt:'1314'},function(data){ 
						$scope.reason = data;
					});
					
					$scope.yesReason = function(){
						var sid = $scope.radioObj.radioA;
						if(sid === undefined){
							$scope.promt('亲，你还未选择您退款的原因!必选哦!');
							return;
						}
						var param = {
							'order_sn' : id,
							'cancel_status_id' : sid
						}
						console.log(param)
						$ajax.post(API.delOrder,param,function(mdate){
							if(mdate.code === 0){
								$scope.data.splice(index,1);
								$scope.promt('取消订单成功!');
							}else{
								$scope.promt('取消订单失败!');
							}
							$scope.cancelReason = false;
						})
					}
				}
				$scope.noReason = function(){
					$scope.cancelReason = false;
				}
			}
		}
	});
})