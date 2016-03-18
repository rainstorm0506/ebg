// ============================================================ 提示
define(['angularAMD'],function(angularAMD){
	'use strict';
	angularAMD.directive('promt',function($sessionStorage,$timeout,$rootScope){
		return {
			restrict : 'E',
			replace : true,
			template : '<box>\
							<p class="promt" ng-if="!isLogin" ng-class="{current:flag}"><span ng-bind="promtText"></span></p>\
							<div class="loading-wrap" ng-if="loading"><p><i></i><span ng-bing="loadingTxt"></span></p></div>\
						</box>',
			link : function(scope,elem,attr){
				$rootScope.loading = false;

				$rootScope.promt = function(txt){
					scope.promtText = txt;
					scope.flag = true;
					$timeout(function(){
						scope.flag = false;
					},1500);
				}
			}
		}
	})
})