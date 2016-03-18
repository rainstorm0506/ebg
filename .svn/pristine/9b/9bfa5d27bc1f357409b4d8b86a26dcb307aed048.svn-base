<?php
Views::css(array('register'));
Views::js(array('jquery.validate' , 'jquery.sendVerification' , 'jquery.isAgreed'));
Yii::app()->getClientScript()->registerCss('code-verify' , ".form-list .code-verify img{cursor:pointer;width:120px;height:40px;margin:0 0 0 10px}");
if ($this->isPost() && $formError)
	Yii::app()->getClientScript()->registerCoreScript('layer');
?>
	<main class="register-wraper">
		<aside><img src="images/banner/register-ad.png"></aside>
		<section id="registerWrap">
			<fieldset class="form-list" id="<?php echo ($pid==1||$pid==2) ? 'personRegister' : 'companyRegister';?>">
				<legend>快速注册</legend>
				<figure class="reg-fast-head">
					<img src="images/temp/01.png" width="80" height="80">
					<figcaption>张三李四<?php echo $pid;?></figcaption>
				</figure>				
				<div class="reg-fast-txt">
					<h6>只差一步，即可完成登录设置</h6>
					<p>非注册用户：快速完成账号创建，在个人中心设置密码后，可用手机号直接登录；</p>
					<p>已注册用户：关联手机号后，可用社交账号登录</p>
				</div>
				<?php if ($pid==1 || $pid==2) {?>
				<?php $active = $this->beginWidget('CActiveForm',array('action'=>$this->createUrl('passport/register',array('s'=>'member','code'=>$code)))); ?>
					<ul>
						<li><?php echo $active->textField($form,'member[phone]',array('id'=>'member_tel','class'=>'tbox38 tbox38-1','placeholder'=>'请输入手机号','autocomplete'=>'off')); ?></li>
						<li class="code-verify">
						<?php
							echo $active->textField($form , 'member[vxcode]' , array('ags'=>'member','placeholder'=>'请输入图形验证码','class'=>'tbox38 tbox38-2','maxlength'=>6,'style'=>'height:40px'));
							echo '<img ags="member" class="svcode">';
						?>
						</li>
						<li>
							<?php
								$form->member['code'] = empty($form->member['code']) ? '' : (int)$form->member['code'];
								echo $active->textField($form,'member[code]',array('id'=>'member_code','class'=>'verCode tbox38 tbox38-2','placeholder'=>'短信验证码','autocomplete'=>'off'));
							?>
							<a class="btn-2 sms-send member-send">获取短信验证码</a>
						</li>
						<li>
						<?php
							$form->member['reCode'] = empty($form->member['reCode']) ? $code : $form->member['reCode'];
							echo $active->textField($form,'member[reCode]',array('class'=>'tbox38 tbox38-1','placeholder'=>'请输入推荐码（非必填）','autocomplete'=>'off'));
						?>
						</li>
						<li>
							<label>
								<?php
									$form->member['agree'] = isset($form->member['agree']) ? (int)$form->member['agree'] : 1;
									echo $active->checkBox($form,'member[agree]',array('id'=>'member_agree'));
								?>
								<i>我已阅读并同意</i>
								<?php echo CHtml::link('《e办公个人用户注册协议》',$this->createUrl('service/index',array('id'=>11)),array('target'=>'_blank')); ?>
							</label>
						</li>
						<li><?php echo CHtml::submitButton('注册并登录' , array('class'=>'btn-1 btn-1-1','id'=>'memberSubmit')); ?></li>
					</ul>
				<?php $this->endWidget(); ?>
				<?php }elseif($pid==3) {?>
				<?php $active = $this->beginWidget('CActiveForm',array('action'=>$this->createUrl('',array('s'=>'enterprise','code'=>$code)))); ?>
					<ul>
						<li><?php echo $active->textField($form,'enterprise[phone]',array('id'=>'enterprise_tel','class'=>'tbox38 tbox38-1','placeholder'=>'请输入手机号','autocomplete'=>'off')); ?></li>
						<li class="code-verify">
						<?php
							echo $active->textField($form , 'enterprise[vxcode]' , array('ags'=>'enterprise','placeholder'=>'请输入图形验证码','class'=>'tbox38 tbox38-2' , 'maxlength'=>6 , 'style'=>'height:40px'));
							echo '<img ags="enterprise" class="svcode">';
						?>
						</li>
						<li>
							<?php
								$form->enterprise['code'] = empty($form->enterprise['code']) ? '' : (int)$form->enterprise['code'];
								echo $active->textField($form,'enterprise[code]',array('id'=>'enterprise_code','class'=>'verCode tbox38 tbox38-2','placeholder'=>'短信验证码','autocomplete'=>'off'));
							?>
							<a class="btn-2 sms-send enterprise-send">获取短信验证码</a>
						</li>
						<li>
						<?php
							$form->enterprise['reCode'] = empty($form->enterprise['reCode']) ? $code : $form->enterprise['reCode'];
							echo $active->textField($form,'enterprise[reCode]',array('class'=>'tbox38 tbox38-1','placeholder'=>'请输入推荐码（非必填）','autocomplete'=>'off'));
						?>
						</li>
						<li>
							<label>
								<?php
									$form->enterprise['agree'] = isset($form->enterprise['agree']) ? (int)$form->enterprise['agree'] : 1;
									echo $active->checkBox($form,'enterprise[agree]',array('id'=>'enterprise_agree'));
								?>
								<i>我已阅读并同意</i>
								<?php echo CHtml::link('《e办公企业用户注册协议》',$this->createUrl('service/index',array('id'=>12)),array('target'=>'_blank')); ?>
							</label>
						</li>
						<li><?php echo CHtml::submitButton('下一步' , array('class'=>'btn-1 btn-1-1','id'=>'enterpriseSubmit')); ?></li>
					</ul>
				<?php $this->endWidget(); ?>				
				<?php }?>
			</fieldset>
		</section>
	</main>
