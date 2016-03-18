$(function($){
	(function(opt){
		// 参数
		var $top = opt.$ || "go-top";
		var $bottom = opt.bottom || 'go-bottom'
		var topSpeed = opt.speed || 30;
		var botSpeed = opt.speed2 || 50;
		var delay = opt.delay || 10;
		var linear = opt.linear || false;
		// 兼容判断
		var isWebkit = /CSS1Compat/.test(document.compatMode) && /webkit/ig.test(navigator.userAgent);
		var mouseWheel = document.all || isWebkit ? "mousewheel" : "DOMMouseScroll";
		var scrollObject = isWebkit ? document.body : document.documentElement;
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
				return scrollObject.scrollTop;
			}else{
				scrollObject.scrollTop = v;
			}
		}
		// 速度计算
		var tSpeed = function(){ return scrollTop() / topSpeed}
		var bSpeed = function(){ return (scrollObject.scrollHeight-scrollTop())/botSpeed;}
		// 执行效果
		document.getElementById($top).onclick = function(){
			clearInterval(cleartime);
			var speed;
			if(linear){
				speed = tSpeed();
			}
			cleartime = setInterval(function(){
				if(!linear){ speed = tSpeed(); }
				
				scrollTop(scrollTop() - speed);
				if(scrollTop() <= 0){
					clearInterval(cleartime);
				}
			},delay);
			return false;
		}
		
		if(document.getElementById($bottom)){
			document.getElementById($bottom).onclick = function(){
				clearInterval(cleartime);
				var speed;
				if(linear){
					speed = bSpeed();
				}
			
				cleartime = setInterval(function(){
					if(!linear){ speed = bSpeed(); }
					
					scrollTop(scrollTop() + speed);
					
					if(scrollTop() >= scrollObject.scrollHeight-window.innerHeight){	// 判断是否到底部
						clearInterval(cleartime);
					}
				},delay);
				return false;
			}
		}
		
		// 滚轮事件
		bind(document.body, mouseWheel, function (e) { clearInterval(cleartime); });
		
		window.onscroll = function(){
			showTop();
			showBottom();
		}
		
		function showTop(){
			setTimeout(function(){
				if(scrollTop() > $(window).height()/3){
					$('#'+$top).stop(true,false).animate({'opacity':1},500);
				}else{
					$('#'+$top).stop(true,false).animate({'opacity':0},500);
				}
			},200)
		}
		
		function showBottom(){
			setTimeout(function(){
				if(scrollObject.scrollHeight - scrollTop()- $(window).height()> $(window).height()/3){
					$('#'+$bottom).stop(true,false).animate({'opacity':1},500);
				}else{
					$('#'+$bottom).stop(true,false).animate({'opacity':0},500);
				}
			},200)
		}
		
		showTop();
		showBottom();
		
	})({$:'goTop',speed:30,linear:0})
})