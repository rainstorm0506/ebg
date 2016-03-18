<?php
class MyAddressForm extends WebForm
{
	public $is_default,$phone,$address,$dict_four_id,$dict_three_id,$dict_two_id, $dict_one_id,$consignee,$user_id,$id;
	
	//基本验证
	public function rules()
	{
		return array
		(
			array('consignee, phone, dict_two_id, dict_three_id, dict_one_id,address', 'required'),
			array('phone', 'numerical', 'integerOnly'=>true , 'min'=>1),
			array('consignee', 'length', 'max'=>25),
			array('phone' , 'checkPhone'),
		);
	}
	
	public function checkPhone()
	{
		if (!$this->phone)
			$this->addError('phone' , '请选择分类.');
	}
}