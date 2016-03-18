<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="Keywords" content="<?php echo CHtml::encode($this->pageKeywords); ?>">
<meta name="Description" content="<?php echo CHtml::encode($this->pageDescription); ?>">
<link rel='shortcut icon' href='<?php echo Views::imgShow('images/favicon.ico'); ?>' type='image/x-icon' />
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<?php
	Views::css(array('default'));
	Views::jquery();
	Views::js(array('html5' , 'jquery.kefu' , 'jquery.goTop'));
	Yii::app()->getClientScript()->registerCss('default' , 'a,input[type="submit"]{cursor:pointer}.nav-wrap > div > nav nav a:hover{background-color:#e95667;color:#FFF}');
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
	<section class="header">
		<div>
			<h1 class="logo"><?php echo CHtml::link('<i>E办公</i>' , $this->createUrl('home/index')); ?></h1><i></i>
			<a href="<?php echo $this->createUrl('cart/index'); ?>">
				<div class="shopping-cart-btn">
					<b></b><span>我的购物车</span><i>&gt;</i>
					<?php echo '<em id="cartCount"'.($this->viewsCartCount>0?'':'style="display:none"').'>'.$this->viewsCartCount.'</em>'; ?>
				</div>
			</a>
			<?php
				$this->beginWidget('CActiveForm', array('action'=>$this->createUrl('class/search'),'method'=>'get','htmlOptions'=>array('class'=>'search-wrap')));
				echo CHtml::textField ('keyword' , $this->viewsKeyword , array('class' => 'tbox38'));
				echo CHtml::submitButton('搜索' , array('name'=>null));
				$this->endWidget();
			?>
		</div>
	</section>
	<?php if ($this->mainLayoutNav): ?>
	<section class="nav-wrap nav-fold">
		<div>
			<aside>
				<h2><span>全部商品分类</span><s class="tr-t"><i></i><b></b></s></h2>
				<?php echo GlobalGoodsClass::getHtmlCode(false); ?>
			</aside>
			<nav<?php echo ($this->viewsUserID) ? ' class="nav-6"' : ''; ?>>
			<?php
				echo CHtml::link('商品首页' , $this->createUrl('class/index') , Views::linkClass('class','index',array(),array('target'=>'_blank'))).'<i></i>';
				echo CHtml::link('e办公专区' , $this->createUrl('class/self') , Views::linkClass('class','self',array(),array('target'=>'_blank'))).'<i></i>';
				echo CHtml::link('企业采购' , $this->createUrl('purchase/index') , Views::linkClass('purchase','',array(),array('target'=>'_blank'))).'<i></i>';
				echo '<div><h6>'.CHtml::link('逛一逛' , $this->createUrl('stroll/index') , Views::linkClass('stroll','',array(),array('target'=>'_blank')));
				echo '<b class="t-b"></b></h6><nav>';
				foreach (GlobalGather::getGatherFirst() as $gk => $gt)
					echo CHtml::link($gt , $this->createUrl('stroll/index' , array('g'=>$gk)));
				echo '</nav></div><i></i>';
				echo CHtml::link('积分商城' , $this->createUrl('credits/index') , Views::linkClass('credits','',array(),array('target'=>'_blank'))).'<i></i>';
				echo CHtml::link('二手市场' , $this->createUrl('used/index') , Views::linkClass('used','',array(),array('target'=>'_blank')));
				
				if (!$this->viewsUserID)
					echo '<i></i>'.CHtml::link('我要开店' , $this->createFrontUrl('home/merSign'),array('target'=>'_blank'));
			?>
			</nav>
		</div>
	</section>
	<?php endif; ?>
	<?php echo $content; ?>
	<footer class="footer">
		<div>
			<section>
				<h5>联系我们</h5>
				<p>4000-456-423</p>
				<a class="ico-qq" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=2944775460&site=qq&menu=yes"><i></i></a>
				<a class="ico-sina" href="http://weibo.com/ebangong?is_all=1" target="_wb"><i></i></a>
			</section>
			<?php echo GlobalContent::getHtmlFooterList(); ?>
			<aside>
				<i></i>
				<span>关注我们</span>
			</aside>
		</div>
		<footer>Copyright © 成都东鼎泰兴网络科技有限公司 All Rights Reserved - 蜀ICP备15027731号-2</footer>
	</footer>

	<aside id="go-top-wrap">
		<a class="go-ico-1" rel="external nofollow" href="<?php echo $this->createUrl('cart/index'); ?>"><q></q></a>
		<a class="go-ico-2" rel="external nofollow" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=2944775460&site=qq&menu=yes"><q></q></a>
		<a class="go-top" id="goTop"><s class="tr-t"><i></i><b></b></s></a>
	</aside>
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