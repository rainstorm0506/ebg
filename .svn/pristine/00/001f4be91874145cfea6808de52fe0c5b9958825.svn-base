define(['angularAMD'],function(angularAMD){
	// ====================================== 集采列表
	angularAMD.directive('jicaiList',function(){
		return {
			restrict : 'E',
			replace : true,
			template : '<ul class="jicai-list">\
							<li ng-repeat="$p in data">\
								<header>\
									<h6 ng-bind="$p.title"></h6>\
									<time>截止时间：<x ng-bind="$p.price_endtime | date:\'yyyy-MM-dd\'"></x></time>\
								</header>\
								<footer ng-switch on="$p.is_closed">\
									<div>办公用品采购单</div>\
									<p ng-switch-when="1" class="on">等待报价</p>\
									<p ng-switch-when="2">正在报价</p>\
									<p ng-switch-when="3">结束报价</p>\
								</footer>\
								<a ui-sref="detail({id:$p.purchase_sn})"></a>\
							</li>\
						</ul>',
			controller : function($scope,$ajax){
				$ajax.query(API.myPurchaseList,{apt:E.time()},function(data){
					$scope.data = data;
				})
			}
		}
	});
});