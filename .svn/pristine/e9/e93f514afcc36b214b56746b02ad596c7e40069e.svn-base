/**
 * 功能：拖拽元素
 * author：j+2
 * date：2014-09-24
 */
 
$.fn.dragPlug = function(option){
	var opt = {
		dragRange : ".js-drag-range",
		  movebox : ".js-drag-move",
		  minLeft : 0,
		   minTop : 0,
		   cursor : "move",
		      dir : "xy",
		 init : function($this){},
		 moveCallback : function($this,x,y){},
		 mouseupCallback : function($this){}
	};

	$.extend(opt,option);
	var $this     = $(this);
	var minLeft   = opt.minLeft;
	var minTop    = opt.minTop;
	
	var isWindow  = typeof opt.dragRange === "object";
	
	var func = {
		layerX : function(event){
			return document.all ? event.offsetX : event.originalEvent.layerX || event.layerX || event.offsetX;
		},
		layerY : function(event){
			return document.all ? event.offsetY :  event.originalEvent.layerY || event.layerY || event.offsetY;
		}
	}
	// 默认执行
	$(opt.movebox).css("position") === "static" && $(opt.movebox).css({"position":"absolute","z-index":0});
	if(!isWindow){
		$(opt.dragRange).css("position") === "static" && $(opt.dragRange).css("position","relative");
	}

	$(document).ready(function(event){opt.init($this)});
	
	$(this).mousedown(function(evt){
		var $this       = $(this);
		var $rangebox   = isWindow ? $(window) : $(this).parents(opt.dragRange);
		var $movebox    = $(this).parents(opt.movebox);
		var $movebox    = $(this)[0] === $movebox || !$movebox[0] ? $(this) : $movebox;
		
		
		var offsetLeft  = isWindow ? 0 : $rangebox.offset().left;	// 移动范围层，左偏移量
		var offsetTop   = isWindow ? 0 : $rangebox.offset().top;
		var rangeWidth  = $rangebox.width();
		var rangeHeight = $rangebox.height();
		var dragWidth   = $movebox.outerWidth();
		var dragHeight  = $movebox.outerHeight();
		var maxLeft     = rangeWidth - dragWidth + minLeft;
		var maxTop      = rangeHeight - dragHeight + minTop;
		var left        = func.layerX(evt);
		var top         = func.layerY(evt);
				
		isWindow ? $("body").addClass("user-select") : $rangebox.addClass("user-select");
		$movebox.siblings(opt.movebox)[0] && $movebox.css("z-index",1).siblings(opt.movebox).css("z-index",0);
		
		$(document).mousemove(function(event){
			var x = event.pageX - offsetLeft - left;
			var y = event.pageY - offsetTop  - top;
			
			if(x < minLeft){
				x = minLeft;
			}else if(x > maxLeft){
				x = maxLeft;
			}else{
				x = x;
			}
			if(y < minTop){
				y = minTop;
			}else if(y > maxTop){
				y = maxTop;
			}else{
				y = y;
			}
			$this.css("cursor",opt.cursor);
			//$movebox.stop(true,false).animate({"left":x,"top":y},10);
			switch(opt.dir){
				case "x":
					$movebox.css({"left":x});
					break;
				case "y":
					$movebox.css({"left":y});
					break;
				default:
					$movebox.css({"left":x,"top":y});
					break;
			}	
			opt.moveCallback($this,x,y);
		})
	});
	
	$(document).mouseup(function(){
		$(this).unbind("mousemove");
		$this.css("cursor",opt.cursor);
		$("body").removeClass("user-select");
		opt.mouseupCallback($this);
	});
	
	return this;
}