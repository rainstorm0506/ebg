<div class="navigation">
	<span>
		<input type="text" class="search-keyword" tag="支持搜索编号、部门、帐号、真实姓名" style="width:240px" value="<?php echo isset($keyword)?$keyword:''; ?>">
		<?php echo CHtml::link('查询' , $this->createUrl('governor/list' , array('keyword' => '')) , array('class'=>'search-button')); ?>
	</span>
	<ul>
		<li><?php echo CHtml::link('添加 管理员' , $this->createUrl('governor/create') , Views::linkClass('governor' , 'create')); ?></li>
		<li><?php echo CHtml::link('管理员 列表' , $this->createUrl('governor/list') , Views::linkClass('governor' , 'list')); ?></li>
	</ul>
	<i class="clear"></i>
</div>