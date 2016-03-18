<div class="navigation">
	<span>
		<input type="text" class="search-keyword" tag="经营范围名称" style="width:240px" 
			value="<?php echo isset($keyword)?$keyword:''; ?>">  
		<?php echo CHtml::link('查询' , $this->createUrl('scope/list' , array('keyword' => '')) , array('class'=>'search-button')); ?>
	</span>
	<ul>
		<li><?php echo CHtml::link('添加经营范围' , $this->createUrl('scope/create') , Views::linkClass('scope' , 'create'))?></li>
		<li><?php echo CHtml::link('全部列表' , $this->createUrl('scope/list') , Views::linkClass('scope' , 'list', array('keyword'=>null))); ?></li>
	</ul>
	<i class="clear"></i>
</div>