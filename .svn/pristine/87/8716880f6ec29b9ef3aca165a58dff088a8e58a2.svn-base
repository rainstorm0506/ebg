<?php
	Views::css(array('shopping'));
	Yii::app()->getClientScript()->registerCoreScript('layer');
?>
<main class="pay-wrap">
	<!-- 应付金额 -->
	<section class="order-info">
		<div>
			<div>
				<h6>顾客老爷，宝贝已经给您备下了，点击付款即可快马加鞭送到您手上！ 订单号：<?php echo $orders['order_sn']; ?></h6>
				<p>请您在提交订单后<em class="mc">30分钟</em>内完成支付，否则订单会自动取消。</p>
			</div>
			<aside>
				<p>应付金额：<b>¥<?php echo number_format($orders['order_money'],2); ?></b></p>
				<a id="shrinkBtn" class="shrink-btn">订单详情<i class="t-b"></i></a>
			</aside>
		</div>
		<dl>
			<dd>收货地址：<?php echo $orders['cons_address']; ?> 收货人：<?php echo $orders['cons_name'] .' '.$orders['cons_phone']; ?></dd>
			<dd>商品名称：<?php echo String::utf8Truncate(join('，' , $orders['goods']) , 73); ?></dd>
		</dl>
	</section>
	<div class="bank-list" id="bankList">
		<h3>使用银行卡支付</h3>
		<nav>
			<a name="alipay-CCB"><img src="<?php echo Views::imgShow('images/bank/bank-jianshe.png'); ?>"><i></i></a>
			<a name="alipay-ABC"><img src="<?php echo Views::imgShow('images/bank/bank-nongye.png'); ?>"><i></i></a>
			<a name="alipay-BOCB2C"><img src="<?php echo Views::imgShow('images/bank/bank-zhongguo.png'); ?>"><i></i></a>
			<a name="alipay-ICBCB2C"><img src="<?php echo Views::imgShow('images/bank/bank-icbc.png'); ?>"><i></i></a>
			<a name="alipay-POSTGC"><img src="<?php echo Views::imgShow('images/bank/bank-youzheng.png'); ?>"><i></i></a>
			<a name="alipay-COMM"><img src="<?php echo Views::imgShow('images/bank/bank-jiaotong.png'); ?>"><i></i></a>
			<a name="alipay-CITIC"><img src="<?php echo Views::imgShow('images/bank/bank-zhongxin.png'); ?>"><i></i></a>
			<a name="alipay-CMB"><img src="<?php echo Views::imgShow('images/bank/bank-zhaoshang.png'); ?>"><i></i></a>
			<a name="alipay-CIB"><img src="<?php echo Views::imgShow('images/bank/bank-xingye.png'); ?>"><i></i></a>
			<a name="alipay-CMBC"><img src="<?php echo Views::imgShow('images/bank/bank-mingsheng.png'); ?>"><i></i></a>
			<a name="alipay-CDCB"><img src="<?php echo Views::imgShow('images/bank/bank-cd.png'); ?>"><i></i></a>
			<a name="alipay-CDRCB"><img src="<?php echo Views::imgShow('images/bank/bank-cd-nongshang.png'); ?>"><i></i></a>
		</nav>
		<h3>使用第三方支付</h3>
		<nav>
			<a name="alipay"><img src="<?php echo Views::imgShow('images/bank/bank-zhifubao.png'); ?>"><i></i></a>
			<a name="tenpay"><img src="<?php echo Views::imgShow('images/bank/bank-caifutong.png'); ?>"><i></i></a>
		</nav>
	</div>
	<a class="btn-4" id="gotoPay">立即支付</a>
</main>
<form id="gotoPayFrom" method="get" action="<?php echo $this->createUrl('pay/dispose'); ?>"></form>
<script>
var payName = '' , popObj = null;

function closeCancel()
{
	$('#bankList a[name]').removeClass('current');
	payName = '';
	popObj.remove();
}

$(document).ready(function()
{
	$('#shrinkBtn').click(function()
	{
		var $next = $(this).parents('div').next()
		if($next.css('display') === 'none'){
			$(this).addClass('current');
		}else{
			$(this).removeClass('current');
		}
		$next.slideToggle(300)
	})

	$('#bankList a[name]').click(function ()
	{
		$(this).addClass('current').siblings().removeClass('current').parent().siblings('nav').find('a').removeClass('current');
		payName = $(this).attr('name');
	});
	$('a[name="alipay"]').click();

	//支付
	$('#gotoPay').click(function()
	{
		if (!payName)
		{
			layer.msg('请选择支付方式');
			return false;
		}

		var _f = '<input type="hidden" name="pay" value="'+payName+'"><input type="hidden" name="osn" value="<?php echo $orders['order_sn']; ?>">';
		$('#gotoPayFrom').html(_f).attr('target' , '_blank').submit();

		popObj = $('<iframe class="pop-iframe" src="<?php echo $this->createUrl('pay/await' , array('osn'=>$orders['order_sn'])); ?>"></iframe>');
		$('body').append(popObj);
	});
});
</script>