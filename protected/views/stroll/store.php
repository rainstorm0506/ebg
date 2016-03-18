<header>
	<img src="<?php echo Views::imgShow($store['store_avatar']); ?>" width="34" height="34">
	<h3><?php echo $store['store_name']; ?></h3>
	<a id="close" class="close-btn-2"></a>
</header>
<ul>
	<li>店铺地址：<?php echo $store['store_address']; ?></li>
	<li>经营年份：<?php echo ceil(intval(date('Y')) - $store['bus_start_year']); ?>年</li>
	<li class="ls">保&nbsp;&nbsp;证&nbsp;&nbsp;金：<?php echo number_format($store['mer_ensure_money'],2); ?></li>
	<li class="ico-star-wrap">
		<span>推荐指数：</span>
		<?php
			for ($_gx = 1; $_gx <=5; $_gx++)
				echo $store['store_grade']>=$_gx ? '<i class="current"></i>':'<i></i>';
		?>
	</li>
	<li class="qw">
		<span>QQ 交淡：</span>
		<?php if ($store['store_join_qq']): ?>
			<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $store['store_join_qq']; ?>&site=qq&menu=yes">
				<img border="0" src="http://wpa.qq.com/imgd?IDKEY=ffd71fc001de1c9d8878b8408d4f3b972a1a4f6ea699ebfd&pic=52" alt="点击这里给我发消息" title="点击这里给我发消息">
			</a>
		<?php endif; ?>
		<?php echo CHtml::link('进入店铺' , $this->createUrl('store/index',array('mid'=>$store['uid'])) , array('class'=>'btn-6')); ?>
	</li>
</ul>