<?php
	$_x = 0;
	foreach ($address as $val)
	{
		$dict = join('|' , array($val['dict_one_id'] , $val['dict_two_id'] , $val['dict_three_id'] , $val['dict_four_id']));
		$_x++;
		echo '<li dict="'.$dict.'" val="'.$val['id'].'" '.($_x>3 ? 'style="display:none"' : '').'>';
		echo '<p>（'.$val['consignee'].'） '.String::privacyOut($val['phone']).'</p><p>';
		echo GlobalDict::getAreaName($val['dict_one_id']);
		echo GlobalDict::getAreaName($val['dict_two_id'] , $val['dict_one_id']);
		echo GlobalDict::getAreaName($val['dict_three_id'] , $val['dict_one_id'] , $val['dict_two_id']);
		echo GlobalDict::getAreaName($val['dict_four_id'] , $val['dict_one_id'] , $val['dict_two_id'] , $val['dict_three_id']);
		echo '</p><p>'.$val['address'].'</p>';
		echo '<div>'.CHtml::link('修改', $this->createUrl('asyn/address',array('id'=>$val['id'])) , array('class'=>'js-mod')).'</div>';
		echo $val['is_default'] ? CHtml::link('默认地址', null , array('class'=>'this')) : CHtml::link('设为默认地址', $this->createUrl('asyn/userSetDeftAddrs',array('id'=>$val['id'])) , array('class'=>'set-default'));
		echo '<i></i></li>';
	}
?>