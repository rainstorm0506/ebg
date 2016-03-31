// ============================================================ 提示
define(['angularAMD'],function(angularAMD){
	'use strict';
	// ============================== 滑动选择
	angularAMD.directive('swipeSelect',function(){
		return{
			restrict : 'A',
			link : function(scope,elem,attr){
				angular.element(document).ready(function(){
					swipeSelect(scope,elem,attr);
				})
			}
		}
	})
	// ============================== 时间插件
	angularAMD.directive('time',function($rootScope){
		return {
			restrict : 'E',
			replace : true,
			template : '<section class="shadow-wrap" ng-class="{current:isTime === true}">\
							<div>\
								<header>{{timeTitle}}<a class="ico-close" href="javascript:;"><label class="ng-label"><input type="checkbox" ng-model="isTime"></label></a></header>\
								<div class="select-wrap select-wrap-scroll select-wrap-time" select-time="timeV">\
									<ol>\
										<li swipe-select ng-swipe-up ng-swipe-down id="year">\
											<ul><li ng-repeat="i in year"><span ng-bind="i"></span>年</li></ul>\
											<dl><dd></dd><dd></dd><dd></dd></dl>\
										</li>\
										<li swipe-select ng-swipe-up ng-swipe-down id="month">\
											<ul><li ng-repeat="i in month"><span ng-bind="i"></span>月</li></ul>\
											<dl><dd></dd><dd></dd><dd></dd></dl>\
										</li>\
										<li swipe-select ng-swipe-up ng-swipe-down id="day">\
											<ul><li ng-repeat="i in day"><span ng-bind="i"></span>日</li></ul>\
											<dl><dd></dd><dd></dd><dd></dd></dl>\
										</li>\
									</ol>\
								</div>\
								<a class="btn-1-1" href="javascript:;" ng-click="choiceTime()">确认</a>\
							</div>\
						</section>',
			link : function(scope,elem,attr){
				var year = [];
				var month = [];
				var day = [];
				for(var i=2016;i<=2030;i++){
					year.push(i);
				}
				for(var i=1;i<=12;i++){
					month.push(i);
				}
				for(var i=1;i<=31;i++){
					day.push(i);
				}
				scope.year = year;
				scope.month = month;
				scope.day = day;
				
				scope.timeTitle = attr.header;
				
				angular.element(document).ready(function(){
					var id = attr.selectTime;
					var $btn = angular.element(document.querySelector('#'+ id));
					var $year = angular.element(document.querySelector('#year'));
					var $month = angular.element(document.querySelector('#month'));
					var $day = angular.element(document.querySelector('#day'));
					var stack = []
					
					$rootScope.choiceTime = function(){
						var $li = $year.find('li');
						var year = parseInt(getTime($year));
						var month = parseInt(getTime($month));
						var day = parseInt(getTime($day));
						scope.address.timeV = year + '-' + month + '-' + day;

						scope.isTime = false;
						if(!year || !month || !day){
							$rootScope.promt('您还未选择');
							return false;
						}
					}
					function getTime(elem){
						var $li = elem.find('li');
						var val = null;
						angular.forEach($li,function(data,index){
							var $this = $li.eq(index);
							if($this.hasClass('current')){
								val = $this.text()
								return;
							}
						});
						return val;
					}
				})
			}
		}
	})
})