<div class="navigation">
	<span>
		<input type="text" class="search-keyword" tag="支持搜索编号、名称、所属分类" style="width:240px" value="<?php echo isset($keyword)?$keyword:''; ?>">
		<?php echo CHtml::link('查询' , $this->createUrl('promotion/list' , array('keyword' => '')) , array('class'=>'search-button')); ?>
	</span>
	<ul>
		<li><?php echo CHtml::link('添加 广告' , $this->createUrl('promotion/create') , Views::linkClass('governor' , 'create')); ?></li>
		<li><?php echo CHtml::link('广告 列表' , $this->createUrl('promotion/list') , Views::linkClass('promotion' , 'list')); ?></li>
	</ul>
	<i class="clear"></i> 
</div>
