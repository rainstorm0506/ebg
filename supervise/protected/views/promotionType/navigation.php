<div class="navigation">
	<span>
		<input type="text" class="search-keyword" tag="支持搜索广告类型key、分类名称" style="width:240px" value="<?php echo isset($keyword)?$keyword:''; ?>">
		<?php echo CHtml::link('查询' , $this->createUrl('promotionType/list' , array('keyword' => '')) , array('class'=>'search-button')); ?>
	</span>
	<ul>
		<!-- <li><?php echo CHtml::link('添加 广告分类' , $this->createUrl('promotionType/create') , Views::linkClass('promotionType' , 'create')); ?></li>  -->
		<li><?php echo CHtml::link('广告分类 列表' , $this->createUrl('promotionType/list') , Views::linkClass('promotionType' , 'list')); ?></li>
	</ul>
	<i class="clear"></i> 
</div>
