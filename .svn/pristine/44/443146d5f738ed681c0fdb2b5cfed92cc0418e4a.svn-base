$(function($){
	/*
	 * function：层弹跳特效插件
	 *
	 */
	$.fn.floatLayer = function (o) {
		var o    = typeof (o) == "object" ? o : {};
		var qq   = $(this);
		
		o.top    = parseInt(o.top);
		o.bottom = parseInt(o.bottom);
		o.x      = o.x || qq.position().left;

		o.isHide = qq.position().left < 0 ? true : false;
		o.h      = qq.height();
		
		qq.css('position') === 'static' && qq.css('position','absolute');

		function qqDoset(){
			if (qqDoset.doset) {
				clearTimeout(qqDoset.doset);
			}
			qqDoset.doset=setTimeout(qqPoset,50);
		}

		function qqPoset(){
			var
				scrollTop = $(document).scrollTop(),
				winHeight = $(window).height(),
				_top = o.bottom ? parseInt(winHeight + scrollTop - o.bottom - o.h + 20) + "px" : parseInt(scrollTop + (o.top || (winHeight / 2 - o.h / 2)) + 20) + "px";

			qq.stop(true, false).animate({ top: _top }, { duration: o.time || 4000, easing: o.easing || "easeOutElastic" });

		}

		qqDoset();
		
		$(window).scroll(qqDoset).resize(qqDoset);

		return this;
	}
	// ============================================================================================ 客服
		var html ='\
		<aside class="kefu-wrap">\
			<section>\
				<header></header>\
				<div>\
					<h5>售前客服</h5>\
					<p><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=2944775460&site=qq&menu=yes"><img border="0" src="http://wpa.qq.com/pa?p=2:464893998:52" alt="点击这里给我发消息" title="点击这里给我发消息"/><span>售前客服1</span></a></p>\
					<p><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=2835531859&site=qq&menu=yes"><img border="0" src="http://wpa.qq.com/pa?p=2:464893998:52" alt="点击这里给我发消息" title="点击这里给我发消息"/><span>售前客服2</span></a></p>\
					<h5>售后客服</h5>\
					<p><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=2543567079&site=qq&menu=yes"><img border="0" src="http://wpa.qq.com/pa?p=2:464893998:52" alt="点击这里给我发消息" title="点击这里给我发消息"/><span>售后客服1</span></a></p>\
					<p><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=3216637792&site=qq&menu=yes"><img border="0" src="http://wpa.qq.com/pa?p=2:464893998:52" alt="点击这里给我发消息" title="点击这里给我发消息"/><span>售后客服2</span></a></p>\
				</div>\
				<footer></footer>\
			</section>\
			<a id="kefnBtn" href="javascript:;"><span><b>4000-456-423</b></span></a>\
		</aside>';
		
		var $html = $(html);
		
		$('body').append($html);
		
		
		var $a = $("#kefnBtn");
		var $s = $html.find('section');
		var $f = $html.find('footer');
		
		var w = $html.width();
		
		$a.click(function(){
			if(parseInt($s.css('left')) !== 0){
				$s.fadeIn().stop(true,false).animate({'left':0,'opacity':1},1000);
				$(this).fadeOut()
			}else{
				slide();
			}
			return false;
		})
		
		$a.children().click(function(){return false;})

		$f.click(function(){
			slide();
			$a.fadeIn()
			return false;
		})

		function slide(){
			$s.stop(true,false).animate({'left':w,'opacity':0},1000);
		}
		
		$html.floatLayer({bottom:480,time:1000,easing:'easeOutBack'});
})