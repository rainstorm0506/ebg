<?php
class ConvertForm extends WebForm
{
	public $userAddressID , $deliveryWay , $id , $points;
	
	public function rules()
	{
		return array(
			array('userAddressID , deliveryWay , points , id', 'numerical' , 'allowEmpty'=>false , 'integerOnly'=>true , 'min'=>1),
		);
	}
}