<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php
	Views::css(array('login'));
	Views::jquery();
	Views::js(array('html5'));
	Yii::app()->getClientScript()->registerCss('default' , 'a,input[type="submit"]{cursor:pointer}');

	if ($this->isPost() && $formError)
		Yii::app()->getClientScript()->registerCoreScript('layer');
?>
</head>
<body class="bg-null">
	<!-- login -->
	<section class="pop-promt login-box" id="loginWrap">
		<nav id="controllBtn">
			<a class="current">个人/企业登录<i class="t-t"></i></a>
			<a>商家登录<i class="t-t"></i></a>
		</nav>
		<fieldset class="form-list" id="personLogin">
			<legend>个人/企业登录</legend>
			<?php $active = $this->beginWidget('CActiveForm'); ?>
			<?php echo $active->hiddenField($form,'type',array('value'=>0)); ?>
				<div class="wrap">
					<span class="promt error msg">输入的用户名不存在，请核对后再重新输入</span>
				</div>
				<ul>
					<li><?php echo $active->textField($form,'userPhone',array('class'=>'tbox38','placeholder'=>'手机号','autocomplete'=>'off')); ?></li>
					<li><?php echo $active->passwordField($form,'password',array('class'=>'tbox38','placeholder'=>'输入密码','autocomplete'=>'off')); ?></li>
				</ul>
				<div>
					<label><p><?php echo CHtml::link('注册新账号',$this->createUrl('home/sign',array('s'=>'member')) , array('class'=>'pop-link')); ?></p></label>
					<span><?php echo CHtml::link('忘记密码？',$this->createUrl('home/findPassword') , array('class'=>'pop-link')); ?></span>
				</div>
				<?php echo CHtml::submitButton('登  录' , array('class'=>'btn-1')); ?>
			<?php $this->endWidget(); ?>
		</fieldset>
		<fieldset class="form-list dn" id="companyLogin">
			<legend>商家登录</legend>
			<?php $active = $this->beginWidget('CActiveForm'); ?>
			<?php echo $active->hiddenField($form,'type',array('value'=>1)); ?>
				<div class="wrap">
					<span class="promt error msg">输入的用户名不存在，请核对后再重新输入</span>
				</div>
				<ul>
					<li><?php echo $active->textField($form,'userPhone',array('class'=>'tbox38','placeholder'=>'手机号','autocomplete'=>'off')); ?></li>
					<li><?php echo $active->passwordField($form,'password',array('class'=>'tbox38','placeholder'=>'输入密码','autocomplete'=>'off')); ?></li>
				</ul>
				<div>
					<label><p><?php echo CHtml::link('注册成为商家',$this->createUrl('home/merSign')); ?></p></label>
					<span><?php echo CHtml::link('忘记密码？',$this->createUrl('home/findPassword')); ?></span>
				</div>
				<?php echo CHtml::submitButton('登  录' , array('class'=>'btn-1')); ?>
			<?php $this->endWidget(); ?>
		</fieldset>
		<a class="close-btn-2" id="close" href="javascript:;"></a>
	</section>
	<div class="mask"></div>
</body>
</html>
<script>
var allow = {'phone':false , 'password':false};
$(document).ready(function()
{
	$('a.pop-link').click(function()
	{
		if (window.top)
		{
			window.top.location.href = $(this).attr('href');
			return false;
		}
	});
	
	$('#loginWrap')
	.on('click' , 'a.close-btn-2' , function(){
		window.top.userLoginPop && window.top.userLoginPop.remove();
	})
	//切换
	.on('click' , '#controllBtn>a' , function()
	{
		$(this).addClass('current').siblings('a').removeClass('current');
		$('fieldset.form-list').hide().eq($(this).index()).fadeIn(1000);
	})
	.on('blur' , ':text' , function()
	{
		var phone = $(this).val()||'' , span = $(this).closest('ul').prev('div.wrap').children('span');
		allow.phone = false;
		if (!phone)
		{
			span.text('请输入手机号码').show();
		}else if (!(/^(13\d|18\d|14[57]|15[012356789]|17[0678])\d{8}$/.test(phone))){
			span.text('手机号码不合法').show();
		}else{
			allow.phone = true;
			allow.password && span.text('').hide();
		}
	})
	.on('blur' , ':password' , function()
	{
		var password = $(this).val()||'' , span = $(this).closest('ul').prev('div.wrap').children('span');
		allow.password = false;
		if (!password)
		{
			span.text('请输入密码').show();
		}else{
			allow.password = true;
			allow.phone && span.text('').hide();
		}
	})
	//登录
	.on('submit' , 'form' , function()
	{
		$(this).find(':password').blur();
		$(this).find(':text').blur();

		return (allow.phone && allow.password);
	});

	<?php
		$form->type = $form->type ? (int)$form->type : (!empty($_GET['s']) && $_GET['s'] == 'merchant' ? 1 : 0);
		echo "$('#controllBtn>a:eq({$form->type})').click();";
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