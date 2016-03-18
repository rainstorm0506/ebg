<?php
class SendVocodeForm extends ApiForm
{
	public $phone , $type , $apt;
	
	//基本验证
	public function rules()
	{
		return array(
			array('phone,type,apt', 'required'),
			array('type', 'numerical' , 'integerOnly'=>true , 'min'=>1 , 'max'=>2),
			array('apt', 'numerical' , 'integerOnly'=>true),
			array('phone', 'checkPhone'),
		);
	}
	
	public function checkPhone()
	{
		if (!Verify::isPhone($this->phone))
			$this->addError('phone' , '手机号码错误');
		
		$model = ClassLoad::Only('Merchants');/* @var $model Merchants */
		$code = $model->checkUserPhone($this->phone);
		
		switch ($this->type)
		{
			case 1:
				if ($code)
					$this->addError('phone' , '此号码已注册!');
			break;
			case 2:
				if (!$code)
					$this->addError('phone' , '此号码没不是我们的商家!');
			break;
			default:
				$this->addError('phone', '未知错误');
		}
	}
}