<?php
	echo '<div class="navigation"><ul>';
	foreach ($this->userType as $key => $val)
	{
		echo '<li>'.CHtml::link($val , $this->createUrl('userActSet/list' , array('type'=>$key)) , Views::linkClass('userActSet' , 'list' , array('type'=>$key))).'</li>';
	}
	echo '<li>'.CHtml::link('全部列表' , $this->createUrl('userActSet/list') , Views::linkClass('userActSet' , 'list' , array('type'=>null))).'</li>';
	echo '</ul><i class="clear"></i></div>';
?>