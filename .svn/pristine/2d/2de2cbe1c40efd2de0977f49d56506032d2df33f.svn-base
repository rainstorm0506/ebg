<?php
class SuppyForm extends ApiForm
{
	public $apt,$id;
	
	public function rules()
	{
		return array(
			array('apt,id',' required'),
			array('apt' , 'numerical', 'integerOnly'=>true, 'min'=>1),
			array('apt','checkLogin')
		);
	}
	
	public function checkLogin()
	{
		if(!$this->getUid())
		{
			$this->addError('apt', '请先登录账号！');
		}
	}
}