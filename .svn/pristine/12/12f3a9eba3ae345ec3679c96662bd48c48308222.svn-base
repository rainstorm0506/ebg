<?php
	echo '<div class="navigation"><ul>';
	echo '<span>';
	echo '<input type="text" class="search-keyword" tag="手机号码、昵称、商家姓名">  ';
	echo CHtml::link('查询' , $this->createUrl('merchant/list' , array('keyword' => '')) , array('class'=>'search-button')); 
	echo '</span>';
	echo '<li>' . CHtml::link('添加商家会员', $this -> createUrl('merchant/create'), Views::linkClass('merchant', 'create')) . '</li>';
	echo '<li>' . CHtml::link('全部列表', $this -> createUrl('merchant/list'), Views::linkClass('merchant', 'list', array('type' => null))) . '</li>';
	echo '</ul><i class="clear"></i></div>';
?>