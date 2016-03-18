<?php Views::css(array('register')); ?>
<main class="find-wraper">
	<h3>找回密码</h3>
	<ul class="shop-process shop-process-1">
		<li class="current first pass"><b></b><em>1</em><i></i><p>验证身份</p></li>
		<li class="current pass"><b></b><em>2</em><i></i><p>修改登录密码</p></li>
		<li class="current"><b></b><em>3</em><i></i><p>完成</p></li>
	</ul>
	<div class="mod-success">
		<div></div>
		<p>恭喜您，修改密码成功</p>
		<footer><?php echo CHtml::link('请立即登录',$this->createUrl('home/login')); ?></footer>
	</div>
</main>