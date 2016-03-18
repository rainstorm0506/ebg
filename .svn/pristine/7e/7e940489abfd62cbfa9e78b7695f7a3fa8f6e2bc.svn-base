<?php

class MerInfoForm extends ApiForm
{
	public $apt;

	public function rules()
	{
		return array(
			array(
				'apt' , 
				'required'
			) , 
			array(
				'apt' , 
				'numerical' , 
				'integerOnly' => true , 
				'min' => 1
			) , 
			array(
				'apt' , 
				'checkLogin'
			)
		);
	}

	public function checkLogin()
	{
		if ( ! $this->getUid())
		{
			$this->addError('apt' , '请登录后操作！');
		}
	}
}