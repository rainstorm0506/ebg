<?php
	echo '<div class="navigation"><ul>';
	echo '<li>'.CHtml::link('添加等级设定' , $this->createUrl('userLayerSet/create') , Views::linkClass('userLayerSet' , 'create')).'</li>';
	foreach ($this->userType as $key => $val)
	{
		echo '<li>'.CHtml::link($val , $this->createUrl('userLayerSet/list' , array('type'=>$key)) , Views::linkClass('userLayerSet' , 'list' , array('type'=>$key))).'</li>';
	}
	echo '<li>'.CHtml::link('全部列表' , $this->createUrl('userLayerSet/list') , Views::linkClass('userLayerSet' , 'list' , array('type'=>null))).'</li>';
	echo '</ul><i class="clear"></i></div>';
?>