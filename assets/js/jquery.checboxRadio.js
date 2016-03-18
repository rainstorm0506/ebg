/*
 * 模拟 checkbox/radio
 */
$.fn.checboxRadio = function(opt){
	var parents = opt.parents || 'label'
	var $checbox = $(this).find('input[type="checkbox"]');
	var $radio = $(this).find('input[type="radio"]');
	
	var hasP = opt.parents
	
	$checbox.change(function () {
		if ($(this).prop("checked")) {
			$(this).parents(parents).addClass('current');
		} else {
			$(this).parents(parents).removeClass("current");
		}
	});
	
	$radio.change(function () {
		$(this).parents(parents).addClass('current').siblings().removeClass('current');
	});
}
		