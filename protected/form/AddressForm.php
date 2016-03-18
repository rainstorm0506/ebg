<?php
class AddressForm extends WebForm
{
	public $consignee,$dictOneId,$dictTwoId,$dictThreeId,$dictFourId,$address,$phone,$is_default;
	
	public function rules()
	{
		return array(
			array('consignee , dictOneId , dictTwoId , address , phone' , 'required'),
			array('phone' , 'checkPhone'),
			array('dictThreeId , dictFourId , is_default' , 'checkNull'),
		);
	}
	
	public function checkPhone()
	{
		if (!Verify::isPhone($this->phone))
			$this->addError('phone' , '手机号码错误');
	}
}