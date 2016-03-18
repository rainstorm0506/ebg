<?php
class UserLoginForm extends WebApiForm
{
	public $phone , $password , $type , $vxCode , $smsCode , $password_1 , $password_2 , $reCode , $agree , $com_name , $one_id , $two_id , $three_id;
	public $com_address , $com_num , $com_property , $com_license , $com_license_timeout , $com_tax , $com_org;
	
	private $_identity = null;
	
	//基本验证
	public function rules()
	{
		$rules = array(
			array('type , phone , vxCode , smsCode , password_1 , password_2 , agree', 'required'),
			array('type', 'numerical' , 'min'=>1 , 'max'=>2),
			array('agree', 'numerical' , 'min'=>1 , 'max'=>1),
			array('phone', 'checkPhone'),
			array('vxCode', 'checkVxCode'),
			array('smsCode', 'checkSmsCode'),
			array('password_1 , password_2', 'checkPassword'),
			array('reCode' , 'checkNull'),
		);
		
		if ($this->type == 2)
		{
			$rules = array_merge($rules , array(
				array('com_name , one_id , two_id , three_id , com_address , com_num , com_property , com_license , com_license_timeout , com_tax , com_org', 'required'),
				array('one_id', 'checkEnterprise'),
			));
		}
		return $rules;
	}
	
	public function checkEnterprise()
	{
		if (!trim($this->com_name))
			return $this->addError('com_name' , '请填写公司名称!');
		
		$model = ClassLoad::Only('WAuser');/* @var $model WAuser */
		if ($model->checkCompanyName($this->com_name , 0))
			return $this->addError('com_name' , '此公司已注册!');
		
		if (!GlobalDict::getAreaName((int)$this->three_id , (int)$this->one_id , (int)$this->two_id))
			return $this->addError('com_address' , '请选择正确的省市县!');
		
		if (!trim($this->com_address))
			return $this->addError('com_address' , '请填写公司地址!');
		
		if (!trim($this->com_num))
			return $this->addError('com_num' , '请选择公司人数!');
		
		if (!trim($this->com_property))
			return $this->addError('com_property' , '请选择公司类型!');
		
		if (!trim($this->com_license))
			return $this->addError('com_license' , '请选择营业执照!');
		
		if (!trim($this->com_license_timeout) || strtotime($this->com_license_timeout) <= 0)
			return $this->addError('com_license_timeout' , '请选择执照到期时间!');
		
		if (!trim($this->com_tax))
			return $this->addError('com_tax' , '请选择税务登记证!');
		
		if (!trim($this->com_org))
			return $this->addError('com_org' , '请选择组织机构代码!');
	}
	
	public function checkPassword()
	{
		if (!Verify::isPassword($this->password_1))
			return $this->addError('password_1' , '密码格式错误');
		
		if ($this->password_1 != $this->password_2)
			return $this->addError('password_1' , '两次密码输入不一致');
	}
	
	public function checkSmsCode()
	{
		$session	= Yii::app()->session;
		$smsCode	= empty($session['smsCode']['sign']) ? array() : (array)$session['smsCode']['sign'];
		
		if (!$smsCode)
			return $this->addError('phone' , '你未发送过验证号码!');
		
		if ($smsCode['phone'] != $this->phone)
			return $this->addError('phone' , '你接收短信验证的手机号码错误!');
		
		if ($smsCode['verCode'] != $this->smsCode)
			return $this->addError('smsCode' , '短信验证码错误!');
			
		if (time() > $smsCode['expire'])
			return $this->addError('smsCode' , '短信验证码已过期 , 请重新获取!');
	}
	
	public function checkVxCode()
	{
		$session	= Yii::app()->session;
		$sessCode	= empty($session['imgCode']['sign']) ? array() : $session['imgCode']['sign'];
		
		if (empty($sessCode['code']) || $sessCode['code'] != $this->vxCode)
			$this->addError('vxCode' , '图像验证码错误!');
	}
	
	public function checkPhone()
	{
		if (!Verify::isPhone($this->phone))
			return $this->addError('phone' , '手机号码错误');
		
		//验证注册手机号码
		$model = ClassLoad::Only('WAuser');/* @var $model WAuser */
		if ($model->getUserInfo($this->phone))
			return $this->addError('phone' , '此手机号码已注册!');
	}
	
	//验证登录
	public function verifyLogin()
	{
		$vifery = true;
		if (!Verify::isPhone($this->phone))
		{
			$vifery = false;
			$this->addError('phone' , '手机号码错误');
		}
		
		if (!Verify::isPassword($this->password))
		{
			$vifery = false;
			$this->addError('password' , '密码格式错误');
		}
		
		return $vifery;
	}
	
	//验证登录
	public function login()
	{
		if($this->_identity === null)
		{
			$this->_identity = new WebAppIdentity($this->phone , $this->password);
			$this->_identity->authenticate();
		}
		
		if($this->_identity->errorCode === WebAppIdentity::ERROR_NONE)
		{
			Yii::app()->getUser()->login($this->_identity , 0);
			
			# 行为处理 - 个人/企业登录
			if (UserAction::loginAction($this->getUid() , 1) && $user = $this->getUser())
				GlobalUser::setReflushUser(array('phone'=>$user['phone'] , 'user_type'=>$user['user_type']) , 4);
			
			$model = ClassLoad::Only('WAuser');/* @var $model WAuser */
			$model->userLoginTime($this->getUid());
			return true;
		}else{
			switch ($this->_identity->errorCode)
			{
				case WebAppIdentity::ERROR_USERNAME_INVALID :
					$this->addError('phone' , '找不到用户数据');
				break;
				
				case WebAppIdentity::ERROR_PASSWORD_INVALID :
					$this->addError('phone' , '账号或密码错误');
				break;
				
				case WebAppIdentity::ERROR_NONE :
				default :
					$this->addError('phone' , '未知错误');
				break;
			}
		}
		return false;
	}
}