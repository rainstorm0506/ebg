<div class="navigation">
	<span>
		<input type="text" class="search-keyword" tag="手机号、流水号" style="width:240px" 
			value="<?php echo isset($keyword)?$keyword:''; ?>">  
		<?php echo CHtml::link('查询' , $this->createUrl('userCash/list' , array('keyword' => '')) , array('class'=>'search-button')); ?>
	</span>
	<ul>
		<?php 
		foreach ($this->userType as $key => $val)
		{
			echo '<li>'.CHtml::link($val , $this->createUrl('userCash/list' , array('type'=>$key)) , Views::linkClass('userCash' , 'list' , array('type'=>$key))).'</li>';
		}
		?>
		<li><?php echo CHtml::link('全部列表' , $this->createUrl('userCash/list') , Views::linkClass('userCash' , 'list', array('type'=>null))); ?></li>
	</ul>
	<i class="clear"></i>
</div>