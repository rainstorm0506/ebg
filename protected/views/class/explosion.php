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
<main>
<section class="search-condition" id="searchCondition">
		<ul>
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
		<label>
			<input id="showSelf" type="checkbox"<?php echo $self?' checked="checked"':''; ?>>
			<i>只显示e办公自营商品</i>
		</label>
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
					echo ($gs['is_self'] == 1) ? GlobalGoodsTag::displaySelfGoods($gs['tag_id'] , 2) : '';
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

	$('#showSelf').click(function()
	{
		if ($(this).is(':checked'))
		{
			window.location.href = '<?php echo $this->createAppendUrl($this , array('s'=>1)); ?>';
		}else{
			window.location.href = '<?php echo $this->createAppendUrl($this , array() , array('s')); ?>';
		}
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