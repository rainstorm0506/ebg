$(function($){
// 发送验证码
$.fn.sendVerification = function(option){
	var opt = {
				  tel : '#tel',
				 time : 60,
			      txt : '秒后可重新获取',
				 cTxt : '重新获取验证码',
				 site : 'after',
			 callback : function(){},
				stype : '',
		 nullCallback : function($tel){
			 
			if(this.site === 'after'){
				var $str = $('<span class="promt error">手机号码不能为空</span>');
				var $err = $tel.siblings('.error');
				if(!$err[0]){
					$tel.after($str);
				}else{
					$err.text('手机号码不能为空')
				}
			}else{
				$('#promt').stop(true,false).fadeIn().text('手机号码不能为空')
			}
			
			//alert('手机号码不能为空');
		 },
		 testCallback : function($tel){
			if(this.site === 'after'){
				var $str = $('<span class="promt error">手机号码不合法</span>');
				var $err = $tel.siblings('.error');
				if(!$err[0]){
					$tel.after($str);
				}else{
					$err.text('手机号码不合法')
				}
			}else{
				$('#promt').stop(true,false).fadeIn().text('手机号码不合法')
			}
			
			//alert('手机号码不合法');
		 }
	}
	
	$.extend(opt,option);

	var time = opt.time;
	//var str1 = '重新' + $(this).text();
	var str1 = opt.cTxt;
	var str2 = opt.txt;
	var $tel   = $(opt.tel);
	var $input = $(this).prev('input[type="text"]');

	var cleartime;
	
	$tel.focus(checkInput).blur(checkInput).keyup(checkInput);
	
	function checkInput(){
		var val    = $tel.val();
		var isNull = $.trim(val) === "";
		var test   = /^(13\d|18\d|14[57]|15[012356789]|17[0678])\d{8}$/.test(val);
		if(!isNull && test){
			$input.prop('disabled',false);
		}else{
			$input.prop('disabled',true);	// 禁用;
		}
	}
	
	$(this).click(function(){
		
		if($(this).hasClass('disabled')) return;
		
		var $this  = $(this);
		var val    = $tel.val();
		var isNull = $.trim(val) === "";
		var test   = /^(13\d|18\d|14[57]|15[012356789]|17[0678])\d{8}$/.test(val);
		
		if(isNull || !test){
			$input.prop('disabled',true);	// 禁用;
		}
		
		if(isNull){
			opt.nullCallback($tel);
			return false;
		}else if(!test){
			opt.testCallback($tel);
			return false
		}
		
		if (typeof(_send_permission)=='object' && _send_permission[opt.stype] === null)
		{
			$(this).nextAll('span,q').remove();
			$(this).after('<q class="promt error msg no-sms">请输入有效的手机号码</q>');
			return false;
		}else if (typeof(_send_permission)=='object' && _send_permission[opt.stype] === false){
			$(this).nextAll('span,q').remove();
			$(this).after('<q class="promt error msg no-sms">此号码已注册 , 不能发送短信!</q>');
			return false;
		}

		if (typeof(_code_permission)=='object' && (!_code_permission || !_code_permission[opt.stype]))
		{
			$(this).nextAll('span,q').remove();
			$(this).after('<q class="promt error msg no-sms">请输入正确的图形验证码!</q>');
			return false;
		}
		
		if (typeof(_code_permissions)=='object' && (!_code_permissions || !_code_permissions[opt.stype]))
		{
			$('#PersonForm_vxcode').focus();
			$('#promt').stop(true,false).fadeIn().text('请输入正确的图形验证码!');
			return false;
		}

		if($(this).hasClass('current')) return;
		
		$(this).addClass('current');
		
		$this.html('<i>'+ time +'</i>' + str2);
		
		cleartime = setInterval(function(){
			time--;
			if(time < 0){
				clearInterval(cleartime);
				$this.removeClass('current');
				$this.text(str1);
				time = opt.time;
				return false;
			}
			$this.html('<i>'+ time +'</i>' + str2)
		},1000);
		
		opt.callback($(this));
		
		return false;
	});
}
});