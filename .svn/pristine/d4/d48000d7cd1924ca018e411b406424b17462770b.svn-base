// 搜索条件
$(function($){
~function(){
	var $wrap = $('#searchCondition');
	var $more = $wrap.find('.js-btn-more');
	var $select = $wrap.find('.js-btn-select');
	var sh = 196;
	
	$more.each(function(){
		var $aside = $(this).siblings('aside');
		var d_h = $aside.find('dl').height();
		var a_h = $aside.height();
		$aside.attr('data',a_h)
		if(a_h >= d_h){
			$(this).remove();
		}
	}).click(function(){condition($(this));})
	
	$select.click(function(){
		condition($(this),true);
		var $aside = $(this).siblings('aside');
		var $nav = $(this).siblings('nav');
		$nav.stop(true,false).fadeIn();
		
		$(this).hide().siblings('.js-btn-more').hide()
		
		$aside.find('dd').children().click(function(){
			if(!$(this).parent().hasClass('current')){
				if($aside.find('dl .current').length > 3){
					alert('已选条件不能大于5个');
					return false;
				}
			}
			$(this).parent().toggleClass('current');
			
			if($aside.find('dl .current').length != 0){
				$nav.find('.btn-3-3').css('display','inline-block');
			}else{
				$nav.find('.btn-3-3').hide();
			}
		})
		
		if(!$(this).parents('li').hasClass('search-brand')){
			$aside.find('dd').each(function(i){
				if(i !== 0){
					var id = "dd-" + $(this).parents('li').index() + '-' + i;
					$(this).html('<label for='+ id +'><input id='+ id +' type="checkbox"><i>'+ $(this).children().text() +'</i></label>');
				}
			});
			
			$aside.find('input').change(function(){
				var len = $(this).parents('dl').find(':checked').length;
				if(len !== 0){
					$nav.find('.btn-3-3').css('display','inline-block');
				}else{
					$nav.find('.btn-3-3').hide();
				}
				if(len > 4){
					alert('已选条件不能大于5个');
					$(this).prop('checked',false);
					return false;
				}
			})
		}
		
		return false;
	})
	
	// ============================= 取消多选
	$wrap.find('.js-btn-esc').click(function(){
		var $aside = $(this).parent().siblings('aside');
		$(this).parent().hide().siblings('.js-btn-select,.js-btn-more').removeClass('current').fadeIn().siblings('.js-btn-more').find('em').text('更多');
		
		$aside.prop('scrollTop',0).removeClass('oy').stop(true,false).animate({'height':parseInt($aside.attr('data'))},500);
		$aside.find('dd').removeClass('current');
		if(!$(this).parents('li').hasClass('search-brand')){
			$aside.find('dd').each(function(i){
				if(i !== 0){
					$(this).html('<a href="javascript:;">'+ $(this).find('i').text() +'</a>')
				}
			})
		}
	})
	
	function condition($this,s){
		var $aside = $this.siblings('aside');
		var d_h = $aside.find('dl').height();
		var a_h = parseInt($aside.attr('data'));
		
		if($aside.height() === a_h){
			height = d_h;
			if(d_h > sh){
				$aside.addClass('oy');
				height = sh;
			}
			!s && $this.addClass('current').find('em').text('收起');
			$aside.stop(true,false).animate({'height':height},500);
		}else{
			if(s) return false;
		
			height = a_h;
			$aside.removeClass('oy');
			$this.removeClass('current').find('em').text('更多');
			$aside.prop('scrollTop',0).stop(true,false).animate({'height':height},500);
		}
	}
	
	// ============================= 更多条件
	
	var $jsMore = $wrap.find('.js-more');
	var $btnMore = $('#btnMore');
	var txt = $btnMore.text();
	if(!$jsMore[0]){
		$btnMore.hide()
	}
	
	$btnMore.click(function(){
		if($jsMore.css('display') === 'none'){
			$(this).addClass('current').find('em').text('收起更多选项');
		}else{
			$(this).removeClass('current').find('em').text(txt);
		}
		$jsMore.stop(true,false).slideToggle(300);
	})
}()
});