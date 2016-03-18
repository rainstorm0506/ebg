<style type='text/css'>
.public-wraper ul li span:first-child{
	width:150px;
}
</style>
<div class="navigation">
	<span>
		<input type="text" class="search-keyword" tag="支持搜索编号、活动名称" style="width:240px" value="<?php echo isset($keyword)?$keyword:''; ?>">
		<?php echo CHtml::link('查询' , $this->createUrl('privilege/list' , array('keyword' => '')) , array('class'=>'search-button')); ?>
	</span>
	<ul>
		<li><?php echo CHtml::link('添加优惠活动(按订单)' , $this->createUrl('privilege/createOrder') , Views::linkClass('governor' , 'create')); ?></li>
		<li><?php echo CHtml::link('添加优惠活动(按用户)' , $this->createUrl('privilege/createUser') , Views::linkClass('governor' , 'create')); ?></li>
		<li><?php echo CHtml::link('优惠活动列表' , $this->createUrl('privilege/list') , Views::linkClass('governor' , 'list')); ?></li>
	</ul>
	<i class="clear"></i>
</div>