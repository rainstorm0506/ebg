<?php
class PurchaseForm extends WebForm
{
	public $starttime, $endtime, $is_closed, $price,$keyword;

	//基本验证
	public function rules()
	{
		return array(
			array('price','required'),
			array('is_closed' , 'numerical' , 'integerOnly'=>true)
		);
	}
}