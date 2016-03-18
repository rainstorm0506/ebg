/**
 * 功能：图片各种轮播效果
 * 创建日期：2012-06-28
 * 修改日期：2012-10-12
 * 创建人：j+2
*/
(function ($) {
    $.fn.generalSlidePlug = function (option) {
        var opt = {
            outerbox : "#outerbox",
                 btn : "#controllBtn a",
             leftbtn : "#leftbtn",
            rightbtn : "#rightbtn",
               title : "",
              inTime : 1000,
            inEasing : "linear",
             outTime : 1000,
           outEasing : "linear",
               delay : 5000,
        currentStyle : "current",
	   css3showClass : "css3-show",
	   css3hideClass : "css3-hide",
                data : "data",
                 dir : "fade",
               event : "mouseover",
		  defaultNum : 0,
			 hasCss3 : false,
			autoPlay : true
        }
        $.extend(opt, option);
		
		var $btn        = $(opt.btn);
		var $outerbox   = $(opt.outerbox);
		var $title      = $(opt.title);
		var _current    = opt.currentStyle;
		var index       = 0;
		var $child      = $(this).children();
		var len         = $child.length;
		var $this       = $(this);
		var noAnimation = $("body").css("-moz-animation") !== undefined || $("body").css("-webkit-animation") !== undefined || $("body").css("-ms-animation") !== undefined || $("body").css("-o-animation") !== undefined || $("body").css("animation") !== undefined ? false : true;
		
		var cleartime;
		
		var isCss3 = $("body").css("-moz-animation") !== undefined || 
				 $("body").css("-webkit-animation") !== undefined || 
				 $("body").css("-ms-animation") !== undefined || 
				 $("body").css("-o-animation") !== undefined || 
				 $("body").css("animation") !== undefined
		
		$child.css("display") === "none"   && $child.fadeIn(opt.inTime);
		$(this).css("display") === "none"  && $(this).fadeIn(opt.inTime);
	
		// 判断滚动层个数与按钮个数是否一致
		// if (len != $btn.length) { throw new Error("在 id " + opt.btn + " 处，请注意，图片个数，与控制按钮不一致");}
		if (len <= 1) { return false };
		
		if(opt.dir === "fade" || opt.dir === "opacity"){
			$(this).css("position") === "static" && $(this).css({ "position": "relative"});
			$child.css({ "position": "absolute", "left": 0, "top": 0, "z-index": 0 });
		}
		
		if(opt.dir === "left" || opt.dir === "top"){
			var layerWidth  = $child.outerWidth() !== 0 ? $child.outerWidth() :  $(this).parent().outerWidth();
			var layerHeight = $child.outerHeight() !== 0 ? $child.outerHeight() :  $(this).parent().outerHeight();
			$(this).css("position") === "static" && $(this).css({ "position": "relative"});
			$(this).parent().css("position") === "static" && $(this).parent().css("position","relative");
			if(opt.dir === "left"){
				$child.css("float","left");
				$(this).width(len * layerWidth);
			}else{
				$child.css("vertical-align","middle");
			}
		}
		
		$.fn.extend({
			hoverStop: function () {
				this.hover(function () {
					clearInterval(cleartime);
				}, function () {
					
					cleartime = setInterval(function () {
						index++;
						if (index >= len) index = 0;
						show(index);
					}, opt.delay);
				}).trigger("mouseleave");
				return this;
			}
		});
		
		opt.autoPlay && $outerbox.hoverStop();
		
		// 按钮控制
		$btn[0] && $btn[opt.event](function () {
			index = $btn.index($(this));
			show(index);
			return false;
		}).eq(opt.defaultNum)[opt.event]();
		
		//右边按钮控制
		$(opt.rightbtn)[0] && $(opt.rightbtn).click(function () {
			index++;
			if (index == len) index = 0;
			show(index);
		});
		
		//左边按钮控制
		$(opt.leftbtn)[0] && $(opt.leftbtn).click(function () {
			index--;
			if (index < 0) index = len - 1;
			show(index);
		});
		
		function show(index){
			if(opt.hasCss3){
				if(isCss3){
					$child.eq(index).addClass(opt.css3showClass).removeClass(opt.css3hideClass).siblings(opt.layer).addClass(opt.css3hideClass).removeClass(opt.css3showClass);
					/*
					var clear = setTimeout(function(){
						$child.eq(index).css("z-index",1).siblings(opt.layer).css("z-index",0)
					},2000)*/
				}else{
					switchDir(index);
				}
			}else{
				switchDir(index);
			}
			
			!$btn.children()[0] ? $btn.eq(index).addClass(_current).siblings().removeClass(_current) : $btn.eq(index).parent().addClass(_current).siblings().removeClass(_current);
		
			$title[0] && addTitle(index);	// 动态变换 title 及 链接
			
		}
		
		// 动态变换 title 及 链接
		function addTitle(index){
			// 变换链接
			$child.find("a")[0] ? $title.attr("href",$child.eq(index).find("a").attr("href")) : $title.attr("href",$child.eq(index).attr("href"));
			// 变换 title
			$child.find("img").attr("title") !== undefined && $child.find("img").attr("title") !== "" ?
				$title.text($child.eq(index).find("img").attr("title")) :
				$title.text($child.eq(index).attr(opt.data));
		}
		
		function switchDir(index){
			switch (opt.dir) {
				case "left": //向左滑动
					$this.stop(true, false).animate({ left: -layerWidth * index }, opt.inTime, opt.inEasing);
					break;
				case "top": //向上滑动
					$this.stop(true, false).animate({ top: -layerHeight * index }, opt.inTime, opt.inEasing);
					break;
				case "fade": //淡隐淡出
					$child.eq(index).stop(true, false).fadeIn(opt.inTime).siblings().stop(true,false).fadeOut(opt.outTime);
					break;
				case "opacity": //淡隐淡出
					$child.eq(index).css("z-index", 1).stop(true, false).animate({ "opacity": "1" }, opt.inTime,opt.inEasing).siblings().css("index",0).animate({ "opacity": "0" }, opt.outTime,opt.outEasing);
					break;
				default: //显示隐藏
					$child.eq(index).show().siblings().hide();
					break;
			}
		}
		
		return this;
    }
})(jQuery)
