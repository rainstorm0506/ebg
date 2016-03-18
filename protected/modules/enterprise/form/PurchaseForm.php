<?php
class PurchaseForm extends WebForm
{
	public $starttime, $endtime, $is_closed;

	//基本验证
	public function rules()
	{
		return array(
			array('is_closed' , 'numerical' , 'integerOnly'=>true)
		);
	}

}