<script>
var _send_permission = {'member':null , 'enterprise':null} , _code_permission = {'member':null , 'enterprise':null};

$(document).ready(function(){
	<?php if ($pid==1 || $pid==2) { ?>
	$('#personRegister').validate({
		rule : {
			member_tel : {
				required : '手机号码不能为空',
				mobile : '手机号码不合法',
				promt : '请输入手机号，验证后，您可以用该手机号登录',
				callback : function(e){}
			},
			member_code : {
				promt : '请输入您收到的验证码'
			},
		}
	});
	<?php }elseif($pid==3){?>
	$('#companyRegister').validate({
		rule : {
			enterprise_tel : {
				required : '手机号码不能为空',
				mobile : '手机号码不合法',
				promt : '请输入手机号，验证后，您可以用该手机号登录',
				callback : function(e){}
			},
			enterprise_code : {
				promt : '请输入您收到的验证码'
			},
		}
	});
	<?php }?>
	//图形验证码
	$('.svcode').click(function(){
		$(this).attr('src' , '<?php echo $this->createUrl('asyn/getVcdoe'); ?>?type='+$(this).attr('ags')+'&_x='+Math.random());
	}).click();
	
	//验证码
	$('.code-verify>:text[ags]')
	.keydown(function(){
		var ags = $(this).attr('ags');
		if (!_send_permission[ags])
		{
			$(this).val('').attr('disabled' , true);
			$('#'+ags+'_tel').change();
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

	$.validate.reg('repeat',function(val){return val === $('#member_password').val();});
	$.validate.reg('repeat2',function(val){return val === $('#enterprise_password').val();});
	//验证短信 - 个人
	$('.member-send').sendVerification({tel:'#member_tel' , 'stype':'member' , 'callback':function(self){
		var phone = $('#member_tel').val()||'';
		$(self).nextAll('span,q').remove();
		if (!(/^(13\d|18\d|14[57]|15[012356789]|17[0678])\d{8}$/.test(phone)))
		{
			$(self).after('<q class="promt error msg no-sms">手机号码不合法</q>');
			return false;
		}
		//send sms
		$.getJSON('<?php echo $this->createUrl('asyn/sendSmsCode'); ?>' , {'phone':phone , 'type':'member'} , function(json){
			
			if (json.code !== 0)
			{
				$(self).after('<q class="promt error msg no-sms">'+json.message+'</q>');
			}else{
				$(self).after('<span class="promt promt-tag">短信已发送成功!</span>');
			}
		});
	}});
	//验证短信 - 企业
	$('.enterprise-send').sendVerification({tel:'#enterprise_tel' , 'stype':'enterprise' , 'callback':function(self){
		var phone = $('#enterprise_tel').val()||'';
		$(self).nextAll('span,q').remove();
		if (!(/^(13\d|18\d|14[57]|15[012356789]|17[0678])\d{8}$/.test(phone)))
		{
			$(self).after('<q class="promt error msg no-sms">手机号码不合法</q>');
			return false;
		}
		//send sms
		$.getJSON('<?php echo $this->createUrl('asyn/sendSmsCode'); ?>' , {'phone':phone , 'type':'enterprise'} , function(json){
			
			if (json.code !== 0)
			{
				$(self).after('<q class="promt error msg no-sms">'+json.message+'</q>');
			}else{
				$(self).after('<span class="promt promt-tag">短信已发送成功!</span>');
			}
		});
	}});

	//同意协议
	$('#member_agree').isAgreed({btn : '#memberSubmit , .member-send'});
	$('#enterprise_agree').isAgreed({btn : '#enterpriseSubmit , .enterprise-send'});

	$('#registerWrap')
	//切换
	.on('click' , '#controllBtn>a' , function()
	{
		$(this).addClass('current').siblings('a').removeClass('current');
		$('fieldset.form-list').hide().eq($(this).index()).fadeIn(1000);
	})
	//验证码改变
	.on('change' , '.verCode' , function(){
		var val = $(this).val() ,
			_id = $(this).attr('id') ,
			phone = $(this).closest('ul').find('input:text[name$="[phone]"]').val()||'' ,
			recode = $(this).closest('ul').find('input:text[name$="[reCode]"]'),
			e = this;

		if (val == '' || phone == '')
			return false;

		$(e).nextAll('span,q').remove();

		_id = _id.substr(0,6) === 'member' ? 'member' : 'enterprise';
		//verify code
		$.getJSON('<?php echo $this->createUrl('asyn/verifySmsCode'); ?>' , {'phone':phone,'code':val,'type':_id} , function(json){
			if (json.code !== 0)
			{
				$(e).next('a').after('<q class="promt error msg no-sms">'+json.message+'</q>');

			}else{
				$(e).next('a').after('<q class="success"></q>');
				// 获取推荐码
				$.getJSON('<?php echo $this->createUrl('asyn/getReCode'); ?>', {phone:phone}, function(res){
					if (res.code == 0) {
						if (res.data.code) {
							recode.val(res.data.code).attr('readonly', 'readonly');
						}
					}
				});
			}
		});
	})
	//验证是否已注册
	.on('change' , '#member_tel , #enterprise_tel' , function()
	{
		var phone = $(this).val() || '' , _id = $(this).attr('id') , e = this , _a = $(e).parent().next('li').children('a');
		$(e).nextAll('span,q').remove();
		if (!(/^(13\d|18\d|14[57]|15[012356789]|17[0678])\d{8}$/.test(phone)))
		{
			$(e).after('<q class="promt error msg no-sms">手机号码不合法</q>');
			return false;
		}
		
		_id = _id.substr(0 , 6) === 'member' ? 'member' : 'enterprise';
		$.getJSON('<?php echo $this->createUrl('asyn/verifyPhone'); ?>' , {'phone':phone,'type':_id} , function(json)
		{
			$(e).nextAll('span,q').remove();
			if (json.code !== 0)
			{
				_send_permission[_id] = false;
				_a.addClass('disabled');
				$('.code-verify>:text[ags="'+_id+'"]').attr('disabled' , true);
				$(e).after('<q class="promt error msg no-sms">'+json.message+'</q>');
			}else{
				_send_permission[_id] = true;
				_a.removeClass('disabled');
				$('.code-verify>:text[ags="'+_id+'"]').removeAttr('disabled');
				$(e).after('<q class="success"></q>');
			}
		});
	});

	<?php
		if ($form->signType == 'member')
		{
			echo "$('#controllBtn>a.member').click();";
		}else{
			echo "$('#controllBtn>a.enterprise').click();";
		}
	?>

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