<div class="navigation">
	<?php if (!empty($navShow)): ?>
		<span>
		<form method="get" action="<?php echo $this->createUrl('list'); ?>">
			<input type="text" name="keyword" class="search-keyword" placeholder="支持搜索电脑城名称、店铺编号" style="width:320px" value="<?php echo empty($_GET['keyword'])?'':$_GET['keyword']; ?>">
			<a class="search-button">搜索</a>
		</form>
	</span>
	<?php endif;?>
	<ul>
		<li><?php echo CHtml::link('添加电脑城' , $this->createUrl('create') , Views::linkClass('gather' , 'create')); ?></li>
		<li><?php echo CHtml::link('添加楼层' , $this->createUrl('storey') , Views::linkClass('gather' , 'storey')); ?></li>
		<li><?php echo CHtml::link('添加店铺编号' , $this->createUrl('store') , Views::linkClass('gather' , 'store')); ?></li>
		<li><?php echo CHtml::link('电脑城列表' , $this->createUrl('list') , Views::linkClass('gather' , 'list')); ?></li>
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