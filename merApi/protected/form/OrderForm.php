<?php
class OrderForm extends ApiForm
{
	public $status, $optionType, $oid, $uid, $apt, $typename;
	
	//基本验证
	public function rules()
	{
		switch ($this->optionType)
		{
			case 'list':
				return array
				(
					array('status, apt', 'required'),
					array('status' ,'numerical' , 'integerOnly'=>true , 'min'=>0) ,
					array('status' , 'length', 'max'=>3) ,
					array('apt' ,'length', 'min'=>1)
				);
				break;
			case 'detail':
				return array
				(
					array('oid, apt', 'required'),
					array('oid' , 'length', 'min'=>14, 'max'=>16) ,
					array('apt' ,'length', 'min'=>1)
				);
				break;
			case 'express':
				return array
				(
					array('apt', 'required'),
					array('apt' ,'length', 'min'=>1)
				);
				break;
			case 'option':
				return array
				(
					array('oid, apt, typename', 'required'),
					array('oid' , 'length', 'min'=>14, 'max'=>16) ,
					array('apt' ,'length', 'min'=>1)
				);
				break;
		}
	}
}