$(function($){
/*
 * function：图片自动切换，只用 index 按钮控制
 * author：j+2
 * date：2013-08-06
 */

$.tabPlugin = function (option) {
	var opt = {
			   layer : "#changeLayer .js-box",
		         btn : "#controllBtn a",    // 索引值按钮
		currentStyle : "current",
		      inTime : 200,
			inEasing : "linear",
			 outTime : 200,
		   outEasing : "linear",
		       event : "mouseover",
			     dir : "",
		  defaultNum : 0,					// 默认显示索引值
		       delay : 2000,
			autoPlay : false,
		   hasParent : false,
		    callback : function(){}
	}
	$.extend(opt, option);
	
	var $btn      = $(opt.btn);
	var _current  = opt.currentStyle;
	var index     = 0;
	var $this     = $(opt.layer);
	var len       = $this.length;
	var cleartime;
	
	//$this.css("display") === "none" && $this.fadeIn(1000);
	
	if(!$this[0]) return false;
	
	if (len <= 1) { return false; };
	
	!function(){
		if(opt.dir === "fade" || opt.dir === "slide"){
			$this.wrapAll("<div></div>").css({ "position": "absolute", "left": 0, "top": 0 }).parent().css("position", "relative");
		}
	}()

	// 按钮控制
	$btn[opt.event](function () {
		index = $btn.index($(this));
		show(index);
		opt.callback($(this))
		return false;
	}).eq(opt.defaultNum)[opt.event]();
	
	opt.autoPlay && $this.parent().hover(function(){
		clearInterval(cleartime);
	},function(){
		cleartime = setInterval(function () {
			//show(index);
			//index++;
			//if (index === len) index = 0;
			index++;
			if (index >= len) index = 0;
			show(index);
		}, opt.delay);
	}).trigger("mouseleave");
	

	// 停止与继续
	function show(index) {
		switch(opt.dir){
			case "slide":
				if($this.is(":animated")) return false;
				$this.eq(index).css("z-index",1).stop(true,false).slideDown({ duration: opt.inTime, easing: opt.inEasing }).queue(function(){
                        $(this).siblings(opt.layer).stop(true,false).slideUp({ duration: opt.outTime, easing: opt.outEasing }).dequeue();
                    }).siblings(opt.layer).css("z-index",0);
				break;
			case "fade":
				$this.eq(index).stop(true,false).fadeIn(opt.inTime).siblings(opt.layer).stop(true,false).fadeOut(opt.outTime);
				break;
			default:
				$this.eq(index).show().siblings(opt.layer).hide();
				
		}
		!opt.hasParent ? $btn.eq(index).addClass(_current).siblings(opt.btn).removeClass(_current) : 
						 $btn.eq(index).parent().addClass(_current).siblings(opt.btn).removeClass(_current);
	}
}
});