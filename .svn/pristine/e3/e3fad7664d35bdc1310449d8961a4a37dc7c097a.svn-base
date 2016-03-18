<?php
class PurchaseForm extends ApiForm
{
	public $type, $optionType, $pid, $gid, $price, $goodsNum, $uid, $apt, $typename;

	//基本验证
	public function rules()
	{
		switch ($this->optionType)
		{
			case 'list':
				return array
				(
				array('type, apt', 'required'),
				array('type' ,'numerical' , 'integerOnly'=>true , 'min'=>1, 'max'=>3) ,
				array('apt' ,'length', 'min'=>1)
				);
				break;
			case 'detail':
				return array
				(
				array('pid, apt', 'required'),
				array('pid' , 'length', 'min'=>14, 'max'=>16) ,
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
			case 'select':
				return array
				(
				array('gid, apt', 'required'),
				array('apt, gid' ,'length', 'min'=>1)
				);
				break;
			case 'option':
				return array
				(
				array('pid, gid, apt, price, goodsNum', 'required'),
				array('pid' , 'length', 'min'=>14, 'max'=>16) ,
				array('apt, gid, price' ,'length', 'min'=>1)
				);
				break;
		}
	}
}