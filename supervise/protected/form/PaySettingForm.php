<?php
class PaySettingForm extends SForm
{
	public $describe , $rank;
	
	//基本验证
	public function rules()
	{
		return array
		(
			array('rank', 'required'),
			array('describe', 'length' , 'max'=>300),
		);
	}
}