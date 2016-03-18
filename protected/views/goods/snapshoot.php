<main>
	<div class="kuai-tit">
		<p>商品ID: <?php echo $shoot['goods_id']; ?></p>
		<h3><?php echo $shoot['goods_title']; ?></h3>
	</div>
	<section class="goods-info-wrap">
		<div class="pic-dis-wrap">
			<figure><img src="<?php echo Views::imgShow($shoot['goods_cover']); ?>"></figure>
		</div>
		<section class="goods-info-content goods-info-content-kuai">
			<ul>
				<li><h6>价<q>空空</q>格</h6><strong><b>￥</b><?php echo number_format($shoot['unit_price'],2); ?></strong></li>
				<?php
					if (!empty($shoot['goods_attrs']))
					{
						foreach ($shoot['goods_attrs'] as $vx)
							echo "<li><h6>{$vx[1]}</h6><p>{$vx[2]}</p></li>";
					}
				?>
				<li><h6>商品类型</h6><p><?php echo $shoot['goods_type'] == 1 ? '全新' : '二手'; ?></p></li>
			</ul>
			<div class="kuai-check">
				<i></i>
				<p>您现在查看的是 <a>交易快照</a></p>
				<p><?php echo CHtml::link('点此查看最新商品详情' , $this->createUrl($shoot['goods_type']==1?'goods/index':'used/intro' , array('id'=>$shoot['goods_id']))); ?></p>
			</div>
		</section>
		<?php if (!empty($store)): ?>
		<aside class="aisde-store aisde-store-r">
			<h2><?php echo $store['store_name']; ?></h2>
			<ul class="store-list">
				<li class="ico-star-wrap-1">
					<h6>评价：</h6>
					<?php
						for ($_gx = 1; $_gx <=5; $_gx++)
							echo $store['store_grade']>=$_gx ? '<i class="current"></i>':'<i></i>';
					?>
				</li>
				<li><h6>店主：</h6><span><?php echo $store['mer_name']; ?></span></li>
				<?php if ($store['store_join_qq']): ?>
				<li>
					<h6>联系：</h6>
					<a class="ico-1-wrap" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $store['store_join_qq']; ?>&site=qq&menu=yes">
						<i></i><span>联系店主</span>
					</a>
				</li>
				<?php endif; ?>
				<li><h6>保证金：</h6><i class="ico-dep-1"></i><span class="ico-dep-wrap"><i></i><em><?php echo (int)$store['mer_ensure_money']; ?>元</em></span></li>
				<li><h6>经营年限：</h6><span><?php echo ceil(intval(date('Y')) - $store['bus_start_year']); ?>年</span></li>
			</ul>
			<nav class="stroe-btn-wrap">
			<?php
				echo CHtml::link('进入店铺' , $this->createUrl('store/index',array('mid'=>$store['uid'])) , array('class'=>'btn-6'));
				$this->widget('CollectWidget', array('id' => $store['uid'] , 'type'=>2));
			?>
			</nav>
		</aside>
		<?php endif; ?>
		<div class="clear"></div>
	</section>
	
	<section id="detailBox">
		<nav class="detail-nav" id="detailNav">
			<a class="current">商品详情</a>
			<?php echo empty($shoot['args']['title']) ? '' : '<a>商品参数</a>'; ?>
		</nav>
		<article class="editor-goods js-box"><?php echo $shoot['content']; ?></article>
		<?php if (!empty($shoot['args']['title'])): ?>
		<div class="js-box dn">
			<table class="tab-param">
				<colgroup>
					<col style="width:20%">
					<col style="width:auto">
				</colgroup>
				<tbody>
				<?php
					foreach ($shoot['args']['title'] as $_k => $_v)
					{
						if (empty($shoot['args']['name'][$_k]) || empty($shoot['args']['value'][$_k]))
							continue;

						echo '<tr><td class="tit" colspan="2">'.$_v.'</td></tr>';
						foreach ($shoot['args']['name'][$_k] as $vk => $vv)
							echo '<tr><td>'.$vv.'</td><td>'.$shoot['args']['value'][$_k][$vk].'</td></tr>';
					}
				?>
				</tbody>
			</table>
			<?php
				if (!empty($goodsJoin))
				{
					$_gjHtml = '<h3 class="goods-tit">最佳组合</h3><ul class="pic-list-1 pic-list-1-2 js-comments">';
					foreach ($goodsJoin as $gs)
					{
						$_gjHtml .=
							'<li><figure><a href="'.$this->createUrl('goods/index' , array('id'=>$gs['id'])).'">' .
							'<img src="'.Views::imgShow($gs['cover']).'" width="184"></a></figure><div class="name">' .
							CHtml::link($gs['title'],$this->createUrl('goods/index' , array('id'=>$gs['id']))).'</div><p>' .
							GlobalGoods::getPrice($gs['base_price'],$gs['user_layer_ratio'],$gs['min_price'],$gs['max_price']).'</p></li>';
					}
					$_gjHtml .= '</ul>';
					echo $_gjHtml;
				}
			?>
		</div>
		<?php endif; ?>
	</section>
</main>
<script>
$(document).ready(function(){
	//切换
	$('#detailNav>a').click(function()
	{
		var _iex = $(this).index() , _dboxChild = $(this).closest('#detailBox').children('.js-box');
		$(this).addClass('current').siblings('.current').removeClass('current');
		_dboxChild.hide();
		_dboxChild.eq(_iex).show();
	});
});
</script>