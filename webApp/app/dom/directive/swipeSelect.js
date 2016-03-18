// ============================================================ 滑动选择
var swipeSelect = function(scope,elem,attr){
	angular.element(document).ready(function(){
		var $ul = elem.find('ul');
		var len = $ul.children().length;
		if($ul.children().length < 3) return false	

		!elem.find('div').prop('nodeName') && $ul.wrap('<div class="scroll"></div>');
		var $scroll = elem.find('div');
		$ul.after($ul.clone());
		
		var $slide = elem.find('div');
		var h = $slide.find('ul').children().prop('clientHeight');
		
		var $li = elem.find('li');

		var slideHeight = $slide.prop('clientHeight');
		var ulHeight    = $ul.prop('clientHeight');
		var hideHeight  = elem.prop('clientHeight');
		var site = 0;
		var index = 0;
		
		var otop = getOffset($li.eq(1)).top;
		setVal(site);
		setCurrent();
		//$li.eq(1).addClass('current');
		elem.on('swipeup',function(){
			var site = parseInt($slide.attr('site'));
			var ha = h+1;
			if(slideHeight + site <= hideHeight + ha) {
				$scroll.removeClass('scroll');
				site = 0;
				setVal(site);
			};
			init(site - ha);
		}).on('swipedown',function(){
			var site = parseInt($slide.attr('site'));
			if(site === 0) {
				$scroll.removeClass('scroll');
				site = -ulHeight-1;
				setVal(site);
			}; 
			init(site + h+1);
		})
		
		function init(y){
			setTimeout(function(){
				!$scroll.hasClass('scroll') && $scroll.addClass('scroll');
				setVal(y);
				setCurrent();
			},1);
		}
		
		function setVal(val){
			//$slide.css({'-moz-transform':'translateY('+ val +'px)','-webkit-transform':'translateY('+ val +'px)','transform':'translateY('+ val +'px)'}).attr('site',val);
			$slide.css('top',val + 'px').attr('site',val);
		}
		
		function setCurrent(){
			setTimeout(function(){
				angular.forEach($li,function(data,index){
					var $this = $li.eq(index);
					
					var offsetTop = index < len ? getOffset($this).top : getOffset($this).top - ulHeight-1;
					
					//$li.eq(index).attr('rel',offsetTop);
					
					offsetTop === otop ||  Math.abs(otop) < Math.abs(offsetTop+10) && Math.abs(otop) > Math.abs(offsetTop - 10) ? $this.addClass('current') : $this.removeClass('current');
				})
			},400)
		}
	})
}
function getOffset(Node,offset) {
	if(!offset) {
		offset = {};
		offset.top = 0;
	}
	if(Node.hasClass('select-wrap-scroll')) {//当该节点为body节点时，结束递归
		return offset;
	}

	offset.top += Node.prop('offsetTop');
	return getOffset(angular.element(Node.prop('parentNode')), offset);//向上累加offset里的值
}