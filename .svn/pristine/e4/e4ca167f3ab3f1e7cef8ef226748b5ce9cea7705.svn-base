<?php
/**
 * Description of CartForm
 * 购物车表单验证
 * @author Administrator
 */
class CartForm extends WebApiForm{
	public $userAddressID , $payType , $deliveryWay , $remark , $privilege , $reduction , $changeLock;
	
	public function rules()
	{
		return array(
			array('userAddressID , payType , deliveryWay', 'numerical' , 'allowEmpty'=>false , 'integerOnly'=>true , 'min'=>1),
			array('remark , privilege , reduction , changeLock', 'checkNull'),
		);
	}    
}
