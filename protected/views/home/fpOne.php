<?php
Views::css(array('register'));
Views::js(array('jquery.validate' , 'jquery.sendVerification'));
Yii::app()->getClientScript()->registerCss('code-verify' , ".form-list .code-verify img{cursor:pointer;width:120px;height:40px;margin:0 0 0 10px}");

if ($this->isPost() && $formError)
	Yii::app()->getClientScript()->registerCoreScript('layer');
?>
<style type="text/css">
.utcx span{padding:13px 0 0 0}
.utcx label,.utcx input{float:left;line-height:30px;display:inline-block;}
.utcx label{padding:0 0 0 5px}
</style>
<main class="find-wraper">
	<h3>找回密码</h3>
	<ul class="shop-process shop-process-1">
		<li class="current first"><b></b><em>1</em><i></i><p>验证身份</p></li>
		<li><b></b><em>2</em><i></i><p>修改登录密码</p></li>
		<li><b></b><em>3</em><i></i><p>完成</p></li>
	</ul>
	<fieldset class="form-list crbox18-group">
		<legend>设置登录密码</legend>
		<?php $active = $this->beginWidget('CActiveForm' , array('id'=>'findPassword')); ?>
			<ul>
				<li class="utcx">
					<h6>账号类型：</h6>
					<?php
						$form->ut = $form->ut ? $form->ut : 'member';
						echo $active->radioButtonList($form,'ut',array('member'=>'会员用户','enterprise'=>'企业用户','merchant'=>'商家用户'), array('separator'=>''));
					?>
				</li>
				<li>
					<h6>输入手机号：</h6>
					<?php echo $active->textField($form,'phone',array('id'=>'phone','class'=>'tbox38 tbox38-1','placeholder'=>'验证手机','autocomplete'=>'off')); ?>
				</li>
				<li class="code-verify">
					<h6>输入验证码：</h6>
					<?php
						echo $active->textField($form , 'vxcode' , array('ags'=>'find','class'=>'tbox38 tbox38-2','placeholder'=>'请输入图形验证码','maxlength'=>6,'style'=>'height:40px'));
						echo '<img ags="member" class="svcode">';
					?>
				</li>
				<li>
					<h6>请填写手机校验码：</h6>
					<?php
						$_disabled = true;
						if (!empty($form->vcode))
						{
							$form->vcode = (int)$form->vcode;
							$_disabled = false;
						}else{
							$form->vcode = '';
						}
						echo $active->textField($form,'vcode',array('id'=>'vcode','class'=>'verCode tbox38 tbox38-2','disabled'=>$_disabled,'placeholder'=>'短信验证码','autocomplete'=>'off'));
					?>
					<a class="btn-2 sms-send member-send">获取短信验证码</a>
				</li>
				<li>
					<h6>&nbsp;</h6>
					<?php echo CHtml::submitButton('下一步' , array('class'=>'btn-1 btn-1-1')); ?>
				</li>
			</ul>
		<?php $this->endWidget(); ?>
	</fieldset>
</main>
<script>
var _send_permission = {'find':true} , _code_permission = {'find':null};

$(document).ready(function()
{
	//图形验证码
	$('.svcode').click(function(){
		$(this).attr('src' , '<?php echo $this->createUrl('asyn/getVcdoe'); ?>?type=find&_x='+Math.random());
	}).click();

	//验证码
	$('.code-verify>:text[ags]')
	.change(function(){
		var ex = this , _v = $(ex).val();
		$(ex).nextAll('span,q').remove();
		if ($.trim(_v) == '')
		{
			$(ex).next().after('<q class="promt error msg no-sms">请输入验证码</q>');
		}else{
			$.getJSON('<?php echo $this->createUrl('asyn/verifyVcode'); ?>' , {'code':_v,'ags':'find'} , function(json){
				if (json.code !== 0)
				{
					_code_permission.find = false;
					$(ex).next().after('<q class="promt error msg no-sms">'+json.message+'</q>');
				}else{
					_code_permission.find = true;
					$(ex).next().after('<q class="success"></q>');
				}
			});
		}
	});

	//验证短信
	$('.member-send').sendVerification({tel:'#phone' , 'stype':'find' , 'callback':function(self){
		var phone = $('#phone').val()||'';
		$(self).nextAll('span,q').remove();
		if (!(/^(13\d|18\d|14[57]|15[012356789]|17[0678])\d{8}$/.test(phone)))
		{
			$(self).after('<q class="promt error msg no-sms">手机号码不合法</q>');
			return false;
		}
		var type = $('.utcx>span>:radio:checked').val();
		//send sms
		$.getJSON('<?php echo $this->createUrl('asyn/sendSmsCode'); ?>' , {'phone':phone,'type':type,'nt':'find'} , function(json){
			if (json.code !== 0)
			{
				$(self).after('<q class="promt error msg no-sms">'+json.message+'</q>');
			}else{
				$(self).after('<span class="promt promt-tag">短信已发送成功!</span>');
			}
		});
	}});

	$('#findPassword').validate(
	{
		rule :
		{
			phone :
			{
				required : '手机号码不能为空',
				mobile : '手机号码不合法',
				promt : '请输入手机号，验证后，您可以用该手机号登录'
			},
			vcode : { promt : '请输入您收到的验证码'}
		}
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