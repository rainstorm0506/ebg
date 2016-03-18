<?php
	Views::css(array('shopping'));
?>
<main>
	<section class="pay-fault-wrap">
		<div>
			<i></i>
			<article>
				<h6>支付失败</h6>
				<p>可能支付接口还未返回支付的状态，请刷新此页面查看最新的订单支付状态，或者您也可以联系我们的客户人员。</p>
			</article>
		</div>
		<nav>
		<?php
			echo CHtml::link('刷新-查看最新的订单支付状态',$this->createUrl('pay/finish' , array('osn'=>$osn)) , array('style'=>'margin:0 30px 0 0'));
			echo CHtml::link('重新支付订单',$this->createUrl('pay/index' , array('osn'=>$osn)));
		?>
		</nav>
	</section>
</main>