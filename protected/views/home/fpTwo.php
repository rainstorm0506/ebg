<?php
Views::css(array('register'));
Views::js(array('jquery.validate'));

if ($this->isPost() && $formError)
	Yii::app()->getClientScript()->registerCoreScript('layer');
?>
<main class="find-wraper">
	<h3>找回密码</h3>
	<ul class="shop-process shop-process-1">
		<li class="current first pass"><b></b><em>1</em><i></i><p>验证身份</p></li>
		<li class="current"><b></b><em>2</em><i></i><p>修改登录密码</p></li>
		<li><b></b><em>3</em><i></i><p>完成</p></li>
	</ul>
	<fieldset class="form-list crbox18-group set-password-form">
		<legend>设置登录密码</legend>
		<?php $active = $this->beginWidget('CActiveForm' , array('id'=>'findPassword')); ?>
			<ul>
				<li>
					<h6>新的登录密码：</h6>
					<?php echo $active->passwordField($form,'password',array('id'=>'password','class'=>'tbox38 tbox38-1','placeholder'=>'输入密码','autocomplete'=>'off')); ?>
				</li>
				<li>
					<h6>请再次输入新密码：</h6>
					<?php echo $active->passwordField($form,'confirmPassword',array('id'=>'confirmPassword','class'=>'tbox38 tbox38-1','placeholder'=>'确认密码','autocomplete'=>'off')); ?>
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
$(document).ready(function()
{
	$('#findPassword').validate(
	{
		rule : 
		{
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