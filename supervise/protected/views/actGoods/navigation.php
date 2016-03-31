<style type="text/css">
	.navigation form select{border:1px solid #CCC;padding:3px;margin:0 8px 0 0;}
</style>
<div class="navigation">
	<?php if (!empty($navShow)): ?>
	<span>
		<form method="get" action="<?php echo $this->createUrl('list'); ?>">
			<?php
				echo CHtml::dropDownList('shelf',(empty($_GET['shelf'])?0:(int)$_GET['shelf']),CMap::mergeArray(array(''=>' - 上下架状态 - '),$shelfStatus));
			?>
			<input type="text" name="keyword" class="search-keyword" placeholder="支持搜索活动商品名称、活动商品ID、活动商品货号" style="width:320px;margin:0 8px 0 0;" value="<?php echo empty($_GET['keyword'])?'':$_GET['keyword']; ?>">
			<a class="search-button">查询</a>
		</form>
	</span>
	<script>
		$(document).ready(function()
		{
			$('.navigation')
			.on('click' , '.search-button' , function()
			{
				$(this).parent('form').submit();
			});
		});
	</script>
	<?php endif; ?>
	<?php if (!empty($copy)): ?>
		<span>
		<form method="get" action="<?php echo $this->createUrl('goodsList'); ?>">
			<input type="text" name="keyword" class="search-keyword" placeholder="支持搜索全新商品名称、全新商品ID、全新商品货号" style="width:320px;margin:0 8px 0 0;" value="<?php echo empty($_GET['keyword'])?'':$_GET['keyword']; ?>">
			<a class="search-button">查询</a>
		</form>
		</span>
		<script>
			$(document).ready(function()
			{
				$('.navigation')
					.on('click' , '.search-button' , function()
					{
						$(this).parent('form').submit();
					});
			});
		</script>
	<?php endif; ?>
	<ul>
		<li><?php echo CHtml::link('商品 列表' , $this->createUrl('list') , Views::linkClass('actGoods' , 'list')); ?></li>
		<li><?php echo CHtml::link('添加 商品' , $this->createUrl('create') , Views::linkClass('actGoods' , 'create')); ?></li>
		<li><?php echo CHtml::link('复制现有常规商品' , $this->createUrl('goodsList') , Views::linkClass('actGoods' , 'goodsList')); ?></li>
	</ul>
	<i class="clear"></i>
</div>