<?php
Views::css(array('register'));
Views::js(array('jquery.validate' , 'jquery.sendVerification' , 'jquery.isAgreed'));
Yii::app()->getClientScript()->registerCss('code-verify' , ".form-list .code-verify img{cursor:pointer;width:120px;height:40px;margin:0 0 0 10px}");
if ($this->isPost() && $formError)
	Yii::app()->getClientScript()->registerCoreScript('layer');
?>
<main class="register-wraper">
	<aside><img src="<?php echo Views::imgShow('images/banner/register-ad.png'); ?>"></aside>
	<section id="registerWrap">
		<fieldset class="form-list">
			<legend>快速注册</legend>
			<figure class="reg-fast-head">
				<img src="<?php echo $third['face'];?>" width="80" height="80">
				<figcaption><?php echo $third['nickname'];?></figcaption>
			</figure>				
			<div class="reg-fast-txt">
				<h6>只差一步，即可完成登录设置</h6>
				<p>非注册用户：快速完成账号创建，在个人中心设置密码后，可用手机号直接登录；</p>
				<p>已注册用户：关联手机号后，可用社交账号登录</p>
			</div>
			<?php $active = $this->beginWidget('CActiveForm', array('id'=>'thirdForm','enableAjaxValidation'=>true)); ?>
			<ul>
				<li>
				<?php
					echo $active->textField($form,'phone',array('id'=>'phone','class'=>'tbox38 tbox38-1','placeholder'=>'请输入手机号','autocomplete'=>'off'));
					echo $active->hiddenField($form,'isReg',array('id'=>'isReg'));
				?>
				</li>
				<li class="code-verify">
				<?php
					$form->vxcode = '';
					echo $active->textField($form , 'vxcode' , array('id'=>'vxcode','ags'=>'third','placeholder'=>'请输入图形验证码','class'=>'tbox38 tbox38-2','maxlength'=>6,'style'=>'height:40px'));
					echo '<img ags="member" class="svcode">';
				?>
				</li>
				<li>
				<?php
					$form->smscode = '';
					echo $active->textField($form,'smscode',array('id'=>'smscode','class'=>'verCode tbox38 tbox38-2','placeholder'=>'短信验证码','autocomplete'=>'off'));
					echo '<a class="btn-2 sms-send">获取短信验证码</a>';
				?>
				</li>
				<li class="pxx" style="margin-top:-20px;display:none;">
				<?php
					echo '<p style="color:#d00f2b">您已注册，请输出密码验证登录。</p>';
					echo $active->passwordField($form,'password',array('class'=>'tbox38 tbox38-1','placeholder'=>'请输入您的密码','autocomplete'=>'off' , 'style'=>'clear:both'));
				?>
				</li>
				<li><?php echo $active->textField($form,'recode',array('class'=>'tbox38 tbox38-1','placeholder'=>'请输入推荐码（非必填）','autocomplete'=>'off')); ?></li>
				<li>
					<label>
					<?php
						echo $active->checkBox($form,'agree',array('id'=>'agree'));
						echo '<i>我已阅读并同意</i>';
						if ($third['seat'] == 1)
						{
							echo CHtml::link('《e办公用户注册协议》',$this->createUrl('service/index',array('id'=>11)),array('target'=>'_blank'));
						}else{
							echo CHtml::link('《e办公商家注册协议》',$this->createUrl('service/index',array('id'=>13)),array('target'=>'_blank'));
						}
					?>
					</label>
				</li>
				<li><?php echo CHtml::submitButton($third['seat']==1?'注册并登录':'下一步',array('class'=>'btn-1 btn-1-1 btn-disabled','id'=>'thirdSubmit')); ?></li>
			</ul>
			<?php $this->endWidget(); ?>
		</fieldset>	
	</section>
