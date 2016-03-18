<div class="navigation">
	<?php if (!empty($navShow)): ?>
	<span>
		<form method="get" action="<?php echo $this->createUrl('list'); ?>">
			<input type="text" name="keyword" class="search-keyword" placeholder="支持搜索商品名称、商品货号" style="width:320px" value="<?php echo empty($_GET['keyword'])?'':$_GET['keyword']; ?>">
			<a class="search-button">查询</a>
		</form>
	</span>
	<?php endif;?>
	<ul>
		<li><?php echo CHtml::link('添加兑换商品' , $this->createUrl('create') , Views::linkClass('pointsgoods' , 'create')); ?></li>
		<li><?php echo CHtml::link('兑换商品列表' , $this->createUrl('list') , Views::linkClass('pointsgoods' , 'list')); ?></li>
	</ul>
	<i class="clear"></i>
</div>
<script>
	$(document).ready(function() {
		//提交
		$('.navigation').on('click', '.search-button', function () {
			$(this).parent('form').submit();
		});
	})
</script>