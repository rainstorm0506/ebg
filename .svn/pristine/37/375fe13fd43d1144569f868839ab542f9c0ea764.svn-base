<?php
class SignForm extends WebForm
{
	public $member = array() , $enterprise = array();
	public $companyName , $companyAddress , $companyNumber , $companyType , $expireTime , $dictOneId , $dictTwoId , $dictThreeId;
	public $com_license = '';
	public $com_tax = '';
	public $com_org = '';
	public $signType;
	
	public $sessKey = '' , $vxcode = '';
	public $phone , $vcode , $password , $confirmPassword , $agree;
	
	//基本验证
	public function rules()
	{
		switch ($this->signType)
		{
			case 'member'			: return array(array('member' , 'checkMember'));
			case 'enterprise'		: return array(array('enterprise' , 'checkEnterprise'));
			
			case 'enterpriseTwo'	: return array(
				array('enterprise' , 'checkEnterpriseTwo'),
				array('companyName,dictOneId,dictTwoId,dictThreeId,companyAddress,companyNumber,companyType,expireTime,com_license,com_tax,com_org' , 'checkNull'),
			);
			
			case 'merchant'			: return array(
				array('phone , vcode , password , confirmPassword , agree' , 'required'),
				array('phone' , 'checkMerchant')
			);
		}
	}
	
	//验证企业注册下一步
	public function checkEnterpriseTwo()
	{
		if (!trim($this->companyName))
			$this->addError('enterprise' , '请填写公司名称!');
		
		$model = ClassLoad::Only('Home');/* @var $model Home */
		if ($model->checkCompanyName($this->companyName , 0))
			$this->addError('enterprise' , '此公司已注册!');
		
		if ((int)$this->dictOneId <= 0 || (int)$this->dictTwoId <= 0 || (int)$this->dictThreeId <= 0 || !trim($this->companyAddress))
			$this->addError('enterprise' , '请填写公司地址!');
		
		if (!trim($this->companyNumber))
			$this->addError('enterprise' , '请选择公司人数!');
		
		if (!trim($this->companyType))
			$this->addError('enterprise' , '请选择公司类型!');
		
		if (!trim($this->com_license))
			$this->addError('enterprise' , '请选择营业执照!');

		if (!trim($this->expireTime))
			$this->addError('enterprise' , '请选择执照到期时间!');
		
		if (!trim($this->com_tax))
			$this->addError('enterprise' , '请选择税务登记证!');
		
		if (!trim($this->com_org))
			$this->addError('enterprise' , '请选择组织机构代码!');
	}
	
	public function checkEnterprise()
	{
		if (($returned = $this->verifySmsCode($this->enterprise['phone'] , (int)$this->enterprise['code'])) !== true)
			$this->addError('enterprise' , $returned[1]);
		
		$model = ClassLoad::Only('Home');/* @var $model Home */
		if ($model->checkUserPhone($this->enterprise['phone'] , false))
			$this->addError('enterprise' , '此号码已注册!');
		
		if (!Verify::isPassword($this->enterprise['password']))
			$this->addError('enterprise' , '密码格式错误');
		
		if ($this->enterprise['password'] != $this->enterprise['confirmPassword'])
			$this->addError('enterprise' , '两次密码输入不一致');
		
		if (empty($this->enterprise['agree']))
			$this->addError('enterprise' , '你不同意注册协议 , 不能注册');
	}
	
	public function checkMember()
	{
		if (($returned = $this->verifySmsCode($this->member['phone'] , (int)$this->member['code'])) !== true)
			$this->addError('member' , $returned[1]);
		
		$model = ClassLoad::Only('Home');/* @var $model Home */
		if ($model->checkUserPhone($this->member['phone'] , false))
			$this->addError('member' , '此号码已注册!');
		
		if (!Verify::isPassword($this->member['password']))
			$this->addError('member' , '密码格式错误');
		
		if ($this->member['password'] != $this->member['confirmPassword'])
			$this->addError('member' , '两次密码输入不一致');
		
		if (empty($this->member['agree']))
			$this->addError('member' , '你不同意注册协议 , 不能注册');
	}
	
	public function checkMerchant()
	{
		if (($res = $this->verifySmsCode($this->phone, $this->vcode)) !== true)
			$this->addError('phone' , $res[1]);
		
		$model = ClassLoad::Only('Home');/* @var $model Home */
		if ($model->checkUserPhone($this->phone , true))
			$this->addError('phone' , '此号码已注册!');
		
		if (!Verify::isPassword($this->password))
			$this->addError('phone' , '密码格式错误');
		
		if ($this->password != $this->confirmPassword)
			$this->addError('phone' , '两次密码输入不一致');
		
		if (empty($this->agree))
			$this->addError('phone' , '你不同意注册协议 , 不能注册');
	}
	
	/**
	 * 验证 短信
	 * @param		string		$phone		手机号码
	 * @param		string		$code		验证码
	 */
	public function verifySmsCode($phone , $code , $second = 300)
	{
		if (!Verify::isPhone($phone))
			return array(1 , '&nbsp;* 手机号码错误');

		if (!$this->sessKey && !empty($this->userType[$this->signType]))
			$this->sessKey = $this->userType[$this->signType];
		
		$session = Yii::app()->session;
		#print_r($session[$this->sessKey]);exit;
		if (empty($session[$this->sessKey]['phone']))
			return array(2 , '&nbsp;* 没有发送验证短信');
	
		if ($session[$this->sessKey]['phone'] != $phone)
			return array(3 , '&nbsp;* 当前手机号码和验证号码不一致');
		
		if (empty($session[$this->sessKey]['verCode']))
			return array(4 , '&nbsp;* 此号码没有发送过验证短信');

		if ($session[$this->sessKey]['verCode'] != $code)
			return array(5 , '&nbsp;* 短信验证码错误');

		if ((time() - $second) > (isset($session[$this->sessKey]['sendTime'])?(int)$session[$this->sessKey]['sendTime']:0))
			return array(6 , '&nbsp;* 验证超时 , 请重新验证!');

		return true;
	}
}