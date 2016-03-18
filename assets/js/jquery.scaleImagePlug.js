$(function($){
	/**
	 * 功能：放大鐘效果
	 * author：j+2
	 * date：2014-09-03
	 */
	(function($){
		$.fn.scaleImagePlug = function(option){
			
			var boxWidth    = $(this).width();
			var boxHeight   = $(this).height();
			
			var opt = {
				   time : "fast",
				   size : 150,
				opacity : .5,
				  width : boxWidth,
				 height : boxHeight,
				  space : 2
			}
			$.extend(opt,option);
			
			/**
			 * 变量赋值
			 */	
			var $this       = $(this);
			var size        = opt.size;
			var width       = opt.width;
			var height      = opt.height;
			var ih			= size*height/width;
			
			/**
			 * 默认配置
			 */
			var bLeft = boxWidth + opt.space;
			
			!function(){
				!$this.find("i")[0] && $this.css("position","relative").append("<i style='width:"+ size +"px;height:"+ ih +"px;filter:alpha(opacity="+ opt.opacity*100 +");opacity:"+ opt.opacity +";position:absolute;left:50px;top:0;z-index:1;cursor:move;display:none;background-color:#eae67d;'></i>");
				!$this.find("div")[0] && $this.append("<div style='\width:0;height:0;position:absolute;display:none;overflow:hidden;left:"+ bLeft +"px;top:0px;background-color:#fff;overflow:hidden'></div>");
			}();

			$(this).hover(function(event){
				var $img        = $(this).find("img");
				var $i          = $(this).find("i");
				var $div        = $(this).find("div");
				
				var dWidth      = $div.outerWidth();
				var dHeight     = $div.outerHeight();
				var iWidth      = $i.outerWidth();
				var iHalfWidth  = iWidth /2;
				var iHeight     = $i.outerHeight();
				
				var iHalfHeight = iHeight /2;
				var $bigImg = $("<img src='" + $img.attr("src") +"' style='width:"+ boxWidth*width/size +"px;height:auto;position:relative;'>");
				$bigImg.appendTo($div);
				$bigImg.load(function(){
					$i.stop(true,false).fadeIn(opt.time).next().stop(true,false).fadeIn(opt.time,function(){
						$div.css({
							//"width"  : $bigImg.width()  * (iWidth/boxWidth),	// 小框宽度 / 小图宽度 === 大框宽度 / 大图宽度
							//"height" : $bigImg.height() * (iHeight/boxHeight)   // 小框高度 / 小图高度 === 大框高度 / 大图高度
							width : width,
							height : height
						});
					});
				})
				
				$(this).mousemove(function(event){
					var left,top;
					var pageX = event.pageX || event.screenX || event.clientX;
					var pageY = event.pageY || event.screenY || event.clientY;
					
					var x = pageX - $(this).offset().left;
					var y = pageY - $(this).offset().top;
					
					
					if(x < iHalfWidth){
						left = 0;
					}else if(x > boxWidth - iHalfWidth){
						left = boxWidth - iWidth;
					}else{
						left = x - iHalfWidth;
					}
					if(y < iHalfHeight){
						top = 0;
					}else if(y > boxHeight - iHalfHeight){
						top = boxHeight - iHeight;
					}else{
						top = y - iHalfHeight;
					}
					
					$i.css({"left":left,"top":top});
					
					$bigImg.css({
						"left" : -$i.position().left / boxWidth * $bigImg.width(),	// 小框左偏移量 / 小图宽度 === 图片left / 大图宽度
						"top"  : -$i.position().top  / boxHeight * $bigImg.height() // 小框上偏移量 / 小图高度 === 图片top  / 大图高度
					});
					
					event.stopPropagation();
				});
				
				$div.hover(function(){
					remove($i,$div);
					return false;
				}).mousemove(function(){
					remove($i,$div);
					return false;
				});
				
				
			},function(){remove($(this).find("i"),$(this).find("div"))});
			
			
			function remove($i,$div){
				//$i.css("display") !== "none" && $i.stop(true,false).fadeOut(opt.time).next().stop(true,false).fadeOut(opt.time);
				$i.css("display") !== "none" && $i.stop(true,false).fadeOut(opt.time).next().hide();
				$this.unbind("mousemove");
				$div.children().remove();
			}
			
		}
	})(jQuery);
});