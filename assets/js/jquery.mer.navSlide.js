// ================================== 导航收缩
$(function($){
	$.fn.navSlide = function(){
		$(this).click(function(){
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
});