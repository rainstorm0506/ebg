<nav class="current-stie">
	<span><?php echo CHtml::link('首页' , $this->createUrl('class/index')); ?></span>
	<i>&gt;</i><span><?php echo $store['store_name']; ?></span>
</nav>
<figure class="banner-img-wrap"><img src="<?php echo Views::imgShow('images/banner/banner-dianpu.png'); ?>"></figure>
<main>
	<section class="store-info-wrap">
		<?php if ($store['store_avatar']): ?>
		<figure><div><img src="<?php echo Views::imgShow($store['store_avatar']); ?>" width="120" height="120"></div></figure>
		<?php endif; ?>
		<div>
			<strong><?php echo $store['store_name']; ?></strong>
			<?php if ($store['store_join_qq']): ?>
			<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $store['store_join_qq']; ?>&site=qq&menu=yes">
				<img border="0" src="http://wpa.qq.com/imgd?IDKEY=ffd71fc001de1c9d8878b8408d4f3b972a1a4f6ea699ebfd&pic=52" alt="点击这里给我发消息" title="点击这里给我发消息">
			</a>
			<?php endif; ?>
			<?php if($store['store_tel']): ?>
			<p class="tel" style="display: inherit; margin-top: 4px;"><i></i><span><?php echo $store['store_tel']; ?></span></p>
			<?php endif; ?>
		</div>
		<ul>
			<li>
				店铺地址：<?php echo $store['store_address']; ?>
				<span class="link"><?php echo CHtml::link('在平面图中查看' , $this->createUrl('stroll/index')); ?></span>
			</li>
			<li>经营年份：<?php echo ceil(intval(date('Y')) - $store['bus_start_year']); ?>年</li>
			<li>保 证 金：<?php echo number_format($store['mer_ensure_money'],2); ?></li>
			<li class="ico-star-wrap">
				<span>推荐指数：</span>
				<?php
					for ($_gx = 1; $_gx <=5; $_gx++)
						echo $store['store_grade']>=$_gx ? '<i class="current"></i>':'<i></i>';
				?>
			</li>
		</ul>
	</section>
	<h3 class="title-line"><i></i>店铺介绍<b></b></h3>
	<article class="editor-wrap"><?php echo $store['store_describe']; ?></article>
	<section class="clearfix">
		<aside class="aisde-store">
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
				<li>
					<h6>保证金：</h6>
					<i class="ico-dep-1"></i>
					<span class="ico-dep-wrap"><i></i><em><?php echo intval($store['mer_ensure_money']); ?>元</em></span>
				</li>
				<li><h6>经营年限：</h6><span><?php echo ceil(intval(date('Y')) - $store['bus_start_year']); ?>年</span></li>
			</ul>
			<nav class="stroe-btn-wrap"><?php $this->widget('CollectWidget', array('id' => $store['uid'] , 'type'=>2)); ?></nav>
			<?php echo GlobalGoodsClass::getGoodsHtml(); ?>
		</aside>
		<a name="list"></a>
		<section class="store-right-wrap">
			<header class="search-nav-bar">
				<nav class="sort-nav">
				<?php
					$_class = $type=='new' ? array('class'=>'current') : array();
					$_pars = array('t'=>'new' , '#'=>'list');
					echo CHtml::link('全新商品' , $this->createAppendUrl($this,$_pars,array('o','by')) , $_class);

					$_class = $type=='used' ? array('class'=>'current') : array();
					$_pars = array('t'=>'used' , '#'=>'list');
					echo CHtml::link('二手商品' , $this->createAppendUrl($this,$_pars,array('o','by')) , $_class);
				?>
				</nav>
				<span style="margin:0 0 0 2em">排序：</span>
				<nav class="sort-nav">
				<?php
					$_class = $order&&$by ? array() : array('class'=>'current');
					echo CHtml::link('综合' , $this->createAppendUrl($this,array('#'=>'list'),array('o','by')) , $_class);

					$_class = array('class'=>($order=='price'&&$by=='desc'?'down':'up').($order=='price'?' current':''));
					$_pars = array('o'=>'price' , 'by'=>$order=='price'&&$by=='asc'?'desc':'asc' , '#'=>'list');
					echo CHtml::link('<span>价格</span><i></i>' , $this->createAppendUrl($this,$_pars) , $_class);

					$_class = array('class'=>($order=='sales'&&$by=='asc'?'up':'down').($order=='sales'?' current':''));
					$_pars = array('o'=>'sales' , 'by'=>$order=='sales'&&$by=='desc'?'asc':'desc' , '#'=>'list');
					echo CHtml::link('<span>销量</span><i></i>' , $this->createAppendUrl($this,$_pars) , $_class);

					$_class = array('class'=>($order=='putaway'&&$by=='asc'?'up':'down').($order=='putaway'?' current':''));
					$_pars = array('o'=>'putaway' , 'by'=>$order=='putaway'&&$by=='desc'?'asc':'desc' , '#'=>'list');
					echo CHtml::link('<span>上架时间</span><i class="sort-arrow"></i>' , $this->createAppendUrl($this,$_pars) , $_class);
				?>
				</nav>
				<form id="goodsQuery" method="get" action="<?php echo $this->createAppendUrl($this , array('w'=>'--keyword--')); ?>">
					<input class="tbox24" type="text" value="<?php echo $keyword; ?>" placeholder="在结果中搜索">
					<input type="submit" value="确定" style="cursor:pointer">
				</form>
				<?php $this->widget('WebSimplePager', array('pages'=>$page , 'anchor'=>'list')); ?>
			</header>
			<section class="pic-list-2-wrap">
				<ul class="pic-list-2">
				<?php if ($type == 'new'): ?>
					<?php foreach ($list as $gs): ?>
					<li>
						<figure>
							<a href="<?php echo $this->createUrl('goods/index' , array('id'=>$gs['id'])); ?>">
								<img src="<?php echo Views::imgShow($gs['cover']); ?>" width="230" height="230">
							</a>
							<?php echo $gs['is_self']==1?'<i>自营</i>':''; ?>
						</figure>
						<p><?php echo GlobalGoods::getPrice($gs['base_price'] , $gs['user_layer_ratio'] , $gs['min_price'] , $gs['max_price']); ?></p>
						<div class="name"><?php echo CHtml::link($gs['title'],$this->createUrl('goods/index' , array('id'=>$gs['id']))); ?></div>
						<footer>
						<?php
							$this->widget('PraiseWidget', array('gid' => $gs['id'] , 'type'=>1 , 'praise'=>$gs['praise']));
							$this->widget('ShareWidget', array('title'=>$gs['title'] , 'pic'=>Views::imgShow($gs['cover']) , 'src'=>$this->createAbsoluteUrl('goods/index' , array('id'=>$gs['id']))));
						?>
						</footer>
					</li>
					<?php endforeach; ?>
				<?php else: ?>
					<?php foreach($list as $v): ?>
					<li>
						<figure>
							<a href="<?php echo $this->createUrl('used/intro',array('id'=>$v['id'])); ?>">
								<img src="<?php echo Views::imgShow($v['cover']); ?>" width="230" height="230">
							</a>
							<?php echo $v['is_self']==1?'<i style="background-color:#d00f2b;">自营</i>':'<i>二手</i>'; ?>
						</figure>
						<p><em>¥ <?php echo $v['sale_price']; ?></em><span><?php echo $v['collect']; ?>人收藏</span></p>
						<div class="name"><a href="<?php echo $this->createUrl('used/intro',array('id'=>$v['id'])); ?>"><?php echo $v['title']; ?></a></div>
						<footer><i></i><span><?php echo $store['mer_name']; ?></span></footer>
					</li>
					<?php endforeach; ?>
				<?php endif; ?>
				</ul>
				<?php $this->widget('WebListPager', array('pages'=>$page , 'anchor'=>'list')); ?>
			</section>
		</section>
	</section>
</main>

<script>
$(document).ready(function()
{
	$('#navList h5,#navList h6').click(function()
	{
		if($(this).parent().hasClass('default'))
			return false;
		if($(this).next().css('display') === 'none'){
			$(this).addClass('current').parent().addClass('current')
		}else{
			$(this).removeClass('current').parent().removeClass('current')
		}
		$(this).next().slideToggle();
	});

	$('#goodsQuery').submit(function()
	{
		var
			keyword = $(this).children(':text:eq(0)').val() || '',
			url = $(this).attr('action');

		if (keyword)
			window.location.href = url.replace('--keyword--',keyword)+ '#list';
		else
			window.location.href = url.replace('&w=--keyword--','')+ '#list';
		return false;
	});
});
</script>