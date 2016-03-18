// ============================================================ 滑动选择
define(['angularAMD'],function(angularAMD){
	'use strict';
	angularAMD.directive('swipeBanner',function(){
		return function(scope,elem,attr){
			angular.element(document).ready(function(){
				var $ul = elem.find('ul');
				var len = $ul.children().length;
				elem.css('width',elem.prop('clientWidth') + 'px');

				if( len === 1) return false	

				!elem.find('div').prop('nodeName') && $ul.wrap('<div class="scroll"></div>');
				
				$ul.after($ul.clone());
				
				var $slide = elem.find('div');
				var $ul = $slide.find('ul');
				var $li = $slide.find('li');
				var $nav = elem.find('nav');
				var index = 0;
				
				var hideWidth = elem.prop('clientWidth');
				
				$ul.css({'height':'100%','float':'left'});
				$li.css({'width':hideWidth+'px','height':'100%','float':'left'});

				var slideWidth = len*2*hideWidth
				
				$slide.css({'width':slideWidth + 'px','height':'100%'});

				setVal(0);
				setCurrent(index);
				
				elem.on('swipeleft',function(){
					var site = parseInt($slide.attr('site'));
					index++;
					if(slideWidth + site === hideWidth) {
						$slide.removeClass('scroll');
						site = -hideWidth;
						setVal(site);
					}
					if(index === len){
						index = 0;
					}
					setTimeout(function(){
						$slide.addClass('scroll');
						setVal(site - hideWidth);
						setCurrent(index);
					},300)
				}).on('swiperight',function(){
					var site = parseInt($slide.attr('site'));
					index--;
					if(index < 0){
						index = len - 1;
					}
					if(site === 0) {
						$slide.removeClass('scroll');
						site = -slideWidth/2;
						setVal(site);
					}; 
					setTimeout(function(){
						$slide.addClass('scroll');
						setVal(site + hideWidth);
						setCurrent(index);
					},300)
				})
				
				function setVal(val){
					$slide.css({'-moz-transform':'translateX('+ val +'px)','-webkit-transform':'translateX('+ val +'px)','transform':'translateX('+ val +'px)'}).attr('site',val);
					//$slide.css('left',val + 'px').attr('site',val);
				}
				
				function setCurrent(index){
					$nav.children().removeClass('current').eq(index).addClass('current');
				}
			})
		} 
	})
})