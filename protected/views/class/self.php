<?php
Views::js(array('jquery.search'));

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

<nav class="current-stie">
	<span><?php echo CHtml::link('首页' , $this->createUrl('class/index')); ?></span>
	<i>&gt;</i><span><?php echo ($tier === 0)?'e办公专区':CHtml::link('e办公专区',$this->createUrl('')); ?></span>
	<?php foreach ($chain as $cid): ?>
	<i>&gt;</i><span><?php echo CHtml::link(GlobalGoodsClass::getClassName($cid),$this->createUrl('',array('id'=>$cid))); ?></span>
	<?php endforeach; ?>
	<?php
		echo $id ? ('<i>&gt;</i><span>'.GlobalGoodsClass::getClassName($id).'</span>') : '';
		
		if ($brandID)
			echo '<i>&gt;</i><a href="'.$this->createAppendUrl($this , array() , array('b')).'"><i>品牌：</i><em>'.GlobalBrand::getBrandName($brandID , 1).'</em><b></b></a>';
		if ($priceStart && $priceEnd)
			echo '<i>&gt;</i><a href="'.$this->createAppendUrl($this , array() , array('ps','pe')).'"><i>价格：</i><em>'.($priceStart.' - '.$priceEnd).'</em><b></b></a>';
		echo $html_nav;
		if ($keyword)
			echo '<i>&gt;</i><a href="'.$this->createAppendUrl($this , array() , array('w')).'"><i>关键字：</i><em>'.$keyword.'</em><b></b></a>';
	?>
</nav>

