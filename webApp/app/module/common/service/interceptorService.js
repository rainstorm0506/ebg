// ============================================================ 搜索模块指令
window.E = {
	path : 'http://192.168.0.240/webApp/api/',
	time : function(){
		return parseInt(Date.parse(new Date())/1000);
	},
	API : {
			   msCode : 'global.sendSmsCode',				// 短信验证码
      		  imgCode : 'global.getImgCode',				// 图片验主码
		viferyImgCode : 'global.viferyImgCode',  			// 异步验证图片验证码
	      sendSmsCode : 'global.sendSmsCode',				// 发送手机短信码
	    viferySmsCode : 'global.viferySmsCode'				// 异步验证手机短信码
	}
}
define(['angularAMD'],function(angularAMD){
	'use strict';

	angularAMD.factory('userIntercepted',function($q,$rootScope,$localStorage){
		return {
			request : function(config){	// 请求成功
				// 基于 token 的认证
				// 在基于 token 的认证里，不再使用 cookie 和session。token 可被用于在每次向服务器请求时认证用户
			
				//var token = $rootScope.token;
				
				//if(token){config.headers['token'] = token;}
				config.reqTime = new Date().getTime();	// 请求时间
				//$rootScope.loading = true;
				return config;
			},
			response : function(response){	// 响应成功
				response.config.resTime = new Date().getTime();	// 响应时间
				//$rootScope.loading = false;
				return response;
			},
			requestError : function(rejection){	// 请求失败
				return rejection;
			},
			responseError : function(response){	// 响应失败
				var data = response.data;
				console.log('API响应失败：%c' + response.config.url,'color:blue')
				/*
				console.log(response)
				// 判断错误码，如果是未登录
				if(data['errorCode'] === '500999'){
					// 清空用户本地 token 存储的信息
					//$rootsScope.user = {token : ''}	
					
					
					// 全局事件，方便其他 view 获取该事件，并给以相应的提示或处理
					$rootScope.$emit('userIntercepted','notLogin',response);
				}
				
				// 如果登录超时
				if(data['errorCode'] === '500998'){
					$rootScope.$emit('userIntercepted','sessionOut',response);
				}
				*/
				return $q.reject(response);
			}
		}
	}).constant('AUTH_EVENTS', {
		loginSuccess: 'auth-login-success',
		loginFailed: 'auth-login-failed',
		logoutSuccess: 'auth-logout-success',
		sessionTimeout: 'auth-session-timeout',
		notAuthenticated: 'auth-not-authenticated',
		notAuthorized: 'auth-not-authorized'
	})
	// 权限配置
	.constant('USER_ROLES', {
		all: '*',
		guest: 'guest',
		admin: 'admin',
		person: 'person',			// 个人
		enterprise : 'enterprise',	// 企业
		merchants : 'merchants',	// 商家
	})
	// 公共配置
	.provider('public',{
		// 配置POST解析josn数据
		$get : function($httpProvider){
			$httpProvider.interceptors.push('userIntercepted');
			//$httpProvider.defaults.headers.put['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
		    $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
    		$httpProvider.defaults.headers.post['Accept'] = 'application/json, text/javascript, */*; q=0.01';  
    		$httpProvider.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';  
		    
			var param = function(obj) {
				var query = '', name, value, fullSubName, subName, subValue, innerObj, i;

				for(name in obj) {
					value = obj[name];
	
					if(value instanceof Array) {
						for (i = 0; i < value.length; ++i) {
							subValue = value[i];
							fullSubName = name + '[' + i + ']';
							innerObj = {};
							innerObj[fullSubName] = subValue;
							query += param(innerObj) + '&';
						}
					}else if(value instanceof Object) {
						for(subName in value) {
							subValue = value[subName];
							fullSubName = name + '[' + subName + ']';
							innerObj = {};
							innerObj[fullSubName] = subValue;
							query += param(innerObj) + '&';
						}
					}else if(value !== undefined && value !== null){
						query += encodeURIComponent(name) + '=' + encodeURIComponent(value) + '&';
					}
				}
	
				return query.length ? query.substr(0, query.length - 1) : query;
			};
		    
	      	$httpProvider.defaults.transformRequest = [function(data) {
	    		return angular.isObject(data) && String(data) !== '[object File]' ? param(data) : data;
	 		}];
		}
	}).service('ePublic',function($rootScope,$http,$localStorage,$sessionStorage,$window,$location,$log,$ajax){
		this.run = function(){
			
			$rootScope.imgPath = 'public/images/';
			
			// token 首页请求
			$http.post(E.path + 'global.token').success(function(data){
				if($localStorage['x-token'] === '' || $localStorage['x-token'] === undefined){
					$localStorage.$default({'x-token':data['x-token'],'x-vers':'2.0.0'});
				}
				console.log($localStorage)
			});
			// ============================= 路由监听
			$rootScope.$on('$stateChangeSuccess', function(event, toState, toParams, fromState, fromParams){
       			//$log.debug('successfully changed states') ;
	            
	            //$log.debug('event', event);
	            //$log.debug('toState', toState);
	            //$log.debug('toParams', toParams);
	            //$log.debug('fromState', fromState);
	            //$log.debug('fromParams', fromParams);
	        });
	 
	        $rootScope.$on('$stateNotFound', function(event, unfoundState, fromState, fromParams){
	            $log.error('路由无效: ' + unfoundState);
	        });
	        
	        $rootScope.$on('$stateChangeError', function(event, toState, toParams, fromState, fromParams, error){
	            //$log.error('路由改变错误: ' + error);
	            
	            //$log.debug('event', event);
	            //$log.debug('toState', toState);
	            //$log.debug('toParams', toParams);
	            //$log.debug('fromState', fromState);
	            //$log.debug('fromParams', fromParams);
	        });
		}
		// 排序
		this.sort = function($scope,opt){
			var opt = opt || {
				   api : '',
				    by : '',
				   asc : '',
				method : ''
			}
			var api = API[opt.api];
			
			$ajax.query(api,{o:opt.by,by:'desc'},function(data){ // 默认降序
				
				$scope.data = data;
			})
			
			$rootScope[opt.asc] = false;
			$rootScope[opt.method] = function(){
				if(!$rootScope[opt.asc]){
					$ajax.query(api,{o:opt.by,by:'asc'},function(data){
						$scope.data = data;
					})
					$rootScope[opt.asc] = true;
				}else{
					$ajax.query(api,{o:opt.by,by:'desc'},function(data){
						$scope.data = data;
					})
					$rootScope[opt.asc] = false;
				}
			}
		}
		return this;
	})
})

/*
 * 注册拦截器到 config 中
	
	app.config(function($httpProvider){
		$httpProvider.interceptors.push('userIntercepted')
	})
 
*/
