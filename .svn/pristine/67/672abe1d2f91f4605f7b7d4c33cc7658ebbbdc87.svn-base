$(function($){
	// ================================== 导航收缩
	$.fn.navSlide = function(){
		$(this).click(function(){
			if($(this).parent().hasClass('default')) return false;
			if($(this).next().css('display') === 'none'){
				$(this).addClass('current').parent().addClass('current')
			}else{
				$(this).removeClass('current').parent().removeClass('current')
			}
			$(this).next().slideToggle()
		})
	}

	$('#navList h5').navSlide();
	$('#navList h6').navSlide();
	$('#merchantSubNav li > a').navSlide();
});