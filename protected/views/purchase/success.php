	<?php
	Views::css('default');
	Views::jquery();
	?>
	<main class="shop-wrap">
		<!-- 当前位置 -->
		<nav class="current-stie">
			<span><a href="#">首页</a></span><i>&gt;</i>
			<span><?php echo CHtml::link('企业采购 ' , $this->createUrl('index'));?></span><i>&gt;</i>
			<span>发布采购单</span>
		</nav>
		<!-- 发面采购订单成功 -->
		<section class="procurement-success">
			<i class="ico-17"></i>
			<section>
				<h6>您好！您发布的采购单已成功发布，请等待平台审核，通过后我们将短信或电话通知您！</h6>
				<p>您的联系手机号 : <?php echo str_replace(substr($phone, 3,4), '****', $phone)?></p>
				<nav>
					<span>您可以： </span>
					<span><a href="/">返回首页</a></span>
					<span><?php echo CHtml::link('继续购物 ' , $this->createUrl('index'));?></span>
				</nav>
			</section>
		</section>
	</main>
