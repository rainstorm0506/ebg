/**
 *	function：是否同意协议
 *  author：j+2
 *  date : 2015-12-09
 */

 $.fn.isAgreed = function(option){
	var opt = {
		btn : 'input[type="submit"]',
		disabledStyle : 'btn-disabled',
		send : '.btn-2'
	}
	$.extend(opt,option);
	
	var $btn = $(opt.btn);
	var style = opt.disabledStyle;
	var $this = $(this);
	
	$(this).change(function(){
		var $form = $(this).parents('form');
		var $forms = $form.find('input,select,textarea');
		var $send = $form.find(opt.send);
		
		if($(this).prop('checked')){
			$btn.removeClass(style);
			$forms.not($(this)).prop('disabled',false);
			$send.removeClass('disabled');
		}else{
			$btn.addClass(style);
			$forms.not($(this)).prop('disabled',true);
			$send.addClass('disabled');
		}
	}).eq(0).change()
	
	$(this).parents('form').submit(function(){
		if(!$this.prop('checked')){
			return false;
		}
	})
 }
