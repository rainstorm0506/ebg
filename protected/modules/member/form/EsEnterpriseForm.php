<?php
class EsEnterpriseForm  extends WebForm
{
	public $member = array() , $enterprise = array();
	public $companyName , $companyAddress , $companyNumber , $companyType , $expireTime , $dictOneId , $dictTwoId , $dictThreeId;
	public $com_license = '';
	public $com_tax = '';
	public $com_org = '';
	
	public $sessKey = '';
	public $phone , $vcode , $password , $confirmPassword , $agree;
	
	//基本验证
	public function rules()
	{
		return array(
			array('enterprise' , 'checkEnterpriseTwo'),
			array('companyName,dictOneId,dictTwoId,dictThreeId,companyAddress,companyNumber,companyType,expireTime,com_license,com_tax,com_org' , 'checkNull'),
		);
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

	/**
	 * 验证 短信
	 * @param		string		$phone		手机号码
	 * @param		string		$code		验证码
	 */
	public function verifySmsCode($phone , $code , $second = 300)
	{
		if (!Verify::isPhone($phone))
			return array(1 , '手机号码错误');
		
		if (!$this->sessKey && $this->userType[$this->signType])
			$this->sessKey = $this->userType[$this->signType];
		
		$session = Yii::app()->session;
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