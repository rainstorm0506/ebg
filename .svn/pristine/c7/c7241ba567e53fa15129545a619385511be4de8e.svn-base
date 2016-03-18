<?php
	Views::css(array('shopping'));
?>
<main class="shop-wrap">
	<ul class="shop-process">
		<li class="current first"><b></b><em>1</em><i></i><p>我的购物车</p></li>
		<li class="current"><b></b><em>2</em><i></i><p>确认订单信息</p></li>
		<li class="current"><b></b><em>3</em><i></i><p>成功提交订单</p></li>
	</ul>

	<section class="shop-success">
		<i class="ico-7"></i>
		<section>
			<h6>订单已经提交成功</h6>
			<p>
				<strong>货物寄送至：</strong>
				<?php echo $orders['cons_address'] . ' ' . $orders['cons_name']; ?> 收
				<span><?php echo $orders['cons_phone']; ?></span></p>
			<nav>
				<span>您还可以看看：</span>
				<?php
					$_oix = '';
					switch ($this->getUserType())
					{
						case 1: $_oix = 'member'; break;
						case 2: $_oix = 'enterprise'; break;
						case 3: $_oix = 'merchant'; break;
					}
					echo '<span>'.CHtml::link('已买到的商品' , $this->createUrl($_oix.'/order/index')).'</span><i>|</i>';
					echo '<span>'.CHtml::link('订单详情' , $this->createUrl($_oix.'/order/detail',array('oid'=>$orders['order_sn']))).'</span><i>|</i>';
					echo '<span>'.CHtml::link('继续逛逛' , $this->createUrl('class/index')).'</span>';
				?>
			</nav>
		</section>
	</section>
</main>