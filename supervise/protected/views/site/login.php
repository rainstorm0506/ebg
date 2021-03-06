<!doctype html>
<html>
<head>
<meta charset="utf-8">
<?php Views::css(array('admin' , 'extension'));Views::jquery();Views::js('html5'); ?>
<title>登录</title>
</head>
<body>
<div id="login">
	<header><i></i></header>
	<section>
		<?php $active = $this->beginWidget('CActiveForm', array('id'=>'login-form' , 'htmlOptions'=>array('class'=>'form-wraper'))); ?>
			<ul>
                                <li>
                                        <?php 
                                                echo $active->error($form , 'status' , array('style'=>'padding:0 0 0 50px;color:red;font-weight:bold;'));
                                        ?>
                                </li>
				<li>
					<span><label>用户名：</label></span>
					<?php
						echo $active->textField($form , 'account' , array('class'=>'textbox'));
						echo $active->error($form , 'account' , array('style'=>'padding:0 0 0 50px'));
					?>
				</li>
				<li>
					<span>
					<label>密<i>&nbsp; &nbsp;</i>码：</label></span>
					<?php
						echo $active->passwordField($form , 'password' , array('class'=>'textbox'));
						echo $active->error($form , 'password' , array('style'=>'padding:0 0 0 50px'));
					?>
				</li>
				<li class="code">
					<span><label>验证码：</label></span>
					<?php
						echo $active->textField($form , 'codes' , array('class' => 'textbox' , 'maxlength' => 6 , 'style'=>'height:40px'));
						$active->widget('CCaptcha',array('showRefreshButton'=>false,'clickableImage'=>true,'imageOptions'=>array('style'=>'cursor:pointer;width:135px;height:40px')));
						echo $active->error($form , 'codes' , array('style'=>'padding:0 0 0 50px;clear:both'));
					?>
				</li>
				<li>
					<span>&nbsp;</span>
					<?php
						echo CHtml::submitButton('登 录' , array('class' => 'btn-1'));
						echo CHtml::resetButton('重 填' , array('class' => 'btn-1' , 'id'=>'reset'));
					?>
				</li>
			</ul>
		<?php $this->endWidget(); ?>
	</section>
</div>
</body>
</html>

<script>
if (window.top != window.self)
	window.top.location.href = window.location.href;

window.onload = function()
{
	//重置清空
	document.getElementById('reset').onclick = function()
	{
		var input = document.getElementsByTagName('input') , g = input.length , attr = '';
		if (g > 0)
		{
			for (var i = 0 ; i < g ; i++)
			{
				attr = input[i].getAttribute('type');
				if (attr == 'text' || attr == 'password')
					input[i].setAttribute('value' , '');
			}
		}
	};

	//刷新要改变验证码的需求...
	document.getElementsByClassName('code').item(0).getElementsByTagName('img').item(0).click();
};
</script>