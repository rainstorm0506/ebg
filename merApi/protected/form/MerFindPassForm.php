<?php
class MerFindPassForm extends ApiForm
{
	public $phone , $password , $vcode , $apt , $outTime , $sessKey;
	
	//基本验证
	public function rules()
	{
		return array(
			array('phone,password,vcode,apt', 'required'),
			array('apt', 'numerical' , 'integerOnly'=>true),
			array('phone', 'verifySmsCode'),
			array('phone', 'checkPhone'),
			array('password', 'checkPassword'),
		);
	}
	
	public function checkPhone()
	{
		$model = ClassLoad::Only('Merchants');/* @var $model Merchants */
		if (!$model->checkUserPhone($this->phone))
			$this->addError('phone' , '此号码没有注册!');
	}
	
	public function checkPassword()
	{
		if (!Verify::isPassword($this->password))
			$this->addError('password' , '密码格式错误');
	}
	
	/**
	 * 验证 短信
	 * @param		string		$phone		手机号码
	 * @param		string		$code		验证码
	 */
	public function verifySmsCode()
	{
		if (!Verify::isPhone($this->phone))
			$this->addError('phone', '手机号码错误');
	
		$session = Yii::app()->session;
		$session->open();
		if (empty($session[$this->sessKey]['phone']))
			$this->addError('phone', '没有发送验证短信');
	
		if ($session[$this->sessKey]['phone'] != $this->phone)
			$this->addError('phone', '当前手机号码和验证号码不一致');
	
		if (empty($session[$this->sessKey]['verCode']))
			$this->addError('phone', '此号码没有发送过验证短信');
	
		if ($session[$this->sessKey]['verCode'] != $this->vcode)
			$this->addError('phone', '短信验证码错误');
	
		if ((time() - $this->outTime) > (isset($session[$this->sessKey]['sendTime'])?(int)$session[$this->sessKey]['sendTime']:0))
			$this->addError('phone', '验证超时 , 请重新验证!');
	}
}