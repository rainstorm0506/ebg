// 主函数入口文件，加载应用程序，启动angular
require.config({
	// 配置路径
	paths: {
		       angular : 'public/js/angular/angular',
		  angularRoute : 'public/js/angular/angular-ui-router',
		angularAnimate : 'public/js/angular/angular-animate',
		  angularSwipe : 'public/js/angular/angular-swipe',
		    angularAMD : 'public/js/angular/angularAMD',
		     ngStorage : 'public/js/angular/ngStorage'
	},
	shim: {
		       'angular' : {'exports' : 'angular'},
		  'angularRoute' : ['angular'],
	  	'angularAnimate' : ['angular'],
		  'angularSwipe' : ['angular'],
		    'angularAMD' : ['angular'],
		     'ngStorage' : ['angular']
	},
	deps: ['app/module/store/app']
});