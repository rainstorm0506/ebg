// ============================================================ 回到顶部
define(['angularAMD'],function(angularAMD){
	'use strict';
	angularAMD.directive('goTop',function(){
		return function(scope,elem,attr){
			var $this = element;
			// 参数
			var topSpeed = 20;
			var delay    = 10;
			var linear   = 0;
			var $scrollBody = angular.element(document.getElementById('mainBody'));
			// 兼容判断
			var isWebkit = /CSS1Compat/.test(document.compatMode) && /webkit/ig.test(navigator.userAgent);
			var mouseWheel = document.all || isWebkit ? "mousewheel" : "DOMMouseScroll";
			//var scrollObject = isWebkit ? document.body : document.documentElement;
			var scrollObject =  $scrollBody;

			// var 
			var cleartime;
			// 事件绑定
			var bind = function (elem, type, handler) {
				if (elem.addEventListener) {
					elem.addEventListener(type, handler, false);
				} else if (elem.attachEvent) {
					elem.attachEvent("on" + type, handler);
				} else {
					elem["on" + type] = handler;
				}
			}
			// 获取与设置 scrollTop
			var scrollTop = function(v){
				if(arguments.length===0){
					return scrollObject.prop('scrollTop');
				}else{
					scrollObject.prop('scrollTop',v);
				}
			}
			// 速度计算
			var tSpeed = function(){ return scrollTop() / topSpeed}
			var bSpeed = function(){ return (scrollObject.scrollHeight-scrollTop())/botSpeed;}
			// 执行效果
			$this.on('touchstart mousedown',function(){
				clearInterval(cleartime);
				var speed;
				if(linear){
					speed = tSpeed();
				}
				console.log(scrollTop())
				cleartime = setInterval(function(){
					if(!linear){ speed = tSpeed(); }
					
					scrollTop(scrollTop() - speed);
					if(scrollTop() <= 0){
						clearInterval(cleartime);
					}
				},delay);
				return false;
			})
			
			// 滚轮事件
			bind($scrollBody, mouseWheel, function (e) { clearInterval(cleartime); alert(0)});
			
			$scrollBody.on('scroll',function(){
				showTop();
			})
		
			function showTop(){
				setTimeout(function(){
					if(scrollTop() > document.body.clientHeight/3){
						$this.addClass('current');
					}else{
						$this.removeClass('current');
					}
				},200)
			}
			
			showTop();
		}
	})
)}