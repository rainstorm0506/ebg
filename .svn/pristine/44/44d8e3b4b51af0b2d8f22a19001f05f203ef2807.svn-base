<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php
	Views::css(array('shopping'));
	Views::jquery();
	Views::js(array('html5'));
	Yii::app()->getClientScript()->registerCss('default' , 'a,input[type="submit"]{cursor:pointer}');
?>
</head>
<body class="bg-null">
	<!-- login -->
	<section class="pop-promt pop-promt-a">
		<a id="close" class="close-btn-2"></a>
		<section class="pay-fault">
			<i></i>
			<article>
				<h6>请您在新打开的页面上完成付款</h6>
				<p>付款完成前请不要关闭此窗口。</p>
				<p>完成付款后请根据您的情况点击下面的按钮</p>
				<nav>
				<?php
					echo CHtml::link('已完成付款' , $this->createUrl('pay/finish' , array('osn'=>$osn)) , array('class'=>'btn-1 btn-1-13 mr10px'));
					echo CHtml::link('付款遇到问题' , $this->createUrl('service/index' , array('id'=>5)) , array('class'=>'btn-1 btn-1-13','target'=>'_blank'));
				?>
				</nav>
				<footer><?php echo CHtml::link('返回选择其他支付方式' , null , array('id'=>'cancel')); ?></footer>
			</article>
		</section>		
	</section>
	<div class="mask"></div>
	<script src="js/jquery-popClose.js"></script>
</body>
</html>
<script>
$(document).ready(function()
{
	$('#close').click(function()
	{
		(window.parent != window) && window.parent.popObj.remove();
	});

	$('a[href],#cancel').click(function()
	{
		if (window.parent != window)
		{
			if ($(this).attr('id') == 'cancel')
				window.top.closeCancel();
			else
				window.top.location.href = $(this).attr('href');
		}
	});
});
</script>