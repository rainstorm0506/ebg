/**
 * function：文本框提示效果适用于 js 单独控制，无 placeholder
 * author：j+2
 * date：2014-07-24
 */
$.fn.placeholderEasePlug = function(option){
	var opt = {
		val:"placedata",
		reset:"#reset"
	}
	$.extend(opt,option);
	
	var placeholder = $(this).attr(opt.val);

	var isPassword = $(this).is(":password");
	if(isPassword){
		var $id = $(this).attr("id") + '-copy';
		$(this).hide().after('<input type="text" id="' + $id +'" class="' + $(this).attr("class") + '" value=' + placeholder +'>');
	}
	var $this = !$(this).is(":password") ? $(this) : $("#" + $id);
	
	var isError = function($$){	
		return $.trim($$.val()) === '' || $.trim($$.val()) === placeholder;
	}
	
	$this.focus(function () {
		if (isError($(this))) {
			isPassword ? $(this).hide().prev().show().addClass("current").focus()
					   : $(this).val('').addClass("current");
		}
	}).val(placeholder);
	
	$(this).blur(function(){
		if(isError($(this))){
			!isPassword ? $(this).val(placeholder).removeClass("current") : $(this).val('').hide().removeClass("current").next().show().val(placeholder) ;
		}
	})
	
	// reset
	$(opt.reset)[0] && $(opt.reset).click(function(){
		$(this).parents("form").find("input[type='text']").each(function(){
			if($(this).prev().attr("type") !== "password"){
				$(this).val($(this).attr(opt.val)).removeClass("current").removeClass("fcurrent");
			}else{
				$(this).val($(this).prev().attr(opt.val)).show().prev().val('').hide();
			}
		})
		return false;
	});
}
$.fn.placeholderPlug = function () {
	var placeholder = $(this).attr("placeholder");
	var $this = $(this);
	var isError = function(obj){	// 为空、或为预留值
		return $.trim($this.val()) === '' || $.trim($this.val()) === placeholder;
	}
	if(placeholder !== '' && placeholder !== undefined){
		// 判断浏览器是否支持html5属性： placeholder
		if("placeholder" in document.createElement("input")){
			$(this).focus(function(){
				isError() && $(this).addClass("fcurrent");
			}).blur(function(){
				isError() && $(this).removeClass("fcurrent");
			}).keydown(function(){
				//isError() ? $(this).removeClass("current").addClass("fcurrent") : $(this).addClass("current").removeClass("fcurrent");
			}).keyup(function(){
				isError() ? $(this).removeClass("current").addClass("fcurrent") : $(this).addClass("current").removeClass("fcurrent");
			}).val('');
			return;
		}
		$(this).placeholderEasePlug({val:'placeholder'});
	}
}