<main>
	<section class="search-condition" id="searchCondition">
		<ul>
			<?php if (!empty($classList)): ?>
			<li>
				<h6>子分类：</h6>
				<aside>
					<dl>
						<?php foreach ($classList as $cid => $ctitle): ?>
						<dd><?php echo CHtml::link($ctitle , $this->createAppendUrl($this , array('id'=>$cid)) , array('rel'=>'nofollow')); ?></dd>
						<?php endforeach; ?>
					</dl>
				</aside>
			</li>
			<?php endif; ?>
			<?php if (!empty($brand) && !$brandID): ?>
				<li class="search-brand">
					<h6>品牌：</h6>
					<aside>
						<dl>
							<?php foreach ($brand as $bid => $bval): ?>
								<dd>
									<a rel="nofollow" href="<?php echo $this->createAppendUrl($this , array('b'=>$bid)); ?>">
										<?php
										echo $bval[2] ? ('<img src="'.Views::imgShow($bval[2]).'">') : '';
										echo '<span>'.GlobalBrand::getBrandName($bid , 1).'</span><i></i>';
										?>
									</a>
								</dd>
							<?php endforeach; ?>
						</dl>
					</aside>
					<a class="btn-more js-btn-more"><em>更多</em><s class="tr-b"><i></i><b></b></s></a>
					<div class="clear"></div>
				</li>
			<?php endif; ?>
			<?php if (!empty($priceGroup) && ($priceStart<1 || $priceEnd<1)): ?>
			<li class="search-price">
				<h6>价格：</h6>
				<aside>
					<dl>
						<?php foreach ($priceGroup as $pgVal): ?>
						<dd><a rel="nofollow" href="<?php echo $this->createAppendUrl($this , array('ps'=>$pgVal['price_start'],'pe'=>$pgVal['price_end'])); ?>"><?php echo $pgVal['price_start'].' - '.$pgVal['price_end']; ?></a></dd>
						<?php endforeach; ?>
					</dl>
				</aside>
				<form id="priceDiy" method="get" action="<?php echo $this->createAppendUrl($this , array('ps'=>'--ps--','pe'=>'--pe--')); ?>">
					<input class="tbox24 int-price" type="text"><i>-</i>
					<input class="tbox24 int-price" type="text">
					<input class="btn-3 btn-3-2" type="submit" value="确定">
				</form>
			</li>
			<?php endif; ?>
			<?php echo $html_attrs; ?>
		</ul>
	</section>
	<?php if ($list): ?>
	<header class="search-nav-bar crbox18-group">
		<span>排序：</span>
		<nav class="sort-nav">
		<?php
			$_rel = array('rel'=>'nofollow');
			$_class = array_merge($order&&$by ? array() : array('class'=>'current') , $_rel);
			echo CHtml::link('综合' , $this->createAppendUrl($this,array(),array('o','by')) , $_class);

			$_class = array_merge(array('class'=>($order=='price'&&$by=='desc'?'down':'up').($order=='price'?' current':'')) , $_rel);
			$_pars = array('o'=>'price' , 'by'=>$order=='price'&&$by=='asc'?'desc':'asc');
			echo CHtml::link('<span>价格</span><i></i>' , $this->createAppendUrl($this,$_pars) , $_class);

			$_class = array_merge(array('class'=>($order=='sales'&&$by=='asc'?'up':'down').($order=='sales'?' current':'')) , $_rel);
			$_pars = array('o'=>'sales' , 'by'=>$order=='sales'&&$by=='desc'?'asc':'desc');
			echo CHtml::link('<span>销量</span><i></i>' , $this->createAppendUrl($this,$_pars) , $_class);

			$_class = array_merge(array('class'=>($order=='putaway'&&$by=='asc'?'up':'down').($order=='putaway'?' current':'')) , $_rel);
			$_pars = array('o'=>'putaway' , 'by'=>$order=='putaway'&&$by=='desc'?'asc':'desc');
			echo CHtml::link('<span>上架时间</span><i class="sort-arrow"></i>' , $this->createAppendUrl($this,$_pars) , $_class);
		?>
		</nav>
		<form id="goodsQuery" method="get" action="<?php echo $this->createAppendUrl($this , array('w'=>'--keyword--')); ?>">
			<input class="tbox24" type="text" placeholder="在结果中搜索">
			<input type="submit" value="确定" style="cursor:pointer">
		</form>
		<?php $this->widget('WebSimplePager', array('pages' => $page)); ?>
	</header>
	
	<section class="pic-list-2-wrap">
		<ul class="pic-list-2">
			<?php foreach ($list as $gs): ?>
			<li>
				<figure>
					<a href="<?php echo $this->createUrl('goods/index' , array('id'=>$gs['id'])); ?>" target="_blank">
						<img src="<?php echo Views::imgShow($gs['cover']); ?>" width="230" height="230">
					</a>
					<?php echo isset($gs['tag_id']) ? GlobalGoodsTag::displayTag($gs['tag_id']) : ''; ?>
				</figure>
				<p>
				<?php
					echo GlobalGoods::getPrice($gs['base_price'] , $gs['user_layer_ratio'] , $gs['min_price'] , $gs['max_price']);
					echo $gs['is_self']==1 ? GlobalGoodsTag::displaySelfGoods($gs['tag_id'] , 2) : '';
				?>
				</p>
				<div class="name"><?php echo CHtml::link($gs['title'],$this->createUrl('goods/index' , array('id'=>$gs['id']))); ?></div>
				<footer>
				<?php
					$this->widget('PraiseWidget', array('gid'=>$gs['id'] , 'type'=>1 , 'praise'=>$gs['praise']));
					$this->widget('ShareWidget', array('title'=>$gs['title'] , 'pic'=>Views::imgShow($gs['cover']) , 'src'=>$this->createAbsoluteUrl('goods/index' , array('id'=>$gs['id']))));
				?>
				</footer>
			</li>
			<?php endforeach; ?>
		</ul>
		<?php $this->widget('WebListPager', array('pages' => $page)); ?>
	</section>
	<?php else: Views::css(array('e-pei')); ?>
	<div class="no-found"><i></i><p>暂时没有符合您需求条件的商品</p></div>
	<?php endif; ?>
</main>

<script>
$(document).ready(function()
{
	$('#priceDiy')
	.on('keyup' , 'input.int-price' , function()
	{
		var re = /[^\d]*/g;
		$(this).val($(this).val().replace(re , ''));
	})
	.on('change' , 'input.int-price' , function()
	{
		if (parseInt($(this).val()||0 , 10) < 1)
			$(this).val(1);
	})
	.submit(function()
	{
		var
			_i1 = parseInt($(this).children(':text:eq(0)').val()||0 , 10),
			_i2 = parseInt($(this).children(':text:eq(1)').val()||0 , 10),
			url = $(this).attr('action');

		(_i1 && _i2) && (window.location.href = url.replace('--ps--',_i1).replace('--pe--',_i2));
		return false;
	});

	$('#goodsQuery').submit(function()
	{
		var
			keyword = $(this).children(':text:eq(0)').val() || '',
			url = $(this).attr('action');

		keyword && (window.location.href = url.replace('--keyword--',keyword));
		return false;
	});
});
</script>