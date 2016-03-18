<?php
class CartForm extends WebForm
{
	public $userAddressID , $payType , $deliveryWay , $remark , $privilege , $reduction , $changeLock;
	
	public function rules()
	{
		return array(
			array('userAddressID , payType , deliveryWay', 'numerical' , 'allowEmpty'=>false , 'integerOnly'=>true , 'min'=>1),
			array('remark , privilege , reduction , changeLock', 'checkNull'),
		);
	}
}