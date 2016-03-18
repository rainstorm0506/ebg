<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<?php Views::css(array('e-pei'));Views::js(array('html5')); ?>
</head>
<body>
	<main class="error-404">
		<?php
			if (isset($error['errorCode']) && $error['errorCode'] == -789)
			{
				echo '<h6>'.$error['message'].'</h6>';
			}else{
				echo '<h5>404</h5><h6>抱歉，你访问的页面不存在</h6>';
			}
		?>
		<p>请稍后再试，或者联系e办公客户：4000-456-423</p>
		<dl>
			<dt><span id="node" class="mc"></span> 秒之后页面自动跳转，你还可以：</dt>
			<dd>1）<?php echo CHtml::link('返回首页' , $this->createUrl('home/index')); ?></dd>
			<dd class="last">
				2）去其他地方逛逛：
				<?php
					echo CHtml::link('逛一逛' , $this->createUrl('stroll/index'));
					echo CHtml::link('企业采购' , $this->createUrl('purchase/index'));
					echo CHtml::link('积分商城' , $this->createUrl('credits/index'));
					echo CHtml::link('二手市场' , $this->createUrl('used/index'));
				?>
			</dd>
		</dl>
		<i></i>
	</main>
</body>
</html>
<script>
var _x = null;
window.onload = function()
{
	var node = document.getElementById('node') , _v = 4 , _fun = function(){
		_v = _v - 1;
		if (_v <= 0)
		{
			clearTimeout(_x);
			window.location.href = '<?php echo $this->createUrl('home/index'); ?>';
		}
		node.innerHTML = _v;
		_x = window.setTimeout(_fun , 1000);
	};
	_fun();
};
</script>