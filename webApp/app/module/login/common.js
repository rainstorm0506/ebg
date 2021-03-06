﻿// 依赖加载模板集合
define([
	// =============================== 库模块
	'angularAMD',
	'angular',
	'angularRoute',
	'angularAnimate',
	'angularSwipe',
	'ngStorage',
	// =============================== 自定义私有指令模块
	
	// =============================== 自定义公共指令模块
	'app/module/common/directive/publicDirective',
	'app/module/common/directive/timeDirective',
	// =============================== dome 指令模块
	'app/dom/directive/selectOnly',
	'app/dom/directive/sendCode',
	'app/dom/directive/swipeSelect',
	// =============================== 自定义公共服务模块
	'app/module/common/service/interceptorService',
	'app/module/common/service/ajaxService'
],function(angularAMD,angular){
	angular.modules = [
		// =============================== 库模块
		'ui.router',
		'ngAnimate',
		'swipe',
		'ngStorage'
		// =============================== 自定义私有指令模块
		
		// =============================== 自定义公共指令模块
		
		// =============================== dome 指令模块
	
	]
	return angularAMD;
})

