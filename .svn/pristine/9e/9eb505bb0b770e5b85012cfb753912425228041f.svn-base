/**
 * function：走马灯，向左滚动 
 * author：j+2
 * date：2015-01-13
 */
 
$.fn.scrollLeftPlug = function(option){
	var opt = {
		outerbox : "#outerbox",
		 leftbtn : "#leftBtn",
		rightbtn : "#rightBtn",
		interval : true,	// 是否间歇滚动
		 animate : true,	// 使用 animate 方式滚动
		duration : 500,
		  easing : "linear",
		   delay : 2000,	// 间隔滚动时间
		   space : 1,		// 滚动倍数
		   speed : 1,		// left 每间隔滚动距离
		  cSpeed : 1,       // 点击按钮 left 每间隔滚动距离
	   downSpeed : 6,		// 鼠标按下时的间隔滚动距离
		downTime : 10,		// 鼠标按下时的间隔时间
		autoPlay : true
	}
	$.extend(opt,option);
	
	var $this      = $(this);
	var $childList = $(this).children();	// ul 列表
	var $outerbox  = $(opt.outerbox);		// 最外层
	var $hidebox   = $(this).parent();		// 隐藏层
	
	var mSpace     = $childList.children().outerWidth() + parseInt($childList.children().css("margin-right"));
	var space      = opt.space === 0 ? $hidebox.width() : mSpace * opt.space;

	var cleartime,clear,clearInter;

	// $childList.css("float") !== "left" && $childList.css("float","left");
	
	//var w  = $childList.width();
	var w = $childList.children().length * mSpace;

	
	if($hidebox.width() >= w) return false;
	
	$hidebox.width() === $(this).width() && $(this).width(50000);
	
	// 默认设置
	(function(){
		$this.css("position") === "static" && $this.css({"position":"relative","left":0});
		$hidebox.css("position") === "static" && $hidebox.css("position","relative");
		!$this.hasClass("clearfix") && $this.addClass("clearfix");
		
		$childList.clone().appendTo($this);
		
		$childList.css("float") !== "left" && $this.children().css("float","left");
	})();
	
	// 自动滚动
	opt.autoPlay && $outerbox.hover(function(){
		clearInterval(cleartime);
	},function(){
		opt.animate ? scroll("left",opt.speed,opt.delay,true) : scroll("left",opt.speed,opt.delay);
	}).trigger("mouseleave");
	
	// 左按钮
	$(opt.leftbtn)[0] && $(opt.leftbtn).click(function(){
		opt.animate ? animateScroll("right") : intervalScroll("right",opt.cSpeed,1);
	}).mousedown(function(){
		downScroll("right");
	}).mouseup(function(){
		clearInterval(cleartime);
	});
	// 右按钮
	$(opt.rightbtn)[0] && $(opt.rightbtn).click(function(){
		opt.animate ? animateScroll("left") : intervalScroll("left",opt.cSpeed,1);
	}).mousedown(function(){
		downScroll("left");
	}).mouseup(function(){
		clearInterval(cleartime);
	})
	
	$(document).mouseup(function(){
		clearTimeout(clear);
	});
	
	// animate向左间歇滚动
	function animateScroll(dir){
		if($this.is(":animated")) return;
		if(dir === "left"){
			var left = parseInt($this.css("left")) - space;
			$this.stop(true,false).animate({"left":left},opt.duration,opt.easing,function(){
				left + w <= 0 && $this.css("left",0);
			});
		}else{
			if(parseInt($this.css("left"))===0){
				$this.css("left",-w);
			}
			var left = parseInt($this.css("left")) + space;

			$this.stop(true,false).animate({"left":left},opt.duration,opt.easing,function(){
				left >= 0 && $this.css("left",-w);
			});
		}
	}
	
	// 间歇滚动
	function intervalScroll(dir,speed){
		clearInterval(clearInter);
		clearInter = setInterval(function(){
			continuousScroll(dir,speed,1);
		},5);
	}
	
	// 连续向左滚动无间歇
	function continuousScroll(dir,speed,interval){
		if(dir === "left"){
			var left = parseInt($this.css("left")) - speed;
			$this.css("left", left);
			left + w <= 0 && $this.css("left", "0");
		}else{
			var left = parseInt($this.css("left")) + speed;
			$this.css("left", left);
			left >= 0 && $this.css("left", -w);
		}
		// 间歇滚动
		if(interval){
			if(left % space === 0 ) clearInterval(clearInter);
		}
	}
	
	function downScroll(dir){
		if(opt.animate) return false;
		clear = setTimeout(function(){
			scroll(dir,opt.downSpeed,opt.downTime);
		},150);
	}
	
	function scroll(dir,speed,delay,animate){
		clearInterval(cleartime);
		clearTimeout(clear);
		cleartime = setInterval(function(){
			if(animate){
				animateScroll(dir,speed);
			}else{
				opt.interval ? intervalScroll(dir,speed) : continuousScroll(dir,speed);
			}
		},delay);
	}
}