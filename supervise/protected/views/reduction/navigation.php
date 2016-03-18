<div class="navigation">
	<span>
		<input type="text" class="search-keyword" tag="支持搜索编号、活动名称" style="width:240px" value="<?php echo isset($keyword)?$keyword:''; ?>">
		<?php echo CHtml::link('查询' , $this->createUrl('reduction/list' , array('keyword' => '')) , array('class'=>'search-button')); ?>
	</span>
	<ul>
		<li><?php echo CHtml::link('添加 满减活动' , $this->createUrl('reduction/create') , Views::linkClass('governor' , 'create')); ?></li>
		<li><?php echo CHtml::link('满减活动 列表' , $this->createUrl('reduction/list') , Views::linkClass('governor' , 'list')); ?></li>
	</ul>
	<i class="clear"></i>
</div>