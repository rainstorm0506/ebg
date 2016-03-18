<?php
class FindPassForm extends WebForm
{
	public $step , $phone , $vxcode , $vcode , $ut , $sessKey , $password , $confirmPassword;
	
	public function rules()
	{
		switch ($this->step)
		{
			case 'one':
				return array(
					array('phone , vcode , ut', 'required'),
					array('phone', 'checkPhone'),
				);
			break;
			
			case 'two':
				return array(
					array('password , confirmPassword', 'required'),
					array('password', 'checkPassWord'),
				);
			break;
		}
	}
	
	public function checkPassWord()
	{
		if (!Verify::isPassword($this->password))
			$this->addError('password' , '密码格式错误');
		
		if ($this->password != $this->confirmPassword)
			$this->addError('password' , '两次密码输入不一致');
	}
	
	public function checkPhone()
	{
		if (($code = $this->verifySmsCode($this->phone , $this->vcode)) !== true)
			$this->addError('phone' , $code[1]);
		
		$_x = 0; $type = array();
		foreach ($this->userType as $k => $v)
			$type[$k] = (++$_x);
		$ut = empty($type[$this->ut]) ? 0 : (int)$type[$this->ut];
		
		$user = ClassLoad::Only('Home');/* @var $user Home */
		$info = $user->getUserInfo($this->phone , $ut === 3);
		if (empty($info))
		{
			$this->addError('phone' , '查询不到数据!');
		}else{
			if ((int)$info['user_type'] !== $ut)
			{
				$this->addError('phone' , '账号类型错误!');
				return false;
			}
			
			$status = (int)$info['status_id'];
			switch ($ut)
			{
				case 1 : $status = $status===510 ? true : $status; break;
				case 2 : $status = $status===610 ? true : $status; break;
				case 3 : $status = $status===710 ? true : $status; break;
				default: $status = -1;
			}
			
			if ($status !== true)
				$this->addError('phone' , '账号处于非异常状态,不能修改密码!');
		}
	}
	
	/**
	 * 验证 短信
	 * @param		string		$phone		手机号码
	 * @param		string		$code		验证码
	 */
	public function verifySmsCode($phone , $code , $second = 300)
	{
		if (!Verify::isPhone($phone))
			return array(1 , '手机号码错误');
		
		if (!empty($this->userType[$this->ut]))
			$this->sessKey = $this->userType[$this->ut];
		
		$session = Yii::app()->session;
		if (!$this->sessKey)
			return array(-1 , '没有sessKey');
		#print_r($session[$this->sessKey]);exit;
		if (empty($session[$this->sessKey]['phone']))
			return array(2 , '没有发送验证短信');
	
			if ($session[$this->sessKey]['phone'] != $phone)
				return array(3 , '当前手机号码和验证号码不一致');
	
			if (empty($session[$this->sessKey]['verCode']))
				return array(4 , '此号码没有发送过验证短信');
	
			if ($session[$this->sessKey]['verCode'] != $code)
				return array(5 , '短信验证码错误');
	
			if ((time() - $second) > (isset($session[$this->sessKey]['sendTime'])?(int)$session[$this->sessKey]['sendTime']:0))
				return array(6 , '验证超时 , 请重新验证!');
	
			return true;
	}
}