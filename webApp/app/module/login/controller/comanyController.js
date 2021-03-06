﻿define(['angularAMD'],function(angularAMD){
	'use strict';
	
	// =========================================== 升级企业用户
	return function($scope,$rootScope,$timeout,$localStorage,$state,$ajax,ePublic){
		$rootScope.title = title + "企业用户信息填写";
		
		$scope.province = {}
		$scope.city = {}
		$scope.add　= true;
		
		// 公共方法
		$scope.$on('selectCurrent',function(e,$elem,callback,errorback){
			var stack = [];
			angular.forEach($elem,function(data,index){
				var $this = $elem.eq(index);
				if($this.hasClass('current')){
					callback($this,index);
				}else{
					stack.push(index);
				}
			});
			
			if(!errorback) return;
			
			if(stack.length == $elem.length){
				$timeout(function(){
					errorback();
				},100)
			}
		})
		
		// 选择人数
		$scope.choicePerson = function(){
			var $li = angular.element(document.querySelector('#choicePerson')).children();
			$scope.$emit('selectCurrent',$li,function($this){
				$scope.address.personV = $this.text();
				$scope.isPerson = false;
			},function(){
				$scope.promt('您还未选择人数!');
			})
		}
		// 选择公司类型
		$scope.choiceType = function(){
			var $li = angular.element(document.querySelector('#choiceType')).children();
			
			$scope.$emit('selectCurrent',$li,function($this){
				$scope.address.typeV = $this.text();
				$scope.isType = false;
			},function(){
				$scope.promt('您还未选择公司类型!');
			})
		}
		/*
		$scope.address = {
			name: '',
			address: '',
			province: '',
			city: '',
			county: '',
			personV: '请选择',
			timeV: '请选择',
			typeV: '请选择'
		}*/
		
		delete $localStorage.companyInfo;
		
		$scope.next = function(){
			if(!$scope.check) return false;
			
			if($rootScope.checkInfo($scope.address)){
				$localStorage.companyInfo = $scope.address;
				$scope.go('comanyUpload');
			}
		}
	}
})

