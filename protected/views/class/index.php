<?php
if (!empty($banner))
{
	Views::js('jquery-generalSlidePlug');
	Yii::app()->getClientScript()->registerScript('class_banner','$("#picList").generalSlidePlug({dir:"fade"});');
	
	$_banner_nav = '<nav id="controllBtn">';
	echo '<section class="index-banner" id="outerbox" style="height:365px"><ul id="picList">';
	$_x = 0;
	foreach ($banner as $val)
	{
		$_banner_nav .= '<a'.($_x==0?' class="current"':'').'></a>';
		echo '<li style="background-image:url('.Views::imgShow($val['image_url']).')"></li>';
		$_x = 1;
	}
	$_banner_nav .= '</nav>';
	echo '</ul><div>'.$_banner_nav.'</div></section>';
}
?>
<ul class="footer-service">
	<li><i class="t-ico-1"></i><article><h2>保障</h2><p>正品保障，可提供发票</p></article></li>
	<li><i class="t-ico-2"></i><article><h2><a href="<?php echo $this->createUrl('dispatching/index'); ?>" target="_blank">急速物流</a></h2><p>三环及南延线，一小时送达</p></article></li>
	<li><i class="t-ico-3"></i><article><h2><a href="<?php echo $this->createUrl('maintain/index'); ?>" target="_blank">无忧售后</a></h2><p>不满意现场退货，实体店支撑</p></article></li>
</ul>

<!-- main -->
<main>
	<!-- 今日新品 -->
	<header class="tit-wrap mb15px">
		<h3>秒杀某东</h3><b></b>
		<?php echo CHtml::link('更多' , $this->createUrl('class/explosion')); ?>
	</header>
	<?php if ($explosion): ?>
	<ul class="pic-list-1 pic-list-1-1">
		<?php foreach ($explosion as $gs): ?>
		<li>
			<figure>
				<a href="<?php echo $this->createUrl('goods/index' , array('id'=>$gs['id'])); ?>">
					<img src="<?php echo Views::imgShow($gs['cover']); ?>" width="150" height="150">
				</a>
				<?php echo $gs['tag_id'];?>
				<?php echo !empty($gs['tag_id']) ? GlobalGoodsTag::displayTag($gs['tag_id']) : ''; ?>
			</figure>
			<div class="name"><?php echo CHtml::link($gs['title'],$this->createUrl('goods/index' , array('id'=>$gs['id']))); ?></div>
			<div class="price">
				<p>
				<?php if($gs['base_price'] > 0):?>
					<?php echo GlobalGoods::getPrice($gs['base_price'] , $gs['user_layer_ratio'], $gs['min_price'] , $gs['max_price']); ?>
				<?php else:?>
					<?php echo '￥'.$gs['min_price']; ?>
				<?php endif;?>
				</p>
				
				<?php echo ($gs['is_self'] == 1) ? GlobalGoodsTag::displaySelfGoods($gs['tag_id'] , 2) : ''; ?>
			</div>
			<footer>
			<?php
				$this->widget('PraiseWidget', array('gid'=>$gs['id'] , 'type'=>1 , 'praise'=>$gs['praise']));
				$this->widget('ShareWidget', array('title'=>$gs['title'] , 'pic'=>Views::imgShow($gs['cover']) , 'src'=>$this->createAbsoluteUrl('goods/index' , array('id'=>$gs['id']))));
			?>
			</footer>
		</li>
		<?php endforeach; ?>
	</ul>
	<?php endif; ?>

	<?php
		$_x = 0;
		foreach ($class as $cid => $cvx)
		{
			$_x++;
			echo "<header class=\"tit-wrap\"><i class=\"ico-f-{$_x}\"></i><h3>{$cvx['title']}</h3>";
			echo CHtml::link('更多' , $this->createUrl('class/list',array('id'=>$cid)))."</header>";
			echo '<section class="index-pic-wrap">';
			
			if (!empty($cvx['ad']))
			{
				echo '<section class="pic-change-wrap">';
				$_img = '<img src="'.Views::imgShow($cvx['ad']['image_url']).'" width="210" height="260">';
				echo (empty($cvx['ad']['link'])) ? $_img : CHtml::link($_img , $cvx['ad']['link']);
				echo '</section>';
			}
			
			if (!empty($cvx['goods']))
			{
				echo '<ul class="pic-list-1 pic-list-1-1">';
				foreach ($cvx['goods'] as $gs)
				{
					echo '<li><figure><a href="'.$this->createUrl('goods/index' , array('id'=>$gs['id'])).'"><img src="'.Views::imgShow($gs['cover']).'" width="230" height="230"></a>';
					echo (isset($gs['tag_id']) ? GlobalGoodsTag::displayTag($gs['tag_id']) : '') . '</figure>';
					echo '<div class="name">'.CHtml::link($gs['title'],$this->createUrl('goods/index' , array('id'=>$gs['id']))).'</div>';
					echo '<div class="price"><p>'.GlobalGoods::getPrice($gs['base_price'] , $gs['user_layer_ratio'] , $gs['min_price'] , $gs['max_price']).'</p>';
					echo (!empty($gs['is_self']) && $gs['is_self'] == 1? GlobalGoodsTag::displaySelfGoods($gs['tag_id'] , 2) : '') . '</div><footer>';
					$this->widget('PraiseWidget', array('gid'=>$gs['id'] , 'type'=>1 , 'praise'=>$gs['praise']));
					$this->widget('ShareWidget', array('title'=>$gs['title'] , 'pic'=>Views::imgShow($gs['cover']) , 'src'=>$this->createAbsoluteUrl('goods/index' , array('id'=>$gs['id']))));
					echo '</footer></li>';
				}
				echo '</ul>';
			}
			echo '</section>';
		}
	?>
</main>