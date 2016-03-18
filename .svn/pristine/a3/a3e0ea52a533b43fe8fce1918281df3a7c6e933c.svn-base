<?php
Views::css(array('register'));
Views::js(array('jquery.validate' , 'jquery.sendVerification' , 'jquery.isAgreed'));
Yii::app()->getClientScript()->registerCss('code-verify' , ".form-list .code-verify img{cursor:pointer;width:120px;height:40px;margin:0 0 0 10px}");

if ($this->isPost() && $formError)
	Yii::app()->getClientScript()->registerCoreScript('layer');
?>
<main class="register-wraper">
	<aside><img src="<?php echo Views::imgShow('images/banner/register-ad.png'); ?>"></aside>
	<section>
		<h3 class="tit-1">我要开店</h3>
		<fieldset class="form-list">
			<legend>我要开店</legend>
			<?php $active = $this->beginWidget('CActiveForm',array('id'=>'companyRegister')); ?>
				<ul>
					<li><?php echo $active->textField($form,'phone',array('id'=>'phone','class'=>'tbox38 tbox38-1','placeholder'=>'验证手机','autocomplete'=>'off')); ?></li>
					<li class="code-verify">
					<?php
						echo $active->textField($form , 'vxcode' , array('ags'=>'merchant','placeholder'=>'请输入图形验证码','class'=>'tbox38 tbox38-2','maxlength'=>6,'style'=>'height:40px'));
						echo '<img ags="merchant" class="svcode">';
					?>
					</li>
					<li>
						<?php
							$form->vcode = empty($form->vcode) ? '' : (int)$form->vcode;
							echo $active->textField($form,'vcode',array('id'=>'vcode','class'=>'verCode tbox38 tbox38-2','placeholder'=>'短信验证码','autocomplete'=>'off'));
						?>
						<a class="btn-2 sms-send member-send">获取短信验证码</a>
					</li>
					<li><?php echo $active->passwordField($form,'password',array('id'=>'password','class'=>'tbox38 tbox38-1','placeholder'=>'输入密码','autocomplete'=>'off')); ?></li>
					<li><?php echo $active->passwordField($form,'confirmPassword',array('id'=>'confirmPassword','class'=>'tbox38 tbox38-1','placeholder'=>'确认密码','autocomplete'=>'off')); ?></li>
					<li>
						<label>
							<?php
								$form->agree = isset($form->agree) ? (int)$form->agree : 1;
								echo $active->checkBox($form,'agree',array('id'=>'agree'));
							?>
							<i>我已阅读并同意</i>
							<?php echo CHtml::link('《e办公商家注册协议》',$this->createUrl('service/index',array('id'=>13)),array('target'=>'_blank')); ?>
						</label>
					</li>
					<li><?php echo CHtml::submitButton('下一步' , array('class'=>'btn-1 btn-1-1','id'=>'submitRegisterBtn')); ?></li>
				</ul>
			<?php $this->endWidget(); ?>
		</fieldset>
	</section>
</main>
<script>
var _send_permission = {'merchant':null} , _code_permission = {'merchant':null};

