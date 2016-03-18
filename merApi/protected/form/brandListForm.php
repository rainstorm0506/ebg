<?php
class brandListForm extends ApiForm{
	public $apt;
	public function rules()
	{
		return array(
			array('apt',' required'),
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