</main>
<script>
var _send_permission = {'third':true} , _code_permission = {'third':null};
$(document).ready(function()
{
	$('#thirdForm').validate({
		rule : {
			phone : {
				required : '手机号码不能为空',
				mobile : '手机号码不合法',
				promt : '请输入手机号，验证后，您可以用该手机号登录'
			},
			vxcode : {
				required : '图像验证码不能为空',
				promt : '请输入您看到的图像验证码',
				callback : function(){}
			},
			smscode : {
				required : '短信验证码不能为空',
				promt : '请输入您收到的验证码',
				callback : function(){}
			}
		}
	});

	//检查手机号码是否已注册
	$('#phone').change(function()
	{
		$.getJSON('<?php echo $this->createUrl('asyn/verifyPhone'); ?>' , {'phone':$(this).val(),'type':'<?php echo $third['seat']==2 ? 'merchant':''; ?>'} , function(json){
			if (json.code === 2)
			{
				$('.pxx').show().next().hide();
				$('#isReg').val(1);
			}else{
				$('.pxx').hide().next().show();
				$('#isReg').val(0);
			}
		});
	});

	//同意协议
	$('#agree').click(function(){$('#agree').isAgreed({btn : '#thirdSubmit'})});

	//图形验证码
	$('.svcode').click(function(){
		$(this).attr('src' , '<?php echo $this->createUrl('asyn/getVcdoe'); ?>?type=third&_x='+Math.random());
	}).click();

	//验证码
	$('.code-verify>:text[ags]')
	.keydown(function(){
		if (!_send_permission.third)
		{
			$(this).val('').attr('disabled' , true);
			$('#phone').change();
		}else{
			$(this).removeAttr('disabled');
		}
	})
	.change(function(){
		var ex = this , _v = $(ex).val();
		$(ex).nextAll('span,q').remove();
		if ($.trim(_v) == '')
		{
			$(ex).next().after('<q class="promt error msg no-sms">请输入验证码</q>');
		}else{
			$.getJSON('<?php echo $this->createUrl('asyn/verifyVcode'); ?>' , {'code':_v,'ags':'third'} , function(json){
				if (json.code !== 0)
				{
					_code_permission.third = false;
					$(ex).next().after('<q class="promt error msg no-sms">'+json.message+'</q>');
				}else{
					_code_permission.third = true;
					$(ex).next().after('<q class="success"></q>');
				}
			});
		}
	});

	//异步发送短信
	$('.sms-send').sendVerification({tel:'#phone' , 'stype':'third' , 'callback':function(e){
		var phone = $('#phone').val()||'';
		$(e).nextAll('span,q').remove();
		if (!(/^(13\d|18\d|14[57]|15[012356789]|17[0678])\d{8}$/.test(phone)))
		{
			$(e).after('<q class="promt error msg no-sms">手机号码不合法</q>');
			return false;
		}
		//send sms
		$.getJSON('<?php echo $this->createUrl('asyn/sendSmsCode'); ?>' , {'phone':phone , 'type':'third'} , function(json){
			if (json.code !== 0)
			{
				$(e).after('<q class="promt error msg no-sms">'+json.message+'</q>');
			}else{
				$(e).after('<span class="promt promt-tag">短信已发送成功!</span>');
			}
		});
	}});

	$('#registerWrap')
	//异步验证短信
	.on('change' , '.verCode' , function(){
		var val = $(this).val() ,
			phone = $('#phone').val()||'';
			e = this;

		if (val == '' || phone == '')
			return false;

		$(e).nextAll('span,q').remove();
		//verify code
		$.getJSON('<?php echo $this->createUrl('asyn/verifySmsCode'); ?>' , {'phone':phone,'code':val,'type':'third'} , function(json){
			if (json.code !== 0)
			{
				$(e).next('a').after('<q class="promt error msg no-sms">'+json.message+'</q>');
			}else{
				$(e).next('a').after('<q class="success"></q>');
			}
		});
	});

	<?php echo $form->isReg ? "$('.pxx').show().next().hide();" : ''; ?>

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