$(document).ready(function(){
	//图形验证码
	$('.svcode').click(function(){
		$(this).attr('src' , '<?php echo $this->createUrl('asyn/getVcdoe'); ?>?type=merchant&_x='+Math.random());
	}).click();
	
	//验证码
	$('.code-verify>:text[ags]')
	.keydown(function(){
		if (!_send_permission.merchant)
		{
			$(this).val('').attr('disabled' , true);
			$('#phone').change();
		}else{
			$(this).removeAttr('disabled');
		}
	})
	.change(function(){
		var ex = this , _v = $(ex).val() , _ags = $(ex).attr('ags');
		$(ex).nextAll('span,q').remove();
		if ($.trim(_v) == '')
		{
			$(ex).next().after('<q class="promt error msg no-sms">请输入验证码</q>');
		}else{
			$.getJSON('<?php echo $this->createUrl('asyn/verifyVcode'); ?>' , {'code':_v,'ags':_ags} , function(json){
				if (json.code !== 0)
				{
					_code_permission[_ags] = false;
					$(ex).next().after('<q class="promt error msg no-sms">'+json.message+'</q>');
				}else{
					_code_permission[_ags] = true;
					$(ex).next().after('<q class="success"></q>');
				}
			});
		}
	});

	$('#sendBtn').sendVerification();

	$.validate.reg('repeat',function(val){return val === $('#password').val()});
	$('#companyRegister').validate(
	{
		rule : 
		{
			phone : {
				required : '手机号码不能为空',
				mobile : '手机号码不合法',
				promt : '请输入手机号，验证后，您可以用该手机号登录',
				callback : function(e){}
			},
			vcode : { promt : '请输入您收到的验证码'},
			password : {
				required : '密码不能为空',
				password : '以字母开头，长度在6~18之间，只能包含字符、数字和下划线',
				promt : '以字母开头，长度在6~18之间，只能包含字符、数字和下划线'
			},
			confirmPassword : {
				required : '密码不能为空',
				repeat : '两次密码输入不一致'
			}
		}
	});

	$('#agree').isAgreed({btn : '#submitRegisterBtn , .member-send'});

	//验证短信 - 个人
	$('.member-send').sendVerification({tel:'#phone' , 'stype':'merchant' , 'callback':function(self){
		var phone = $('#phone').val()||'';
		$(self).nextAll('span,q').remove();
		if (!(/^(13\d|18\d|14[57]|15[012356789]|17[0678])\d{8}$/.test(phone)))
		{
			$(self).after('<q class="promt error msg no-sms">手机号码不合法</q>');
			return false;
		}
		//send sms
		$.getJSON('<?php echo $this->createUrl('asyn/sendSmsCode'); ?>' , {'phone':phone , 'type':'merchant'} , function(json){
			
			if (json.code !== 0)
			{
				$(self).after('<q class="promt error msg no-sms">'+json.message+'</q>');
			}else{
				$(self).after('<span class="promt promt-tag">短信已发送成功!</span>');
			}
		});
	}});

	$('#companyRegister')
	//验证码改变
	.on('change' , '.verCode' , function(){
		var val = $(this).val() , phone = $('#phone').val()||'' , e = this;
		if (val == '' || phone == '')
			return false;

		$(e).nextAll('span,q').remove();
		//verify code
		$.getJSON('<?php echo $this->createUrl('asyn/verifySmsCode'); ?>' , {'phone':phone,'code':val,'type':'merchant'} , function(json){
			if (json.code !== 0)
			{
				$(e).next('a').after('<q class="promt error msg no-sms">'+json.message+'</q>');
			}else{
				$(e).next('a').after('<q class="success"></q>');
			}
		});
	})
	//验证是否已注册
	.on('change' , '#phone' , function()
	{
		var phone = $(this).val() || '' , e = this , _a = $(e).parent().next('li').children('a');
		$(e).nextAll('span,q').remove();
		if (!(/^(13\d|18\d|14[57]|15[012356789]|17[0678])\d{8}$/.test(phone)))
		{
			$(e).after('<q class="promt error msg no-sms">手机号码不合法</q>');
			return false;
		}

		$.getJSON('<?php echo $this->createUrl('asyn/verifyPhone'); ?>' , {'phone':phone,'type':'merchant'} , function(json)
		{
			$(e).nextAll('span,q').remove();
			if (json.code !== 0)
			{
				_send_permission.merchant = false;
				_a.addClass('disabled');
				$('.code-verify>:text[ags="merchant"]').attr('disabled' , true);
				$(e).after('<q class="promt error msg no-sms">'+json.message+'</q>');
			}else{
				_send_permission.merchant = true;
				_a.removeClass('disabled');
				$('.code-verify>:text[ags="merchant"]').removeAttr('disabled');
				$(e).after('<q class="success"></q>');
			}
		});
	});

	<?php if ($formError): ?>
	(function(){
		var formError = <?php echo json_encode($formError); ?> , code = '' , wr = '' , k = 0 , a = b = null;
		for (a in formError)
		{
			for (b in formError[a])
			{
				code += wr + (++k) + ' . ' + formError[a][b];
				wr = '<br />';
			}
		}
		layer.alert(code);
	})();
	<?php endif; ?>
});
</script>