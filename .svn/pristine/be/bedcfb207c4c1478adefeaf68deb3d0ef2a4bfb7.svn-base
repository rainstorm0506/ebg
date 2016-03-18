<?php
class UserActSetForm extends SForm
{
	public $fraction , $exp , $money , $action_describe;
	
	//基本验证
	public function rules()
	{
		return array
		(
			array('fraction , exp , money', 'required'),
			array('fraction , exp', 'numerical' , 'integerOnly'=>true),
			array('money', 'numerical'),
			array('action_describe' , 'checkNull')
		);
	}
}