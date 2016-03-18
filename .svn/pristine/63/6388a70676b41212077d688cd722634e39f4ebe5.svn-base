<?php
	echo '<div class="navigation"><ul>';
	echo '<span>';
	echo '<input type="text" class="search-keyword" tag="手机号码、昵称、公司名称">  ';
	echo CHtml::link('查询' , $this->createUrl('company/list' , array('keyword' => '')) , array('class'=>'search-button')); 
	echo '</span>';
	echo '<li>' . CHtml::link('添加企业会员', $this -> createUrl('company/create'), Views::linkClass('company', 'create')) . '</li>';
	echo '<li>' . CHtml::link('全部列表', $this -> createUrl('company/list'), Views::linkClass('company', 'list', array('type' => null))) . '</li>';
	echo '</ul><i class="clear"></i></div>';
?>