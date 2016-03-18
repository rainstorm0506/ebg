define(['angularAMD'],function(angularAMD){
	'use strict';
	angularAMD.directive('selectTime',function(){
		return{
			restrict : 'A',
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
				
				angular.element(document).ready(function(){
					var id = attr.selectTime;
					var $btn = angular.element(document.querySelector('#'+ id));
					var $year = angular.element(document.querySelector('#year'));
					var $month = angular.element(document.querySelector('#month'));
					var $day = angular.element(document.querySelector('#day'));
					var stack = []
					$btn.on('touchstart mousedown',function(){
						var $li = $year.find('li');
						var year = parseInt(getTime($year));
						var month = parseInt(getTime($month));
						var day = parseInt(getTime($day));
						scope[id] = year + '-' + month + '-' + day;

						scope.isTime = false;
						if(!year || !month || !day){
							addPromt('您还未选择');
							return false;
						}
					})
					
					function getTime(elem){
						var $li = elem.find('li');
						var val = null;
						scope.$apply(function(){
							angular.forEach($li,function(data,index){
								var $this = $li.eq(index);
								if($this.hasClass('current')){
									val = $this.text()
									return;
								}
							});
						})
						
						return val;
					}
				})
			}
		}
	})
	// ============================== 选择人数与类型
	angularAMD.directive('comfirmTo',function(){
		return function(scope,elem,attr){
			// 确认结果
			var id = attr.comfirmTo;
			var $target = angular.element(document.querySelector('#' + id));
			elem.on('touchstart mousedown',function(){
				var $this = angular.element(this);
				var $child = $this.parent().find('ul').children();
				angular.forEach($child,function(data,index){
					var $this = $child.eq(index);
					if($this.hasClass('current')){
						scope.$apply(function(){
							scope[id] = $this.find('h6').text();
						})
						scope.isPerson = false;
						scope.isType = false;
					}
				});
				if(!scope[id]){
					addPromt('您还未选择');
				}
			})
		}
	});
	// ============================== 选择地址省市
	var $cityBtn = angular.element(document.querySelector('#cityBtn'));
	angularAMD.directive('swipeSelect',function(){
		return{
			restrict : 'A',
			link : function(scope,elem,attr){
				angular.element(document).ready(function(){
					swipeSelect(scope,elem,attr)
				})
			}
		}
	}).directive('selectAddress',function(){
		return{
			link : function(scope,elem,attr){
				provCity(elem,attr,scope);
			}
		}
	}).directive('city',function($timeout){
		return{
			restrict : 'E',
			replace : true,
			template : '<div class="select-wrap select-wrap-scroll" id="cityWrap" select-address="cityV" ng-swipe-up ng-swipe-down><dl><dd></dd><dd></dd><dd></dd></dl></div>',
			link : function(scope,elem,attr){
				scope.selectCity = function(){
					scope.isCity = true;
					if($cityBtn.attr('flag')){return;}
					var prov = scope.provinces;
					var jPov = scope.provinceV;
					var $scroll = elem.find('div');
					elem.unbind('swipeup');
					elem.unbind('swipedown');
					if(jPov === '请选择省'){
						addPromt('请选择省');
						scope.isCity = false;
						return false;
					}
					if($scroll !== []){
						$scroll.children().eq(1).remove();
						$scroll.css('top',0 + 'px').attr('site',0);
					}
					
					$cityBtn.attr('flag',1);
					angular.forEach(prov,function(data,index){
						if(prov[index].name === scope.provinceV){
							var city = prov[index].city;
							var str = "";
							
							angular.forEach(city,function(data,index){
								str += '<li>'+ data.name +'</li>';
							})
							elem.prepend('<ul>' + str + '</ul>');
							if(city.length === 1){
								elem.find('li').addClass('current');
							}
							swipeSelect(scope,elem,attr);
						}
					});
				}
			}
		}
	})
	// =========================================== 确定选择省份或市
	function provCity(elem,attr,scope){
		angular.element(document).ready(function(){
			var id = attr.selectAddress;
			var $btn = angular.element(document.querySelector('#'+ id));
			$btn.on('touchstart mousedown',function(){
				var $li = elem.find('li');
				scope.$apply(function(){
					angular.forEach($li,function(data,index){
						var $this = $li.eq(index);
						if($this.hasClass('current')){
							scope[id] = $this.text();
							if(id === "provinceV"){
								var city = ['北京','天津','上海','重庆','香港','澳门','台湾']
								scope.isProv = false;
								scope.cityV = '请选择市';
								$cityBtn.removeAttr('flag');
								var $city = angular.element(document.querySelector('#'+attr.targetCity));
								$city.find('div').remove();
								$city.find('ul').remove();
								angular.forEach(city,function(data,index){
									if(scope[id] === data){
										scope.cityV = data;
									}
								})
							}else{
								scope.isCity = false;
							}
							return;
						}
					});
					if(id === "provinceV"){
						if(scope[id] === '请选择省'){
							addPromt('您还未选择省份');
						}
					}else{
						if(scope[id] === '请选择市'){
							addPromt('您还未选择市');
						}
					}
				})
			})
		})
	}

	return ['$scope','$rootScope','$http',function($scope,$rootScope,$http){
		$rootScope.title = title + "企业用户信息填写";
		$http.get('public/js/data/area.json').success(function(response){
			$scope.provinces = response;
		});
	}]
})

