<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="Keywords" content="<?php echo CHtml::encode($this->pageKeywords); ?>">
<meta name="Description" content="<?php echo CHtml::encode($this->pageDescription); ?>">
<link rel='shortcut icon' href='<?php echo Views::imgShow('images/favicon.ico'); ?>' type='image/x-icon' />
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<?php Views::css(array('merchant'));Views::jquery();Views::js('html5'); ?>
<style>
.remmendCode{color: #fff;}
.userCode{text-decoration : underline;color:red}
</style>
</head>
<body>
	<section class="header-nav">
		<div>
			<aside>
				<span><?php echo CHtml::link('<em>e办公首页<b>.</b>电脑城在线</em>' , $this->createFrontUrl('home/index')); ?></span>
				<i>|</i>
				<span>欢迎来到e办公<b>.</b>电脑城在线</span>
			</aside>
			<nav>
				<div class="s-wrap">
					<h5><span><?php echo $this->viewsUserName; ?></span><s class="tr-b"><i></i><b></b></s></h5>
					<dl>
					<?php
						echo '<dd>'.CHtml::link('升级企业用户' , $this->createFrontUrl('member/esEnterprise') , array('rel'=>'nofollow')).'</dd>';
						echo '<dd>'.CHtml::link('我的收藏' , $this->createUrl('myCollection/index') , array('rel'=>'nofollow')).'</dd>';
						echo '<dd>'.CHtml::link('个人中心' , $this->createUrl('home/index') , array('rel'=>'nofollow')).'</dd>';
						echo '<dd>'.CHtml::link('退出' , $this->createFrontUrl('home/logout') , array('rel'=>'nofollow')).'</dd>';
					?>
					</dl>
				</div><i>|</i>
				<span><?php echo CHtml::link('我的订单' , $this->createUrl('order/index') , array('rel'=>'nofollow')); ?></span><i>|</i>
				<span><?php echo CHtml::link('企业采购' , $this->createFrontUrl('purchase/index') , array('rel'=>'nofollow')); ?></span><i>|</i>
				<span><?php echo CHtml::link('e维修' , $this->createFrontUrl('maintain/index') , array('rel'=>'nofollow')); ?></span><i>|</i>
				<span><?php echo CHtml::link('e配送' , $this->createFrontUrl('dispatching/index') , array('rel'=>'nofollow')); ?></span><i>|</i>
				<span><?php echo CHtml::link('服务中心' , $this->createFrontUrl('service/index') , array('rel'=>'nofollow')); ?></span>
			</nav>
		</div>
	</section>
	<section class="header">
		<div>
			<h1 class="logo"><?php echo CHtml::link('<i>E办公</i>' , $this->createFrontUrl('home/index')); ?></h1><i></i>
			<a href="<?php echo $this->createFrontUrl('cart/index'); ?>">
				<div class="shopping-cart-btn">
					<b></b><span>我的购物车</span><i>&gt;</i>
					<?php echo '<em id="cartCount"'.($this->viewsCartCount>0?'':'style="display:none"').'>'.$this->viewsCartCount.'</em>'; ?>
				</div>
			</a>
			<?php
				$this->beginWidget('CActiveForm', array('action'=>$this->createFrontUrl('class/search'),'method'=>'get','htmlOptions'=>array('class'=>'search-wrap')));
				echo CHtml::textField ('keyword' , '' , array('class' => 'tbox38'));
				echo CHtml::submitButton('搜索' , array('name'=>null));
				$this->endWidget();
			?>
		</div>
	</section>
	<section class="nav-wrap nav-fold">
		<div>
			<aside>
				<h2><span>全部商品分类</span><s class="tr-t"><i></i><b></b></s></h2>
				<?php echo GlobalGoodsClass::getHtmlCode(false); ?>
			</aside>
			<nav class="nav-6">
			<?php
				echo CHtml::link('商品首页' , $this->createFrontUrl('class/index'),array('target'=>'_blank')).'<i></i>';
				echo CHtml::link('e办公专区' , $this->createFrontUrl('class/self'),array('target'=>'_blank')).'<i></i>';
				echo CHtml::link('企业采购' , $this->createFrontUrl('purchase/index'),array('target'=>'_blank')).'<i></i>';
				echo '<div><h6>'.CHtml::link('逛一逛' , $this->createFrontUrl('stroll/index'),array('target'=>'_blank'));
				echo '<b class="t-b"></b></h6><nav>';
				foreach (GlobalGather::getGatherFirst() as $gk => $gt)
					echo CHtml::link($gt , $this->createFrontUrl('stroll/index' , array('g'=>$gk)));
				echo '</nav></div><i></i>';
				echo CHtml::link('积分商城' , $this->createFrontUrl('credits/index'),array('target'=>'_blank')).'<i></i>';
				echo CHtml::link('二手市场' , $this->createFrontUrl('used/index'),array('target'=>'_blank'));
			?>
			</nav>
		</div>
	</section>
	<!-- header end -->
	<?php $user = $this->getUser(); ?>
	<section class="merchant-banner">
		<div>
			<section class="center-head-info">
				<figure>
					<div><img src="<?php echo Views::imgShow(empty($user['face']) ? 'images/default-face.jpg' : $user['face']);?>" width="120" height="120"></div>
					<em class="growth-level"><?php echo GlobalUser::getUserLayerName($user['exp'],$user['user_type']); ?></em>
				</figure>

				<article>
					<header><h2><?php echo $user['nickname']; ?></h2><span>（手机号：<?php echo $user['phone']; ?>)</span></header>
					<p>
						<span>我的积分：<?php echo $user['fraction']; ?>分</span>
						<i>|</i>
						<span>我的提现：￥<?php echo $user['money']; ?></span>
						<a class="btn-1 btn-1-8" href="/member/myWithdrawal/showWithdrawal">提现</a>
					</p>
					<p><span>我的推荐码：</span><?php echo CHtml::link($user['user_code'] , $this->createUrl('myRecommend/index'),array('class'=>'userCode'));?></p>
					<div class="growth">
						<span>成长值</span>
						<dl><dd style="width:<?php echo GlobalUser::getExpRatio($user['user_type'] , (int)$user['exp']); ?>%"></dd></dl>
						<i><?php echo $user['exp']; ?></i>
					</div>
				</article>
			</section>

			<aside>
				<?php 
					echo CHtml::link('<i class="l-ico-2"></i><span>收货地址</span>' , $this->createFrontUrl('member/MyAddress'));
					echo CHtml::link('<i class="l-ico-1"></i><span>修改登录密码</span>' , $this->createFrontUrl('member/PersonInfo/showVerity'), array('style'=>'width:85px'));
				?>
			</aside>
		</div>
	</section>
	<main>
		<?php if ($this->showLeftNav): ?>
		<nav class="merchant-subnav">
			<ul id="merchantSubNav">
				<?php 
					$model = ClassLoad::Only('Comment'); /* @var $model Comment */
					$total = $model->getCommnetNum();
				?>
				<li <?php if($this->leftNavType == 'order'):?>class="current"<?php endif;?>>
					<?php echo CHtml::link('<i><b class="c-ico-1"></b></i><span>我的订单</span>' , $this->createFrontUrl('member/order'));?>
				</li>
				<li <?php if($this->leftNavType == 'comment'):?>class="current"<?php endif;?>>
					<?php echo CHtml::link('<i><b class="c-ico-7"></b></i><span>我的评价<q>'.(!empty($total) ? $total : '').'</q></span>' , $this->createFrontUrl('member/comment'));?>
				</li>
				<li <?php if($this->leftNavType == 'collection'):?>class="current"<?php endif;?>>
					<?php echo CHtml::link('<i><b class="c-ico-2"></b></i><span>我的收藏</span>' , $this->createFrontUrl('member/myCollection'));?>
				</li>
				<li <?php if($this->leftNavType == 'personInfo'):?>class="current"<?php endif;?>>
					<?php echo CHtml::link('<i><b class="c-ico-3"></b></i><span>密码及登录信息</span>' , $this->createFrontUrl('member/personInfo'));?>
				</li>
				<li <?php if($this->leftNavType == 'preferential'):?>class="current"<?php endif;?>>
					<?php echo CHtml::link('<i><b class="c-ico-5"></b></i><span>我的优惠券</span>' , $this->createFrontUrl('member/myPreferential'));?>
				</li>
				<li <?php if($this->leftNavType == 'exchange'):?>class="current"<?php endif;?>>
					<?php echo CHtml::link('<i><b class="c-ico-6"></b></i><span>我的兑换</span>' , $this->createFrontUrl('member/myExchange'));?>
				</li>
				<li <?php if($this->leftNavType == 'myAddress'):?>class="current"<?php endif;?>>
					<?php echo CHtml::link('<i><b class="c-ico-9"></b></i><span>我的收货地址</span>' , $this->createFrontUrl('member/myAddress'));?>
				</li>
				<li <?php if($this->leftNavType == 'withdrawal'):?>class="current"<?php endif;?>>
					<?php echo CHtml::link('<i><b class="c-ico-9"></b></i><span>提现记录</span>' , $this->createFrontUrl('member/myWithdrawal'));?>
				</li>
				<li <?php if($this->leftNavType == 'commission'):?>class="current"<?php endif;?>>
					<?php echo CHtml::link('<i><b class="c-ico-11"></b></i><span>推荐提成</span>' , $this->createFrontUrl('member/commission'));?>
				</li>
			</ul>
		</nav>
		<?php endif; ?>
		<?php echo $content; ?>
	</main>

	<footer class="footer">
		<div>
			<section>
				<h5>联系我们</h5>
				<p>4000-456-423</p>
				<a class="ico-qq" rel="external nofollow" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=2944775460&site=qq&menu=yes"><i></i></a>
				<a class="ico-sina" rel="external nofollow" href="http://weibo.com/ebangong?is_all=1" target="_wb"><i></i></a>
			</section>
			<?php echo GlobalContent::getHtmlFooterList(); ?>
			<aside>
				<i></i>
				<span>关注我们</span>
			</aside>
		</div>
		<footer>Copyright © 成都东鼎泰兴网络科技有限公司 All Rights Reserved - 蜀ICP备15027731号-2</footer>
	</footer>
</body>
</html>