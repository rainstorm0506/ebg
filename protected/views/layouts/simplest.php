<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="Keywords" content="<?php echo CHtml::encode($this->pageKeywords); ?>">
<meta name="Description" content="<?php echo CHtml::encode($this->pageDescription); ?>">
<link rel='shortcut icon' href='<?php echo Views::imgShow('images/favicon.ico'); ?>' type='image/x-icon'/>
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<?php
	Views::jquery();
	Views::js(array('html5'));
?>
</head>
<body>
<section class="header-nav">
	<div>
		<aside>
			<span><?php echo CHtml::link('<em>e办公首页<b>.</b>电脑城在线</em>' , $this->createUrl('home/index')); ?></span>
			<i>|</i>
			<span>欢迎来到e办公<b>.</b>电脑城在线</span>
		</aside>

		<nav>
			<?php if ($this->viewsUserID): ?>
			<div class="s-wrap">
				<h5><span><?php echo $this->viewsUserName; ?></span><s class="tr-b"><i></i><b></b></s></h5>
				<dl>
				<?php
					$_oix = '';
					switch ($this->getUserType())
					{
						case 1:
							$_oix = '<span>'.CHtml::link('我的订单' , $this->createUrl('member/order/index') , array('rel'=>'nofollow')).'</span><i>|</i>';
							echo '<dd>'.CHtml::link('升级企业用户' , $this->createUrl('member/esEnterprise/index') , array('rel'=>'nofollow')).'</dd>';
							echo '<dd>'.CHtml::link('我的收藏' , $this->createUrl('member/myCollection/index') , array('rel'=>'nofollow')).'</dd>';
							echo '<dd>'.CHtml::link('个人中心' , $this->createUrl('member/home/index') , array('rel'=>'nofollow')).'</dd>';
						break;
						case 2:
							echo '<dd>'.CHtml::link('集采订单' , $this->createUrl('enterprise/purchase/index') , array('rel'=>'nofollow')).'</dd>';
							echo '<dd>'.CHtml::link('企业收藏' , $this->createUrl('enterprise/myCollection/index') , array('rel'=>'nofollow')).'</dd>';
							echo '<dd>'.CHtml::link('企业中心' , $this->createUrl('enterprise/home/index') , array('rel'=>'nofollow')).'</dd>';
						break;
						case 3:
							$_oix = '<span>'.CHtml::link('管理订单' , $this->createUrl('merchant/order/index') , array('rel'=>'nofollow')).'</span><i>|</i>';
							echo '<dd>'.CHtml::link('我的首页' , $this->createUrl('merchant/home/index') , array('rel'=>'nofollow')).'</dd>';
							echo '<dd>'.CHtml::link('商家中心' , $this->createUrl('merchant/order/index') , array('rel'=>'nofollow')).'</dd>';
							echo '<dd>'.CHtml::link('商品管理' , $this->createUrl('merchant/goods/index') , array('rel'=>'nofollow')).'</dd>';
						break;
					}
					echo '<dd>'.CHtml::link('退出' , $this->createUrl('home/logout') , array('rel'=>'nofollow')).'</dd>';
				?>
				</dl>
			</div><i>|</i>
			<?php echo $_oix; else: ?>
			<em><?php $this->widget('LoginWidget'); ?></em>
			<em><?php echo CHtml::link('免费注册' , $this->createUrl('home/sign') , array('rel'=>'nofollow')); ?></em>
			<span><?php echo CHtml::link('我要开店' , $this->createUrl('home/merSign') , array('rel'=>'nofollow')); ?></span><i>|</i>
			<?php endif; ?>
			<span><?php echo CHtml::link('企业采购' , $this->createUrl('purchase/index') , array('rel'=>'nofollow')); ?></span><i>|</i>
			<span><?php echo CHtml::link('e维修' , $this->createUrl('maintain/index') , array('rel'=>'nofollow')); ?></span><i>|</i>
			<span><?php echo CHtml::link('e配送' , $this->createUrl('dispatching/index') , array('rel'=>'nofollow')); ?></span><i>|</i>
			<span><?php echo CHtml::link('服务中心' , $this->createUrl('service/index') , array('rel'=>'nofollow')); ?></span>
		</nav>
	</div>
</section>
<?php echo $content; ?>
</body>
</html>
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?29b3f43ee03f603e146840b4cc00db52";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>